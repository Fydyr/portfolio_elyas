<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/settings.php';

class PricesAdminController extends BaseController
{
    private function checkAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('HTTP/1.1 403 Forbidden');
            echo view('403', ['title' => '403 - Accès interdit']);
            exit;
        }
    }

    public function index(): void
    {
        $this->checkAuth();
        global $pdo;
        $prices = $pdo->query("SELECT * FROM price_items ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
        $commissionStatus = setting('commission_status', 'open');
        $commissionNote   = setting('commission_status_note', '');
        echo $this->view('admin_prices', compact('prices', 'commissionStatus', 'commissionNote'));
    }

    /** Met à jour le statut des commissions affiché sur la page d'accueil. */
    public function saveStatus(): void
    {
        $this->checkAuth();
        global $pdo;

        $status = (($_POST['commission_status'] ?? 'open') === 'closed') ? 'closed' : 'open';
        $note   = trim($_POST['commission_status_note'] ?? '');

        try {
            $stmt = $pdo->prepare(
                "INSERT INTO site_settings (`key`, `value`, `is_markdown`) VALUES (:k, :v, 0)
                 ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)"
            );
            $stmt->execute([':k' => 'commission_status',      ':v' => $status]);
            $stmt->execute([':k' => 'commission_status_note', ':v' => $note]);
            $_SESSION['success'] = 'Statut des commissions mis à jour.';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
        }
        header('Location: ' . url('admin/prices'));
        exit;
    }

    public function edit($id = null): void
    {
        $this->checkAuth();
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':title'       => trim($_POST['title'] ?? ''),
                ':description' => trim($_POST['description'] ?? '') ?: null,
                ':price'       => trim($_POST['price'] ?? '') ?: null,
                ':icon'        => trim($_POST['icon'] ?? '') ?: null,
                ':sort_order'  => (int)($_POST['sort_order'] ?? 0),
                ':visible'     => isset($_POST['visible']) ? 1 : 0,
            ];

            if ($id === null) {
                $sql = "INSERT INTO price_items (title, description, price, icon, sort_order, visible)
                        VALUES (:title, :description, :price, :icon, :sort_order, :visible)";
            } else {
                $sql = "UPDATE price_items SET title=:title, description=:description, price=:price, icon=:icon,
                        sort_order=:sort_order, visible=:visible WHERE id=:id";
                $data[':id'] = (int)$id;
            }

            try {
                $pdo->prepare($sql)->execute($data);
                $priceId = $id === null ? (int)$pdo->lastInsertId() : (int)$id;

                // Lien réciproque avec une catégorie portfolio (1:1)
                $catId = ($_POST['portfolio_category_id'] ?? '') !== '' ? (int)$_POST['portfolio_category_id'] : null;
                $pdo->prepare("UPDATE portfolio_categories SET commission_id = NULL WHERE commission_id = :pid")
                    ->execute([':pid' => $priceId]);
                if ($catId !== null) {
                    $pdo->prepare("UPDATE portfolio_categories SET commission_id = :pid WHERE id = :cid")
                        ->execute([':pid' => $priceId, ':cid' => $catId]);
                }

                $_SESSION['success'] = $id === null ? 'Commission ajoutée.' : 'Commission modifiée.';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
            }
            header('Location: ' . url('admin/prices'));
            exit;
        }

        $price = null;
        if ($id !== null) {
            $stmt = $pdo->prepare("SELECT * FROM price_items WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $price = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$price) {
                $_SESSION['error'] = 'Tarif introuvable.';
                header('Location: ' . url('admin/prices'));
                exit;
            }
        }

        // Catégories portfolio pour le sélecteur + catégorie déjà liée
        $categories = [];
        $linkedCategoryId = null;
        try {
            $categories = $pdo->query("SELECT id, name FROM portfolio_categories ORDER BY sort_order, name")->fetchAll(PDO::FETCH_ASSOC);
            if ($id !== null) {
                $st = $pdo->prepare("SELECT id FROM portfolio_categories WHERE commission_id = :id LIMIT 1");
                $st->execute([':id' => $id]);
                $linkedCategoryId = $st->fetchColumn() ?: null;
            }
        } catch (Exception $e) {}

        echo $this->view('admin_price_edit', compact('price', 'categories', 'linkedCategoryId'));
    }

    public function delete(): void
    {
        $this->checkAuth();
        global $pdo;
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $pdo->prepare("DELETE FROM price_items WHERE id = :id")->execute([':id' => $id]);
            $_SESSION['success'] = 'Tarif supprimé.';
        }
        header('Location: ' . url('admin/prices'));
        exit;
    }
}

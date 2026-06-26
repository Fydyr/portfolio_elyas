<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../includes/db.php';

class SocialAdminController extends BaseController
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
        $links = $pdo->query("SELECT * FROM social_links ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
        echo $this->view('admin_social', compact('links'));
    }

    public function edit($id = null): void
    {
        $this->checkAuth();
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':platform'   => trim($_POST['platform'] ?? '') ?: 'link',
                ':label'      => trim($_POST['label'] ?? ''),
                ':url'        => trim($_POST['url'] ?? ''),
                ':icon'       => trim($_POST['icon'] ?? '') ?: null,
                ':featured'   => isset($_POST['featured']) ? 1 : 0,
                ':sort_order' => (int)($_POST['sort_order'] ?? 0),
                ':visible'    => isset($_POST['visible']) ? 1 : 0,
            ];

            if ($data[':label'] === '' || !filter_var($data[':url'], FILTER_VALIDATE_URL)) {
                $_SESSION['error'] = 'Label et URL valide requis.';
                header('Location: ' . url('admin/social'));
                exit;
            }

            try {
                if ($id === null) {
                    $sql = "INSERT INTO social_links (platform, label, url, icon, featured, sort_order, visible)
                            VALUES (:platform, :label, :url, :icon, :featured, :sort_order, :visible)";
                } else {
                    $sql = "UPDATE social_links SET platform=:platform, label=:label, url=:url, icon=:icon,
                            featured=:featured, sort_order=:sort_order, visible=:visible WHERE id=:id";
                    $data[':id'] = (int)$id;
                }
                $pdo->prepare($sql)->execute($data);
                $_SESSION['success'] = $id === null ? 'Réseau ajouté.' : 'Réseau modifié.';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
            }
            header('Location: ' . url('admin/social'));
            exit;
        }

        $link = null;
        if ($id !== null) {
            $stmt = $pdo->prepare("SELECT * FROM social_links WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $link = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$link) {
                $_SESSION['error'] = 'Réseau introuvable.';
                header('Location: ' . url('admin/social'));
                exit;
            }
        }
        echo $this->view('admin_social_edit', compact('link'));
    }

    public function delete(): void
    {
        $this->checkAuth();
        global $pdo;
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $pdo->prepare("DELETE FROM social_links WHERE id = :id")->execute([':id' => $id]);
            $_SESSION['success'] = 'Réseau supprimé.';
        }
        header('Location: ' . url('admin/social'));
        exit;
    }
}

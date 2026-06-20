<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../includes/db.php';

class PortfolioAdminController extends BaseController
{
    private const UPLOAD_DIR = __DIR__ . '/../assets/img/portfolio/';
    private const IMAGE_EXT = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const VIDEO_EXT = ['mp4', 'webm', 'ogg', 'mov'];
    private const MAX_IMAGE = 12 * 1024 * 1024;          // 12 MB
    private const MAX_VIDEO = 2 * 1024 * 1024 * 1024;    // 2 GB

    private function checkAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('HTTP/1.1 403 Forbidden');
            echo view('403', ['title' => '403 - Accès interdit']);
            exit;
        }
    }

    /** Liste des catégories. */
    public function index(): void
    {
        $this->checkAuth();
        global $pdo;

        $categories = $pdo->query(
            "SELECT c.*,
                    (SELECT COUNT(*) FROM portfolio_images i WHERE i.category_id = c.id) AS image_count,
                    pi.title AS commission_title
             FROM portfolio_categories c
             LEFT JOIN price_items pi ON pi.id = c.commission_id
             ORDER BY c.sort_order, c.name"
        )->fetchAll(PDO::FETCH_ASSOC);

        echo $this->view('admin_portfolio', compact('categories'));
    }

    /** Création / édition d'une catégorie. */
    public function edit($id = null): void
    {
        $this->checkAuth();
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            if ($slug === '') $slug = $this->slugify($name);

            $data = [
                ':name'          => $name,
                ':slug'          => $slug,
                ':description'   => trim($_POST['description'] ?? '') ?: null,
                ':icon'          => trim($_POST['icon'] ?? '') ?: null,
                ':commission_id' => ($_POST['commission_id'] ?? '') !== '' ? (int)$_POST['commission_id'] : null,
                ':sort_order'    => (int)($_POST['sort_order'] ?? 0),
                ':visible'       => isset($_POST['visible']) ? 1 : 0,
            ];

            try {
                if ($id === null) {
                    $sql = "INSERT INTO portfolio_categories (name, slug, description, icon, commission_id, sort_order, visible)
                            VALUES (:name, :slug, :description, :icon, :commission_id, :sort_order, :visible)";
                    $pdo->prepare($sql)->execute($data);
                    $id = (int)$pdo->lastInsertId();
                } else {
                    $sql = "UPDATE portfolio_categories SET name=:name, slug=:slug, description=:description, icon=:icon,
                            commission_id=:commission_id, sort_order=:sort_order, visible=:visible WHERE id=:id";
                    $data[':id'] = (int)$id;
                    $pdo->prepare($sql)->execute($data);
                }

                // Lien 1:1 : une commission ne peut être liée qu'à une seule catégorie
                if ($data[':commission_id'] !== null) {
                    $pdo->prepare("UPDATE portfolio_categories SET commission_id = NULL WHERE commission_id = :cid AND id <> :id")
                        ->execute([':cid' => $data[':commission_id'], ':id' => (int)$id]);
                }

                $_SESSION['success'] = 'Catégorie enregistrée.';
                header('Location: ' . url('admin/portfolio/' . (int)$id . '/images'));
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
                header('Location: ' . url('admin/portfolio'));
                exit;
            }
        }

        $category = null;
        if ($id !== null) {
            $stmt = $pdo->prepare("SELECT * FROM portfolio_categories WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$category) {
                $_SESSION['error'] = 'Catégorie introuvable.';
                header('Location: ' . url('admin/portfolio'));
                exit;
            }
        }
        $commissions = $pdo->query("SELECT id, title FROM price_items ORDER BY sort_order, title")->fetchAll(PDO::FETCH_ASSOC);
        echo $this->view('admin_portfolio_edit', compact('category', 'commissions'));
    }

    public function delete(): void
    {
        $this->checkAuth();
        global $pdo;
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            // Supprimer les fichiers images d'abord
            $imgs = $pdo->prepare("SELECT filename FROM portfolio_images WHERE category_id = :id");
            $imgs->execute([':id' => $id]);
            foreach ($imgs->fetchAll(PDO::FETCH_COLUMN) as $file) {
                $this->deleteFile($file);
            }
            $pdo->prepare("DELETE FROM portfolio_categories WHERE id = :id")->execute([':id' => $id]); // cascade images
            $_SESSION['success'] = 'Catégorie supprimée.';
        }
        header('Location: ' . url('admin/portfolio'));
        exit;
    }

    /** Gestionnaire d'images d'une catégorie. */
    public function images($id): void
    {
        $this->checkAuth();
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM portfolio_categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$category) {
            $_SESSION['error'] = 'Catégorie introuvable.';
            header('Location: ' . url('admin/portfolio'));
            exit;
        }

        $imgStmt = $pdo->prepare("SELECT * FROM portfolio_images WHERE category_id = :id ORDER BY sort_order, id");
        $imgStmt->execute([':id' => $id]);
        $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

        echo $this->view('admin_portfolio_images', compact('category', 'images'));
    }

    /** Upload de plusieurs images d'un coup. */
    public function uploadImages($id): void
    {
        $this->checkAuth();
        global $pdo;
        $id = (int)$id;

        if (!is_dir(self::UPLOAD_DIR)) {
            @mkdir(self::UPLOAD_DIR, 0755, true);
        }

        // sort_order de départ = max actuel + 1
        $start = (int)$pdo->query("SELECT COALESCE(MAX(sort_order), 0) FROM portfolio_images WHERE category_id = " . $id)->fetchColumn();

        $count = 0;
        if (!empty($_FILES['images']['name'][0])) {
            $stmt = $pdo->prepare("INSERT INTO portfolio_images (category_id, filename, sort_order) VALUES (:cid, :file, :ord)");
            $n = count($_FILES['images']['name']);
            for ($i = 0; $i < $n; $i++) {
                if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
                $file = [
                    'name'     => $_FILES['images']['name'][$i],
                    'tmp_name' => $_FILES['images']['tmp_name'][$i],
                    'size'     => $_FILES['images']['size'][$i],
                ];
                $saved = $this->saveUpload($file);
                if ($saved) {
                    $stmt->execute([':cid' => $id, ':file' => $saved, ':ord' => ++$start]);
                    $count++;
                }
            }
        }

        $_SESSION[$count ? 'success' : 'error'] = $count ? "$count image(s) ajoutée(s)." : "Aucune image valide importée.";
        header('Location: ' . url('admin/portfolio/' . $id . '/images'));
        exit;
    }

    /** Ajoute un média par lien (YouTube ou lien vidéo direct). */
    public function addEmbed($id): void
    {
        $this->checkAuth();
        global $pdo;
        $id = (int)$id;

        $url     = trim($_POST['embed_url'] ?? '');
        $caption = trim($_POST['caption'] ?? '') ?: null;

        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            $_SESSION['error'] = 'Lien invalide.';
            header('Location: ' . url('admin/portfolio/' . $id . '/images'));
            exit;
        }

        $start = (int)$pdo->query("SELECT COALESCE(MAX(sort_order), 0) FROM portfolio_images WHERE category_id = " . $id)->fetchColumn();
        $pdo->prepare("INSERT INTO portfolio_images (category_id, embed_url, caption, sort_order) VALUES (:cid, :url, :cap, :ord)")
            ->execute([':cid' => $id, ':url' => $url, ':cap' => $caption, ':ord' => $start + 1]);

        $_SESSION['success'] = 'Vidéo (lien) ajoutée.';
        header('Location: ' . url('admin/portfolio/' . $id . '/images'));
        exit;
    }

    /** Met à jour la légende et l'ordre d'une image. */
    public function updateImage(): void
    {
        $this->checkAuth();
        global $pdo;
        $imgId = (int)($_POST['image_id'] ?? 0);
        $catId = (int)($_POST['category_id'] ?? 0);
        if ($imgId > 0) {
            $pdo->prepare("UPDATE portfolio_images SET caption = :cap, sort_order = :ord WHERE id = :id")
                ->execute([
                    ':cap' => trim($_POST['caption'] ?? '') ?: null,
                    ':ord' => (int)($_POST['sort_order'] ?? 0),
                    ':id'  => $imgId,
                ]);
            $_SESSION['success'] = 'Image mise à jour.';
        }
        header('Location: ' . url('admin/portfolio/' . $catId . '/images'));
        exit;
    }

    public function deleteImage(): void
    {
        $this->checkAuth();
        global $pdo;
        $imgId = (int)($_POST['image_id'] ?? 0);
        $catId = (int)($_POST['category_id'] ?? 0);
        if ($imgId > 0) {
            $stmt = $pdo->prepare("SELECT filename FROM portfolio_images WHERE id = :id");
            $stmt->execute([':id' => $imgId]);
            $file = $stmt->fetchColumn();
            if ($file) {
                // Ne supprime le fichier que si aucune autre ligne ne l'utilise
                $this->deleteFile($file);
                // Si c'était la cover, on la retire
                $pdo->prepare("UPDATE portfolio_categories SET cover_image = NULL WHERE id = :cid AND cover_image = :f")
                    ->execute([':cid' => $catId, ':f' => $file]);
            }
            $pdo->prepare("DELETE FROM portfolio_images WHERE id = :id")->execute([':id' => $imgId]);
            $_SESSION['success'] = 'Image supprimée.';
        }
        header('Location: ' . url('admin/portfolio/' . $catId . '/images'));
        exit;
    }

    public function setCover(): void
    {
        $this->checkAuth();
        global $pdo;
        $catId = (int)($_POST['category_id'] ?? 0);
        $file  = trim($_POST['filename'] ?? '');
        if ($catId > 0 && $file !== '') {
            $pdo->prepare("UPDATE portfolio_categories SET cover_image = :f WHERE id = :id")
                ->execute([':f' => $file, ':id' => $catId]);
            $_SESSION['success'] = 'Image de couverture définie.';
        }
        header('Location: ' . url('admin/portfolio/' . $catId . '/images'));
        exit;
    }

    // ===== Helpers =====

    private function saveUpload(array $file): ?string
    {
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $isImage = in_array($ext, self::IMAGE_EXT, true);
        $isVideo = in_array($ext, self::VIDEO_EXT, true);
        if (!$isImage && !$isVideo) return null;

        $size = (int)($file['size'] ?? 0);
        if ($isImage && $size > self::MAX_IMAGE) return null;
        if ($isVideo && $size > self::MAX_VIDEO) return null;

        // Pour les images, on valide que c'est bien une image
        if ($isImage && function_exists('getimagesize') && getimagesize($file['tmp_name']) === false) return null;

        $fileName = uniqid('pf_', true) . '.' . $ext;
        $dest = self::UPLOAD_DIR . $fileName;
        return move_uploaded_file($file['tmp_name'], $dest) ? $fileName : null;
    }

    /** True si le fichier est une vidéo (d'après l'extension). */
    public static function isVideoFile(string $filename): bool
    {
        return in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), self::VIDEO_EXT, true);
    }

    private function deleteFile(string $file): void
    {
        global $pdo;
        // Sécurité : ne pas supprimer si le fichier est encore référencé ailleurs
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM portfolio_images WHERE filename = :f");
        $stmt->execute([':f' => $file]);
        if ((int)$stmt->fetchColumn() <= 1) {
            $path = self::UPLOAD_DIR . basename($file);
            if (is_file($path)) @unlink($path);
        }
    }

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text ?: 'category-' . substr(uniqid(), -5);
    }
}

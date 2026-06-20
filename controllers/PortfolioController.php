<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../includes/db.php';

class PortfolioController extends BaseController
{
    /** Grille des catégories visibles (page Portfolio). */
    public function index(): void
    {
        global $pdo;

        $categories = $pdo->query(
            "SELECT c.*,
                    (SELECT COUNT(*) FROM portfolio_images i WHERE i.category_id = c.id) AS image_count,
                    COALESCE(c.cover_image,
                             (SELECT i.filename FROM portfolio_images i WHERE i.category_id = c.id AND i.filename IS NOT NULL ORDER BY i.sort_order, i.id LIMIT 1)
                    ) AS cover_file,
                    (SELECT i.embed_url FROM portfolio_images i WHERE i.category_id = c.id ORDER BY i.sort_order, i.id LIMIT 1) AS cover_embed
             FROM portfolio_categories c
             WHERE c.visible = 1
             ORDER BY c.sort_order, c.name"
        )->fetchAll(\PDO::FETCH_ASSOC);

        echo $this->view('portfolio', compact('categories'));
    }

    /** Galerie d'une catégorie (toutes ses images). */
    public function category(string $slug): void
    {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM portfolio_categories WHERE slug = :slug AND visible = 1");
        $stmt->execute([':slug' => $slug]);
        $category = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$category) {
            header('HTTP/1.1 404 Not Found');
            echo $this->view('404', ['title' => '404 - Not found']);
            return;
        }

        $imgStmt = $pdo->prepare("SELECT * FROM portfolio_images WHERE category_id = :id ORDER BY sort_order, id");
        $imgStmt->execute([':id' => $category['id']]);
        $images = $imgStmt->fetchAll(\PDO::FETCH_ASSOC);

        // Commission liée (pour le bouton "Commission this")
        $commission = null;
        if (!empty($category['commission_id'])) {
            $cStmt = $pdo->prepare("SELECT id, title FROM price_items WHERE id = :id");
            $cStmt->execute([':id' => $category['commission_id']]);
            $commission = $cStmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        }

        echo $this->view('portfolioCategory', compact('category', 'images', 'commission'));
    }
}

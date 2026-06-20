<?php

require_once 'BaseController.php';

class AdminController extends BaseController
{
    // ===== Dashboard d'administration =====
    public function admin()
    {
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin']) == 1) {
            header('HTTP/1.1 403 Forbidden');
            echo view('403', ['title' => '403 - Accès interdit']);
            exit;
        }

        include_once 'includes/db.php';
        global $pdo;

        $stats = [
            'categories_total' => 0,
            'images_total'     => 0,
            'skills_total'     => 0,
            'skill_cats_total' => 0,
            'prices_total'     => 0,
            'users_total'      => 0,
            'visitors'         => 0,
            'last_migration'   => null,
        ];

        try { $stats['categories_total'] = (int)$pdo->query("SELECT COUNT(*) FROM portfolio_categories")->fetchColumn(); } catch (Exception $e) {}
        try { $stats['images_total']     = (int)$pdo->query("SELECT COUNT(*) FROM portfolio_images")->fetchColumn(); } catch (Exception $e) {}
        try { $stats['skills_total']     = (int)$pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn(); } catch (Exception $e) {}
        try { $stats['skill_cats_total'] = (int)$pdo->query("SELECT COUNT(*) FROM skill_categories")->fetchColumn(); } catch (Exception $e) {}
        try { $stats['prices_total']     = (int)$pdo->query("SELECT COUNT(*) FROM price_items")->fetchColumn(); } catch (Exception $e) {}
        try { $stats['users_total']      = (int)$pdo->query("SELECT COUNT(*) FROM user")->fetchColumn(); } catch (Exception $e) {}

        try {
            $row = $pdo->query("SELECT filename, applied_at FROM schema_migrations ORDER BY applied_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            if ($row) $stats['last_migration'] = $row;
        } catch (Exception $e) {}

        // Compteur de visites (fichier texte existant)
        $counter = __DIR__ . '/../assets/docs/compteur.txt';
        if (is_file($counter)) {
            $stats['visitors'] = (int)file_get_contents($counter);
        }

        // Dernières catégories portfolio
        $latestCategories = [];
        try {
            $stmt = $pdo->query(
                "SELECT c.id, c.name, c.visible,
                        (SELECT COUNT(*) FROM portfolio_images i WHERE i.category_id = c.id) AS image_count
                 FROM portfolio_categories c ORDER BY c.id DESC LIMIT 5"
            );
            $latestCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {}

        // Historique des visites sur 30 jours (jours sans visite = 0)
        $visitsByDay = [];
        $visits7d    = 0;
        $visits30d   = 0;
        try {
            $rows = $pdo->query(
                "SELECT day, count FROM daily_visits
                 WHERE day >= (CURDATE() - INTERVAL 29 DAY)
                 ORDER BY day ASC"
            )->fetchAll(PDO::FETCH_ASSOC);
            $byDay = [];
            foreach ($rows as $r) {
                $byDay[$r['day']] = (int)$r['count'];
            }
            for ($i = 29; $i >= 0; $i--) {
                $day = date('Y-m-d', strtotime("-$i days"));
                $c   = $byDay[$day] ?? 0;
                $visitsByDay[$day] = $c;
                $visits30d += $c;
                if ($i < 7) $visits7d += $c;
            }
        } catch (Exception $e) {}

        echo $this->view('admin', compact('stats', 'latestCategories', 'visitsByDay', 'visits7d', 'visits30d'));
    }
}

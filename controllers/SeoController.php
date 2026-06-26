<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/meta-config.php';

class SeoController extends BaseController
{
    /**
     * /sitemap.xml - sitemap dynamique listant les pages publiques + tous les projets visibles.
     */
    public function sitemap(): void
    {
        global $pdo;

        $base = siteBaseUrl();
        $today = date('Y-m-d');

        $urls = [
            ['loc' => $base . '/',               'priority' => '1.0', 'changefreq' => 'weekly', 'lastmod' => $today],
            ['loc' => $base . '/projects',       'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => $today],
            ['loc' => $base . '/links',          'priority' => '0.7', 'changefreq' => 'monthly','lastmod' => $today],
            ['loc' => $base . '/price',          'priority' => '0.6', 'changefreq' => 'monthly','lastmod' => $today],
            ['loc' => $base . '/legal-mention',  'priority' => '0.3', 'changefreq' => 'yearly', 'lastmod' => $today],
        ];

        try {
            $stmt = $pdo->query("SELECT slug FROM portfolio_categories WHERE visible = 1 ORDER BY sort_order, id");
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $c) {
                $urls[] = [
                    'loc'        => $base . '/projects/' . rawurlencode($c['slug']),
                    'priority'   => '0.8',
                    'changefreq' => 'monthly',
                    'lastmod'    => $today,
                ];
            }
        } catch (PDOException $e) {
            // table absente -> on émet juste les pages statiques
        }

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($u['loc'], ENT_QUOTES | ENT_XML1, 'UTF-8') . "</loc>\n";
            echo "    <lastmod>" . $u['lastmod'] . "</lastmod>\n";
            echo "    <changefreq>" . $u['changefreq'] . "</changefreq>\n";
            echo "    <priority>" . $u['priority'] . "</priority>\n";
            echo "  </url>\n";
        }
        echo '</urlset>' . "\n";
    }

    /**
     * /robots.txt - autorise tout sauf /admin* et /login, pointe vers le sitemap.
     */
    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        $base = siteBaseUrl();
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /login\n";
        echo "Disallow: /logout\n";
        echo "\n";
        echo "Sitemap: " . $base . "/sitemap.xml\n";
    }
}

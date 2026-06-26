<?php
/**
 * Meta tags SEO / Open Graph / Twitter / JSON-LD.
 *
 * - getPageMeta($pageName, $customMeta) : retourne le tableau de meta pour une page
 * - renderMetaTags($meta) : émet les <meta> dans <head>
 * - renderJsonLd($pageName, $context) : émet le bloc Schema.org JSON-LD
 *
 * Les pages dynamiques (projet, etc.) peuvent passer un $customMeta avec
 * une image, un type article, etc., qui surchargent les valeurs par défaut.
 */

const SITE_NAME       = 'Fynt — VTuber, Artist & Live2D Rigger';
const SITE_AUTHOR     = 'Fynt';
const SITE_TWITTER    = '@_FoxBee';
const SITE_LOCALE     = 'en_US';
const SITE_DEFAULT_OG = '/assets/img/img_logo.png';

/**
 * Calcule l'URL absolue du site (sans path).
 */
function siteBaseUrl(): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
}

/**
 * Convertit une URL relative ou un chemin en URL absolue.
 */
function absoluteUrl(string $path): string
{
    if (strpos($path, 'http') === 0) return $path;
    if ($path === '' || $path[0] !== '/') $path = '/' . $path;
    return siteBaseUrl() . $path;
}

/**
 * Catalogue des meta par page. Edite ici pour ajuster les descriptions/titres.
 */
function pagesMetaCatalog(): array
{
    return [
        'index' => [
            'title'       => 'Fynt — VTuber, Animator, Artist & Live2D Rigger',
            'description' => "Portfolio of Fynt (Fyntsu): VTuber artist specialising in 3D modelling, Live2D rigging, animation, art and emotes for streamers and creators. Commissions open.",
            'type'        => 'profile',
        ],
        'home' => [
            'title'       => 'Fynt — VTuber, Animator, Artist & Live2D Rigger',
            'description' => "Portfolio of Fynt (Fyntsu): VTuber artist specialising in 3D modelling, Live2D rigging, animation, art and emotes for streamers and creators.",
            'type'        => 'profile',
        ],
        'projects' => [
            'title'       => 'Portfolio - Fynt',
            'description' => "Selected work by Fynt: 3D VTuber models, Live2D rigs, animations, illustrations and emotes.",
            'type'        => 'website',
        ],
        'about' => [
            'title'       => 'About - Fynt',
            'description' => "Fynt (Fyntsu) is a VTuber artist working across 3D modelling, Live2D rigging, animation and illustration. Learn more and get in touch.",
            'type'        => 'profile',
        ],
        'links' => [
            'title'       => 'Links - Fynt',
            'description' => "All of Fynt's links in one place: commissions (VGen), Discord, and every social platform.",
            'type'        => 'website',
        ],
        'legal-mention' => [
            'title'       => 'Legal notice - Fynt',
            'description' => "Legal notice and terms for Fynt's portfolio: editor, intellectual property and data protection.",
            'type'        => 'website',
        ],
        'price' => [
            'title'       => 'Commissions - Fynt',
            'description' => "Commission prices for Fynt: 3D modelling, animation, Live2D rigging, PSD making, art and emotes. Open via VGen.",
            'type'        => 'website',
        ],
        'login' => [
            'title'       => 'Connexion - ' . SITE_NAME,
            'description' => "Espace d'administration du portfolio.",
            'type'        => 'website',
            'noindex'     => true,
        ],
        'admin' => [
            'title'       => 'Administration - ' . SITE_NAME,
            'description' => "Panneau d'administration.",
            'type'        => 'website',
            'noindex'     => true,
        ],
    ];
}

/**
 * Retourne les meta finaux pour une page, fusionnés avec d'éventuels customs (projet, etc.).
 */
function getPageMeta(string $page = 'index', array $custom = []): array
{
    $defaults = [
        'title'        => SITE_NAME,
        'description'  => SITE_NAME . " - VTuber artist & Live2D rigger.",
        'image'        => SITE_DEFAULT_OG,
        'type'         => 'website',
        'twitter_card' => 'summary_large_image',
        'noindex'      => false,
    ];

    $catalog = pagesMetaCatalog();
    $base = $catalog[$page] ?? [];
    $meta = array_merge($defaults, $base, $custom);

    // URL canonique = URL courante (sans query string)
    if (empty($meta['url'])) {
        $path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
        $meta['url'] = siteBaseUrl() . $path;
    }

    // image -> URL absolue
    $meta['image'] = absoluteUrl($meta['image']);

    return $meta;
}

/**
 * Conserve la signature historique createCustomMeta().
 */
function createCustomMeta(string $title, string $description, ?string $image = null, string $type = 'website'): array
{
    $m = ['title' => $title, 'description' => $description, 'type' => $type];
    if ($image !== null) $m['image'] = $image;
    return $m;
}

/**
 * Émet les balises <meta>.
 */
function renderMetaTags(array $meta): void
{
    $esc = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

    echo '<meta name="description" content="' . $esc($meta['description']) . '">' . "\n";
    echo '<meta name="author" content="' . $esc(SITE_AUTHOR) . '">' . "\n";
    echo '<link rel="canonical" href="' . $esc($meta['url']) . '">' . "\n";

    if (!empty($meta['noindex'])) {
        echo '<meta name="robots" content="noindex,nofollow">' . "\n";
    } else {
        echo '<meta name="robots" content="index,follow">' . "\n";
    }

    // Open Graph
    echo '<meta property="og:site_name" content="' . $esc(SITE_NAME) . '">' . "\n";
    echo '<meta property="og:locale" content="' . $esc(SITE_LOCALE) . '">' . "\n";
    echo '<meta property="og:type" content="' . $esc($meta['type']) . '">' . "\n";
    echo '<meta property="og:title" content="' . $esc($meta['title']) . '">' . "\n";
    echo '<meta property="og:description" content="' . $esc($meta['description']) . '">' . "\n";
    echo '<meta property="og:url" content="' . $esc($meta['url']) . '">' . "\n";
    echo '<meta property="og:image" content="' . $esc($meta['image']) . '">' . "\n";
    echo '<meta property="og:image:alt" content="' . $esc($meta['title']) . '">' . "\n";

    if (!empty($meta['image_width']) && !empty($meta['image_height'])) {
        echo '<meta property="og:image:width" content="' . $esc($meta['image_width']) . '">' . "\n";
        echo '<meta property="og:image:height" content="' . $esc($meta['image_height']) . '">' . "\n";
    }

    if (!empty($meta['article_published_time'])) {
        echo '<meta property="article:published_time" content="' . $esc($meta['article_published_time']) . '">' . "\n";
    }
    if (!empty($meta['article_author'])) {
        echo '<meta property="article:author" content="' . $esc($meta['article_author']) . '">' . "\n";
    }

    // Twitter
    echo '<meta name="twitter:card" content="' . $esc($meta['twitter_card']) . '">' . "\n";
    echo '<meta name="twitter:site" content="' . $esc(SITE_TWITTER) . '">' . "\n";
    echo '<meta name="twitter:creator" content="' . $esc(SITE_TWITTER) . '">' . "\n";
    echo '<meta name="twitter:title" content="' . $esc($meta['title']) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . $esc($meta['description']) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . $esc($meta['image']) . '">' . "\n";
    echo '<meta name="twitter:image:alt" content="' . $esc($meta['title']) . '">' . "\n";
}

/**
 * Émet le bloc JSON-LD Schema.org en fonction de la page.
 *  - Person sur l'accueil
 *  - CreativeWork sur un détail de projet (via $context['project'])
 *  - BreadcrumbList sur projects/contact/legal/price
 */
function renderJsonLd(string $page, array $context = []): void
{
    $base = siteBaseUrl();
    $blocks = [];

    if (in_array($page, ['index', 'home'], true)) {
        $blocks[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Person',
            'name'        => SITE_AUTHOR,
            'alternateName' => 'Fyntsu',
            'url'         => $base . '/',
            'image'       => absoluteUrl(SITE_DEFAULT_OG),
            'jobTitle'    => 'VTuber Artist & Live2D Rigger',
            'description' => "VTuber artist specialising in 3D modelling, Live2D rigging, animation and illustration.",
            'sameAs'      => [
                'https://vgen.co/fyfyntt',
                'https://twitter.com/_FoxBee',
                'https://www.instagram.com/fyfyntt/',
                'https://www.youtube.com/@Fynt_Elyas',
                'https://www.twitch.tv/fyfyntt',
                'https://www.artstation.com/fyntsu/profile',
                'https://ko-fi.com/fyntsu',
            ],
            'knowsAbout' => $context['skills_names'] ?? [],
        ];
        $blocks[] = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => SITE_NAME,
            'url'      => $base . '/',
            'inLanguage' => 'en',
        ];
    }

    if ($page === 'project-detail' && !empty($context['project'])) {
        $p = $context['project'];
        $img = !empty($p['img1']) ? absoluteUrl('/assets/img/projects/' . $p['img1']) : absoluteUrl(SITE_DEFAULT_OG);
        $blocks[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'CreativeWork',
            'name'        => $p['title'] ?? '',
            'description' => strip_tags($p['description'] ?? ''),
            'image'       => $img,
            'url'         => $base . '/project-detail/' . ($p['id'] ?? ''),
            'author'      => ['@type' => 'Person', 'name' => SITE_AUTHOR],
            'keywords'    => $p['languages'] ?? null,
        ];
    }

    // Fil d'Ariane pour les pages secondaires
    $breadcrumbs = [
        'projects'      => [['Home', '/'], ['Portfolio', '/projects']],
        'links'         => [['Home', '/'], ['Links', '/links']],
        'legal-mention' => [['Home', '/'], ['Legal notice', '/legal-mention']],
        'price'         => [['Home', '/'], ['Commissions', '/price']],
    ];
    if (isset($breadcrumbs[$page])) {
        $items = [];
        foreach ($breadcrumbs[$page] as $i => [$name, $path]) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $name,
                'item'     => $base . $path,
            ];
        }
        $blocks[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    if ($page === 'project-detail' && !empty($context['project'])) {
        $blocks[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $base . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Portfolio', 'item' => $base . '/projects'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $context['project']['title'] ?? '', 'item' => $base . '/project-detail/' . ($context['project']['id'] ?? '')],
            ],
        ];
    }

    foreach ($blocks as $b) {
        echo '<script type="application/ld+json">' . "\n";
        echo json_encode($b, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo "\n" . '</script>' . "\n";
    }
}

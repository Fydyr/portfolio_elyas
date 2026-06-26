<?php

// Vérifier que les helpers sont chargés
if (!function_exists('view')) {
    throw new Exception('Les fonctions helpers ne sont pas chargées');
}

// Middleware admin
$router->before('GET|POST', '/admin/.*', function () {
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin']) == 1) {
        header('HTTP/1.1 403 Forbidden');
            echo view('403', ['title' => '403 - Accès interdit']);
    }
});

// ===== SEO (sitemap + robots) =====
$router->get('/sitemap.xml', function () {
    include_once 'controllers/SeoController.php';
    (new SeoController())->sitemap();
});
$router->get('/robots.txt', function () {
    include_once 'controllers/SeoController.php';
    (new SeoController())->robots();
});

// ===== Routes principales =====

// Page d'accueil (correspond à /index.php)
$router->get('/', function () {
    include_once 'controllers/HomeController.php';
    $controller = new HomeController();
    $controller->index();
});

$router->get('/home', function () {
    include_once 'controllers/HomeController.php';
    $controller = new HomeController();
    $controller->index();
});

// Page À propos
$router->get('/about', function () {
    include_once './controllers/AboutController.php';
    (new AboutController())->index();
});

// Page Portfolio : grille des catégories
$router->get('/projects', function () {
    include_once './controllers/PortfolioController.php';
    (new PortfolioController())->index();
});

// Galerie d'une catégorie de portfolio (par slug)
$router->get('/projects/([a-zA-Z0-9-]+)', function ($slug) {
    include_once './controllers/PortfolioController.php';
    (new PortfolioController())->category($slug);
});

// Page Links (réseaux sociaux / link-in-bio)
$router->get('/links', function () {
    include_once './controllers/LinksController.php';
    (new LinksController())->index();
});


// Page des mentions légales (correspond à /index.php/legals-mentions)
$router->get('/legal-mention', function () {
    include_once './controllers/LegalMentionsController.php';
    $controller = new LegalMentionsController();
    $controller->legalMentions();
});


// Page des prix des commissions (correspond à /index.php/commissions)
$router->get('/price', function () {
    include_once './controllers/OtherController.php';
    $controller = new OtherController();
    $controller->price();
});

// Page de la blague du théière (correspond à /index.php/418)
$router->get('/418', function () {
    include_once './controllers/OtherController.php';
    $controller = new OtherController();
    $controller->teapot();
});

// ===== Routes d'authentification =====

// Authentification
$router->get('/login', function () {
    include_once 'controllers/AccountController.php';
    $controller = new AccountController();
    $controller->login();
});

$router->post('/login', function () {
    include_once 'controllers/AccountController.php';
    $controller = new AccountController();
    $controller->login();
});

// page de déconnexion
$router->get('/logout', function () {
    include_once 'controllers/AccountController.php';
    $controller = new AccountController();
    $controller->logout();
});

// ===== Routes d'administration =====

// Page d'administration (correspond à /index.php/admin)
$router->get('/admin', function () {
    include_once 'controllers/AdminController.php';
    $controller = new AdminController();
    $controller->admin();
});

// ===== Admin Skills =====
$router->get('/admin/skills', function () {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->index();
});
$router->get('/admin/skills/add', function () {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->editSkill(null);
});
$router->post('/admin/skills/add', function () {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->editSkill(null);
});
$router->get('/admin/skills/edit/(\d+)', function ($id) {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->editSkill($id);
});
$router->post('/admin/skills/edit/(\d+)', function ($id) {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->editSkill($id);
});
$router->post('/admin/skills/delete', function () {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->deleteSkill();
});
$router->get('/admin/skills/category/add', function () {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->editCategory(null);
});
$router->post('/admin/skills/category/add', function () {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->editCategory(null);
});
$router->get('/admin/skills/category/edit/(\d+)', function ($id) {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->editCategory($id);
});
$router->post('/admin/skills/category/edit/(\d+)', function ($id) {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->editCategory($id);
});
$router->post('/admin/skills/category/delete', function () {
    include_once 'controllers/SkillsAdminController.php';
    (new SkillsAdminController())->deleteCategory();
});

// ===== Admin About =====
$router->get('/admin/about', function () {
    include_once 'controllers/AboutAdminController.php';
    (new AboutAdminController())->index();
});
$router->post('/admin/about/save', function () {
    include_once 'controllers/AboutAdminController.php';
    (new AboutAdminController())->saveSettings();
});
$router->get('/admin/about/section/add', function () {
    include_once 'controllers/AboutAdminController.php';
    (new AboutAdminController())->editSection(null);
});
$router->post('/admin/about/section/add', function () {
    include_once 'controllers/AboutAdminController.php';
    (new AboutAdminController())->editSection(null);
});
$router->get('/admin/about/section/edit/(\d+)', function ($id) {
    include_once 'controllers/AboutAdminController.php';
    (new AboutAdminController())->editSection($id);
});
$router->post('/admin/about/section/edit/(\d+)', function ($id) {
    include_once 'controllers/AboutAdminController.php';
    (new AboutAdminController())->editSection($id);
});
$router->post('/admin/about/section/delete', function () {
    include_once 'controllers/AboutAdminController.php';
    (new AboutAdminController())->deleteSection();
});

// ===== Admin Prices =====
$router->get('/admin/prices', function () {
    include_once 'controllers/PricesAdminController.php';
    (new PricesAdminController())->index();
});
$router->post('/admin/prices/status', function () {
    include_once 'controllers/PricesAdminController.php';
    (new PricesAdminController())->saveStatus();
});
$router->get('/admin/prices/add', function () {
    include_once 'controllers/PricesAdminController.php';
    (new PricesAdminController())->edit(null);
});
$router->post('/admin/prices/add', function () {
    include_once 'controllers/PricesAdminController.php';
    (new PricesAdminController())->edit(null);
});
$router->get('/admin/prices/edit/(\d+)', function ($id) {
    include_once 'controllers/PricesAdminController.php';
    (new PricesAdminController())->edit($id);
});
$router->post('/admin/prices/edit/(\d+)', function ($id) {
    include_once 'controllers/PricesAdminController.php';
    (new PricesAdminController())->edit($id);
});
$router->post('/admin/prices/delete', function () {
    include_once 'controllers/PricesAdminController.php';
    (new PricesAdminController())->delete();
});

// ===== Admin Portfolio =====
$router->get('/admin/portfolio', function () {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->index();
});
$router->get('/admin/portfolio/add', function () {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->edit(null);
});
$router->post('/admin/portfolio/add', function () {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->edit(null);
});
$router->post('/admin/portfolio/delete', function () {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->delete();
});
$router->post('/admin/portfolio/image/update', function () {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->updateImage();
});
$router->post('/admin/portfolio/image/delete', function () {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->deleteImage();
});
$router->post('/admin/portfolio/cover', function () {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->setCover();
});
$router->get('/admin/portfolio/edit/(\d+)', function ($id) {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->edit($id);
});
$router->post('/admin/portfolio/edit/(\d+)', function ($id) {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->edit($id);
});
$router->get('/admin/portfolio/(\d+)/images', function ($id) {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->images($id);
});
$router->post('/admin/portfolio/(\d+)/images', function ($id) {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->uploadImages($id);
});
$router->post('/admin/portfolio/(\d+)/embed', function ($id) {
    include_once 'controllers/PortfolioAdminController.php';
    (new PortfolioAdminController())->addEmbed($id);
});

// ===== Admin Réseaux sociaux =====
$router->get('/admin/social', function () {
    include_once 'controllers/SocialAdminController.php';
    (new SocialAdminController())->index();
});
$router->get('/admin/social/add', function () {
    include_once 'controllers/SocialAdminController.php';
    (new SocialAdminController())->edit(null);
});
$router->post('/admin/social/add', function () {
    include_once 'controllers/SocialAdminController.php';
    (new SocialAdminController())->edit(null);
});
$router->get('/admin/social/edit/(\d+)', function ($id) {
    include_once 'controllers/SocialAdminController.php';
    (new SocialAdminController())->edit($id);
});
$router->post('/admin/social/edit/(\d+)', function ($id) {
    include_once 'controllers/SocialAdminController.php';
    (new SocialAdminController())->edit($id);
});
$router->post('/admin/social/delete', function () {
    include_once 'controllers/SocialAdminController.php';
    (new SocialAdminController())->delete();
});

// ==== Routes de test =====
$router->get('/test', function () {
    echo "<h1>✅ Test route fonctionne !</h1>";
    echo "<p><strong>URL:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
    echo "<p><strong>Base path configuré:</strong> /index.php</p>";
    echo "<p><a href='" . url('') . "'>Retour accueil</a></p>";
});

$router->get('/debug', function () {
    echo "<h1>🐛 Debug Router</h1>";
    echo "<div style='background: #f0f0f0; padding: 1rem; font-family: monospace;'>";
    echo "<strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "<br>";
    echo "<strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "<br>";
    echo "<strong>REQUEST_METHOD:</strong> " . $_SERVER['REQUEST_METHOD'] . "<br>";
    echo "<strong>Base path Bramus:</strong> /index.php<br>";
    echo "</div>";
    echo "<h3>🧪 Tests :</h3>";
    echo "<ul>";
    echo "<li><a href='" . url('') . "'>Accueil</a></li>";
    echo "<li><a href='" . url('test') . "'>Test</a></li>";
    echo "</ul>";
});

// ==== 404 ====
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo view('404', ['title' => '404 - Page non trouvée']);
});
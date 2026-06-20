<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/settings.php';

class AboutController extends BaseController
{
    public function index(): void
    {
        global $pdo;

        $skillsCount   = 0;
        $projectsCount = 0;
        try { $skillsCount   = (int)$pdo->query("SELECT COUNT(*) FROM skills WHERE visible = 1")->fetchColumn(); } catch (Exception $e) {}
        try { $projectsCount = (int)$pdo->query("SELECT COUNT(*) FROM portfolio_images")->fetchColumn(); } catch (Exception $e) {}

        // Editable settings (managed via /admin/about)
        $heroSubtitle = nl2br(htmlspecialchars(
            setting('about_hero_subtitle', 'VTuber artist & Live2D rigger.'),
            ENT_QUOTES,
            'UTF-8'
        ));
        $bioHtml  = settingHtml('about_bio', '');
        $sections = loadAboutSections();

        echo $this->view('about', compact(
            'skillsCount', 'projectsCount', 'heroSubtitle', 'bioHtml', 'sections'
        ));
    }
}

<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/settings.php';

class LinksController extends BaseController
{
    public function index(): void
    {
        $links = loadSocialLinks(false);
        echo $this->view('links', compact('links'));
    }
}

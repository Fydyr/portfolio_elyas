<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../includes/db.php';

class OtherController extends BaseController
{
    public function price()
    {
        global $pdo;
        $prices = $pdo->query(
            "SELECT p.*,
                    (SELECT c.slug FROM portfolio_categories c WHERE c.commission_id = p.id AND c.visible = 1 ORDER BY c.sort_order LIMIT 1) AS portfolio_slug,
                    (SELECT c.name FROM portfolio_categories c WHERE c.commission_id = p.id AND c.visible = 1 ORDER BY c.sort_order LIMIT 1) AS portfolio_name
             FROM price_items p
             WHERE p.visible = 1
             ORDER BY p.sort_order, p.id"
        )->fetchAll(\PDO::FETCH_ASSOC);
        echo $this->view('price', ['prices' => $prices]);
    }

    public function teapot()
    {
        echo $this->view('418');
    }
}

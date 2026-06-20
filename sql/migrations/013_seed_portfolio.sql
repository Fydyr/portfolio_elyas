-- Seed portfolio categories, linked to matching commissions by title.
-- Images are added later via /admin/portfolio.

INSERT IGNORE INTO portfolio_categories (name, slug, description, icon, commission_id, sort_order) VALUES
('3D Modelling',  '3d-modelling',  'VTuber-ready 3D models, outfits, hair and accessories.', 'fas fa-cube',         (SELECT id FROM price_items WHERE title = '3D Modelling'   LIMIT 1), 1),
('Live2D Rigging','live2d-rigging','Live2D rigs and rig-ready PSDs, brought to life.',       'fas fa-bezier-curve', (SELECT id FROM price_items WHERE title = 'Live2D Rigging' LIMIT 1), 2),
('Animation',     'animation',     'Custom animations, intros and clips.',                   'fas fa-film',         (SELECT id FROM price_items WHERE title = 'Animation'      LIMIT 1), 3),
('Art & Emotes',  'art-emotes',    'Illustrations and expressive emotes.',                   'fas fa-palette',      (SELECT id FROM price_items WHERE title = 'Art'            LIMIT 1), 4);

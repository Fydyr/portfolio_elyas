-- Seed "services" reused via the skill_categories / skills tables.
-- On the home page each category renders as a card and each "skill" is a
-- clickable service with a modal (type, price range, what's included, order link).

-- Service categories
INSERT IGNORE INTO skill_categories (id, name, description, icon, icon_bg, sort_order) VALUES
(1, '3D Modelling',    'VTuber-ready 3D assets modelled from your design.',         'fas fa-cube',          NULL,                                                  1),
(2, 'Live2D Rigging',  'Bringing 2D models to life, rig-ready and stream-ready.',   'fas fa-bezier-curve',  'var(--gradient-secondary)',                           2),
(3, 'Animation',       'Custom animations for streams, intros and content.',        'fas fa-film',          'var(--gradient-warning)',                             3),
(4, 'Art & Emotes',    'Illustrations and emotes for your channel and brand.',      'fas fa-palette',       'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)',   4);

-- 3D Modelling services
INSERT IGNORE INTO skills (category_id, name, slug, description, type, level, icon, doc_url, features, sort_order) VALUES
(1, 'Full Model',     'full-model',  'A complete VTuber-ready 3D model built from your reference, ready to rig.',
    '3D Model', '€90–170', 'fas fa-user-astronaut', 'https://vgen.co/fyfyntt',
    '["Whole outfit with hair: €60–130","Full model: €90–170","Built clean and rig-friendly","Made from your design / reference"]', 1),
(1, 'Outfit',         'outfit',      'Full outfit modelling, with or without matching hair.',
    '3D Model', '€35–130', 'fas fa-shirt', 'https://vgen.co/fyfyntt',
    '["Whole outfit: €35–90","Whole outfit + hair: €60–130","Clothing top: €5–20","Clothing bottom: €5–25"]', 2),
(1, 'Hair',           'hair',        '3D hair pieces modelled to match your character.',
    '3D Model', '€10–40', 'fas fa-wind', 'https://vgen.co/fyfyntt',
    '["Full hair: €25–40","Hair bangs: €15–20","Back hair: €10–20"]', 3),
(1, 'Accessories',    'accessories', 'Extra props, accessories and footwear for your model.',
    '3D Model', '€10–30', 'fas fa-gem', 'https://vgen.co/fyfyntt',
    '["Accessories: €10–20","Shoes: €15–30"]', 4);

-- Live2D Rigging services
INSERT IGNORE INTO skills (category_id, name, slug, description, type, level, icon, doc_url, features, sort_order) VALUES
(2, 'Live2D Rig',     'live2d-rig',  'Full Live2D rigging so your model is ready to stream and react.',
    'Rigging', '€110–150', 'fas fa-bezier-curve', 'https://vgen.co/fyfyntt',
    '["Head & body angles (X/Y/Z)","Expressions & toggles","Eye & mouth rigs","Hair & accessory physics","Hand assets"]', 1),
(2, 'PSD Making',     'psd-making',  'Clean, fully separated PSD prepared and layered for rigging.',
    'Prep', '€70–120', 'fas fa-layer-group', 'https://vgen.co/fyfyntt',
    '["Fully separated layers","Rig-ready folder structure","Cleaned & named layers"]', 2);

-- Animation services
INSERT IGNORE INTO skills (category_id, name, slug, description, type, level, icon, doc_url, features, sort_order) VALUES
(3, 'Animation',      'animation',   'Custom 2D/3D animation, priced by length. Perfect for intros, transitions and clips.',
    'Animation', 'from €1.50 / sec', 'fas fa-film', 'https://vgen.co/fyfyntt',
    '["1 second: €1.50","30 seconds: €45","1 minute: €90","2 minutes: €180","3 minutes: €270","4 minutes: €360"]', 1);

-- Art & Emotes services
INSERT IGNORE INTO skills (category_id, name, slug, description, type, level, icon, doc_url, features, sort_order) VALUES
(4, 'Illustration',   'illustration','Character illustrations from bust to full body, with optional background.',
    'Art', '€50–60', 'fas fa-paintbrush', 'https://vgen.co/fyfyntt',
    '["Bust: €50","Half body: €55","Full body: €60","Background add-on: +€25"]', 1),
(4, 'Emotes',         'emotes',      'Expressive emotes for Twitch & Discord, with bulk discounts.',
    'Art', 'from €5', 'fas fa-face-smile', 'https://vgen.co/fyfyntt',
    '["1 emote: €5","5 emotes: €25 (15% off)","10 emotes: €50 (25% off)","20 emotes: €100 (35% off)"]', 2);

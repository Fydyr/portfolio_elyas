-- About page content (editable later via /admin/about).

INSERT IGNORE INTO site_settings (`key`, `value`, `is_markdown`) VALUES
('about_hero_subtitle',
 "VTuber, animator, artist and Live2D rigger.\nI create models, rigs, animations and art for streamers and content creators.",
 0),
('about_bio',
 "Hi, I'm **Fynt** (also known as *Fyntsu*) — a multidisciplinary VTuber artist working across **3D modelling**, **Live2D rigging**, **animation** and **illustration**.\n\nI help streamers and creators bring their characters to life, from the first sketch to a fully rigged, stream-ready model. Whether you need a complete VTuber model, a clean rig, custom emotes or a short animation, I love turning ideas into expressive, polished work.\n\nCommissions are open through **VGen**, and you can follow my latest work across **YouTube, Twitch, Twitter, Instagram** and more. Feel free to reach out on Discord if you have a project in mind!",
 1),
('commission_status', 'open', 0),
('commission_status_note', '', 0);

-- About sections ("What I create")
INSERT IGNORE INTO about_sections (slug, title, icon, content, sort_order) VALUES
('models-rigs',
 'Models & Rigs',
 'bi bi-person-bounding-box',
 "Full VTuber models and clean Live2D rigs — head & body angles, expressions, physics and hand assets, ready to stream.",
 1),
('animation-art',
 'Animation & Art',
 'bi bi-film',
 "Custom animations, illustrations and expressive emotes for your channel, intros and brand.",
 2),
('open-commissions',
 'Open for commissions',
 'bi bi-stars',
 "Commissions are open via VGen. Check the waitlist board for current progress, or reach out on Discord to discuss your project.",
 3);

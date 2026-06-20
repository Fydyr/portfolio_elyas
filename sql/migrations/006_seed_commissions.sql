-- Commission price list (rendered on the /price "Commissions" page).
-- One card per service category, with the full breakdown in the description.

INSERT IGNORE INTO price_items (title, description, price, icon, sort_order) VALUES
('3D Modelling',
 'VTuber-ready 3D assets modelled from your design.
- Full hair: €25–40
- Hair bangs: €15–20
- Back hair: €10–20
- Clothing top: €5–20
- Clothing bottom: €5–25
- Shoes: €15–30
- Accessories: €10–20
- Whole outfit: €35–90
- Whole outfit with hair: €60–130
- Full model: €90–170',
 '€5 – €170', 'fas fa-cube', 1),

('Animation',
 'Custom animation priced by length — ideal for intros, transitions and clips.
- 1 second: €1.50
- 30 seconds: €45
- 1 minute: €90
- 2 minutes: €180
- 3 minutes: €270
- 4 minutes: €360',
 'from €1.50 / second', 'fas fa-film', 2),

('Live2D Rigging',
 'Full Live2D rigging so your model is ready to stream and react.
- Head & body angles (X / Y / Z)
- Expressions & toggles
- Eye & mouth rigs
- Hair & accessory physics
- Hand assets',
 '€110 – €150', 'fas fa-bezier-curve', 3),

('PSD Making',
 'Clean, fully separated and layered PSD prepared for rigging.
- Fully separated layers
- Rig-ready folder structure
- Cleaned & named layers',
 '€70 – €120', 'fas fa-layer-group', 4),

('Art',
 'Character illustrations, with an optional background add-on.
- Bust: €50
- Half body: €55
- Full body: €60
- Background add-on: +€25',
 '€50 – €60 (+€25 bg)', 'fas fa-palette', 5),

('Emotes',
 'Expressive emotes for Twitch & Discord, with bulk discounts.
- 1 emote: €5
- 5 emotes: €25 (15% off)
- 10 emotes: €50 (25% off)
- 20 emotes: €100 (35% off)',
 'from €5', 'fas fa-face-smile', 6);

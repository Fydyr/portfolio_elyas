-- Social links, manageable from the admin panel.
-- `featured` = shown in the compact rows (hero + footer); all visible ones show on About/Contact.

CREATE TABLE IF NOT EXISTS social_links (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    platform   VARCHAR(50)  NOT NULL,
    label      VARCHAR(100) NOT NULL,
    url        VARCHAR(500) NOT NULL,
    icon       VARCHAR(100),
    featured   TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    visible    TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO social_links (platform, label, url, icon, featured, sort_order) VALUES
('twitter',    'Twitter',    'https://twitter.com/_FoxBee',                'fab fa-twitter',    1, 1),
('instagram',  'Instagram',  'https://www.instagram.com/fyfyntt/',         'fab fa-instagram',  1, 2),
('youtube',    'YouTube',    'https://www.youtube.com/@Fynt_Elyas',        'fab fa-youtube',    1, 3),
('twitch',     'Twitch',     'https://www.twitch.tv/fyfyntt',              'fab fa-twitch',     1, 4),
('tiktok',     'TikTok',     'https://www.tiktok.com/@fyfyntt',            'fab fa-tiktok',     1, 5),
('kofi',       'Ko-fi',      'https://ko-fi.com/fyntsu',                   'fas fa-mug-hot',    1, 6),
('discord',    'Discord',    'https://discord.gg/DTvkz3BQHz',              'fab fa-discord',    1, 7),
('vgen',       'VGen',       'https://vgen.co/fyfyntt',                    'fas fa-pen-nib',    0, 8),
('artstation', 'ArtStation', 'https://www.artstation.com/fyntsu/profile',  'fab fa-artstation', 0, 9),
('deviantart', 'DeviantArt', 'https://www.deviantart.com/fyntb',           'fab fa-deviantart', 0, 10),
('cara',       'Cara',       'https://cara.app/fyfyntt',                   'fas fa-paintbrush', 0, 11),
('patreon',    'Patreon',    'https://www.patreon.com/fyfynt',             'fab fa-patreon',    0, 12);

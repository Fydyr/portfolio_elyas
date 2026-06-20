-- Portfolio: categories (one gallery page each) + unlimited images per category.
-- Each category can be linked 1:1 to a commission (price_items).

CREATE TABLE IF NOT EXISTS portfolio_categories (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(150) NOT NULL,
    slug          VARCHAR(150) UNIQUE NOT NULL,
    description   TEXT,
    icon          VARCHAR(100),
    cover_image   VARCHAR(255),
    commission_id INT NULL,
    sort_order    INT DEFAULT 0,
    visible       TINYINT(1) DEFAULT 1,
    FOREIGN KEY (commission_id) REFERENCES price_items(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS portfolio_images (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    filename    VARCHAR(255) NOT NULL,
    caption     VARCHAR(255),
    sort_order  INT DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES portfolio_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Art' Afric - Base de données e-commerce
-- PHP 8 / MySQL

CREATE DATABASE IF NOT EXISTS art_afric CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE art_afric;

-- Utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('admin', 'employee', 'client') DEFAULT 'client',
    avatar VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    email_verified_at DATETIME DEFAULT NULL,
    reset_token VARCHAR(100) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Journal connexions
CREATE TABLE login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    success TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Produits
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(280) NOT NULL UNIQUE,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(12,2) NOT NULL,
    sale_price DECIMAL(12,2) DEFAULT NULL,
    sku VARCHAR(50) UNIQUE,
    stock INT DEFAULT 0,
    material VARCHAR(100) DEFAULT NULL,
    color VARCHAR(100) DEFAULT NULL,
    sizes JSON DEFAULT NULL,
    images JSON DEFAULT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    is_new TINYINT(1) DEFAULT 0,
    is_promo TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    rating_avg DECIMAL(3,2) DEFAULT 0,
    rating_count INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

-- Avis produits
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(200) DEFAULT NULL,
    comment TEXT,
    is_approved TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Favoris
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_fav (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Adresses
CREATE TABLE addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    label VARCHAR(50) DEFAULT 'Domicile',
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255) DEFAULT NULL,
    city VARCHAR(100) NOT NULL,
    region VARCHAR(100) DEFAULT NULL,
    postal_code VARCHAR(20) DEFAULT NULL,
    country VARCHAR(100) DEFAULT 'Sénégal',
    phone VARCHAR(20) NOT NULL,
    is_default TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Coupons
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    type ENUM('percentage', 'fixed') DEFAULT 'percentage',
    value DECIMAL(10,2) NOT NULL,
    min_order DECIMAL(12,2) DEFAULT 0,
    max_uses INT DEFAULT NULL,
    used_count INT DEFAULT 0,
    starts_at DATETIME DEFAULT NULL,
    expires_at DATETIME DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Commandes
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    status ENUM('pending','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
    subtotal DECIMAL(12,2) NOT NULL,
    shipping_cost DECIMAL(12,2) DEFAULT 0,
    discount DECIMAL(12,2) DEFAULT 0,
    tax DECIMAL(12,2) DEFAULT 0,
    total DECIMAL(12,2) NOT NULL,
    coupon_code VARCHAR(50) DEFAULT NULL,
    payment_method ENUM('cash_on_delivery','card','orange_money','wave','free_money','paypal','stripe') DEFAULT 'cash_on_delivery',
    payment_status ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
    shipping_method VARCHAR(50) DEFAULT 'standard',
    shipping_address JSON NOT NULL,
    billing_address JSON DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    tracking_number VARCHAR(100) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Lignes de commande
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(12,2) NOT NULL,
    size VARCHAR(20) DEFAULT NULL,
    color VARCHAR(50) DEFAULT NULL,
    total DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- Messages contact
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Newsletter
CREATE TABLE newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1,
    subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Blog
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(280) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    category VARCHAR(100) DEFAULT 'Culture',
    is_published TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    published_at DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- Notifications
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    link VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Index
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_featured ON products(is_featured, is_active);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);

CREATE TABLE settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO settings (setting_key, setting_value) VALUES
('footer_description', 'Boutique premium dédiée à l''artisanat africain authentique. Chaque pièce raconte une histoire, chaque création célèbre notre héritage.'),
('contact_address', 'Dakar, Sénégal'),
('contact_phone', '+221 33 123 45 67'),
('contact_email', 'contact@artafric.com'),
('social_facebook', ''),
('social_instagram', ''),
('social_whatsapp', ''),
('social_pinterest', ''),
('whatsapp_order_number', '221777929623'),
('header_ad_text', ''),
('header_ad_link', ''),
('about_story', 'Fondée en 2020 à Dakar, Art'' Afric est née d''une passion profonde pour l''artisanat africain et d''une volonté de le faire rayonner sur la scène internationale. Nous collaborons directement avec plus de 200 artisans à travers le continent.'),
('about_mission', 'Valoriser l''artisanat africain en le rendant accessible au monde entier, tout en garantissant une rémunération équitable aux créateurs.'),
('about_vision', 'Devenir la référence mondiale de l''e-commerce premium pour les produits africains authentiques.'),
('about_values', 'Authenticité, excellence, équité, durabilité et fierté culturelle.'),
('about_team', 'Amadou Diop | Fondateur & CEO
Fatou Sow | Directrice Artistique
Moussa Kane | Responsable Logistique');

CREATE TABLE partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    website_url VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO partners (name, sort_order) VALUES
('Artisans du Sénégal', 1),
('Maison Bogolan', 2),
('Wax Hollandais', 3),
('Atelier Touareg', 4),
('Créations Baoulé', 5);

-- Données initiales
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('Admin', 'Art Afric', 'admin@artafric.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Aissatou', 'Diallo', 'client@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client');

INSERT INTO categories (name, slug, description, sort_order) VALUES
('Vêtements', 'vetements', 'Boubous, dashikis, tenues traditionnelles et modernes', 1),
('Sacs', 'sacs', 'Sacs en cuir, raphia et tissu wax', 2),
('Bijoux', 'bijoux', 'Colliers, bracelets et boucles d''oreilles artisanaux', 3),
('Chaussures', 'chaussures', 'Sandales, babouches et chaussures en cuir', 4),
('Décoration', 'decoration', 'Objets décoratifs et art de la table', 5),
('Tissus', 'tissus', 'Wax, bogolan, kente et autres tissus africains', 6),
('Accessoires', 'accessoires', 'Chapeaux, écharpes et ceintures', 7),
('Artisanat', 'artisanat', 'Sculptures, masques et objets d''art', 8);

INSERT INTO products (category_id, name, slug, description, short_description, price, sale_price, sku, stock, material, color, sizes, images, is_featured, is_new, is_promo, rating_avg, rating_count) VALUES
(1, 'Boubou Brodé Premium', 'boubou-brode-premium', 'Magnifique boubou traditionnel en bazin riche brodé à la main par des artisans sénégalais. Pièce unique alliant élégance et authenticité.', 'Boubou bazin brodé main - Collection Premium', 85000, NULL, 'VET-001', 15, 'Bazin riche', 'Or et Blanc', '["S","M","L","XL"]', '["boubou-1.jpg","boubou-2.jpg"]', 1, 1, 0, 4.8, 24),
(1, 'Dashiki Wax Coloré', 'dashiki-wax-colore', 'Dashiki vibrant en wax hollandais aux motifs géométriques africains. Coupe moderne et confortable.', 'Dashiki wax aux couleurs éclatantes', 25000, 19900, 'VET-002', 30, 'Wax hollandais', 'Multicolore', '["S","M","L","XL","XXL"]', '["dashiki-1.jpg"]', 1, 0, 1, 4.5, 18),
(2, 'Sac Raphia Artisanal', 'sac-raphia-artisanal', 'Sac à main tressé en raphia naturel par des artisanes du Sénégal. Poignée en cuir véritable.', 'Sac raphia fait main - Édition limitée', 35000, NULL, 'SAC-001', 20, 'Raphia et cuir', 'Naturel', '["Unique"]', '["sac-raphia-1.jpg"]', 1, 1, 0, 4.9, 31),
(3, 'Collier Perles Massaï', 'collier-perles-massai', 'Collier traditionnel en perles colorées inspiré de l''art Massaï. Chaque pièce est unique.', 'Collier perles artisanales Massaï', 18000, NULL, 'BIJ-001', 25, 'Perles et fil métal', 'Rouge et Or', '["Unique"]', '["collier-1.jpg"]', 1, 0, 0, 4.7, 15),
(3, 'Bracelet Bronze Touareg', 'bracelet-bronze-touareg', 'Bracelet en bronze gravé selon la tradition touareg du Mali. Symboles ancestraux.', 'Bracelet bronze gravé Touareg', 12000, 9900, 'BIJ-002', 40, 'Bronze', 'Bronze', '["Unique"]', '["bracelet-1.jpg"]', 0, 0, 1, 4.6, 22),
(4, 'Sandales Cuir Touareg', 'sandales-cuir-touareg', 'Sandales en cuir pleine fleur avec semelle en caoutchouc naturel. Confortables et durables.', 'Sandales cuir artisanal Touareg', 22000, NULL, 'CHAU-001', 18, 'Cuir pleine fleur', 'Marron', '["38","39","40","41","42","43"]', '["sandales-1.jpg"]', 1, 0, 0, 4.4, 12),
(5, 'Masque Décoratif Baoulé', 'masque-decoratif-baoule', 'Réplique artisanale de masque Baoulé de Côte d''Ivoire. Sculpture en bois d''ébène.', 'Masque Baoulé sculpté main', 45000, NULL, 'DEC-001', 8, 'Bois d''ébène', 'Noir naturel', '["Unique"]', '["masque-1.jpg"]', 1, 0, 0, 5.0, 8),
(6, 'Tissu Wax 6 Yards', 'tissu-wax-6-yards', 'Tissu wax hollandais premium, 6 yards. Motifs exclusifs Art'' Afric.', 'Wax hollandais premium 6 yards', 15000, NULL, 'TIS-001', 50, 'Coton wax', 'Bleu et Or', '["6 yards"]', '["wax-1.jpg"]', 0, 1, 0, 4.3, 45),
(7, 'Chapeau Bogolan', 'chapeau-bogolan', 'Chapeau en bogolan malien teint à la boue. Accessoire unique pour un look afro-chic.', 'Chapeau bogolan fait main', 14000, NULL, 'ACC-001', 22, 'Bogolan', 'Terre de Sienne', '["Unique"]', '["chapeau-1.jpg"]', 0, 1, 0, 4.2, 9),
(8, 'Sculpture Éléphant Bois', 'sculpture-elephant-bois', 'Sculpture d''éléphant en bois de teck sculpté à la main au Ghana. Symbole de sagesse.', 'Sculpture éléphant teck Ghana', 55000, 48000, 'ART-001', 5, 'Bois de teck', 'Bois naturel', '["Unique"]', '["sculpture-1.jpg"]', 1, 0, 1, 4.9, 6);

INSERT INTO coupons (code, type, value, min_order, max_uses, expires_at) VALUES
('BIENVENUE10', 'percentage', 10, 10000, 100, '2026-12-31 23:59:59'),
('ARTAFRIC5000', 'fixed', 5000, 30000, 50, '2026-06-30 23:59:59');

INSERT INTO blog_posts (author_id, title, slug, excerpt, content, category, is_published, published_at) VALUES
(1, 'L''art du bogolan : tradition et modernité', 'art-bogolan-tradition-modernite', 'Découvrez l''histoire fascinante du bogolan malien et son renouveau dans la mode contemporaine.', '<p>Le bogolan, ou « boue de la terre », est un tissu traditionnel malien teint avec de la boue fermentée. Cette technique millénaire continue d''inspirer les créateurs du monde entier.</p><p>Chez Art'' Afric, nous collaborons directement avec des artisans pour préserver cette tradition tout en l''adaptant aux goûts modernes.</p>', 'Culture', 1, NOW()),
(1, 'Comment porter le wax au quotidien', 'porter-wax-quotidien', 'Nos conseils pour intégrer le wax dans votre garde-robe avec élégance.', '<p>Le wax n''est plus réservé aux occasions spéciales. Découvrez comment le porter au bureau, en soirée ou en week-end.</p>', 'Mode africaine', 1, NOW());

INSERT INTO newsletter_subscribers (email) VALUES ('newsletter@example.com');

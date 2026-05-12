CREATE DATABASE IF NOT EXISTS alimentation;
USE alimentation;

-- =========================================================
-- GENRES
-- =========================================================

CREATE TABLE genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(20) NOT NULL UNIQUE
);

INSERT INTO genres (nom) VALUES
('Male'),
('Female'),
('Autre');

-- =========================================================
-- UTILISATEURS
-- =========================================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,

    genre_id INT NOT NULL,

    is_gold BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (genre_id)
        REFERENCES genres(id)
        ON DELETE RESTRICT
);

-- =========================================================
-- ETAT PHYSIQUE UTILISATEUR
-- =========================================================

CREATE TABLE etat_user (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL UNIQUE,

    taille DECIMAL(4,2) NOT NULL COMMENT 'taille en metres',
    poids DECIMAL(5,2) NOT NULL COMMENT 'poids en kg',

    imc DECIMAL(5,2)
    GENERATED ALWAYS AS (
        poids / (taille * taille)
    ) STORED,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- =========================================================
-- OBJECTIFS
-- =========================================================

CREATE TABLE objectifs (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO objectifs (nom, description) VALUES
('Augmenter son poids', 'Prise de masse'),
('Réduire son poids', 'Perte de poids'),
('Atteindre son IMC idéal', 'Atteindre un IMC normal');

-- =========================================================
-- OBJECTIFS UTILISATEUR
-- =========================================================

CREATE TABLE user_objectifs (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    objectif_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (objectif_id)
        REFERENCES objectifs(id)
        ON DELETE RESTRICT
);

-- =========================================================
-- REGIMES
-- =========================================================

CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(150) NOT NULL,
    description TEXT,

    variation_poids DECIMAL(5,2) NOT NULL COMMENT 'variation poids en kg',

    pourcentage_viande DECIMAL(5,2) DEFAULT 0,
    pourcentage_poisson DECIMAL(5,2) DEFAULT 0,
    pourcentage_volaille DECIMAL(5,2) DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

-- =========================================================
-- REGIME <-> OBJECTIF
-- =========================================================

CREATE TABLE regime_objectifs (
    id INT AUTO_INCREMENT PRIMARY KEY,

    regime_id INT NOT NULL,
    objectif_id INT NOT NULL,

    FOREIGN KEY (regime_id)
        REFERENCES regimes(id)
        ON DELETE CASCADE,

    FOREIGN KEY (objectif_id)
        REFERENCES objectifs(id)
        ON DELETE CASCADE
);

-- =========================================================
-- DUREES
-- =========================================================

CREATE TABLE durees (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(50) NOT NULL,
    nombre_jours INT NOT NULL
);

INSERT INTO durees (nom, nombre_jours) VALUES
('1 semaine', 7),
('2 semaines', 14),
('1 mois', 30),
('3 mois', 90);

-- =========================================================
-- PRIX REGIME SELON DUREE
-- =========================================================

CREATE TABLE regime_prix (
    id INT AUTO_INCREMENT PRIMARY KEY,

    regime_id INT NOT NULL,
    duree_id INT NOT NULL,

    prix DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (regime_id)
        REFERENCES regimes(id)
        ON DELETE CASCADE,

    FOREIGN KEY (duree_id)
        REFERENCES durees(id)
        ON DELETE CASCADE
);

-- =========================================================
-- ACTIVITES SPORTIVES
-- =========================================================

CREATE TABLE activites_sportives (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(100) NOT NULL,
    description TEXT,

    calories_brulees_heure INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- REGIME <-> ACTIVITE SPORTIVE
-- =========================================================

CREATE TABLE regime_activites (
    id INT AUTO_INCREMENT PRIMARY KEY,

    regime_id INT NOT NULL,
    activite_id INT NOT NULL,

    FOREIGN KEY (regime_id)
        REFERENCES regimes(id)
        ON DELETE CASCADE,

    FOREIGN KEY (activite_id)
        REFERENCES activites_sportives(id)
        ON DELETE CASCADE
);

-- =========================================================
-- PORTEFEUILLE
-- =========================================================

CREATE TABLE portefeuilles (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL UNIQUE,

    solde DECIMAL(10,2) DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- =========================================================
-- CODES RECHARGE
-- =========================================================

CREATE TABLE codes_recharge (
    id INT AUTO_INCREMENT PRIMARY KEY,

    code VARCHAR(100) NOT NULL UNIQUE,

    montant DECIMAL(10,2) NOT NULL,

    est_utilise BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- HISTORIQUE RECHARGE
-- =========================================================

CREATE TABLE recharge_portefeuille (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    code_id INT NOT NULL,

    montant DECIMAL(10,2) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (code_id)
        REFERENCES codes_recharge(id)
        ON DELETE CASCADE
);

-- =========================================================
-- ABONNEMENT GOLD
-- =========================================================

CREATE TABLE abonnements_gold (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL UNIQUE,

    prix DECIMAL(10,2) NOT NULL,

    date_debut TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    actif BOOLEAN DEFAULT TRUE,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);


CREATE TABLE user_regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    duree_id INT NOT NULL,
    prix_paye DECIMAL(10,2) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE,
    FOREIGN KEY (duree_id) REFERENCES durees(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--utilisateurs avec des mot de passe deja crypte genre le mdp c'est "12345678" pour tout les utilisateurs 
--mais pour faciliter l'insert j'ai deja automatiquement 
--crypte le mdp et je l'ai mis dans la requete d'insertion, 
--comme ca pas besoin de se casser la tete a faire du bcrypt a la main pour chaque
-- utilisateur, et ca evite les erreurs de syntaxe dans les requetes d'insert

INSERT INTO users (nom, email, password, genre_id, is_gold) VALUES
('Paulo Dybala', 'paulo@gmail.com', '$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 1, FALSE),
('Jude Bellingham',   'jude@gmail.com',   '$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 1, FALSE),
('Kenan Yildiz',    'kenan@gmail.com','$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 1, TRUE),
('Charles Leclerc',     'charles@gmail.com', '$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 1, FALSE),
('Olivier Girou',   'olivier@gmailcom',  '$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 1, FALSE);

INSERT INTO etat_user (user_id, taille, poids) VALUES
(1, 1.77, 75.00),
(2, 1.86, 75.00),
(3, 1.85, 72.00),
(4, 1.80, 69.00),
(5, 1.92, 91.00);

--portefeuilles de chaque users avec deja une solde presente si on le veux 
INSERT INTO portefeuilles (user_id, solde) VALUES
(1, 12000),
(2, 25000),
(3, 90000),
(4, 60000),
(5, 75000);

-- 15 Recharge codes
INSERT INTO codes_recharge (code, montant) VALUES
('NUTRI-2026-0001', 10000),
('NUTRI-2026-0002', 15000),
('NUTRI-2026-0003', 20000),
('NUTRI-2026-0004', 25000),
('NUTRI-2026-0005', 30000),
('NUTRI-2026-0006', 35000),
('NUTRI-2026-0007', 40000),
('NUTRI-2026-0008', 45000),
('NUTRI-2026-0009', 50000),
('NUTRI-2026-0010', 60000),
('NUTRI-2026-0011', 75000),
('NUTRI-2026-0012', 90000),
('NUTRI-2026-0013', 100000),
('NUTRI-2026-0014', 125000),
('NUTRI-2026-0015', 150000);

-- 5 Regimes (if regimes table already has items, these will append)
INSERT INTO regimes (nom, description, variation_poids, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
('Régime Végan Léger', 'Faible apport en protéines animales, axé sur végétaux', -1.5, 0, 10, 0),
('Régime Riche en Protéines', 'Favorise la prise de masse musculaire', 3.5, 50, 20, 30),
('Régime Méditerranéen', 'Équilibré, riche en poisson et légumes', 0.5, 25, 40, 35),
('Régime Faible en Glucides', 'Réduction des apports en amidon et sucres', -2.0, 35, 25, 40),
('Régime Sportif Intensif', 'Conçu pour sportifs à fort besoin calorique', 5.0, 45, 25, 30);

-- 5 Activités sportives
INSERT INTO activites_sportives (nom, description, calories_brulees_heure) VALUES
('Randonnée', 'Marche prolongée sur terrains variés', 300),
('CrossFit', 'Entraînement fonctionnel à haute intensité', 650),
('Pilates', 'Renforcement du centre et mobilité', 180),
('Boxe', 'Cardio et renforcement musculaire', 550),
('Aviron', 'Exercice complet du corps', 600);

--donnee d'acces pour le cote admin, le mdp est admin123
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$rgl1TggXU5MXbZ0NsX2cYuZRl2sdarYRPbyYeU.MtEsWigbXGtGGO');
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
('M'),
('F'),
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


INSERT INTO regimes (nom, description, variation_poids, pourcentage_viande, pourcentage_poisson, pourcentage_volaille)
VALUES 
('Régime Hypercalorique', 'Prise de masse rapide', 4, 40, 30, 30),
('Régime Protéiné', 'Augmentation musculaire', 6, 50, 20, 30),
('Régime Équilibré', 'Gain progressif', 2, 35, 35, 30);


INSERT INTO regime_objectifs (regime_id, objectif_id)
VALUES 
(1, 1),
(2, 1),
(3, 1);

INSERT INTO regime_prix (regime_id, duree_id, prix)
VALUES 
(1, 3, 50000),
(2, 3, 80000),
(3, 3, 35000);


-- Activités sportives
INSERT INTO activites_sportives (nom, description, calories_brulees_heure) VALUES
('Musculation',     'Exercices de résistance pour développer la masse musculaire', 400),
('Cardio léger',    'Marche rapide ou vélo à faible intensité',                    250),
('Course à pied',   'Jogging à allure modérée',                                    500),
('Natation',        'Nage toutes nages confondues',                                 450),
('Yoga',            'Étirements et postures pour la souplesse et la récupération',  150),
('HIIT',            'Entraînement fractionné de haute intensité',                   600),
('Vélo elliptique', 'Cardio faible impact sur les articulations',                   350),
('Saut à la corde', 'Exercice cardio intense au poids du corps',                    550);


INSERT INTO regime_activites (regime_id, activite_id) VALUES
(1, 1), (1, 2), (1, 5);


INSERT INTO regime_activites (regime_id, activite_id) VALUES
(2, 1), (2, 6), (2, 3);


INSERT INTO regime_activites (regime_id, activite_id) VALUES
(3, 2), (3, 4), (3, 7);

INSERT INTO portefeuilles (user_id, solde) VALUES (1, 150000);


INSERT INTO codes_recharge (code, montant) VALUES
('NUTRI-2024-AAAA', 50000),
('NUTRI-2024-BBBB', 100000),
('NUTRI-2024-CCCC', 150000);
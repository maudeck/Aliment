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
-- TYPE REPAS
-- =========================================================

CREATE TABLE type_repas (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO type_repas (nom) VALUES
('Petit déjeuner'),
('Déjeuner'),
('Dîner'),
('Collation');

-- =========================================================
-- ALIMENTS
-- =========================================================

CREATE TABLE aliments (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(150) NOT NULL,

    calories INT NOT NULL COMMENT 'calories pour 100g',

    proteines DECIMAL(5,2) DEFAULT 0,
    glucides DECIMAL(5,2) DEFAULT 0,
    lipides DECIMAL(5,2) DEFAULT 0
);

-- =========================================================
-- REPAS
-- =========================================================

CREATE TABLE repas (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(150) NOT NULL,

    type_repas_id INT NOT NULL,

    FOREIGN KEY (type_repas_id)
        REFERENCES type_repas(id)
        ON DELETE RESTRICT
);

-- =========================================================
-- REPAS <-> ALIMENT
-- =========================================================

CREATE TABLE repas_aliments (
    repas_id INT NOT NULL,
    aliment_id INT NOT NULL,

    quantite_gramme DECIMAL(6,2) NOT NULL,

    PRIMARY KEY (repas_id, aliment_id),

    FOREIGN KEY (repas_id)
        REFERENCES repas(id)
        ON DELETE CASCADE,

    FOREIGN KEY (aliment_id)
        REFERENCES aliments(id)
        ON DELETE RESTRICT
);

-- =========================================================
-- PROGRAMMES UTILISATEUR
-- =========================================================

CREATE TABLE programmes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    duree_id INT NOT NULL,

    calories_journalieres INT NOT NULL,

    date_debut DATE,
    date_fin DATE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (regime_id)
        REFERENCES regimes(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (duree_id)
        REFERENCES durees(id)
        ON DELETE RESTRICT
);

-- =========================================================
-- SEMAINES PROGRAMME
-- =========================================================

CREATE TABLE semaines_programme (
    id INT AUTO_INCREMENT PRIMARY KEY,

    programme_id INT NOT NULL,

    numero_semaine INT NOT NULL,

    FOREIGN KEY (programme_id)
        REFERENCES programmes(id)
        ON DELETE CASCADE
);

-- =========================================================
-- JOURS SEMAINE
-- =========================================================

CREATE TABLE jours_semaine (
    id INT AUTO_INCREMENT PRIMARY KEY,

    semaine_id INT NOT NULL,

    nom_jour VARCHAR(20) NOT NULL,

    FOREIGN KEY (semaine_id)
        REFERENCES semaines_programme(id)
        ON DELETE CASCADE
);

-- =========================================================
-- REPAS JOUR
-- =========================================================

CREATE TABLE repas_jour (
    id INT AUTO_INCREMENT PRIMARY KEY,

    jour_id INT NOT NULL,
    repas_id INT NOT NULL,

    FOREIGN KEY (jour_id)
        REFERENCES jours_semaine(id)
        ON DELETE CASCADE,

    FOREIGN KEY (repas_id)
        REFERENCES repas(id)
        ON DELETE RESTRICT
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
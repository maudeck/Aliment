

CREATE DATABASE IF NOT EXISTS alimentation ;
USE alimentation;


CREATE TABLE genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(20) NOT NULL UNIQUE
);

INSERT INTO genres (nom) VALUES
('M'),
('F'),
('Autre');


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    genre_id INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE RESTRICT
);


CREATE TABLE etat_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    taille DECIMAL(3, 2) NOT NULL COMMENT 'Taille en mètres (ex: 1.75)',
    poids DECIMAL(5, 2) NOT NULL COMMENT 'Poids en kg',
    imc DECIMAL(4, 2) GENERATED ALWAYS AS (poids / (taille * taille)) STORED COMMENT 'IMC calculé automatiquement',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ;

CREATE TABLE objectifs_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO objectifs_list (nom, description) VALUES
('Augmenter son poids', 'Objectif : Prendre du poids'),
('Réduire son poids', 'Objectif : Perdre du poids'),
('Atteindre son IMC idéal', 'Objectif : Atteindre un IMC sain') ;

CREATE TABLE user_objectifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    objectif_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (objectif_id) REFERENCES objectifs_list(id) ON DELETE RESTRICT
);
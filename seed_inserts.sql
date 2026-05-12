-- Seed data: 5 users, 15 recharge codes, 5 regimes, 5 activities
-- Safe to run multiple times if DB allows duplicate entries (use with care)

-- Users (passwords use the same bcrypt hash for '12345678')
INSERT INTO users (nom, email, password, genre_id, is_gold) VALUES
('Alice Dupont', 'alice@example.com', '$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 2, FALSE),
('Bob Rakoto',   'bob@example.com',   '$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 1, FALSE),
('Carlos R.',    'carlos@example.com','$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 1, TRUE),
('Diana R.',     'diana@example.com', '$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 2, FALSE),
('Eric Andri',   'eric@example.com',  '$2y$10$Fwo.0brtbqdrWilnBowjb.15A.OP/F5PxRWQrGgYQKHQEwVsEfC8e', 1, FALSE);

-- Portefeuilles for those users (assumes users IDs are 1..5 or auto-assigned accordingly)
-- If user IDs differ, adjust accordingly.
INSERT INTO portefeuilles (user_id, solde) VALUES
(1, 120000),
(2, 25000),
(3, 500000),
(4, 0),
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

-- Optional: link some users to regimes (user_regimes) and activities (regime_activites)
-- Example: give user 2 the 'Régime Riche en Protéines' if IDs align; adjust IDs as needed.
-- INSERT INTO user_regimes (user_id, regime_id, duree_id, prix_paye) VALUES (2, <regime_id>, 3, 80000);

-- End of seed file

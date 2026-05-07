<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route d'accueil
$routes->get('/', 'Home::index');

// ============================================
// Routes d'INSCRIPTION (2 étapes)
// ============================================
$routes->get('/register', 'Register::index');                    // Étape 1 - Formulaire
$routes->post('/register/store', 'Register::store');            // Étape 1 - Traitement
$routes->get('/register/step2', 'Register::step2');             // Étape 2 - Formulaire
$routes->post('/register/step2/store', 'Register::store2');     // Étape 2 - Traitement

// ============================================
// Routes de LOGIN
// ============================================
// À créer ultérieurement
// $routes->get('/login', 'Login::index');
// $routes->post('/login/authenticate', 'Login::authenticate');
// $routes->get('/logout', 'Login::logout');


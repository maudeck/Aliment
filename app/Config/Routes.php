<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route d'accueil -> inscription (etape 1)
$routes->get('/', 'Register::index');

// Page d'accueil apres inscription
$routes->get('/home', 'Home::index');

// ============================================
// Routes d'INSCRIPTION (2 étapes)
// ============================================
$routes->get('/register', 'Register::index');
$routes->post('/register/store', 'Register::store');
$routes->get('/register/step2', 'Register::step2');
$routes->post('/register/step2/store', 'Register::store2');

// ============================================
// Routes de LOGIN
// ============================================
$routes->get('/login', 'Login::index');
$routes->post('/login/authenticate', 'Login::authenticate');
$routes->get('/logout', 'Login::logout');

$routes->get('/register/objectif', 'Register::objectif');
$routes->post('/register/objectif/store', 'Register::storeObjectif');

$routes->get('/home',          'Home::index');
$routes->post('/home/acheter', 'Home::acheter');
$routes->post('/home/gold',    'Home::devenirGold');
$routes->get('/regimes',       'Regimes::index');
$routes->get('/regimes/export/(:num)', 'Regimes::exportPdf/$1');

// ============================================
// Routes PORTEFEUILLE
// ============================================
$routes->get('/portefeuille',            'PortefeuilleController::index');
$routes->post('/portefeuille/recharger', 'PortefeuilleController::recharger');

$routes->post('home/recharger', 'Home::recharger');

// ============================================
// Routes ADMIN
// ============================================
$routes->get('/admin', 'Admin::index');
$routes->get('/admin/regimes', 'AdminRegimes::index');
$routes->post('/admin/regimes/store', 'AdminRegimes::store');
$routes->get('/admin/activites', 'AdminActivites::index');
$routes->post('/admin/activites/store', 'AdminActivites::store');
$routes->get('/admin/codes', 'AdminCodes::index');
$routes->post('/admin/codes/validate', 'AdminCodes::validateCode');
$routes->get('/admin/settings', 'AdminSettings::index');
$routes->post('/admin/settings/store', 'AdminSettings::store');
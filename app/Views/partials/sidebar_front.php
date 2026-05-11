<?php
$active = $active ?? 'home';
$logoPath = $logo_path ?? 'logo/logo_sans_background.png';
$isGold = $isGold ?? false;

$links = [
    'home' => ['/home', 'Accueil', 'acceuil.png'],
    'objectif' => ['/register/objectif', 'Objectifs', 'objectif.png'],
    'regimes' => ['/regimes', 'Régimes', 'regime.png'],
    'activites' => ['/activites', 'Activités', 'exercice.png'],
    'portefeuille' => ['/portefeuille', 'Portefeuille', 'wallet.png'],
    'gold' => ['/home#gold-offer', 'Option Gold', 'gold.png'],
];

$icons = [
    'home' => 'acceuil.png',
    'objectif' => 'objectif.png',
    'regimes' => 'regime.png',
    'activites' => 'exercice.png',
    'portefeuille' => 'wallet.png',
    'gold' => 'gold.png',
];
?>
<div class="sidebar">
  <div>
    <div class="brand">
      <img src="<?= base_url($logoPath); ?>" alt="Logo" class="brand-logo">
      <div class="brand-text">
        <h2>NutriLife</h2>
        <small>Suivi alimentaire</small>
      </div>
    </div>

    <nav class="nav">
      <?php foreach ($links as $key => [$url, $label, $icon]): ?>
        <a class="nav-link <?= $active === $key ? 'active' : '' ?> <?= $key === 'gold' && $isGold ? 'gold-active' : '' ?>" href="<?= base_url($url); ?>">
          <img src="<?= base_url('icon/' . $icon); ?>" alt="<?= esc($label); ?>" class="nav-icon">
          <span><?= esc($label) ?></span>
        </a>
      <?php endforeach; ?>
    </nav>
  </div>

  <a class="logout" href="<?= base_url('/logout'); ?>">Se déconnecter</a>
</div>

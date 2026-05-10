<?php
$active = $active ?? 'home';
$logoPath = $logo_path ?? 'logo/logo_sans_background.png';

$links = [
    'home' => ['/home', 'Accueil'],
    'objectif' => ['/register/objectif', 'Objectifs'],
    'regimes' => ['/regimes', 'Régimes'],
    'activites' => ['/activites', 'Activités'],
    'portefeuille' => ['/portefeuille', 'Portefeuille'],
    'gold' => ['/home#gold-offer', 'Option Gold'],
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
      <?php foreach ($links as $key => [$url, $label]): ?>
        <a class="nav-link <?= $active === $key ? 'active' : '' ?>" href="<?= base_url($url); ?>"><?= esc($label) ?></a>
      <?php endforeach; ?>
    </nav>
  </div>

  <a class="logout" href="<?= base_url('/logout'); ?>">Se déconnecter</a>
</div>

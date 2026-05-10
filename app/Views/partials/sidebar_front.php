<?php
$active = $active ?? 'home';
$logoPath = $logo_path ?? null;

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
      <?php if (!empty($logoPath)): ?>
        <img src="<?= base_url('logo/logo_sans_background.png'); ?>" alt="Logo" class="brand-logo">
      <?php else: ?>
        <div class="brand-logo-fallback"></div>
      <?php endif; ?>
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

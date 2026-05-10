<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Regimes - NutriLife</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="<?= base_url('css/regimes-page.css'); ?>">
</head>
<body>

  <div class="sidebar">
    <div>
      <div class="brand">
        <img src="<?= base_url('logo/logo_sans_background.png'); ?>" alt="Logo" class="brand-logo">
        <div class="brand-text">
          <h2>NutriLife</h2>
          <small>Suivi alimentaire</small>
        </div>
      </div>

      <nav class="nav">
        <a class="nav-link" href="<?= base_url('/home'); ?>">Accueil</a>
        <a class="nav-link" href="<?= base_url('/register/objectif'); ?>">Objectifs</a>
        <a class="nav-link active" href="<?= base_url('/regimes'); ?>">Regimes</a>
            <a class="nav-link" href="<?= base_url('/activites'); ?>">Activités</a>
        <a class="nav-link" href="<?= base_url('/portefeuille'); ?>">Portefeuille</a>
        <a class="nav-link" href="<?= base_url('/home#gold-offer'); ?>">Option Gold</a>
      </nav>
    </div>

    <a class="logout" href="<?= base_url('/logout'); ?>">Se deconnecter</a>
  </div>

  <div class="main">
    <div class="page-header">
      <div>
        <p class="eyebrow">Mon espace</p>
        <h1>Regimes</h1>
        <p class="subtitle">Vos regimes achetes</p>
      </div>
    </div>

    <div class="hero-card">
      <div class="list-panel visible" id="regimeListPanel">
        <div class="list-panel-header">
          <div>
            <h2>Mes regimes achetes</h2>
            <p>Voici la liste des regimes que vous avez achetes.</p>
          </div>
          <div>
            <a href="<?= base_url('regimes/export/0'); ?>" class="btn-secondary" title="Exporter tous mes regimes en PDF">Exporter en PDF</a>
          </div>
        </div>

        <?php if (empty($regimes)): ?>
          <div class="empty-state">Vous n'avez pas encore de regimes achetes pour le moment.</div>
        <?php else: ?>
          <div class="regime-list">
            <?php foreach ($regimes as $regime): ?>
              <?php
                $regimeNom = (string) ($regime['regime_nom'] ?? '');
                $regimeDescription = (string) ($regime['regime_description'] ?? '');
                $variationPoids = (string) ($regime['variation_poids'] ?? '0');
                $pourcentageViande = (string) ($regime['pourcentage_viande'] ?? '0');
                $pourcentagePoisson = (string) ($regime['pourcentage_poisson'] ?? '0');
                $pourcentageVolaille = (string) ($regime['pourcentage_volaille'] ?? '0');
              ?>
              <div class="regime-item">
                <div class="regime-item-top">
                  <div>
                    <h3><?= esc($regimeNom); ?></h3>
                    <?php if ($regimeDescription !== ''): ?>
                      <p class="regime-desc"><?= esc($regimeDescription); ?></p>
                    <?php endif; ?>
                    <div class="regime-meta">
                      <span class="badge badge-green"><?= esc($variationPoids); ?> kg</span>
                      <span class="badge badge-cream"><?= esc($pourcentageViande); ?>% viande</span>
                      <span class="badge badge-cream"><?= esc($pourcentagePoisson); ?>% poisson</span>
                      <span class="badge badge-cream"><?= esc($pourcentageVolaille); ?>% volaille</span>
                    </div>
                  </div>
                </div>

                <div class="regime-actions"></div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</body>
</html>

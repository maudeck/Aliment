<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Régimes – NutriLife</title>
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
        <a class="nav-link active" href="<?= base_url('/regimes'); ?>">Régimes</a>
        <a class="nav-link" href="<?= base_url('/portefeuille'); ?>">Portefeuille</a>
        <a class="nav-link" href="<?= base_url('/home#gold-offer'); ?>">Option Gold</a>
      </nav>
    </div>

    <a class="logout" href="<?= base_url('/logout'); ?>">Se déconnecter</a>
  </div>

  <div class="main">
    <div class="page-header">
      <div>
        <p class="eyebrow">Mon espace</p>
        <h1>Régimes</h1>
        <p class="subtitle">Vos régimes achetés</p>
      </div>
    </div>

    <div class="hero-card">
      <div class="list-panel visible" id="regimeListPanel">
        <div class="list-panel-header">
          <div>
            <h2>Mes régimes achetés</h2>
            <p>Voici la liste des régimes que vous avez achetés.</p>
          </div>
          <div>
            <a href="<?= base_url('regimes/export/0'); ?>" class="btn-export" title="Exporter tous mes régimes en PDF">↓ Exporter en PDF</a>
          </div>
        </div>

        <?php if (empty($regimes)): ?>
          <div class="empty-state">Vous n'avez pas encore de régimes achetés pour le moment.</div>
        <?php else: ?>
          <div class="regime-list">
            <?php foreach ($regimes as $regime): ?>
              <div class="regime-item">
                <div class="regime-item-top">
                  <div>
                    <h3><?= esc($regime['regime_nom']); ?></h3>
                    <?php if (!empty($regime['regime_description'])): ?>
                      <p class="regime-desc"><?= esc($regime['regime_description']); ?></p>
                    <?php endif; ?>
                    <div class="regime-meta">
                      <span class="badge badge-green"><?= esc($regime['variation_poids']); ?> kg</span>
                      <span class="badge badge-cream"><?= esc($regime['pourcentage_viande']); ?>% viande</span>
                      <span class="badge badge-cream"><?= esc($regime['pourcentage_poisson']); ?>% poisson</span>
                      <span class="badge badge-cream"><?= esc($regime['pourcentage_volaille']); ?>% volaille</span>
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
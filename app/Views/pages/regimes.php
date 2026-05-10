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
  <?= view('partials/sidebar_front', ['active' => 'regimes', 'logo_path' => $logo_path ?? null]); ?>

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

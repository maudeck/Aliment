<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mes Régimes – NutriLife</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="stylesheet" href="<?= base_url('css/regimes.css'); ?>">

</head>

<body>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <div>
      <div class="brand">
        <div class="brand-logo-fallback"></div>
        <div class="brand-text">
          <h2>NutriLife</h2>
          <small>Suivi alimentaire</small>
        </div>
      </div>

      <nav class="nav">
        <a class="nav-link" href="<?= base_url('/home'); ?>">Accueil</a>
        <a class="nav-link" href="<?= base_url('/register/objectif'); ?>">Objectifs</a>
        <a class="nav-link active" href="<?= base_url('/regimes'); ?>">Régimes</a>
        <a class="nav-link" href="#">Activités</a>
        <a class="nav-link" href="#">Portefeuille</a>
        <a class="nav-link" href="#">Option Gold</a>
      </nav>
    </div>

    <a class="logout" href="<?= base_url('/logout'); ?>">Se déconnecter</a>
  </div>

  <!-- MAIN -->
  <div class="main">

    <div class="page-header">
      <div>
        <p class="eyebrow">Mon espace</p>
        <h1>Mes Régimes</h1>
        <p>Tous vos régimes achetés avec leurs informations complètes.</p>
      </div>
    </div>

    <?php if (empty($regimes)): ?>

      <div class="empty-state">
        <h2>Aucun régime acheté</h2>
        <p>Vous n'avez pas encore acheté de régime. Retournez à l'accueil pour en choisir un.</p>
        <a href="<?= base_url('/home'); ?>" class="btn-primary">Voir les régimes recommandés</a>
      </div>

    <?php else: ?>

      <?php foreach ($regimes as $regime): ?>
        <div class="regime-card">

          <!-- HEADER -->
          <div class="regime-card-header">
            <div>
              <h2><?= esc($regime['regime_nom']); ?></h2>
              <?php if (!empty($regime['regime_description'])): ?>
                <p style="color:var(--text-muted); font-size:14px; margin-top:4px;">
                  <?= esc($regime['regime_description']); ?>
                </p>
              <?php endif; ?>
              <div class="regime-meta">
                <span class="badge badge-green">
                  <?= $regime['variation_poids'] > 0 ? '+' : ''; ?><?= esc($regime['variation_poids']); ?> kg
                </span>
                <span class="badge badge-cream">
                  <?= esc($regime['duree_nom']); ?> (<?= esc($regime['nombre_jours']); ?> jours)
                </span>
              </div>
            </div>
            <div class="regime-price-paid">
              <small>Prix payé</small>
              <strong><?= number_format($regime['prix_paye'], 0, ',', ' '); ?> Ar</strong>
            </div>
          </div>

          <!-- INFO GRID -->
          <div class="info-grid">

            <!-- NUTRITION -->
            <div class="info-block">
              <h3>Répartition nutritionnelle</h3>
              <div class="nutrition-row">
                <div class="nutrition-item">
                  <p>Viande</p>
                  <h4><?= esc($regime['pourcentage_viande']); ?>%</h4>
                </div>
                <div class="nutrition-item">
                  <p>Poisson</p>
                  <h4><?= esc($regime['pourcentage_poisson']); ?>%</h4>
                </div>
                <div class="nutrition-item">
                  <p>Volaille</p>
                  <h4><?= esc($regime['pourcentage_volaille']); ?>%</h4>
                </div>
              </div>
            </div>

            <!-- ACTIVITES -->
            <div class="info-block">
              <h3>Activités recommandées</h3>
              <?php if (!empty($regime['activites'])): ?>
                <?php foreach ($regime['activites'] as $act): ?>
                  <div class="activite-item">
                    <div>
                      <p class="activite-nom"><?= esc($act['nom']); ?></p>
                      <?php if (!empty($act['description'])): ?>
                        <p class="activite-desc"><?= esc($act['description']); ?></p>
                      <?php endif; ?>
                    </div>
                    <?php if (!empty($act['calories_brulees_heure'])): ?>
                      <span class="cal-badge"><?= esc($act['calories_brulees_heure']); ?> kcal/h</span>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p style="color:var(--text-muted); font-size:14px;">Aucune activité liée.</p>
              <?php endif; ?>
            </div>

          </div>

          <!-- FOOTER -->
          <div class="regime-card-footer">
            <span class="date-achat">
              Acheté le <?= date('d/m/Y à H:i', strtotime($regime['date_achat'])); ?>
            </span>
            <button class="btn-export" disabled title="Disponible prochainement">
              ↓ Exporter en PDF
            </button>
          </div>

        </div>
      <?php endforeach; ?>

    <?php endif; ?>

  </div>

</body>
</html>
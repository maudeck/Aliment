<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Activités sportives – NutriLife</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('css/home.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('css/activites.css'); ?>">
</head>

<body>
  <?= view('partials/sidebar_front', ['active' => 'activites', 'logo_path' => $logo_path ?? null]); ?>

  <!-- MAIN -->
  <div class="main">

    <div class="page-header">
      <div>
        <p class="eyebrow">Sport & bien-être</p>
        <h1>Activités sportives</h1>
        <p class="subtitle">Les exercices recommandés selon vos régimes et objectifs.</p>
      </div>
    </div>

    <div class="activites-page">

      <?php if (!empty($activitesPar)): ?>
        <p class="section-title">Activités liées à vos régimes</p>

        <?php foreach ($activitesPar as $regimeId => $group): ?>
          <div class="regime-group">
            <div class="regime-header">
              <span class="regime-badge">Régime</span>
              <span class="regime-name"><?= esc((string) ($group['regime']['nom'] ?? '')) ?></span>
              <?php if ($group['regime']['achete']): ?>
                <span class="regime-status">✓ Acheté</span>
              <?php endif; ?>
            </div>

            <div class="acts-grid">
              <?php foreach ($group['activites'] as $act): ?>
                <?php
                  $cal = (int) $act['calories_brulees_heure'];
                  $intensity = $cal < 300 ? 'low' : ($cal < 500 ? 'mid' : 'high');
                ?>
                <div class="act-card">
                  <div class="act-name"><?= esc((string) ($act['nom'] ?? '')) ?></div>
                  <div class="act-desc"><?= esc((string) ($act['description'] ?? '')) ?></div>
                  <?php if ($cal): ?>
                    <span class="act-cal"><?= $cal ?> kcal/h</span>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>

      <?php else: ?>
        <div class="empty-state">
          <p>Aucune activité liée à vos régimes pour le moment.</p>
          <p style="margin-top:8px"><a href="<?= base_url('/register/objectif') ?>">Choisissez un objectif</a> pour voir les activités recommandées.</p>
        </div>
      <?php endif; ?>

      <!-- Catalogue complet -->
      <p class="section-title">Catalogue complet des activités</p>

      <?php if (!empty($toutesActivites)): ?>
        <table class="catalogue-table">
          <thead>
            <tr>
              <th>Activité</th>
              <th>Description</th>
              <th>Calories / heure</th>
              <th>Intensité</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($toutesActivites as $act): ?>
            <?php
              $cal = (int) $act['calories_brulees_heure'];
              $intensity = $cal < 300 ? 'low' : ($cal < 500 ? 'mid' : 'high');
              $intensityLabel = $cal < 300 ? 'Légère' : ($cal < 500 ? 'Modérée' : 'Intense');
              $intensityIcon = $cal < 300 ? '🟢' : ($cal < 500 ? '🟡' : '🔴');
            ?>
            <tr>
              <td><strong><?= esc((string) ($act['nom'] ?? '')) ?></strong></td>
              <td style="color:var(--text-muted);font-size:0.82rem"><?= esc((string) ($act['description'] ?? '—')) ?></td>
              <td>
                <?php if ($cal): ?>
                  <span class="cal-pill">🔥 <?= $cal ?> kcal/h</span>
                <?php else: ?>
                  <span style="color:var(--text-muted)">—</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="intensity-<?= $intensity ?>"><?= $intensityIcon ?> <?= $intensityLabel ?></span>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="empty-state">
          <p>Aucune activité dans le catalogue.</p>
        </div>
      <?php endif; ?>

    </div>
  </div>

</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Accueil – NutriLife</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="<?= base_url('css/home.css'); ?>">
 

  <style>
   
  </style>
</head>

<body>

   <!-- SIDEBAR -->
  <div class="sidebar">
    <div>
      <div class="brand">
        <?php if (!empty($logo_path)): ?>
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
        <a class="nav-link active" href="<?= base_url('/home'); ?>">Accueil</a>
        <a class="nav-link" href="<?= base_url('/register/objectif'); ?>">Objectifs</a>
        <a class="nav-link" href="<?= base_url('/regimes'); ?>">Régimes</a>
        <a class="nav-link" href="#">Activités</a>
        <a class="nav-link" href="#">Portefeuille</a>
        <a class="nav-link" href="#">Option Gold</a>
      </nav>
    </div>
 
    <a class="logout" href="<?= base_url('/logout'); ?>">Se déconnecter</a>
  </div>
 
  <!-- MAIN -->
  <div class="main">
 
    <!-- HEADER -->
    <div class="page-header">
      <div>
        <p class="eyebrow">Bonjour,</p>
        <h1><?= isset($user['nom']) ? htmlspecialchars($user['nom']) : 'Utilisateur'; ?></h1>
        <p class="subtitle">Votre plan de suivi alimentaire est prêt.</p>
      </div>
      <div class="status-pill">
        Objectif : <strong><?= !empty($objectifNom) ? htmlspecialchars($objectifNom) : 'Non défini'; ?></strong>
      </div>
    </div>
 
    <?php if (session()->getFlashdata('succes')): ?>
      <div class="flash-succes"><?= session()->getFlashdata('succes'); ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('erreur')): ?>
      <div class="flash-erreur"><?= session()->getFlashdata('erreur'); ?></div>
    <?php endif; ?>
 
    <!-- STATS -->
    <div class="stats">
      <div class="stat-card">
        <p class="stat-label">Poids actuel</p>
        <p class="stat-value">
          <?= isset($etat['poids']) ? htmlspecialchars($etat['poids']) : 'N/A'; ?>
          <span>kg</span>
        </p>
      </div>
      <div class="stat-card">
        <p class="stat-label">Taille</p>
        <p class="stat-value">
          <?= isset($etat['taille']) ? htmlspecialchars($etat['taille']) : 'N/A'; ?>
          <span>m</span>
        </p>
      </div>
      <div class="stat-card">
        <p class="stat-label">IMC</p>
        <p class="stat-value">
          <?= isset($etat['imc']) ? number_format($etat['imc'], 1) : 'N/A'; ?>
        </p>
      </div>
    </div>
 
    <!-- RÉGIME RECOMMANDÉ + GOLD -->
    <div class="recommendation">
 
      <div class="regime-box">
        <h2>Régime recommandé</h2>
        <p>Basé sur votre IMC et votre objectif.</p>
 
        <?php if (!empty($regimes)): ?>
          <?php $r = $regimes[0]; ?>
          <div class="regime-detail">
            <h2><?= esc($r['nom']); ?></h2>
            <p>
              <?= $r['variation_poids'] > 0 ? '+' : ''; ?><?= esc($r['variation_poids']); ?> kg
              <?= !empty($r['duree']) ? 'en ' . esc($r['duree']) : ''; ?>
            </p>
 
            <div class="nutrition">
              <div>
                <p>Viande</p>
                <h3><?= esc($r['pourcentage_viande']); ?>%</h3>
              </div>
              <div>
                <p>Poisson</p>
                <h3><?= esc($r['pourcentage_poisson']); ?>%</h3>
              </div>
              <div>
                <p>Volaille</p>
                <h3><?= esc($r['pourcentage_volaille']); ?>%</h3>
              </div>
            </div>
 
            <?php if (!empty($r['prix'])): ?>
              <p class="regime-price"><?= number_format($r['prix'], 0, ',', ' '); ?> Ar</p>
            <?php endif; ?>
 
            <div class="buttons">
              <?php if ($r['achete']): ?>
                <button class="btn-validated" disabled>✓ Achat validé</button>
              <?php else: ?>
                <form method="POST" action="<?= base_url('/home/acheter'); ?>">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="regime_id" value="<?= $r['id']; ?>">
                  <input type="hidden" name="duree_id"  value="3">
                  <button type="submit" class="btn-green">Acheter</button>
                </form>
              <?php endif; ?>
              <a href="<?= base_url('/regimes'); ?>" class="btn-white">Voir régimes</a>
            </div>
          </div>
        <?php else: ?>
          <p class="empty">Aucun régime disponible pour le moment.</p>
        <?php endif; ?>
      </div>
 
      <!-- GOLD -->
      <div class="gold">
        <small>Option Gold</small>
        <h2>−15% sur tous les régimes</h2>
        <p>Passez à l'offre Gold pour bénéficier de réductions exclusives.</p>
        <div class="gold-price">
          <small>Prix</small>
          <h1>120 000 Ar</h1>
        </div>
        <button>Devenir Gold</button>
      </div>
 
    </div>
 
    <!-- ACTIVITÉS + PORTEFEUILLE -->
    <div class="bottom">
 
      <div class="activity">
        <h2>Activités recommandées</h2>
        <?php if (!empty($activites)): ?>
          <?php foreach ($activites as $activite): ?>
            <div class="activity-item">
              <strong><?= esc($activite['nom']); ?></strong>
              <?php if (!empty($activite['description'])): ?>
                <p class="activity-desc"><?= esc($activite['description']); ?></p>
              <?php endif; ?>
              <?php if (!empty($activite['calories_brulees_heure'])): ?>
                <span class="activity-cal"><?= esc($activite['calories_brulees_heure']); ?> kcal/h</span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="empty">Aucune activité disponible pour ce régime.</p>
        <?php endif; ?>
      </div>
 
      <div class="wallet">
        <h2>Portefeuille</h2>
        <div class="wallet-balance">
          <small>Solde</small>
          <h1>
            <?= !empty($solde) ? number_format($solde, 0, ',', ' ') . ' Ar' : '0 Ar'; ?>
          </h1>
        </div>
        <input type="text" placeholder="Entrer un code">
        <button>Ajouter de l'argent</button>
      </div>
 
    </div>
 
    <!-- AUTRES RÉGIMES -->
    <?php if (!empty($regimes) && count($regimes) > 1): ?>
    <div class="all-regimes">
      <h2>Autres régimes compatibles</h2>
      <div class="regime-list">
        <?php foreach (array_slice($regimes, 1) as $regime): ?>
          <div class="regime-card">
            <h3><?= esc($regime['nom']); ?></h3>
            <p>
              <?= $regime['variation_poids'] > 0 ? '+' : ''; ?><?= esc($regime['variation_poids']); ?> kg
              <?= !empty($regime['duree']) ? 'en ' . esc($regime['duree']) : ''; ?>
            </p>
            <?php if (!empty($regime['prix'])): ?>
              <p class="price"><?= number_format($regime['prix'], 0, ',', ' '); ?> Ar</p>
            <?php endif; ?>
            <?php if ($regime['achete']): ?>
              <button class="btn-validated" disabled>✓ Achat validé</button>
            <?php else: ?>
              <form method="POST" action="<?= base_url('/home/acheter'); ?>">
                <?= csrf_field(); ?>
                <input type="hidden" name="regime_id" value="<?= $regime['id']; ?>">
                <input type="hidden" name="duree_id"  value="3">
                <button type="submit" class="btn-green" style="width:100%">Acheter</button>
              </form>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
 
  </div><!-- /main -->

</body>
</html>

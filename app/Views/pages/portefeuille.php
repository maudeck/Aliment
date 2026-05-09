<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portefeuille – NutriLife</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="<?= base_url('css/wallet.css'); ?>">
</head>

<body>

  <!-- SIDEBAR -->
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
        <a class="nav-link" href="<?= base_url('/regimes'); ?>">Régimes</a>
        <a class="nav-link" href="<?= base_url('/activites'); ?>">Activités</a>
        <a class="nav-link active" href="<?= base_url('/portefeuille'); ?>">Portefeuille</a>
        <a class="nav-link" href="<?= base_url('/home#gold-offer'); ?>">Option Gold</a>
      </nav>
    </div>

    <a class="logout" href="<?= base_url('/logout'); ?>">Se déconnecter</a>
  </div>

  <!-- MAIN -->
  <div class="main">

    <!-- HEADER -->
    <div class="page-header">
      <div>
        <p class="eyebrow">Finance</p>
        <h1>Mon Portefeuille</h1>
        <p class="subtitle">Gérez votre solde et rechargez via un code.</p>
      </div>
    </div>

    <div class="wallet-page">

      <!-- Flash messages -->
      <?php if (session()->getFlashdata('succes')): ?>
        <div class="flash-succes"><?= session()->getFlashdata('succes'); ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('erreur')): ?>
        <div class="flash-erreur"><?= session()->getFlashdata('erreur'); ?></div>
      <?php endif; ?>

      <!-- Solde -->
      <div class="solde-card">
        <div class="solde-info">
          <small>Solde disponible</small>
          <h1><?= number_format($solde, 0, ',', ' '); ?> Ar</h1>
        </div>
        <div class="solde-icon">💰</div>
      </div>

      <!-- Recharge -->
      <div class="recharge-card">
        <h2>Recharger mon portefeuille</h2>
        <p>Entrez un code de recharge pour créditer votre solde instantanément.</p>

        <form method="POST" action="<?= base_url('/portefeuille/recharger'); ?>" class="code-form">
          <?= csrf_field(); ?>
          <input
            type="text"
            name="code"
            placeholder="Ex : NUTRI-2024-XXXX"
            maxlength="50"
            autocomplete="off"
            required
          >
          <button type="submit">Ajouter</button>
        </form>
      </div>

      <!-- Historique des recharges -->
      <div class="history-card">
        <h2>Historique des recharges</h2>

        <?php if (empty($historique)): ?>
          <div class="history-empty">
            <p>Aucune recharge effectuée pour le moment.</p>
          </div>
        <?php else: ?>
          <table class="history-table">
            <thead>
              <tr>
                <th>Code utilisé</th>
                <th>Montant</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($historique as $h): ?>
                <tr>
                  <td><span class="badge-code"><?= esc($h['code']); ?></span></td>
                  <td><span class="montant-plus">+<?= number_format($h['montant'], 0, ',', ' '); ?> Ar</span></td>
                  <td><span class="history-date"><?= date('d/m/Y à H:i', strtotime($h['created_at'])); ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

      <!-- Historique des achats de régimes -->
      <div class="history-card">
        <h2>Historique des achats de régimes</h2>

        <?php if (empty($achatsRegimes)): ?>
          <div class="history-empty">
            <p>Aucun régime acheté pour le moment.</p>
          </div>
        <?php else: ?>
          <table class="history-table">
            <thead>
              <tr>
                <th>Régime</th>
                <th>Durée</th>
                <th>Prix payé</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($achatsRegimes as $achat): ?>
                <tr>
                  <td><strong><?= esc($achat['regime_nom']); ?></strong></td>
                  <td><?= esc($achat['duree_nom']); ?></td>
                  <td>
                    <span class="montant-moins">
                      −<?= number_format($achat['prix_paye'], 0, ',', ' '); ?> Ar
                    </span>
                  </td>
                  <td>
                    <span class="history-date">
                      <?= !empty($achat['date_achat']) ? date('d/m/Y à H:i', strtotime($achat['date_achat'])) : '—'; ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

    </div><!-- /wallet-page -->

  </div><!-- /main -->

</body>
</html>
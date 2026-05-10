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
        <p class="subtitle">Choisissez une action, puis affichez la liste des régimes si nécessaire.</p>
      </div>
    </div>

    <div class="hero-card">
      <div class="action-grid">
        <button type="button" class="action-card" id="toggleAddBtn">
          <div class="action-icon">➕</div>
          <div class="action-title">Ajouter un régime</div>
          <div class="action-text">Créer un nouveau régime avec sa description, sa variation de poids et sa composition.</div>
        </button>

        <button type="button" class="action-card" id="toggleListBtn">
          <div class="action-icon">📋</div>
          <div class="action-title">Afficher la liste regime</div>
          <div class="action-text">La liste des régimes reste cachée jusqu’à votre clic.</div>
        </button>
      </div>

      <!-- Formulaire d'ajout -->
      <div class="list-panel" id="regimeAddPanel" style="display:none;">
        <div class="list-panel-header">
          <div>
            <h2>Ajouter un régime</h2>
            <p>Remplissez le formulaire pour ajouter un nouveau régime.</p>
          </div>
        </div>
        <form method="post" action="<?= base_url('regimes/store') ?>">
          <?= csrf_field() ?>
          <?php if(isset($validation)): ?>
            <div class="flash-erreur" style="margin-bottom:16px;">
              <?= $validation->listErrors() ?>
            </div>
          <?php endif; ?>
          <div class="form-group">
            <label for="nom">Nom du régime</label>
            <input type="text" class="form-control" name="nom" id="nom" value="<?= old('nom') ?>" required>
          </div>

          <!-- FOOTER -->
          <div class="regime-card-footer">
            <span class="date-achat">
            </span>
            <a href="base_url('/regimes/export/' class="btn-export" title="Exporter en PDF">
              ↓ Exporter en PDF
            </a>
          </div>
          <div class="form-group">
            <label for="pourcentage_viande">% Viande</label>
            <input type="number" step="0.01" class="form-control" name="pourcentage_viande" id="pourcentage_viande" value="<?= old('pourcentage_viande') ?>">
          </div>
          <div class="form-group">
            <label for="pourcentage_poisson">% Poisson</label>
            <input type="number" step="0.01" class="form-control" name="pourcentage_poisson" id="pourcentage_poisson" value="<?= old('pourcentage_poisson') ?>">
          </div>
          <div class="form-group">
            <label for="pourcentage_volaille">% Volaille</label>
            <input type="number" step="0.01" class="form-control" name="pourcentage_volaille" id="pourcentage_volaille" value="<?= old('pourcentage_volaille') ?>">
          </div>
          <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
      </div>

      <div class="list-panel" id="regimeListPanel">
        <div class="list-panel-header">
          <div>
            <h2>Liste des régimes</h2>
            <p>Chaque régime est affiché avec les actions modifier et supprimer.</p>
          </div>
        </div>

        <?php if (empty($regimes)): ?>
          <div class="empty-state">Aucun régime enregistré pour le moment.</div>
        <?php else: ?>
          <div class="regime-list">
            <?php foreach ($regimes as $regime): ?>
              <div class="regime-item">
                <div class="regime-item-top">
                  <div>
                    <h3><?= esc($regime['nom']); ?></h3>
                    <?php if (!empty($regime['description'])): ?>
                      <p class="regime-desc"><?= esc($regime['description']); ?></p>
                    <?php endif; ?>
                    <div class="regime-meta">
                      <span class="badge badge-green"><?= esc($regime['variation_poids']); ?> kg</span>
                      <span class="badge badge-cream"><?= esc($regime['pourcentage_viande']); ?>% viande</span>
                      <span class="badge badge-cream"><?= esc($regime['pourcentage_poisson']); ?>% poisson</span>
                      <span class="badge badge-cream"><?= esc($regime['pourcentage_volaille']); ?>% volaille</span>
                    </div>
                  </div>
                </div>

                <div class="regime-actions">
                  <a href="<?= base_url('regimes/edit/' . $regime['id']); ?>" class="btn-secondary">Modifier</a>
                  <a href="<?= base_url('regimes/delete/' . $regime['id']); ?>" class="btn-danger" onclick="return confirm('Supprimer ce régime ?');">Supprimer</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    const toggleListBtn = document.getElementById('toggleListBtn');
    const regimeListPanel = document.getElementById('regimeListPanel');
    const toggleAddBtn = document.getElementById('toggleAddBtn');
    const regimeAddPanel = document.getElementById('regimeAddPanel');

    toggleListBtn.addEventListener('click', () => {
      regimeListPanel.style.display = regimeListPanel.style.display === 'block' ? 'none' : 'block';
      toggleListBtn.classList.toggle('active');
    });
    toggleAddBtn.addEventListener('click', () => {
      regimeAddPanel.style.display = regimeAddPanel.style.display === 'block' ? 'none' : 'block';
      toggleAddBtn.classList.toggle('active');
    });
  </script>
</body>
</html>
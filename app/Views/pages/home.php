<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Accueil – NutriLife</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="<?= base_url('css/home.css'); ?>">
</head>

<body>
  <?= view('partials/sidebar_front', ['active' => 'home', 'logo_path' => $logo_path ?? null]); ?>

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
                <form method="POST" action="<?= base_url('/home/acheter'); ?>" class="ajax-home-form ajax-buy-form" data-regime-id="<?= $r['id']; ?>">
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
      <div class="gold" id="gold-offer">
        <small>Option Gold</small>
        <h2>−15% sur tous les régimes</h2>
        <p>Passez à l'offre Gold pour bénéficier de réductions exclusives.</p>
        <div class="gold-price">
          <small>Prix</small>
          <h1>120 000 Ar</h1>
        </div>
        <?php if (!empty($isGold)): ?>
          <button class="btn-validated" disabled>Gold actif</button>
        <?php else: ?>
          <form method="POST" action="<?= base_url('/home/gold'); ?>" class="ajax-home-form ajax-gold-form">
            <?= csrf_field(); ?>
            <button type="submit">Devenir Gold</button>
          </form>
        <?php endif; ?>
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

      <!-- PORTEFEUILLE (fonctionnel) -->
      <div class="wallet">
        <h2>Portefeuille</h2>
        <div class="wallet-balance">
          <small>Solde</small>
          <h1>
            <?= !empty($solde) ? number_format($solde, 0, ',', ' ') . ' Ar' : '0 Ar'; ?>
          </h1>
        </div>

        <?php if (session()->getFlashdata('wallet_succes')): ?>
          <div class="flash-succes" style="margin: 8px 0; font-size: 0.85rem;">
            <?= session()->getFlashdata('wallet_succes'); ?>
          </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('wallet_erreur')): ?>
          <div class="flash-erreur" style="margin: 8px 0; font-size: 0.85rem;">
            <?= session()->getFlashdata('wallet_erreur'); ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('/home/recharger'); ?>" class="ajax-home-form ajax-wallet-form">
          <?= csrf_field(); ?>
          <input type="text" name="code" placeholder="Entrer un code" autocomplete="off" required>
          <button type="submit">Ajouter de l'argent</button>
        </form>

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
                <form method="POST" action="<?= base_url('/home/acheter'); ?>" class="ajax-home-form ajax-buy-form" data-regime-id="<?= $regime['id']; ?>">
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

  <script>
    (function () {
      const forms = document.querySelectorAll('.ajax-home-form');

      function escapeHtml(value) {
        return String(value)
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;');
      }

      function showNotice(target, message, type) {
        const box = document.createElement('div');
        box.className = type === 'success' ? 'flash-succes' : 'flash-erreur';
        box.style.marginTop = '10px';
        box.textContent = message;
        target.parentNode.insertBefore(box, target);
        setTimeout(() => box.remove(), 3000);
      }

      function setSubmitting(form, submitting) {
        const btn = form.querySelector('button[type="submit"]');
        if (!btn) return;
        if (submitting) {
          btn.dataset.originalText = btn.textContent;
          btn.disabled = true;
          btn.textContent = 'Chargement...';
        } else {
          btn.disabled = false;
          if (btn.dataset.originalText) {
            btn.textContent = btn.dataset.originalText;
          }
        }
      }

      forms.forEach((form) => {
        form.addEventListener('submit', async function (e) {
          e.preventDefault();
          setSubmitting(form, true);

          try {
            const response = await fetch(form.action, {
              method: 'POST',
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: new FormData(form)
            });

            const payload = await response.json();

            if (!payload.success) {
              throw new Error(payload.message || 'Une erreur est survenue.');
            }

            if (form.classList.contains('ajax-wallet-form')) {
              const walletCard = form.closest('.wallet');
              if (walletCard) {
                const balance = walletCard.querySelector('.wallet-balance h1');
                if (balance && payload.data && typeof payload.data.solde !== 'undefined') {
                  balance.textContent = `${Number(payload.data.solde).toLocaleString('fr-FR')} Ar`;
                }
              }
              form.reset();
            }

            if (form.classList.contains('ajax-gold-form')) {
              showNotice(form, payload.message || 'Opération réussie.', 'success');
              form.outerHTML = '<button class="btn-validated" disabled>Gold actif</button>';
              return;
            }

            if (form.classList.contains('ajax-buy-form')) {
              showNotice(form, payload.message || 'Opération réussie.', 'success');
              form.outerHTML = '<button class="btn-validated" disabled>✓ Achat validé</button>';
              return;
            }

            showNotice(form, payload.message || 'Opération réussie.', 'success');
          } catch (error) {
            showNotice(form, escapeHtml(error.message || 'Erreur réseau.'), 'error');
          } finally {
            if (document.body.contains(form)) {
              setSubmitting(form, false);
            }
          }
        });
      });
    })();
  </script>

</body>
</html>
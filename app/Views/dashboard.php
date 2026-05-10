<?= view('partials/header') ?>
<?= view('partials/sidebar') ?>

<main class="nl-main">
  <div class="nl-topbar">
    <h1>Tableau de bord</h1>
    <div class="nl-actions">
      <a class="btn btn--ghost" href="/regimes">Gestion des régimes</a>
      <a class="btn btn--primary" href="/regimes/export/0">Exporter PDF</a>
    </div>
  </div>

  <div class="nl-grid">
    <section class="col-8">
      <div class="card">
        <div class="kpi">
          <div>
            <div class="label">Utilisateurs actifs</div>
            <div class="value">1,248</div>
          </div>
          <div>
            <div class="label">Régimes en cours</div>
            <div class="value">24</div>
          </div>
          <div>
            <div class="label">NPS</div>
            <div class="value">+42</div>
          </div>
        </div>
      </div>

      <div class="card" style="margin-top:18px">
        <div class="chart-placeholder">Graphique — Aperçu des calories</div>
      </div>

      <div class="card" style="margin-top:18px">
        <h2 style="margin:0 0 8px 0;font-size:16px">Régimes recommandés</h2>
        <div class="regime-list">
          <?= view('components/regime_card', ['title'=>'Régime Équilibré','meta'=>'30 jours • Débutant']) ?>
          <?= view('components/regime_card', ['title'=>'Détox Léger','meta'=>'7 jours • Tous niveaux']) ?>
          <?= view('components/regime_card', ['title'=>'Performance Pro','meta'=>'60 jours • Avancé']) ?>
        </div>
      </div>
    </section>

    <aside class="col-4">
      <div class="card">
        <h3 style="margin-top:0">Résumé rapide</h3>
        <p style="color:rgba(11,11,11,0.6);margin:8px 0 0 0">Aperçu des indicateurs clefs et activités récentes.</p>
        <div style="margin-top:12px">
          <?= view('components/image_placeholder', ['size'=>'small']) ?>
        </div>
      </div>
    </aside>
  </div>

</main>

</div> <!-- .nl-app -->
</body>
</html>

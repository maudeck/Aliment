<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Gestion des Régimes'); ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/admin-regimes.css'); ?>">
    <style>
        .alert { padding: 12px 15px; border-radius: 4px; margin-bottom: 15px; font-weight: 500; }
        .alert.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .activity-row { display: grid; grid-template-columns: 1fr auto auto; gap: 8px; align-items: center; margin-bottom: 8px; }
        .activity-row select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-small { padding: 6px 12px; font-size: 0.875rem; border: none; border-radius: 4px; cursor: pointer; }
        .btn-small.primary { background: #007bff; color: white; }
        .btn-small.secondary { background: #6c757d; color: white; }
        .btn-small.danger { background: #dc3545; color: white; }
        .admin-button { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; }
        .admin-button.primary { background: #007bff; color: white; }
        .admin-button.secondary { background: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="admin-shell">
        <?= view('partials/admin_sidebar'); ?>

        <main class="admin-content">
            <section class="admin-hero">
                <div>
                    <h2>Gestion des Régimes</h2>
                    <p>Créez et préparez les régimes avec leurs objectifs, macros et activités associées.</p>
                </div>
                <div class="admin-pill">CRUD Régimes</div>
            </section>

            <section class="admin-grid">
                <article class="admin-card">
                    <h3>Créer un régime</h3>
                    <form id="regimeForm" class="admin-form" method="post" action="<?= base_url('/admin/regimes/store'); ?>">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="name">Nom du régime *</label>
                            <input id="name" name="name" type="text" placeholder="ex: Régime énergie" required>
                        </div>

                        <div class="form-group">
                            <label for="objectif">Objectif *</label>
                            <input id="objectif" name="objectif" type="text" placeholder="ex: Perdre du poids" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Variation de poids (kg) *</label>
                            <input id="price" name="price" type="number" step="0.01" placeholder="ex: 2.5" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description détaillée *</label>
                            <textarea id="description" name="description" rows="3" placeholder="Décrivez le régime en détail..." required></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="proteines">Viande (%)</label>
                                <input id="proteines" name="proteines" type="number" value="30" step="0.01" min="0" max="100">
                            </div>
                            <div class="form-group">
                                <label for="glucides">Poisson (%)</label>
                                <input id="glucides" name="glucides" type="number" value="40" step="0.01" min="0" max="100">
                            </div>
                            <div class="form-group">
                                <label for="lipides">Volaille (%)</label>
                                <input id="lipides" name="lipides" type="number" value="30" step="0.01" min="0" max="100">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="activities">Activités associées</label>
                            <textarea id="activities" name="activities" rows="3" placeholder="Ex: Musculation, Cardio léger, Yoga"></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="admin-button primary">Enregistrer</button>
                            <button type="reset" class="admin-button secondary">Réinitialiser</button>
                        </div>
                    </form>
                </article>

                <article class="admin-card full">
                    <h3>Repères de saisie</h3>
                    <p class="text-muted">La page a été réorganisée pour rester à droite du sidebar et ne plus tomber sous celui-ci. Les champs ci-dessus correspondent au contrôleur actuel.</p>
                    <div class="admin-list" style="margin-top:16px;">
                        <div class="admin-list-item">
                            <div>
                                <strong>Nom et objectif</strong>
                                <small>Utilisés pour identifier rapidement le régime.</small>
                            </div>
                        </div>
                        <div class="admin-list-item">
                            <div>
                                <strong>Poids et macros</strong>
                                <small>Variation de poids, viande, poisson et volaille.</small>
                            </div>
                        </div>
                        <div class="admin-list-item">
                            <div>
                                <strong>Activités</strong>
                                <small>Liste textuelle des activités associées au régime.</small>
                            </div>
                        </div>
                    </div>
                </article>
            </section>
        </main>
    </div>

    <script>
    </script>
    </script>
</body>
</html>

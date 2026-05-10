<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/admin-regimes.css'); ?>">
</head>
<body>
    <?php $activites = $activites ?? []; ?>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <div class="admin-brand-mark"></div>
                <div>
                    <h1>NutriLife Admin</h1>
                    <small>Gestion du système</small>
                </div>
            </div>

            <nav class="admin-nav">
                <a href="<?= base_url('/admin'); ?>">
                    <strong>Tableau de bord</strong>
                    <span>Aperçu général des modules admin</span>
                </a>
                <a href="<?= base_url('/admin/regimes'); ?>">
                    <strong>CRUD Régimes</strong>
                    <span>Créer, lire, modifier, supprimer les régimes</span>
                </a>
                <a href="<?= base_url('/admin/activites'); ?>" class="active">
                    <strong>CRUD Activités sportives</strong>
                    <span>Gérer les activités liées aux régimes</span>
                </a>
                <a href="<?= base_url('/admin/codes'); ?>">
                    <strong>Validation des codes</strong>
                    <span>Contrôler les recharges du portefeuille</span>
                </a>
                <a href="<?= base_url('/admin/settings'); ?>">
                    <strong>CRUD Paramètres</strong>
                    <span>Gérer les données de référence et réglages</span>
                </a>
            </nav>

            <div class="admin-footer">
                <a class="admin-logout" href="<?= base_url('/logout'); ?>">Se déconnecter</a>
            </div>
        </aside>

        <main class="admin-content">
            <section class="admin-hero">
                <div>
                    <h2>Gestion des Activités Sportives</h2>
                    <p>Créer, modifier et supprimer les activités sportives associées aux régimes.</p>
                </div>
            </section>

            <section class="admin-grid">
                <article class="admin-card full">
                    <h3>Ajouter / Modifier une activité</h3>

                    <?php if (!empty($flash_success)): ?>
                        <div class="flash-ok"><?= esc($flash_success); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($flash_error)): ?>
                        <div class="flash-err"><?= esc($flash_error); ?></div>
                    <?php endif; ?>

                    <form id="activityForm" class="admin-form" method="post" action="<?= base_url('/admin/activites/store'); ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" id="activityId" name="activity_id">
                        <div class="form-group">
                            <label for="nom">Nom de l'activité *</label>
                            <input type="text" id="nom" name="nom" required placeholder="Ex: Course à pied">
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" rows="4" required placeholder="Décrivez l'activité..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="calories_brulees_heure">Calories brûlées / heure *</label>
                            <input type="number" id="calories_brulees_heure" name="calories_brulees_heure" required placeholder="500" min="0">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="admin-button primary" id="activitySubmit">Créer l'activité</button>
                            <button type="reset" class="admin-button secondary">Réinitialiser</button>
                        </div>
                    </form>
                </article>

                <article class="admin-card full">
                    <h3>Liste des activités</h3>
                    <div class="table-wrapper">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Calories/h</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($activites)): ?>
                                    <tr>
                                        <td colspan="5" class="text-muted text-center">Aucune activité enregistrée.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($activites as $activite): ?>
                                        <?php
                                            $activityId = (string) ($activite['id'] ?? '');
                                            $activityName = (string) ($activite['nom'] ?? '');
                                            $activityDescription = (string) ($activite['description'] ?? '');
                                            $calories = (int) ($activite['calories_brulees_heure'] ?? 0);
                                        ?>
                                        <tr data-id="<?= esc($activityId); ?>">
                                            <td class="text-muted"><?= esc($activityId); ?></td>
                                            <td><strong><?= esc($activityName); ?></strong></td>
                                            <td><?= esc($activityDescription !== '' ? $activityDescription : '—'); ?></td>
                                            <td><?= esc((string) $calories); ?> cal</td>
                                            <td class="action-buttons">
                                                <button type="button" class="btn-small primary js-edit-activity"
                                                    data-id="<?= esc($activityId); ?>"
                                                    data-nom="<?= esc($activityName); ?>"
                                                    data-description="<?= esc($activityDescription); ?>"
                                                    data-calories="<?= esc((string) $calories); ?>">
                                                    Éditer
                                                </button>
                                                <form method="post" action="<?= base_url('/admin/activites/delete/' . $activityId); ?>" onsubmit="return confirm('Supprimer cette activité ?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn-small danger">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </article>
            </section>
        </main>
    </div>

    <script>
    const activityForm = document.getElementById('activityForm');
    const activityId = document.getElementById('activityId');
    const activitySubmit = document.getElementById('activitySubmit');

    document.querySelectorAll('.js-edit-activity').forEach(button => {
        button.addEventListener('click', () => {
            activityId.value = button.dataset.id || '';
            document.getElementById('nom').value = button.dataset.nom || '';
            document.getElementById('description').value = button.dataset.description || '';
            document.getElementById('calories_brulees_heure').value = button.dataset.calories || '';
            activityForm.action = `<?= base_url('/admin/activites/update'); ?>/${button.dataset.id}`;
            activitySubmit.textContent = 'Mettre à jour l’activité';
            activityForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    activityForm.querySelector('button[type="reset"]').addEventListener('click', () => {
        activityId.value = '';
        activityForm.action = '<?= base_url('/admin/activites/store'); ?>';
        activitySubmit.textContent = 'Créer l’activité';
    });
    </script>
</body>
</html>

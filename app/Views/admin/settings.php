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
    <?php
        $genres = $genres ?? [];
        $objectifs = $objectifs ?? [];
        $durees = $durees ?? [];
        $goldMembers = $goldMembers ?? 0;
        $goldSubscriptions = $goldSubscriptions ?? 0;
    ?>
    <div class="admin-shell">
        <?= view('partials/admin_sidebar'); ?>

        <main class="admin-content">
            <section class="admin-hero">
                <div>
                    <h2>Gestion des Paramètres</h2>
                    <p>Gérer les données de référence de la base de données.</p>
                </div>
            </section>

            <section class="admin-grid">
                <article class="admin-card">
                    <h3>Genres</h3>

                    <?php if (!empty($flash_success)): ?>
                        <div class="flash-ok"><?= esc($flash_success); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($flash_error)): ?>
                        <div class="flash-err"><?= esc($flash_error); ?></div>
                    <?php endif; ?>

                    <form id="genreForm" class="admin-form" method="post" action="<?= base_url('/admin/settings/store/genre'); ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" id="genre_id" name="id">
                        <div class="form-group">
                            <label for="genre-nom">Nom du genre *</label>
                            <input type="text" id="genre-nom" name="nom" required placeholder="Ex: Autre">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="admin-button primary" id="genreSubmit">Ajouter</button>
                            <button type="reset" class="admin-button secondary">Réinitialiser</button>
                        </div>
                    </form>
                    <div class="param-list">
                        <?php foreach ($genres as $genre): ?>
                            <?php
                                $genreId = (string) ($genre['id'] ?? '');
                                $genreNom = (string) ($genre['nom'] ?? '');
                            ?>
                            <div class="param-item">
                                <span><?= esc($genreNom); ?></span>
                                <div class="action-buttons">
                                    <button type="button" class="btn-small primary js-edit-genre"
                                        data-id="<?= esc($genreId); ?>"
                                        data-nom="<?= esc($genreNom); ?>">Modifier</button>
                                    <form method="post" action="<?= base_url('/admin/settings/delete/genre/' . $genreId); ?>" onsubmit="return confirm('Supprimer ce genre ?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-small danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="admin-card">
                    <h3>Objectifs</h3>
                    <form id="objectifForm" class="admin-form" method="post" action="<?= base_url('/admin/settings/store/objectif'); ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" id="objectif_id" name="id">
                        <div class="form-group">
                            <label for="objectif-nom">Nom de l'objectif *</label>
                            <input type="text" id="objectif-nom" name="nom" required placeholder="Ex: Perte de poids">
                        </div>
                        <div class="form-group">
                            <label for="objectif-desc">Description</label>
                            <textarea id="objectif-desc" name="description" rows="2" placeholder="Optionnel..."></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="admin-button primary" id="objectifSubmit">Ajouter</button>
                            <button type="reset" class="admin-button secondary">Réinitialiser</button>
                        </div>
                    </form>
                    <div class="param-list">
                        <?php foreach ($objectifs as $objectif): ?>
                            <?php
                                $objectifId = (string) ($objectif['id'] ?? '');
                                $objectifNom = (string) ($objectif['nom'] ?? '');
                                $objectifDescription = (string) ($objectif['description'] ?? '');
                            ?>
                            <div class="param-item">
                                <span>
                                    <strong><?= esc($objectifNom); ?></strong>
                                    <?php if ($objectifDescription !== ''): ?>
                                        <small><?= esc($objectifDescription); ?></small>
                                    <?php endif; ?>
                                </span>
                                <div class="action-buttons">
                                    <button type="button" class="btn-small primary js-edit-objectif"
                                        data-id="<?= esc($objectifId); ?>"
                                        data-nom="<?= esc($objectifNom); ?>"
                                        data-description="<?= esc($objectifDescription); ?>">Modifier</button>
                                    <form method="post" action="<?= base_url('/admin/settings/delete/objectif/' . $objectifId); ?>" onsubmit="return confirm('Supprimer cet objectif ?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-small danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="admin-card">
                    <h3>Durées</h3>
                    <form id="dureeForm" class="admin-form" method="post" action="<?= base_url('/admin/settings/store/duree'); ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" id="duree_id" name="id">
                        <div class="form-group">
                            <label for="duree-nom">Nom de la durée *</label>
                            <input type="text" id="duree-nom" name="nom" required placeholder="Ex: 30 jours">
                        </div>
                        <div class="form-group">
                            <label for="duree-jours">Nombre de jours *</label>
                            <input type="number" id="duree-jours" name="nombre_jours" required placeholder="30" min="1">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="admin-button primary" id="dureeSubmit">Ajouter</button>
                            <button type="reset" class="admin-button secondary">Réinitialiser</button>
                        </div>
                    </form>
                    <div class="param-list">
                        <?php foreach ($durees as $duree): ?>
                            <?php
                                $dureeId = (string) ($duree['id'] ?? '');
                                $dureeNom = (string) ($duree['nom'] ?? '');
                                $dureeJours = (string) ($duree['nombre_jours'] ?? '0');
                            ?>
                            <div class="param-item">
                                <span>
                                    <strong><?= esc($dureeNom); ?></strong>
                                    <small><?= esc($dureeJours); ?> jours</small>
                                </span>
                                <div class="action-buttons">
                                    <button type="button" class="btn-small primary js-edit-duree"
                                        data-id="<?= esc($dureeId); ?>"
                                        data-nom="<?= esc($dureeNom); ?>"
                                        data-jours="<?= esc($dureeJours); ?>">Modifier</button>
                                    <form method="post" action="<?= base_url('/admin/settings/delete/duree/' . $dureeId); ?>" onsubmit="return confirm('Supprimer cette durée ?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-small danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="admin-card full">
                    <h3>Résumé des références</h3>
                    <div class="param-info">
                        <p><strong>Genres :</strong> <?= count($genres); ?></p>
                        <p><strong>Objectifs :</strong> <?= count($objectifs); ?></p>
                        <p><strong>Durées :</strong> <?= count($durees); ?></p>
                    </div>
                </article>
            </section>
        </main>
    </div>

    <script>
    const genreForm = document.getElementById('genreForm');
    const objectifForm = document.getElementById('objectifForm');
    const dureeForm = document.getElementById('dureeForm');

    const genreSubmit = document.getElementById('genreSubmit');
    const objectifSubmit = document.getElementById('objectifSubmit');
    const dureeSubmit = document.getElementById('dureeSubmit');

    document.querySelectorAll('.js-edit-genre').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('genre_id').value = button.dataset.id || '';
            document.getElementById('genre-nom').value = button.dataset.nom || '';
            genreForm.action = `<?= base_url('/admin/settings/update/genre'); ?>/${button.dataset.id}`;
            genreSubmit.textContent = 'Mettre à jour';
        });
    });

    document.querySelectorAll('.js-edit-objectif').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('objectif_id').value = button.dataset.id || '';
            document.getElementById('objectif-nom').value = button.dataset.nom || '';
            document.getElementById('objectif-desc').value = button.dataset.description || '';
            objectifForm.action = `<?= base_url('/admin/settings/update/objectif'); ?>/${button.dataset.id}`;
            objectifSubmit.textContent = 'Mettre à jour';
        });
    });

    document.querySelectorAll('.js-edit-duree').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('duree_id').value = button.dataset.id || '';
            document.getElementById('duree-nom').value = button.dataset.nom || '';
            document.getElementById('duree-jours').value = button.dataset.jours || '';
            dureeForm.action = `<?= base_url('/admin/settings/update/duree'); ?>/${button.dataset.id}`;
            dureeSubmit.textContent = 'Mettre à jour';
        });
    });

    [genreForm, objectifForm, dureeForm].forEach(form => {
        form.querySelector('button[type="reset"]').addEventListener('click', () => {
            form.action = form.id === 'genreForm'
                ? '<?= base_url('/admin/settings/store/genre'); ?>'
                : form.id === 'objectifForm'
                    ? '<?= base_url('/admin/settings/store/objectif'); ?>'
                    : '<?= base_url('/admin/settings/store/duree'); ?>';

            if (form.id === 'genreForm') {
                document.getElementById('genre_id').value = '';
                genreSubmit.textContent = 'Ajouter';
            }

            if (form.id === 'objectifForm') {
                document.getElementById('objectif_id').value = '';
                objectifSubmit.textContent = 'Ajouter';
            }

            if (form.id === 'dureeForm') {
                document.getElementById('duree_id').value = '';
                dureeSubmit.textContent = 'Ajouter';
            }
        });
    });
    </script>
            </section>
        </main>
    </div>
</body>
</html>

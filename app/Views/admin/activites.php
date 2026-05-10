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
                    <h3>Ajouter une nouvelle activité</h3>
                    <form class="admin-form">
                        <div class="form-group">
                            <label for="nom">Nom de l'activité *</label>
                            <input type="text" id="nom" name="nom" required placeholder="Ex: Course à pied">
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" rows="4" required placeholder="Décrivez l'activité..."></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="calories">Calories/heure *</label>
                                <input type="number" id="calories" name="calories" required placeholder="500" min="0">
                            </div>
                            <div class="form-group">
                                <label for="intensite">Intensité *</label>
                                <select id="intensite" name="intensite" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="faible">Faible</option>
                                    <option value="modere">Modérée</option>
                                    <option value="elevee">Élevée</option>
                                    <option value="tres-elevee">Très élevée</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="admin-button primary">Créer l'activité</button>
                            <button type="reset" class="admin-button secondary">Réinitialiser</button>
                        </div>
                    </form>
                </article>

                <article class="admin-card full">
                    <h3>List des activités</h3>
                    <div class="table-wrapper">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Calories/h</th>
                                    <th>Intensité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-muted">1</td>
                                    <td><strong>Course à pied</strong></td>
                                    <td>600 cal</td>
                                    <td><span class="badge">Élevée</span></td>
                                    <td class="action-buttons">
                                        <a href="#" class="btn-small primary">Éditer</a>
                                        <a href="#" class="btn-small danger">Supprimer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">2</td>
                                    <td><strong>Natation</strong></td>
                                    <td>500 cal</td>
                                    <td><span class="badge">Élevée</span></td>
                                    <td class="action-buttons">
                                        <a href="#" class="btn-small primary">Éditer</a>
                                        <a href="#" class="btn-small danger">Supprimer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">3</td>
                                    <td><strong>Yoga</strong></td>
                                    <td>250 cal</td>
                                    <td><span class="badge">Modérée</span></td>
                                    <td class="action-buttons">
                                        <a href="#" class="btn-small primary">Éditer</a>
                                        <a href="#" class="btn-small danger">Supprimer</a>
                                    </td>
                                </tr>
                                <tr class="not-saved">
                                    <td colspan="5" class="text-muted text-center">
                                        Les données de cette section sont simulées • Interface non connectée à la base de données
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </article>
            </section>
        </main>
    </div>
</body>
</html>

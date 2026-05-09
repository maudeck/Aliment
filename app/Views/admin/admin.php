<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Dashboard'); ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css'); ?>">
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
                <a href="<?= base_url('/admin'); ?>" class="active">
                    <strong>Tableau de bord</strong>
                    <span>Aperçu général des modules admin</span>
                </a>
                <a href="<?= base_url('/admin/regimes'); ?>">
                    <strong>CRUD Régimes</strong>
                    <span>Créer, lire, modifier, supprimer les régimes</span>
                </a>
                <a href="<?= base_url('/admin/activites'); ?>">
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
            <section class="admin-hero" id="dashboard">
                <div>
                    <h2>Tableau de bord administrateur</h2>
                    <p>
                        Espace central pour administrer les régimes, les activités sportives,
                        valider les codes de recharge et gérer les paramètres de l'application.
                    </p>
                </div>
                <div class="admin-pill">Accès admin actif</div>
            </section>

            <section class="admin-grid">
                <article class="admin-card regimes" id="regimes">
                    <h3>CRUD Régimes</h3>
                    <p>Créer et modifier les régimes proposés aux utilisateurs, gérer les prix et les durées disponibles.</p>
                    <div class="actions">
                        <a class="admin-button primary" href="<?= base_url('/admin/regimes'); ?>">Ajouter un régime</a>
                        <a class="admin-button secondary" href="<?= base_url('/admin/regimes'); ?>">Voir la liste</a>
                    </div>
                </article>

                <article class="admin-card activities" id="activites">
                    <h3>CRUD Activités sportives</h3>
                    <p>Configurer les activités associées aux régimes avec descriptions et calories brûlées par heure.</p>
                    <div class="actions">
                        <a class="admin-button primary" href="<?= base_url('/admin/activites'); ?>">Ajouter une activité</a>
                        <a class="admin-button secondary" href="<?= base_url('/admin/activites'); ?>">Voir les activités</a>
                    </div>
                </article>

                <article class="admin-card codes" id="codes">
                    <h3>Validation des codes portefeuille</h3>
                    <p>Vérifier les codes de recharge des utilisateurs et suivre les montants crédités dans les portefeuilles.</p>
                    <div class="actions">
                        <a class="admin-button primary" href="<?= base_url('/admin/codes'); ?>">Valider un code</a>
                        <a class="admin-button secondary" href="<?= base_url('/admin/codes'); ?>">Historique des codes</a>
                    </div>
                </article>

                <article class="admin-card settings" id="settings">
                    <h3>CRUD Paramètres nécessaires</h3>
                    <p>Gérer les données de base du système comme les genres, objectifs, durées et autres paramètres de référence.</p>
                    <div class="actions">
                        <a class="admin-button primary" href="<?= base_url('/admin/settings'); ?>">Gérer les paramètres</a>
                        <a class="admin-button secondary" href="<?= base_url('/admin/settings'); ?>">Voir les tables</a>
                    </div>
                </article>

                <article class="admin-card full">
                    <h3>Raccourcis d'administration</h3>
                    <div class="admin-list">
                        <div class="admin-list-item">
                            <div>
                                <strong>Régimes actifs</strong>
                                <small>Accéder rapidement au CRUD des régimes</small>
                            </div>
                            <span class="admin-badge">À connecter</span>
                        </div>
                        <div class="admin-list-item">
                            <div>
                                <strong>Activités sportives</strong>
                                <small>Créer ou éditer les activités associées</small>
                            </div>
                            <span class="admin-badge">À connecter</span>
                        </div>
                        <div class="admin-list-item">
                            <div>
                                <strong>Codes de recharge</strong>
                                <small>Contrôler les validations de portefeuille</small>
                            </div>
                            <span class="admin-badge">À connecter</span>
                        </div>
                    </div>
                </article>
            </section>
        </main>
    </div>
</body>
</html>

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
                <a href="<?= base_url('/admin/activites'); ?>">
                    <strong>CRUD Activités sportives</strong>
                    <span>Gérer les activités liées aux régimes</span>
                </a>
                <a href="<?= base_url('/admin/codes'); ?>">
                    <strong>Validation des codes</strong>
                    <span>Contrôler les recharges du portefeuille</span>
                </a>
                <a href="<?= base_url('/admin/settings'); ?>" class="active">
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
                    <h2>Gestion des Paramètres</h2>
                    <p>Gérer les données de référence et les réglages généraux du système.</p>
                </div>
            </section>

            <section class="admin-grid">
                <article class="admin-card">
                    <h3>Genres</h3>
                    <form class="admin-form">
                        <div class="form-group">
                            <label for="genre-nom">Nom du genre *</label>
                            <input type="text" id="genre-nom" name="genre_nom" required placeholder="Ex: Autre">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="admin-button primary">Ajouter</button>
                        </div>
                    </form>
                    <div class="param-list">
                        <div class="param-item">
                            <span>Masculin</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item">
                            <span>Féminin</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item">
                            <span>Autre</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item not-saved">
                            <em>Données simulées</em>
                        </div>
                    </div>
                </article>

                <article class="admin-card">
                    <h3>Objectifs</h3>
                    <form class="admin-form">
                        <div class="form-group">
                            <label for="objectif-nom">Nom de l'objectif *</label>
                            <input type="text" id="objectif-nom" name="objectif_nom" required placeholder="Ex: Perte de poids">
                        </div>
                        <div class="form-group">
                            <label for="objectif-desc">Description</label>
                            <textarea id="objectif-desc" name="objectif_desc" rows="2" placeholder="Optionnel..."></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="admin-button primary">Ajouter</button>
                        </div>
                    </form>
                    <div class="param-list">
                        <div class="param-item">
                            <span>Perte de poids</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item">
                            <span>Prise de muscle</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item">
                            <span>Maintien</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item not-saved">
                            <em>Données simulées</em>
                        </div>
                    </div>
                </article>

                <article class="admin-card">
                    <h3>Durées</h3>
                    <form class="admin-form">
                        <div class="form-group">
                            <label for="duree-jours">Nombre de jours *</label>
                            <input type="number" id="duree-jours" name="duree_jours" required placeholder="30" min="1">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="admin-button primary">Ajouter</button>
                        </div>
                    </form>
                    <div class="param-list">
                        <div class="param-item">
                            <span>7 jours</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item">
                            <span>14 jours</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item">
                            <span>30 jours</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item">
                            <span>90 jours</span>
                            <a href="#" class="btn-small danger">Supprimer</a>
                        </div>
                        <div class="param-item not-saved">
                            <em>Données simulées</em>
                        </div>
                    </div>
                </article>

                <article class="admin-card">
                    <h3>Abonnement Gold</h3>
                    <form class="admin-form">
                        <div class="form-group">
                            <label for="gold-prix">Prix mensuel (AR) *</label>
                            <input type="number" id="gold-prix" name="gold_prix" required placeholder="99" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="gold-desc">Description</label>
                            <textarea id="gold-desc" name="gold_desc" rows="2" placeholder="Bénéfices du Gold..."></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="admin-button primary">Mettre à jour</button>
                        </div>
                    </form>
                    <div class="param-info">
                        <p><strong>Prix actuel:</strong> 99 AR/mois</p>
                        <p><strong>Bénéfices:</strong> Accès à tous les régimes premium</p>
                        <p class="not-saved"><em>Données simulées</em></p>
                    </div>
                </article>

                <article class="admin-card full">
                    <h3>Configurations système</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="notifications" checked> Activer les notifications par email
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="maintenance"> Mode maintenance
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="api_debug"> Mode debug API
                            </label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="admin-button primary">Sauvegarder les configurations</button>
                    </div>
                    <p class="not-saved"><em>Les données de cette section sont simulées • Interface non connectée à la base de données</em></p>
                </article>
            </section>
        </main>
    </div>
</body>
</html>

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
                <a href="<?= base_url('/admin/codes'); ?>" class="active">
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
                    <h2>Validation des Codes Portefeuille</h2>
                    <p>Vérifier et valider les codes de recharge pour les portefeuilles des utilisateurs.</p>
                </div>
            </section>

            <section class="admin-grid">
                <article class="admin-card full">
                    <h3>Valider un code</h3>
                    <form class="admin-form">
                        <div class="form-group">
                            <label for="code">Code de recharge *</label>
                            <input type="text" id="code" name="code" required placeholder="Ex: CODE-2024-ABC123" maxlength="20">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="montant">Montant (AR) *</label>
                                <input type="number" id="montant" name="montant" required placeholder="100" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label for="utilisateurs_max">Limité à (utilisateurs) *</label>
                                <input type="number" id="utilisateurs_max" name="utilisateurs_max" required placeholder="1" min="1">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="note">Notes optionnelles</label>
                            <textarea id="note" name="note" rows="2" placeholder="Raison ou détails du code..."></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="admin-button primary">Valider le code</button>
                            <button type="reset" class="admin-button secondary">Réinitialiser</button>
                        </div>
                    </form>
                </article>

                <article class="admin-card full">
                    <h3>Codes en attente de validation</h3>
                    <div class="table-wrapper">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Montant</th>
                                    <th>Limité à</th>
                                    <th>Créé le</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>CODE-2024-ABC123</strong></td>
                                    <td>100 AR</td>
                                    <td>1 utilisateur</td>
                                    <td class="text-muted">15/05/2026</td>
                                    <td><span class="status-badge pending">En attente</span></td>
                                    <td class="action-buttons">
                                        <a href="#" class="btn-small primary">Valider</a>
                                        <a href="#" class="btn-small danger">Rejeter</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>CODE-2024-DEF456</strong></td>
                                    <td>250 AR</td>
                                    <td>5 utilisateurs</td>
                                    <td class="text-muted">14/05/2026</td>
                                    <td><span class="status-badge pending">En attente</span></td>
                                    <td class="action-buttons">
                                        <a href="#" class="btn-small primary">Valider</a>
                                        <a href="#" class="btn-small danger">Rejeter</a>
                                    </td>
                                </tr>
                                <tr class="not-saved">
                                    <td colspan="6" class="text-muted text-center">
                                        Les données de cette section sont simulées • Interface non connectée à la base de données
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="admin-card full">
                    <h3>Historique des codes validés</h3>
                    <div class="table-wrapper">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Montant</th>
                                    <th>Utilisations</th>
                                    <th>Validé le</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>CODE-2024-XYZ789</strong></td>
                                    <td>200 AR</td>
                                    <td>1/3 utilisateurs</td>
                                    <td class="text-muted">10/05/2026</td>
                                    <td><span class="status-badge active">Actif</span></td>
                                </tr>
                                <tr>
                                    <td><strong>CODE-2024-OLD001</strong></td>
                                    <td>150 AR</td>
                                    <td>2/2 utilisateurs</td>
                                    <td class="text-muted">01/05/2026</td>
                                    <td><span class="status-badge used">Épuisé</span></td>
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

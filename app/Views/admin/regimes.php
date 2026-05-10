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
                <a href="<?= base_url('/admin/regimes'); ?>" class="active">
                    <strong>CRUD Régimes</strong>
                    <span>Gérer les régimes de la plateforme</span>
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
            <section class="admin-hero">
                <div>
                    <h2>Gestion des Régimes</h2>
                    <p>Créez, modifiez et supprimez les régimes nutritionnels de NutriLife.</p>
                </div>
            </section>

            <div class="admin-grid">
                <article class="admin-card full">
                    <h3>Ajouter / Modifier un régime</h3>
                    <div id="notifications"></div>
                    <form id="regimeForm" class="admin-form" method="post" action="<?= base_url('/admin/regimes/store'); ?>">
                        <input type="hidden" id="regimeId" name="regime_id">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Nom du régime *</label>
                                <input id="name" name="name" type="text" placeholder="ex: Keto Premium" required>
                            </div>
                            <div class="form-group">
                                <label for="objectif">Objectif *</label>
                                <select id="objectif" name="objectif" required>
                                    <option value="">-- Sélectionner un objectif --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Variation de poids (kg) *</label>
                                <input id="price" name="price" type="number" step="0.01" placeholder="ex: 2.5" required>
                            </div>
                            <div class="form-group">
                                <label for="proteines">Viande (%)</label>
                                <input id="proteines" name="proteines" type="number" value="30" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="glucides">Poisson (%)</label>
                                <input id="glucides" name="glucides" type="number" value="40" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="lipides">Volaille (%)</label>
                                <input id="lipides" name="lipides" type="number" value="30" step="0.01">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description détaillée *</label>
                            <textarea id="description" name="description" rows="3" placeholder="Décrivez le régime en détail..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="activityId">Activité sportive *</label>
                            <select id="activityId" name="activity_id" required>
                                <option value="">-- Sélectionner une activité --</option>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="admin-button primary">Enregistrer</button>
                            <button type="reset" class="admin-button secondary">Réinitialiser</button>
                        </div>
                    </form>
                </article>

                <article class="admin-card full">
                    <h3>Régimes existants</h3>
                    <div class="search-toolbar">
                        <div class="search-command">
                            <input id="regimeSearch" class="search-input" type="search" placeholder="Rechercher par nom, description...">
                        </div>
                        <div>
                            <small class="text-muted">Filtre instantané</small>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table class="admin-table" id="regimesTable">
                            <thead>
                                <tr>
                                    <th>Nom & Description</th>
                                    <th>Objectif</th>
                                    <th>Variation poids</th>
                                    <th>Macros</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="regimesTableBody">
                                <tr><td colspan="5" style="text-align:center; padding: 20px;">Chargement...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </article>
            </div>
        </main>
    </div>

    <script>
    (function() {
        const form = document.getElementById('regimeForm');
        const tbody = document.getElementById('regimesTableBody');
        const searchInput = document.getElementById('regimeSearch');
        const objectifSelect = document.getElementById('objectif');
        const activitySelect = document.getElementById('activityId');
        const regimeIdInput = document.getElementById('regimeId');
        const notificationsDiv = document.getElementById('notifications');

        let allObjectifs = [];
        let allActivities = [];

        // Charger les données initiales
        loadObjectifs();
        loadActivities();
        loadRegimes();

        // Événement formulaire
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const isEdit = regimeIdInput.value !== '';
            await submitForm(isEdit ? 'update' : 'add');
        });

        // Filtre recherche
        searchInput.addEventListener('input', async function() {
            const query = this.value.trim();
            if (query) {
                const url = `<?= base_url('/admin/regimes/api'); ?>?search=${encodeURIComponent(query)}`;
                const response = await fetch(url);
                const regimes = await response.json();
                renderRegimes(regimes);
            } else {
                await loadRegimes();
            }
        });

        // Réinitialiser
        form.querySelector('button[type="reset"]').addEventListener('click', function() {
            regimeIdInput.value = '';
            if (activitySelect) activitySelect.value = '';
            form.querySelector('button[type="submit"]').textContent = 'Enregistrer';
        });

        async function loadObjectifs() {
            try {
                const response = await fetch('<?= base_url('/admin/regimes/api'); ?>?type=objectifs');
                allObjectifs = await response.json();
                populateObjectifs();
            } catch (e) {
                console.error('Erreur chargement objectifs:', e);
                allObjectifs = [
                    { id: 1, nom: 'Augmenter son poids' },
                    { id: 2, nom: 'Réduire son poids' },
                    { id: 3, nom: 'Atteindre son IMC idéal' }
                ];
                populateObjectifs();
            }
        }

        async function loadActivities() {
            try {
                const response = await fetch('<?= base_url('/admin/regimes/api'); ?>?type=activities');
                allActivities = await response.json();
            } catch (e) {
                console.error('Erreur chargement activités:', e);
                allActivities = [
                    { id: 1, nom: 'Musculation' },
                    { id: 2, nom: 'Cardio léger' },
                    { id: 3, nom: 'Course à pied' },
                    { id: 4, nom: 'Natation' },
                    { id: 5, nom: 'Yoga' },
                    { id: 6, nom: 'HIIT' }
                ];
            }
            populateActivities();
        }

        async function loadRegimes() {
            try {
                const response = await fetch('<?= base_url('/admin/regimes/api'); ?>');
                const regimes = await response.json();
                renderRegimes(regimes);
            } catch (e) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:red;">Erreur chargement régimes</td></tr>';
            }
        }

        function populateObjectifs() {
            objectifSelect.innerHTML = '<option value="">-- Sélectionner un objectif --</option>';
            allObjectifs.forEach(obj => {
                const opt = document.createElement('option');
                opt.value = obj.id;
                opt.textContent = obj.nom;
                objectifSelect.appendChild(opt);
            });
        }

        function populateActivities() {
            activitySelect.innerHTML = '<option value="">-- Sélectionner une activité --</option>';
            allActivities.forEach(act => {
                const opt = document.createElement('option');
                opt.value = act.id;
                opt.textContent = act.nom;
                activitySelect.appendChild(opt);
            });
        }

        function renderRegimes(regimes) {
            if (!Array.isArray(regimes) || regimes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 20px;">Aucun régime trouvé.</td></tr>';
                return;
            }

            tbody.innerHTML = regimes.map(regime => `
                <tr data-regime-id="${regime.id}" data-search="${((regime.nom || '') + ' ' + (regime.description || '') + ' ' + (regime.objectif_nom || '') + ' ' + (regime.activity_nom || '')).toLowerCase()}">
                    <td>
                        <strong>${escapeHtml(regime.nom || '')}</strong><br>
                        <small class="text-muted">${escapeHtml(regime.description || 'Sans description')}</small>
                    </td>
                    <td><small>Objectif: ${escapeHtml(regime.objectif_nom || '—')}<br>Activité: ${escapeHtml(regime.activity_nom || '—')}</small></td>
                    <td><strong>${escapeHtml(regime.variation_poids || '0')} kg</strong></td>
                    <td><small>V:${escapeHtml(regime.pourcentage_viande || '0')}% P:${escapeHtml(regime.pourcentage_poisson || '0')}% Vol:${escapeHtml(regime.pourcentage_volaille || '0')}%</small></td>
                    <td class="action-buttons">
                        <button type="button" class="btn-small primary edit-regime" data-id="${regime.id}">Modifier</button>
                        <button type="button" class="btn-small danger delete-regime" data-id="${regime.id}">Supprimer</button>
                    </td>
                </tr>
            `).join('');

            // Événements
            document.querySelectorAll('.edit-regime').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const id = this.dataset.id;
                    const regime = regimes.find(r => r.id == id);
                    if (regime) {
                        fillFormFromRegime(regime);
                    }
                });
            });

            document.querySelectorAll('.delete-regime').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const id = this.dataset.id;
                    if (confirm('Êtes-vous sûr de vouloir supprimer ce régime ?')) {
                        await deleteRegime(id);
                    }
                });
            });
        }

        function fillFormFromRegime(regime) {
            regimeIdInput.value = regime.id;
            document.getElementById('name').value = regime.nom || '';
            document.getElementById('description').value = regime.description || '';
            document.getElementById('price').value = regime.variation_poids || '';
            document.getElementById('proteines').value = regime.pourcentage_viande || '30';
            document.getElementById('glucides').value = regime.pourcentage_poisson || '40';
            document.getElementById('lipides').value = regime.pourcentage_volaille || '30';
            document.getElementById('objectif').value = regime.objectif_id || '';
            if (activitySelect) {
                activitySelect.value = regime.activity_id || '';
            }

            form.querySelector('button[type="submit"]').textContent = 'Modifier le régime';
            form.scrollIntoView({ behavior: 'smooth' });
        }

        async function submitForm(action) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enregistrement...';

            try {
                const url = action === 'update' 
                    ? `<?= base_url('/admin/regimes/update'); ?>/${regimeIdInput.value}`
                    : '<?= base_url('/admin/regimes/store'); ?>';

                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form)
                });

                const payload = await response.json();

                if (!payload.success) {
                    throw new Error(payload.message || 'Erreur inconnue');
                }

                showNotification(payload.message || 'Opération réussie.', 'success');
                form.reset();
                regimeIdInput.value = '';
                if (activitySelect) activitySelect.value = '';
                submitBtn.textContent = 'Enregistrer';

                await loadRegimes();
            } catch (error) {
                showNotification(error.message || 'Erreur lors de l\'opération.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }

        async function deleteRegime(id) {
            try {
                const response = await fetch(`<?= base_url('/admin/regimes/delete'); ?>/${id}`, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const payload = await response.json();

                if (!payload.success) {
                    throw new Error(payload.message || 'Erreur lors de la suppression');
                }

                showNotification('Régime supprimé avec succès.', 'success');
                await loadRegimes();
            } catch (error) {
                showNotification(error.message || 'Erreur lors de la suppression.', 'error');
            }
        }

        function showNotification(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert ${type}`;
            alert.textContent = message;
            notificationsDiv.innerHTML = '';
            notificationsDiv.appendChild(alert);
            setTimeout(() => alert.remove(), 4000);
        }

        function escapeHtml(s) {
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }
    })();
    </script>
</body>
</html>

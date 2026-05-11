<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Gestion des Régimes'); ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/admin-regimes.css'); ?>">
    <style>

    </style>
</head>
<body>
    <div class="admin-shell">
    <?= view('partials/admin_sidebar'); ?>


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
                                <label for="prix">Prix du régime (Ar) *</label>
                                <input id="prix" name="prix" type="number" step="0.01" placeholder="ex: 50000" required>
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
                            <label>Activités sportives *</label>
                            <div id="activitiesContainer" class="activity-selects">
                                <div class="activity-select-row">
                                    <select class="activity-select" name="activity_id[]" required>
                                        <option value="">-- Sélectionner une activité --</option>
                                    </select>
                                    <button type="button" class="btn-small secondary remove-activity" aria-label="Supprimer l'activité">−</button>
                                </div>
                            </div>
                            <button type="button" id="addActivity" class="btn-small primary">+ Ajouter une activité</button>
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
                                    <th>Prix</th>
                                    <th>Macros</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="regimesTableBody">
                                <tr><td colspan="6" style="text-align:center; padding: 20px;">Chargement...</td></tr>
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
        const activitiesContainer = document.getElementById('activitiesContainer');
        const addActivityButton = document.getElementById('addActivity');
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
            resetActivityRows();
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
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">Erreur chargement régimes</td></tr>';
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
            const selects = activitiesContainer.querySelectorAll('select.activity-select');
            selects.forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">-- Sélectionner une activité --</option>';
                allActivities.forEach(act => {
                    const opt = document.createElement('option');
                    opt.value = act.id;
                    opt.textContent = act.nom;
                    select.appendChild(opt);
                });
                if (currentValue) {
                    select.value = currentValue;
                }
            });
        }

        function createActivityRow(selectedValue = '') {
            const row = document.createElement('div');
            row.className = 'activity-select-row';

            const select = document.createElement('select');
            select.className = 'activity-select';
            select.name = 'activity_id[]';
            select.required = true;

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '-- Sélectionner une activité --';
            select.appendChild(defaultOption);

            allActivities.forEach(act => {
                const opt = document.createElement('option');
                opt.value = act.id;
                opt.textContent = act.nom;
                select.appendChild(opt);
            });

            if (selectedValue) {
                select.value = selectedValue;
            }

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn-small secondary remove-activity';
            removeButton.setAttribute('aria-label', "Supprimer l'activité");
            removeButton.textContent = '−';
            removeButton.addEventListener('click', () => removeActivityRow(row));

            row.appendChild(select);
            row.appendChild(removeButton);
            return row;
        }

        function resetActivityRows() {
            activitiesContainer.innerHTML = '';
            activitiesContainer.appendChild(createActivityRow());
        }

        function removeActivityRow(row) {
            const rows = activitiesContainer.querySelectorAll('.activity-select-row');
            if (rows.length <= 1) {
                row.querySelector('select').value = '';
                return;
            }
            row.remove();
        }

        if (addActivityButton) {
            addActivityButton.addEventListener('click', function() {
                activitiesContainer.appendChild(createActivityRow());
            });
        }

        function renderRegimes(regimes) {
            if (!Array.isArray(regimes) || regimes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 20px;">Aucun régime trouvé.</td></tr>';
                return;
            }

            tbody.innerHTML = regimes.map(regime => `
                <tr data-regime-id="${regime.id}" data-search="${((regime.nom || '') + ' ' + (regime.description || '') + ' ' + (regime.objectif_nom || '') + ' ' + (regime.activity_nom || '')).toLowerCase()}">
                    <td>
                        <strong>${escapeHtml(regime.nom || '')}</strong><br>
                        <small class="text-muted">${escapeHtml(regime.description || 'Sans description')}</small>
                    </td>
                    <td><small>Objectif: ${escapeHtml(regime.objectif_nom || '—')}<br>Activités: ${escapeHtml(regime.activity_noms || '—')}</small></td>
                    <td><strong>${escapeHtml(regime.variation_poids || '0')} kg</strong></td>
                    <td><strong>${escapeHtml(regime.prix || '0')} Ar</strong></td>
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
            document.getElementById('prix').value = regime.prix || '';
            document.getElementById('proteines').value = regime.pourcentage_viande || '30';
            document.getElementById('glucides').value = regime.pourcentage_poisson || '40';
            document.getElementById('lipides').value = regime.pourcentage_volaille || '30';
            document.getElementById('objectif').value = regime.objectif_id || '';
            const selectedIds = (regime.activity_ids || '')
                .toString()
                .split(',')
                .map(value => value.trim())
                .filter(value => value !== '');

            activitiesContainer.innerHTML = '';
            if (selectedIds.length === 0) {
                activitiesContainer.appendChild(createActivityRow());
            } else {
                selectedIds.forEach(activityId => {
                    activitiesContainer.appendChild(createActivityRow(activityId));
                });
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
                resetActivityRows();
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
                    method: 'POST',
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

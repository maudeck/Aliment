<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Gestion des Régimes'); ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/admin-regimes.css'); ?>">
    <style>
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: white; padding: 30px; border-radius: 8px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 15px; }
        .modal-header h2 { margin: 0; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #666; }
        .modal-close:hover { color: #000; }
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
                    <span>Gestion de vos régimes</span>
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
                    <p>Pilotez les programmes nutritionnels de la plateforme NutriLife.</p>
                </div>
            </section>

            <div class="admin-grid">
                <article class="admin-card full">
                    <h3>Ajouter un nouveau régime</h3>
                    <form id="regimeForm" class="admin-form" method="post" action="<?= base_url('regimes/store'); ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="regimeName">Nom du régime</label>
                                <input id="regimeName" name="nom" type="text" placeholder="ex: Keto Premium" required>
                            </div>
                            <div class="form-group">
                                <label for="regimePrice">Variation de poids</label>
                                <input id="regimePrice" name="variation_poids" type="number" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="regimeDesc">Description détaillée</label>
                            <textarea id="regimeDesc" name="description" rows="3" required></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="regimeProt">Viande (%)</label>
                                <input id="regimeProt" name="pourcentage_viande" type="number" value="30" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="regimeGlucides">Poisson (%)</label>
                                <input id="regimeGlucides" name="pourcentage_poisson" type="number" value="40" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="regimeLipides">Volaille (%)</label>
                                <input id="regimeLipides" name="pourcentage_volaille" type="number" value="30" step="0.01">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="admin-button primary">Enregistrer le programme</button>
                            <button type="reset" class="admin-button secondary">Annuler</button>
                        </div>
                    </form>
                </article>

                <article class="admin-card full">
                    <h3>Programmes actifs</h3>
                    <div class="search-toolbar">
                        <div class="search-command">
                            <input id="regimeSearch" class="search-input" type="search" placeholder="Recherche par nom, description...">
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
                                    <th>Variation de poids</th>
                                    <th>Macros</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="regimesTableBody">
                                <!-- Les régimes seront chargés ici -->
                            </tbody>
                        </table>
                    </div>
                </article>
            </div>
        </main>
    </div>


    <!-- Champ caché pour savoir si on édite -->
    <input type="hidden" id="editRegimeId" name="edit_id" form="regimeForm">

    <script>
    (function(){
        const form = document.getElementById('regimeForm');
        const tbody = document.getElementById('regimesTableBody');
        const searchInput = document.getElementById('regimeSearch');
        const editIdInput = document.getElementById('editRegimeId');
        const submitButton = form ? form.querySelector('button[type="submit"]') : null;
        const resetButton = form ? form.querySelector('button[type="reset"]') : null;
        const createLabel = 'Enregistrer le programme';
        const updateLabel = 'Enregistrer les modifications';

        loadRegimes();

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                loadRegimes(query);
            });
        }

        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (editIdInput.value) {
                    await updateRegime(editIdInput.value);
                } else {
                    await addRegime();
                }
            });
        }

        if (resetButton) {
            resetButton.addEventListener('click', function() {
                setCreateMode();
            });
        }

        async function loadRegimes(search = '') {
            try {
                let url = '<?= base_url('regimes/filter'); ?>';
                if (search) {
                    url += '?q=' + encodeURIComponent(search);
                }
                const response = await fetch(url);
                const payload = await response.json();
                const regimes = payload && Array.isArray(payload.data) ? payload.data : [];
                renderRegimes(regimes);
            } catch (error) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px; color: red;">Erreur lors du chargement.</td></tr>';
            }
        }

        function renderRegimes(regimes) {
            if (!Array.isArray(regimes) || regimes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Aucun régime trouvé.</td></tr>';
                return;
            }
            tbody.innerHTML = '';
            regimes.forEach(regime => {
                tbody.appendChild(createRegimeRow(regime));
            });
        }

        function createRegimeRow(regime) {
            const tr = document.createElement('tr');
            tr.dataset.regimeId = regime.id || '';
            tr.dataset.search = ((regime.nom || '') + ' ' + (regime.description || '')).toLowerCase();
            tr.innerHTML = `
                <td>
                    <strong>${escapeHtml(regime.nom || '')}</strong><br>
                    <small class="text-muted">${escapeHtml(regime.description || 'Sans description')}</small>
                </td>
                <td><strong>${escapeHtml(regime.variation_poids || '0')} kg</strong></td>
                <td><small>V:${escapeHtml(regime.pourcentage_viande || '0')}% P:${escapeHtml(regime.pourcentage_poisson || '0')}% Vol:${escapeHtml(regime.pourcentage_volaille || '0')}%</small></td>
                <td class="action-buttons">
                    <button type="button" class="btn-small primary edit-regime" data-id="${regime.id}">Modifier</button>
                    <button type="button" class="btn-small danger delete-regime" data-id="${regime.id}">Supprimer</button>
                </td>
            `;
            tr.querySelector('.edit-regime').addEventListener('click', function() {
                fillFormForEditFromRow(tr);
            });
            tr.querySelector('.delete-regime').addEventListener('click', async function() {
                const id = this.dataset.id;
                if (confirm('Êtes-vous sûr de vouloir supprimer ce régime ?')) {
                    await deleteRegime(id, tr);
                }
            });
            return tr;
        }

        function fillFormForEditFromRow(tr) {
            editIdInput.value = tr.dataset.regimeId;
            const tds = tr.querySelectorAll('td');
            document.getElementById('regimeName').value = tds[0].querySelector('strong').textContent.trim();
            document.getElementById('regimeDesc').value = tds[0].querySelector('small').textContent.trim();
            document.getElementById('regimePrice').value = tds[1].textContent.replace('kg','').trim();
            const prot = tds[2].textContent.match(/V:([\d.]+)/);
            const pois = tds[2].textContent.match(/P:([\d.]+)/);
            const vola = tds[2].textContent.match(/Vol:([\d.]+)/);
            document.getElementById('regimeProt').value = prot ? prot[1] : '';
            document.getElementById('regimeGlucides').value = pois ? pois[1] : '';
            document.getElementById('regimeLipides').value = vola ? vola[1] : '';
            if (submitButton) {
                submitButton.textContent = updateLabel;
            }
        }

        // plus besoin de fillFormForEdit, remplacé par fillFormForEditFromRow

        async function addRegime() {
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Enregistrement...';
            }
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                const payload = await response.json();
                if (!payload.success) throw new Error(payload.message || 'Erreur inconnue');
                form.reset();
                setCreateMode();
                // Ajout instantané dans le DOM
                if (payload.data) {
                    removeEmptyStateRow();
                    tbody.prepend(createRegimeRow(payload.data));
                } else {
                    await loadRegimes();
                }
                showNotice(payload.message || 'Régime ajouté avec succès.', 'success');
            } catch (error) {
                showNotice(error.message || 'Erreur lors de l\'enregistrement.', 'error');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
        }

        async function updateRegime(id) {
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Enregistrement...';
            }
            try {
                const formData = new FormData(form);
                const response = await fetch(`<?= base_url('regimes/update/'); ?>${id}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                const payload = await response.json();
                if (!payload.success) throw new Error(payload.message || 'Erreur inconnue');
                form.reset();
                setCreateMode();
                // Mise à jour instantanée dans le DOM
                if (payload.data) {
                    const row = tbody.querySelector(`tr[data-regime-id="${id}"]`);
                    if (row) {
                        const newRow = createRegimeRow(payload.data);
                        tbody.replaceChild(newRow, row);
                    } else {
                        await loadRegimes();
                    }
                } else {
                    await loadRegimes();
                }
                showNotice(payload.message || 'Régime modifié avec succès.', 'success');
            } catch (error) {
                showNotice(error.message || 'Erreur lors de la modification.', 'error');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
        }

        async function deleteRegime(id, row) {
            try {
                const response = await fetch(`<?= base_url('regimes/delete/'); ?>${id}`, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const payload = await response.json();
                if (payload.success) {
                    if (row && row.parentNode) row.parentNode.removeChild(row);
                    ensureEmptyStateRow();
                    showNotice('Régime supprimé avec succès.', 'success');
                } else {
                    throw new Error(payload.message || 'Erreur lors de la suppression');
                }
            } catch (error) {
                showNotice(error.message || 'Erreur lors de la suppression.', 'error');
            }
        }

        // plus besoin de filterRegimes, le filtre se fait côté serveur

        function setCreateMode() {
            editIdInput.value = '';
            if (submitButton) {
                submitButton.textContent = createLabel;
            }
        }

        function removeEmptyStateRow() {
            const onlyRow = tbody.querySelector('tr');
            if (!onlyRow) {
                return;
            }
            const hasId = onlyRow.getAttribute('data-regime-id');
            if (!hasId && onlyRow.textContent.includes('Aucun régime trouvé')) {
                tbody.innerHTML = '';
            }
        }

        function ensureEmptyStateRow() {
            if (tbody.querySelectorAll('tr').length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Aucun régime trouvé.</td></tr>';
            }
        }

        function showNotice(message, type) {
            const notice = document.createElement('div');
            notice.className = 'alert';
            notice.style.marginBottom = '20px';
            notice.style.padding = '10px 15px';
            notice.style.borderRadius = '4px';
            if (type === 'success') {
                notice.style.backgroundColor = '#d4edda';
                notice.style.color = '#155724';
            } else {
                notice.style.backgroundColor = '#f8d7da';
                notice.style.color = '#721c24';
            }
            notice.textContent = message;
            form.parentNode.insertBefore(notice, form);
            setTimeout(() => notice.remove(), 3000);
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

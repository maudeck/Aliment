<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Gestion des Régimes'); ?></title>
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

    <script>
        (function(){
            const form = document.getElementById('regimeForm');
            const tbody = document.getElementById('regimesTableBody');
            const searchInput = document.getElementById('regimeSearch');
            const table = document.getElementById('regimesTable');

            // Charger les régimes au démarrage
            loadRegimes();

            // Événement de recherche instantanée
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim().toLowerCase();
                    filterRegimes(query);
                });
            }

            // Événement de soumission du formulaire
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    await addRegime();
                });
            }

            async function loadRegimes() {
                try {
                    const response = await fetch('<?= base_url('regimes/api'); ?>');
                    const regimes = await response.json();
                    renderRegimes(regimes);
                } catch (error) {
                    console.error('Erreur lors du chargement des régimes:', error);
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px; color: red;">Erreur lors du chargement.</td></tr>';
                }
            }

            function renderRegimes(regimes) {
                if (!Array.isArray(regimes) || regimes.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Aucun régime trouvé.</td></tr>';
                    return;
                }

                tbody.innerHTML = regimes.map(regime => `
                    <tr data-regime-id="${escapeHtml(regime.id || '')}" data-search="${escapeHtml((regime.nom || '') + ' ' + (regime.description || ''))}">
                        <td>
                            <strong>${escapeHtml(regime.nom || '')}</strong><br>
                            <small class="text-muted">${escapeHtml(regime.description || 'Sans description')}</small>
                        </td>
                        <td><strong>${escapeHtml(regime.variation_poids || '0')} kg</strong></td>
                        <td><small>V:${escapeHtml(regime.pourcentage_viande || '0')}% P:${escapeHtml(regime.pourcentage_poisson || '0')}% Vol:${escapeHtml(regime.pourcentage_volaille || '0')}%</small></td>
                        <td class="action-buttons">
                            <a href="<?= base_url('regimes/edit/'); ?>${regime.id}" class="btn-small primary">Modifier</a>
                            <button type="button" class="btn-small danger delete-regime" data-id="${regime.id}">Supprimer</button>
                        </td>
                    </tr>
                `).join('');

                // Ajouter les événements de suppression
                document.querySelectorAll('.delete-regime').forEach(btn => {
                    btn.addEventListener('click', async function() {
                        const id = this.dataset.id;
                        if (confirm('Êtes-vous sûr de vouloir supprimer ce régime ?')) {
                            await deleteRegime(id);
                        }
                    });
                });
            }

            async function addRegime() {
                const submitButton = form.querySelector('button[type="submit"]');
                const originalLabel = submitButton ? submitButton.textContent : 'Enregistrer le programme';
                
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Enregistrement...';
                }

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const payload = await response.json();

                    if (!payload.success) {
                        throw new Error(payload.message || 'Erreur inconnue');
                    }

                    form.reset();
                    await loadRegimes();

                    showNotice(payload.message || 'Régime ajouté avec succès.', 'success');
                } catch (error) {
                    showNotice(error.message || 'Erreur lors de l\'enregistrement.', 'error');
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = originalLabel;
                    }
                }
            }

            async function deleteRegime(id) {
                try {
                    const response = await fetch(`<?= base_url('regimes/delete/'); ?>${id}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const payload = await response.json();

                    if (payload.success) {
                        await loadRegimes();
                        showNotice('Régime supprimé avec succès.', 'success');
                    } else {
                        throw new Error(payload.message || 'Erreur lors de la suppression');
                    }
                } catch (error) {
                    showNotice(error.message || 'Erreur lors de la suppression.', 'error');
                }
            }

            function filterRegimes(query) {
                const rows = Array.from(tbody.querySelectorAll('tr'));
                
                if (!query) {
                    rows.forEach(row => row.style.display = '');
                    return;
                }

                rows.forEach(row => {
                    const searchText = (row.dataset.search || row.textContent).toLowerCase();
                    row.style.display = searchText.indexOf(query) !== -1 ? '' : 'none';
                });
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

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
            <section class="admin-hero">
                <div>
                    <h2>Gestion des Régimes</h2>
                    <p>Pilotez les programmes nutritionnels de la plateforme NutriLife.</p>
                </div>
            </section>

            <div class="admin-grid">
                <article class="admin-card full">
                    <h3>Ajouter un nouveau régime</h3>
                    <form id="regimeForm" class="admin-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="regimeName">Nom du régime</label>
                                <input id="regimeName" name="name" type="text" placeholder="ex: Keto Premium">
                            </div>
                            <div class="form-group">
                                <label for="regimeObjectif">Objectif</label>
                                <select id="regimeObjectif" name="objectif">
                                    <option value="perte">Perte de poids</option>
                                    <option value="prise">Prise de muscle</option>
                                    <option value="maintien">Maintien</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="regimePrice">Prix (AR)</label>
                                <input id="regimePrice" name="price" type="number" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="regimeDesc">Description détaillée</label>
                            <textarea id="regimeDesc" name="description" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Activités sportives</label>
                            <div id="activitiesRows" style="display:grid;gap:10px;">
                                <div class="activity-row" style="display:grid;grid-template-columns:1fr auto auto;gap:8px;align-items:center;">
                                    <select class="regime-activity-select" name="activities[]">
                                        <option value="">Sélectionner une activité...</option>
                                        <option value="Course à pied">Course à pied</option>
                                        <option value="Natation">Natation</option>
                                        <option value="Musculation">Musculation</option>
                                        <option value="Cyclisme">Cyclisme</option>
                                        <option value="Yoga">Yoga</option>
                                    </select>
                                    <button type="button" class="admin-button secondary add-activity-row" aria-label="Ajouter une activité">[+]</button>
                                    <button type="button" class="btn-small danger remove-activity-row" aria-label="Supprimer cette activité">[-]</button>
                                </div>
                            </div>
                            <small class="text-muted">Choisissez une activité puis cliquez sur [+] pour ajouter une autre ligne.</small>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="regimeProt">Protéines (%)</label>
                                <input id="regimeProt" name="proteines" type="number" value="30">
                            </div>
                            <div class="form-group">
                                <label for="regimeGlucides">Glucides (%)</label>
                                <input id="regimeGlucides" name="glucides" type="number" value="40">
                            </div>
                            <div class="form-group">
                                <label for="regimeLipides">Lipides (%)</label>
                                <input id="regimeLipides" name="lipides" type="number" value="30">
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
                            <input id="regimeSearch" class="search-input" type="search" placeholder="Recherche par nom, objectif ou description...">
                        </div>
                        <div>
                            <small class="text-muted">Filtre instantané (client-side)</small>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table class="admin-table" id="regimesTable">
                            <thead>
                                <tr>
                                    <th>Nom & Description</th>
                                    <th>Objectif</th>
                                    <th>Activités</th>
                                    <th>Macros</th>
                                    <th>Tarif</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Régime Keto</strong><br>
                                        <small class="text-muted">30 jours • -5kg attendus</small>
                                    </td>
                                    <td><span class="badge-obj perte">Perte de poids</span></td>
                                    <td><small>Course à pied, Yoga</small></td>
                                    <td><small>P:30 G:10 L:60</small></td>
                                    <td><strong>299 AR</strong></td>
                                    <td class="action-buttons">
                                        <a href="#" class="btn-small primary">Modifier</a>
                                        <a href="#" class="btn-small danger">Supprimer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Prise de Masse Express</strong><br>
                                        <small class="text-muted">45 jours • +4kg attendus</small>
                                    </td>
                                    <td><span class="badge-obj prise">Prise de muscle</span></td>
                                    <td><small>Musculation, Natation</small></td>
                                    <td><small>P:40 G:40 L:20</small></td>
                                    <td><strong>450 AR</strong></td>
                                    <td class="action-buttons">
                                        <a href="#" class="btn-small primary">Modifier</a>
                                        <a href="#" class="btn-small danger">Supprimer</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <script>
                        (function(){
                            const input = document.getElementById('regimeSearch');
                            const table = document.getElementById('regimesTable');
                            if (!input || !table) return;
                            input.addEventListener('input', function(){
                                const q = this.value.trim().toLowerCase();
                                const rows = Array.from(table.tBodies[0].rows);
                                if (!q) {
                                    rows.forEach(r => r.style.display = '');
                                    return;
                                }
                                rows.forEach(r => {
                                    const text = r.textContent.replace(/\s+/g,' ').toLowerCase();
                                    r.style.display = text.indexOf(q) !== -1 ? '' : 'none';
                                });
                            });

                            const form = document.getElementById('regimeForm');
                            const tbody = table.tBodies[0];
                            if (!form || !tbody) return;

                            const activitiesRows = document.getElementById('activitiesRows');
                            if (activitiesRows) {
                                activitiesRows.addEventListener('click', function(e){
                                    const addBtn = e.target.closest('.add-activity-row');
                                    const removeBtn = e.target.closest('.remove-activity-row');

                                    if (addBtn) {
                                        e.preventDefault();
                                        const row = document.createElement('div');
                                        row.className = 'activity-row';
                                        row.style.display = 'grid';
                                        row.style.gridTemplateColumns = '1fr auto auto';
                                        row.style.gap = '8px';
                                        row.style.alignItems = 'center';
                                        row.innerHTML = `
                                            <select class="regime-activity-select" name="activities[]">
                                                <option value="">Sélectionner une activité...</option>
                                                <option value="Course à pied">Course à pied</option>
                                                <option value="Natation">Natation</option>
                                                <option value="Musculation">Musculation</option>
                                                <option value="Cyclisme">Cyclisme</option>
                                                <option value="Yoga">Yoga</option>
                                            </select>
                                            <button type="button" class="admin-button secondary add-activity-row" aria-label="Ajouter une activité">[+]</button>
                                            <button type="button" class="btn-small danger remove-activity-row" aria-label="Supprimer cette activité">[-]</button>
                                        `;
                                        activitiesRows.appendChild(row);
                                    }

                                    if (removeBtn) {
                                        e.preventDefault();
                                        const row = removeBtn.closest('.activity-row');
                                        if (!row) return;
                                        const totalRows = activitiesRows.querySelectorAll('.activity-row').length;
                                        if (totalRows > 1) {
                                            row.remove();
                                        }
                                    }
                                });
                            }

                            form.addEventListener('submit', function(e){
                                e.preventDefault();
                                const name = document.getElementById('regimeName').value.trim();
                                const objectif = document.getElementById('regimeObjectif').value;
                                const price = document.getElementById('regimePrice').value.trim();
                                const desc = document.getElementById('regimeDesc').value.trim();
                                const activities = Array.from(document.querySelectorAll('.regime-activity-select'))
                                    .map(function(select){ return select.value.trim(); })
                                    .filter(function(value){ return value !== ''; });
                                const prot = document.getElementById('regimeProt').value || '0';
                                const gluc = document.getElementById('regimeGlucides').value || '0';
                                const lip = document.getElementById('regimeLipides').value || '0';

                                if (!name) {
                                    alert('Le nom du régime est requis.');
                                    return;
                                }

                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>
                                        <strong>${escapeHtml(name)}</strong><br>
                                        <small class="text-muted">${escapeHtml(desc || 'Sans description')}</small>
                                    </td>
                                    <td><span class="badge-obj ${escapeHtml(objectif)}">${escapeHtml(capitalize(objectif))}</span></td>
                                    <td><small>${escapeHtml(activities.length ? activities.join(', ') : 'Aucune activité')}</small></td>
                                    <td><small>P:${escapeHtml(prot)} G:${escapeHtml(gluc)} L:${escapeHtml(lip)}</small></td>
                                    <td><strong>${escapeHtml(price ? price + ' AR' : '0 AR')}</strong></td>
                                    <td class="action-buttons">
                                        <a href="#" class="btn-small primary">Modifier</a>
                                        <a href="#" class="btn-small danger">Supprimer</a>
                                    </td>
                                `;
                                tbody.appendChild(tr);
                                form.reset();

                                if (activitiesRows) {
                                    const rows = Array.from(activitiesRows.querySelectorAll('.activity-row'));
                                    rows.forEach(function(row, idx){
                                        if (idx > 0) row.remove();
                                    });
                                }

                                const notice = document.createElement('div');
                                notice.className = 'alert';
                                notice.textContent = 'Régime ajouté (simulation UI).';
                                form.parentNode.insertBefore(notice, form);
                                setTimeout(() => notice.remove(), 3000);
                            });

                            function escapeHtml(s){
                                return String(s)
                                    .replace(/&/g, '&amp;')
                                    .replace(/</g, '&lt;')
                                    .replace(/>/g, '&gt;')
                                    .replace(/"/g, '&quot;');
                            }

                            function capitalize(s){
                                if (!s) return '';
                                return s.charAt(0).toUpperCase() + s.slice(1);
                            }
                        })();
                    </script>
                </article>
            </div>
        </main>
    </div>
</body>
</html>
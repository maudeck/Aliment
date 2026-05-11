<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc((string) ($title ?? 'Codes de recharge')); ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/admin-codes.css'); ?>">
    <style>
     
    </style>
</head>
<body>
<div class="admin-shell">
    <?= view('partials/admin_sidebar'); ?>

    <main class="admin-content">

        <section class="admin-hero">
            <div>
                <h2>Codes de Recharge Portefeuille</h2>
                <p>Créez, gérez et suivez l'utilisation des codes de recharge.</p>
            </div>
            <div class="admin-pill">Gestion des codes</div>
        </section>

        <!-- Flash messages -->
        <?php if (!empty($flash_succes)): ?>
            <div class="flash-ok">✓ <?= esc($flash_succes) ?></div>
        <?php endif; ?>
        <?php if (!empty($flash_erreur)): ?>
            <div class="flash-err">✗ <?= esc($flash_erreur) ?></div>
        <?php endif; ?>

        <div class="filter-bar">
            <input id="searchCode" type="text" placeholder="Rechercher un code">
            <select id="filterStatus" onchange="filterTable()">
                    <option value="">Tous les statuts</option>
                    <option value="dispo">Disponibles</option>
                    <option value="use">Utilisés</option>
                </select>
        </div>

        <div class="table-wrapper">
            <table class="codes-table" id="codesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Utilisé par</th>
                        <th>Utilisé le</th>
                        <th>Créé le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($codes)): ?>
                    <tr class="empty-row"><td colspan="8">Aucun code pour le moment.</td></tr>
                <?php else: ?>
                    <?php foreach ($codes as $c): ?>
                    <tr data-status="<?= $c['est_utilise'] ? 'use' : 'dispo' ?>">
                        <td><?= esc((string) ($c['id'] ?? '')) ?></td>
                        <td><span class="code-mono"><?= esc((string) ($c['code'] ?? '')) ?></span></td>
                        <td><strong><?= number_format((float) ($c['montant'] ?? 0), 0, ',', ' ') ?> Ar</strong></td>
                        <td>
                            <?php if ($c['est_utilise']): ?>
                                <span class="badge-use">✗ Utilisé</span>
                            <?php else: ?>
                                <span class="badge-dispo">✓ Disponible</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($c['utilisateur_nom']): ?>
                                <div style="font-weight:600"><?= esc((string) ($c['utilisateur_nom'] ?? '')) ?></div>
                                <div style="font-size:0.75rem;color:var(--muted)"><?= esc((string) ($c['utilisateur_email'] ?? '')) ?></div>
                            <?php else: ?>
                                <span style="color:var(--muted)">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:0.8rem;color:var(--muted)">
                            <?= $c['utilise_le'] ? date('d/m/Y H:i', strtotime($c['utilise_le'])) : '—' ?>
                        </td>
                        <td style="font-size:0.8rem;color:var(--muted)">
                            <?= date('d/m/Y', strtotime($c['created_at'])) ?>
                        </td>
                        <td>
                            <?php if (!$c['est_utilise']): ?>
                            <form method="POST" action="<?= base_url('/admin/codes/delete/' . $c['id']) ?>" onsubmit="return confirm('Supprimer ce code ?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-danger">Supprimer</button>
                            </form>
                            <?php else: ?>
                                <span style="color:var(--muted);font-size:0.78rem">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tab : Créer un code -->
        <div id="tab-create" class="tab-panel">
            <div class="codes-form">
                <h4>Créer un code de recharge unique</h4>
                <p style="font-size:0.82rem;color:var(--muted);margin-bottom:16px">
                    Codes existants dans la BDD : <strong>NUTRI-2024-AAAA</strong>, <strong>NUTRI-2024-BBBB</strong>, <strong>NUTRI-2024-CCCC</strong>
                </p>
                <form method="POST" action="<?= base_url('/admin/codes/store') ?>">
                    <?= csrf_field() ?>
                    <div class="form-row-inline">
                        <div class="form-group" style="flex:2">
                            <label>Code *</label>
                            <input type="text" name="code" placeholder="Ex : NUTRI-2025-PROMO" maxlength="100" required style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label>Montant (Ar) *</label>
                            <input type="number" name="montant" placeholder="50000" min="1" step="1" required>
                        </div>
                        <button type="submit" class="btn-primary">✓ Créer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab : Génération en lot -->
        <div id="tab-batch" class="tab-panel">
            <div class="codes-form">
                <h4>Générer plusieurs codes automatiquement</h4>
                <p style="font-size:0.82rem;color:var(--muted);margin-bottom:16px">
                    Les codes sont générés aléatoirement avec le préfixe choisi. Maximum 50 codes par lot.
                </p>
                <form method="POST" action="<?= base_url('/admin/codes/generate') ?>">
                    <?= csrf_field() ?>
                    <div class="form-row-inline">
                        <div class="form-group">
                            <label>Préfixe</label>
                            <input type="text" name="prefix" value="NUTRI" maxlength="20" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label>Nombre (max 50)</label>
                            <input type="number" name="nombre" value="5" min="1" max="50" required>
                        </div>
                        <div class="form-group">
                            <label>Montant par code (Ar) *</label>
                            <input type="number" name="montant" placeholder="50000" min="1" step="1" required>
                        </div>
                        <button type="submit" class="btn-primary">⚡ Générer</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + name).classList.add('active');
}

function filterTable() {
    const search = document.getElementById('searchCode').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    document.querySelectorAll('#codesTable tbody tr').forEach(tr => {
        if (tr.classList.contains('empty-row')) return;
        const code   = tr.querySelector('.code-mono')?.textContent.toLowerCase() ?? '';
        const trStatus = tr.dataset.status;
        const matchSearch = code.includes(search);
        const matchStatus = !status || trStatus === status;
        tr.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
}

// Majuscules automatiques sur le champ code
document.querySelectorAll('input[name="code"], input[name="prefix"]').forEach(el => {
    el.addEventListener('input', () => { el.value = el.value.toUpperCase(); });
});
</script>
</body>
</html>
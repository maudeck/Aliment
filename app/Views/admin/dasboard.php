<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Dashboard'); ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        /* ── Dashboard extras ── */
        .dash-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            margin: 0 0 16px 0;
        }

        /* KPI cards */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }
        .kpi-card {
            background: var(--panel);
            border-radius: 14px;
            padding: 20px 22px;
            border: 1.5px solid var(--line);
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .kpi-card .kpi-label {
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 500;
        }
        .kpi-card .kpi-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1;
        }
        .kpi-card .kpi-sub {
            font-size: 0.75rem;
            color: var(--muted);
        }
        .kpi-card.accent { border-color: var(--primary); }
        .kpi-card.accent .kpi-value { color: var(--primary); }
        .kpi-card.gold { border-color: #f0a500; }
        .kpi-card.gold .kpi-value { color: #f0a500; }

        /* Chart grid */
        .chart-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 32px;
        }
        .chart-grid.three { grid-template-columns: 1fr 1fr 1fr; }
        .chart-card {
            background: var(--panel);
            border-radius: 14px;
            padding: 22px;
            border: 1.5px solid var(--line);
        }
        .chart-card h4 {
            margin: 0 0 18px 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
        }
        .chart-card.full { grid-column: 1 / -1; }
        .chart-wrap {
            position: relative;
            height: 220px;
        }
        .chart-wrap.tall { height: 280px; }

        /* IMC stat band */
        .imc-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 14px;
        }
        .imc-stat {
            flex: 1;
            background: var(--bg);
            border-radius: 10px;
            padding: 12px 14px;
            text-align: center;
        }
        .imc-stat .val {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
        }
        .imc-stat .lbl {
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 2px;
        }

        /* Recent users table */
        .dash-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        .dash-table th {
            text-align: left;
            padding: 8px 12px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted);
            border-bottom: 1.5px solid var(--line);
        }
        .dash-table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
            color: var(--text);
        }
        .dash-table tr:last-child td { border-bottom: none; }
        .badge-gold {
            background: #fff3cd;
            color: #856404;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .badge-normal {
            background: #e8f4fd;
            color: var(--primary);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        /* Progress bar for codes */
        .progress-bar-wrap {
            background: var(--bg);
            border-radius: 99px;
            height: 10px;
            margin: 10px 0 6px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: var(--primary);
            transition: width .4s ease;
        }
    </style>
</head>
<body>
<div class="admin-shell">

    <!-- Sidebar -->
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
                <span>Statistiques & graphes</span>
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

    <!-- Main content -->
    <main class="admin-content">

        <!-- Hero -->
        <section class="admin-hero">
            <div>
                <h2>Tableau de bord</h2>
                <p>Vue d'ensemble des utilisateurs, régimes, IMC et activité de la plateforme.</p>
            </div>
            <div class="admin-pill">Accès admin actif</div>
        </section>

        <!-- ── KPI Cards ── -->
        <p class="dash-section-title">Indicateurs clés</p>
        <div class="kpi-grid">
            <div class="kpi-card accent">
                <span class="kpi-label">Utilisateurs inscrits</span>
                <span class="kpi-value"><?= $totalUsers ?></span>
                <span class="kpi-sub"><?= $totalGold ?> membres Gold</span>
            </div>
            <div class="kpi-card gold">
                <span class="kpi-label">Membres Gold</span>
                <span class="kpi-value"><?= $totalGold ?></span>
                <span class="kpi-sub"><?= $totalUsers > 0 ? round($totalGold / $totalUsers * 100) : 0 ?>% des inscrits</span>
            </div>
            <div class="kpi-card">
                <span class="kpi-label">Régimes disponibles</span>
                <span class="kpi-value"><?= $totalRegimes ?></span>
                <span class="kpi-sub"><?= $totalActivites ?> activités sportives</span>
            </div>
            <div class="kpi-card">
                <span class="kpi-label">Chiffre d'affaires</span>
                <span class="kpi-value"><?= number_format($chiffreAffaires, 0, ',', ' ') ?></span>
                <span class="kpi-sub">Ar — total des ventes</span>
            </div>
            <div class="kpi-card">
                <span class="kpi-label">Codes disponibles</span>
                <span class="kpi-value"><?= $codesDispo ?></span>
                <span class="kpi-sub"><?= $codesUtilises ?>/<?= $codesTotal ?> utilisés</span>
            </div>
            <div class="kpi-card">
                <span class="kpi-label">Profils IMC renseignés</span>
                <span class="kpi-value"><?= $imcCount ?></span>
                <span class="kpi-sub">IMC moyen : <?= $imcMoyenne ?></span>
            </div>
        </div>

        <!-- ── Graphes ligne 1 : IMC + Inscriptions ── -->
        <p class="dash-section-title">Santé & activité</p>
        <div class="chart-grid">

            <!-- IMC Distribution -->
            <div class="chart-card">
                <h4>📊 Distribution IMC des utilisateurs</h4>
                <div class="imc-stats">
                    <div class="imc-stat">
                        <div class="val"><?= $imcMoyenne ?></div>
                        <div class="lbl">IMC moyen</div>
                    </div>
                    <div class="imc-stat">
                        <div class="val"><?= $imcMin ?></div>
                        <div class="lbl">IMC min</div>
                    </div>
                    <div class="imc-stat">
                        <div class="val"><?= $imcMax ?></div>
                        <div class="lbl">IMC max</div>
                    </div>
                    <div class="imc-stat">
                        <div class="val"><?= $imcCount ?></div>
                        <div class="lbl">Profils</div>
                    </div>
                </div>
                <div class="chart-wrap">
                    <canvas id="chartImc"></canvas>
                </div>
            </div>

            <!-- Inscriptions -->
            <div class="chart-card">
                <h4>📈 Nouvelles inscriptions (6 mois)</h4>
                <div class="chart-wrap">
                    <canvas id="chartInscriptions"></canvas>
                </div>
            </div>

        </div>

        <!-- ── Graphes ligne 2 : Objectifs + Genres + Top Régimes ── -->
        <p class="dash-section-title">Profils & préférences</p>
        <div class="chart-grid three">

            <!-- Objectifs -->
            <div class="chart-card">
                <h4>🎯 Objectifs des utilisateurs</h4>
                <div class="chart-wrap">
                    <canvas id="chartObjectifs"></canvas>
                </div>
            </div>

            <!-- Genres -->
            <div class="chart-card">
                <h4>👤 Répartition par genre</h4>
                <div class="chart-wrap">
                    <canvas id="chartGenres"></canvas>
                </div>
            </div>

            <!-- Top régimes -->
            <div class="chart-card">
                <h4>🥗 Régimes les plus achetés</h4>
                <div class="chart-wrap">
                    <canvas id="chartRegimes"></canvas>
                </div>
            </div>

        </div>

        <!-- ── Codes portefeuille ── -->
        <p class="dash-section-title">Codes de recharge</p>
        <div class="chart-grid">
            <div class="chart-card">
                <h4>🎫 Utilisation des codes</h4>
                <?php $pct = $codesTotal > 0 ? round($codesUtilises / $codesTotal * 100) : 0; ?>
                <div style="display:flex; gap:24px; margin-bottom:12px;">
                    <div style="flex:1; text-align:center;">
                        <div style="font-size:1.6rem; font-weight:700; color:var(--primary)"><?= $codesUtilises ?></div>
                        <div style="font-size:0.75rem; color:var(--muted)">Utilisés</div>
                    </div>
                    <div style="flex:1; text-align:center;">
                        <div style="font-size:1.6rem; font-weight:700; color:#2ecc71"><?= $codesDispo ?></div>
                        <div style="font-size:0.75rem; color:var(--muted)">Disponibles</div>
                    </div>
                    <div style="flex:1; text-align:center;">
                        <div style="font-size:1.6rem; font-weight:700; color:var(--text)"><?= $codesTotal ?></div>
                        <div style="font-size:0.75rem; color:var(--muted)">Total</div>
                    </div>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width:<?= $pct ?>%"></div>
                </div>
                <div style="font-size:0.75rem; color:var(--muted); text-align:right"><?= $pct ?>% utilisés</div>
                <div class="chart-wrap" style="height:160px; margin-top:12px;">
                    <canvas id="chartCodes"></canvas>
                </div>
            </div>

            <!-- Utilisateurs récents -->
            <div class="chart-card">
                <h4>🕐 Dernières inscriptions</h4>
                <?php if (empty($recentUsers)): ?>
                    <p style="color:var(--muted); font-size:0.85rem;">Aucun utilisateur pour le moment.</p>
                <?php else: ?>
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Genre</th>
                                <th>Statut</th>
                                <th>Inscrit le</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recentUsers as $u): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600"><?= esc($u['nom']) ?></div>
                                    <div style="font-size:0.72rem; color:var(--muted)"><?= esc($u['email']) ?></div>
                                </td>
                                <td><?= esc($u['genre'] ?? '—') ?></td>
                                <td>
                                    <?php if ($u['is_gold']): ?>
                                        <span class="badge-gold">⭐ Gold</span>
                                    <?php else: ?>
                                        <span class="badge-normal">Standard</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size:0.8rem; color:var(--muted)">
                                    <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

    </main>
</div>

<script>
// ── Données PHP → JS ────────────────────────────────────────────
const imcLabels  = <?= json_encode(array_keys($imcCategories)) ?>;
const imcData    = <?= json_encode(array_values($imcCategories)) ?>;

const inscMois   = <?= json_encode(array_column($inscriptionsRows, 'mois')) ?>;
const inscData   = <?= json_encode(array_map('intval', array_column($inscriptionsRows, 'total'))) ?>;

const objLabels  = <?= json_encode(array_column($objectifRows, 'nom')) ?>;
const objData    = <?= json_encode(array_map('intval', array_column($objectifRows, 'total'))) ?>;

const genLabels  = <?= json_encode(array_column($genreRows, 'nom')) ?>;
const genData    = <?= json_encode(array_map('intval', array_column($genreRows, 'total'))) ?>;

const regLabels  = <?= json_encode(array_column($topRegimesRows, 'nom')) ?>;
const regData    = <?= json_encode(array_map('intval', array_column($topRegimesRows, 'total'))) ?>;

// Palette
const palette = ['#6096ba','#274c77','#a3cef1','#8b8c89','#e7ecef'];
const imcColors = ['#a3cef1','#2ecc71','#f39c12','#e74c3c'];

Chart.defaults.font.family = "'Space Grotesk', sans-serif";
Chart.defaults.color = '#8b8c89';

// ── 1. IMC Doughnut ─────────────────────────────────────────────
new Chart(document.getElementById('chartImc'), {
    type: 'doughnut',
    data: {
        labels: imcLabels,
        datasets: [{ data: imcData, backgroundColor: imcColors, borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } }
        },
        maintainAspectRatio: false
    }
});

// ── 2. Inscriptions Line ────────────────────────────────────────
new Chart(document.getElementById('chartInscriptions'), {
    type: 'line',
    data: {
        labels: inscMois.length ? inscMois : ['—'],
        datasets: [{
            label: 'Inscriptions',
            data: inscData.length ? inscData : [0],
            borderColor: '#6096ba',
            backgroundColor: 'rgba(96,150,186,0.12)',
            borderWidth: 2.5,
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#274c77'
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        },
        maintainAspectRatio: false
    }
});

// ── 3. Objectifs Bar ────────────────────────────────────────────
new Chart(document.getElementById('chartObjectifs'), {
    type: 'bar',
    data: {
        labels: objLabels.length ? objLabels : ['Aucun'],
        datasets: [{
            data: objData.length ? objData : [0],
            backgroundColor: palette,
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 20 } }
        },
        maintainAspectRatio: false
    }
});

// ── 4. Genres Pie ───────────────────────────────────────────────
new Chart(document.getElementById('chartGenres'), {
    type: 'pie',
    data: {
        labels: genLabels.length ? genLabels : ['—'],
        datasets: [{
            data: genData.length ? genData : [1],
            backgroundColor: ['#6096ba','#a3cef1','#8b8c89'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } } },
        maintainAspectRatio: false
    }
});

// ── 5. Top Régimes Bar horizontal ──────────────────────────────
new Chart(document.getElementById('chartRegimes'), {
    type: 'bar',
    data: {
        labels: regLabels.length ? regLabels : ['Aucun'],
        datasets: [{
            data: regData.length ? regData : [0],
            backgroundColor: '#274c77',
            borderRadius: 6,
            borderSkipped: false
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
            y: { grid: { display: false }, ticks: { font: { size: 10 } } }
        },
        maintainAspectRatio: false
    }
});

// ── 6. Codes Doughnut ──────────────────────────────────────────
new Chart(document.getElementById('chartCodes'), {
    type: 'doughnut',
    data: {
        labels: ['Utilisés', 'Disponibles'],
        datasets: [{
            data: [<?= $codesUtilises ?>, <?= $codesDispo ?>],
            backgroundColor: ['#6096ba','#e7ecef'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        cutout: '60%',
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } } },
        maintainAspectRatio: false
    }
});
</script>
</body>
</html>
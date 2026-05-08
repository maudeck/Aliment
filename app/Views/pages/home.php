<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="<?= base_url('css/home.css'); ?>">

        
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">AL</div>
                <div>
                    <p class="brand-name">Aliment</p>
                    <p class="brand-subtitle">Tableau de bord</p>
                </div>
            </div>

            <nav class="nav">
                <a class="nav-link active" href="<?= base_url('/home'); ?>">Accueil</a>
                <a class="nav-link" href="<?= base_url('/register/objectif'); ?>">Objectif</a>
                <a class="nav-link" href="#">Regimes</a>
                <a class="nav-link" href="#">Activites</a>
                <a class="nav-link" href="#">portefeuille</a>
                <a class="nav-link" href="#">abonement</a>
            </nav>

            <div class="sidebar-footer">
                <a class="logout" href="<?= base_url('/logout'); ?>">Se deconnecter</a>
            </div>
        </aside>

        <main class="content">
            <header class="page-header">
                <div>
                    <p class="eyebrow">Bonjour,</p>
                    <h1><?= isset($user['nom']) ? htmlspecialchars($user['nom']) : 'Utilisateur'; ?></h1>
                    <p class="subtitle">Votre plan de suivi alimentaire est pret.</p>
                </div>
                <div class="status-pill">
                    Objectif: <strong><?= !empty($objectifNom) ? htmlspecialchars($objectifNom) : 'non defini'; ?></strong>
                </div>
            </header>

            <section class="stats">
                <div class="stat-card">
                    <p class="stat-label">Poids actuel</p>
                    <p class="stat-value">
                        <?= isset($etat['poids']) ? htmlspecialchars($etat['poids']) : 'N/A'; ?> <span>kg</span>
                    </p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Taille</p>
                    <p class="stat-value">
                        <?= isset($etat['taille']) ? htmlspecialchars($etat['taille']) : 'N/A'; ?> <span>m</span>
                    </p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">IMC</p>
                    <p class="stat-value">
                        <?= isset($etat['imc']) ? number_format($etat['imc'], 1) : 'N/A'; ?>
                    </p>
                </div>
            </section>

            <section class="grid">
                <div class="panel">
                    <h2>Resume du jour</h2>
                    <p>Suivez vos repas et vos activites pour rester motive. Vos recommandations apparaitront ici.</p>
                    <button class="primary">Voir mes regimes</button>
                </div>
                <div class="panel alt">
                    <h2>Prochaine etape</h2>
                    <p>Choisissez une activite adaptee a votre objectif et planifiez votre semaine.</p>
                    <button class="secondary">Voir les activites</button>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
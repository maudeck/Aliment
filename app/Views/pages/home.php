<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="<?= base_url('css/home.css'); ?>">

        
</head>
<body>
    <div class="container">
        <h1>Bienvenue <?= isset($user['nom']) ? htmlspecialchars($user['nom']) : 'Utilisateur'; ?></h1>
        
        <p class="welcome-message">
            Votre objectif est de <strong><?= isset($etat['objectif']) ? htmlspecialchars($etat['objectif']) : 'non défini'; ?></strong>
        </p>

        <?php if (isset($user) && isset($etat)): ?>
            <div class="user-info">
                <div class="info-item">
                    <span class="info-label">Poids actuel :</span> <?= isset($etat['poids']) ? htmlspecialchars($etat['poids']) : 'N/A'; ?> kg
                </div>
                <div class="info-item">
                    <span class="info-label">Taille :</span> <?= isset($etat['taille']) ? htmlspecialchars($etat['taille']) : 'N/A'; ?> m
                </div>
                <div class="info-item">
                    <span class="info-label">IMC :</span> <?= isset($etat['imc']) ? number_format($etat['imc'], 1) : 'N/A'; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
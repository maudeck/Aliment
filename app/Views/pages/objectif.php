<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objectif – NutriLife</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="<?= base_url('css/objectif.css'); ?>">
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div>
            <div class="brand">
                <?php if (!empty($logo_path)): ?>
                    <img src="<?= base_url('logo/logo_sans_background.png'); ?>" alt="Logo" class="brand-logo">
                <?php else: ?>
                    <div class="brand-logo-fallback"></div>
                <?php endif; ?>
                <div class="brand-text">
                    <h2>NutriLife</h2>
                    <small>Suivi alimentaire</small>
                </div>
            </div>

            <nav class="nav">
                <a class="nav-link" href="<?= base_url('/home'); ?>">Accueil</a>
                <a class="nav-link active" href="<?= base_url('/register/objectif'); ?>">Objectifs</a>
                <a class="nav-link" href="<?= base_url('/regimes'); ?>">Régimes</a>
                <a class="nav-link" href="<?= base_url('/activites'); ?>">Activités</a>
                <a class="nav-link" href="<?= base_url('/portefeuille'); ?>">Portefeuille</a>
                <a class="nav-link" href="<?= base_url('/home#gold-offer'); ?>">Option Gold</a>
            </nav>
        </div>

        <a class="logout" href="<?= base_url('/logout'); ?>">Se déconnecter</a>
    </div>

    <!-- MAIN -->
    <div class="main">
        <div class="container">
        <h2 class="title">Votre Résultat IMC</h2>

        <div class="imc"></div>

        <p class="description">
            Vous êtes en <br>
            Choisissez votre objectif :
        </p>

        <?php if (!empty($errors)): ?>
            <div style="background: #fee; border: 1px solid #fcc; border-radius: 8px; padding: 12px; margin-bottom: 20px; color: #c33;">
                <ul style="margin-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/register/objectif/store'); ?>" method="POST" id="objectifForm">
            <?= csrf_field(); ?>

            <?php if (!empty($objectifs)): ?>
                <?php foreach ($objectifs as $objectif): ?>
                    <label class="goal-card" for="objectif-<?= $objectif['id']; ?>" onclick="selectGoal(this, '<?= $objectif['id']; ?>')">
                        <input
                            type="radio"
                            id="objectif-<?= $objectif['id']; ?>"
                            name="objectif_id"
                            value="<?= $objectif['id']; ?>"
                            <?= old('objectif_id') == $objectif['id'] ? 'checked' : ''; ?>
                            hidden
                        >
                        <div class="goal-title"><?= htmlspecialchars($objectif['nom']); ?></div>
                        <?php if (!empty($objectif['description'])): ?>
                            <div class="goal-text"><?= htmlspecialchars($objectif['description']); ?></div>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>

            <button type="submit" class="btn">Continuer</button>
        </form>
        </div><!-- /container -->
    </div><!-- /main -->

    <script>
        function selectGoal(selectedCard, value) {
            let cards = document.querySelectorAll('.goal-card');

            cards.forEach(card => {
                card.classList.remove('active');
            });

            selectedCard.classList.add('active');
            const radio = selectedCard.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
        }

        // Pour restaurer le statut active apres rechargement
        document.addEventListener('DOMContentLoaded', function() {
            let cards = document.querySelectorAll('.goal-card');
            cards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    card.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
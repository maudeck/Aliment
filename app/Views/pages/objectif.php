<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('css/objectif.css'); ?>">
</head>
<body>
    <div class="container">
        <h2 class="title">Votre Résultat IMC</h2>

        <div class="imc"><?= $imc; ?></div>

        <p class="description">
            Vous êtes en <?= strtolower($statut); ?>. <br>
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

            <!-- Perdre -->
            <div class="goal-card" onclick="selectGoal(this, 'perdre')">
                <div class="goal-icon">🔥</div>
                <div class="goal-title">Perdre du poids</div>
                <div class="goal-text">
                    Réduire votre masse corporelle et atteindre un poids plus sain.
                </div>
            </div>

            <!-- Maintenir -->
            <div class="goal-card" onclick="selectGoal(this, 'maintenir')">
                <div class="goal-icon">⚖️</div>
                <div class="goal-title">Maintenir un IMC idéal</div>
                <div class="goal-text">
                    Stabiliser votre poids actuel et garder une bonne santé.
                </div>
            </div>

            <!-- Gagner -->
            <div class="goal-card" onclick="selectGoal(this, 'gagner')">
                <div class="goal-icon">💪</div>
                <div class="goal-title">Gagner du poids</div>
                <div class="goal-text">
                    Augmenter votre poids ou votre masse musculaire.
                </div>
            </div>

            <input type="hidden" name="objectif" id="objectif" value="<?= old('objectif') ?? ''; ?>">
            <button type="submit" class="btn">Continuer</button>
        </form>
    </div>

    <script>
        function selectGoal(selectedCard, value) {
            let cards = document.querySelectorAll('.goal-card');

            cards.forEach(card => {
                card.classList.remove('active');
            });

            selectedCard.classList.add('active');
            document.getElementById('objectif').value = value;
        }

        // Pour restaurer le statut active apres rechargement
        document.addEventListener('DOMContentLoaded', function() {
            let activeObjectif = document.getElementById('objectif').value;
            if (activeObjectif) {
                let cards = document.querySelectorAll('.goal-card');
                let index = ['perdre', 'maintenir', 'gagner'].indexOf(activeObjectif);
                if (index !== -1 && cards[index]) {
                    cards[index].classList.add('active');
                }
            }
        });
    </script>
</body>
</html>
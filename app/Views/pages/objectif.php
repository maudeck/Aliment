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

            <?php if (!empty($objectifs)): ?>
                <?php foreach ($objectifs as $objectif): ?>
                    <div class="goal-card" onclick="selectGoal(this, '<?= $objectif['id']; ?>')">
                        <div class="goal-icon">🎯</div>
                        <div class="goal-title"><?= htmlspecialchars($objectif['nom']); ?></div>
                        <?php if (!empty($objectif['description'])): ?>
                            <div class="goal-text"><?= htmlspecialchars($objectif['description']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <input type="hidden" name="objectif_id" id="objectif_id" value="<?= old('objectif_id') ?? ''; ?>">
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
            document.getElementById('objectif_id').value = value;
        }

        // Pour restaurer le statut active apres rechargement
        document.addEventListener('DOMContentLoaded', function() {
            let activeObjectif = document.getElementById('objectif_id').value;
            if (activeObjectif) {
                let cards = document.querySelectorAll('.goal-card');
                cards.forEach(card => {
                    if (card.getAttribute('onclick').includes("'" + activeObjectif + "'")) {
                        card.classList.add('active');
                    }
                }
            }
        });
    </script>
</body>
</html>
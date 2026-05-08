<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> </title>
    <link rel="stylesheet" href="<?= base_url('css/objectif.css'); ?>">
</head>
<body>
    <div class="container">
        <h2 class="title">Votre Résultat IMC</h2>

        <div class="imc"</div>

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
                        <div class="goal-icon">🎯</div>
                        <div class="goal-title"><?= htmlspecialchars($objectif['nom']); ?></div>
                        <?php if (!empty($objectif['description'])): ?>
                            <div class="goal-text"><?= htmlspecialchars($objectif['description']); ?></div>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>

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
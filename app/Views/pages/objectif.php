<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objectif – NutriLife</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">    <link rel="stylesheet" href="<?= base_url('css/sidebar.css'); ?>">    <link rel="stylesheet" href="<?= base_url('css/objectif.css'); ?>">
</head>
<body>
    <?= view('partials/sidebar_front', ['active' => 'objectif', 'logo_path' => $logo_path ?? null]); ?>

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

        <form action="<?= base_url('/register/objectif/store'); ?>" method="POST" id="objectifForm" class="ajax-objectif-form">
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

        (function () {
            const form = document.getElementById('objectifForm');
            if (!form) return;

            function showNotice(target, message, type) {
                const box = document.createElement('div');
                box.className = type === 'success' ? 'flash-succes' : 'flash-erreur';
                box.style.marginTop = '10px';
                box.textContent = message;
                target.parentNode.insertBefore(box, target);
                setTimeout(() => box.remove(), 3000);
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const button = form.querySelector('button[type="submit"]');
                const original = button ? button.textContent : '';

                if (button) {
                    button.disabled = true;
                    button.textContent = 'Chargement...';
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(form)
                    });

                    const payload = await response.json();

                    if (!payload.success) {
                        throw new Error(payload.message || 'Objectif invalide.');
                    }

                    showNotice(form, payload.message || 'Objectif enregistré.', 'success');
                    setTimeout(() => {
                        window.location.href = '<?= base_url('/home'); ?>';
                    }, 700);
                } catch (error) {
                    showNotice(form, error.message || 'Erreur réseau.', 'error');
                } finally {
                    if (button) {
                        button.disabled = false;
                        button.textContent = original;
                    }
                }
            });
        })();
    </script>
</body>
</html>
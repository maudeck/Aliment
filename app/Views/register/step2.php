<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Etape 2</title>
    <link rel="stylesheet" href="<?= base_url('css/register-step2.css'); ?>">
</head>
<body>
    <main class="register-shell register-shell--step2">
        <section class="register-visual" aria-hidden="true">
            <div class="register-visual-overlay"></div>
            <div class="register-visual-quote">Votre santé commence dans votre assiette.</div>
            <img src="<?= base_url('image/healthy-menu_loginpages.jpg'); ?>" alt="Visuel NutriLife" class="register-visual-image">
        </section>

        <section class="register-content">
            <section class="card register-card">
                <h1>Healthy bowl</h1>
                <p class="step">info sante</p>

        <?php if (!empty($errors)): ?>
            <div class="alert">
                <strong>Erreurs:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

                <form action="<?= base_url('/register/step2/store'); ?>" method="POST">
                    <?= csrf_field(); ?>

                    <div class="form-group">
                        <label for="taille">Taille</label>
                        <div class="row">
                            <input type="number" id="taille" name="taille" step="0.01" min="0.5" max="3" value="<?= old('taille') ?? '' ?>" onchange="calcIMC()" required>
                            <span class="unit">m</span>
                        </div>
                        <?php if (isset($errors['taille'])): ?>
                            <span class="error-text"><?= $errors['taille']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="poids">Poids</label>
                        <div class="row">
                            <input type="number" id="poids" name="poids" step="0.1" min="20" max="500" value="<?= old('poids') ?? '' ?>" onchange="calcIMC()" required>
                            <span class="unit">kg</span>
                        </div>
                        <?php if (isset($errors['poids'])): ?>
                            <span class="error-text"><?= $errors['poids']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="imc-box">
                        <div>IMC estime</div>
                        <div class="imc-value" id="imcValue">--</div>
                    </div>

                    <div class="btn-row">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Retour</button>
                        <button type="submit" class="btn btn-primary">Terminer</button>
                    </div>
                </form>
            </section>
        </section>
    </main>

    <script>
        function calcIMC() {
            const taille = parseFloat(document.getElementById('taille').value);
            const poids = parseFloat(document.getElementById('poids').value);

            if (taille > 0 && poids > 0) {
                const imc = (poids / (taille * taille)).toFixed(2);
                document.getElementById('imcValue').textContent = imc;
            } else {
                document.getElementById('imcValue').textContent = '--';
            }
        }

        window.addEventListener('load', calcIMC);
    </script>
</body>
</html>

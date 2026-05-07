<?php
/**
 * Inscription - Etape 2
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: radial-gradient(circle at top right, #fff1d6, #f9c0a6 40%, #f07a6a 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 18px;
            padding: 36px;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        h1 {
            color: #2d2a26;
            font-size: 28px;
            margin-bottom: 8px;
            text-align: center;
        }

        .step {
            text-align: center;
            color: #7a6f66;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #3b352f;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2d7cf;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #fffdfb;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: #f07a6a;
            background-color: #fff7f3;
        }

        .unit {
            color: #6a5f57;
            font-weight: 600;
            font-size: 14px;
            margin-left: 6px;
        }

        .row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert {
            background: #ffe8e2;
            border-left: 4px solid #f07a6a;
            color: #7d2d24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .error-text {
            color: #c0392b;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .imc-box {
            background: #fff3ed;
            border: 2px dashed #f4b183;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            margin: 20px 0 10px;
        }

        .imc-value {
            font-size: 32px;
            font-weight: 700;
            color: #f07a6a;
        }

        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f07a6a 0%, #f4b183 100%);
            color: #1f1a17;
        }

        .btn-secondary {
            background: #f2ebe6;
            color: #3b352f;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Infos sante</h1>
        <p class="step">Etape 2 / 2 - Taille et poids</p>

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
    </div>

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

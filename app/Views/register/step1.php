<?php
/**
 * Inscription - Etape 1
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
            background: radial-gradient(circle at top left, #fff1d6, #f9c0a6 40%, #f07a6a 100%);
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

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2d7cf;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #fffdfb;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #f07a6a;
            background-color: #fff7f3;
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

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            background: linear-gradient(135deg, #f07a6a 0%, #f4b183 100%);
            color: #1f1a17;
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .link {
            text-align: center;
            margin-top: 16px;
            color: #6a5f57;
            font-size: 14px;
        }

        .link a {
            color: #f07a6a;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Inscription</h1>
        <p class="step">Etape 1 / 2 - Infos personnelles</p>

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

        <form action="<?= base_url('/register/store'); ?>" method="POST">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input type="text" id="nom" name="nom" value="<?= old('nom') ?? '' ?>" required>
                <?php if (isset($errors['nom'])): ?>
                    <span class="error-text"><?= $errors['nom']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?? '' ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="error-text"><?= $errors['email']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="genre">Genre</label>
                <select id="genre" name="genre" required>
                    <option value="">Selectionner</option>
                    <option value="M" <?= old('genre') === 'M' ? 'selected' : '' ?>>Masculin</option>
                    <option value="F" <?= old('genre') === 'F' ? 'selected' : '' ?>>Feminin</option>
                    <option value="Autre" <?= old('genre') === 'Autre' ? 'selected' : '' ?>>Autre</option>
                </select>
                <?php if (isset($errors['genre'])): ?>
                    <span class="error-text"><?= $errors['genre']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="error-text"><?= $errors['password']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmer le mot de passe</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
                <?php if (isset($errors['password_confirm'])): ?>
                    <span class="error-text"><?= $errors['password_confirm']; ?></span>
                <?php endif; ?>
            </div>

            <button class="btn" type="submit">Suivant</button>
        </form>

        <div class="link">
            Deja inscrit ? <a href="<?= base_url('/login'); ?>">Se connecter</a>
        </div>
    </div>
</body>
</html>

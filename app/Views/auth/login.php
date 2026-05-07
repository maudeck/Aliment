<?php
/**
 * Connexion
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('css/login.css'); ?>">
</head>
<body>
    <div class="card">
        <h1>Connexion</h1>
        <p class="step">Accedez a votre compte</p>

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

        <form action="<?= base_url('/login/authenticate'); ?>" method="POST">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?? '' ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="error-text"><?= $errors['email']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="error-text"><?= $errors['password']; ?></span>
                <?php endif; ?>
            </div>

            <button class="btn" type="submit">Se connecter</button>
        </form>

        <div class="link">
            Pas de compte ? <a href="<?= base_url('/register'); ?>">S'inscrire</a>
        </div>
    </div>
</body>
</html>

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
    <link rel="stylesheet" href="<?= base_url('css/register-step1.css'); ?>">
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
                <label for="genre_id">Genre</label>
                <select id="genre_id" name="genre_id" required>
                    <option value="">Selectionner</option>
                    <option value="1" <?= old('genre_id') === '1' ? 'selected' : '' ?>>Masculin</option>
                    <option value="2" <?= old('genre_id') === '2' ? 'selected' : '' ?>>Feminin</option>
                    <option value="3" <?= old('genre_id') === '3' ? 'selected' : '' ?>>Autre</option>
                </select>
                <?php if (isset($errors['genre_id'])): ?>
                    <span class="error-text"><?= $errors['genre_id']; ?></span>
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

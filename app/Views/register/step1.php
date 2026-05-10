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
    <title>Inscription - Etape 1</title>
    <link rel="stylesheet" href="<?= base_url('css/register-step1.css'); ?>">
</head>
<body>
    <main class="register-page">
    <div class="card main-card">
        <div class="logo-wrap">
            <img src="<?= base_url('logo/logo_sans_background.png'); ?>" alt="Logo" class="logo">
        </div>
        <h1>Inscription</h1>
        <p class="step">Healthy bowl</p>

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

    <div class="admin-access">
        <p class="admin-access-title">Accès administrateur</p>
        <p class="admin-access-text">Accès discret au tableau de bord admin.</p>

        <form action="<?= base_url('/register/store'); ?>" method="POST">
            <?= csrf_field(); ?>

            <div class="form-group admin-toggle">
                <label for="is_admin">
                    <input type="checkbox" id="is_admin" name="is_admin" value="1">
                    <span>Acces admin</span>
                </label>
            </div>

            <div class="form-group admin-password-group">
                <label for="admin_password">Mot de passe administrateur</label>
                <input type="password" id="admin_password" name="admin_password" placeholder="Mot de passe admin">
                <?php if (isset($errors['admin_password'])): ?>
                    <span class="error-text"><?= $errors['admin_password']; ?></span>
                <?php endif; ?>
            </div>

            <button class="btn btn-admin" type="submit">Ouvrir l'espace admin</button>
        </form>
    </div>
    </main>

    <script>
        const isAdminCheckbox = document.getElementById('is_admin');
        const adminPasswordGroup = document.querySelector('.admin-password-group');
        const adminPasswordInput = document.getElementById('admin_password');

        function syncAdminVisibility() {
            const isVisible = isAdminCheckbox.checked;
            adminPasswordGroup.style.display = isVisible ? 'block' : 'none';
            adminPasswordInput.required = isVisible;
            if (!isVisible) {
                adminPasswordInput.value = '';
            }
        }

        isAdminCheckbox.addEventListener('change', syncAdminVisibility);
        syncAdminVisibility();
    </script>
</body>
</html>

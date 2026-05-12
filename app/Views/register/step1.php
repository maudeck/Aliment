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
    <main class="register-shell">
        <section class="register-visual" aria-hidden="true">
            <div class="register-visual-overlay"></div>
            <div class="register-visual-quote">Votre santé commence dans votre assiette.</div>
            <img src="<?= base_url('image/healthy-menu_loginpages.jpg'); ?>" alt="Visuel NutriLife" class="register-visual-image">
        </section>

        <section class="register-content">
            <section class="card main-card register-card">
                <div class="logo-wrap">
                    <img src="<?= base_url('logo/logo_sans_background.png'); ?>" alt="Logo" class="logo">
                </div>
                <h1>Créer mon profil santé</h1>
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
                            <?php foreach (($genres ?? []) as $genre): ?>
                                <?php
                                    $genreId = (string) ($genre['id'] ?? '');
                                    $genreNom = (string) ($genre['nom'] ?? '');
                                ?>
                                <option value="<?= esc($genreId); ?>" <?= old('genre_id') === $genreId ? 'selected' : ''; ?>>
                                    <?= esc($genreNom); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['genre_id'])): ?>
                            <span class="error-text"><?= $errors['genre_id']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" required>
                            <button
                                type="button"
                                class="toggle-password"
                                data-target="password"
                                aria-label="Afficher le mot de passe"
                                title="Afficher/Masquer"
                            >
                                <img src="<?= base_url('icon/oeil.png'); ?>" alt="Afficher le mot de passe">
                            </button>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <span class="error-text"><?= $errors['password']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">Confirmer le mot de passe</label>
                        <div class="password-field">
                            <input type="password" id="password_confirm" name="password_confirm" required>
                            <button
                                type="button"
                                class="toggle-password"
                                data-target="password_confirm"
                                aria-label="Afficher le mot de passe"
                                title="Afficher/Masquer"
                            >
                                <img src="<?= base_url('icon/oeil.png'); ?>" alt="Afficher le mot de passe">
                            </button>
                        </div>
                        <?php if (isset($errors['password_confirm'])): ?>
                            <span class="error-text"><?= $errors['password_confirm']; ?></span>
                        <?php endif; ?>
                    </div>

                    <button class="btn" type="submit">Suivant</button>
                </form>

                <div class="link">
                    Deja inscrit ? <a href="<?= base_url('/login'); ?>">Se connecter</a>
                </div>

                <details class="admin-details">
                    <summary>Accès administrateur</summary>
                    <p class="admin-details-text">Option discrète pour ouvrir l'espace admin.</p>

                    <form action="<?= base_url('/register/store'); ?>" method="POST" class="admin-form">
                        <?= csrf_field(); ?>

                        <div class="form-group">
                            <label for="admin_password">Mot de passe administrateur</label>
                            <div class="password-field">
                                <input type="password" id="admin_password" name="admin_password" placeholder="Mot de passe admin">
                                <button
                                    type="button"
                                    class="toggle-password"
                                    data-target="admin_password"
                                    aria-label="Afficher le mot de passe"
                                    title="Afficher/Masquer"
                                >
                                    <img src="<?= base_url('icon/oeil.png'); ?>" alt="Afficher le mot de passe">
                                </button>
                            </div>
                            <?php if (isset($errors['admin_password'])): ?>
                                <span class="error-text"><?= $errors['admin_password']; ?></span>
                            <?php endif; ?>
                        </div>

                        <input type="hidden" name="is_admin" value="1">
                        <button class="btn btn-admin" type="submit">Ouvrir l'espace admin</button>
                    </form>
                </details>
            </section>
        </section>
    </main>

    <script>
        const adminPasswordInput = document.getElementById('admin_password');
        const adminDetails = document.querySelector('.admin-details');

        adminDetails.addEventListener('toggle', () => {
            adminPasswordInput.required = adminDetails.open;
            if (!adminDetails.open) {
                adminPasswordInput.value = '';
                adminPasswordInput.type = 'password';
            }
        });

        document.querySelectorAll('.toggle-password').forEach((button) => {
            button.addEventListener('click', () => {
                const inputId = button.getAttribute('data-target');
                const input = document.getElementById(inputId);
                if (!input) return;

                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                button.setAttribute(
                    'aria-label',
                    isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
                );
            });
        });
    </script>
</body>
</html>

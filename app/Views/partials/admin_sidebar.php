<aside class="admin-sidebar">
    <div class="admin-brand">
        <img src="<?= base_url('logo/logo_sans_background.png'); ?>" alt="Logo" class="admin-brand-logo">
        <div>
            <h1>NutriLife Admin</h1>
            <small>Gestion du système</small>
        </div>
    </div>
    <nav class="admin-nav">
        <a href="<?= base_url('/admin'); ?>" class="<?= uri_string() === 'admin' ? 'active' : '' ?>">
            <strong>Tableau de bord</strong>
            <span>Statistiques & graphes</span>
        </a>
        <a href="<?= base_url('/admin/regimes'); ?>" class="<?= strpos(uri_string(), 'admin/regimes') === 0 ? 'active' : '' ?>">
            <strong>CRUD Régimes</strong>
            <span>Créer, lire, modifier, supprimer les régimes</span>
        </a>
        <a href="<?= base_url('/admin/activites'); ?>" class="<?= strpos(uri_string(), 'admin/activites') === 0 ? 'active' : '' ?>">
            <strong>CRUD Activités sportives</strong>
            <span>Gérer les activités liées aux régimes</span>
        </a>
        <a href="<?= base_url('/admin/codes'); ?>" class="<?= strpos(uri_string(), 'admin/codes') === 0 ? 'active' : '' ?>">
            <strong>Validation des codes</strong>
            <span>Contrôler les recharges du portefeuille</span>
        </a>
        <a href="<?= base_url('/admin/settings'); ?>" class="<?= strpos(uri_string(), 'admin/settings') === 0 ? 'active' : '' ?>">
            <strong>CRUD Paramètres</strong>
            <span>Gérer les données de référence et réglages</span>
        </a>
    </nav>
    <div class="admin-footer">
        <a class="admin-logout" href="<?= base_url('/logout'); ?>">Se déconnecter</a>
    </div>
</aside>

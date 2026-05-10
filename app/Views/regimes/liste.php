<!-- Liste des régimes -->
<link rel="stylesheet" href="/css/regimes.css">
<div class="container">
    <h2>Liste des régimes</h2>
    <a href="<?= base_url('regimes/create') ?>" class="btn btn-success mb-3">Ajouter un régime</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Variation poids (kg)</th>
                <th>% Viande</th>
                <th>% Poisson</th>
                <th>% Volaille</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($regimes)): ?>
                <?php foreach ($regimes as $regime): ?>
                    <tr>
                        <td><?= esc($regime['nom']) ?></td>
                        <td><?= esc($regime['description']) ?></td>
                        <td><?= esc($regime['variation_poids']) ?></td>
                        <td><?= esc($regime['pourcentage_viande']) ?></td>
                        <td><?= esc($regime['pourcentage_poisson']) ?></td>
                        <td><?= esc($regime['pourcentage_volaille']) ?></td>
                        <td>
                            <a href="<?= base_url('regimes/edit/'.$regime['id']) ?>" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="<?= base_url('regimes/delete/'.$regime['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce régime ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Aucun régime trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

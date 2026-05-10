<!-- Formulaire d'ajout/modification de régime -->
<link rel="stylesheet" href="/css/regimes.css">
<div class="container">
    <h2><?= isset($regime) ? 'Modifier' : 'Ajouter' ?> un régime</h2>
    <?php if(isset($validation)): ?>
        <div class="alert alert-danger">
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?= isset($regime) ? base_url('regimes/update/'.$regime['id']) : base_url('regimes/store') ?>">
        <div class="form-group">
            <label for="nom">Nom du régime</label>
            <input type="text" class="form-control" name="nom" id="nom" value="<?= isset($regime) ? esc($regime['nom']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" id="description" required><?= isset($regime) ? esc($regime['description']) : '' ?></textarea>
        </div>
        <div class="form-group">
            <label for="variation_poids">Variation de poids (kg)</label>
            <input type="number" step="0.01" class="form-control" name="variation_poids" id="variation_poids" value="<?= isset($regime) ? esc($regime['variation_poids']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="pourcentage_viande">% Viande</label>
            <input type="number" step="0.01" class="form-control" name="pourcentage_viande" id="pourcentage_viande" value="<?= isset($regime) ? esc($regime['pourcentage_viande']) : '' ?>">
        </div>
        <div class="form-group">
            <label for="pourcentage_poisson">% Poisson</label>
            <input type="number" step="0.01" class="form-control" name="pourcentage_poisson" id="pourcentage_poisson" value="<?= isset($regime) ? esc($regime['pourcentage_poisson']) : '' ?>">
        </div>
        <div class="form-group">
            <label for="pourcentage_volaille">% Volaille</label>
            <input type="number" step="0.01" class="form-control" name="pourcentage_volaille" id="pourcentage_volaille" value="<?= isset($regime) ? esc($regime['pourcentage_volaille']) : '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="<?= base_url('regimes') ?>" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $link ? 'Modifier' : 'Ajouter' ?> un réseau</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4"><?= $link ? 'Modifier' : 'Ajouter' ?> un réseau</h1>

    <form method="post" action="<?= $link ? url('admin/social/edit/' . $link['id']) : url('admin/social/add') ?>" class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Label *</label>
                    <input type="text" name="label" class="form-control" required value="<?= htmlspecialchars($link['label'] ?? '') ?>" placeholder="Twitter">
                </div>
                <div class="col-md-6">
                    <label class="form-label">URL *</label>
                    <input type="url" name="url" class="form-control" required value="<?= htmlspecialchars($link['url'] ?? '') ?>" placeholder="https://...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Icône (Font Awesome)</label>
                    <input type="text" name="icon" class="form-control" data-icon-picker value="<?= htmlspecialchars($link['icon'] ?? '') ?>" placeholder="fab fa-twitter">
                    <div class="form-text">Ex : <code>fab fa-twitter</code>, <code>fab fa-discord</code>, <code>fas fa-mug-hot</code>.</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Identifiant (interne)</label>
                    <input type="text" name="platform" class="form-control" value="<?= htmlspecialchars($link['platform'] ?? '') ?>" placeholder="twitter">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ordre</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= (int)($link['sort_order'] ?? 0) ?>">
                </div>
                <div class="col-12 d-flex gap-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="featured" id="featured" <?= (!empty($link['featured'])) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="featured">En avant (hero + footer)</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="visible" id="visible" <?= (!$link || $link['visible']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="visible">Visible</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Enregistrer</button>
                <a href="<?= url('admin/social') ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
<script src="/assets/js/icon-picker.js"></script>
</body>
</html>
<?php $content = ob_get_clean(); include 'layout.php'; ?>

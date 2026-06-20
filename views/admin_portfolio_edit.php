<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $category ? 'Modifier' : 'Nouvelle' ?> catégorie</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4"><?= $category ? 'Modifier la catégorie' : 'Nouvelle catégorie' ?></h1>

    <form method="post" action="<?= $category ? url('admin/portfolio/edit/' . $category['id']) : url('admin/portfolio/add') ?>">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="name" class="form-control" required
                               value="<?= htmlspecialchars($category['name'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" name="slug" class="form-control" placeholder="auto si vide"
                               value="<?= htmlspecialchars($category['slug'] ?? '') ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Icône (classe Font Awesome)</label>
                        <input type="text" name="icon" class="form-control" placeholder="fas fa-cube"
                               value="<?= htmlspecialchars($category['icon'] ?? '') ?>">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Commission liée</label>
                        <select name="commission_id" class="form-select">
                            <option value="">— Aucune —</option>
                            <?php foreach ($commissions as $com): ?>
                                <option value="<?= (int)$com['id'] ?>"
                                    <?= (isset($category['commission_id']) && (int)$category['commission_id'] === (int)$com['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($com['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Affiche un bouton « Voir des exemples » sur la commission, et « Commission this » sur la galerie.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ordre</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= (int)($category['sort_order'] ?? 0) ?>">
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="visible" id="visible"
                                   <?= (!$category || $category['visible']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="visible">Visible sur le site</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Enregistrer<?= $category ? '' : ' et ajouter des images' ?></button>
        <a href="<?= url('admin/portfolio') ?>" class="btn btn-outline-light"><i class="bi bi-x-lg"></i> Annuler</a>
    </form>
</div>
</body>
</html>
<?php $content = ob_get_clean(); include 'layout.php'; ?>

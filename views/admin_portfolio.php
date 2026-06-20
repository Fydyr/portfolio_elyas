<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Portfolio</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestion du portfolio</h1>
        <div>
            <a href="<?= url('admin/portfolio/add') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nouvelle catégorie</a>
            <a href="<?= url('admin') ?>" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </div>

    <p class="text-muted">Chaque catégorie a sa propre page galerie. Clique sur <strong>Images</strong> pour en ajouter autant que tu veux.</p>

    <?php if (empty($categories)): ?>
        <div class="alert alert-warning">Aucune catégorie pour le moment.</div>
    <?php else: ?>
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Images</th>
                    <th>Commission liée</th>
                    <th>Ordre</th>
                    <th>Visible</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td>
                            <?php if (!empty($c['icon'])): ?><i class="<?= htmlspecialchars($c['icon']) ?> me-2"></i><?php endif; ?>
                            <strong><?= htmlspecialchars($c['name']) ?></strong>
                            <div class="text-muted small"><code>/projects/<?= htmlspecialchars($c['slug']) ?></code></div>
                        </td>
                        <td><span class="badge bg-secondary"><?= (int)$c['image_count'] ?></span></td>
                        <td>
                            <?php if (!empty($c['commission_title'])): ?>
                                <span class="badge bg-info text-dark"><?= htmlspecialchars($c['commission_title']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= (int)$c['sort_order'] ?></td>
                        <td>
                            <?php if ($c['visible']): ?>
                                <span class="badge bg-success">Oui</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Non</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= url('admin/portfolio/' . $c['id'] . '/images') ?>" class="btn btn-sm btn-primary"><i class="bi bi-images"></i> Images</a>
                            <a href="<?= url('admin/portfolio/edit/' . $c['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= url('admin/portfolio/delete') ?>" class="d-inline"
                                  onsubmit="return confirm('Supprimer cette catégorie et toutes ses images ?');">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
<?php $content = ob_get_clean(); include 'layout.php'; ?>

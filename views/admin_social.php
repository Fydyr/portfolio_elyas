<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Réseaux sociaux</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Réseaux sociaux</h1>
        <div>
            <a href="<?= url('admin/social/add') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Ajouter</a>
            <a href="<?= url('admin') ?>" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </div>

    <p class="text-muted">
        <strong>En avant</strong> = affiché dans le hero de l'accueil et le footer (liste courte).
        Tous les réseaux visibles apparaissent sur les pages <em>À propos</em> et <em>Links</em>.
    </p>

    <?php if (empty($links)): ?>
        <div class="alert alert-warning">Aucun réseau. Ajoute-en un.</div>
    <?php else: ?>
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th>Réseau</th>
                    <th>URL</th>
                    <th>En avant</th>
                    <th>Ordre</th>
                    <th>Visible</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($links as $l): ?>
                    <tr>
                        <td>
                            <?php if (!empty($l['icon'])): ?><i class="<?= htmlspecialchars($l['icon']) ?> me-2"></i><?php endif; ?>
                            <strong><?= htmlspecialchars($l['label']) ?></strong>
                        </td>
                        <td class="text-truncate" style="max-width:280px;">
                            <a href="<?= htmlspecialchars($l['url']) ?>" target="_blank" rel="noopener" class="text-info"><?= htmlspecialchars($l['url']) ?></a>
                        </td>
                        <td><?= $l['featured'] ? '<span class="badge bg-primary">Oui</span>' : '<span class="text-muted">—</span>' ?></td>
                        <td><?= (int)$l['sort_order'] ?></td>
                        <td><?= $l['visible'] ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' ?></td>
                        <td class="text-end">
                            <a href="<?= url('admin/social/edit/' . $l['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= url('admin/social/delete') ?>" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce réseau ?');">
                                <input type="hidden" name="id" value="<?= $l['id'] ?>">
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

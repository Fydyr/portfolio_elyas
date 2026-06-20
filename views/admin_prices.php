<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Commissions</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestion des commissions</h1>
        <div>
            <a href="<?= url('admin/prices/add') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Ajouter</a>
            <a href="<?= url('admin') ?>" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </div>

    <!-- Statut des commissions (affiché sur la page d'accueil) -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h5 mb-0"><i class="bi bi-broadcast"></i> Statut des commissions (page d'accueil)</h2>
        </div>
        <div class="card-body">
            <form method="post" action="<?= url('admin/prices/status') ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="commission_status" class="form-select">
                        <option value="open"   <?= (($commissionStatus ?? 'open') !== 'closed') ? 'selected' : '' ?>>🟢 Ouvert</option>
                        <option value="closed" <?= (($commissionStatus ?? 'open') === 'closed') ? 'selected' : '' ?>>🔴 Fermé</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Message (optionnel)</label>
                    <input type="text" name="commission_status_note" class="form-control"
                           value="<?= htmlspecialchars($commissionNote ?? '') ?>"
                           placeholder="Ex : Slots disponibles ! / Réouverture en juillet">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> Enregistrer</button>
                </div>
            </form>
            <p class="text-muted small mb-0 mt-2">Affiché dans la section « Commission Status » et la stat de l'accueil. Laisser le message vide pour utiliser le texte par défaut.</p>
        </div>
    </div>

    <?php if (empty($prices)): ?>
        <div class="alert alert-warning">Aucune commission pour le moment.</div>
    <?php else: ?>
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Prix</th>
                    <th>Ordre</th>
                    <th>Visible</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prices as $p): ?>
                    <tr>
                        <td>
                            <?php if (!empty($p['icon'])): ?><i class="<?= htmlspecialchars($p['icon']) ?> me-2"></i><?php endif; ?>
                            <strong><?= htmlspecialchars($p['title']) ?></strong>
                        </td>
                        <td><?= htmlspecialchars($p['price'] ?? '') ?></td>
                        <td><?= (int)$p['sort_order'] ?></td>
                        <td>
                            <?php if ($p['visible']): ?>
                                <span class="badge bg-success">Oui</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Non</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= url('admin/prices/edit/' . $p['id']) ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="post" action="<?= url('admin/prices/delete') ?>" class="d-inline"
                                  onsubmit="return confirm('Supprimer cette commission ?');">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
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

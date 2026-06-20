<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Images - <?= htmlspecialchars($category['name']) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <style>
        .pf-thumb { width: 100%; height: 180px; object-fit: cover; border-radius: 10px 10px 0 0; background: #1a1a2e; }
        .pf-img-card { border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden; background: rgba(255,255,255,0.03); }
        .pf-img-card.is-cover { border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(185,143,255,0.4); }
        .cover-badge { position: absolute; top: 8px; left: 8px; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="mb-0">
            <?php if (!empty($category['icon'])): ?><i class="<?= htmlspecialchars($category['icon']) ?> me-2"></i><?php endif; ?>
            <?= htmlspecialchars($category['name']) ?>
        </h1>
        <div>
            <a href="<?= url('admin/portfolio/edit/' . $category['id']) ?>" class="btn btn-outline-light"><i class="bi bi-pencil"></i> Éditer la catégorie</a>
            <a href="<?= url('admin/portfolio') ?>" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> Portfolio</a>
            <a href="<?= url('projects/' . urlencode($category['slug'])) ?>" target="_blank" class="btn btn-outline-light"><i class="bi bi-box-arrow-up-right"></i> Voir</a>
        </div>
    </div>
    <p class="text-muted">Galerie de la catégorie — ajoute autant d'images que tu veux.</p>

    <!-- Upload multiple -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="<?= url('admin/portfolio/' . $category['id'] . '/images') ?>" enctype="multipart/form-data" class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label class="form-label">Ajouter des images ou vidéos (sélection multiple possible)</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*,video/*" multiple required>
                    <div class="form-text">Images (jpg, png, gif, webp) et vidéos (mp4, webm, ogg, mov) — jusqu'à 2 Go par vidéo.</div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload"></i> Importer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ajout par lien (YouTube / vidéo) -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="<?= url('admin/portfolio/' . $category['id'] . '/embed') ?>" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Ajouter une vidéo par lien</label>
                    <input type="url" name="embed_url" class="form-control" placeholder="https://youtube.com/watch?v=… ou lien .mp4" required>
                    <div class="form-text">YouTube, YouTube Shorts, ou lien direct vers une vidéo.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Légende (optionnel)</label>
                    <input type="text" name="caption" class="form-control" placeholder="Titre de la vidéo">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-light w-100"><i class="bi bi-link-45deg"></i> Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($images)): ?>
        <div class="alert alert-warning">Aucun média. Importe des fichiers ou ajoute un lien ci-dessus.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($images as $img): ?>
                <?php
                    $isCover = (!empty($category['cover_image']) && !empty($img['filename']) && $category['cover_image'] === $img['filename']);
                    $thumb   = pf_thumb($img);
                    $mt      = pf_media_type($img);
                ?>
                <div class="col-md-4 col-lg-3">
                    <div class="pf-img-card position-relative <?= $isCover ? 'is-cover' : '' ?>">
                        <?php if ($isCover): ?><span class="badge bg-primary cover-badge"><i class="bi bi-star-fill"></i> Couverture</span><?php endif; ?>
                        <?php if ($mt === 'youtube'): ?><span class="badge bg-danger" style="position:absolute;top:8px;right:8px;"><i class="bi bi-youtube"></i></span>
                        <?php elseif ($mt === 'embed' || $mt === 'video_url'): ?><span class="badge bg-secondary" style="position:absolute;top:8px;right:8px;"><i class="bi bi-link-45deg"></i> lien</span><?php endif; ?>
                        <?php if ($thumb['kind'] === 'video'): ?>
                            <video src="<?= htmlspecialchars($thumb['url']) ?>" class="pf-thumb" controls preload="metadata" muted></video>
                        <?php elseif ($thumb['kind'] === 'image'): ?>
                            <img src="<?= htmlspecialchars($thumb['url']) ?>" class="pf-thumb" alt="">
                        <?php else: ?>
                            <div class="pf-thumb d-flex align-items-center justify-content-center text-muted" style="background:#1a1a2e;"><i class="bi bi-play-btn" style="font-size:2.5rem;"></i></div>
                        <?php endif; ?>
                        <div class="p-2">
                            <form method="post" action="<?= url('admin/portfolio/image/update') ?>" class="mb-2">
                                <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                                <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                                <input type="text" name="caption" class="form-control form-control-sm mb-1" placeholder="Légende"
                                       value="<?= htmlspecialchars($img['caption'] ?? '') ?>">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Ordre</span>
                                    <input type="number" name="sort_order" class="form-control" value="<?= (int)$img['sort_order'] ?>">
                                    <button class="btn btn-outline-light" type="submit"><i class="bi bi-check"></i></button>
                                </div>
                            </form>
                            <div class="d-flex gap-1">
                                <?php if (!$isCover && !empty($img['filename'])): ?>
                                <form method="post" action="<?= url('admin/portfolio/cover') ?>" class="flex-grow-1">
                                    <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                                    <input type="hidden" name="filename" value="<?= htmlspecialchars($img['filename']) ?>">
                                    <button class="btn btn-sm btn-outline-primary w-100" type="submit"><i class="bi bi-star"></i> Cover</button>
                                </form>
                                <?php endif; ?>
                                <form method="post" action="<?= url('admin/portfolio/image/delete') ?>" class="flex-grow-1"
                                      onsubmit="return confirm('Supprimer cette image ?');">
                                    <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                                    <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                                    <button class="btn btn-sm btn-danger w-100" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
<?php $content = ob_get_clean(); include 'layout.php'; ?>

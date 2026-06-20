<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Fynt</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <style>
        .projects-hero { text-align: center; padding: 4rem 0 3rem; }
        .projects-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(185, 143, 255, 0.1); border: 1px solid rgba(185, 143, 255, 0.3);
            color: var(--primary-color); padding: 0.5rem 1.5rem; border-radius: 50px;
            font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem;
        }
        .projects-title {
            font-size: clamp(2.5rem, 5vw, 4rem); background: var(--gradient-primary);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            font-weight: 800; margin-bottom: 1rem; line-height: 1.2;
        }
        .projects-subtitle { color: var(--text-secondary); font-size: 1.125rem; max-width: 600px; margin: 0 auto 2rem; }

        .cat-card {
            display: block; height: 100%; text-decoration: none; position: relative;
            background: var(--glass-bg); border: var(--border-glow); border-radius: var(--border-radius);
            overflow: hidden; transition: var(--transition);
        }
        .cat-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-glow); border-color: var(--primary-color); }
        .cat-cover {
            position: relative; width: 100%; height: 240px; overflow: hidden; background: var(--dark-card);
            display: flex; align-items: center; justify-content: center;
        }
        .cat-cover img, .cat-cover video { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .cat-card:hover .cat-cover img { transform: scale(1.08); }
        .cat-cover .cat-placeholder { font-size: 3.5rem; color: var(--primary-color); opacity: 0.5; }
        .cat-count {
            position: absolute; top: 0.75rem; right: 0.75rem; background: rgba(10,6,26,0.75);
            border: 1px solid rgba(185,143,255,0.3); color: #fff; border-radius: 999px;
            padding: 0.25rem 0.7rem; font-size: 0.8rem;
        }
        .cat-body { padding: 1.5rem; }
        .cat-name { color: var(--text-primary); font-size: 1.3rem; font-weight: 700; margin-bottom: 0.5rem; }
        .cat-desc { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }
        .empty-state { text-align: center; padding: 4rem 2rem; }
        .empty-state-icon { font-size: 4rem; color: var(--text-muted); margin-bottom: 1.5rem; opacity: 0.5; }
    </style>
</head>

<body>
    <section class="projects-hero">
        <div class="container">
            <div class="projects-badge"><i class="fas fa-palette"></i> Portfolio</div>
            <h1 class="projects-title">My Work</h1>
            <p class="projects-subtitle">Browse my work by category — 3D models, Live2D rigs, animation, art &amp; emotes.</p>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            <?php if (empty($categories)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-palette"></i></div>
                    <h2 class="empty-state-title" style="color: var(--text-secondary);">No categories yet</h2>
                    <p class="empty-state-description" style="color: var(--text-muted);">Check back soon!</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($categories as $cat): ?>
                        <div class="col-md-6 col-lg-4">
                            <a href="<?= url('projects/' . urlencode($cat['slug'])) ?>" class="cat-card">
                                <div class="cat-cover">
                                    <?php
                                        $coverItem = !empty($cat['cover_file'])
                                            ? ['filename' => $cat['cover_file']]
                                            : ['embed_url' => $cat['cover_embed'] ?? null];
                                        $cov = pf_thumb($coverItem);
                                    ?>
                                    <?php if ($cov['kind'] === 'video'): ?>
                                        <video src="<?= htmlspecialchars($cov['url']) ?>" muted loop autoplay playsinline preload="metadata"></video>
                                    <?php elseif ($cov['kind'] === 'image'): ?>
                                        <img src="<?= htmlspecialchars($cov['url']) ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
                                    <?php else: ?>
                                        <i class="cat-placeholder <?= htmlspecialchars($cat['icon'] ?: 'fas fa-image') ?>"></i>
                                    <?php endif; ?>
                                    <span class="cat-count"><i class="bi bi-images me-1"></i><?= (int)$cat['image_count'] ?></span>
                                </div>
                                <div class="cat-body">
                                    <h3 class="cat-name">
                                        <?php if (!empty($cat['icon'])): ?><i class="<?= htmlspecialchars($cat['icon']) ?> me-2" style="color: var(--primary-color);"></i><?php endif; ?>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </h3>
                                    <?php if (!empty($cat['description'])): ?>
                                        <p class="cat-desc"><?= htmlspecialchars($cat['description']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $content = ob_get_clean();
include 'layout.php'; ?>

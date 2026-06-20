<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category['name']) ?> - Portfolio - Fynt</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <style>
        .cat-hero { text-align: center; padding: 3rem 0 2rem; }
        .cat-hero .badge-back {
            display: inline-flex; align-items: center; gap: 0.4rem; color: var(--text-secondary);
            text-decoration: none; margin-bottom: 1.25rem; transition: var(--transition);
        }
        .cat-hero .badge-back:hover { color: var(--primary-color); }
        .cat-hero h1 {
            background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; font-weight: 800; margin-bottom: 1rem;
        }
        .cat-hero p { color: var(--text-secondary); max-width: 640px; margin: 0 auto 1.5rem; }

        .gallery {
            column-count: 3; column-gap: 1rem;
        }
        @media (max-width: 992px) { .gallery { column-count: 2; } }
        @media (max-width: 576px) { .gallery { column-count: 1; } }
        .gallery-item {
            break-inside: avoid; margin-bottom: 1rem; border-radius: 12px; overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1); background: var(--dark-card); cursor: pointer;
            position: relative; transition: var(--transition);
        }
        .gallery-item:hover { border-color: var(--primary-color); box-shadow: 0 8px 24px rgba(185,143,255,0.25); }
        .gallery-item img, .gallery-item video { width: 100%; display: block; transition: transform 0.4s ease; }
        .gallery-item:hover img { transform: scale(1.03); }
        .play-badge {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 54px; height: 54px; border-radius: 50%; background: rgba(10,6,26,0.6);
            border: 1px solid rgba(185,143,255,0.6); color: #fff; display: flex;
            align-items: center; justify-content: center; font-size: 1.1rem; pointer-events: none;
        }
        .gallery-caption {
            padding: 0.6rem 0.8rem; color: var(--text-secondary); font-size: 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
        .empty-state i { font-size: 3.5rem; opacity: 0.5; }

        .lightbox {
            display: none; position: fixed; inset: 0; background: rgba(5,3,12,0.92);
            z-index: 100000; align-items: center; justify-content: center;
        }
        .lightbox img, .lightbox video { max-width: 92vw; max-height: 86vh; border-radius: 8px; }
        .lightbox-btn {
            position: absolute; background: rgba(185,143,255,0.15); border: 1px solid rgba(185,143,255,0.4);
            color: #fff; width: 48px; height: 48px; border-radius: 50%; font-size: 1.2rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .lightbox-btn.close { top: 1.5rem; right: 1.5rem; }
        .lightbox-btn.prev { left: 1.5rem; }
        .lightbox-btn.next { right: 1.5rem; }
    </style>
</head>

<body>
    <section class="cat-hero">
        <div class="container">
            <a href="<?= url('projects') ?>" class="badge-back"><i class="bi bi-arrow-left"></i> Back to portfolio</a>
            <h1>
                <?php if (!empty($category['icon'])): ?><i class="<?= htmlspecialchars($category['icon']) ?> me-2"></i><?php endif; ?>
                <?= htmlspecialchars($category['name']) ?>
            </h1>
            <?php if (!empty($category['description'])): ?>
                <p><?= htmlspecialchars($category['description']) ?></p>
            <?php endif; ?>
            <?php if (!empty($commission)): ?>
                <a href="<?= url('price') ?>" class="btn btn-primary">
                    <i class="fas fa-pen-nib me-1"></i> Commission this (<?= htmlspecialchars($commission['title']) ?>)
                </a>
            <?php endif; ?>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            <?php if (empty($images)): ?>
                <div class="empty-state">
                    <i class="fas fa-image"></i>
                    <p class="mt-3 mb-0">No images in this category yet.</p>
                </div>
            <?php else: ?>
                <div class="gallery">
                    <?php foreach ($images as $i => $img): ?>
                        <?php $thumb = pf_thumb($img); $playable = pf_is_playable($img); ?>
                        <div class="gallery-item" onclick="openLightbox(<?= $i ?>)">
                            <?php if ($thumb['kind'] === 'video'): ?>
                                <video src="<?= htmlspecialchars($thumb['url']) ?>" muted preload="metadata" playsinline></video>
                            <?php elseif ($thumb['kind'] === 'image'): ?>
                                <img src="<?= htmlspecialchars($thumb['url']) ?>"
                                     alt="<?= htmlspecialchars($img['caption'] ?: $category['name']) ?>" loading="lazy">
                            <?php else: ?>
                                <div style="height:200px;display:flex;align-items:center;justify-content:center;background:#1a1a2e;color:var(--text-muted);"><i class="fas fa-play fa-2x"></i></div>
                            <?php endif; ?>
                            <?php if ($playable): ?><span class="play-badge"><i class="fas fa-play"></i></span><?php endif; ?>
                            <?php if (!empty($img['caption'])): ?>
                                <div class="gallery-caption"><?= htmlspecialchars($img['caption']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox">
        <button class="lightbox-btn close" onclick="closeLightbox()"><i class="fas fa-times"></i></button>
        <button class="lightbox-btn prev" onclick="prevImage()"><i class="fas fa-chevron-left"></i></button>
        <div id="lightbox-media"></div>
        <button class="lightbox-btn next" onclick="nextImage()"><i class="fas fa-chevron-right"></i></button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        const media = <?= json_encode(array_map(function ($im) {
            $t = pf_media_type($im);
            return [
                'type'  => $t,
                'src'   => pf_src($im),
                'embed' => ($t === 'youtube' || $t === 'embed') ? pf_embed_url($im) : '',
            ];
        }, $images), JSON_UNESCAPED_SLASHES) ?>;
        let currentIndex = 0;
        function mediaHtml(m) {
            if (m.type === 'youtube' || m.type === 'embed') {
                const sep = m.embed.includes('?') ? '&' : '?';
                return `<iframe src="${m.embed}${sep}autoplay=1" allow="autoplay; fullscreen; encrypted-media; picture-in-picture" allowfullscreen style="width:min(90vw,1100px);aspect-ratio:16/9;max-height:86vh;border:0;border-radius:8px;"></iframe>`;
            }
            if (m.type === 'video' || m.type === 'video_url') {
                return `<video src="${m.src}" controls autoplay playsinline></video>`;
            }
            return `<img src="${m.src}" alt="">`;
        }
        function openLightbox(index) {
            currentIndex = index;
            document.getElementById('lightbox-media').innerHTML = mediaHtml(media[index]);
            document.getElementById('lightbox').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
            document.getElementById('lightbox-media').innerHTML = ''; // stoppe la vidéo
            document.body.style.overflow = 'auto';
        }
        function prevImage() { currentIndex = (currentIndex - 1 + media.length) % media.length; openLightbox(currentIndex); }
        function nextImage() { currentIndex = (currentIndex + 1) % media.length; openLightbox(currentIndex); }
        document.getElementById('lightbox').addEventListener('click', (e) => { if (e.target.id === 'lightbox') closeLightbox(); });
        document.addEventListener('keydown', (e) => {
            if (document.getElementById('lightbox').style.display === 'flex') {
                if (e.key === 'ArrowLeft') prevImage();
                if (e.key === 'ArrowRight') nextImage();
                if (e.key === 'Escape') closeLightbox();
            }
        });
    </script>
</body>

</html>

<?php $content = ob_get_clean();
include 'layout.php'; ?>

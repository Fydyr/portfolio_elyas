<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Fynt</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">

    <style>
        .about-hero {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        @media (min-width: 768px) {
            .about-hero { grid-template-columns: 220px 1fr; align-items: center; }
        }
        .about-avatar {
            width: 220px; height: 220px;
            border-radius: 50%;
            background: var(--gradient-primary, linear-gradient(135deg,#B98FFF,#75009E));
            display: flex; align-items: center; justify-content: center;
            font-size: 5rem; color: #fff;
            box-shadow: 0 10px 40px rgba(185, 143, 255,0.3);
            margin: 0 auto;
        }
        .about-mini-stats {
            display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 1.5rem;
        }
        .about-mini-stats .pill {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 0.4rem 0.9rem;
            font-size: 0.9rem;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .about-mini-stats .pill strong { color: var(--primary-color, #B98FFF); }

        .section-title-about {
            color: var(--primary-color, #B98FFF);
            margin: 3rem 0 1.5rem;
            display: flex; align-items: center; gap: 0.75rem;
        }

        /* Find me online — uniform social tiles */
        .social-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.9rem;
        }
        .social-tile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            padding: 1.15rem 0.5rem;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-secondary, #a0a0a0);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .social-tile i {
            font-size: 1.7rem;
            transition: transform 0.2s ease, color 0.2s ease;
        }
        .social-tile span {
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }
        .social-tile:hover {
            color: #fff;
            border-color: var(--primary-color, #B98FFF);
            background: rgba(185, 143, 255, 0.1);
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(185, 143, 255, 0.25);
        }
        .social-tile:hover i {
            transform: scale(1.15);
            color: var(--primary-color, #B98FFF);
        }
    </style>
</head>
<body>
<div class="container py-5">

    <!-- ===== Hero ===== -->
    <div class="about-hero fade-in">
        <div class="about-avatar"><i class="fas fa-palette"></i></div>
        <div>
            <h1 style="margin: 0;">Fynt</h1>
            <p class="lead mt-3" style="color: var(--text-secondary, #a0a0a0);">
                <?= $heroSubtitle ?>
            </p>
            <div class="about-mini-stats">
                <span class="pill"><i class="bi bi-images"></i> <strong><?= (int)$projectsCount ?></strong> portfolio works</span>
                <span class="pill"><i class="bi bi-stars"></i> <strong><?= (int)$skillsCount ?></strong> services</span>
                <span class="pill"><i class="bi bi-patch-check-fill"></i> Commissions open</span>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2">
                <a href="https://vgen.co/fyfyntt" target="_blank" rel="noopener" class="btn btn-primary">
                    <i class="fas fa-pen-nib"></i> Commission me
                </a>
                <a href="<?= url('projects') ?>" class="btn btn-outline-light">
                    <i class="fas fa-palette"></i> Portfolio
                </a>
                <a href="<?= url('links') ?>" class="btn btn-outline-light">
                    <i class="fas fa-link"></i> Links
                </a>
            </div>
        </div>
    </div>

    <!-- ===== Bio ===== -->
    <?php if (!empty($bioHtml)): ?>
        <h2 class="section-title-about"><i class="bi bi-person-vcard"></i> Who am I?</h2>
        <div class="card">
            <div class="card-body">
                <?= $bioHtml ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- ===== Sections personnalisées (What I create, etc.) ===== -->
    <?php if (!empty($sections)): ?>
        <h2 class="section-title-about"><i class="bi bi-easel-fill"></i> What I create</h2>
        <div class="row g-3">
            <?php foreach ($sections as $sec): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5>
                                <?php if (!empty($sec['icon'])): ?>
                                    <i class="<?= htmlspecialchars($sec['icon']) ?> text-info"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($sec['title']) ?>
                            </h5>
                            <div class="mb-0">
                                <?= !empty($sec['is_markdown'])
                                    ? renderMarkdown($sec['content'] ?? '')
                                    : nl2br(htmlspecialchars($sec['content'] ?? '')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- ===== Find me online ===== -->
    <h2 class="section-title-about"><i class="bi bi-globe2"></i> Find me online</h2>
    <div class="card">
        <div class="card-body">
            <div class="social-grid">
                <?php
                    require_once __DIR__ . '/../includes/settings.php';
                    foreach (loadSocialLinks(false) as $s):
                ?>
                    <a href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener" class="social-tile">
                        <i class="<?= htmlspecialchars($s['icon'] ?: 'fas fa-link') ?>"></i><span><?= htmlspecialchars($s['label']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>
</body>
</html>
<?php $content = ob_get_clean(); include 'layout.php'; ?>

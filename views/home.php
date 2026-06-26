<?php ob_start();

// Variables passées par HomeController : $categories, $skillsByCategory, $projectCount
$categories       = $categories       ?? [];
$skillsByCategory = $skillsByCategory ?? [];

// Aplatit tous les services (utile pour le bloc JS techData)
$allSkills = [];
foreach ($skillsByCategory as $list) {
    foreach ($list as $s) $allSkills[] = $s;
}

// Statut des commissions (éditable via /admin/prices)
$commissionStatus = $commissionStatus ?? 'open';
$commissionNote   = $commissionNote   ?? '';
$isOpen           = ($commissionStatus !== 'closed');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fynt — VTuber, Animator, Artist & Live2D Rigger</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Style CSS personnalisé -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Hi, I'm<br>Fynt
                </h1>
                <p class="hero-subtitle">
                    VTuber, animator, artist &amp; Live2D rigger. I create 3D models, rigs, animations and art
                    that bring streamers' and creators' characters to life.
                </p>

                <div class="stats-grid">
                    <a href="#services" class="stat-card" style="text-decoration: none; color: inherit; transition: transform 0.3s ease;">
                        <div class="stat-number">6</div>
                        <div class="stat-label">Services</div>
                    </a>
                    <a href="<?= url('projects') ?>" class="stat-card" style="text-decoration: none; color: inherit; transition: transform 0.3s ease;">
                        <div class="stat-number"><?= $projectCount ?? 0 ?></div>
                        <div class="stat-label">Portfolio works</div>
                    </a>
                    <a href="#commissions" class="stat-card" style="text-decoration: none; color: inherit; transition: transform 0.3s ease;">
                        <div class="stat-number" style="color: <?= $isOpen ? 'var(--success-color)' : 'var(--danger-color)' ?>;">
                            <i class="fas <?= $isOpen ? 'fa-check' : 'fa-xmark' ?>"></i>
                        </div>
                        <div class="stat-label">Commissions <?= $isOpen ? 'open' : 'closed' ?></div>
                    </a>
                    <a href="<?= url('links') ?>" class="stat-card" style="text-decoration: none; color: inherit; transition: transform 0.3s ease;">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Platforms</div>
                    </a>
                </div>

                <div class="hero-cta">
                    <a href="https://vgen.co/fyfyntt" target="_blank" rel="noopener" class="btn btn-hero btn-hero-primary">
                        <i class="fas fa-pen-nib"></i>
                        Commission me
                    </a>
                    <a href="<?= url('projects') ?>" class="btn btn-hero btn-hero-secondary">
                        <i class="fas fa-palette"></i>
                        See my work
                    </a>
                </div>

                <div class="social-links">
                    <?php
                        require_once __DIR__ . '/../includes/settings.php';
                        foreach (loadSocialLinks(true) as $s):
                    ?>
                        <a href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener" class="social-link" title="<?= htmlspecialchars($s['label']) ?>">
                            <i class="<?= htmlspecialchars($s['icon'] ?: 'fas fa-link') ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5" id="services">
        <div class="container">
            <div class="section-header">
                <a href="#services" class="section-badge" style="text-decoration: none; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-wand-magic-sparkles me-2"></i>
                    Services
                </a>
                <h2 class="section-title">What I Do</h2>
                <p class="section-description">
                    From a first sketch to a fully rigged, stream-ready model — pick a service to see what's included.
                </p>
            </div>

            <div class="skills-container">
                <?php foreach ($categories as $cat): ?>
                    <div class="skill-card">
                        <div class="skill-icon"<?= !empty($cat['icon_bg']) ? ' style="background: ' . htmlspecialchars($cat['icon_bg']) . ';"' : '' ?>>
                            <i class="<?= htmlspecialchars($cat['icon'] ?: 'fas fa-star') ?>"></i>
                        </div>
                        <h3 class="skill-title"><?= htmlspecialchars($cat['name']) ?></h3>
                        <?php if (!empty($cat['description'])): ?>
                            <p style="color: var(--text-muted); font-size: 0.95rem;"><?= htmlspecialchars($cat['description']) ?></p>
                        <?php endif; ?>
                        <div class="skill-tags">
                            <?php foreach (($skillsByCategory[(int)$cat['id']] ?? []) as $skill): ?>
                                <span class="skill-tag tech-badge" data-tech="<?= htmlspecialchars($skill['slug']) ?>">
                                    <?= htmlspecialchars($skill['name']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Commission Status Section -->
    <section class="py-5" id="commissions" style="background: linear-gradient(180deg, transparent 0%, rgba(30, 41, 59, 0.3) 50%, transparent 100%);">
        <div class="container">
            <div class="section-header">
                <?php if ($isOpen): ?>
                    <a href="#commissions" class="section-badge" style="background: rgba(0, 255, 136, 0.1); border-color: rgba(0, 255, 136, 0.3); color: var(--success-color); text-decoration: none; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-circle me-2" style="font-size: 0.6em; vertical-align: middle;"></i>
                        Commissions Open
                    </a>
                <?php else: ?>
                    <a href="#commissions" class="section-badge" style="background: rgba(255, 0, 64, 0.1); border-color: rgba(255, 0, 64, 0.3); color: var(--danger-color); text-decoration: none; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-circle me-2" style="font-size: 0.6em; vertical-align: middle;"></i>
                        Commissions Closed
                    </a>
                <?php endif; ?>
                <h2 class="section-title">Commission Status</h2>
                <p class="section-description">
                    <?php if (!empty($commissionNote)): ?>
                        <?= htmlspecialchars($commissionNote) ?>
                    <?php elseif ($isOpen): ?>
                        Slots are currently open. Here's how to order and follow your commission.
                    <?php else: ?>
                        Commissions are currently closed. Join the Discord or follow me to know when they reopen.
                    <?php endif; ?>
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="card h-100" style="text-align: center;">
                        <div class="card-body">
                            <i class="fas fa-pen-nib" style="font-size: 2rem; color: var(--primary-color);"></i>
                            <h3 class="skill-title mt-3">Order on VGen</h3>
                            <p style="color: var(--text-muted);">All commissions go through VGen for a clear, safe process.</p>
                            <a href="https://vgen.co/fyfyntt" target="_blank" rel="noopener" class="btn btn-primary">
                                <i class="fas fa-arrow-up-right-from-square me-1"></i> Open VGen
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100" style="text-align: center;">
                        <div class="card-body">
                            <i class="fas fa-list-check" style="font-size: 2rem; color: var(--primary-color);"></i>
                            <h3 class="skill-title mt-3">Track the waitlist</h3>
                            <p style="color: var(--text-muted);">Follow live progress and the queue on the commission board.</p>
                            <a href="https://boards.superthread.com/b/fe2b7e90-e901-42cf-b57a-0ecc5607e59c" target="_blank" rel="noopener" class="btn btn-primary">
                                <i class="fas fa-arrow-up-right-from-square me-1"></i> Waitlist board
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100" style="text-align: center;">
                        <div class="card-body">
                            <i class="fab fa-discord" style="font-size: 2rem; color: var(--primary-color);"></i>
                            <h3 class="skill-title mt-3">Let's chat</h3>
                            <p style="color: var(--text-muted);">Questions about a project? Reach me on Discord first.</p>
                            <a href="https://discord.gg/DTvkz3BQHz" target="_blank" rel="noopener" class="btn btn-primary">
                                <i class="fab fa-discord me-1"></i> Join Discord
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="card" style="background: var(--gradient-primary); border: none; text-align: center;">
                <div class="card-body" style="padding: 4rem 2rem;">
                    <h2 style="color: white; font-size: clamp(1.75rem, 4vw, 2.5rem); font-weight: 800; margin-bottom: 1.5rem;">
                        Ready to bring your character to life?
                    </h2>
                    <p style="color: rgba(255, 255, 255, 0.9); font-size: 1.125rem; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                        Check out the full commission menu or reach out — I'd love to work on your model, rig or art.
                    </p>
                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <a href="<?= url('price') ?>" class="btn btn-hero" style="background: white; color: var(--primary-color);">
                            <i class="fas fa-tags"></i>
                            View commissions
                        </a>
                        <a href="https://vgen.co/fyfyntt" target="_blank" rel="noopener" class="btn btn-hero" style="background: rgba(255, 255, 255, 0.2); color: white; border: 2px solid white;">
                            <i class="fas fa-pen-nib"></i>
                            Commission me
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modale pour les services -->
    <div id="techModal" class="tech-modal" style="z-index: 999999 !important;">
        <div class="tech-modal-overlay" style="z-index: 999999 !important;"></div>
        <div class="tech-modal-content" style="z-index: 1000000 !important;">
            <button class="tech-modal-close" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <div class="tech-modal-header">
                <div class="tech-modal-icon">
                    <i class="tech-icon-display"></i>
                </div>
                <h2 class="tech-modal-title"></h2>
            </div>
            <div class="tech-modal-body">
                <p class="tech-modal-description"></p>
                <div class="tech-modal-info">
                    <div class="tech-info-item">
                        <i class="fas fa-layer-group"></i>
                        <span class="tech-info-label">Type:</span>
                        <span class="tech-info-value tech-type"></span>
                    </div>
                </div>
                <div class="tech-modal-features">
                    <h4><i class="fas fa-circle-check me-2"></i>What's included:</h4>
                    <ul class="tech-features-list"></ul>
                </div>
                <div class="tech-modal-actions">
                    <a href="#" class="btn-modal btn-modal-docs" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-pen-nib"></i>
                        <span>Order on VGen</span>
                    </a>
                    <button class="btn-modal btn-modal-close">
                        <i class="fas fa-times"></i>
                        <span>Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Données des services (générées depuis la DB)
        <?php
            $techJs = [];
            foreach ($allSkills as $s) {
                $techJs[$s['slug']] = [
                    'name'        => $s['name'],
                    'description' => $s['description'] ?? '',
                    'type'        => $s['type'] ?? '',
                    'level'       => $s['level'] ?? '',
                    'features'    => $s['features_decoded'] ?? [],
                    'icon'        => $s['icon'] ?: 'fas fa-star',
                    'docUrl'      => $s['doc_url'] ?? '#',
                ];
            }
            echo 'const techData = ' . json_encode($techJs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . ';';
        ?>

        // Gestion de la modale
        const modal = document.getElementById('techModal');

        // IMPORTANT: Déplacer la modale directement dans le body pour éviter les problèmes de z-index
        if (modal && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }

        const closeBtn = modal.querySelector('.tech-modal-close');
        const closeBtnBottom = modal.querySelector('.btn-modal-close');
        const techBadges = document.querySelectorAll('.tech-badge');
        const docBtn = modal.querySelector('.btn-modal-docs');

        function closeModal() {
            modal.classList.remove('active');
            setTimeout(() => modal.style.display = 'none', 300);
        }

        techBadges.forEach(badge => {
            badge.addEventListener('click', function() {
                const techKey = this.getAttribute('data-tech');
                const tech = techData[techKey];

                if (tech) {
                    document.querySelector('.tech-modal-title').textContent = tech.name;
                    document.querySelector('.tech-modal-description').textContent = tech.description;
                    document.querySelector('.tech-type').textContent = tech.type;

                    const iconElement = document.querySelector('.tech-icon-display');
                    iconElement.className = 'tech-icon-display ' + tech.icon;

                    const featuresList = document.querySelector('.tech-features-list');
                    featuresList.innerHTML = '';
                    tech.features.forEach(feature => {
                        const li = document.createElement('li');
                        li.innerHTML = `<i class="fas fa-check-circle"></i> ${feature}`;
                        featuresList.appendChild(li);
                    });

                    docBtn.href = tech.docUrl;

                    modal.style.display = 'flex';
                    setTimeout(() => modal.classList.add('active'), 10);
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        closeBtn.addEventListener('click', function() {
            closeModal();
            document.body.style.overflow = 'auto';
        });

        closeBtnBottom.addEventListener('click', function() {
            closeModal();
            document.body.style.overflow = 'auto';
        });

        const modalOverlay = modal.querySelector('.tech-modal-overlay');
        modalOverlay.addEventListener('click', function() {
            closeModal();
            document.body.style.overflow = 'auto';
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
                document.body.style.overflow = 'auto';
            }
        });

        // Smooth scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.skill-card, .stat-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>

</html>

<?php $content = ob_get_clean();
include 'layout.php'; ?>

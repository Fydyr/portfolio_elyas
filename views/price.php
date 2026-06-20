<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commissions - Fynt</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Style CSS personnalisé -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <!-- EmailJS SDK -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="profile-header fade-in">
            <div class="profile-avatar">
                <i class="bi bi-tags-fill"></i>
            </div>
            <h1>Commissions</h1>
            <h2>Prices for models, rigs, animation, art &amp; emotes</h2>
        </div>

        <!-- Main Content Section -->
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <?php if (empty($prices ?? [])): ?>
                    <div class="alert alert-info">No commissions configured yet.</div>
                <?php else: ?>
                    <?php foreach ($prices as $item): ?>
                        <div class="card fade-in mb-4">
                            <div class="card-header">
                                <h2 class="mb-0">
                                    <?php if (!empty($item['icon'])): ?>
                                        <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($item['title']) ?>
                                </h2>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($item['description'])): ?>
                                    <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($item['portfolio_slug'])): ?>
                                    <a href="<?= url('projects/' . urlencode($item['portfolio_slug'])) ?>" class="btn btn-outline-light btn-sm">
                                        <i class="bi bi-images me-1"></i> View examples
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="card fade-in mb-4">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <i class="bi bi-info-circle-fill"></i>
                            How to order &amp; terms
                        </h2>
                    </div>
                    <div class="card-body">
                        <p style="color: var(--text-secondary);">
                            Prices are starting estimates in <strong style="color: var(--text-primary);">EUR (€)</strong>
                            and may vary with complexity, detail and the number of revisions.
                            For an exact quote, reach out before ordering.
                        </p>

                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <div class="d-flex gap-3">
                                    <i class="bi bi-bag-check-fill" style="color: var(--primary-color); font-size: 1.4rem; flex-shrink: 0;"></i>
                                    <div>
                                        <strong style="color: var(--text-primary);">Ordering</strong>
                                        <p class="mb-0" style="color: var(--text-secondary);">All commissions go through <strong>VGen</strong>. Got a question first? Ping me on Discord.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-3">
                                    <i class="bi bi-credit-card-2-front-fill" style="color: var(--primary-color); font-size: 1.4rem; flex-shrink: 0;"></i>
                                    <div>
                                        <strong style="color: var(--text-primary);">Payment</strong>
                                        <p class="mb-0" style="color: var(--text-secondary);">Via <strong>PayPal</strong> or <strong>Ko-fi</strong>, taken up front before work begins.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-3">
                                    <i class="bi bi-list-check" style="color: var(--primary-color); font-size: 1.4rem; flex-shrink: 0;"></i>
                                    <div>
                                        <strong style="color: var(--text-primary);">Waitlist</strong>
                                        <p class="mb-0" style="color: var(--text-secondary);">Follow live progress and the queue on the commission board.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-3">
                                    <i class="bi bi-shield-check" style="color: var(--primary-color); font-size: 1.4rem; flex-shrink: 0;"></i>
                                    <div>
                                        <strong style="color: var(--text-primary);">Usage</strong>
                                        <p class="mb-0" style="color: var(--text-secondary);">Fine for your own streaming/content with credit. Please don't resell, trace or claim it as your own.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr style="border-color: rgba(255, 255, 255, 0.12); margin: 1.75rem 0 1.25rem;">

                        <div class="d-flex flex-wrap gap-2">
                            <a class="btn btn-primary" href="https://vgen.co/fyfyntt" target="_blank" rel="noopener"><i class="bi bi-pencil-fill me-1"></i> Commission me on VGen</a>
                            <a class="btn btn-outline-light" href="https://discord.gg/DTvkz3BQHz" target="_blank" rel="noopener"><i class="bi bi-discord me-1"></i> Discord</a>
                            <a class="btn btn-outline-light" href="https://boards.superthread.com/b/fe2b7e90-e901-42cf-b57a-0ecc5607e59c" target="_blank" rel="noopener"><i class="bi bi-kanban me-1"></i> Waitlist board</a>
                            <a class="btn btn-outline-light" href="https://paypal.me/FyntB" target="_blank" rel="noopener"><i class="bi bi-paypal me-1"></i> PayPal</a>
                            <a class="btn btn-outline-light" href="https://ko-fi.com/fyntsu" target="_blank" rel="noopener"><i class="bi bi-cup-hot-fill me-1"></i> Ko-fi</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<?php $content = ob_get_clean();
include 'layout.php'; ?>
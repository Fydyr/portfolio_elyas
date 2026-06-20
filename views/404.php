<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Fynt</title>
    <!-- Style CSS personnalisé -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="container">
        <div class="profile-header fade-in">
            <div class="profile-avatar">
                <i class="fas fa-compass fa-3x"></i>
            </div>
            <h1>404 - Page not found</h1>
            <p class="lead text-secondary mb-4">
                <?php if (isset($message)): ?>
                    <?= $message ?><br>
                <?php else: ?>
                    The page you're looking for doesn't exist or has moved.<br>
                <?php endif; ?>
                Check the URL or head back home.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="<?= homeUrl() ?>" class="btn btn-primary mt-3"><i class="fas fa-home me-2"></i>Back to home</a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary mt-3"><i class="fas fa-arrow-left me-2"></i>Go back</a>
            </div>
        </div>
    </div>

</body>

</html>

<?php $content = ob_get_clean();
include 'layout.php'; ?>

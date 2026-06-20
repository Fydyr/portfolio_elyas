<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Fynt</title>
    <!-- Style CSS personnalisé -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="container">
        <div class="profile-header fade-in">
            <div class="profile-avatar">
                <i class="fas fa-ban fa-3x"></i>
            </div>
            <h1>403 - Forbidden</h1>
            <p class="lead text-secondary mb-4">
                You don't have permission to access this page.<br>
                If you think this is a mistake, get in touch.
            </p>
            <a href="/" class="btn btn-primary mt-3"><i class="fas fa-home"></i> Back to home</a>
        </div>
    </div>

</body>

</html>

<?php $content = ob_get_clean();
include 'layout.php'; ?>
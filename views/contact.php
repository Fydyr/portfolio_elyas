<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Fynt</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Style CSS personnalisé -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="profile-header fade-in">
            <div class="profile-avatar">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <h1>Get in touch</h1>
            <h2>For commissions, the fastest way is Discord or VGen — pick a platform below</h2>
        </div>

        <div class="container">
            <!-- Lien vers les réseaux sociaux -->
            <div class="card fade-in mb-4">
                <div class="card-header">
                    <h2 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        Find me online
                    </h2>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h3 class="text-primary">
                                <a href="https://discord.gg/DTvkz3BQHz" target="_blank" rel="noopener" class="text-decoration-none">
                                    <i class="fab fa-discord text-muted me-2"></i>
                                    Discord
                                </a>
                            </h3>
                            <p class="text-muted small">The fastest way to reach me</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="text-primary">
                                <a href="https://vgen.co/fyfyntt" target="_blank" rel="noopener" class="text-decoration-none">
                                    <i class="fas fa-pen-nib text-muted me-2"></i>
                                    VGen
                                </a>
                            </h3>
                            <p class="text-muted small">Order a commission</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="text-primary">
                                <a href="https://twitter.com/_FoxBee" target="_blank" rel="noopener" class="text-decoration-none">
                                    <i class="fab fa-twitter text-muted me-2"></i>
                                    Twitter
                                </a>
                            </h3>
                            <p class="text-muted small">Latest work &amp; updates</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="text-primary">
                                <a href="https://www.youtube.com/@Fynt_Elyas" target="_blank" rel="noopener" class="text-decoration-none">
                                    <i class="fab fa-youtube text-muted me-2"></i>
                                    YouTube
                                </a>
                            </h3>
                            <p class="text-muted small">Videos &amp; animation</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="text-primary">
                                <a href="https://www.twitch.tv/fyfyntt" target="_blank" rel="noopener" class="text-decoration-none">
                                    <i class="fab fa-twitch text-muted me-2"></i>
                                    Twitch
                                </a>
                            </h3>
                            <p class="text-muted small">Live streams</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="text-primary">
                                <a href="https://ko-fi.com/fyntsu" target="_blank" rel="noopener" class="text-decoration-none">
                                    <i class="fas fa-mug-hot text-muted me-2"></i>
                                    Ko-fi
                                </a>
                            </h3>
                            <p class="text-muted small">Support &amp; tips</p>
                        </div>
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
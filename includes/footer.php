</div>
</main>

<?

// Compteur de visites
// Nom du fichier pour stocker le compteur
$fichier_compteur = __DIR__ . '/../assets/docs/compteur.txt';

// Fonction pour lire le nombre de visiteurs
function lire_compteur($fichier)
{
    if (file_exists($fichier)) {
        $compteur = file_get_contents($fichier);
        return (int)$compteur;
    }
    return 0;
}

// Fonction pour écrire le nombre de visiteurs
function ecrire_compteur($fichier, $nombre)
{
    file_put_contents($fichier, $nombre);
}

// Fonction pour vérifier si c'est un nouveau visiteur
function est_nouveau_visiteur()
{
    if (!isset($_SESSION['visite_comptee'])) {
        $_SESSION['visite_comptee'] = true;
        return true;
    }
    return false;
}

// Logique principale du compteur
$compteur_actuel = lire_compteur($fichier_compteur);

// Incrémenter seulement si c'est un nouveau visiteur dans cette session
if (est_nouveau_visiteur()) {
    $compteur_actuel++;
    ecrire_compteur($fichier_compteur, $compteur_actuel);

    // Log également dans daily_visits pour le graphique du dashboard
    if (isset($pdo) && $pdo instanceof PDO) {
        try {
            $pdo->prepare(
                "INSERT INTO daily_visits (day, count) VALUES (CURDATE(), 1)
                 ON DUPLICATE KEY UPDATE count = count + 1"
            )->execute();
        } catch (PDOException $e) {
            // table absente -> on ignore silencieusement (le compteur fichier marche encore)
        }
    }
}
?>

<!-- Footer -->
<footer class="footer-section">
    <div class="footer-bg-effect"></div>

    <!-- Footer principal -->
    <div class="footer-main">
        <div class="container">
            <div class="row g-4 justify-content-center">

                <!-- Colonne 1: À propos -->
                <div class="col-lg-4 col-md-12">
                    <div class="footer-widget">
                        <div class="footer-brand mb-4">
                            <h3 class="footer-title">
                                <span class="brand-icon">✦</span>
                                <?php echo $site_title; ?>
                            </h3>
                            <p class="footer-tagline">VTuber · Animator · Artist · Live2D Rigger</p>
                        </div>
                        <p class="footer-description">
                            Bringing characters to life — 3D models, Live2D rigs, animation, art &amp; emotes for streamers and creators.
                        </p>

                        <!-- Stats -->
                        <div class="footer-stats">
                            <div class="stat-item">
                                <span class="stat-number counter" data-target="<?= $project_count ?>"><?= $project_count ?></span>
                                <span class="stat-label">Portfolio works</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number counter" data-target="6">6</span>
                                <span class="stat-label">Services offered</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number counter" data-target="<?php echo $compteur_actuel; ?>"><?php echo $compteur_actuel; ?></span>
                                <span class="stat-label">Total visitors</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne 2: Contact & Réseaux -->
                <div class="col-lg-4 col-md-12">
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">Let's connect</h4>

                        <!-- Informations de contact -->
                        <div class="footer-contact mb-4">
                            <div class="contact-item">
                                <i class="fab fa-discord"></i>
                                <a href="https://discord.gg/DTvkz3BQHz" target="_blank" rel="noopener">Discord server</a>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-pen-nib"></i>
                                <a href="https://vgen.co/fyfyntt" target="_blank" rel="noopener">Commission me on VGen</a>
                            </div>
                        </div>

                        <!-- Réseaux sociaux -->
                        <div class="footer-social">
                            <h5 class="social-title">Follow me</h5>
                            <div class="social-links">
                                <a href="https://twitter.com/_FoxBee" target="_blank" rel="noopener" class="social-link" title="Twitter / X">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.instagram.com/fyfyntt/" target="_blank" rel="noopener" class="social-link" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://www.youtube.com/@Fynt_Elyas" target="_blank" rel="noopener" class="social-link" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="https://www.twitch.tv/fyfyntt" target="_blank" rel="noopener" class="social-link" title="Twitch">
                                    <i class="fab fa-twitch"></i>
                                </a>
                                <a href="https://www.tiktok.com/@fyfyntt" target="_blank" rel="noopener" class="social-link" title="TikTok">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                                <a href="https://ko-fi.com/fyntsu" target="_blank" rel="noopener" class="social-link" title="Ko-fi">
                                    <i class="fas fa-mug-hot"></i>
                                </a>
                                <a href="https://www.artstation.com/fyntsu/profile" target="_blank" rel="noopener" class="social-link" title="ArtStation">
                                    <i class="fab fa-artstation"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="footer-copyright">
                        <p>
                            &copy; <?= date('Y'); ?>
                            <strong><?= $site_title; ?></strong>.
                            All rights reserved. Hosted by <a href="https://www.ionos.fr" target="_blank" rel="noopener">IONOS</a>.
                            Please don't repost, trace or reuse my work without permission.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="footer-legal">
                        <ul class="legal-links">
                            <li><a href="<?= url('legal-mention') ?>">Legal notice</a></li>
                        </ul>
                        <ul class="legal-links">
                            <li><a href="<?= url('login')?>">Login</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Status indicator -->
            <!-- <div class="status-indicator">
                <div class="status-dot"></div>
                <span class="status-text">Disponible pour de nouveaux projets</span>
            </div> -->
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Particles.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les particules
        if (typeof particlesJS !== 'undefined') {
            // Poussière d'étoiles flottante (sans lignes) — ambiance VTuber/artiste
            particlesJS('particles-bg', {
                particles: {
                    number: {
                        value: 70,
                        density: { enable: true, value_area: 900 }
                    },
                    color: {
                        value: ["#B98FFF", "#D4B3FF", "#FFFFFF"]
                    },
                    shape: { type: "circle" },
                    opacity: {
                        value: 0.5,
                        random: true,
                        anim: { enable: true, speed: 0.8, opacity_min: 0.1, sync: false }
                    },
                    size: {
                        value: 2.5,
                        random: true,
                        anim: { enable: true, speed: 1.5, size_min: 0.4, sync: false }
                    },
                    line_linked: { enable: false },
                    move: {
                        enable: true,
                        speed: 0.6,
                        direction: "top",
                        random: true,
                        straight: false,
                        out_mode: "out",
                        bounce: false
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: { enable: false },
                        onclick: { enable: false },
                        resize: true
                    }
                },
                retina_detect: true
            });
        }

        // Auto-hide alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });

        // Compteurs animés
        const counters = document.querySelectorAll('.counter');

        const observerOptions = {
            threshold: 0.7
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-target'));
                    const increment = target / 50;
                    let current = 0;

                    const updateCounter = () => {
                        if (current < target) {
                            current += increment;
                            counter.textContent = Math.ceil(current);
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = target;
                        }
                    };

                    updateCounter();
                    observer.unobserve(counter);
                }
            });
        }, observerOptions);

        counters.forEach(counter => observer.observe(counter));
    });
</script>

</body>

</html>
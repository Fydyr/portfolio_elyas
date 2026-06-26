<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Links - Fynt</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <style>
        .links-wrap { max-width: 560px; margin: 0 auto; padding: 3.5rem 1rem 4rem; text-align: center; }
        .links-avatar {
            width: 110px; height: 110px; border-radius: 50%; margin: 0 auto 1.25rem;
            background: var(--gradient-primary); display: flex; align-items: center; justify-content: center;
            font-size: 3rem; color: #fff; box-shadow: 0 10px 40px rgba(185,143,255,0.35);
        }
        .links-name {
            background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; font-weight: 800; margin-bottom: 0.35rem;
        }
        .links-tagline { color: var(--text-secondary); margin-bottom: 2rem; }

        .link-cta {
            display: flex; align-items: center; justify-content: center; gap: 0.6rem;
            width: 100%; padding: 1rem 1.25rem; margin-bottom: 0.9rem; border-radius: 14px;
            font-weight: 700; text-decoration: none; transition: var(--transition);
            border: 1px solid rgba(185,143,255,0.35); color: var(--text-primary);
            background: var(--glass-bg); backdrop-filter: blur(10px);
        }
        .link-cta:hover { transform: translateY(-3px); border-color: var(--primary-color); box-shadow: 0 8px 24px rgba(185,143,255,0.25); color: #fff; }
        .link-cta.primary { background: var(--gradient-primary); border: none; color: #fff; }
        .link-cta i { font-size: 1.2rem; }

        .links-sep { color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; margin: 1.75rem 0 1rem; }

        .links-socials { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 0.8rem; }
        .links-tile {
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 1.1rem 0.5rem; border-radius: 14px; text-decoration: none;
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: var(--text-secondary);
            transition: var(--transition);
        }
        .links-tile i { font-size: 1.7rem; transition: transform .2s ease, color .2s ease; }
        .links-tile span { font-size: 0.9rem; }
        .links-tile:hover { color: #fff; border-color: var(--primary-color); background: rgba(185,143,255,0.1); transform: translateY(-4px); }
        .links-tile:hover i { transform: scale(1.15); color: var(--primary-color); }
    </style>
</head>

<body>
    <div class="links-wrap fade-in">
        <div class="links-avatar"><span style="font-family:'Courier New',monospace; color:#fff;">✦</span></div>
        <h1 class="links-name">Fynt</h1>
        <p class="links-tagline">VTuber · Animator · Artist · Live2D Rigger</p>

        <!-- CTA principaux -->
        <a href="https://vgen.co/fyfyntt" target="_blank" rel="noopener" class="link-cta primary">
            <i class="fas fa-pen-nib"></i> Commission me on VGen
        </a>
        <a href="https://discord.gg/DTvkz3BQHz" target="_blank" rel="noopener" class="link-cta">
            <i class="fab fa-discord"></i> Join my Discord
        </a>
        <a href="<?= url('price') ?>" class="link-cta">
            <i class="fas fa-tags"></i> See commission prices
        </a>
        <a href="<?= url('projects') ?>" class="link-cta">
            <i class="fas fa-palette"></i> Browse my portfolio
        </a>

        <div class="links-sep">Find me online</div>

        <div class="links-socials">
            <?php foreach ($links as $s): ?>
                <a href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener" class="links-tile">
                    <i class="<?= htmlspecialchars($s['icon'] ?: 'fas fa-link') ?>"></i>
                    <span><?= htmlspecialchars($s['label']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $content = ob_get_clean();
include 'layout.php'; ?>

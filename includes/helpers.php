<?php

function view($template, $data = [])
{
    // Gérer les meta tags si fournis
    if (isset($data['page_meta'])) {
        global $page_meta;
        $page_meta = $data['page_meta'];
    }

    extract($data);
    ob_start();
    include "views/$template.php";
    $content = ob_get_clean();
    return $content;
}

function json($data, $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

function redirect($url)
{
    header("Location: " . url($url));
    exit;
}

function url($path = '')
{
    $scriptName = $_SERVER['SCRIPT_NAME']; // ex: /index.php  ou  /sousdossier/index.php
    $basePath = dirname($scriptName);

    if ($basePath === '/' || $basePath === '\\') {
        $basePath = '';
    }

    $path = ltrim($path, '/');
    if ($path === '') {
        return $basePath === '' ? '/' : $basePath . '/';
    }

    return $basePath . '/' . $path;
}

function homeUrl()
{
    return url('');
}

function post($key, $default = null)
{
    return $_POST[$key] ?? $default;
}

function clean($string)
{
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

function flash($type, $message)
{
    $_SESSION['flash'][$type] = $message;
}

function getFlash($type)
{
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

/* ===== Helpers médias portfolio (images / vidéos uploadées + liens YouTube / vidéo) ===== */

/** Extrait l'ID d'une vidéo YouTube depuis une URL, ou null. */
function pf_youtube_id(?string $url): ?string
{
    if (!$url) return null;
    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})~', $url, $m)) {
        return $m[1];
    }
    return null;
}

function pf_is_video_file(?string $name): bool
{
    return $name !== null && (bool)preg_match('/\.(mp4|webm|ogg|mov)$/i', $name);
}

/** Type d'un média portfolio : image | video | youtube | video_url | embed */
function pf_media_type(array $item): string
{
    if (!empty($item['embed_url'])) {
        if (pf_youtube_id($item['embed_url'])) return 'youtube';
        if (preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $item['embed_url'])) return 'video_url';
        return 'embed';
    }
    return pf_is_video_file($item['filename'] ?? null) ? 'video' : 'image';
}

/** URL source directe (fichier uploadé ou lien brut). */
function pf_src(array $item): string
{
    if (!empty($item['filename'])) return '/assets/img/portfolio/' . $item['filename'];
    return $item['embed_url'] ?? '';
}

/** URL d'iframe d'embed (YouTube normalisé, sinon lien tel quel). */
function pf_embed_url(array $item): string
{
    $yt = pf_youtube_id($item['embed_url'] ?? '');
    if ($yt) return 'https://www.youtube.com/embed/' . $yt;
    return $item['embed_url'] ?? '';
}

/** Vignette d'aperçu : ['kind' => 'image'|'video'|'none', 'url' => ...]. */
function pf_thumb(array $item): array
{
    switch (pf_media_type($item)) {
        case 'image':     return ['kind' => 'image', 'url' => pf_src($item)];
        case 'video':     return ['kind' => 'video', 'url' => pf_src($item)];
        case 'video_url': return ['kind' => 'video', 'url' => $item['embed_url']];
        case 'youtube':   return ['kind' => 'image', 'url' => 'https://img.youtube.com/vi/' . pf_youtube_id($item['embed_url']) . '/hqdefault.jpg'];
        default:          return ['kind' => 'none',  'url' => ''];
    }
}

/** True si le média n'est pas une simple image (affichage badge ▶). */
function pf_is_playable(array $item): bool
{
    return pf_media_type($item) !== 'image';
}

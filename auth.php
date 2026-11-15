<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function startAppSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name('poet_admin_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function csrfToken(): string
{
    startAppSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(): void
{
    startAppSession();
    $submitted = $_POST['csrf_token'] ?? '';
    if (!is_string($submitted) || empty($_SESSION['csrf_token'])
        || !hash_equals($_SESSION['csrf_token'], $submitted)) {
        http_response_code(403);
        exit('Недействительный запрос. Обновите страницу и попробуйте ещё раз.');
    }
}

function requireAdmin(): void
{
    startAppSession();
    if (($_SESSION['admin_logged_in'] ?? false) !== true) {
        header('Location: login.php');
        exit;
    }
}

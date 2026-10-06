<?php
// core/core.php
// Included at the top of every page.

ob_start();

// ---------- Session ----------
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ($_SERVER['SERVER_PORT'] ?? null) == 443;

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ---------- Base URL ----------
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ecomlab2');
}

// ---------- Auth helpers ----------
function is_logged_in() {
    return isset($_SESSION['customer_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
}

function get_user_id() {
    return $_SESSION['customer_id'] ?? null;
}

function get_user_role() {
    return $_SESSION['user_role'] ?? null;
}

function require_login() {
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header("Location: " . BASE_URL . "/view/login.php");
        exit;
    }
}

function require_admin() {
    if (!is_admin()) {
        header("Location: " . BASE_URL . "/index.php");
        exit;
    }
}

// ---------- Logout ----------
function secure_logout() {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
    session_start();
    session_regenerate_id(true);
}

// ---------- Session security ----------
function sessionTimeout() {
    $timeoutDuration = 1800; // 30 minutes

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeoutDuration) {
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "/view/login.php");
        exit;
    }

    $_SESSION['last_activity'] = time();
}

function sessionHijackingCheck() {
    $userIp    = $_SERVER['REMOTE_ADDR'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (!is_logged_in()) {
        $_SESSION['user_ip']    = $userIp;
        $_SESSION['user_agent'] = $userAgent;
        return;
    }

    if (!isset($_SESSION['user_ip'], $_SESSION['user_agent']) ||
        $_SESSION['user_ip'] !== $userIp ||
        $_SESSION['user_agent'] !== $userAgent) {

        secure_logout();
        $_SESSION['error'] = "Session hijacking attempt detected. Please log in again.";
        header("Location: " . BASE_URL . "/view/login.php");
        exit;
    }
}

function sessionSecurity() {
    sessionHijackingCheck();
    sessionTimeout();
}

sessionSecurity(); // runs on every page load
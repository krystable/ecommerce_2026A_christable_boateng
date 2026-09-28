<?php

// ============================================================
// core.php
// ------------------------------------------------------------
// This file gets included at the top of every protected page in
// an app (require_once "../core/core.php";). It's the place for
// anything that needs to happen on EVERY page load - not just
// database access (that's what core/db_class.php is for).
//
// Below is a checklist of what this file is typically responsible
// for. Build these out one at a time as the app needs them.
// ============================================================

// TODO: start output buffering (ob_start())
// header('Location: ...') redirects fail if any output was already
// sent to the browser. Buffering output here means pages further
// down the line can still redirect safely even after printing
// something.
ob_start();

// TODO: start and secure the session
// - session_start() must run before $_SESSION can be read/written
//   anywhere else in the app
// - on a real (HTTPS) server, harden the session cookie:
//   session.cookie_secure, session.cookie_httponly, session.cookie_samesite
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0, // Session cookie expires when the browser closes
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Strict', // or 'Lax' depending on your needs
    ]);
    session_start();
}



// TODO: check for login
// A function that checks if a "logged in" session value is set.
// If not, remember the page the user was trying to reach, then
// redirect to the login page and stop the rest of the script from running.
function is_logged_in() {
    if (!isset($_SESSION['customer_id'])) {
        return isset($_SESSION['customer_id']);
    }
}

function is_admin() {
    return isset($_SESSION['customer_role']) && $_SESSION['customer_role'] === 1;
}
// TODO: get the logged-in user's id
// A small getter so pages don't touch $_SESSION directly - they
// just call something like core_get_user_id().
function get_user_id() {
    return $_SESSION['customer_id'] ?? null;
}
// TODO: get the logged-in user's role
// Same idea as above, for role (e.g. admin, customer, staff) so
// pages can decide what to show based on who's looking.
function get_user_role() {
    return $_SESSION['customer_role'] ?? null;
}


function require_login() {
    if (!is_logged_in()) {
        // Store the current page so we can redirect back after login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header("Location: ../views/login.php");
        exit;
    }
}

function require_admin() {
    if (!is_admin()) {
        // Optionally, you could redirect to a "not authorized" page
        header("Location: /index.php");
        exit;
    }
}



// TODO: session timeout
// Track the time of the last request. If too much time has passed
// since then, log the user out automatically.
function sessionTimeout() {
    $timeoutDuration = 1800; // 30 minutes

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeoutDuration) {
        // Last request was more than 30 minutes ago
        session_unset();     // Unset $_SESSION variable for the run-time
        session_destroy();   // Destroy session data in storage
        header("Location: ../views/login.php");
        exit;
    }

    $_SESSION['last_activity'] = time(); // Update last activity time stamp
}




   
// TODO: detect session hijacking
// Store the user's IP address and browser (User-Agent) at login.
// On every page load, compare them to the current request - if
// they don't match, something is wrong, so log the user out.
function sessionHijackingCheck() {
    $userIp = $_SERVER['REMOTE_ADDR'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (!is_logged_in()) {
        $_SESSION['user_ip'] = $userIp;
        $_SESSION['user_agent'] = $userAgent;
        return;
    }

    if (!isset($_SESSION['user_ip'], $_SESSION['user_agent']) ||
        $_SESSION['user_ip'] !== $userIp ||
        $_SESSION['user_agent'] !== $userAgent ){


        // Possible session hijacking attempt
        secure_logout();
        $_SESSION['error'] = "Session hijacking attempt detected. Please log in again.";
        header("Location: ../views/login.php");
        exit;
        
       
    }
}

// TODO: secure logout
// A function that clears all session data, deletes the session
// cookie, destroys the session, and starts a fresh one - used by
// both a manual "log out" click and the automatic checks above.
function secure_logout() {
    // Unset all session variables
    $_SESSION = [];

    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Start a new session
    session_start();

    session_regenerate_id(true); // Regenerate session ID to prevent fixation
}
// TODO: actually run the session check(s) above
// Whatever function ties this all together (e.g. sessionSecurity())
// should be called here, so simply including this file is enough
// to protect a page - no extra function calls needed on every page.
function sessionSecurity() {
    sessionHijackingCheck();
    sessionTimeout();

}
sessionSecurity(); // Run the session security checks on every page load
?>

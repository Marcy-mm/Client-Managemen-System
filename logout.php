<?php

session_start();
session_unset();
session_destroy();

// Expire the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
$em = "First Login";
header("Location: login.php?error=" . urlencode($em));

exit();

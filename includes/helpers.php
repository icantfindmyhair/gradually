<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_verify($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function e($str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function redirect($path)
{
    header("Location: $path");
    exit();
}

function input_date($str)
{
    // Normalize to Y-m-d or return null
    $t = strtotime($str);
    return $t ? date('Y-m-d', $t) : null;
}

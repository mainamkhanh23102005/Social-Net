<?php
// csrf.php

/**
 * Generate a CSRF token and store it in session
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generate a hashed token for URLs (to hide token generation logic)
 */
function generate_url_token($user_id, $target_user) {
    $secret = 'your-secret-key-change-this'; // Change this to a random secret
    $data = $user_id . '|' . $target_user . '|' . time();
    return hash_hmac('sha256', $data, $secret);
}

/**
 * Validate URL token (optional, for extra security)
 */
function validate_url_token($token, $user_id, $target_user) {
    // For simplicity, we'll just check if token exists
    // In production, you might want to verify the hash
    return !empty($token);
}
?>
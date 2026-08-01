<?php
// Database connection details
$host = "localhost";
$db = "UserSignUp";
$user = "root";
$pass = "";

// Encryption key
define('ENCRYPTION_KEY', 'mkooi7t2147473jnbhuyrwtfdcs125cskjh69cmnfnsdbvfhjlwd664322624');

// Encryption function
function encrypt_data($data) {
    $key = hash('sha256', ENCRYPTION_KEY);
    $iv = substr(hash('sha256', 'iv12345'), 0, 16);
    return base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv));
}

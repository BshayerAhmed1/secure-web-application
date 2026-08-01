<?php
require 'config.php';

session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize user input to prevent SQL injection
function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Encrypt user input for comparison with encrypted data in the database
//function encrypt_data($data) {
    // Replace this with your encryption logic
    //return openssl_encrypt($data, 'aes-128-cbc', 'secret_key', 0, '1234567890123456');
//}

//function decrypt_data($data) {
    // Replace this with your decryption logic
    //return openssl_decrypt($data, 'aes-128-cbc', 'secret_key', 0, '1234567890123456');
//}

// Define the maximum allowed failed attempts and time window
define('MAX_FAILED_ATTEMPTS', 3);
define('TIME_WINDOW_MINUTES', 15);
define('LOCKOUT_TIME_MINUTES', 30);

// Function to count failed attempts within the last 15 minutes
function count_failed_attempts($username) {
    global $conn;
    $timestamp_limit = date("Y-m-d H:i:s", strtotime('-' . TIME_WINDOW_MINUTES . ' minutes'));

    // SQL query to count failed login attempts in the last 15 minutes
    $sql = "SELECT COUNT(*) AS failed_count FROM login_attempts
            WHERE username = '$username'
            AND status = 'Failure'
            AND timestamp > '$timestamp_limit'";
    
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['failed_count'];
}

// Function to check if the account is locked
function is_account_locked($username) {
    global $conn;
    $timestamp_limit = date("Y-m-d H:i:s", strtotime('-' . LOCKOUT_TIME_MINUTES . ' minutes'));

    // Check if there are failed attempts and if the account is locked
    $sql = "SELECT COUNT(*) AS lock_count FROM login_attempts
            WHERE username = '$username'
            AND status = 'Failure'
            AND timestamp > '$timestamp_limit'";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    return $row['lock_count'] >= MAX_FAILED_ATTEMPTS;
}

// Check for empty fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    // Encrypt username and password before storing in the database
    $encrypted_username = encrypt_data($username);
    $encrypted_password = encrypt_data($password);
    
    // Check if the account is locked due to failed attempts
    if (is_account_locked($encrypted_username)) {
        echo "Your account is locked due to multiple failed login attempts. Please try again later.";
        exit;
    }

    // SQL query to find the user based on encrypted username
    $sql = "SELECT id, username, password_hash FROM Users WHERE username = '$encrypted_username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Decrypt username for comparison
        $decrypted_username = decrypt_data($row['username']);
        
        // Check password hash
        if (password_verify($password, $row['password_hash'])) {
            // Successful login
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $decrypted_username;
            header("Location: dashboard.php");  // Redirect to dashboard or home page

            // Log successful attempt
            log_attempt($encrypted_username, true);
            exit();
        } else {
            // Failed login
            log_attempt($encrypted_username, false);
            echo "Invalid username or password.";
        }
    } else {
        // No user found
        log_attempt($encrypted_username, false);
        echo "Invalid username or password.";
    }
}

// Function to log login attempts
function log_attempt($username, $success) {
    global $conn;

    $timestamp = date("Y-m-d H:i:s");
    $status = $success ? "Success" : "Failure";

    $log_sql = "INSERT INTO login_attempts (username, status, timestamp) VALUES ('$username', '$status', '$timestamp')";
    $conn->query($log_sql);
}

$conn->close();
?>

# Secure Full-Stack Web Application & Authentication System

## 📌 Project Overview
A secure full-stack web application built with a focus on defense-in-depth security principles. The platform features robust front-end validation seamlessly integrated with a PHP backend that enforces database encryption, SQL Injection protection, and brute-force mitigation mechanisms.

---

## 🛡️ Core Security Features & Mitigations

### 1. SQL Injection Mitigation
* Implemented **Prepared Statements with Parameterized Queries** (`mysqli::prepare` / `bind_param`) for user registration to ensure safe database execution[cite: 7].
* Integrated custom sanitization logic (`mysqli_real_escape_string` and string trimming) across authentication inputs[cite: 6].

### 2. Data Encryption & Password Hashing
* **AES-256-CBC Encryption:** Sensitive user fields (e.g., usernames and email addresses) are encrypted symmetrically prior to database insertion[cite: 5, 6, 7].
* **Bcrypt Password Hashing:** User passwords are hashed securely using `PASSWORD_BCRYPT` with salt generation, ensuring raw credentials are never stored in plaintext[cite: 7].

### 3. Account Protection & Anti-Brute-Force
* **Account Lockout Policy:** Automatically tracks login failures and locks accounts for 30 minutes after 3 consecutive failed attempts within a 15-minute window[cite: 6].
* **Audit Logging:** Logs every authentication attempt (success or failure) alongside timestamps in a dedicated database audit trail (`login_attempts`)[cite: 6].

### 4. Client-Side Defensive UX
* Real-time input validation enforcing password complexity rules (uppercase letters, numbers, and special characters)[cite: 1, 2].
* Interactive OTP verification and password recovery simulation flows[cite: 1, 2].

---

## 🗄️ Database Schema & Setup

To replicate the backend database environment, execute the following SQL script in MySQL/MariaDB:

sql
CREATE DATABASE IF NOT EXISTS UserSignUp;
USE UserSignUp;

-- Users Table (Stores AES-256 encrypted credentials & bcrypt hashes)
CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Login Attempts Table (Used for brute-force tracking & account lockouts)
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    status ENUM('Success', 'Failure') NOT NULL,
    timestamp DATETIME NOT NULL
);

---

## 🛠️ Tech Stack & Architecture

* **Frontend:** HTML5, CSS3, JavaScript (ES6+), Tailwind CSS
* **Backend:** PHP 8.x
* **Database:** MySQL
* **Cryptography:** OpenSSL (`AES-256-CBC`), Native PHP Password Hashing (`BCRYPT`)[cite: 5, 7]

---

## 📂 Repository Structure

```text
├── config.php              # Database setup & AES-256-CBC encryption logic[cite: 5]
├── login.php               # Authentication logic with brute-force rate-limiting[cite: 6]
├── signup.php              # Registration endpoint using prepared statements[cite: 7]
├── Home.html               # Main landing page & modal interface[cite: 1]
├── homeafterlogin.html     # Authenticated user dashboard interface[cite: 3]
├── signUp.html            # Registration interface with client-side validation[cite: 2]
└── profile.html           # User profile management interface[cite: 4]

# Secure Full-Stack Web Application & Authentication Simulation Lab

📌 **Project Overview**

A hands-on cybersecurity simulation lab designed to practice implementing defensive security controls and mitigating critical web application vulnerabilities. Built with a focus on defense-in-depth principles, the platform simulates a secure full-stack environment where front-end validation is coupled with a PHP backend enforcing strict data encryption, SQL injection protection, XSS mitigation, and automated brute-force mechanisms.

---

## 🛡️ Core Security Features & Mitigations

1. **SQL Injection Mitigation**
   - Implemented Prepared Statements with Parameterized Queries (`mysqli::prepare` / `bind_param`) during user registration to ensure safe database query execution.
   - Integrated custom sanitization logic (`mysqli_real_escape_string` and string trimming) across authentication inputs.

2. **Data Encryption & Password Hashing**
   - **AES-256-CBC Encryption:** Sensitive user fields (such as usernames and email addresses) are symmetrically encrypted prior to database insertion via `config.php`.
   - **Bcrypt Password Hashing:** User passwords are secured using `PASSWORD_BCRYPT` with salt generation, ensuring raw credentials are never stored in plaintext.

3. **Account Protection & Anti-Brute-Force**
   - **Account Lockout Policy:** Automatically tracks failed login attempts and locks accounts for 30 minutes following 3 consecutive failed attempts within a 15-minute sliding window.
   - **Audit Logging:** Logs every authentication attempt (success or failure) alongside exact timestamps in a dedicated database audit trail (`login_attempts`).

4. **Cross-Site Scripting (XSS) Mitigation**
   - Implemented strict input sanitization and output encoding across user-supplied data and front-end rendering to prevent malicious script injection.

5. **Client-Side Defensive UX**
   - Real-time input validation enforcing strict password complexity rules (requiring uppercase letters, numbers, and special characters).

---

## 🗄️ Database Schema & Setup

To replicate the backend database environment, execute the following SQL script in MySQL/MariaDB:

```sql
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
```
---

🛠️ Tech Stack & Architecture
Frontend: HTML5, CSS3, JavaScript (ES6+), Tailwind CSS

Backend: PHP 8.x

Database: MySQL

Cryptography: OpenSSL (AES-256-CBC), Native PHP Password Hashing (BCRYPT)

---
📂 Repository Structure
```
├── config.php              # Database setup & AES-256-CBC encryption logic
├── login.php               # Authentication logic with brute-force rate-limiting
├── signup.php              # Registration endpoint using prepared statements
├── Home.html               # Main landing page & modal interface
├── homeafterlogin.html     # Authenticated user dashboard interface
├── signUp.html            # Registration interface with client-side validation
└── profile.html           # User profile management interface

```
طط
طط--

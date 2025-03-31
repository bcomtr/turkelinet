<?php
// app/Models/User.php - Clean Version

require_once dirname(__DIR__) . '/Core/Database.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Finds a user by their username.
     * Includes password hash for login verification.
     *
     * @param string $username
     * @return array|false User data or false if not found
     */
    public function findByUsername(string $username) {
        try {
            // Include password_hash for login check
            $sql = "SELECT user_id, username, email, password_hash, role, full_name, is_active
                    FROM users
                    WHERE username = :username LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) { error_log("Error finding user by username ({$username}): " . $e->getMessage()); return false; }
    }

    /**
     * Finds a user by their email address.
     * Includes password hash for login verification.
     *
     * @param string $email
     * @return array|false User data or false if not found
     */
    public function findByEmail(string $email) {
        try {
            // Include password_hash for login check
            $sql = "SELECT user_id, username, email, password_hash, role, full_name, is_active
                    FROM users
                    WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) { error_log("Error finding user by email ({$email}): " . $e->getMessage()); return false; }
    }

    /**
     * Registers a new user. Hashes the password.
     *
     * @param array $data User data
     * @return int|false New user ID or false on failure
     */
    public function registerUser(array $data) {
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) { return false; }
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        if ($passwordHash === false) { error_log("Password hashing failed."); return false; }
        try {
            $sql = "INSERT INTO users (username, email, password_hash, full_name, created_at, is_active, role)
                    VALUES (:username, :email, :password_hash, :full_name, NOW(), 1, 'user')";
            $stmt = $this->conn->prepare($sql);
            $fullName = isset($data['full_name']) ? trim($data['full_name']) : null;
            $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':password_hash', $passwordHash, PDO::PARAM_STR);
            $stmt->bindParam(':full_name', $fullName, PDO::PARAM_STR);
            if ($stmt->execute()) { return $this->conn->lastInsertId(); } else { return false; }
        } catch (PDOException $e) { if ($e->getCode() == 23000) { error_log("Registration failed: Duplicate username or email."); } else { error_log("Error registering user: " . $e->getMessage()); } return false; }
    }

    /**
     * Attempts to log in a user.
     * Verifies the password against the stored hash.
     *
     * @param string $usernameOrEmail Username or Email
     * @param string $password Plain text password
     * @return array|false User data array (excluding password) on success, false on failure (user not found, wrong password, inactive)
     */
    public function loginUser(string $usernameOrEmail, string $password) {
        try {
            // Determine if login is via email or username
            if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
                $user = $this->findByEmail($usernameOrEmail);
            } else {
                $user = $this->findByUsername($usernameOrEmail);
            }

            // Check if user exists
            if (!$user) {
                return false; // User not found
            }

            // Check if account is active
            // Ensure the column name matches your DB schema exactly
            if (!isset($user['is_active']) || !$user['is_active']) {
                error_log("Login attempt for inactive user: {$usernameOrEmail}");
                return false; // Account inactive or column missing/falsy
            }

            // Verify the password
            // Ensure password hash exists before verifying
            if (isset($user['password_hash']) && password_verify($password, $user['password_hash'])) {
                // Password is correct, remove hash before returning user data
                unset($user['password_hash']);
                return $user;
            } else {
                // Incorrect password or hash missing
                return false;
            }
        } catch (Exception $e) {
            error_log("Error during login for ({$usernameOrEmail}): " . $e->getMessage());
            return false;
        }
    }

    // Other methods...
}
?>

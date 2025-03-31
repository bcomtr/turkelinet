<?php
// admin/reset_admin_password.php
// WARNING: Delete this file after use!

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<pre>"; // For better formatting of output

// Include necessary files (adjust paths if needed)
require_once dirname(__DIR__) . '/app/config.php'; // Go up one level
require_once dirname(__DIR__) . '/app/Core/Database.php';

// --- Configuration ---
$targetUsername = 'editor'; // The username to reset password for
$newPassword = 'password'; // The desired new password
// --- End Configuration ---

echo "Attempting to reset password for user: " . htmlspecialchars($targetUsername) . "\n";

try {
    // Get DB connection
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "Database connection obtained.\n";

    // Generate the new password hash
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    if ($newPasswordHash === false) {
        throw new Exception("Password hashing failed!");
    }
    echo "New password hash generated: " . htmlspecialchars($newPasswordHash) . "\n";

    // Prepare the UPDATE statement
    $sql = "UPDATE users SET password_hash = :password_hash WHERE username = :username";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':password_hash', $newPasswordHash, PDO::PARAM_STR);
    $stmt->bindParam(':username', $targetUsername, PDO::PARAM_STR);

    // Execute the update
    echo "Executing database update...\n";
    if ($stmt->execute()) {
        // Check if any row was actually updated
        if ($stmt->rowCount() > 0) {
            echo "SUCCESS: Password for user '" . htmlspecialchars($targetUsername) . "' has been updated successfully!\n";
            echo "You should now be able to log in with username '" . htmlspecialchars($targetUsername) . "' and password '" . htmlspecialchars($newPassword) . "'.\n";
            echo "\nIMPORTANT: DELETE THIS FILE (reset_admin_password.php) NOW!";
        } else {
            echo "WARNING: SQL executed successfully, but no user found with username '" . htmlspecialchars($targetUsername) . "'. No password was changed.\n";
            echo "Please check the username in the script and in your database.\n";
        }
    } else {
        echo "ERROR: Failed to execute the database update statement.\n";
    }

} catch (Throwable $e) { // Catch Throwable for broader errors (PHP 7+)
    echo "ERROR: An exception occurred: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "</pre>";

?>

<?php
if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line.\n";
    exit(1);
}

if ($argc < 2) {
    echo "Usage: php db/set_admin_password.php <password> [email]\n";
    exit(1);
}

$password = $argv[1];
$email = isset($argv[2]) ? $argv[2] : 'admin@tlaab.com';

// Adjust path if needed
require __DIR__ . '/connect.php';

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = mysqli_prepare($connect, "UPDATE users SET password = ? WHERE email = ?");
if (!$stmt) {
    echo "Prepare failed: " . mysqli_error($connect) . "\n";
    exit(1);
}
mysqli_stmt_bind_param($stmt, "ss", $hash, $email);
if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Password updated for {$email}\n";
    } else {
        // Insert admin user if not exists
        $insert = mysqli_prepare($connect, "INSERT INTO users (email, username, password, reg_date, is_admin) VALUES (?, 'admin', ?, NOW(), 1)");
        mysqli_stmt_bind_param($insert, "ss", $email, $hash);
        if (mysqli_stmt_execute($insert)) {
            echo "Admin user created: {$email}\n";
        } else {
            echo "Failed to create admin user: " . mysqli_error($connect) . "\n";
        }
    }
} else {
    echo "Execute failed: " . mysqli_stmt_error($stmt) . "\n";
}

?>

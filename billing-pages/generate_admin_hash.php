<?php
// Generate bcrypt hash for admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
echo "\n";
echo "To verify: " . (password_verify($password, $hash) ? "VALID" : "INVALID") . "\n";

// Also verify the demo password
$demoPassword = 'demo123';
$demoHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
echo "\nDemo password verification: " . (password_verify($demoPassword, $demoHash) ? "VALID" : "INVALID") . "\n";
?> 
<?php
// Generate bcrypt hash for demo123
$password = 'demo123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
echo "\n";
echo "To verify: " . (password_verify($password, $hash) ? "VALID" : "INVALID") . "\n";
?> 
<?php
require_once 'config/config.php';

$email = 'leduytctv2019@gmail.com';

$db = getDB();
$stmt = $db->prepare('SELECT id, email, password, reset_token FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "User info:\n";
print_r($user);

// Test password verify
$testPassword = 'test123'; // Thay bằng password bạn vừa đặt
echo "\n\nTesting password '$testPassword':\n";
if ($user) {
    $result = password_verify($testPassword, $user['password']);
    echo "Password verify result: " . ($result ? 'TRUE - Matched!' : 'FALSE - Not matched') . "\n";
    echo "Hash in DB: " . substr($user['password'], 0, 60) . "...\n";
}

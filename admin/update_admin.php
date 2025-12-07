<?php
// One-time admin credential updater. Visit this file once, then delete it.
require_once __DIR__ . '/../config/database.php';

$newUsername = 'lakee';
$newPasswordPlain = 'lakee123#';

try {
    $newHash = password_hash($newPasswordPlain, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE admins SET username = ?, password = ? WHERE id = 1");
    $stmt->execute([$newUsername, $newHash]);
    echo 'Updated admin credentials successfully. Username: ' . htmlspecialchars($newUsername) . '. You can now log in. DELETE this file (admin/update_admin.php) for security.';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Failed to update admin credentials: ' . htmlspecialchars($e->getMessage());
}
?>



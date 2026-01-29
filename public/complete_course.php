<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$userId = $_SESSION['user']['id'];
$courseId = (int)($_POST['course_id'] ?? 0);

if ($courseId) {
    $stmt = $pdo->prepare("UPDATE enrollments 
                           SET status = 'completed', progress = 100, completed_at = NOW() 
                           WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$userId, $courseId]);
}

header("Location: dashboard.php");
exit;
?>
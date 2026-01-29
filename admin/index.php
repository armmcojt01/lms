<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();
if(!is_admin()){ echo 'Admin only'; exit; }

$stmt = $pdo->query('
    SELECT 
        (SELECT COUNT(*) FROM users) AS users_count,
        (SELECT COUNT(*) FROM courses) AS courses_count,
        (SELECT COUNT(*) FROM enrollments) AS enroll_count
');
$counts = $stmt->fetch();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="d-flex">

    <!-- Sidebar -->
    <?php include __DIR__ . '/../inc/sidebar.php'; ?>

    <!-- Main content -->
    <div class="flex-fill p-4">
        <h4>Admin Dashboard</h4>
        <p>Users: <?= $counts['users_count'] ?> | Courses: <?= $counts['courses_count'] ?> | Enrollments: <?= $counts['enroll_count'] ?></p>

        <p>
            <a href="<?= BASE_URL ?>/admin/users_crud.php" class="btn btn-sm btn-primary"><i class="fa fa-users"></i> Manage Users</a>
            <a href="<?= BASE_URL ?>/admin/courses_crud.php" class="btn btn-sm btn-primary"><i class="fa fa-book"></i> Manage Courses</a>
            <a href="<?= BASE_URL ?>/admin/news_crud.php" class="btn btn-sm btn-primary"><i class="fa fa-newspaper"></i> Manage News</a>
        </p>
    </div>

</div>

</body>
</html>

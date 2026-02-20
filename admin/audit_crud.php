<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

if(!is_admin() && !is_superadmin()){ 
    echo 'Admin only'; 
    exit; 
}

// Prepare Courses
$stmt = $pdo->prepare("
    SELECT c.id, c.title, c.description, c.thumbnail, c.created_at
    FROM courses c 
    ORDER BY c.id DESC
");
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Prepare User

$stmt = $pdo->prepare("
    SELECT u.id, u.username, u.role, u.fname,u.departments
    FROM users u 
    ORDER BY u.id DESC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare edited infor

$stmt = $pdo->prepare("
    SELECT e.id, e.title, e.description, e.thumbnail, e.proponent_id, e.file_pdf, e.file_video, e.created_at, e.expires_at, e.is_active, e.summary
    FROM edit e 
    ORDER BY e.id DESC
");
$stmt->execute();
$edit = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT d.id, d.name
    FROM departments d 
    ORDER BY d.id DESC
");
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!doctype html>
<html>
<head>
<title>Super - Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/sidebar.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/profile.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/manager.css" rel="stylesheet">
</head>
<body>
<div class="lms-sidebar-container">
<?php include __DIR__ . '/../inc/sidebar.php'; ?>
</div>

<div class="container">
<h1>Audit Trail</h1>


<!-- table trail -->
<table class="table">
<thead>
<tr>

<th>Course_ID</th>
<th>old value</th>
<th>New value</th>
<th>Edited by</th>
<th>Edited at </th>
<th>role</th>
<th>Department</th>
<th>Date Edited</th>
</tr>
</thead>
<tbody>

<!-- call user id -->
<?php foreach ($courses as $course): ?>
    <?php foreach ($users as $user): ?>
            <?php foreach ($departments as $department): ?>
<tr>

 <h5 class="mb-3"><td><?= htmlspecialchars($course['id']) ?></td></h5>
<td><?= htmlspecialchars('old Value') ?></td> 
<td><?= htmlspecialchars('New Value') ?></td>
<td><?= htmlspecialchars($user['fname']) ?></td>
<td><?= htmlspecialchars($course['created_at']) ?></td>
<td><?= htmlspecialchars($user['role']) ?></td>
<td><?= htmlspecialchars($department['name']) ?></td>
<td><?= htmlspecialchars('DATE - NULL') ?></td>
</tr>
    </div>
<!-- close call -->
<?php endforeach; ?>
<?php endforeach; ?>
<?php endforeach; ?>
</tbody>
</table>
</div>
</body>
</html> 

<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

if (!is_admin() && !is_proponent()) {
    echo "Access denied";
    exit;
}

$act = $_GET['act'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$message = '';

// Handle Add
if ($act === 'addform' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $totalDuration = 0;
        if ($course['file_pdf']) $totalDuration += 60; // assume 1 min for PDF reading
        if ($course['file_video']) {
            // You can store video duration in DB or get via PHP using FFmpeg, for now assume 300 sec
            $totalDuration += 300; 
        }

    // NEW: expiration inputs
    $expires_at = $_POST['expires_at'] ?? null;   // date input
    $valid_days = $_POST['valid_days'] ?? null;   // number of days

    // Auto-calculate expiration if valid_days is provided
    if (!empty($valid_days)) {
        $expires_at = date('Y-m-d', strtotime("+{$valid_days} days"));
    }

    $thumbnail = null;
    $pdf = null;
    $video = null;

    // Thumbnail upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        move_uploaded_file(
            $_FILES['thumbnail']['tmp_name'],
            __DIR__ . '/../uploads/images/' . $filename
        );
        $thumbnail = $filename;
    }

    // PDF upload
    if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION));
        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        move_uploaded_file(
            $_FILES['file_pdf']['tmp_name'],
            __DIR__ . '/../uploads/pdf/' . $filename
        );
        $pdf = $filename;
    }

    // Video upload
    if (isset($_FILES['file_video']) && $_FILES['file_video']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['file_video']['name'], PATHINFO_EXTENSION));
        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        move_uploaded_file(
            $_FILES['file_video']['tmp_name'],
            __DIR__ . '/../uploads/video/' . $filename
        );
        $video = $filename;
    }

    // INSERT with expiration
    $stmt = $pdo->prepare("
        INSERT INTO courses 
        (title, description, thumbnail, file_pdf, file_video, proponent_id, created_at, expires_at, is_active)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, 1)
    ");

    $stmt->execute([
        $title,
        $description,
        $thumbnail,
        $pdf,
        $video,
        $_SESSION['user']['id'],
        $expires_at
    ]);

    header('Location: courses_crud.php');
    exit;
}


// Handle Edit
if ($act === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$id]);
    $course = $stmt->fetch();

    if (!$course) {
        echo "Course not found";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $expires_at = $_POST['expires_at'] ?? '';
        $thumbnail = $course['thumbnail'];
        $pdf = $course['file_pdf'];
        $video = $course['file_video'];

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], __DIR__ . '/../uploads/images/' . $filename);
            $thumbnail = $filename;
        }

        if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION));
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($_FILES['file_pdf']['tmp_name'], __DIR__ . '/../uploads/pdf/' . $filename);
            $pdf = $filename;
        }

        if (isset($_FILES['file_video']) && $_FILES['file_video']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['file_video']['name'], PATHINFO_EXTENSION));
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($_FILES['file_video']['tmp_name'], __DIR__ . '/../uploads/video/' . $filename);
            $video = $filename;
        }

        $stmt = $pdo->prepare("UPDATE courses SET title=?, description=?, expires_at=?, thumbnail=?, file_pdf=?, file_video=? WHERE id=?");
        $stmt->execute([$title, $description, $thumbnail, $pdf, $video, $id, $expires_at]);
        header('Location: courses_crud.php');
        exit;
    }
}

// Handle Delete
if ($act === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id=?");
    $stmt->execute([$id]);
    header('Location: courses_crud.php');
    exit;
}

// Fetch all courses
$courses = $pdo->query("SELECT c.*, u.username FROM courses c LEFT JOIN users u ON c.proponent_id = u.id ORDER BY c.created_at DESC")->fetchAll();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Courses CRUD</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.main { padding: 30px; }
.card img { height: 180px; object-fit: cover; }
.card-actions { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
</style>
</head>
<body>
<?php include __DIR__ . '/../inc/sidebar.php'; ?>
<div class="main container">
<h3 class="mb-4">Courses Management</h3>

<?php if ($act === 'addform' || $act === 'edit'): ?>
<?php $editing = ($act === 'edit'); ?>
<div class="card p-4 mb-4 shadow-sm bg-white rounded">
<form method="post" enctype="multipart/form-data" action="">
    <div class="mb-3"><input name="title" class="form-control" placeholder="Title" required value="<?= $editing ? htmlspecialchars($course['title']) : '' ?>"></div>
    <div class="mb-3"><textarea name="description" class="form-control" placeholder="Description" rows="4" required><?= $editing ? htmlspecialchars($course['description']) : '' ?></textarea></div>
    <div class="row">
    <div class="col-md-6 mb-3">
        <label>Expiration Date</label>
        <input type="date" name="expires_at" class="form-control"
               value="<?= $editing && $course['expires_at'] ? $course['expires_at'] : '' ?>">
    </div>

    <div class="col-md-6 mb-3">
        <label>Validity (Days)</label>
        <input type="number" name="valid_days" class="form-control"
               placeholder="Example: 5">
        <small class="text-muted">Auto-calculate expiration</small>
    </div>
    </div>
    <div class="mb-3">
        <label>Thumbnail</label> <input type="file" name="thumbnail" class="form-control">
        <?php if($editing && $course['thumbnail']): ?>
            <img src="<?= BASE_URL ?>/uploads/images/<?= $course['thumbnail'] ?>" width="120" class="mt-2">
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label>PDF</label> <input type="file" name="file_pdf" class="form-control">
        <?php if($editing && $course['file_pdf']): ?>
            <a href="<?= BASE_URL ?>/uploads/pdf/<?= $course['file_pdf'] ?>" target="_blank" class="d-block mt-1">View PDF</a>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label>Video</label> <input type="file" name="file_video" class="form-control">
        <?php if($editing && $course['file_video']): ?>
            <a href="<?= BASE_URL ?>/uploads/video/<?= $course['file_video'] ?>" target="_blank" class="d-block mt-1">View Video</a>
        <?php endif; ?>
    </div>

    <button class="btn btn-primary"><?= $editing ? 'Update Course' : 'Add Course' ?></button>
    <a href="courses_crud.php" class="btn btn-secondary ms-2">Back to List</a>
</form>
</div>

<?php else: ?>
<p><a href="?act=addform" class="btn btn-success mb-3">Add New Course</a></p>

<div class="row row-cols-1 row-cols-md-3 g-4">
<?php foreach ($courses as $c): ?>
<div class="col">
    <div class="card h-100 shadow-sm bg-white rounded">
        <img src="<?= BASE_URL ?>/uploads/images/<?= htmlspecialchars($c['thumbnail'] ?: 'placeholder.png') ?>" class="card-img-top" alt="Course Thumbnail">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($c['title']) ?></h5>
            <p class="card-text"><?= htmlspecialchars(substr($c['description'],0,100)) ?>...</p>
            <div class="card-actions mt-auto">
                <?php if (is_admin() || $c['proponent_id'] == $_SESSION['user']['id']): ?>
                    <a href="?act=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-warning flex-fill">Edit</a>
                <?php endif; ?>
                <?php if (is_admin()): ?>
                    <a href="?act=delete&id=<?= $c['id'] ?>" onclick="return confirm('Delete this course?')" class="btn btn-sm btn-danger flex-fill">Delete</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

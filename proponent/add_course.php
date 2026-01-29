<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';

require_login();

// Only proponents or admins can add courses
if(!is_proponent() && !is_admin()){
    header('Location: ' . BASE_URL . '/public/dashboard.php');
    exit;
}

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $userId = $_SESSION['user']['id']; // Proponent or Admin ID

    // --- THUMBNAIL UPLOAD ---
    $thumbnail = null;
    if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        if(in_array($ext, $allowed_ext)) {
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            $target = __DIR__ . '/../uploads/images/' . $filename;
            if(move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target)) {
                $thumbnail = $filename;
            } else {
                $message = 'Failed to upload thumbnail.';
            }
        } else {
            $message = 'Invalid thumbnail file type.';
        }
    }

    // --- PDF UPLOAD ---
    $pdf = null;
    if(isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['pdf'];
        $ext = strtolower(pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION));
        if(in_array($ext, $allowed_ext)) {
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            $target = __DIR__ . '/../uploads/pdf/' . $filename;
            if(move_uploaded_file($_FILES['file_pdf']['tmp_name'], $target)) {
                $pdf = $filename;
            } else {
                $message = 'Failed to upload PDF.';
            }
        } else {
            $message = 'Invalid PDF file type.';
        }
    }

    // --- VIDEO UPLOAD ---
    $video = null;
    if(isset($_FILES['file_video']) && $_FILES['file_video']['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['mp4','webm','ogg'];
        $ext = strtolower(pathinfo($_FILES['file_video']['name'], PATHINFO_EXTENSION));
        if(in_array($ext, $allowed_ext)) {
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            $target = __DIR__ . '/../uploads/video/' . $filename;
            if(move_uploaded_file($_FILES['file_video']['tmp_name'], $target)) {
                $video = $filename;
            } else {
                $message = 'Failed to upload video.';
            }
        } else {
            $message = 'Invalid video file type.';
        }
    }

    // --- INSERT INTO DATABASE ---
    if(empty($message)) {
        $stmt = $pdo->prepare("
            INSERT INTO courses
            (title, description, thumbnail, file_pdf, file_video, proponent_id, created_at, is_active)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), 1)
        ");
        $stmt->execute([$title, $description, $thumbnail, $pdf, $video, $userId]);
        $message = 'Course added successfully!';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add Course - LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/../inc/sidebar.php'; ?>

<div class="main">
    <h3>Add New Course</h3>

    <?php if($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Course Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Course Description</label>
            <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Course Thumbnail (optional)</label>
            <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="mb-3">
            <label for="file_pdf" class="form-label">PDF / Ebook (optional)</label>
            <input type="file" name="file_pdf" id="file_pdf" class="form-control" accept=".pdf">
        </div>

        <div class="mb-3">
            <label for="file_video" class="form-label">Video (optional)</label>
            <input type="file" name="file_video" id="file_video" class="form-control" accept=".mp4,.webm,.ogg">
        </div>

        <button type="submit" class="btn btn-primary">Add Course</button>
    </form>
</div>

</body>
</html>

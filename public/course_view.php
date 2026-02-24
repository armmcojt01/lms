<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/functions.php';


require_login();
$u = current_user();

$courseId = intval($_GET['id'] ?? 0);
if(!$courseId) die('Invalid course ID');

// Fetch course
$stmt = $pdo->prepare('SELECT c.*, u.fname, u.lname FROM courses c LEFT JOIN users u ON c.proponent_id = u.id WHERE c.id = ?');
$stmt->execute([$courseId]);
$course = $stmt->fetch();
if(!$course) die('Course not found');

// Fetch enrollment if student
$enrollment = null;

if (is_student()) {

// BLOCK if course expired or inactive
$today = date('Y-m-d');

if (
$course['is_active'] == 0 ||
($course['expires_at'] && $today > $course['expires_at'])
) {

die('<div class="alert alert-danger m-4">
<h5>Course Unavailable</h5>
<p>This course has expired or is no longer active.</p>
</div>');
}

// Check enrollment
$stmt = $pdo->prepare('SELECT * FROM enrollments WHERE user_id=? AND course_id=?');
$stmt->execute([$u['id'], $courseId]);
$enrollment = $stmt->fetch();

if (!$enrollment) {
// Auto-create enrollment ONLY if course is valid
$stmt = $pdo->prepare('
INSERT INTO enrollments 
(user_id, course_id, enrolled_at, status, progress, total_time_seconds) 
VALUES (?, ?, NOW(), "ongoing", 0, 0)
');
$stmt->execute([$u['id'], $courseId]);

$enrollmentId = $pdo->lastInsertId();
$enrollment = [
'id' => $enrollmentId,
'progress' => 0,
'total_time_seconds' => 0,
'status' => 'ongoing'
];
} else {
$enrollmentId = $enrollment['id'];
}
}

// Handle AJAX time tracking
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['seconds']) && is_student()) {
$seconds = intval($_POST['seconds']);
$total_seconds = $enrollment['total_time_seconds'] + $seconds;
$progress = $total_seconds;
$stmt = $pdo->prepare('UPDATE enrollments SET total_time_seconds=?, progress=? WHERE id=?');
$stmt->execute([$total_seconds, $progress, $enrollment['id']]);
echo json_encode(['success'=>true]);
exit;
}

// Handle completion - UPDATED WITH EMAIL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_completed']) && is_student()) {

// Start transaction
$pdo->beginTransaction();

try {
// Update enrollment status
$stmt = $pdo->prepare("
UPDATE enrollments 
SET status = 'completed', completed_at = NOW() 
WHERE id = ?
");
$stmt->execute([$enrollment['id']]);

// Get student info for email
$studentName = trim($u['fname'] . ' ' . $u['lname']);
if (empty($studentName)) $studentName = $u['username'];

// SEND COMPLETION EMAIL PROTOTYPE - REPLACE WITH ACTUAL FUNCTION
// $emailResult = sendCourseCompletionEmailSimple(
// $u['email'],
// $studentName,
// $course['title']
// );

// Log email result
if ($emailResult['success']) {
error_log("Completion email sent to {$u['email']} for course: {$course['title']}");
} else {
error_log("Failed to send completion email: " . $emailResult['message']);
}

$pdo->commit();

echo json_encode([
'success' => true,
'email_sent' => $emailResult['success'],
'message' => $emailResult['success'] ? 'Course completed and email sent!' : 'Course completed but email failed to send.'
]);

} catch (Exception $e) {
$pdo->rollBack();
echo json_encode([
'success' => false,
'message' => 'Error completing course: ' . $e->getMessage()
]);
}

exit;
}

// Rest of your existing code...
// ============================================
// FETCH ENROLLED STUDENTS - FOR ADMIN/PROPONENT
// ============================================

// 1. ALL enrolled students (ongoing + completed)
$stmt = $pdo->prepare('
    SELECT 
        u.id, 
        u.fname, 
        u.lname, 
        u.email,
        u.username,
        e.status,
        e.progress,
        e.total_time_seconds,
        e.enrolled_at,
        e.completed_at,
        DATE_FORMAT(e.enrolled_at, "%M %d, %Y") as enrolled_date,
        DATE_FORMAT(e.completed_at, "%M %d, %Y") as completed_date,
        CASE 
            WHEN e.status = "completed" THEN "bg-success"
            WHEN e.status = "ongoing" THEN "bg-warning"
            ELSE "bg-secondary"
        END as status_color,
        CASE 
            WHEN e.status = "completed" THEN "Completed"
            WHEN e.status = "ongoing" THEN "Ongoing"
            ELSE "Not Started"
        END as status_text
    FROM enrollments e
    JOIN users u ON e.user_id = u.id 
    WHERE e.course_id = ?
    ORDER BY 
        CASE e.status 
            WHEN "ongoing" THEN 1 
            WHEN "completed" THEN 2 
            ELSE 3 
        END,
        e.enrolled_at DESC
');
$stmt->execute([$courseId]);
$enrolledStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Count statistics
$stmt = $pdo->prepare('
    SELECT 
        COUNT(*) as total_enrolled,
        SUM(CASE WHEN status = "ongoing" THEN 1 ELSE 0 END) as ongoing_count,
        SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_count
    FROM enrollments 
    WHERE course_id = ?
');
$stmt->execute([$courseId]);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Completion rate
$completionRate = 0;
if ($stats['total_enrolled'] > 0) {
    $completionRate = round(($stats['completed_count'] / $stats['total_enrolled']) * 100);
}

//4 rpt
if (isset($_GET['export']) && $_GET['export'] == 'csv' && (is_admin() || is_proponent())) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="enrolled_students_course_' . $courseId . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Student Name', 'Email', 'Username', 'Status', 'Enrolled Date', 'Completed Date', 'Progress (%)', 'Time Spent (mins)']);
    
    foreach ($enrolledStudents as $student) {
        $timeMinutes = round($student['total_time_seconds'] / 60, 1);
        fputcsv($output, [
            $student['fname'] . ' ' . $student['lname'],
            $student['email'],
            $student['username'],
            $student['status_text'],
            $student['enrolled_date'],
            $student['completed_date'] ?? 'N/A',
            $student['progress'] . '%',
            $timeMinutes
        ]);
    }
    fclose($output);
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=htmlspecialchars($course['title'])?> - LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/sidebar.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
/* Add toast notification styles */
.toast-notification {
position: fixed;
top: 20px;
right: 20px;
z-index: 9999;
animation: slideIn 0.3s ease;
}

@keyframes slideIn {
from {
transform: translateX(100%);
opacity: 0; 
}
to {
transform: translateX(0);
opacity: 1;
}
}
</style>
</head>
<body>
<!-- Sidebar -->
<div class="lms-sidebar-container">
<?php include __DIR__ . '/../inc/sidebar.php'; ?>
</div>

<!-- Toast notification container -->
<div id="toastContainer" class="toast-notification"></div>

<!-- Main Content -->
<div class="course-content-wrapper">
<!-- Course Header -->
<div class="course-header">
<div class="d-flex justify-content-between align-items-start">
<div>
<h3><?=htmlspecialchars($course['title'])?></h3>
<p><?=nl2br(htmlspecialchars($course['description']))?></p>
</div>

<!-- Export Button for Admin/Proponent -->
<?php if((is_admin() || is_proponent()) && count($enrolledStudents) > 0): ?>
<a href="?id=<?= $courseId ?>&export=csv" class="export-btn">
<i class="fas fa-download me-2"></i>Export CSV
</a>
<?php endif; ?>
</div>
</div>

<!-- Course Info -->
<div class="course-info-card">
<div class="course-instructor">
<div class="instructor-avatar">
<?= substr($course['fname'] ?? 'I', 0, 1) . substr($course['lname'] ?? 'nstructor', 0, 1) ?>
</div>
<div class="instructor-info">
<h5><?= htmlspecialchars($course['fname'] ?? 'Instructor') ?> <?= htmlspecialchars($course['lname'] ?? '') ?></h5>
<p>Course Instructor</p>
</div>
</div>
</div>

<!-- Progress Section for Students -->
<?php if(is_student()): ?>
<div class="progress-section">
<div class="progress-header">
<h5><i class="fas fa-chart-line me-2"></i>Your Progress</h5>
<div class="time-spent">
<i class="fas fa-clock me-1"></i>
Time spent: <span id="timeSpent"><?= intval($enrollment['total_time_seconds'] ?? 0) ?></span> seconds
</div>
</div>

<div class="status-container">
<?php if(($enrollment['status'] ?? '') === 'completed'): ?>
<span class="badge bg-success">
<i class="fas fa-check-circle me-2"></i>Completed
</span>
<?php else: ?>
<span class="badge bg-warning">
<i class="fas fa-spinner me-2"></i>Ongoing
</span>
<?php endif; ?>
</div>

<?php if(($enrollment['status'] ?? '') === 'completed'): ?>
<div class="alert alert-success mt-3">
<i class="fas fa-graduation-cap me-2"></i>
Congratulations! You have successfully completed this course 🎓
<br>
<small>A confirmation email has been sent to your email address.</small>
</div>
<?php endif; ?>

<!-- Complete Button -->
<?php if(($enrollment['status'] ?? '') !== 'completed'): ?>
<button id="completeBtn" class="btn btn-success mt-3" disabled>
<i class="fas fa-check-circle me-2"></i>Mark as Complete
</button>
<small class="text-muted ms-2">
<i class="fas fa-info-circle"></i>
Watch the video completely to enable completion
</small>
<?php endif; ?>
</div>
<?php endif; ?>

<!-- PDF Content -->
<?php if($course['file_pdf']): ?>
<div class="content-card">
<h5><i class="fas fa-file-pdf text-danger"></i> Course PDF Material</h5>

<div class="pdf-viewer">
<iframe
src="<?= BASE_URL ?>/uploads/pdf/<?= htmlspecialchars($course['file_pdf']) ?>"
width="100%"
height="600"
style="border:none; border-radius: 8px;">
</iframe>
</div>

<p class="mt-3">
<a class="btn btn-outline-primary"
href="<?= BASE_URL ?>/uploads/pdf/<?= htmlspecialchars($course['file_pdf']) ?>"
target="_blank">
<i class="fas fa-external-link-alt me-2"></i>Open PDF in new tab
</a>
</p>
</div>
<?php endif; ?>

<!-- Video Content -->
<?php if($course['file_video']): ?>
<div class="content-card">
<h5><i class="fas fa-video text-primary"></i> Course Video</h5>

<div class="video-player">
<video id="courseVideo" width="100%" controls>
<source src="<?= BASE_URL ?>/uploads/video/<?= htmlspecialchars($course['file_video']) ?>" type="video/mp4">
Your browser does not support HTML5 video.
</video>
</div>
</div>
<?php endif; ?>

<!-- Students List for Admin/Proponent -->
<?php if((is_admin() || is_proponent()) && count($enrolledStudents) > 0): ?>
<div class="students-section mt-4">
<div class="d-flex justify-content-between align-items-center mb-3">
<h5><i class="fas fa-users me-2"></i>Enrolled Students</h5>

<!-- Stats Summary -->
<div class="stats-summary">
<span class="badge bg-primary me-2">Total: <?= $stats['total_enrolled'] ?? 0 ?></span>
<span class="badge bg-warning me-2">Ongoing: <?= $stats['ongoing_count'] ?? 0 ?></span>
<span class="badge bg-success">Completed: <?= $stats['completed_count'] ?? 0 ?></span>
</div>
</div>

<!-- Completion Rate -->
<div class="completion-rate mb-3">
<div class="d-flex align-items-center">
<span class="me-2">Completion Rate:</span>
<div class="progress flex-grow-1" style="height: 10px;">
<div class="progress-bar bg-success" style="width: <?= $completionRate ?>%"></div>
</div>
<span class="ms-2 fw-bold"><?= $completionRate ?>%</span>
</div>
</div>

<!-- Students Table -->
<div class="table-responsive">
<table class="table table-hover">
<thead>
<tr>
<th>Student</th>
<th>Email</th>
<th>Status</th>
<th>Progress</th>
<th>Enrolled</th>
<th>Completed</th>
<th>Time Spent</th>
</tr>
</thead>
<tbody>
<?php foreach($enrolledStudents as $student): ?>
<tr>
<td>
<?= htmlspecialchars($student['fname'] . ' ' . $student['lname']) ?>
<br><small class="text-muted">@<?= htmlspecialchars($student['username']) ?></small>
</td>
<td><?= htmlspecialchars($student['email']) ?></td>
<td>
<span class="badge <?= $student['status_color'] ?>">
<?= $student['status_text'] ?>
</span>
</td>
<td>
<div class="d-flex align-items-center">
<div class="progress flex-grow-1 me-2" style="height: 5px; width: 80px;">
<div class="progress-bar" style="width: <?= $student['progress'] ?>%"></div>
</div>
<small><?= $student['progress'] ?>%</small>
</div>
</td>
<td><?= $student['enrolled_date'] ?></td>
<td><?= $student['completed_date'] ?? '—' ?></td>
<td><?= round($student['total_time_seconds'] / 60, 1) ?> mins</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
<?php endif; ?>
</div>

<script>
<?php if(is_student()): ?>
let totalSeconds = parseInt($('#timeSpent').text() || 0);
let videoCompleted = false;
let pdfCompleted = false;
let autoCompleteSeconds = 60; // 60 seconds for PDF reading

// Function to show toast notification
function showToast(message, type = 'success') {
const toast = $(`
<div class="alert alert-${type} alert-dismissible fade show" role="alert">
<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
${message}
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
`);

$('#toastContainer').append(toast);

// Auto dismiss after 5 seconds
setTimeout(() => {
toast.alert('close');
}, 5000);
}

// Auto update time spent
setInterval(function(){
totalSeconds++;
$('#timeSpent').text(totalSeconds);
$.post(window.location.href, {seconds: 1});
}, 1000);

// Video Element
const video = document.getElementById('courseVideo');
const completeBtn = document.getElementById('completeBtn');

if (video) {
// Disable complete button initially
if (completeBtn) completeBtn.disabled = true;

// Enable complete button when video ends
video.addEventListener('ended', function() {
videoCompleted = true;
if (completeBtn) {
completeBtn.disabled = false;
completeBtn.classList.add('btn-pulse');
}
// Check if both conditions are met
checkCompletion();
});

// Optional: Enable after watching 90% of video
video.addEventListener('timeupdate', function() {
if (!videoCompleted && video.duration > 0) {
let progress = (video.currentTime / video.duration) * 100;
if (progress >= 90) { // 90% watched
videoCompleted = true;
if (completeBtn) completeBtn.disabled = false;
checkCompletion();
}
}
});
}

// PDF Reading Timer
<?php if($course['file_pdf']): ?>
setInterval(function() {
if (!pdfCompleted && completeBtn) {
pdfReadSeconds++;
if (pdfReadSeconds >= autoCompleteSeconds) {
pdfCompleted = true;
checkCompletion();
}
}
}, 1000);
<?php endif; ?>

// Check if both conditions are met
function checkCompletion() {
let canComplete = false;

if (video) {
canComplete = videoCompleted;
} else {
// If no video, just check PDF
canComplete = pdfCompleted;
}

if (canComplete && completeBtn) {
completeBtn.disabled = false;
}
}

// Video ended
$('#courseVideo').on('ended', function() {
completeCourse();
});

// Complete button click
$('#completeBtn').on('click', function(){
completeCourse();
});

function completeCourse() {
$.post(window.location.href, { mark_completed: 1 }, function(response) {
if (response.success) {
let message = '🎉 Congratulations! Course marked as completed!';
if (response.email_sent) {
message += ' A confirmation email has been sent to your email address.';
} else {
message += ' Note: Confirmation email could not be sent.';
}

showToast(message, 'success');

// Reload after 2 seconds to show updated status
setTimeout(() => {
location.reload();
}, 2000);
} else {
showToast('Error: ' + response.message, 'danger');
}
});
}
<?php endif; ?>

// Animation on load
document.addEventListener('DOMContentLoaded', function() {
const cards = document.querySelectorAll('.content-card, .progress-section, .course-info-card, .students-section');
cards.forEach((card, index) => {
card.style.opacity = '0';
card.style.transform = 'translateY(20px)';
card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

setTimeout(() => {
card.style.opacity = '1';
card.style.transform = 'translateY(0)';
}, index * 100);
});
});
</script>
</body>
</html>
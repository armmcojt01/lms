
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: white !important;
            cursor: not-allowed !important;
            opacity: 0.65;
            pointer-events: none;
        }
        .btn-enrolled {
            background-color: #970e6e !important;
            border-color: #970e6e !important;
            color: white !important;
            cursor: not-allowed !important;
            opacity: 0.65;
            pointer-events: none;
        }
        .btn-locked {
            background-color: #7e0026 !important;
            border-color: #7e0026 !important;
            color: #212529 !important;
            cursor: not-allowed !important;
            opacity: 0.65;
            pointer-events: none;
        }
        .expired-badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            background-color: #dc3545;
            margin-left: 10px;
        }
        .active-course-alert {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .active-course-link {
            color: #533f03;
            font-weight: bold;
            text-decoration: underline;
        }
</style>
</head>
<body>
    <!-- Sidebar -->
<div class="lms-sidebar-container">
<?php include __DIR__ . '/../inc/sidebar.php'; ?>
</div>

    <!-- Main Content -->
<div class="course-content-wrapper">
        <!-- Display session messages -->
<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
<?= $_SESSION['success'] ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
<?= $_SESSION['error'] ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

        <!-- pls gumana ka warning if user is alredy enroll sa ibang kors-->
<?php if ($hasActiveEnrollment && $enrollStatus !== 'ongoing' && !$isExpired): ?>
<div class="active-course-alert">
<i class="fas fa-info-circle"></i> 
<strong>You have an active enrollment:</strong> 
You are currently enrolled in <a href="course.php?id=<?= $activeCourseId ?>" class="active-course-link"><?= htmlspecialchars($activeCourseTitle) ?></a>. 
You can only be enrolled in one course at a time. Please complete or drop your current course before enrolling in a new one.
</div>
<?php endif; ?>

        <!-- Course Header -->
<div class="course-header">
<h3>
<?=htmlspecialchars($course['title'])?>
<?php if ($isExpired): ?>
<span class="expired-badge">EXPIRED</span>
<?php endif; ?>
</h3>
<p><?=nl2br(htmlspecialchars($course['description']))?></p>
</div>

        <!-- Course Info -->
<div class="course-info-card">
<div class="course-instructor">
<div class="instructor-avatar">
<?= substr($course['fname'] ?? 'I', 0, 1) . substr($course['lname'] ?? 'Instructor', 0, 1) ?>
</div>
<div class="instructor-info">
<h5><?= htmlspecialchars($course['fname'] ?? 'Instructor') ?> <?= htmlspecialchars($course['lname'] ?? '') ?></h5>
<p>Course Instructor</p>
</div>
</div>
<div class="modern-course-info-meta">
<div>
<div class="meta-item">
<i class="fas fa-calendar-alt"></i>
<span>Created on: <?= date('F j, Y', strtotime($course['created_at'] ?? '')) ?></span>
</div>
<div class="meta-item">
<i class="fas fa-clock"></i>
<span>Expires on: <?= $course['expires_at'] ? date('F j, Y', strtotime($course['expires_at'])) : 'No expiration' ?></span>
 <?php if ($isExpired): ?>
 <span class="badge bg-danger ms-2">Expired</span>
<?php endif; ?>
</div>
</div> 
</div>

<div class="modern-card-actions mt-3">
<?php if ($isExpired): ?>
                    <!-- EXPIRED - Gray Button -->
<button class="btn btn-expired" disabled>
<i class="fas fa-hourglass-end"></i> Course Expired
</button>
<small class="text-muted d-block mt-2">
<i class="fas fa-info-circle"></i> This course expired on <?= date('F j, Y', strtotime($course['expires_at'])) ?>
</small>
                    
<?php elseif ($enrollStatus === 'ongoing'): ?>
                    <!-- ALREADY ENROLLED - Green Button -->
<button class="btn btn-enrolled" disabled>
<i class="fas fa-check-circle"></i> Already Enrolled
</button>
<small class="text-muted d-block mt-2">
<i class="fas fa-info-circle"></i> You are already enrolled in this course. 
<class="text-primary">Continue Learning</a>
 </small>
                    
<?php elseif ($hasActiveEnrollment): ?>
<!-- LOCKED - Yellow Button (has other active enrollment) -->
<button class="btn btn-locked" disabled>
<i class="fas fa-lock"></i> Enrollment Locked
</button>
<small class="text-muted d-block mt-2">
<i class="fas fa-info-circle"></i> You are currently enrolled in 
  <a href="course.php?id=<?= $activeCourseId ?>"><?= htmlspecialchars($activeCourseTitle) ?></a>. 
 Complete that course first.
 </small>
                    
<?php else: ?>
                    <!-- ENROLL NOW POST form -->
<form method="POST" style="display: inline;">
<button type="submit" name="enroll" class="btn btn-primary">
<i class="fas fa-sign-in-alt"></i> Enroll Now
</button>
</form>
<?php if ($course['expires_at']): ?>
<small class="text-muted d-block mt-2">
<i class="fas fa-info-circle"></i> This course expires on <?= date('F j, Y', strtotime($course['expires_at'])) ?>
</small>
<?php endif; ?>
<?php endif; ?>
</div>
</div>

<!-- test Preview Section -->
<div class="mt-4">
<h4>Course Preview</h4>
<div class="modern-course-info-content p-3 border rounded">
<?= $course['summary'] ?? '<p class="text-muted">No preview available.</p>' ?>
</div>
</div>
</div>

<script>
$(document).ready(function() {
const isExpired = <?= $isExpired ? 'true' : 'false' ?>;
const enrollStatus = "<?= $enrollStatus ?>";
const hasActiveEnrollment = <?= $hasActiveEnrollment ? 'true' : 'false' ?>;
        
if (isExpired || hasActiveEnrollment) {
$('.btn-primary').addClass('disabled').attr('disabled', true);
}
        
        // Confirmation for enrollment
 $('button[name="enroll"]').click(function(e) {
if (!confirm('Are you sure you want to enroll in this course? You can only be enrolled in one course at a time.')) {
e.preventDefault();
return false;
}
});
});
</script>
</body>
</html>
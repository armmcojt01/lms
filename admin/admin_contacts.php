<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

// Only admin can access
if (!is_admin() && !is_superadmin()) {
    echo 'Access denied';
    exit;
}

// Mark as read
if (isset($_GET['mark_read']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin_contacts.php');
    exit;
}

// Delete message
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin_contacts.php');
    exit;
}

// Get filter
$filter = $_GET['filter'] ?? 'all';

if ($filter === 'unread') {
    $messages = $pdo->query("SELECT * FROM contact_messages WHERE is_read = 0 ORDER BY created_at DESC")->fetchAll();
} elseif ($filter === 'read') {
    $messages = $pdo->query("SELECT * FROM contact_messages WHERE is_read = 1 ORDER BY created_at DESC")->fetchAll();
} else {
    $messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
}

$unread_count = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/sidebar.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .message-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }
        .message-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .message-card.unread {
            border-left: 4px solid #007bff;
            background: #f0f7ff;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .message-subject {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        .message-meta {
            color: #666;
            font-size: 13px;
        }
        .message-meta i {
            width: 16px;
            margin-right: 5px;
            color: #007bff;
        }
        .message-content {
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            white-space: pre-wrap;
        }
        .badge-unread {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
        }
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .filter-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="lms-sidebar-container">
        <?php include __DIR__ . '/../inc/sidebar.php'; ?>
    </div>

    <div class="main-content-wrapper">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>
                    <i class="fas fa-envelope me-2 text-primary"></i>
                    Contact Messages
                </h3>
                <span class="badge bg-primary"><?= $unread_count ?> Unread</span>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <h5>Total Messages</h5>
                        <h2><?= count($messages) ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h5>Unread</h5>
                        <h2 style="color: #007bff;"><?= $unread_count ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h5>Read</h5>
                        <h2 style="color: #28a745;"><?= count($messages) - $unread_count ?></h2>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="btn-group">
                    <a href="?filter=all" class="btn btn-<?= $filter == 'all' ? 'primary' : 'outline-secondary' ?>">All</a>
                    <a href="?filter=unread" class="btn btn-<?= $filter == 'unread' ? 'primary' : 'outline-secondary' ?>">Unread</a>
                    <a href="?filter=read" class="btn btn-<?= $filter == 'read' ? 'primary' : 'outline-secondary' ?>">Read</a>
                </div>
            </div>

            <!-- Messages -->
            <?php if (empty($messages)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <h5>No messages found</h5>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message-card <?= $msg['is_read'] ? '' : 'unread' ?>" id="msg-<?= $msg['id'] ?>">
                        <div class="message-header">
                            <div class="message-subject">
                                <?= htmlspecialchars($msg['subject']) ?>
                                <?php if (!$msg['is_read']): ?>
                                    <span class="badge-unread ms-2">NEW</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="?mark_read=1&id=<?= $msg['id'] ?>" class="btn btn-sm btn-outline-primary" title="Mark as read">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="?delete=1&id=<?= $msg['id'] ?>" 
                                   onclick="return confirm('Delete this message?')" 
                                   class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="message-meta">
                            <div class="row">
                                <div class="col-md-4">
                                    <i class="fas fa-user"></i> <?= htmlspecialchars($msg['name']) ?>
                                </div>
                                <div class="col-md-4">
                                    <i class="fas fa-envelope"></i> 
                                    <a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a>
                                </div>
                                <div class="col-md-4">
                                    <i class="fas fa-clock"></i> <?= date('M d, Y h:i A', strtotime($msg['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="message-content">
                            <?= nl2br(htmlspecialchars($msg['message'])) ?>
                        </div>
                        
                        <div class="mt-2 text-end">
                            <a href="mailto:<?= htmlspecialchars($msg['email']) ?>?subject=Re: <?= urlencode($msg['subject']) ?>" 
                               class="btn btn-sm btn-success">
                                <i class="fas fa-reply me-1"></i>Reply via Email
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
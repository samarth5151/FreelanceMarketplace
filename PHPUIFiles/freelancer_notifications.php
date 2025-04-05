<?php
session_start();
require_once 'Connection/db_connection.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$freelancer_id = $_SESSION["freelancer_id"] ?? 0;

// Fetch all notifications
$notifications = [];
$stmt = $db->prepare("SELECT fn.*, j.job_title, u.username as client_name 
                     FROM freelancer_notifications fn
                     JOIN jobs j ON fn.job_id = j.id
                     JOIN users u ON fn.client_id = u.id
                     WHERE fn.freelancer_id = :freelancer_id
                     ORDER BY fn.created_at DESC");
$stmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
$result = $stmt->execute();

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $notifications[] = $row;
}

// Mark all as read
$db->exec("UPDATE freelancer_notifications SET is_read = 1 WHERE freelancer_id = $freelancer_id AND is_read = 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-time {
            font-size: 12px;
            color: #6c757d;
        }
        .unread {
            background-color: #f0f8ff;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Notifications</h2>
            <a href="freelancer_dashboard.php" class="btn btn-outline-primary">Back to Dashboard</a>
        </div>
        
        <?php if (empty($notifications)): ?>
            <div class="alert alert-info">You have no notifications.</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($notifications as $notification): ?>
                    <a href="job_details_freelancer.php?id=<?= $notification['job_id'] ?>" 
                       class="list-group-item list-group-item-action notification-item">
                        <div class="d-flex justify-content-between">
                            <h5><?= htmlspecialchars($notification['client_name']) ?></h5>
                            <small class="notification-time">
                                <?= (new DateTime($notification['created_at']))->format('M j, Y g:i a') ?>
                            </small>
                        </div>
                        <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                        <small class="text-muted">Job: <?= htmlspecialchars($notification['job_title']) ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
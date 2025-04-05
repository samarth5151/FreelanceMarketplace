<?php
session_start();

$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// Get user ID
$userQuery = $db->prepare("SELECT users_id FROM users WHERE username = :username");
$userQuery->bindValue(':username', $username, SQLITE3_TEXT);
$userResult = $userQuery->execute();
$user = $userResult->fetchArray(SQLITE3_ASSOC);
$user_id = $user['users_id'];

// Mark all notifications as read when visiting this page
$markAllReadQuery = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id");
$markAllReadQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$markAllReadQuery->execute();

// Fetch all notifications
$notificationsQuery = $db->prepare("
    SELECT n.*, j.job_title, f.name as freelancer_name 
    FROM notifications n
    JOIN jobs j ON n.job_id = j.id
    JOIN freelancers f ON n.freelancer_id = f.id
    WHERE n.user_id = :user_id
    ORDER BY n.created_at DESC
");
$notificationsQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$notificationsResult = $notificationsQuery->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .notification-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .notification-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .notification-title {
            font-weight: 600;
            color: #0d6efd;
        }
        .notification-time {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .notification-message {
            margin-bottom: 10px;
        }
        .notification-footer {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .no-notifications {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Notifications</h2>
        
        <?php if ($notification = $notificationsResult->fetchArray(SQLITE3_ASSOC)): ?>
            <div class="notification-list">
                <?php do { ?>
                    <div class="notification-card">
                        <div class="notification-header">
                            <span class="notification-title"><?= htmlspecialchars($notification['job_title']) ?></span>
                            <span class="notification-time"><?= date('M d, h:i A', strtotime($notification['created_at'])) ?></span>
                        </div>
                        <div class="notification-message">
                            <?= htmlspecialchars($notification['message']) ?>
                        </div>
                        <div class="notification-footer">
                            From: <?= htmlspecialchars($notification['freelancer_name']) ?>
                        </div>
                        <div class="mt-2">
                            <a href="job_details.php?id=<?= $notification['job_id'] ?>" class="btn btn-sm btn-primary">View Job</a>
                        </div>
                    </div>
                <?php } while ($notification = $notificationsResult->fetchArray(SQLITE3_ASSOC)); ?>
            </div>
        <?php else: ?>
            <div class="no-notifications">
                <i class="fas fa-bell-slash fa-3x mb-3" style="color: #6c757d;"></i>
                <h4>No notifications yet</h4>
                <p>You'll see notifications here when freelancers submit work or send messages.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
session_start();
require_once 'Connection/db_connection.php';

$freelancer_id = $_GET['freelancer_id'] ?? 0;

// Fetch unread notifications count
$count = 0;
$stmt = $db->prepare("SELECT COUNT(*) as count FROM freelancer_notifications WHERE freelancer_id = :freelancer_id AND is_read = 0");
$stmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);
$count = $row['count'];

// If there are new notifications, fetch them for the dropdown
$html = '';
if ($count > 0) {
    $stmt = $db->prepare("SELECT fn.*, j.job_title, u.username as client_name 
                         FROM freelancer_notifications fn
                         JOIN jobs j ON fn.job_id = j.id
                         JOIN users u ON fn.client_id = u.id
                         WHERE fn.freelancer_id = :freelancer_id
                         ORDER BY fn.created_at DESC LIMIT 5");
    $stmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $html = '<li><h6 class="dropdown-header">Notifications</h6></li>';
    while ($notification = $result->fetchArray(SQLITE3_ASSOC)) {
        $date = new DateTime($notification['created_at']);
        $html .= '
        <li>
            <a class="dropdown-item notification-item unread" href="job_details_freelancer.php?id='.$notification['job_id'].'">
                <div class="d-flex justify-content-between">
                    <strong>'.htmlspecialchars($notification['client_name']).'</strong>
                    <small class="notification-time">'.$date->format('M j, Y g:i a').'</small>
                </div>
                <div class="mt-1">'.htmlspecialchars($notification['message']).'</div>
                <div class="text-muted small mt-1">Job: '.htmlspecialchars($notification['job_title']).'</div>
            </a>
        </li>';
    }
    $html .= '<li><div class="view-all-notifications"><a href="freelancer_notifications.php">View All Notifications</a></div></li>';
    
    // Mark as read
    $db->exec("UPDATE freelancer_notifications SET is_read = 1 WHERE freelancer_id = $freelancer_id AND is_read = 0");
}

header('Content-Type: application/json');
echo json_encode([
    'count' => $count,
    'html' => $html
]);
?>
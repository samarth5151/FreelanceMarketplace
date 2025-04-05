<?php
session_start();
header('Content-Type: application/json');

$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    
    // Get user ID
    $userQuery = $db->prepare("SELECT users_id FROM users WHERE username = :username");
    $userQuery->bindValue(':username', $username, SQLITE3_TEXT);
    $userResult = $userQuery->execute();
    $user = $userResult->fetchArray(SQLITE3_ASSOC);
    $user_id = $user['users_id'];

    // Count unread notifications
    $unreadCountQuery = $db->prepare("SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = :user_id AND is_read = 0");
    $unreadCountQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $unreadCountResult = $unreadCountQuery->execute();
    $unreadCount = $unreadCountResult->fetchArray(SQLITE3_ASSOC)['unread_count'] ?? 0;

    echo json_encode(['unread_count' => $unreadCount]);
} else {
    echo json_encode(['unread_count' => 0]);
}
?>
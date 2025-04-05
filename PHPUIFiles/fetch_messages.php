<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

// Fetch user ID from session
$users_username = $_SESSION['username'];
$usersQuery = $db->prepare("SELECT * FROM users WHERE username = :username");
$usersQuery->bindValue(':username', $users_username, SQLITE3_TEXT);
$usersResult = $usersQuery->execute();
$user = $usersResult->fetchArray(SQLITE3_ASSOC);
$user_id = $user['users_id'];

// Fetch freelancer ID from GET parameter
$freelancer_id = $_GET['freelancer_id'];

// Fetch messages between the user and freelancer
$messagesQuery = $db->prepare("
    SELECT * FROM messages 
    WHERE (sender_id = :user_id AND receiver_id = :freelancer_id)
    OR (sender_id = :freelancer_id AND receiver_id = :user_id)
    ORDER BY timestamp ASC
");
$messagesQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$messagesQuery->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
$messagesResult = $messagesQuery->execute();

while ($message = $messagesResult->fetchArray(SQLITE3_ASSOC)) {
    $messageClass = ($message['sender_id'] == $user_id) ? 'user-message' : 'freelancer-message';
    echo "<div class='message $messageClass'>
            <p>{$message['message']}</p>
            <small>" . date('h:i A', strtotime($message['timestamp'])) . "</small>
          </div>";
}
?>
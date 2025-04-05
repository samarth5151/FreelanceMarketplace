<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

// Fetch freelancer details from session
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$freelancer_username = $_SESSION["username"];
$freelancerQuery = $db->prepare("SELECT * FROM freelancers WHERE username = :username");
$freelancerQuery->bindValue(':username', $freelancer_username, SQLITE3_TEXT);
$freelancerResult = $freelancerQuery->execute();
$freelancer = $freelancerResult->fetchArray(SQLITE3_ASSOC);
$freelancer_id = $freelancer['id'];

// Fetch user details if a user is selected
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {
    exit();
}

// Fetch messages
$messagesQuery = $db->prepare("
    SELECT * FROM messages 
    WHERE (sender_id = :freelancer_id AND receiver_id = :user_id)
    OR (sender_id = :user_id AND receiver_id = :freelancer_id)
    ORDER BY timestamp ASC
");
$messagesQuery->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
$messagesQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$messagesResult = $messagesQuery->execute();

while ($message = $messagesResult->fetchArray(SQLITE3_ASSOC)) {
    // Determine if the message is sent by the freelancer or received from the user
    $messageClass = ($message['sender_id'] == $freelancer_id) ? 'user-message' : 'freelancer-message';

    echo "<div class='message $messageClass'>
            <p>{$message['message']}</p>
            <small>" . date('h:i A', strtotime($message['timestamp'])) . "</small>
          </div>";
}
?>
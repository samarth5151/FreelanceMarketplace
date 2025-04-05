<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

// Get the sender_id, receiver_id, and message from the POST request
$sender_id = $_POST['sender_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

// Validate input
if (empty($sender_id) || empty($receiver_id) || empty($message)) {
    echo "Error: Missing required fields.";
    exit();
}

// Set the timezone to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');

// Get current time in Kolkata timezone
$current_time = date('Y-m-d H:i:s');

// Insert the message into the database with Kolkata time
$query = $db->prepare("
    INSERT INTO messages (sender_id, receiver_id, message, timestamp)
    VALUES (:sender_id, :receiver_id, :message, :timestamp)
");
$query->bindValue(':sender_id', $sender_id, SQLITE3_INTEGER);
$query->bindValue(':receiver_id', $receiver_id, SQLITE3_INTEGER);
$query->bindValue(':message', $message, SQLITE3_TEXT);
$query->bindValue(':timestamp', $current_time, SQLITE3_TEXT);

if ($query->execute()) {
    echo "Message sent successfully.";
} else {
    echo "Error: Failed to send message.";
}
?>
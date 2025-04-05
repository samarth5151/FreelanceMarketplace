<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

$sender_id = $_POST['sender_id'];
$receiver_id = $_POST['receiver_id'];
$file = $_FILES['file'];

$target_dir = "uploads/attachments/";
$target_file = $target_dir . basename($file['name']);
move_uploaded_file($file['tmp_name'], $target_file);

$stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message, attachment_path) VALUES (:sender_id, :receiver_id, :message, :attachment_path)");
$stmt->bindValue(':sender_id', $sender_id, SQLITE3_INTEGER);
$stmt->bindValue(':receiver_id', $receiver_id, SQLITE3_INTEGER);
$stmt->bindValue(':message', 'Attachment', SQLITE3_TEXT);
$stmt->bindValue(':attachment_path', $target_file, SQLITE3_TEXT);
$stmt->execute();

echo "Attachment sent successfully!";
?>
<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

// Get POST data
$job_id = $_POST['job_id'] ?? 0;
$freelancer_id = $_POST['freelancer_id'] ?? 0;
$client_id = $_POST['client_id'] ?? 0;
$file_name = $_POST['file_name'] ?? '';
$rating = $_POST['rating'] ?? 0;

// Insert into Platform_Rating table
$stmt = $db->prepare("INSERT INTO Platform_Rating (job_id, freelancer_id, client_id, file_name, rating) 
                      VALUES (:job_id, :freelancer_id, :client_id, :file_name, :rating)");
$stmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
$stmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
$stmt->bindValue(':client_id', $client_id, SQLITE3_INTEGER);
$stmt->bindValue(':file_name', $file_name, SQLITE3_TEXT);
$stmt->bindValue(':rating', $rating, SQLITE3_FLOAT);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to save rating']);
}
?>
<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

$freelancerId = $_GET['id'] ?? 0;

$query = $db->prepare("SELECT * FROM freelancers WHERE id = :id");
$query->bindValue(':id', $freelancerId, SQLITE3_INTEGER);
$result = $query->execute();
$freelancer = $result->fetchArray(SQLITE3_ASSOC);

header('Content-Type: application/json');
echo json_encode($freelancer);


?>
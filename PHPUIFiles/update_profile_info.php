
<?php
session_start();
if (!isset($_SESSION["username"])) {
    die("Unauthorized access!");
}

$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');
if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}

// Sanitize and validate input
$skills = SQLite3::escapeString($_POST['skills']);
$tools = SQLite3::escapeString($_POST['tools']);
$languages = SQLite3::escapeString($_POST['languages']);
$availability = SQLite3::escapeString($_POST['availability']);
$degree = SQLite3::escapeString($_POST['degree']);
$institute = SQLite3::escapeString($_POST['institute']);
$graduation_year = intval($_POST['graduation_year']);
$username = $_SESSION["username"];

// Update query
$query = "UPDATE freelancers SET 
          skills = '$skills',
          tools = '$tools',
          languages = '$languages',
          availability = '$availability',
          degree = '$degree',
          institute = '$institute',
          graduation_year = '$graduation_year'
          WHERE username = '$username'";

if ($db->exec($query)) {
    echo "Profile updated successfully!";
} else {
    echo "Error updating profile: " . $db->lastErrorMsg();
}

$db->close();
?>
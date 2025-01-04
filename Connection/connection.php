<?php

// session_start();

// if(isset($_SESSION["Username"])){
// 	$username=$_SESSION["Username"];
// }
// else{
// 	$username="";
//     header("location: login.php");
// }

$db = new SQLite3('C:\xampp\htdocs\MegaProject\Connection\Freelance_db.db');

if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}

// $username ="samarth50";
// Debugging: List all tables
// $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table';");
// while ($row = $tables->fetchArray(SQLITE3_ASSOC)) {
//     echo "Table: " . $row['name'] . "<br>";
// }

// Fetch user details
// $sql = "SELECT * FROM users WHERE username = :username";
// $stmt = $db->prepare($sql);
// if (!$stmt) {
//     die("Error preparing the statement: " . $db->lastErrorMsg());
// }
// $stmt->bindValue(':username', $username, SQLITE3_TEXT);
// $result = $stmt->execute();

// if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
//    echo $name = $row["users_name"];
//     echo $email = $row["users_email"];
//     echo $contactNo = $row["users_contact"];
//     echo $gender = $row["users_gender"];
//     echo $birthdate = $row["users_dob"];
    
// } else {
//     echo "No results found.<br>";
// }

// $db->close();
?>

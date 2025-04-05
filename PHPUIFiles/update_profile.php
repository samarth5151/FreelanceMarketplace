<?php
session_start();

$db = new SQLite3('C:/xampp/htdocs/FreelanceMarketplace/Connection/Freelance_db.db');

if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    die("Username not set.");
}

// Get form data
$name = $_POST['name'];
$tagline = $_POST['tagline'];
$email = $_POST['email'];
$contact = $_POST['contact'];

// Retrieve the existing profile picture path from the database
$sql = "SELECT profile_picture FROM freelancers WHERE username = :username";
$stmt = $db->prepare($sql);
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);

if (!$row) {
    die("User not found.");
}

$existingProfilePicture = $row['profile_picture'];

// Handle file upload
if ($_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'C:/xampp/htdocs/FreelanceMarketplace/profile_uploads/';
    
    // Ensure the directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
    }

    // Delete the existing profile picture if it exists
    if ($existingProfilePicture && file_exists($uploadDir . basename($existingProfilePicture))) {
        unlink($uploadDir . basename($existingProfilePicture));
    }

    // Generate a unique filename to avoid conflicts
    $fileName =basename($_FILES['profilePicture']['name']);
    $uploadFile = $uploadDir . $fileName;

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $uploadFile)) {
        $profilePicture = 'profile_uploads/' . $fileName; // Relative path for database
    } else {
        die("Error uploading file. Please check directory permissions.");
    }
} else {
    // If no new file is uploaded, keep the existing profile picture
    $profilePicture = $existingProfilePicture;
}

// Update the database
$sql = "UPDATE freelancers SET name = :name, tagline = :tagline, email = :email, contact = :contact, profile_picture = :profilePicture WHERE username = :username";
$stmt = $db->prepare($sql);
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':tagline', $tagline, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':contact', $contact, SQLITE3_TEXT);
$stmt->bindValue(':profilePicture', $profilePicture, SQLITE3_TEXT);
$stmt->bindValue(':username', $username, SQLITE3_TEXT);

if ($stmt->execute()) {
    echo "Profile updated successfully!";
} else {
    echo "Error updating profile: " . $db->lastErrorMsg();
}

$db->close();
?>
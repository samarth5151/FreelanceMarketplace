<?php

// Start session to manage user login state
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Specify the correct database file path
$db = new SQLite3('C:/xampp/htdocs/MegaProject/Connection/Freelance_db.db');

// Check if the connection is successful
if (!$db) {
    exit("Error: Could not connect to the database.");
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash password
    $email = htmlspecialchars(trim($_POST['email']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $dob = htmlspecialchars(trim($_POST['dob']));

    // Initialize profile image path (empty string if no file uploaded)
    $profileImgPath = '';

    // Handle file upload for profile image
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/profile_uploads/'; // Absolute path

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                exit("Error: Unable to create upload directory.");
            }
        }

        // Generate a unique name for the file
        $safeFileName = uniqid() . '_' . basename($_FILES['profile']['name']);
        $profileImgPath = $uploadDir . $safeFileName;

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['profile']['tmp_name'], $profileImgPath)) {
            exit("Error: Failed to upload file.");
        }
    }

    // Convert profile image path to relative for database storage
    $relativeProfileImgPath = str_replace(__DIR__, '', $profileImgPath);

    // Prepare SQL statement to insert data into the table
    $stmt = $db->prepare('INSERT INTO users (users_name, username, users_password, users_email, users_contact, users_gender, users_dob, users_profile_img) 
                          VALUES (:name, :username, :password, :email, :contact, :gender, :dob, :profile_img)');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':contact', $contact, SQLITE3_TEXT);
    $stmt->bindValue(':gender', $gender, SQLITE3_TEXT);
    $stmt->bindValue(':dob', $dob, SQLITE3_TEXT);
    $stmt->bindValue(':profile_img', $relativeProfileImgPath, SQLITE3_TEXT);

    // Execute the statement and handle errors
    try {
        $stmt->execute();

        // Store username in session
        $_SESSION['username'] = $username;

        // Debugging: Check if session is set
        if (!isset($_SESSION['username'])) {
            exit("Error: Session not set.");
        }else{
            echo "Session set successfully.";
        }
        echo $username;

        // Redirect to user dashboard
        header("Location: user_dashboard.php");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}

// Close database connection
$db->close();

?>
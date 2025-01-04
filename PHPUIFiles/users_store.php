<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session to manage user login state
session_start();

// Specify the correct database file path
$db = new SQLite3('C:\xampp\htdocs\MegaProject\Connection\Freelance_db.db');

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

    // Handle file upload
    $profileImgPath = '';
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'profile_uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $safeFileName = uniqid() . '_' . basename($_FILES['profile']['name']);
        $profileImgPath = $uploadDir . $safeFileName;
        if (!move_uploaded_file($_FILES['profile']['tmp_name'], $profileImgPath)) {
            $profileImgPath = '';
        }
    }

    // Prepare SQL statement to insert data into the table
    $stmt = $db->prepare('INSERT INTO users (users_name,username, users_password, users_email, users_contact, users_gender, users_dob, users_profile_img) 
                          VALUES (:name, :username, :password, :email, :contact, :gender, :dob, :profile_img)');
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':contact', $contact);
    $stmt->bindValue(':gender', $gender);
    $stmt->bindValue(':dob', $dob);
    $stmt->bindValue(':profile_img', $profileImgPath);

    // Execute the statement
    try {
        $stmt->execute();

        // Store username in session
        $_SESSION['username'] = $username;

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

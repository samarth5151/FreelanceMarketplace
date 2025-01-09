<?php
// Database connection
$db = new SQLite3('C:\\xampp\\htdocs\\MegaProject\\Connection\\Freelance_db.db');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the freelancer's data from the form
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $skills = $_POST['skills'];
    $tools = $_POST['tools'];
    $tagline = $_POST['tagline'];
    $aboutMe = $_POST['aboutMe'];
    $experience = $_POST['experience'];
    $languages = $_POST['languages'];
    $availability = $_POST['availability'];
    $degree = $_POST['degree'];
    $institute = $_POST['institute'];
    $graduationYear = $_POST['graduationYear'];
    
    // Handling file upload for profile picture and resume
    $profilePicture = null;
    $resume = null;

    // Handle Profile Picture upload
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
        $profilePicture = 'uploads/' . $_FILES['profile']['name'];
        move_uploaded_file($_FILES['profile']['tmp_name'], $profilePicture);
    }

    // Handle Resume upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $resume = 'uploads/' . $_FILES['resume']['name'];
        move_uploaded_file($_FILES['resume']['tmp_name'], $resume);
    }

    // Insert freelancer data into the SQLite database
    $stmt = $db->prepare('
        INSERT INTO freelancers (name, username, password, email, contact, gender, dob, skills, tools, tagline, about_me, experience, languages, availability, degree, institute, graduation_year, profile_picture, resume)
        VALUES (:name, :username, :password, :email, :contact, :gender, :dob, :skills, :tools, :tagline, :about_me, :experience, :languages, :availability, :degree, :institute, :graduation_year, :profile_picture, :resume)
    ');

    // Binding parameters
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':contact', $contact, SQLITE3_TEXT);
    $stmt->bindValue(':gender', $gender, SQLITE3_TEXT);
    $stmt->bindValue(':dob', $dob, SQLITE3_TEXT);
    $stmt->bindValue(':skills', $skills, SQLITE3_TEXT);
    $stmt->bindValue(':tools', $tools, SQLITE3_TEXT);
    $stmt->bindValue(':tagline', $tagline, SQLITE3_TEXT);
    $stmt->bindValue(':about_me', $aboutMe, SQLITE3_TEXT);
    $stmt->bindValue(':experience', $experience, SQLITE3_INTEGER);
    $stmt->bindValue(':languages', $languages, SQLITE3_TEXT);
    $stmt->bindValue(':availability', $availability, SQLITE3_TEXT);
    $stmt->bindValue(':degree', $degree, SQLITE3_TEXT);
    $stmt->bindValue(':institute', $institute, SQLITE3_TEXT);
    $stmt->bindValue(':graduation_year', $graduationYear, SQLITE3_INTEGER);
    $stmt->bindValue(':profile_picture', $profilePicture, SQLITE3_TEXT);
    $stmt->bindValue(':resume', $resume, SQLITE3_TEXT);

    // Execute the query
    $result = $stmt->execute();

    if ($result) {
        echo "Freelancer details have been stored successfully!";
    } else {
        echo "Error storing freelancer details!";
    }
}
?>

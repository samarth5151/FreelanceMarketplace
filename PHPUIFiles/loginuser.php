<?php
session_start(); // Start the session

// Database connection
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize error array
    $errors = [];
    
    // Validate and sanitize inputs
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $usertype = $_POST['usertype'] ?? '';
    
    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors['username'] = "Username must be at least 3 characters";
    }
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    }
    
    // Validate user type
    if (empty($usertype) || !in_array($usertype, ['freelancer', 'employer'])) {
        $errors['usertype'] = "Please select a valid user type";
    }
    
    // If there are validation errors, show them
    if (!empty($errors)) {
        // Store errors in session and redirect back
        $_SESSION['login_errors'] = $errors;
        $_SESSION['old_input'] = [
            'username' => $username,
            'usertype' => $usertype
        ];
        header('Location: login.php');
        exit();
    }
    
    // Proceed with authentication if no validation errors
    if ($usertype === 'freelancer') {
        $query = "SELECT * FROM freelancers WHERE username = :username";
    } elseif ($usertype === 'employer') {
        $query = "SELECT * FROM users WHERE username = :username";
    }
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($user) {
        $hashedPassword = ($usertype === 'freelancer') ? $user['password'] : $user['users_password'];
        
        if (password_verify($password, $hashedPassword)) {
            // Password is correct
            $_SESSION['username'] = $user['username'];
            $_SESSION['usertype'] = $usertype;
            
            if ($usertype === 'freelancer') {
                header('Location: freelancer_dashboard.php');
            } elseif ($usertype === 'employer') {
                header('Location: user_dashboard.php');
            }
            exit();
        } else {
            $errors['auth'] = "Invalid username or password";
        }
    } else {
        $errors['auth'] = "Invalid username or password";
    }
    
    // If authentication fails
    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['old_input'] = [
            'username' => $username,
            'usertype' => $usertype
        ];
        header('Location: login.php');
        exit();
    }
}
?>
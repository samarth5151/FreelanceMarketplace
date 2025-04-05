<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    die("You must be logged in to post a job.");
}

$username = $_SESSION["username"];

// Connect to the SQLite database
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}

// Create the 'jobs' table if it doesn't exist
$createTableQuery = "CREATE TABLE IF NOT EXISTS jobs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    job_title TEXT NOT NULL,
    job_category TEXT NOT NULL,
    job_description TEXT NOT NULL,
    primary_skill TEXT NOT NULL,
    additional_skills TEXT,
    experience_level TEXT NOT NULL,
    budget REAL NOT NULL,
    deadline DATE NOT NULL,
    attachments TEXT,
    additional_questions TEXT,
    status TEXT DEFAULT 'Open', -- New column: Status of the job (e.g., Open, In Progress, Closed)
    NoOfbidsReceived INTEGER DEFAULT 0, -- New column: Number of bids received
    posted_date DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if (!$db->exec($createTableQuery)) {
    die("Failed to create table: " . $db->lastErrorMsg());
}

// Check if the new columns exist, and if not, add them
$columnsToAdd = [
    "status" => "TEXT DEFAULT 'Open'",
    "NoOfbidsReceived" => "INTEGER DEFAULT 0"
];

foreach ($columnsToAdd as $columnName => $columnDefinition) {
    $checkColumnQuery = "PRAGMA table_info(jobs)";
    $result = $db->query($checkColumnQuery);
    $columnExists = false;

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        if ($row["name"] == $columnName) {
            $columnExists = true;
            break;
        }
    }

    if (!$columnExists) {
        $alterTableQuery = "ALTER TABLE jobs ADD COLUMN $columnName $columnDefinition";
        if (!$db->exec($alterTableQuery)) {
            die("Failed to add column $columnName: " . $db->lastErrorMsg());
        }
    }
}

// Handle job posting form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $job_title = $_POST["job_title"];
    $job_category = $_POST["job_category"];
    $job_description = $_POST["job_description"];
    $primary_skill = $_POST["primary_skill"];
    $additional_skills = $_POST["additional_skills"];
    $experience_level = $_POST["experience_level"];
    $budget = $_POST["budget"];
    $deadline = $_POST["deadline"];
    $additional_questions = $_POST["additional_questions"];

    // Handle file upload (attachments)
    $attachments = "";
    if ($_FILES["attachments"]["error"] == 0) {
        $target_dir = "job_attachments/";
        
        // Ensure the directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create the directory if it doesn't exist
        }

        $target_file = $target_dir . basename($_FILES["attachments"]["name"]);

        // Debug: Print file information
        echo "<pre>";
        print_r($_FILES["attachments"]);
        echo "</pre>";

        // Check if the file was uploaded successfully
        if (move_uploaded_file($_FILES["attachments"]["tmp_name"], $target_file)) {
            $attachments = $target_file;
        } else {
            echo "<script>alert('Failed to move uploaded file.');</script>";
        }
    } else {
        echo "<script>alert('File upload error: " . $_FILES["attachments"]["error"] . "');</script>";
    }

    // Insert job details into the database
    $insertQuery = "INSERT INTO jobs (
        username, job_title, job_category, job_description, primary_skill, additional_skills, 
        experience_level, budget, deadline, attachments, additional_questions, status, NoOfbidsReceived
    ) VALUES (
        '$username', '$job_title', '$job_category', '$job_description', '$primary_skill', 
        '$additional_skills', '$experience_level', $budget, '$deadline', '$attachments', 
        '$additional_questions', 'Open', 0
    )";

    if ($db->exec($insertQuery)) {
        echo "<script>window.location.href = 'user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to post job. Error: " . $db->lastErrorMsg() . "');</script>";
    }
}

$db->close();
?>
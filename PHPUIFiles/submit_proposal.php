<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    die(json_encode(['success' => false, 'error' => 'You must be logged in to submit a proposal']));
}

// Database connection
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

if (!$db) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed']));
}

// Debug: Log received POST data
error_log("Received POST data: " . print_r($_POST, true));

// Get freelancer ID based on username
$username = $_SESSION["username"];
$stmt = $db->prepare('SELECT id FROM freelancers WHERE username = :username');
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$result = $stmt->execute();
$freelancer = $result->fetchArray(SQLITE3_ASSOC);

if (!$freelancer) {
    die(json_encode(['success' => false, 'error' => 'Freelancer not found']));
}

$freelancer_id = $freelancer['id'];



// Get form data
$job_id = $_POST['job-id'] ?? null;
$bid_amount = $_POST['bid-amount'] ?? null;
$proposal_text = $_POST['proposal-text'] ?? null;
$completion_time = $_POST['completion-time'] ?? null;

// Debug: Log the job ID received
error_log("Job ID received: " . $job_id);

// Validate input
if (!$job_id || !$bid_amount || !$proposal_text) {
    $debug_info = [
        'received_job_id' => $job_id,
        'received_bid_amount' => $bid_amount,
        'received_proposal_text' => $proposal_text
    ];
    error_log("Validation failed: " . print_r($debug_info, true));
    die(json_encode([
        'success' => false, 
        'error' => 'All fields are required',
        'debug' => $debug_info
    ]));
}

// Check if job exists
$stmt = $db->prepare('SELECT id FROM jobs WHERE id = :job_id');
$stmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$job = $result->fetchArray(SQLITE3_ASSOC);

if (!$job) {
    // Debug: Log additional information about the missing job
    error_log("Job not found in database. Searched for ID: " . $job_id);
    
    // Check if any jobs exist at all
    $check = $db->querySingle("SELECT COUNT(*) as count FROM jobs");
    error_log("Total jobs in database: " . $check);
    
    die(json_encode([
        'success' => false, 
        'error' => 'Job not found',
        'debug' => [
            'searched_job_id' => $job_id,
            'total_jobs_in_db' => $check
        ]
    ]));
}

// Debug: Log before inserting proposal
error_log("Attempting to insert proposal for job ID: " . $job_id);

// Add this right before your INSERT statement
$checkStmt = $db->prepare('SELECT id FROM proposals WHERE job_id = :job_id AND freelancer_id = :freelancer_id');
$checkStmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
$checkStmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
$existing = $checkStmt->execute()->fetchArray();

if ($existing) {
    http_response_code(400); // Bad Request
    die(json_encode([
        'success' => false, 
        'duplicate' => true
    ]));
}

// Insert proposal into database
$stmt = $db->prepare('
    INSERT INTO proposals (job_id, freelancer_id, bid_amount, proposal_text, status)
    VALUES (:job_id, :freelancer_id, :bid_amount, :proposal_text, :status)
');

$stmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
$stmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
$stmt->bindValue(':bid_amount', $bid_amount, SQLITE3_FLOAT);
$stmt->bindValue(':proposal_text', $proposal_text, SQLITE3_TEXT);
$stmt->bindValue(':status', 'Pending', SQLITE3_TEXT);

$result = $stmt->execute();

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false, 
        'error' => 'Failed to submit proposal',
        'db_error' => $db->lastErrorMsg()
    ]);
}

$db->close();
?>
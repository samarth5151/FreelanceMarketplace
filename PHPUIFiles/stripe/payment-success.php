<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

if (!isset($_SESSION['payment_data'])) {
    die("Payment session expired.");
}

$job_id = $_SESSION['payment_data']['job_id'];
$proposal_id = $_SESSION['payment_data']['proposal_id'];
$freelancer_id = $_SESSION['payment_data']['freelancer_id'];
$bid_amount = $_SESSION['payment_data']['bid_amount'];
$platform_fee = $bid_amount * 0.15; // 15% platform fee
$freelancer_amount = $bid_amount - $platform_fee;

// Get user_id from username in session
$username = $_SESSION["username"] ?? '';
if (empty($username)) {
    die("User not logged in.");
}

// Fetch user_id from users table
$stmt = $db->prepare("SELECT users_id FROM users WHERE username = ?");
$stmt->bindValue(1, $username, SQLITE3_TEXT);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    die("User not found.");
}

$user_id = $user['users_id'];

// Begin transaction
$db->exec('BEGIN TRANSACTION');

try {
    // Insert into orders table
    $stmt = $db->prepare("INSERT INTO orders (job_id, freelancer_id, user_id, proposal_id, amount, platform_fee, freelancer_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $job_id, SQLITE3_INTEGER);
    $stmt->bindValue(2, $freelancer_id, SQLITE3_INTEGER);
    $stmt->bindValue(3, $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(4, $proposal_id, SQLITE3_INTEGER);
    $stmt->bindValue(5, $bid_amount, SQLITE3_FLOAT);
    $stmt->bindValue(6, $platform_fee, SQLITE3_FLOAT);
    $stmt->bindValue(7, $freelancer_amount, SQLITE3_FLOAT);
    $stmt->execute();
    $order_id = $db->lastInsertRowID();

    $stmt = $db->prepare("INSERT INTO clientspayments (client_id, job_id, amount, payment_date, order_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(2, $job_id, SQLITE3_INTEGER);
    $stmt->bindValue(3, $bid_amount, SQLITE3_FLOAT);
    $stmt->bindValue(4, date('Y-m-d H:i:s'), SQLITE3_TEXT);
    $stmt->bindValue(5, $order_id, SQLITE3_INTEGER); // Add this line
    $stmt->execute();
    $payment_id = $db->lastInsertRowID();

    // Record platform revenue
    $stmt = $db->prepare("INSERT INTO revenue (payment_id, amount) VALUES (?, ?)");
    $stmt->bindValue(1, $payment_id, SQLITE3_INTEGER);
    $stmt->bindValue(2, $platform_fee, SQLITE3_FLOAT);
    $stmt->execute();

    // Update admin balance (full amount)
    $stmt = $db->prepare("UPDATE admin SET balance = balance + ? WHERE username = 'root'");
    $stmt->bindValue(1, $bid_amount, SQLITE3_FLOAT);
    $stmt->execute();

    // Update freelancer's pending payments
    $stmt = $db->prepare("UPDATE freelancers SET pending_payments = pending_payments + ? WHERE id = ?");
    $stmt->bindValue(1, $freelancer_amount, SQLITE3_FLOAT);
    $stmt->bindValue(2, $freelancer_id, SQLITE3_INTEGER);
    $stmt->execute();

    // Update job status
    $db->exec("UPDATE jobs SET status = 'In Progress' WHERE id = $job_id");

    // Update proposal status
    $db->exec("UPDATE proposals SET status = 'Accepted' WHERE id = $proposal_id");

    $db->exec('COMMIT');

    // Clear session data
    unset($_SESSION['payment_data']);

    // Redirect back to job details
    header("Location: ../job_details.php?id=$job_id&status=success");
    exit;

} catch (Exception $e) {
    $db->exec('ROLLBACK');
    die("Error processing payment: " . $e->getMessage());
}
?>
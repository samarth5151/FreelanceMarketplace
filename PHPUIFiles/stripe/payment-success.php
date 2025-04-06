<?php
session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['payment_data'])) {
    die("Payment session expired.");
}

// Extract payment data with validation
$payment_data = $_SESSION['payment_data'];
$job_id = $payment_data['job_id'] ?? null;
$proposal_id = $payment_data['proposal_id'] ?? null;
$freelancer_id = $payment_data['freelancer_id'] ?? null;
$bid_amount = floatval($payment_data['bid_amount'] ?? 0);

if (!$job_id || !$proposal_id || !$freelancer_id || $bid_amount <= 0) {
    die("Invalid payment data.");
}

$platform_fee = $bid_amount * 0.15;
$freelancer_amount = $bid_amount - $platform_fee;

// Get user_id
$username = $_SESSION["username"] ?? '';
if (empty($username)) {
    die("User not logged in.");
}

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
    // 1. Insert into orders table
    $stmt = $db->prepare("INSERT INTO orders (job_id, freelancer_id, user_id, proposal_id, amount, platform_fee, freelancer_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $job_id, SQLITE3_INTEGER);
    $stmt->bindValue(2, $freelancer_id, SQLITE3_INTEGER);
    $stmt->bindValue(3, $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(4, $proposal_id, SQLITE3_INTEGER);
    $stmt->bindValue(5, $bid_amount, SQLITE3_FLOAT);
    $stmt->bindValue(6, $platform_fee, SQLITE3_FLOAT);
    $stmt->bindValue(7, $freelancer_amount, SQLITE3_FLOAT);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to create order: " . $db->lastErrorMsg());
    }
    $order_id = $db->lastInsertRowID();

    // 2. Insert into clientspayments
    $stmt = $db->prepare("INSERT INTO clientspayments (client_id, job_id, amount, payment_date, order_id) VALUES (?, ?, ?, datetime('now'), ?)");
    $stmt->bindValue(1, $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(2, $job_id, SQLITE3_INTEGER);
    $stmt->bindValue(3, $bid_amount, SQLITE3_FLOAT);
    $stmt->bindValue(4, $order_id, SQLITE3_INTEGER);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to record client payment: " . $db->lastErrorMsg());
    }
    $payment_id = $db->lastInsertRowID();

    // 3. Record platform revenue - FIXED WITH DATE
    $stmt = $db->prepare("INSERT INTO revenue (payment_id, amount, revenue_date) VALUES (?, ?, datetime('now'))");
    $stmt->bindValue(1, $payment_id, SQLITE3_INTEGER);
    $stmt->bindValue(2, $platform_fee, SQLITE3_FLOAT);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to record platform revenue: " . $db->lastErrorMsg());
    }

    // 4. Update admin balance
    $stmt = $db->prepare("UPDATE admin SET balance = balance + ? WHERE username = 'root'");
    $stmt->bindValue(1, $bid_amount, SQLITE3_FLOAT);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update admin balance: " . $db->lastErrorMsg());
    }

    // 5. Update freelancer's pending payments
    $stmt = $db->prepare("UPDATE freelancers SET pending_payments = pending_payments + ? WHERE id = ?");
    $stmt->bindValue(1, $freelancer_amount, SQLITE3_FLOAT);
    $stmt->bindValue(2, $freelancer_id, SQLITE3_INTEGER);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update freelancer pending payments: " . $db->lastErrorMsg());
    }

    // 6. Update job status
    $stmt = $db->prepare("UPDATE jobs SET status = 'In Progress' WHERE id = ?");
    $stmt->bindValue(1, $job_id, SQLITE3_INTEGER);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update job status: " . $db->lastErrorMsg());
    }

    // 7. Update proposal status
    $stmt = $db->prepare("UPDATE proposals SET status = 'Accepted' WHERE id = ?");
    $stmt->bindValue(1, $proposal_id, SQLITE3_INTEGER);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update proposal status: " . $db->lastErrorMsg());
    }

    // Commit transaction
    $db->exec('COMMIT');
    unset($_SESSION['payment_data']);
    
    header("Location: ../job_details.php?id=$job_id&status=success");
    exit;

} catch (Exception $e) {
    $db->exec('ROLLBACK');
    error_log("Payment Error: " . $e->getMessage());
    die("Error processing payment: " . $e->getMessage());
}
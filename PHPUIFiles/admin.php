<?php
// Database connection
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

// Fetch all dashboard statistics
$clientCount = $db->querySingle("SELECT COUNT(*) FROM users");
$freelancerCount = $db->querySingle("SELECT COUNT(*) FROM freelancers");
$totalUsers = $clientCount + $freelancerCount;
$totalPlatformRevenue = $db->querySingle("SELECT SUM(amount) FROM revenue") ?: 0;
$totalClientPayments = $db->querySingle("SELECT SUM(amount) FROM clientspayments") ?: 0;
$totalFreelancerEarnings = $totalClientPayments * 0.85; // 15% platform fee
$totalJobsCompleted = $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'Submitted'");


$revenueData = [];
$revenueLabels = [];
$weeks = 8;

for ($i = $weeks - 1; $i >= 0; $i--) {
    $date = new DateTime();
    $date->modify("-$i week");
    $weekStart = $date->format('Y-m-d');
    $date->modify("+6 days");
    $weekEnd = $date->format('Y-m-d');
    
    $weekRevenue = $db->querySingle("
        SELECT SUM(amount) 
        FROM revenue 
        WHERE date(revenue_date) BETWEEN '$weekStart' AND '$weekEnd'
    ") ?: 0;
    
    $revenueData[] = $weekRevenue;
    $revenueLabels[] = $date->format('M d');
}


// Fetch clients data
$clientsResult = $db->query("
    SELECT 
        u.users_id,
        u.users_name,
        u.username,
        u.users_email AS email,
        DATE(u.users_dob) AS joined_date,
        COUNT(j.id) AS jobs_posted,
        COALESCE(SUM(o.amount), 0) AS total_spent
    FROM users u
    LEFT JOIN jobs j ON u.username = j.username
    LEFT JOIN orders o ON j.id = o.job_id AND o.user_id = u.users_id
    GROUP BY u.users_id
    ORDER BY u.users_name
");

$clients = [];
while ($row = $clientsResult->fetchArray(SQLITE3_ASSOC)) {
    $clients[] = $row;
}

// Fetch freelancers data
$freelancersResult = $db->query("
    SELECT 
        f.id,
        f.name,
        f.username,
        f.email,
        f.skills,
        f.experience,
        f.availability,
        f.profile_picture,
        f.tagline,
        f.about_me,
        f.degree,
        f.institute,
        f.graduation_year,
        f.languages,
        f.contact,
        (SELECT COUNT(*) FROM orders WHERE freelancer_id = f.id AND status = 'Completed') AS jobs_completed,
        (SELECT AVG(rating) FROM ratings WHERE freelancer_id = f.id) AS rating,
        COALESCE(f.earnings, 0) AS earnings
    FROM freelancers f
    ORDER BY f.name
");

$freelancers = [];
while ($row = $freelancersResult->fetchArray(SQLITE3_ASSOC)) {
    $freelancers[] = $row;
}

// Fetch active jobs data
$activeJobsResult = $db->query("
    SELECT 
        o.id AS order_id,
        j.job_title AS job_title,
        u.users_name AS client_name,
        j.budget,
        (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) AS proposals,
        j.deadline,
        o.status
    FROM orders o
    JOIN jobs j ON o.job_id = j.id
    JOIN users u ON o.user_id = u.users_id
    WHERE o.status IN ('Open', 'Bidding', 'In Progress')
    ORDER BY j.posted_date DESC
");

$activeJobs = [];
while ($row = $activeJobsResult->fetchArray(SQLITE3_ASSOC)) {
    $activeJobs[] = $row;
}

// Fetch job history data
$jobHistoryResult = $db->query("
    SELECT 
        o.id AS order_id,
        j.job_title AS job_title,
        o.status,
        u.users_name AS client_name,
        f.name AS freelancer_name,
        o.amount,
        o.platform_fee AS commission,
        o.completed_at
    FROM orders o
    JOIN jobs j ON o.job_id = j.id
    LEFT JOIN freelancers f ON o.freelancer_id = f.id
    JOIN users u ON o.user_id = u.users_id
    WHERE o.status = 'Submitted'
    ORDER BY o.completed_at DESC
");

$jobHistory = [];
while ($row = $jobHistoryResult->fetchArray(SQLITE3_ASSOC)) {
    $jobHistory[] = $row;
}

// Fetch financial data
$totalPayments = $db->querySingle("SELECT SUM(amount) FROM clientspayments") ?: 0;
$platformRevenue = $db->querySingle("SELECT SUM(amount) FROM revenue") ?: 0;


// Client payments - UPDATED WITH JOINS
$clientPaymentsResult = $db->query("
    SELECT 
        cp.id,
        u.users_name AS client_name,  
        j.job_title,
        cp.amount,
        cp.payment_date,
        cp.status
    FROM clientspayments cp
    JOIN users u ON cp.client_id = u.users_id
    JOIN jobs j ON cp.job_id = j.id
    ORDER BY cp.payment_date DESC
    LIMIT 50
");
$clientPayments = [];
while ($row = $clientPaymentsResult->fetchArray(SQLITE3_ASSOC)) {
    $clientPayments[] = $row;
}

// Freelancer payouts
$freelancerPayoutsResult = $db->query("
    SELECT 
        ws.id as submission_id,
        f.name as freelancer_name,
        f.id as freelancer_id,
        j.job_title as job_title,
        u.users_name as client_name,
        o.amount,
        ws.status
    FROM work_submissions ws
    JOIN orders o ON ws.order_id = o.id
    JOIN freelancers f ON ws.freelancer_id = f.id
    JOIN jobs j ON ws.job_id = j.id
    JOIN users u ON o.user_id = u.users_id
    WHERE ws.status IN ('Approved', 'Paid')
    ORDER BY ws.submission_date DESC
");

$freelancerPayouts = [];
while ($row = $freelancerPayoutsResult->fetchArray(SQLITE3_ASSOC)) {
    $freelancerPayouts[] = $row;
}

// Process payout if requested
if (isset($_POST['process_payout'])) {
    $submissionId = $_POST['submission_id'];
    $amount = $_POST['amount'];
    
    $db->exec('BEGIN TRANSACTION');
    
    try {
        // Update work submission status to Paid
        $stmt = $db->prepare("UPDATE work_submissions SET status = 'Paid' WHERE id = ?");
        $stmt->bindValue(1, $submissionId, SQLITE3_INTEGER);
        $stmt->execute();

        // Get freelancer_id from submission
        $freelancerId = $db->querySingle("SELECT freelancer_id FROM work_submissions WHERE id = $submissionId");
        if (!$freelancerId) {
            throw new Exception('Freelancer not found for this submission');
        }

        // Update freelancer's earnings
        $stmt = $db->prepare("UPDATE freelancers SET earnings = COALESCE(earnings, 0) + ? WHERE id = ?");
        $stmt->bindValue(1, $amount, SQLITE3_FLOAT);
        $stmt->bindValue(2, $freelancerId, SQLITE3_INTEGER);
        $stmt->execute();

        // Update admin balance
        $admin_balance = $db->querySingle("SELECT balance FROM admin WHERE username = 'root'");
        $new_admin_balance = $admin_balance - $amount;
        if ($new_admin_balance < 0) {
            throw new Exception('Insufficient platform balance');
        }
        $db->exec("UPDATE admin SET balance = $new_admin_balance WHERE username = 'root'");

        // Record transaction
        $orderId = $db->querySingle("SELECT order_id FROM work_submissions WHERE id = $submissionId");
        $stmt = $db->prepare("INSERT INTO transactions (order_id, freelancer_id, amount, transaction_date, status) VALUES (?, ?, ?, datetime('now'), 'completed')");
        $stmt->bindValue(1, $orderId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $freelancerId, SQLITE3_INTEGER);
        $stmt->bindValue(3, $amount, SQLITE3_FLOAT);
        $stmt->execute();

        $db->exec('COMMIT');
        
        // Refresh the page to show updated data
        header("Location: admin.php");
        exit();
    } catch (Exception $e) {
        $db->exec('ROLLBACK');
        $error = $e->getMessage();
    }
}


if (isset($_POST['process_payout'])) {
    // Debug: Log initial POST data
    error_log("Payout initiated - POST data: " . print_r($_POST, true));
    
    $submissionId = $_POST['submission_id'];
    $amount = floatval($_POST['amount']);
    $freelancerId = $_POST['freelancer_id'];
    
    // Debug: Validate inputs
    error_log("Processing payout - Submission ID: $submissionId, Amount: $amount, Freelancer ID: $freelancerId");
    
    if ($amount <= 0) {
        error_log("Invalid amount: $amount");
        die("Invalid payment amount");
    }

    $db->exec('BEGIN TRANSACTION');
    try {
        // Debug: Check current freelancer balance
        $freelancerBefore = $db->querySingle("SELECT earnings, pending_payments FROM freelancers WHERE id = $freelancerId", true);
        error_log("Freelancer before payment: " . print_r($freelancerBefore, true));
        
        // Debug: Check admin balance before
        $adminBefore = $db->querySingle("SELECT balance FROM admin WHERE username = 'root'");
        error_log("Admin balance before: $adminBefore");

        // 1. Update work submission status
        $stmt = $db->prepare("UPDATE work_submissions SET status = 'Paid' WHERE id = ?");
        $stmt->bindValue(1, $submissionId, SQLITE3_INTEGER);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update work submission: " . $db->lastErrorMsg());
        }
        error_log("Work submission $submissionId marked as Paid");

        // 2. Update freelancer's earnings
        $stmt = $db->prepare("UPDATE freelancers SET 
            earnings = earnings + ?,
            pending_payments = pending_payments - ? 
            WHERE id = ?");
        $stmt->bindValue(1, $amount, SQLITE3_FLOAT);
        $stmt->bindValue(2, $amount, SQLITE3_FLOAT);
        $stmt->bindValue(3, $freelancerId, SQLITE3_INTEGER);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update freelancer: " . $db->lastErrorMsg());
        }
        
        // Debug: Verify freelancer update
        $freelancerAfter = $db->querySingle("SELECT earnings, pending_payments FROM freelancers WHERE id = $freelancerId", true);
        error_log("Freelancer after payment: " . print_r($freelancerAfter, true));

        // 3. Deduct from admin balance
        $stmt = $db->prepare("UPDATE admin SET balance = balance - ? WHERE username = 'root'");
        $stmt->bindValue(1, $amount, SQLITE3_FLOAT);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update admin balance: " . $db->lastErrorMsg());
        }
        
        // Debug: Verify admin update
        $adminAfter = $db->querySingle("SELECT balance FROM admin WHERE username = 'root'");
        error_log("Admin balance after: $adminAfter");

        // 4. Record transaction
        $stmt = $db->prepare("INSERT INTO transactions (
            freelancer_id, 
            amount, 
            type, 
            status
        ) VALUES (?, ?, 'payout', 'completed')");
        $stmt->bindValue(1, $freelancerId, SQLITE3_INTEGER);
        $stmt->bindValue(2, $amount, SQLITE3_FLOAT);
        if (!$stmt->execute()) {
            throw new Exception("Failed to record transaction: " . $db->lastErrorMsg());
        }
        $transactionId = $db->lastInsertRowID();
        error_log("Transaction recorded - ID: $transactionId");

        $db->exec('COMMIT');
        error_log("Payout completed successfully");
        
        // Debug output for browser (remove in production)
        echo "<script>console.log('Payout processed successfully');</script>";
        
        header("Location: admin.php?section=financial&payout=success&txid=$transactionId");
        exit;
        
    } catch (Exception $e) {
        $db->exec('ROLLBACK');
        error_log("Payout ERROR: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
        
        // Debug output for browser (remove in production)
        echo "<script>console.error('Payout failed: " . addslashes($e->getMessage()) . "');</script>";
        
        $error = "Payout failed: " . $e->getMessage();
        
        // Store error in session for display after redirect
        $_SESSION['payout_error'] = $error;
        header("Location: admin.php?section=financial&payout=error");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Freelance Platform</title>
    
    <!-- Required Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --background-color: #f8fafc;
            --card-color: #ffffff;
            --text-color: #1e293b;
            --muted-color: #64748b;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            background: #1e293b;
            height: 100vh;
            position: fixed;
            width: 280px;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .main-content {
            margin-left: 280px;
            transition: all 0.3s ease;
            padding: 2rem;
        }

        .stat-card {
            background: var(--card-color);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .total-balance-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInUp 0.5s ease;
        }

        .chart-container {
            background: var(--card-color);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            height: 400px;
        }

        .nav-link {
            color: #94a3b8;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 4px 0;
        }

        .nav-link:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .section-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            border-radius: 12px 12px 0 0;
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }
            .main-content {
                margin-left: 0;
            }
        }

        .hidden {
            display: none;
        }

        .payment-btn {
            padding: 6px 16px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .payment-btn:hover {
            transform: scale(1.05);
        }

        .user-type-chart {
            max-width: 300px;
            margin: 0 auto;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            animation: fadeInUp 0.5s ease;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: white;
        }

        .bg-primary { background-color: var(--primary-color); }
        .bg-warning { background-color: var(--warning-color); }
        .bg-info { background-color: #0ea5e9; }
        .bg-success { background-color: var(--success-color); }
        .bg-secondary { background-color: #64748b; }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar px-3 py-4">
    <div class="brand mb-5">
        <h3 class="text-white text-center">Admin Console</h3>
    </div>
    
    <nav class="nav flex-column">
        <a class="nav-link active" href="#dashboard" onclick="showSection('dashboard')">
            <i class="fas fa-home me-2"></i> Dashboard
        </a>
        <a class="nav-link" href="#users" onclick="showSection('users')">
            <i class="fas fa-users me-2"></i> User Management
        </a>
        <a class="nav-link" href="#jobs" onclick="showSection('jobs')">
            <i class="fas fa-briefcase me-2"></i> Job Management
        </a>
        <a class="nav-link" href="#financial" onclick="showSection('financial')">
            <i class="fas fa-coins me-2"></i> Financial
        </a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    
    <!-- Dashboard Section -->
    <div id="dashboard">
        <!-- Top Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Platform Overview</h2>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card animate-card">
                    <h5 class="text-muted mb-3">Total Users</h5>
                    <h2 class="text-primary fw-bold"><?= $totalUsers ?></h2>
                    <div class="d-flex justify-content-between text-muted">
                        <small>Last 7 days</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card animate-card">
                    <h5 class="text-muted mb-3">Total Platform Revenue</h5>
                    <h2 class="text-success fw-bold">$<?= number_format($totalPlatformRevenue, 2) ?></h2>
                    <div class="d-flex justify-content-between text-muted">
                        <small>This month</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card animate-card">
                    <h5 class="text-muted mb-3">Total Freelancer Earnings</h5>
                    <h2 class="text-warning fw-bold">$<?= number_format($totalFreelancerEarnings, 2) ?></h2>
                    <div class="d-flex justify-content-between text-muted">
                        <small>This month</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card animate-card">
                    <h5 class="text-muted mb-3">Total Jobs Completed</h5>
                    <h2 class="text-info fw-bold"><?= $totalJobsCompleted ?></h2>
                    <div class="d-flex justify-content-between text-muted">
                        <small>This month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-8">
                <div class="chart-container animate-card">
                    <h5 class="mb-3">Platform Growth</h5>
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="chart-container user-type-chart animate-card">
                    <h5 class="mb-3">User Distribution</h5>
                    <canvas id="userDistribution"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management Section -->
    <div id="users" class="hidden">
        <!-- Client Management -->
        <div class="card border-0 shadow mb-4 animate-card">
            <div class="section-header">
                <h5 class="mb-0">Client Management</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Joined</th>
                                <th>Jobs Posted</th>
                                <th>Total Spent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="avatar.png" class="rounded-circle me-2" width="35" height="35">
                                        <?= htmlspecialchars($client['users_name']) ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($client['joined_date']) ?></td>
                                <td><?= htmlspecialchars($client['jobs_posted']) ?></td>
                                <td>$<?= number_format($client['total_spent'], 2) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-comment-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Freelancer Management -->
        <div class="card border-0 shadow animate-card">
            <div class="section-header">
                <h5 class="mb-0">Freelancer Management</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Freelancer</th>
                                <th>Skills</th>
                                <th>Rating</th>
                                <th>Completed</th>
                                <th>Earnings</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($freelancers as $freelancer): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="avatar.png" class="rounded-circle me-2" width="35" height="35">
                                        <?= htmlspecialchars($freelancer['name']) ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($freelancer['skills']) ?></td>
                                <td><span class="text-warning"><i class="fas fa-star"></i> <?= number_format($freelancer['rating'] ?? 0, 1) ?></span></td>
                                <td><?= htmlspecialchars($freelancer['jobs_completed']) ?></td>
                                <td>$<?= number_format($freelancer['earnings'], 2) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-comment-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Management Section -->
    <div id="jobs" class="hidden">
        <!-- Active Jobs -->
        <div class="card border-0 shadow mb-4 animate-card">
            <div class="section-header">
                <h5 class="mb-0">Active Jobs</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Client</th>
                                <th>Budget</th>
                                <th>Proposals</th>
                                <th>Deadline</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activeJobs as $job): ?>
                            <tr>
                                <td><?= htmlspecialchars($job['job_title']) ?></td>
                                <td><?= htmlspecialchars($job['client_name']) ?></td>
                                <td>$<?= number_format($job['budget'], 2) ?></td>
                                <td><?= htmlspecialchars($job['proposals']) ?></td>
                                <td><?= date('M d, Y', strtotime($job['deadline'])) ?></td>
                                <td><span class="status-badge <?= getStatusClass($job['status']) ?>"><?= htmlspecialchars($job['status']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Job History -->
        <div class="card border-0 shadow animate-card">
            <div class="section-header">
                <h5 class="mb-0">Job History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Status</th>
                                <th>Client</th>
                                <th>Freelancer</th>
                                <th>Amount</th>
                                <th>Commission</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jobHistory as $job): ?>
                            <tr>
                                <td><?= htmlspecialchars($job['job_title']) ?></td>
                                <td><span class="status-badge <?= getStatusClass($job['status']) ?>"><?= htmlspecialchars($job['status']) ?></span></td>
                                <td><?= htmlspecialchars($job['client_name']) ?></td>
                                <td><?= htmlspecialchars($job['freelancer_name']) ?></td>
                                <td>$<?= number_format($job['amount'], 2) ?></td>
                                <td>$<?= number_format($job['commission'] ?? 0, 2) ?></td>
                        <td><?= !empty($job['completed_at']) ? date('M d, Y', strtotime($job['completed_at'])) : '' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Section -->
    <div id="financial" class="hidden">
        <div class="row mb-4">
            <div class="col-12">
                <div class="total-balance-card">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-3">Platform Balance</h4>
                            
                            <h1 class="display-4 fw-bold">$<?php // Fetch financial data
    $totalPayments = $db->querySingle("SELECT SUM(amount) FROM clientspayments") ?: 0;
    $totalPayouts = $db->querySingle("SELECT SUM(amount) FROM transactions WHERE type = 'payout' AND status = 'completed'") ?: 0;
    $platformBalance = $totalPayments - $totalPayouts;
    echo $platformBalance ?></h1>
                            
                            <p class="mb-0">Total payments received</p>
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-3">Platform Revenue</h4>
                            <h1 class="display-4 fw-bold">$<?= number_format($platformRevenue, 2) ?></h1>
                            <p class="mb-0">20% commission from all payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="financialTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="client-tab" data-bs-toggle="tab" data-bs-target="#client-payments" type="button" role="tab">Client Payments</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="freelancer-tab" data-bs-toggle="tab" data-bs-target="#freelancer-payouts" type="button" role="tab">Freelancer Payouts</button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="financialTabsContent">
            <!-- Client Payments Tab -->
            <div class="tab-pane fade show active" id="client-payments" role="tabpanel">
                <div class="card border-0 shadow mb-4 animate-card">
                    <div class="card-header bg-white border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">Client Payments</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <select class="form-select form-select-sm" id="clientPaymentFilter">
                                            <option value="all">All Payments</option>
                                            <option value="this_month">This Month</option>
                                            <option value="last_month">Last Month</option>
                                            <option value="this_year">This Year</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control form-control-sm" id="clientSearch" placeholder="Search clients...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Client</th>
                                        <th>Job Title</th>
                                        <th>Amount</th>
                                        <th>Platform Fee (20%)</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clientPayments as $payment): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($payment['id']) ?></td>
                                        <td><?= htmlspecialchars($payment['client_name']) ?></td>
                                        <td><?= htmlspecialchars($payment['job_title']) ?></td>
                                        <td>$<?= number_format($payment['amount'], 2) ?></td>
                                        <td>$<?= number_format($payment['amount'] * 0.2, 2) ?></td>
                                        <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                        <td><span class="status-badge bg-success">Paid</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Freelancer Payouts Tab -->
            <div class="tab-pane fade" id="freelancer-payouts" role="tabpanel">
                <div class="card border-0 shadow animate-card">
                    <div class="card-header bg-white border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">Freelancer Payouts</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <select class="form-select form-select-sm" id="freelancerPayoutFilter">
                                            <option value="pending">Pending Approval</option>
                                            <option value="approved">Approved Work</option>
                                            <option value="paid">Paid</option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control form-control-sm" id="freelancerSearch" placeholder="Search freelancers...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Submission ID</th>
                                        <th>Freelancer</th>
                                        <th>Job Title</th>
                                        <th>Client</th>
                                        <th>Amount</th>
                                        <th>Platform Fee (15%)</th>
                                        <th>Payout Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($freelancerPayouts as $payout): 
                                        $platformFee = $payout['amount'] * 0.15;
                                        $payoutAmount = $payout['amount'] - $platformFee;
                                        $statusBadge = $payout['status'] === 'Approved' ? 'bg-warning' : 
                                                     ($payout['status'] === 'Paid' ? 'bg-success' : 'bg-secondary');
                                    ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($payout['submission_id']) ?></td>
                                        <td><?= htmlspecialchars($payout['freelancer_name']) ?></td>
                                        <td><?= htmlspecialchars($payout['job_title']) ?></td>
                                        <td><?= htmlspecialchars($payout['client_name']) ?></td>
                                        <td>$<?= number_format($payout['amount'], 2) ?></td>
                                        <td>$<?= number_format($platformFee, 2) ?></td>
                                        <td>$<?= number_format($payoutAmount, 2) ?></td>
                                        <td><span class="status-badge <?= $statusBadge ?>"><?= htmlspecialchars($payout['status']) ?></span></td>
                                        <td>
                                            <?php if ($payout['status'] === 'Approved'): ?>
                                                <form method="post" onsubmit="return confirm('Confirm payout of $<?= number_format($payoutAmount, 2) ?>?')">
                                                    <input type="hidden" name="process_payout" value="1">
                                                    <input type="hidden" name="submission_id" value="<?= $payout['submission_id'] ?>">
                                                    <input type="hidden" name="amount" value="<?= $payoutAmount ?>">
                                                    <input type="hidden" name="freelancer_id" value="<?= $payout['freelancer_id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-success payment-btn">
                                                        <i class="fas fa-money-bill-wave me-2"></i>Pay Now
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Platform Growth Chart
    const revenueChart = new Chart(document.getElementById('growthChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_reverse($revenueLabels)) ?>,
        datasets: [{
            label: 'Platform Revenue',
            data: <?= json_encode(array_reverse($revenueData)) ?>,
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Revenue ($)' }
            },
            x: { title: { display: true, text: 'Weeks' } }
        }
    }
});

    // Initialize User Distribution Chart with dynamic data
    const userDistribution = new Chart(document.getElementById('userDistribution'), {
        type: 'doughnut',
        data: {
            labels: ['Freelancers', 'Clients'],
            datasets: [{
                data: [<?= $freelancerCount ?>, <?= $clientCount ?>],
                backgroundColor: ['#6366f1', '#10b981'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 12
                }
            },
            cutout: '70%'
        }
    });

    function showSection(sectionId) {
        // Remove 'active' class from all nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });

        // Add 'active' class to the clicked nav link
        const activeLink = document.querySelector(`.nav-link[href="#${sectionId}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }

        // Hide all sections and show only the selected one
        ['dashboard', 'users', 'jobs', 'financial'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                if (id === sectionId) {
                    element.classList.remove('hidden');
                    element.classList.add('animate__animated', 'animate__fadeIn');
                } else {
                    element.classList.add('hidden');
                }
            }
        });
    }

    // Handle URL hash changes
    function handleHashChange() {
        const sectionId = window.location.hash.substring(1); // Remove the '#' from the hash
        if (sectionId) {
            showSection(sectionId);
        }
    }

    // Listen for hash changes
    window.addEventListener('hashchange', handleHashChange);

    // Initialize default view based on URL hash
    const defaultSection = window.location.hash ? window.location.hash.substring(1) : 'dashboard';
    showSection(defaultSection);

    // Add click event listeners to sidebar links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default anchor behavior
            const sectionId = this.getAttribute('href').substring(1); // Get section ID from href
            window.location.hash = sectionId; // Update URL hash
            showSection(sectionId); // Manually trigger section change
        });
    });
});

<?php
function getStatusClass($status) {
    switch (strtolower($status)) {
        case 'open': return 'bg-primary';
        case 'bidding': return 'bg-warning';
        case 'in progress': return 'bg-info';
        case 'submitted': return 'bg-success';
        default: return 'bg-secondary';
    }
}
?>
</script>
</body>
</html>
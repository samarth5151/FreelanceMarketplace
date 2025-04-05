<?php
header('Content-Type: application/json');

$dbPath = 'C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db';

try {
    $db = new SQLite3($dbPath);
    $db->exec('PRAGMA foreign_keys = ON'); // Enable foreign key constraints

    // Determine request type
    $action = $_GET['action'] ?? 'dashboard';

    switch($action) {
        case 'getClients':
            $stmt = $db->prepare("
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
            $result = $stmt->execute();
            $clients = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $clients[] = $row;
            }
            echo json_encode(['success' => true, 'clients' => $clients]);
            break;

        case 'getFreelancers':
            $stmt = $db->prepare("
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
                    (SELECT AVG(rating) FROM job_reviews WHERE freelancer_id = f.id) AS rating,
                    COALESCE(f.earnings, 0) AS earnings
                FROM freelancers f
                ORDER BY f.name
            ");
            $result = $stmt->execute();
            $freelancers = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $freelancers[] = $row;
            }
            echo json_encode(['success' => true, 'freelancers' => $freelancers]);
            break;

        case 'getActiveJobs':
            $stmt = $db->prepare("
                SELECT 
                    o.id AS order_id,
                    j.title AS job_title,
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
            $result = $stmt->execute();
            $activeJobs = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $activeJobs[] = $row;
            }
            echo json_encode(['success' => true, 'activeJobs' => $activeJobs]);
            break;

        case 'getJobHistory':
            $stmt = $db->prepare("
                SELECT 
                    o.id AS order_id,
                    j.title AS job_title,
                    o.status,
                    u.users_name AS client_name,
                    f.name AS freelancer_name,
                    o.amount,
                    o.platform_fee AS commission,
                    o.completed_at
                FROM orders o
                JOIN jobs j ON o.job_id = j.id
                LEFT JOIN freelancers f ON o.freelancer_id = f.id
                WHERE o.status = 'Submitted'
                ORDER BY o.completed_at DESC
            ");
            $result = $stmt->execute();
            $jobHistory = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $jobHistory[] = $row;
            }
            echo json_encode(['success' => true, 'jobHistory' => $jobHistory]);
            break;

        case 'getFinancialData':
            // Get total payments and platform revenue
            $totalPayments = $db->querySingle("SELECT SUM(amount) FROM clientspayments") ?: 0;
            $platformRevenue = $db->querySingle("SELECT SUM(amount) FROM revenue") ?: 0;
            
            // Get client payments
            $stmt = $db->prepare("SELECT * FROM clientspayments ORDER BY payment_date DESC LIMIT 50");
            $result = $stmt->execute();
            $clientPayments = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $clientPayments[] = $row;
            }
            
            // Get freelancer payouts
            $stmt = $db->prepare("
                SELECT 
                    ws.id as submission_id,
                    f.name as freelancer_name,
                    j.title as job_title,
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
            $result = $stmt->execute();
            $freelancerPayouts = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $freelancerPayouts[] = $row;
            }
            
            echo json_encode([
                'success' => true,
                'total_payments' => $totalPayments,
                'platform_revenue' => $platformRevenue,
                'client_payments' => $clientPayments,
                'freelancer_payouts' => $freelancerPayouts
            ]);
            break;

        case 'processPayout':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $submissionId = $data['submission_id'] ?? null;
            $amount = $data['amount'] ?? null;

            if (!$submissionId || !$amount) {
                throw new Exception('Submission ID and amount are required');
            }

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

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                $db->exec('ROLLBACK');
                throw $e;
            }
            break;

        default: // Dashboard stats
            // Get user counts
            $clientCount = $db->querySingle("SELECT COUNT(*) FROM users");
            $freelancerCount = $db->querySingle("SELECT COUNT(*) FROM freelancers");
            $totalUsers = $clientCount + $freelancerCount;

            // Get financial stats
            $totalPlatformRevenue = $db->querySingle("SELECT SUM(amount) FROM revenue") ?: 0;
            $totalClientPayments = $db->querySingle("SELECT SUM(amount) FROM clientspayments") ?: 0;
            $totalFreelancerEarnings = $totalClientPayments * 0.85; // 15% platform fee
            $totalJobsCompleted = $db->querySingle("SELECT COUNT(*) FROM orders WHERE status = 'Submitted'");

            // Get platform growth data
            $growthData = [];
            $growthLabels = [];
            $weeks = 8; // Last 8 weeks

            for ($i = $weeks - 1; $i >= 0; $i--) {
                $date = new DateTime();
                $date->modify("-$i week");
                $weekStart = $date->format('Y-m-d');
                $date->modify("+6 days");
                $weekEnd = $date->format('Y-m-d');
                
                $usersCount = $db->querySingle("SELECT COUNT(*) FROM users WHERE users_dob BETWEEN '$weekStart' AND '$weekEnd'");
                $freelancersCount = $db->querySingle("SELECT COUNT(*) FROM freelancers WHERE created_at BETWEEN '$weekStart' AND '$weekEnd'");
                
                $growthData[] = $usersCount + $freelancersCount;
                $growthLabels[] = $date->format('M d');
            }

            echo json_encode([
                'success' => true,
                'totalUsers' => $totalUsers,
                'totalPlatformRevenue' => $totalPlatformRevenue,
                'totalFreelancerEarnings' => $totalFreelancerEarnings,
                'totalJobsCompleted' => $totalJobsCompleted,
                'growthChartData' => array_reverse($growthData),
                'growthChartLabels' => array_reverse($growthLabels),
                'freelancers' => $freelancerCount,
                'clients' => $clientCount
            ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
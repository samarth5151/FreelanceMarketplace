<?php
header('Content-Type: application/json');

$dbPath = 'C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db';

try {
    $db = new SQLite3($dbPath);
    $db->exec('PRAGMA foreign_keys = ON');

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
            $total_freelancers = $db->querySingle("SELECT COUNT(*) FROM freelancers");
            $active_freelancers = $db->querySingle("SELECT COUNT(*) FROM freelancers WHERE availability = 'Available'");
            $total_earnings = $db->querySingle("SELECT SUM(earnings) FROM freelancers") ?: 0;
            $pending_payouts = $db->querySingle("SELECT SUM(pending_payments) FROM freelancers") ?: 0;

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
                    f.earnings,
                    f.pending_payments,
                    (SELECT COUNT(*) FROM orders WHERE freelancer_id = f.id AND status = 'Completed') AS completed_jobs,
                    (SELECT AVG(rating) FROM job_reviews WHERE freelancer_id = f.id) AS rating,
                    (SELECT strftime('%Y-%m-%d', MIN(created_at)) FROM orders WHERE freelancer_id = f.id) AS join_date
                FROM freelancers f
                ORDER BY f.name
            ");
            $result = $stmt->execute();
            $freelancers = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $freelancers[] = $row;
            }

            echo json_encode([
                'success' => true,
                'total_freelancers' => $total_freelancers,
                'active_freelancers' => $active_freelancers,
                'total_earnings' => $total_earnings,
                'pending_payouts' => $pending_payouts,
                'freelancers' => $freelancers
            ]);
            break;

        case 'getFreelancerDetails':
            $freelancerId = $_GET['id'] ?? null;
            if (!$freelancerId) {
                throw new Exception('Freelancer ID is required');
            }

            $stmt = $db->prepare("
                SELECT 
                    f.*,
                    (SELECT COUNT(*) FROM orders WHERE freelancer_id = f.id AND status = 'Completed') as completed_jobs,
                    (SELECT AVG(rating) FROM job_reviews WHERE freelancer_id = f.id) as rating,
                    (SELECT strftime('%Y-%m-%d', MIN(created_at)) as join_date
                FROM freelancers f
                WHERE f.id = :id
            ");
            $stmt->bindValue(':id', $freelancerId, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $freelancer = $result->fetchArray(SQLITE3_ASSOC);

            if (!$freelancer) {
                throw new Exception('Freelancer not found');
            }

            echo json_encode([
                'success' => true,
                'freelancer' => $freelancer
            ]);
            break;

        case 'getActiveJobs':
            $stmt = $db->prepare("
                SELECT 
                    j.id,
                    j.title AS job_title,
                    u.users_name AS client_name,
                    j.budget,
                    (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) AS proposals,
                    j.deadline,
                    j.status
                FROM jobs j
                JOIN users u ON j.username = u.username
                WHERE j.status IN ('Open', 'Bidding', 'In Progress')
                ORDER BY j.posted_date DESC
            ");
            $result = $stmt->execute();
            $activeJobs = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $activeJobs[] = $row;
            }
            echo json_encode(['success' => true, 'activeJobs' => $activeJobs]);
            break;

        case 'getFinancialData':
            // Get admin balance
            $adminBalance = $db->querySingle("SELECT balance FROM admin WHERE username = 'root'") ?: 0;
            
            // Get total revenue
            $totalRevenue = $db->querySingle("SELECT SUM(amount) FROM revenue") ?: 0;
            
            // Get client payments
            $stmt = $db->prepare("
                SELECT 
                    cp.id as payment_id,
                    cp.amount,
                    cp.payment_date,
                    u.users_name as client_name,
                    j.title as job_title,
                    f.name as freelancer_name
                FROM clientspayments cp
                JOIN users u ON cp.client_id = u.users_id
                JOIN jobs j ON cp.job_id = j.id
                JOIN freelancers f ON cp.freelancer_id = f.id
                ORDER BY cp.payment_date DESC
                LIMIT 50
            ");
            $result = $stmt->execute();
            $clientPayments = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $clientPayments[] = $row;
            }
            
            // Get pending payouts
            $stmt = $db->prepare("
                SELECT 
                    ws.id as submission_id,
                    f.id as freelancer_id,
                    f.name as freelancer_name,
                    j.title as job_title,
                    u.users_name as client_name,
                    o.freelancer_amount as amount,
                    ws.status
                FROM work_submissions ws
                JOIN orders o ON ws.order_id = o.id
                JOIN freelancers f ON ws.freelancer_id = f.id
                JOIN jobs j ON ws.job_id = j.id
                JOIN users u ON o.user_id = u.users_id
                WHERE ws.status = 'Approved'
                ORDER BY ws.submission_date DESC
            ");
            $result = $stmt->execute();
            $pendingPayouts = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $pendingPayouts[] = $row;
            }
            
            echo json_encode([
                'success' => true,
                'admin_balance' => $adminBalance,
                'total_revenue' => $totalRevenue,
                'client_payments' => $clientPayments,
                'pending_payouts' => $pendingPayouts
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
                // Verify admin has sufficient balance
                $currentBalance = $db->querySingle("SELECT balance FROM admin WHERE username = 'root'");
                if ($currentBalance < $amount) {
                    throw new Exception('Insufficient admin balance');
                }

                // Get freelancer_id from submission
                $freelancerId = $db->querySingle("SELECT freelancer_id FROM work_submissions WHERE id = $submissionId");
                if (!$freelancerId) {
                    throw new Exception('Freelancer not found for this submission');
                }

                // Deduct from admin balance
                $stmt = $db->prepare("UPDATE admin SET balance = balance - ? WHERE username = 'root'");
                $stmt->bindValue(1, $amount, SQLITE3_FLOAT);
                $stmt->execute();

                // Update freelancer's earnings and pending payments
                $stmt = $db->prepare("
                    UPDATE freelancers 
                    SET earnings = earnings + ?,
                        pending_payments = pending_payments - ?
                    WHERE id = ?
                ");
                $stmt->bindValue(1, $amount, SQLITE3_FLOAT);
                $stmt->bindValue(2, $amount, SQLITE3_FLOAT);
                $stmt->bindValue(3, $freelancerId, SQLITE3_INTEGER);
                $stmt->execute();

                // Update work submission status
                $stmt = $db->prepare("UPDATE work_submissions SET status = 'Paid' WHERE id = ?");
                $stmt->bindValue(1, $submissionId, SQLITE3_INTEGER);
                $stmt->execute();

                // Record transaction
                $orderId = $db->querySingle("SELECT order_id FROM work_submissions WHERE id = $submissionId");
                $stmt = $db->prepare("
                    INSERT INTO transactions (admin_id, freelancer_id, submission_id, amount, type, description)
                    VALUES (1, ?, ?, ?, 'payout', 'Freelancer payment for completed work')
                ");
                $stmt->bindValue(1, $freelancerId, SQLITE3_INTEGER);
                $stmt->bindValue(2, $submissionId, SQLITE3_INTEGER);
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
            $clientCount = $db->querySingle("SELECT COUNT(*) FROM users");
            $freelancerCount = $db->querySingle("SELECT COUNT(*) FROM freelancers");
            $activeJobsCount = $db->querySingle("SELECT COUNT(*) FROM jobs WHERE status IN ('Open', 'Bidding', 'In Progress')");
            $completedJobsCount = $db->querySingle("SELECT COUNT(*) FROM jobs WHERE status = 'Completed'");
            $totalRevenue = $db->querySingle("SELECT SUM(amount) FROM revenue") ?: 0;
            $adminBalance = $db->querySingle("SELECT balance FROM admin WHERE username = 'root'") ?: 0;
            
            echo json_encode([
                'success' => true,
                'totalUsers' => ($clientCount + $freelancerCount),
                'clients' => $clientCount,
                'freelancers' => $freelancerCount,
                'activeJobs' => $activeJobsCount,
                'completedJobs' => $completedJobsCount,
                'totalRevenue' => $totalRevenue,
                'adminBalance' => $adminBalance
            ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
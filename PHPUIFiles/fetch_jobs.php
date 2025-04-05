<?php
header('Content-Type: application/json');

// Database configuration
$db_path = 'C:/xampp/htdocs/FreelanceMarketplace/Connection/Freelance_db.db'; // Ensure this path is correct

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Retrieve GET parameters and sanitize input
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$experience = isset($_GET['experience']) ? trim($_GET['experience']) : '';
$budget = isset($_GET['budget']) ? trim($_GET['budget']) : '';
$skill = isset($_GET['skill']) ? trim($_GET['skill']) : '';

// Base SQL query
$sql = "SELECT 
            job_title AS title,
            username,
            job_category AS category,
            experience_level AS experience,
            budget,
            status,
            posted_date,
            deadline
        FROM jobs WHERE 1=1";
$params = [];

// Search filter
if (!empty($search)) {
    $sql .= " AND (job_title LIKE :search OR job_description LIKE :search)";
    $params[':search'] = "%$search%";
}

// Experience filter
if (!empty($experience)) {
    $exp_levels = explode(',', $experience);
    $placeholders = [];
    foreach ($exp_levels as $key => $value) {
        $param = ":exp_$key";
        $placeholders[] = $param;
        $params[$param] = $value;
    }
    $sql .= " AND experience_level IN (" . implode(',', $placeholders) . ")";
}

// Budget filter
if (!empty($budget)) {
    $budget_ranges = explode(',', $budget);
    $budget_conditions = [];
    foreach ($budget_ranges as $key => $range) {
        if (strpos($range, '-') !== false) {
            list($min, $max) = explode('-', $range);
            $min_param = ":budget_min_$key";
            $max_param = ":budget_max_$key";
            $budget_conditions[] = "(budget BETWEEN $min_param AND $max_param)";
            $params[$min_param] = (float)$min;
            $params[$max_param] = (float)$max;
        }
    }
    if (!empty($budget_conditions)) {
        $sql .= " AND (" . implode(' OR ', $budget_conditions) . ")";
    }
}

// Skill filter
if (!empty($skill)) {
    $sql .= " AND primary_skill = :skill";
    $params[':skill'] = $skill;
}

// Execute query
try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format output
    foreach ($jobs as &$job) {
        // Calculate posted time ago
        $posted_date = new DateTime($job['posted_date']);
        $now = new DateTime();
        $interval = $now->diff($posted_date);
        $job['posted_ago'] = $interval->days . ' days ago';

        // Format deadline
        $deadline_date = new DateTime($job['deadline']);
        $job['deadline'] = $deadline_date->format('M j, Y');
    }

    echo json_encode($jobs);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Query execution failed']);
}
?>

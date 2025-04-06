<?php
session_start();

$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    $username = "";
}

function fetchJobs($db, $filters = []) {
    $sql = "SELECT 
                j.id,
                j.job_title AS title,
                j.job_category AS category,
                j.experience_level AS experience,
                j.budget,
                j.username,
                j.status,
                j.posted_date,
                j.deadline
            FROM jobs j
            WHERE j.status = 'Open'";
    
    $params = [];
    
    if (!empty($filters['search'])) {
        $sql .= " AND (j.job_title LIKE :search OR j.job_description LIKE :search)";
        $params[':search'] = "%{$filters['search']}%";
    }
    
    if (!empty($filters['categories'])) {
        $categories = is_array($filters['categories']) ? $filters['categories'] : [$filters['categories']];
        $placeholders = [];
        foreach ($categories as $key => $value) {
            $param = ":category_$key";
            $placeholders[] = $param;
            $params[$param] = $value;
        }
        $sql .= " AND j.job_category IN (" . implode(',', $placeholders) . ")";
    }
    
    if (!empty($filters['experience'])) {
        $experience = is_array($filters['experience']) ? $filters['experience'] : [$filters['experience']];
        $placeholders = [];
        foreach ($experience as $key => $value) {
            $param = ":exp_$key";
            $placeholders[] = $param;
            $params[$param] = $value;
        }
        $sql .= " AND j.experience_level IN (" . implode(',', $placeholders) . ")";
    }
    
    if (!empty($filters['budget'])) {
        $budgets = is_array($filters['budget']) ? $filters['budget'] : [$filters['budget']];
        $conditions = [];
        foreach ($budgets as $key => $range) {
            if (strpos($range, '-') !== false) {
                list($min, $max) = explode('-', $range);
                $minParam = ":budget_min_$key";
                $maxParam = ":budget_max_$key";
                $conditions[] = "(j.budget BETWEEN $minParam AND $maxParam)";
                $params[$minParam] = (float)$min;
                $params[$maxParam] = (float)$max;
            }
        }
        if (!empty($conditions)) {
            $sql .= " AND (" . implode(' OR ', $conditions) . ")";
        }
    }
    
    if (!empty($filters['skills'])) {
        $skills = is_array($filters['skills']) ? $filters['skills'] : [$filters['skills']];
        $placeholders = [];
        foreach ($skills as $key => $skill) {
            $param = ":skill_$key";
            $placeholders[] = $param;
            $params[$param] = $skill;
        }
        $sql .= " AND j.primary_skill IN (" . implode(',', $placeholders) . ")";
    }
    
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $result = $stmt->execute();
    $jobs = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $postedDate = new DateTime($row['posted_date']);
        $now = new DateTime();
        $interval = $now->diff($postedDate);
        $row['posted_ago'] = $interval->format('%a days ago');
        
        $deadlineDate = new DateTime($row['deadline']);
        $row['deadline'] = $deadlineDate->format('M j, Y');
        
        $jobs[] = $row;
    }
    
    return $jobs;
}

$filters = [
    'search' => $_GET['search'] ?? '',
    'categories' => $_GET['categories'] ?? [],
    'skills' => $_GET['skills'] ?? [],
    'experience' => $_GET['experience'] ?? [],
    'budget' => $_GET['budget'] ?? []
];

$jobs = fetchJobs($db, $filters);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Jobs | Freelance Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #262b40;
            --accent-green: rgb(32 159 75);
            --text-primary: rgba(0, 0, 0, 0.644);
            --text-hover: #000000;
            --bg-light: whitesmoke;
            --bg-white: whitesmoke;
            --gray-icon: #c9d1d9;
            --button-hover: rgb(81, 81, 81);
            --section-padding: 80px 5%;
            --shoe-color: #4B3621;
            --status-open: #28a745;
            --status-completed: #6c757d;
            --status-inprogress: #17a2b8;
            --status-cancelled: #dc3545;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
            --card-hover-shadow: 0 10px 20px rgba(0, 0, 0, 0.1), 0 6px 6px rgba(0, 0, 0, 0.05);
            --card-border: 1px solid rgba(0, 0, 0, 0.08);
            --card-hover-border: 1px solid rgba(32, 159, 75, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-primary);
            background-color: var(--bg-light);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background-color: var(--bg-white);
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin: 10px 15px 5px 15px;
            border-radius: 12px;
            height: 70px;
        }

        .navbar-container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-dark);
            text-decoration: none;
        }

        .navbar-brand img {
            height: 40px;
        }

        .navbar-nav {
            display: flex;
            list-style: none;
            gap: 30px;
            align-items: center;
        }

        .nav-link {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: var(--text-hover);
        }

        .nav-btn {
            background-color: var(--primary-dark);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .nav-btn:hover {
            background-color: var(--button-hover);
            transform: translateY(-2px);
        }

        /* Search Section */
        .search-section {
            background-color: var(--bg-white);
            padding: 40px 5%;
            margin: 15px;
            border-radius: 12px;
            text-align: center;
        }

        .search-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--primary-dark);
        }

        .search-section h1 span {
            color: var(--accent-green);
            position: relative;
        }

        .search-section h1 span::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 8px;
            background-color: var(--accent-green);
            opacity: 0.3;
            z-index: -1;
        }

        .search-container {
            max-width: 800px;
            margin: 30px auto 0;
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-container input {
            width: 100%;
            padding: 15px 25px;
            border-radius: 50px;
            border: 1px solid rgba(0,0,0,0.1);
            font-size: 1rem;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
            flex-grow: 1;
        }

        .search-container input:focus {
            outline: none;
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(32, 159, 75, 0.2);
        }

        .search-btn {
            position: absolute;
            right: 140px;
            top: 50%;
            transform: translateY(-50%);
            padding: 10px 25px;
            border-radius: 50px;
            background: var(--accent-green);
            border: none;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-btn:hover {
            background: #228c4a;
            transform: translateY(-50%) scale(1.02);
        }

        .filter-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            padding: 10px 20px;
            border-radius: 50px;
            background: var(--primary-dark);
            border: none;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-btn:hover {
            background: var(--button-hover);
            transform: translateY(-50%) scale(1.02);
        }

        /* Filter Popup */
        .filter-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            background: var(--bg-white);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 1001;
            padding: 25px;
            overflow-y: auto;
        }

        .filter-popup.active {
            display: block;
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .filter-title {
            font-size: 1.5rem;
            color: var(--primary-dark);
            font-weight: 600;
        }

        .close-filter {
            font-size: 1.5rem;
            color: var(--text-primary);
            cursor: pointer;
            transition: color 0.2s;
        }

        .close-filter:hover {
            color: var(--text-hover);
        }

        .filter-sections {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        .filter-section {
            margin-bottom: 20px;
        }

        .filter-section h3 {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: var(--primary-dark);
            padding-bottom: 8px;
            border-bottom: 2px solid var(--accent-green);
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-option input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .filter-option label {
            cursor: pointer;
            font-size: 0.95rem;
        }

        .filter-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        .apply-filters {
            background: var(--accent-green);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .apply-filters:hover {
            background: #228c4a;
            transform: translateY(-2px);
        }

        .reset-filters {
            background: transparent;
            color: var(--primary-dark);
            border: 1px solid var(--primary-dark);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .reset-filters:hover {
            background: rgba(0,0,0,0.05);
        }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .overlay.active {
            display: block;
        }

        /* Main Content */
        .main-container {
            display: flex;
            flex-direction: column;
            padding: 0 15px;
            max-width: 1400px;
            margin: 20px auto 60px;
            flex: 1;
        }

        /* Job Grid */
        .job-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            padding: 0 15px;
        }

        .job-card {
            background: var(--bg-white);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: var(--card-shadow);
            border: var(--card-border);
            display: flex;
            flex-direction: column;
            min-height: 280px;
            position: relative;
            overflow: hidden;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
            border-color: var(--card-hover-border);
        }

        .job-card h3 {
            color: var(--primary-dark);
            margin-bottom: 8px;
            font-size: 1.25rem;
            line-height: 1.3;
            word-break: break-word;
        }

        .job-card .client-name {
            color: var(--text-primary);
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .job-card .badge-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .job-card .badge {
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .job-card .badge-category {
            background-color: #e9f5ff;
            color: #0066cc;
        }

        .job-card .badge-experience {
            background-color: #fff4e5;
            color: #ff6b00;
        }

        .job-card .salary {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent-green);
            margin: 10px 0 15px;
        }

        .job-card .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .job-card .status-open {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--status-open);
        }

        .job-card .status-completed {
            background-color: rgba(108, 117, 125, 0.2);
            color: var(--status-completed);
        }

        .job-card .status-inprogress {
            background-color: rgba(23, 162, 184, 0.2);
            color: var(--status-inprogress);
        }

        .job-card .status-cancelled {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--status-cancelled);
        }

        .job-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-primary);
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        .make-proposal-btn {
            margin-top: 15px;
            padding: 10px;
            border-radius: 6px;
            background: var(--primary-dark);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            text-align: center;
            font-size: 0.9rem;
        }

        .make-proposal-btn:hover {
            background: var(--button-hover);
            transform: translateY(-2px);
        }

        /* No Jobs Found */
        .no-jobs {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            background: var(--bg-white);
            border-radius: 12px;
        }

        .no-jobs h4 {
            color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .no-jobs p {
            color: var(--text-primary);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow: auto;
        }

        .modal-content {
            background-color: var(--bg-white);
            margin: 80px auto 20px;
            padding: 30px;
            border-radius: 12px;
            width: 50%;
            max-width: 600px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s;
        }

        .close:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(32, 159, 75, 0.2);
            outline: none;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn-submit {
            background-color: var(--primary-dark);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: var(--button-hover);
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .job-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 900px) {
            .job-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-sections {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0 20px;
                height: 60px;
            }
            
            .navbar-nav {
                gap: 15px;
            }
            
            .nav-btn {
                padding: 8px 15px;
            }
            
            .search-section h1 {
                font-size: 2rem;
            }
            
            .search-btn {
                right: 120px;
                padding: 10px 15px;
            }
            
            .filter-btn {
                padding: 10px 15px;
                right: 5px;
            }
            
            .modal-content {
                width: 90%;
                padding: 20px;
            }
        }

        @media (max-width: 600px) {
            .job-grid {
                grid-template-columns: 1fr;
            }
            
            .search-section {
                padding: 30px 5%;
            }
            
            .search-section h1 {
                font-size: 1.8rem;
            }
            
            .search-btn {
                right: 100px;
                padding: 8px 12px;
                font-size: 0.9rem;
            }
            
            .filter-btn {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
            
            .modal-content {
                width: 95%;
                margin: 40px auto 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a class="navbar-brand" href="../index.php">
                <img src="../Assets/logo2.png" alt="CodeBrains Logo">
            </a>
            <ul class="navbar-nav">
                <li><a class="nav-link" href="../index.php">Home</a></li>
                <li><a class="nav-link" href="#my-jobs">My Jobs</a></li>
                <li><a class="nav-link" href="#messages">Messages</a></li>
                <li><a class="nav-btn" href="#logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="search-section">
        <h1>Find Your <span>Perfect</span> Job Match</h1>
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Search jobs by title, skills, or keywords" value="<?= htmlspecialchars($filters['search']) ?>">
            <button class="search-btn" id="main-search-btn">
                <i class="fas fa-search"></i> Search
            </button>
            <button class="filter-btn" id="filter-toggle">
                <i class="fas fa-filter"></i> Filters
            </button>
        </div>
    </section>

    <!-- Filter Popup -->
    <div class="overlay" id="filter-overlay"></div>
    <div class="filter-popup" id="filter-popup">
        <div class="filter-header">
            <h2 class="filter-title">Filter Jobs</h2>
            <span class="close-filter" id="close-filter">&times;</span>
        </div>
        
        <div class="filter-sections">
            <div class="filter-section">
                <h3>Job Category</h3>
                <div class="filter-options">
                    <div class="filter-option">
                        <input type="checkbox" id="popup-app-dev" value="App Development" <?= in_array('App Development', $filters['categories']) ? 'checked' : '' ?>>
                        <label for="popup-app-dev">App Development</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-web-dev" value="Web Development" <?= in_array('Web Development', $filters['categories']) ? 'checked' : '' ?>>
                        <label for="popup-web-dev">Web Development</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-section">
                <h3>Budget Range ($)</h3>
                <div class="filter-options">
                    <div class="filter-option">
                        <input type="checkbox" id="popup-budget1" value="1-500" <?= in_array('1-500', $filters['budget']) ? 'checked' : '' ?>>
                        <label for="popup-budget1">$1 - $500</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-budget2" value="500-1000" <?= in_array('500-1000', $filters['budget']) ? 'checked' : '' ?>>
                        <label for="popup-budget2">$500 - $1,000</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-budget3" value="1000-5000" <?= in_array('1000-5000', $filters['budget']) ? 'checked' : '' ?>>
                        <label for="popup-budget3">$1,000 - $5,000</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-budget4" value="5000-10000" <?= in_array('5000-10000', $filters['budget']) ? 'checked' : '' ?>>
                        <label for="popup-budget4">$5,000 and above</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-section">
                <h3>Skills</h3>
                <div class="filter-options">
                    <div class="filter-option">
                        <input type="checkbox" id="popup-skill-java" value="Java" <?= in_array('Java', $filters['skills']) ? 'checked' : '' ?>>
                        <label for="popup-skill-java">Java</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-skill-kotlin" value="Kotlin" <?= in_array('Kotlin', $filters['skills']) ? 'checked' : '' ?>>
                        <label for="popup-skill-kotlin">Kotlin</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-skill-swift" value="Swift" <?= in_array('Swift', $filters['skills']) ? 'checked' : '' ?>>
                        <label for="popup-skill-swift">Swift</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-skill-react" value="React Native" <?= in_array('React Native', $filters['skills']) ? 'checked' : '' ?>>
                        <label for="popup-skill-react">React Native</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-skill-flutter" value="Flutter" <?= in_array('Flutter', $filters['skills']) ? 'checked' : '' ?>>
                        <label for="popup-skill-flutter">Flutter</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-skill-js" value="JavaScript" <?= in_array('JavaScript', $filters['skills']) ? 'checked' : '' ?>>
                        <label for="popup-skill-js">JavaScript</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-section">
                <h3>Experience Level</h3>
                <div class="filter-options">
                    <div class="filter-option">
                        <input type="checkbox" id="popup-exp-entry" value="entry" <?= in_array('entry', $filters['experience']) ? 'checked' : '' ?>>
                        <label for="popup-exp-entry">Entry Level</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-exp-intermediate" value="intermediate" <?= in_array('intermediate', $filters['experience']) ? 'checked' : '' ?>>
                        <label for="popup-exp-intermediate">Intermediate</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="popup-exp-expert" value="expert" <?= in_array('expert', $filters['experience']) ? 'checked' : '' ?>>
                        <label for="popup-exp-expert">Expert</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="filter-actions">
            <button type="button" class="reset-filters" id="reset-filters">Reset Filters</button>
            <button type="button" class="apply-filters" id="apply-filters">Apply Filters</button>
        </div>
    </div>

    <div class="main-container">
        <section class="job-grid" id="job-container">
            <?php if (count($jobs) > 0): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="job-card">
                        <span class="status-badge status-<?= strtolower($job['status']) ?>"><?= htmlspecialchars($job['status']) ?></span>
                        <h3><?= htmlspecialchars($job['title']) ?></h3>
                        <p class="client-name"><?= htmlspecialchars($job['username']) ?></p>
                        <div class="badge-container">
                            <span class="badge badge-category"><?= htmlspecialchars($job['category']) ?></span>
                            <span class="badge badge-experience"><?= htmlspecialchars($job['experience']) ?></span>
                        </div>
                        <p class="salary">$<?= number_format($job['budget'], 2) ?></p>
                        <div class="job-meta">
                            <span><?= htmlspecialchars($job['posted_ago']) ?></span>
                            <span>Deadline: <?= htmlspecialchars($job['deadline']) ?></span>
                        </div>
                        <button class="make-proposal-btn" data-job-id="<?= $job['id'] ?>">
                            Make Proposal
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-jobs">
                    <h4>No jobs found matching your criteria</h4>
                    <p>Try adjusting your filters or search terms</p>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <!-- Proposal Modal -->
    <div id="proposal-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Submit Proposal</h2>
                <span class="close">&times;</span>
            </div>
            
            <form id="proposal-form" method="POST">
                <input type="hidden" id="job-id" name="job-id">
                <div class="form-group">
                    <label for="bid-amount">Bid Amount ($)</label>
                    <input type="number" id="bid-amount" name="bid-amount" required min="1" step="0.01">
                </div>
                <div class="form-group">
                    <label for="proposal-text">Proposal Text</label>
                    <textarea id="proposal-text" name="proposal-text" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="completion-time">Estimated Completion Time</label>
                    <select id="completion-time" name="completion-time" required>
                        <option value="1">1 Week</option>
                        <option value="2">2 Weeks</option>
                        <option value="3">3 Weeks</option>
                        <option value="4">4 Weeks</option>
                        <option value="5">1 Month</option>
                        <option value="6">2 Months</option>
                        <option value="7">3 Months</option>
                        <option value="8">6 Months</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Submit Proposal</button>
            </form>
        </div>
    </div>

    <?php include('../PHPUIFiles/Footer.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize filter popup
            const filterToggle = document.getElementById('filter-toggle');
            const filterPopup = document.getElementById('filter-popup');
            const filterOverlay = document.getElementById('filter-overlay');
            const closeFilter = document.getElementById('close-filter');
            const applyFilters = document.getElementById('apply-filters');
            const resetFilters = document.getElementById('reset-filters');
            
            // Toggle filter popup
            filterToggle.addEventListener('click', () => {
                filterPopup.classList.add('active');
                filterOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            
            // Close filter popup
            closeFilter.addEventListener('click', () => {
                filterPopup.classList.remove('active');
                filterOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
            
            filterOverlay.addEventListener('click', () => {
                filterPopup.classList.remove('active');
                filterOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
            
            // Apply filters
            applyFilters.addEventListener('click', () => {
                const filters = {
                    search: document.getElementById('search-input').value,
                    categories: Array.from(document.querySelectorAll('#filter-popup input[type="checkbox"]:checked'))
                        .filter(cb => ['App Development', 'Web Development'].includes(cb.value))
                        .map(cb => cb.value),
                    skills: Array.from(document.querySelectorAll('#filter-popup input[type="checkbox"]:checked'))
                        .filter(cb => ['Java', 'Kotlin', 'Swift', 'React Native', 'Flutter', 'JavaScript'].includes(cb.value))
                        .map(cb => cb.value),
                    experience: Array.from(document.querySelectorAll('#filter-popup input[type="checkbox"]:checked'))
                        .filter(cb => ['entry', 'intermediate', 'expert'].includes(cb.value))
                        .map(cb => cb.value),
                    budget: Array.from(document.querySelectorAll('#filter-popup input[type="checkbox"]:checked'))
                        .filter(cb => ['1-500', '500-1000', '1000-5000', '5000-10000'].includes(cb.value))
                        .map(cb => cb.value)
                };
                
                // Convert filters to URLSearchParams
                const params = new URLSearchParams();
                params.append('search', filters.search);
                filters.categories.forEach(c => params.append('categories[]', c));
                filters.skills.forEach(s => params.append('skills[]', s));
                filters.experience.forEach(e => params.append('experience[]', e));
                filters.budget.forEach(b => params.append('budget[]', b));
                
                // Close popup
                filterPopup.classList.remove('active');
                filterOverlay.classList.remove('active');
                document.body.style.overflow = '';
                
                // Reload with new filters
                window.location.search = params.toString();
            });
            
            // Reset filters
            resetFilters.addEventListener('click', () => {
                document.querySelectorAll('#filter-popup input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
            
            // Initialize proposal modal
            const proposalModal = document.getElementById('proposal-modal');
            const closeModal = document.querySelector('.close');
            const proposalForm = document.getElementById('proposal-form');
            
            // Close modal when clicking outside content
            window.addEventListener('click', (e) => {
                if (e.target === proposalModal) {
                    proposalModal.style.display = 'none';
                }
            });
            
            // Close modal with close button
            closeModal.addEventListener('click', () => {
                proposalModal.style.display = 'none';
            });
            
            // Form submission
            proposalForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                // Show loading state
                const submitBtn = proposalForm.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                
                try {
                    const formData = new FormData(proposalForm);
                    
                    const response = await fetch('submit_proposal.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // Show success message
                        alert('Proposal submitted successfully!');
                        proposalModal.style.display = 'none';
                        proposalForm.reset();
                        
                        // Optionally refresh the job listings
                        loadAllJobs();
                    } else {
                        alert('Error submitting proposal: ' + (result.error || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the proposal.');
                } finally {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
            });
            
            // Attach proposal button listeners
            document.querySelectorAll('.make-proposal-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const jobId = button.getAttribute('data-job-id');
                    document.getElementById('job-id').value = jobId;
                    proposalModal.style.display = 'block';
                });
            });
            
            // Initialize search functionality
            const searchInput = document.getElementById('search-input');
            const searchBtn = document.getElementById('main-search-btn');
            
            const loadAllJobs = debounce(() => {
                const searchTerm = searchInput.value.trim();
                const params = new URLSearchParams(window.location.search);
                
                if (searchTerm) {
                    params.set('search', searchTerm);
                } else {
                    params.delete('search');
                }
                
                window.location.search = params.toString();
            }, 300);
            
            searchBtn.addEventListener('click', loadAllJobs);
            searchInput.addEventListener('input', loadAllJobs);
            
            function debounce(func, timeout = 300) {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(this, args), timeout);
                };
            }
        });
    </script>
</body>
</html>
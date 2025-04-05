<?php
session_start();

$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

$job_id = $_GET['id'] ?? 0;
$username = $_SESSION["username"] ?? '';
$user_type = $_SESSION["user_type"] ?? '';
$user_id = $_SESSION["user_id"] ?? 0;

// Initialize error/success messages
$error = '';
$success = '';

// Handle all POST requests first
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle job deletion
        if (isset($_POST['delete_job'])) {
            // Delete job and related data
            $db->exec('BEGIN TRANSACTION');
            
            // Delete job
            $stmt = $db->prepare("DELETE FROM jobs WHERE id = :id AND username = :username");
            $stmt->bindValue(':id', $_GET['id'], SQLITE3_INTEGER);
            $stmt->bindValue(':username', $_SESSION["username"], SQLITE3_TEXT);
            $stmt->execute();
            
            // Delete proposals
            $stmt = $db->prepare("DELETE FROM proposals WHERE job_id = :job_id");
            $stmt->bindValue(':job_id', $_GET['id'], SQLITE3_INTEGER);
            $stmt->execute();
            
            // Delete files
            $job_dir = 'uploads/jobs/'.(int)$_GET['id'];
            if (is_dir($job_dir)) {
                array_map('unlink', glob("$job_dir/*"));
                rmdir($job_dir);
            }
            
            $db->exec('COMMIT');
            header("Location: jobs_list.php");
            exit;
        }

        // Handle file uploads
        if (isset($_POST['upload_files'])) {
            $job_id = (int)$_GET['id'];
            $upload_dir = "uploads/jobs/$job_id/";
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $error = 'Failed to create upload directory';
                }
            }
            
            if (empty($error)) {
                $allowed_types = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];
                $new_files = [];
                $errors = [];
                
                foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
                    // Skip empty inputs
                    if ($_FILES['attachments']['error'][$key] !== UPLOAD_ERR_OK) {
                        $errors[] = "File " . ($key + 1) . ": Upload error code " . $_FILES['attachments']['error'][$key];
                        continue;
                    }
                    
                    $file_name = basename($_FILES['attachments']['name'][$key]);
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $file_size = $_FILES['attachments']['size'][$key];
                    
                    // Validate file
                    if (!in_array($file_ext, $allowed_types)) {
                        $errors[] = "$file_name: Invalid file type";
                        continue;
                    }
                    
                    if ($file_size > 5000000) {
                        $errors[] = "$file_name: File too large (max 5MB)";
                        continue;
                    }
                    
                    // Generate unique name
                    $new_name = uniqid() . '.' . $file_ext;
                    $dest = $upload_dir . $new_name;
                    
                    if (move_uploaded_file($tmp_name, $dest)) {
                        $new_files[] = $dest;
                    } else {
                        $errors[] = "$file_name: Upload failed";
                    }
                }
                
                // Handle results
                if (!empty($new_files)) {
                    // Get existing attachments
                    $stmt = $db->prepare("SELECT attachments FROM jobs WHERE id = :id");
                    $stmt->bindValue(':id', $job_id, SQLITE3_INTEGER);
                    $result = $stmt->execute();
                    $existing = $result->fetchArray(SQLITE3_ASSOC)['attachments'] ?? '';
                    
                    // Merge with new files
                    $all_files = array_filter(explode(',', $existing));
                    $all_files = array_merge($all_files, $new_files);
                    
                    // Update database
                    $stmt = $db->prepare("UPDATE jobs SET attachments = :attachments WHERE id = :id");
                    $stmt->bindValue(':attachments', implode(',', $all_files), SQLITE3_TEXT);
                    $stmt->bindValue(':id', $job_id, SQLITE3_INTEGER);
                    
                    if ($stmt->execute()) {
                        $success = count($new_files) . ' file(s) uploaded successfully';
                        if (!empty($errors)) {
                            $error = 'Some files failed: ' . implode(', ', $errors);
                        }
                    } else {
                        $error = 'Database update failed';
                    }
                } elseif (!empty($errors)) {
                    $error = 'Upload failed: ' . implode(', ', $errors);
                } else {
                    $error = 'No valid files uploaded';
                }
            }
        }
        
        // Handle file deletion
        if (isset($_POST['delete_file'])) {
            $file_path = $_POST['file_path'];
            $job_id = (int)$_GET['id'];
            
            if (file_exists($file_path)) {
                unlink($file_path);
                
                // Update database
                $stmt = $db->prepare("SELECT attachments FROM jobs WHERE id = :id");
                $stmt->bindValue(':id', $job_id, SQLITE3_INTEGER);
                $result = $stmt->execute();
                $attachments = $result->fetchArray(SQLITE3_ASSOC)['attachments'];
                
                $new_attachments = array_filter(explode(',', $attachments), function($f) use ($file_path) {
                    return $f !== $file_path;
                });
                
                $stmt = $db->prepare("UPDATE jobs SET attachments = :attachments WHERE id = :id");
                $stmt->bindValue(':attachments', implode(',', $new_attachments), SQLITE3_TEXT);
                $stmt->bindValue(':id', $job_id, SQLITE3_INTEGER);
                $stmt->execute();
                
                $success = 'File deleted successfully';
            }
        }

        // Handle work submission approval/rejection
        if (isset($_POST['approve_work']) || isset($_POST['reject_work'])) {
            $submission_id = $_POST['submission_id'];
            $db->exec('BEGIN TRANSACTION');
            
            try {
                if (isset($_POST['approve_work'])) {
                    // Approve the work
                    $stmt = $db->prepare("UPDATE work_submissions SET status = 'Approved', completion_date = :completion_date WHERE id = :submission_id");
                    $stmt->bindValue(':completion_date', date('Y-m-d H:i:s'));
                    $stmt->bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
                    $stmt->execute();
                    
                    // Get submission details for notification
                    $submission = $db->prepare("SELECT freelancer_id, job_id FROM work_submissions WHERE id = :submission_id");
                    $submission->bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
                    $result = $submission->execute()->fetchArray(SQLITE3_ASSOC);
                    $freelancer_id = $result['freelancer_id'];
                    $job_id = $result['job_id'];
                    
                    // Create notification for freelancer
                    $notif_stmt = $db->prepare("INSERT INTO freelancer_notifications (freelancer_id, job_id, client_id, message) VALUES (:freelancer_id, :job_id, :client_id, :message)");
                    $notif_stmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
                    $notif_stmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
                    $notif_stmt->bindValue(':client_id', $user_id, SQLITE3_INTEGER);
                    $notif_stmt->bindValue(':message', 'Your work submission has been approved!', SQLITE3_TEXT);
                    $notif_stmt->execute();
                    
                    // Update order status if all submissions are approved
                    $submission = $db->prepare("SELECT order_id FROM work_submissions WHERE id = :submission_id");
                    $submission->bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
                    $result = $submission->execute()->fetchArray(SQLITE3_ASSOC);
                    $order_id = $result['order_id'];
                    
                    $checkSubmissions = $db->prepare("SELECT COUNT(*) as pending FROM work_submissions WHERE order_id = :order_id AND status != 'Approved'");
                    $checkSubmissions->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
                    $result = $checkSubmissions->execute()->fetchArray(SQLITE3_ASSOC);
                    
                    if ($result['pending'] == 0) {
                        $db->exec("UPDATE orders SET status = 'Completed' WHERE id = $order_id");
                        $db->exec("UPDATE jobs SET status = 'Completed' WHERE id = $job_id");
                        
                        // Create notification for freelancer about job completion
                        $notif_stmt = $db->prepare("INSERT INTO freelancer_notifications (freelancer_id, job_id, client_id, message) VALUES (:freelancer_id, :job_id, :client_id, :message)");
                        $notif_stmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
                        $notif_stmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
                        $notif_stmt->bindValue(':client_id', $user_id, SQLITE3_INTEGER);
                        $notif_stmt->bindValue(':message', 'The job has been marked as completed!', SQLITE3_TEXT);
                        $notif_stmt->execute();
                    }
                    
                    $success = "Work approved successfully!";
                } else {
                    // Reject the work with feedback
                    $feedback = $_POST['rejection_feedback'] ?? '';
                    $stmt = $db->prepare("UPDATE work_submissions SET status = 'Revision Requested', feedback = :feedback WHERE id = :submission_id");
                    $stmt->bindValue(':feedback', $feedback, SQLITE3_TEXT);
                    $stmt->bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
                    $stmt->execute();
                    
                    // Get submission details for notification
                    $submission = $db->prepare("SELECT freelancer_id, job_id FROM work_submissions WHERE id = :submission_id");
                    $submission->bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
                    $result = $submission->execute()->fetchArray(SQLITE3_ASSOC);
                    
                    // Create notification for freelancer
                    $notif_stmt = $db->prepare("INSERT INTO freelancer_notifications (freelancer_id, job_id, client_id, message) VALUES (:freelancer_id, :job_id, :client_id, :message)");
                    $notif_stmt->bindValue(':freelancer_id', $result['freelancer_id'], SQLITE3_INTEGER);
                    $notif_stmt->bindValue(':job_id', $result['job_id'], SQLITE3_INTEGER);
                    $notif_stmt->bindValue(':client_id', $user_id, SQLITE3_INTEGER);
                    $notif_stmt->bindValue(':message', 'Your work submission requires revision: ' . $feedback, SQLITE3_TEXT);
                    $notif_stmt->execute();
                    
                    $success = "Work rejected and revision requested. The freelancer has been notified.";
                }
                
                $db->exec('COMMIT');
            } catch (Exception $e) {
                $db->exec('ROLLBACK');
                throw $e;
            }
        }

        // Handle rating submission
        if (isset($_POST['submit_rating'])) {
            $freelancer_id = $_POST['freelancer_id'];
            $rating = $_POST['rating'];
            $review = $_POST['review'];
            
            // Insert rating into database
            $stmt = $db->prepare("INSERT INTO ratings (job_id, freelancer_id, client_id, rating, review) 
                                 VALUES (:job_id, :freelancer_id, :client_id, :rating, :review)");
            $stmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
            $stmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
            $stmt->bindValue(':client_id', $user_id, SQLITE3_INTEGER);
            $stmt->bindValue(':rating', $rating, SQLITE3_INTEGER);
            $stmt->bindValue(':review', $review, SQLITE3_TEXT);
            
            if ($stmt->execute()) {
                $success = "Rating submitted successfully!";
            } else {
                $error = "Failed to submit rating. Please try again.";
            }
        }

        // Handle close and reopen job actions
        if (isset($_POST['close_job'])) {
            $updateQuery = $db->prepare("UPDATE jobs SET status = 'Closed' WHERE id = :id");
            $updateQuery->bindValue(':id', $job_id, SQLITE3_INTEGER);
            $updateQuery->execute();
            header("Location: job_details.php?id=$job_id");
            exit;
        }
        if (isset($_POST['reopen_job'])) {
            $updateQuery = $db->prepare("UPDATE jobs SET status = 'Open' WHERE id = :id");
            $updateQuery->bindValue(':id', $job_id, SQLITE3_INTEGER);
            $updateQuery->execute();
            header("Location: job_details.php?id=$job_id");
            exit;
        }

    } catch (Exception $e) {
        $db->exec('ROLLBACK');
        $error = 'Operation failed: '.$e->getMessage();
    }
}

// Fetch job details
$jobQuery = $db->prepare("SELECT * FROM jobs WHERE id = :id AND username = :username");
$jobQuery->bindValue(':id', $job_id, SQLITE3_INTEGER);
$jobQuery->bindValue(':username', $username, SQLITE3_TEXT);
$jobResult = $jobQuery->execute();
$job = $jobResult->fetchArray(SQLITE3_ASSOC);

// Fetch proposals count
$proposalsQuery = $db->prepare("SELECT COUNT(*) as count FROM proposals WHERE job_id = :job_id");
$proposalsQuery->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
$countResult = $proposalsQuery->execute()->fetchArray(SQLITE3_ASSOC);
$proposalsCount = $countResult['count'] ?? 0;

// Fetch proposals data
$proposalsDataQuery = $db->prepare("
    SELECT 
        proposals.id AS proposal_id,
        proposals.job_id,
        proposals.bid_amount,
        proposals.proposal_text,
        proposals.status AS status,
        freelancers.id AS freelancer_id,
        freelancers.name AS freelancer_name,
        freelancers.username AS freelancer_username,
        freelancers.email AS freelancer_email,
        freelancers.contact AS freelancer_contact,
        freelancers.gender AS freelancer_gender,
        freelancers.dob AS freelancer_dob,
        freelancers.skills AS freelancer_skills,
        freelancers.tools AS freelancer_tools,
        freelancers.tagline AS freelancer_tagline,
        freelancers.about_me AS freelancer_about_me,
        freelancers.experience AS freelancer_experience,
        freelancers.languages AS freelancer_languages,
        freelancers.availability AS freelancer_availability,
        freelancers.degree AS freelancer_degree,
        freelancers.institute AS freelancer_institute,
        freelancers.graduation_year AS freelancer_graduation_year,
        freelancers.profile_picture AS freelancer_profile_picture,
        freelancers.resume AS freelancer_resume
    FROM proposals 
    INNER JOIN freelancers ON proposals.freelancer_id = freelancers.id 
    WHERE proposals.job_id = :job_id
");
$proposalsDataQuery->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
$proposalsResult = $proposalsDataQuery->execute();

// Fetch hired freelancers
$hiredFreelancersQuery = $db->prepare("
    SELECT 
        orders.id as order_id,
        freelancers.id AS freelancer_id,
        freelancers.name AS freelancer_name,
        freelancers.profile_picture AS freelancer_profile_picture,
        freelancers.skills AS freelancer_skills,
        orders.status as order_status
    FROM orders
    INNER JOIN freelancers ON orders.freelancer_id = freelancers.id
    WHERE orders.job_id = :job_id
");
$hiredFreelancersQuery->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
$hiredFreelancersResult = $hiredFreelancersQuery->execute();

$hiredFreelancers = [];
while ($row = $hiredFreelancersResult->fetchArray(SQLITE3_ASSOC)) {
    $hiredFreelancers[] = $row;
}

// Fetch work submissions for this job
$submissionsQuery = $db->prepare("
    SELECT 
        work_submissions.*,
        freelancers.name AS freelancer_name,
        freelancers.profile_picture AS freelancer_profile_picture
    FROM work_submissions
    INNER JOIN freelancers ON work_submissions.freelancer_id = freelancers.id
    WHERE work_submissions.job_id = :job_id
    ORDER BY work_submissions.submission_date DESC
");
$submissionsQuery->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
$submissionsResult = $submissionsQuery->execute();

$submissions = [];
while ($row = $submissionsResult->fetchArray(SQLITE3_ASSOC)) {
    $submissions[] = $row;
}

function get_file_icon($extension) {
    $icons = [
        'pdf' => 'pdf',
        'doc' => 'word',
        'docx' => 'word',
        'xls' => 'excel',
        'xlsx' => 'excel',
        'ppt' => 'powerpoint',
        'pptx' => 'powerpoint',
        'jpg' => 'image',
        'jpeg' => 'image',
        'png' => 'image',
        'zip' => 'archive',
        'txt' => 'alt',
    ];
    return $icons[strtolower($extension)] ?? 'file';
}

function format_size($bytes) {
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($job['job_title']) ?> - Job Details</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../CSS files/job-details.css">
    <style>
        .submission-card {
            border-left: 4px solid #0d6efd;
            margin-bottom: 20px;
        }
        .submission-card.revision {
            border-left-color: #fd7e14;
        }
        .submission-card.approved {
            border-left-color: #28a745;
        }
        .file-badge {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .badge-submitted {
            background-color: #0d6efd;
            color: white;
        }
        .badge-revision {
            background-color: #fd7e14;
            color: white;
        }
        .badge-approved {
            background-color: #28a745;
            color: white;
        }
        .timeline {
            border-left: 3px solid #dee2e6;
            margin-left: 20px;
            padding-left: 30px;
        }
        .timeline-event {
            position: relative;
            margin-bottom: 30px;
        }
        .timeline-badge {
            position: absolute;
            left: -35px;
            top: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .rating-stars {
            font-size: 24px;
            color: #ffc107;
            cursor: pointer;
        }
        .rating-stars .fas {
            color: #ffc107;
        }
        .rating-stars .far {
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container-lg py-4">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <!-- Job Header -->
        <div class="job-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <h1 class="h2 fw-bold mb-2"><?= htmlspecialchars($job['job_title']) ?></h1>
                <div class="d-flex align-items-center gap-2">
                    <span class="status-badge bg-<?= $job['status'] === 'Open' ? 'success' : ($job['status'] === 'Completed' ? 'primary' : 'secondary') ?>">
                        <?= htmlspecialchars($job['status']) ?>
                    </span>
                    <span class="text-muted">•</span>
                </div>
            </div>
            <div class="job-status-controls d-flex gap-2">
                <form method="POST">
                    <?php if($job['status'] === 'Open'): ?>
                        <button type="submit" name="close_job" class="btn btn-danger px-4 py-2">
                            <i class="fas fa-lock me-2"></i>Close Job
                        </button>
                    <?php elseif($job['status'] === 'Closed' && $job['status'] != 'Completed'): ?>
                        <button type="submit" name="reopen_job" class="btn btn-success px-4 py-2">
                            <i class="fas fa-lock-open me-2"></i>Reopen Job
                        </button>
                    <?php endif; ?>
                </form>
                <?php if($job['status'] != 'Completed'): ?>
                    <form method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this job? This cannot be undone!')">
                        <button type="submit" name="delete_job" class="btn btn-outline-danger px-4 py-2">
                            <i class="fas fa-trash me-2"></i>Delete Job
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="jobTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" 
                        data-bs-target="#details" type="button" role="tab" 
                        aria-controls="details" aria-selected="true">
                    <i class="fas fa-info-circle me-2"></i>Details
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="proposals-tab" data-bs-toggle="tab" 
                        data-bs-target="#proposals" type="button" role="tab" 
                        aria-controls="proposals" aria-selected="false">
                    <i class="fas fa-comments me-2"></i>Proposals (<?= $proposalsCount ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="files-tab" data-bs-toggle="tab" 
                        data-bs-target="#files" type="button" role="tab" 
                        aria-controls="files" aria-selected="false">
                    <i class="fas fa-file-alt me-2"></i>Files
                </button>
            </li>
            <?php if (!empty($hiredFreelancers) || !empty($submissions)): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="submissions-tab" data-bs-toggle="tab" 
                            data-bs-target="#submissions" type="button" role="tab" 
                            aria-controls="submissions" aria-selected="false">
                        <i class="fas fa-tasks me-2"></i>Submissions
                    </button>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content mt-4">
            <!-- Details Tab -->
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-3 fw-semibold">Project Overview</h4>
                                <div class="mb-4">
                                    <h5 class="fw-semibold mb-3">Description</h5>
                                    <p class="text-muted line-height-lg"><?= nl2br(htmlspecialchars($job['job_description'])) ?></p>
                                </div>
                                <h5 class="fw-semibold mb-3">Required Skills</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach (explode(',', $job['primary_skill']) as $skill): ?>
                                        <span class="skill-badge"><?= trim($skill) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-4">Project Timeline</h5>
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="timeline-badge">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-medium">Posted Date</p>
                                            <small class="text-muted"><?= date('M d, Y', strtotime($job['posted_date'])) ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="timeline-badge bg-success">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-medium">Deadline</p>
                                            <small class="text-muted"><?= date('M d, Y', strtotime($job['deadline'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-medium">Budget:</span>
                                    <span class="h5 text-primary mb-0">$<?= number_format($job['budget'], 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-medium">Bids Received:</span>
                                    <span class="badge bg-primary-subtle text-primary fs-6"> <?php echo $proposalsCount?> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hired Freelancers Section -->
                <?php if (!empty($hiredFreelancers)): ?>
                    <div class="hired-freelancers mt-5">
                        <h5 class="fw-semibold mb-4">Hired Freelancers</h5>
                        <div class="row g-4">
                            <?php foreach ($hiredFreelancers as $freelancer): ?>
                                <div class="col-12 col-md-6">
                                    <div class="freelancer-card">
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Freelancer Profile Picture -->
                                            <div class="flex-shrink-0">
                                                <?php if (!empty($freelancer['freelancer_profile_picture'])): ?>
                                                    <img src="<?= htmlspecialchars($freelancer['freelancer_profile_picture']) ?>" 
                                                         class="freelancer-avatar" alt="Freelancer">
                                                <?php else: ?>
                                                    <div class="freelancer-avatar bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-user text-primary fs-4"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <!-- Freelancer Details -->
                                            <div class="flex-grow-1">
                                                <h6><?= htmlspecialchars($freelancer['freelancer_name']) ?></h6>
                                                <div class="d-flex flex-wrap gap-1 mb-2">
                                                    <?php foreach (explode(',', $freelancer['freelancer_skills']) as $skill): ?>
                                                        <span class="badge bg-light text-dark"><?= trim($skill) ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="messages.php?freelancer_id=<?= $freelancer['freelancer_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-comment-dots me-2"></i>Message
                                                    </a>
                                                    <?php if ($freelancer['order_status'] != 'Completed'): ?>
                                                        <a href="#" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#fireFreelancerModal" 
                                                           data-freelancer-id="<?= $freelancer['freelancer_id'] ?>" data-order-id="<?= $freelancer['order_id'] ?>">
                                                            <i class="fas fa-user-slash me-2"></i>Remove
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Proposals Tab -->
            <div class="tab-pane fade" id="proposals" role="tabpanel" aria-labelledby="proposals-tab">
                <?php if ($proposalsCount == 0): ?>
                    <div class="alert alert-info border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fs-5"></i>
                            <span class="fw-medium">No proposals received yet</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php while ($proposal = $proposalsResult->fetchArray(SQLITE3_ASSOC)): ?>
                            <div class="col-12">
                                <div class="proposal-card">
                                    <div class="d-flex align-items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <?php if (!empty($proposal['freelancer_profile_picture'])): ?>
                                                <a href="#" class="view-profile" data-freelancer-id="<?= $proposal['freelancer_id'] ?>">
                                                    <img src="<?= htmlspecialchars($proposal['freelancer_profile_picture']) ?>" class="freelancer-avatar rounded-circle" alt="Freelancer">
                                                </a>
                                            <?php else: ?>
                                                <a href="#" class="view-profile" data-freelancer-id="<?= $proposal['freelancer_id'] ?>">
                                                    <div class="freelancer-avatar rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-user text-primary fs-4"></i>
                                                    </div>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="mb-1 fw-semibold"><?= htmlspecialchars($proposal['freelancer_name']) ?></h5>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="text-muted fs-sm">
                                                            <?= htmlspecialchars($proposal['freelancer_email']) ?>
                                                        </span>
                                                        <span class="text-muted">•</span>
                                                        <span class="freelancer-rating">
                                                            <i class="fas fa-star"></i>4.9 (128 reviews)
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="bid-price">$<?= number_format($proposal['bid_amount'], 2) ?></div>
                                                    <small class="text-muted">Fixed Price</small>
                                                </div>
                                            </div>
                                            <?php if (!empty($proposal['proposal_text'])): ?>
                                                <div class="mt-3">
                                                    <p class="proposal-excerpt mb-0">
                                                        <?= nl2br(htmlspecialchars($proposal['proposal_text'])) ?>
                                                    </p>
                                                    <a href="#" class="read-more">Read more</a>
                                                </div>
                                            <?php endif; ?>
                                            <div class="mt-3 d-flex gap-2 border-top pt-3">
                                                <a href="messages.php?freelancer_id=<?= $proposal['freelancer_id'] ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-comment-dots me-2"></i>Message
                                                </a>
                                                <?php if ($job['status'] === 'Open'): ?>
                                                    <?php if ($proposal['status'] !== 'Accepted'): ?>
                                                        <form action="stripe/create-checkout-session.php" method="POST">
                                                            <input type="hidden" name="job_id" value="<?= $job_id ?>">
                                                            <input type="hidden" name="proposal_id" value="<?= $proposal['proposal_id'] ?>">
                                                            <input type="hidden" name="freelancer_id" value="<?= $proposal['freelancer_id'] ?>">
                                                            <input type="hidden" name="bid_amount" value="<?= $proposal['bid_amount'] ?>">
                                                            <button type="submit" class="btn btn-primary">Hire Now</button>
                                                        </form>
                                                    <?php else: ?>
                                                        <button class="btn btn-success" disabled>Hired</button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Files Tab -->
            <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-semibold mb-0">Project Attachments</h5>
                            <form method="POST" enctype="multipart/form-data" class="upload-btn position-relative">
                                <input type="file" class="form-control" name="attachments[]" multiple 
                                       accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                                <button type="submit" class="btn btn-primary px-4" name="upload_files">
                                    <i class="fas fa-upload me-2"></i>Upload Files
                                </button>
                            </form>
                        </div>
                        
                        <div class="row g-3">
                            <?php foreach (array_filter(explode(',', $job['attachments'])) as $file): ?>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="file-card">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                                                <i class="fas fa-file-<?= get_file_icon(pathinfo($file, PATHINFO_EXTENSION)) ?> text-primary fs-3"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-medium text-truncate"><?= basename($file) ?></div>
                                                <small class="text-muted"><?= format_size(filesize($file)) ?></small>
                                            </div>
                                            <div class="btn-group">
                                                <a href="download.php?file=<?= urlencode($file) ?>&job=<?= $job_id ?>" 
                                                   class="btn btn-sm btn-link text-decoration-none" 
                                                   download>
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <form method="POST">
                                                    <input type="hidden" name="file_path" value="<?= $file ?>">
                                                    <button type="submit" name="delete_file" 
                                                            class="btn btn-sm btn-link text-danger"
                                                            onclick="return confirm('Delete this file permanently?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submissions Tab -->
            <?php if (!empty($hiredFreelancers) || !empty($submissions)): ?>
                <div class="tab-pane fade" id="submissions" role="tabpanel" aria-labelledby="submissions-tab">
                    <div class="row g-4">
                        <?php if (empty($submissions)): ?>
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No work has been submitted yet for this job.
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($submissions as $submission): ?>
                                <div class="col-12">
                                    <div class="card submission-card <?= $submission['status'] == 'Revision Requested' ? 'revision' : 
                                                                     ($submission['status'] == 'Approved' ? 'approved' : '') ?>">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="flex-shrink-0">
                                                    <?php if (!empty($submission['freelancer_profile_picture'])): ?>
                                                        <img src="<?= htmlspecialchars($submission['freelancer_profile_picture']) ?>" 
                                                             class="freelancer-avatar rounded-circle" alt="Freelancer">
                                                    <?php else: ?>
                                                        <div class="freelancer-avatar rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-user text-primary fs-4"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <h5 class="mb-1"><?= htmlspecialchars($submission['freelancer_name']) ?></h5>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="badge bg-<?= 
                                                                    $submission['status'] == 'Submitted' ? 'primary' : 
                                                                    ($submission['status'] == 'Revision Requested' ? 'warning' : 
                                                                    ($submission['status'] == 'Approved' ? 'success' : 'secondary')) ?>">
                                                                    <?= $submission['status'] ?>
                                                                </span>
                                                                <span class="text-muted fs-sm">
                                                                    Submitted on <?= date('M j, Y', strtotime($submission['submission_date'])) ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="text-muted fs-sm">Submission #<?= $submission['id'] ?></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if (!empty($submission['description'])): ?>
                                                        <div class="mb-3">
                                                            <h6 class="fw-semibold mb-2">Description</h6>
                                                            <p><?= nl2br(htmlspecialchars($submission['description'])) ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($submission['files_path'])): ?>
                                                        <div class="mb-3">
                                                            <h6 class="fw-semibold mb-2">Submitted Files</h6>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                <?php foreach (explode(',', $submission['files_path']) as $file): ?>
                                                                    <a href="<?= htmlspecialchars($file) ?>" class="btn btn-sm btn-outline-primary" download>
                                                                        <i class="fas fa-file-<?= get_file_icon(pathinfo($file, PATHINFO_EXTENSION)) ?> me-2"></i>
                                                                        <?= basename($file) ?>
                                                                    </a>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($submission['feedback'] && $submission['status'] == 'Revision Requested'): ?>
                                                        <div class="alert alert-warning mb-3">
                                                            <h6 class="fw-semibold mb-2">Revision Requested</h6>
                                                            <p><?= nl2br(htmlspecialchars($submission['feedback'])) ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Submission Timeline -->
                                                    <div class="timeline mt-4">
                                                        <div class="timeline-event">
                                                            <div class="timeline-badge bg-primary">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </div>
                                                            <h6 class="mb-1">Work Submitted</h6>
                                                            <p class="text-muted fs-sm"><?= date('M j, Y g:i a', strtotime($submission['submission_date'])) ?></p>
                                                        </div>
                                                        
                                                        <?php if ($submission['status'] == 'Revision Requested'): ?>
                                                            <div class="timeline-event">
                                                                <div class="timeline-badge bg-warning">
                                                                    <i class="fas fa-sync-alt"></i>
                                                                </div>
                                                                <h6 class="mb-1">Revision Requested</h6>
                                                                <p class="text-muted fs-sm"><?= date('M j, Y g:i a', strtotime($submission['submission_date'])) ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($submission['status'] == 'Approved'): ?>
                                                            <div class="timeline-event">
                                                                <div class="timeline-badge bg-success">
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                                <h6 class="mb-1">Work Approved</h6>
                                                                <p class="text-muted fs-sm"><?= date('M j, Y g:i a', strtotime($submission['completion_date'])) ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <!-- Client Actions -->
                                                    <?php if ($submission['status'] == 'Submitted' && $job['status'] != 'Completed'): ?>
                                                        <div class="d-flex gap-2 mt-3">
                                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" 
                                                                    data-bs-target="#approveModal" data-submission-id="<?= $submission['id'] ?>">
                                                                <i class="fas fa-check-circle me-2"></i>Approve Work
                                                            </button>
                                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" 
                                                                    data-bs-target="#revisionModal" data-submission-id="<?= $submission['id'] ?>">
                                                                <i class="fas fa-sync-alt me-2"></i>Request Revision
                                                            </button>
                                                        </div>
                                                    <?php elseif ($submission['status'] == 'Approved'): ?>
                                                        <div class="d-flex gap-2 mt-3">
                                                            <?php 
                                                            // Check if rating already exists
                                                            $ratingQuery = $db->prepare("SELECT * FROM ratings WHERE job_id = :job_id AND freelancer_id = :freelancer_id AND client_id = :client_id");
                                                            $ratingQuery->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
                                                            $ratingQuery->bindValue(':freelancer_id', $submission['freelancer_id'], SQLITE3_INTEGER);
                                                            $ratingQuery->bindValue(':client_id', $user_id, SQLITE3_INTEGER);
                                                            $ratingResult = $ratingQuery->execute();
                                                            $ratingExists = $ratingResult->fetchArray(SQLITE3_ASSOC);
                                                            
                                                            if (!$ratingExists): ?>
                                                                <button type="button" class="btn btn-info" data-bs-toggle="modal" 
                                                                        data-bs-target="#ratingModal" 
                                                                        data-submission-id="<?= $submission['id'] ?>"
                                                                        data-freelancer-id="<?= $submission['freelancer_id'] ?>">
                                                                    <i class="fas fa-star me-2"></i>Rate Freelancer
                                                                </button>
                                                            <?php else: ?>
                                                                <button class="btn btn-success" disabled>
                                                                    <i class="fas fa-check-circle me-2"></i>Rated (<?= $ratingExists['rating'] ?>/5)
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Freelancer Profile Modal -->
    <div class="modal fade" id="freelancerProfileModal" tabindex="-1" aria-labelledby="freelancerProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="freelancerProfileModalLabel">Freelancer Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div id="freelancerProfileContent">
                        <!-- Profile content will be dynamically loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Work Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="submission_id" id="approveSubmissionId">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Work</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to approve this work?</p>
                        <p class="text-muted">You won't be able to request revisions after approval.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="approve_work" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i>Approve Work
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Request Revision Modal -->
    <div class="modal fade" id="revisionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="submission_id" id="revisionSubmissionId">
                    <div class="modal-header">
                        <h5 class="modal-title">Request Revision</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_feedback" class="form-label">Revision Instructions</label>
                            <textarea class="form-control" id="rejection_feedback" name="rejection_feedback" rows="4" 
                                      placeholder="Please specify what changes are needed..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="reject_work" class="btn btn-warning">
                            <i class="fas fa-sync-alt me-2"></i>Request Revision
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="submission_id" id="ratingSubmissionId">
                    <input type="hidden" name="freelancer_id" id="ratingFreelancerId">
                    <div class="modal-header">
                        <h5 class="modal-title">Rate Freelancer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="far fa-star rating-star" data-rating="<?= $i ?>"></i>
                                <?php endfor; ?>
                                <input type="hidden" name="rating" id="selectedRating" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Review</label>
                            <textarea class="form-control" id="review" name="review" rows="3" 
                                      placeholder="Share your experience working with this freelancer..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_rating" class="btn btn-primary">
                            Submit Rating
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Fire Freelancer Modal -->
    <div class="modal fade" id="fireFreelancerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="fire_freelancer.php">
                    <input type="hidden" name="order_id" id="fireOrderId">
                    <input type="hidden" name="freelancer_id" id="fireFreelancerId">
                    <div class="modal-header">
                        <h5 class="modal-title">Remove Freelancer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to remove this freelancer from the job?</p>
                        <p class="text-danger">This action cannot be undone. Any unpaid work will be refunded.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-user-slash me-2"></i>Remove Freelancer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to open the freelancer profile modal
        function openFreelancerProfile(freelancerId) {
            fetch(`get_freelancer_profile.php?id=${freelancerId}`)
                .then(response => response.json())
                .then(data => {
                    const profileContent = document.getElementById('freelancerProfileContent');
                    profileContent.innerHTML = `
                        <!-- Profile Header -->
                        <div class="text-center mb-4">
                            <img src="${data.profile_picture || 'https://via.placeholder.com/120'}" 
                                 class="profile-avatar mb-3" alt="Freelancer">
                            <h4 class="fw-semibold mb-1">${data.name}</h4>
                            <p class="text-muted mb-0">${data.tagline || 'No tagline provided'}</p>
                            <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                                <span class="freelancer-rating">
                                    <i class="fas fa-star"></i>4.9 (128 reviews)
                                </span>
                                <span class="text-muted">•</span>
                                <span class="text-success">
                                    <i class="fas fa-circle"></i> Available Now
                                </span>
                            </div>
                        </div>

                        <!-- About Me Section -->
                        <div class="profile-section">
                            <h6>About Me</h6>
                            <p>${data.about_me || 'No description provided.'}</p>
                        </div>

                        <!-- Skills Section -->
                        <div class="profile-section">
                            <h6>Skills</h6>
                            <div>
                                ${data.skills ? data.skills.split(',').map(skill => `
                                    <span class="skill-badge">${skill.trim()}</span>
                                `).join('') : 'No skills listed.'}
                            </div>
                        </div>

                        <!-- Experience Section -->
                        <div class="profile-section">
                            <h6>Experience</h6>
                            <p>${data.experience || 'No experience provided.'}</p>
                        </div>

                        <!-- Education Section -->
                        <div class="profile-section">
                            <h6>Education</h6>
                            <p>${data.degree || 'No degree provided.'} - ${data.institute || 'No institute provided.'} (${data.graduation_year || 'N/A'})</p>
                        </div>
                    `;

                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('freelancerProfileModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching freelancer profile:', error);
                });
        }

        // Attach click event to profile links
        document.querySelectorAll('.view-profile').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const freelancerId = link.getAttribute('data-freelancer-id');
                openFreelancerProfile(freelancerId);
            });
        });

        // Handle approve modal
        const approveModal = document.getElementById('approveModal');
        if (approveModal) {
            approveModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const submissionId = button.getAttribute('data-submission-id');
                document.getElementById('approveSubmissionId').value = submissionId;
            });
        }

        // Handle revision modal
        const revisionModal = document.getElementById('revisionModal');
        if (revisionModal) {
            revisionModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const submissionId = button.getAttribute('data-submission-id');
                document.getElementById('revisionSubmissionId').value = submissionId;
            });
        }

        // Handle rating modal
        const ratingModal = document.getElementById('ratingModal');
        if (ratingModal) {
            ratingModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const submissionId = button.getAttribute('data-submission-id');
                const freelancerId = button.getAttribute('data-freelancer-id');
                document.getElementById('ratingSubmissionId').value = submissionId;
                document.getElementById('ratingFreelancerId').value = freelancerId;
            });
        }

        // Handle fire freelancer modal
        const fireModal = document.getElementById('fireFreelancerModal');
        if (fireModal) {
            fireModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const orderId = button.getAttribute('data-order-id');
                const freelancerId = button.getAttribute('data-freelancer-id');
                document.getElementById('fireOrderId').value = orderId;
                document.getElementById('fireFreelancerId').value = freelancerId;
            });
        }

        // Handle rating stars
        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                document.getElementById('selectedRating').value = rating;
                
                // Update star display
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });
        });
    </script>
</body>
</html>
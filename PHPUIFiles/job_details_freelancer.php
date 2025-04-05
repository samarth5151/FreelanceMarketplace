<?php
session_start();

$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

// Get the order_id from the URL
$order_id = $_GET['id'] ?? 0;

$username = $_SESSION["username"] ?? '';

// Initialize error/success messages
$error = '';
$success = '';

// Handle file upload for job resubmission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resubmit_work'])) {
  $work_description = $_POST['work_description'];
  $submission_id = $_POST['submission_id'];
  
  // Get the job_id and other necessary data first
  $submissionData = $db->prepare("SELECT job_id, freelancer_id FROM work_submissions WHERE id = :submission_id");
  $submissionData->bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
  $result = $submissionData->execute()->fetchArray(SQLITE3_ASSOC);
  
  if (!$result) {
      $error = "Invalid submission data.";
  } else {
      $job_id = $result['job_id'];
      $freelancer_id = $result['freelancer_id'];
      
      // Get job details
      $jobQuery = $db->prepare("SELECT * FROM jobs WHERE id = :job_id");
      $jobQuery->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
      $jobResult = $jobQuery->execute();
      $job = $jobResult->fetchArray(SQLITE3_ASSOC);
      
      if (!$job) {
          $error = "Job not found.";
      } else {
          // Get client details
          $clientQuery = $db->prepare("SELECT users_id, users_name FROM users WHERE username = :username");
          $clientQuery->bindValue(':username', $job['username'], SQLITE3_TEXT);
          $clientResult = $clientQuery->execute();
          $client = $clientResult->fetchArray(SQLITE3_ASSOC);
          
          if (!$client) {
              $error = "Client not found.";
          } else {
              // Handle file uploads
              $upload_dir = 'uploads/deliverables/';
              $uploaded_files = [];
              
              if (!file_exists($upload_dir)) {
                  mkdir($upload_dir, 0777, true);
              }
              
              foreach ($_FILES['work_files']['tmp_name'] as $key => $tmp_name) {
                  $file_name = $_FILES['work_files']['name'][$key];
                  $file_tmp = $_FILES['work_files']['tmp_name'][$key];
                  $file_path = $upload_dir . uniqid() . '_' . basename($file_name);
                  
                  if (move_uploaded_file($file_tmp, $file_path)) {
                      $uploaded_files[] = $file_path;
                  }
              }
              
              if (!empty($uploaded_files)) {
                  $files_path = implode(',', $uploaded_files);
                  $submission_date = date('Y-m-d H:i:s');
                  
                  // Update the existing submission
                  $stmt = $db->prepare("
                      UPDATE work_submissions 
                      SET description = :description, 
                          files_path = :files_path, 
                          submission_date = :submission_date,
                          status = 'Resubmitted',
                          feedback = NULL
                      WHERE id = :submission_id
                  ");
                  
                  $stmt->bindValue(':description', $work_description, SQLITE3_TEXT);
                  $stmt->bindValue(':files_path', $files_path, SQLITE3_TEXT);
                  $stmt->bindValue(':submission_date', $submission_date, SQLITE3_TEXT);
                  $stmt->bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
                  
                  if ($stmt->execute()) {
                      // Update job status back to Review
                      $updateJob = $db->prepare("UPDATE jobs SET status = 'Review' WHERE id = :job_id");
                      $updateJob->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
                      $updateJob->execute();
                      
                      // Create notification for the client
                      $notificationMessage = "Freelancer has resubmitted work for your job: {$job['job_title']}";
                      
                      $notificationStmt = $db->prepare("
                          INSERT INTO notifications 
                          (user_id, job_id, freelancer_id, message, is_read, created_at) 
                          VALUES 
                          (:user_id, :job_id, :freelancer_id, :message, 0, :created_at)
                      ");
                      
                      $notificationStmt->bindValue(':user_id', $client['users_id'], SQLITE3_INTEGER);
                      $notificationStmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
                      $notificationStmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
                      $notificationStmt->bindValue(':message', $notificationMessage, SQLITE3_TEXT);
                      $notificationStmt->bindValue(':created_at', $submission_date, SQLITE3_TEXT);
                      
                      if ($notificationStmt->execute()) {
                          $success = "Your work has been resubmitted successfully!";
                      } else {
                          $error = "Work resubmitted but failed to create notification.";
                      }
                  } else {
                      $error = "Failed to resubmit work. Please try again.";
                  }
              } else {
                  $error = "Please upload at least one file for your submission.";
              }
          }
      }
  }
}


// Handle file upload for job submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_work'])) {
        $work_description = $_POST['work_description'];
        $job_id = $_POST['job_id'];
        
        // First, verify we have a valid job
        $jobQuery = $db->prepare("SELECT * FROM jobs WHERE id = :id");
        $jobQuery->bindValue(':id', $job_id, SQLITE3_INTEGER);
        $jobResult = $jobQuery->execute();
        $job = $jobResult->fetchArray(SQLITE3_ASSOC);

        if (!$job) {
            $error = "Job not found.";
        } else {
            // Handle file uploads
            $upload_dir = 'uploads/deliverables/';
            $uploaded_files = [];
            
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            foreach ($_FILES['work_files']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['work_files']['name'][$key];
                $file_tmp = $_FILES['work_files']['tmp_name'][$key];
                $file_path = $upload_dir . uniqid() . '_' . basename($file_name);
                
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $uploaded_files[] = $file_path;
                }
            }
            
            if (!empty($uploaded_files)) {
                // Save to database
                $files_path = implode(',', $uploaded_files);
                $submission_date = date('Y-m-d H:i:s');
                
                // Get freelancer ID and details
                $freelancerQuery = $db->prepare("SELECT id, name FROM freelancers WHERE username = :username");
                $freelancerQuery->bindValue(':username', $username, SQLITE3_TEXT);
                $freelancerResult = $freelancerQuery->execute();
                $freelancer = $freelancerResult->fetchArray(SQLITE3_ASSOC);
                
                if ($freelancer) {
                    $freelancer_id = $freelancer['id'];
                    $freelancer_name = $freelancer['name'];
                    
                    $stmt = $db->prepare("
                        INSERT INTO work_submissions 
                        (order_id, job_id, freelancer_id, description, files_path, submission_date, status) 
                        VALUES 
                        (:order_id, :job_id, :freelancer_id, :description, :files_path, :submission_date, 'Submitted')
                    ");
                    
                    $stmt->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
                    $stmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
                    $stmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
                    $stmt->bindValue(':description', $work_description, SQLITE3_TEXT);
                    $stmt->bindValue(':files_path', $files_path, SQLITE3_TEXT);
                    $stmt->bindValue(':submission_date', $submission_date, SQLITE3_TEXT);
                    
                    if ($stmt->execute()) {
                        // Update order status
                        $db->exec("UPDATE orders SET status = 'Submitted' WHERE id = $order_id");
                        $db->exec("UPDATE jobs SET status = 'Review' WHERE id = $job_id");
                        
                        // Get client user_id (job poster)
                        $clientQuery = $db->prepare("SELECT users_id FROM users WHERE username = :username");
                        $clientQuery->bindValue(':username', $job['username'], SQLITE3_TEXT);
                        $clientResult = $clientQuery->execute();
                        $client = $clientResult->fetchArray(SQLITE3_ASSOC);
                        
                        if ($client && isset($client['users_id'])) {
                            $client_id = $client['users_id'];
                            
                            // Create notification for the client
                            $notificationMessage = "Freelancer $freelancer_name has submitted work for your job: {$job['job_title']}";
                            
                            $notificationStmt = $db->prepare("
                                INSERT INTO notifications 
                                (user_id, job_id, freelancer_id, message, is_read, created_at) 
                                VALUES 
                                (:user_id, :job_id, :freelancer_id, :message, 0, :created_at)
                            ");
                            
                            $notificationStmt->bindValue(':user_id', $client_id, SQLITE3_INTEGER);
                            $notificationStmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
                            $notificationStmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
                            $notificationStmt->bindValue(':message', $notificationMessage, SQLITE3_TEXT);
                            $notificationStmt->bindValue(':created_at', $submission_date, SQLITE3_TEXT);
                            
                            if (!$notificationStmt->execute()) {
                                error_log("Failed to create notification: " . $db->lastErrorMsg());
                            }
                        } else {
                            error_log("Could not find client user ID for job poster: " . $job['username']);
                        }
                        
                        $success = "Your work has been submitted successfully!";
                    } else {
                        $error = "Failed to submit work. Please try again. Error: " . $db->lastErrorMsg();
                    }
                } else {
                    $error = "Could not verify freelancer account.";
                }
            } else {
                $error = "Please upload at least one file for your submission.";
            }
        }
    } elseif (isset($_POST['resubmit_work'])) {
        $work_description = $_POST['work_description'];
        $submission_id = $_POST['submission_id'];
        
        // Handle file uploads
        $upload_dir = 'uploads/deliverables/';
        $uploaded_files = [];
        
        foreach ($_FILES['work_files']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['work_files']['name'][$key];
            $file_tmp = $_FILES['work_files']['tmp_name'][$key];
            $file_path = $upload_dir . uniqid() . '_' . basename($file_name);
            
            if (move_uploaded_file($file_tmp, $file_path)) {
                $uploaded_files[] = $file_path;
            }
        }
        
        if (!empty($uploaded_files)) {
            $files_path = implode(',', $uploaded_files);
            $submission_date = date('Y-m-d H:i:s');
            
            // Get freelancer ID and details
            $freelancerQuery = $db->prepare("SELECT id, name FROM freelancers WHERE username = :username");
            $freelancerQuery->bindValue(':username', $username, SQLITE3_TEXT);
            $freelancerResult = $freelancerQuery->execute();
            $freelancer = $freelancerResult->fetchArray(SQLITE3_ASSOC);
            
            if ($freelancer) {
                $freelancer_id = $freelancer['id'];
                $freelancer_name = $freelancer['name'];
                
                // Update the existing submission
                $stmt = $db->prepare("
                    UPDATE work_submissions 
                    SET description = :description, 
                        files_path = :files_path, 
                        submission_date = :submission_date,
                        status = 'Resubmitted',
                        feedback = NULL
                    WHERE id = :submission_id
                ");
                
                $stmt->bindValue(':description', $work_description, SQLITE3_TEXT);
                $stmt->bindValue(':files_path', $files_path, SQLITE3_TEXT);
                $stmt->bindValue(':submission_date', $submission_date, SQLITE3_TEXT);
                $stmt->bindValue(':submission_id', $submission_id, SQLITE3_INTEGER);
                
                if ($stmt->execute()) {
                    // Update job status back to Review
                    $db->exec("UPDATE jobs SET status = 'Review' WHERE id = $job_id");
                    
                    // Create notification for the client
                    $notificationMessage = "Freelancer $freelancer_name has resubmitted work for your job: {$job['job_title']}";
                    
                    $notificationStmt = $db->prepare("
                        INSERT INTO notifications 
                        (user_id, job_id, freelancer_id, message, is_read, created_at) 
                        VALUES 
                        (:user_id, :job_id, :freelancer_id, :message, 0, :created_at)
                    ");
                    
                    $notificationStmt->bindValue(':user_id', $client['users_id'], SQLITE3_INTEGER);
                    $notificationStmt->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
                    $notificationStmt->bindValue(':freelancer_id', $freelancer_id, SQLITE3_INTEGER);
                    $notificationStmt->bindValue(':message', $notificationMessage, SQLITE3_TEXT);
                    $notificationStmt->bindValue(':created_at', $submission_date, SQLITE3_TEXT);
                    
                    $notificationStmt->execute();
                    
                    $success = "Your work has been resubmitted successfully!";
                } else {
                    $error = "Failed to resubmit work. Please try again.";
                }
            } else {
                $error = "Could not verify freelancer account.";
            }
        } else {
            $error = "Please upload at least one file for your submission.";
        }
    }
}

// Fetch the job_id from the orders table using the order_id
$orderQuery = $db->prepare("SELECT job_id, status as order_status FROM orders WHERE id = :order_id");
$orderQuery->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
$orderResult = $orderQuery->execute();
$order = $orderResult->fetchArray(SQLITE3_ASSOC);

if (!$order) {
    $error = "Order not found.";
} else {
    $job_id = $order['job_id'];
    $order_status = $order['order_status'];

    // Fetch job details using the job_id
    $jobQuery = $db->prepare("SELECT * FROM jobs WHERE id = :id");
    $jobQuery->bindValue(':id', $job_id, SQLITE3_INTEGER);
    $jobResult = $jobQuery->execute();
    $job = $jobResult->fetchArray(SQLITE3_ASSOC);

    // Check if the job exists
    if (!$job) {
        $error = "Job not found.";
    } else {
        // Fetch the job poster's details
        $posterQuery = $db->prepare("SELECT * FROM users WHERE username = :username");
        $posterQuery->bindValue(':username', $job['username'], SQLITE3_TEXT);
        $posterResult = $posterQuery->execute();
        $poster = $posterResult->fetchArray(SQLITE3_ASSOC);

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

        // Check if the job is assigned to the current freelancer
        $assignedQuery = $db->prepare("SELECT * FROM orders WHERE job_id = :job_id AND freelancer_id = (SELECT id FROM freelancers WHERE username = :username)");
        $assignedQuery->bindValue(':job_id', $job_id, SQLITE3_INTEGER);
        $assignedQuery->bindValue(':username', $username, SQLITE3_TEXT);
        $assignedResult = $assignedQuery->execute();
        $isAssigned = $assignedResult->fetchArray(SQLITE3_ASSOC) ? true : false;

        // Check if work has been submitted
        $submissionQuery = $db->prepare("SELECT * FROM work_submissions WHERE order_id = :order_id");
        $submissionQuery->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
        $submissionResult = $submissionQuery->execute();
        $submission = $submissionResult->fetchArray(SQLITE3_ASSOC);
    }
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($job['job_title']) ?> - Job Details</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../CSS files/job-details.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    .profile-card {
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .profile-card img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
    }
    .profile-card h5 {
      margin-top: 15px;
      font-size: 1.25rem;
      font-weight: 600;
    }
    .profile-card p {
      margin-bottom: 5px;
      font-size: 0.9rem;
      color: #555;
    }
    .assigned-badge {
      background-color: #28a745;
      color: #fff;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 500;
    }
    .submission-card {
      border-left: 4px solid #0d6efd;
      margin-bottom: 20px;
    }
    .submission-files img {
      max-width: 100px;
      max-height: 100px;
      margin-right: 10px;
      margin-bottom: 10px;
      border: 1px solid #ddd;
      padding: 5px;
    }
    .file-thumbnail {
      text-align: center;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    .file-name {
      font-size: 0.8rem;
      margin-top: 5px;
      word-break: break-all;
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
          <span class="status-badge bg-<?= $job['status'] === 'Open' ? 'success' : ($job['status'] === 'In Progress' ? 'warning' : ($job['status'] === 'Review' ? 'info' : 'secondary')) ?>">
            <?= htmlspecialchars($job['status']) ?>
          </span>
          <span class="text-muted">•</span>
          <?php if ($isAssigned): ?>
            <span class="assigned-badge">Assigned to You</span>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="jobTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">
          <i class="fas fa-info-circle me-2"></i>Details
        </button>
      </li>
      <?php if ($job['status'] === 'Open' || $job['status'] === 'In Progress'): ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="proposals-tab" data-bs-toggle="tab" data-bs-target="#proposals" type="button" role="tab" aria-controls="proposals" aria-selected="false">
            <i class="fas fa-comments me-2"></i>Proposals (<?= $proposalsCount ?>)
          </button>
        </li>
      <?php endif; ?>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#files" type="button" role="tab" aria-controls="files" aria-selected="false">
          <i class="fas fa-file-alt me-2"></i>Files
        </button>
      </li>
      <?php if ($isAssigned && ($job['status'] === 'In Progress' || $job['status'] === 'Review')): ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="submit-tab" data-bs-toggle="tab" data-bs-target="#submit" type="button" role="tab" aria-controls="submit" aria-selected="false">
            <i class="fas fa-paper-plane me-2"></i>Submit Work
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
                  <span class="badge bg-primary-subtle text-primary fs-6"> <?php echo $proposalsCount ?> </span>
                </div>
              </div>
            </div>

            <!-- Job Poster's Profile Card -->
            <div class="card border-0 shadow-sm mt-4">
              <div class="card-body">
                <h5 class="fw-semibold mb-4">Job Poster's Profile</h5>
                <div class="profile-card text-center">
                  <?php if (!empty($poster['users_profile_img'])): ?>
                    <img src="<?= htmlspecialchars($poster['users_profile_img']) ?>" alt="Job Poster">
                  <?php else: ?>
                    <div class="profile-avatar bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                      <i class="fas fa-user text-primary fs-4"></i>
                    </div>
                  <?php endif; ?>
                  <h5> <?= htmlspecialchars($poster['users_name'] ?? 'N/A') ?></h5>
                  <p><strong>Email: </strong><?= htmlspecialchars($poster['users_email'] ?? 'N/A') ?></p>
                  <p><strong>Contact: </strong><?= htmlspecialchars($poster['users_contact'] ?? 'N/A') ?></p>
                  <p><strong>Gender: </strong><?= htmlspecialchars($poster['users_gender'] ?? 'No description provided.') ?></p>
                  <p><strong>DOB: </strong><?= htmlspecialchars($poster['users_dob'] ?? 'No description provided.') ?></p>
                  
                  <!-- Add the "Message Now" button -->
                  <a href="freelancer_messages.php?user_id=<?= $poster['users_id'] ?>" class="btn btn-primary mt-3">
                    <i class="fas fa-envelope me-2"></i>Message Now
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Proposals Tab -->
      <?php if ($job['status'] === 'Open' || $job['status'] === 'In Progress'): ?>
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
                          <img src="<?= htmlspecialchars($proposal['freelancer_profile_picture']) ?>" class="freelancer-avatar rounded-circle" alt="Freelancer">
                        <?php else: ?>
                          <div class="freelancer-avatar rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user text-primary fs-4"></i>
                          </div>
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
                      </div>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Files Tab -->
      <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="fw-semibold mb-0">Project Attachments</h5>
              <form method="POST" enctype="multipart/form-data" class="upload-btn position-relative">
                <input type="file" class="form-control" name="attachments[]" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
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
                      <?php
                          $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                          if ($extension === 'pdf') {
                            $fileType = 'pdf';
                          } elseif (in_array($extension, ['doc', 'docx'])) {
                              $fileType = 'word';
                          } elseif (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                              $fileType = 'image';
                          } else {
                              $fileType = 'alt';
                          }
                        
                          ?>
                          <i class="fas fa-file-<?= $fileType ?> text-primary fs-3"></i>
                      </div>
                      <div class="flex-grow-1">
                        <div class="fw-medium text-truncate"><?= basename($file) ?></div>
                        <small class="text-muted"><?= round(filesize($file) / (1024 * 1024), 2) ?> MB</small>
                      </div>
                      <div class="btn-group">
                        <a href="download.php?file=<?= urlencode($file) ?>&job=<?= $job_id ?>" class="btn btn-sm btn-link text-decoration-none" download>
                          <i class="fas fa-download"></i>
                        </a>
                        <form method="POST">
                          <input type="hidden" name="file_path" value="<?= $file ?>">
                          <button type="submit" name="delete_file" class="btn btn-sm btn-link text-danger" onclick="return confirm('Delete this file permanently?')">
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

      <!-- Submit Work Tab -->
      <?php if ($isAssigned && ($job['status'] === 'In Progress' || $job['status'] === 'Review')): ?>
        <div class="tab-pane fade" id="submit" role="tabpanel" aria-labelledby="submit-tab">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <h4 class="card-title mb-4">Submit Your Work</h4>
              
              <?php if ($submission): ?>
                <div class="card submission-card mb-4">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h5 class="mb-0">Your Submission</h5>
                      <span class="badge bg-<?= $submission['status'] === 'Submitted' ? 'info' : ($submission['status'] === 'Approved' ? 'success' : 'danger') ?>">
                        <?= $submission['status'] ?>
                      </span>
                    </div>
                    <p class="mb-3"><?= nl2br(htmlspecialchars($submission['description'])) ?></p>
                    <p class="text-muted mb-3"><small>Submitted on: <?= date('M d, Y H:i', strtotime($submission['submission_date'])) ?></small></p>
                    
                    <h6 class="mt-4 mb-3">Submitted Files:</h6>
                    <div class="submission-files">
                      <?php foreach (explode(',', $submission['files_path']) as $file): ?>
                        <?php if (file_exists($file)): ?>
                          <?php $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); ?>
                          <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <img src="<?= $file ?>" alt="Submission file" class="img-thumbnail">
                          <?php else: ?>
                            <div class="d-inline-block me-3 mb-3">
                              <div class="file-thumbnail">
                                <i class="fas fa-file-<?= $ext === 'pdf' ? 'pdf' : (in_array($ext, ['doc', 'docx']) ? 'word' : 'alt') ?> fa-3x text-primary"></i>
                                <div class="file-name"><?= basename($file) ?></div>
                              </div>
                              <a href="<?= $file ?>" download class="d-block text-center mt-1">
                                <i class="fas fa-download"></i> Download
                              </a>
                            </div>
                          <?php endif; ?>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </div>
                    
                    <?php if (!empty($submission['feedback'])): ?>
                      <div class="mt-4 p-3 bg-light rounded">
                        <h6>Client Feedback:</h6>
                        <p><?= nl2br(htmlspecialchars($submission['feedback'])) ?></p>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>
              
              <?php if ($job['status'] === 'In Progress' && !$submission): ?>
                <form method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="order_id" value="<?= $order_id ?>">
                  <input type="hidden" name="job_id" value="<?= $job_id ?>">
                  
                  <div class="mb-3">
                    <label for="work_description" class="form-label">Work Description</label>
                    <textarea class="form-control" id="work_description" name="work_description" rows="5" required placeholder="Describe the work you've completed..."></textarea>
                  </div>
                  
                  <div class="mb-4">
                    <label for="work_files" class="form-label">Upload Deliverables</label>
                    <input class="form-control" type="file" id="work_files" name="work_files[]" multiple required>
                    <small class="text-muted">Upload all completed files (ZIP, PDF, DOC, images, etc.)</small>
                  </div>
                  
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg" name="submit_work">
                      <i class="fas fa-paper-plane me-2"></i>Submit Work
                    </button>
                  </div>
                </form>
              <?php elseif ($job['status'] === 'Review' && $submission && $submission['status'] === 'Revision Requested'): ?>
                <form method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="order_id" value="<?= $order_id ?>">
                  <input type="hidden" name="job_id" value="<?= $job_id ?>">
                  <input type="hidden" name="submission_id" value="<?= $submission['id'] ?>">
                  
                  <div class="alert alert-warning mb-4">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Revision Requested</h5>
                    <p><?= nl2br(htmlspecialchars($submission['feedback'])) ?></p>
                  </div>
                  
                  <div class="mb-3">
                    <label for="work_description" class="form-label">Updated Work Description</label>
                    <textarea class="form-control" id="work_description" name="work_description" rows="5" required><?= htmlspecialchars($submission['description']) ?></textarea>
                  </div>
                  
                  <div class="mb-4">
                    <label for="work_files" class="form-label">Upload Updated Deliverables</label>
                    <input class="form-control" type="file" id="work_files" name="work_files[]" multiple required>
                    <small class="text-muted">Upload all revised files (ZIP, PDF, DOC, images, etc.)</small>
                    
                    <?php if (!empty($submission['files_path'])): ?>
                      <div class="mt-3">
                        <h6>Previously Submitted Files:</h6>
                        <ul>
                          <?php foreach (explode(',', $submission['files_path']) as $file): ?>
                            <li><?= basename($file) ?></li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    <?php endif; ?>
                  </div>
                  
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg" name="resubmit_work">
                      <i class="fas fa-sync-alt me-2"></i>Resubmit Work
                    </button>
                  </div>
                </form>
              <?php elseif ($job['status'] === 'Review' && $submission): ?>
                <div class="alert alert-info">
                  <i class="fas fa-info-circle me-2"></i> Your work is under review by the client. You'll be notified when they respond.
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Bootstrap 5 Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
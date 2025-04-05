<?php
session_start();

$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    header("Location: login.php");
    exit();
}

// Fetch user data
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $db->query($sql);
if ($result) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $user_id = $row["users_id"];
        $username = $row["username"];
        $users_name = $row["users_name"];
        $email = $row["users_email"];
        $contact = $row["users_contact"];
        $gender = $row["users_gender"];
        $dob = $row["users_dob"];
        $profile_path = $row["users_profile_img"];
    }
} else {
    echo "0 results";
}

// Fetch notifications for the current user
$notificationsQuery = $db->prepare("
    SELECT n.*, j.job_title, f.name as freelancer_name 
    FROM notifications n
    JOIN jobs j ON n.job_id = j.id
    JOIN freelancers f ON n.freelancer_id = f.id
    WHERE n.user_id = :user_id
    ORDER BY n.created_at DESC
    LIMIT 5
");
$notificationsQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$notificationsResult = $notificationsQuery->execute();

// Count unread notifications
$unreadCountQuery = $db->prepare("
    SELECT COUNT(*) as unread_count 
    FROM notifications 
    WHERE user_id = :user_id 
    AND is_read = 0
");
$unreadCountQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$unreadCountResult = $unreadCountQuery->execute();
$unreadCount = $unreadCountResult->fetchArray(SQLITE3_ASSOC)['unread_count'] ?? 0;

// Fetch jobs from the database
$allJobsQuery = "SELECT * FROM jobs WHERE username = '$username'";
$allJobsResult = $db->query($allJobsQuery);

$openJobsQuery = "SELECT * FROM jobs WHERE username = '$username' AND status = 'Open'";
$openJobsResult = $db->query($openJobsQuery);

$inProgressJobsQuery = "SELECT * FROM jobs WHERE username = '$username' AND status = 'In Progress'";
$inProgressJobsResult = $db->query($inProgressJobsQuery);

$completedJobsQuery = "SELECT * FROM jobs WHERE username = '$username' AND status = 'Completed'";
$completedJobsResult = $db->query($completedJobsQuery);

// Count total jobs posted
$totalJobsQuery = "SELECT COUNT(*) as total_jobs FROM jobs WHERE username = '$username'";
$totalJobsResult = $db->query($totalJobsQuery);
$totalJobs = $totalJobsResult->fetchArray(SQLITE3_ASSOC)['total_jobs'];

// Count jobs in progress
$inProgressJobsCountQuery = "SELECT COUNT(*) as in_progress_jobs FROM jobs WHERE username = '$username' AND status = 'In Progress'";
$inProgressJobsCountResult = $db->query($inProgressJobsCountQuery);
$inProgressJobsCount = $inProgressJobsCountResult->fetchArray(SQLITE3_ASSOC)['in_progress_jobs'];

// Count completed jobs
$completedJobsCountQuery = "SELECT COUNT(*) as completed_jobs FROM jobs WHERE username = '$username' AND status = 'Completed'";
$completedJobsCountResult = $db->query($completedJobsCountQuery);
$completedJobsCount = $completedJobsCountResult->fetchArray(SQLITE3_ASSOC)['completed_jobs'];

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_profile"])) {
    $new_name = $_POST["users_name"];
    $new_email = $_POST["users_email"];
    $new_contact = $_POST["users_contact"];
    $new_gender = $_POST["users_gender"];
    $new_dob = $_POST["users_dob"];

    // Handle profile image upload
    if ($_FILES["profile_image"]["error"] == 0) {
        $target_dir = "profile_uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_path = $target_file; // Update the profile path
            } else {
                echo "<script>alert('Failed to upload profile image.');</script>";
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    $update_sql = "UPDATE users SET 
                   users_name = '$new_name', 
                   users_email = '$new_email', 
                   users_contact = '$new_contact', 
                   users_gender = '$new_gender', 
                   users_dob = '$new_dob', 
                   users_profile_img = '$profile_path' 
                   WHERE username = '$username'";

    if ($db->exec($update_sql)) {
        echo "<script>alert('Profile updated successfully!');</script>";
        // Refresh the page to reflect changes
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to update profile.');</script>";
    }
}

// Mark notification as read if clicked
if (isset($_GET['mark_read']) && isset($_GET['notification_id'])) {
    $notification_id = $_GET['notification_id'];
    $updateQuery = $db->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id");
    $updateQuery->bindValue(':id', $notification_id, SQLITE3_INTEGER);
    $updateQuery->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $updateQuery->execute();
    
    // Redirect to the job details page if provided
    if (isset($_GET['job_id'])) {
        header("Location: job_details.php?id=" . $_GET['job_id']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS files/user-dashboard.css">
    <style>
        
       
        /* Notification styles */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            padding: 3px 6px;
        }
        
        .notification-dropdown {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .notification-dropdown .dropdown-item {
            white-space: normal;
            padding: 10px 15px;
        }
        
        .notification-dropdown .dropdown-item.unread {
            background-color: #f8f9fa;
            font-weight: 500;
        }
        
        .notification-content {
            line-height: 1.4;
        }
        
        .notification-message {
            margin-bottom: 5px;
        }
        
        .notification-time {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        /* Responsive styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile-card">
        <img src="<?=$profile_path; ?>" alt="User Profile">
        <?= $profile_path;?>
        <h5><?php echo $username; ?></h5>
        <p id="p1"><span>Name:</span><?php echo $users_name; ?></p>
        <p><span>Email:</span><?php echo $email; ?></p>
        <p><span>Mobile:</span><?php echo $contact; ?></p>
        <p><span>Gender:</span><?php echo $gender; ?></p>
        <p><span>DOB:</span><?php echo $dob; ?></p>
        <a href="#edit" id="edit">Edit Profile</a>
    </div>
</div>

<!-- Edit Profile Popup -->
<div id="editProfilePopup" class="popup hidden">
    <div class="popup-content">
        <span class="close-btn" id="closeEditProfile">&times;</span>
        <h3>Edit Profile</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="profileImg">
                <div class="profile-image-upload">
                    <label for="profile_image">
                        <img src="<?php echo $profile_path; ?>" alt="Profile Image" id="profileImagePreview">
                    </label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;">
                </div>
            </div>
            <div class="field">
                <label>Full Name:</label>
                <input type="text" name="users_name" value="<?php echo $users_name; ?>">
            </div>
            <div class="field">
                <label>Email:</label>
                <input type="email" name="users_email" value="<?php echo $email; ?>">
            </div>
            <div class="field">
                <label>Contact:</label>
                <input type="text" name="users_contact" value="<?php echo $contact; ?>">
            </div>
            <div class="field">
                <label>Gender:</label>
                <select name="users_gender">
                    <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo ($gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="field">
                <label>Date of Birth:</label>
                <input type="date" name="users_dob" value="<?php echo $dob; ?>">
            </div>
            <button type="submit" name="update_profile">Update Profile</button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img style="width:150px;" src="../Assets/logo2.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge bg-danger notification-badge"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsDropdown">
                            <?php 
                            $notificationsResult->reset(); // Reset the pointer to read the results again
                            while ($notification = $notificationsResult->fetchArray(SQLITE3_ASSOC)): ?>
                                <li>
                                    <a class="dropdown-item <?= $notification['is_read'] ? '' : 'unread' ?>" 
                                       href="?mark_read=1&notification_id=<?= $notification['id'] ?>&job_id=<?= $notification['job_id'] ?>">
                                        <div class="notification-content">
                                            <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                                            <small class="notification-time"><?= date('M d, h:i A', strtotime($notification['created_at'])) ?></small>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                            <?php if ($notificationsResult->fetchArray(SQLITE3_ASSOC) === false): ?>
                                <li><a class="dropdown-item" href="#">No notifications yet</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="notifications.php">View all notifications</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="./messages.php?freelancer_id=4">Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overview Panel -->
    <div class="overview-panel">
        <h2>Welcome, <?php echo $users_name; ?>!</h2>
        <p>Here's a summary of your activity:</p>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Jobs Posted</h5>
                        <p><strong><?php echo $totalJobs; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Jobs in Progress</h5>
                        <p><strong><?php echo $inProgressJobsCount; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Completed Jobs</h5>
                        <p><strong><?php echo $completedJobsCount; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Pending Approvals</h5>
                        <p><strong><?= $unreadCount ?></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Job Section -->
    <div class="post-job">
        <h4>Post a Job</h4>
        <p>Create and manage job postings directly from this area.</p>
        <button id="postJobButton">Post New Job</button>
    </div>

    <!-- Jobs Section -->
    <div id="jobs">
        <h3>My Jobs</h3>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#all-jobs">All Jobs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#open-jobs">Open Jobs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#in-progress-jobs">In-Progress Jobs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#completed-jobs">Completed Jobs</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <!-- All Jobs Tab -->
            <div id="all-jobs" class="tab-pane fade show active">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Posted Date</th>
                            <th>Status</th>
                            <th>Bids Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $allJobsResult->reset();
                        while ($row = $allJobsResult->fetchArray(SQLITE3_ASSOC)) {
                            echo "<tr>
                                <td>{$row['job_title']}</td>
                                <td>" . date('M d, Y', strtotime($row['posted_date'])) . "</td>
                                <td><span class='badge bg-" . 
                                    ($row['status'] == 'Open' ? 'success' : 
                                    ($row['status'] == 'In Progress' ? 'warning' : 
                                    ($row['status'] == 'Completed' ? 'primary' : 'secondary'))) . 
                                    "'>{$row['status']}</span></td>
                                <td>{$row['NoOfbidsReceived']}</td>
                                <td>
                                    <a href='job_details.php?id={$row['id']}' class='btn btn-primary'>View Details</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Open Jobs Tab -->
            <div id="open-jobs" class="tab-pane fade">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Posted Date</th>
                            <th>Status</th>
                            <th>Bids Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $openJobsResult->reset();
                        while ($row = $openJobsResult->fetchArray(SQLITE3_ASSOC)) {
                            echo "<tr>
                                <td>{$row['job_title']}</td>
                                <td>" . date('M d, Y', strtotime($row['posted_date'])) . "</td>
                                <td><span class='badge bg-success'>{$row['status']}</span></td>
                                <td>{$row['NoOfbidsReceived']}</td>
                                <td>
                                    <a href='job_details.php?id={$row['id']}' class='btn btn-primary'>View Details</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- In-Progress Jobs Tab -->
            <div id="in-progress-jobs" class="tab-pane fade">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Posted Date</th>
                            <th>Status</th>
                            <th>Bids Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $inProgressJobsResult->reset();
                        while ($row = $inProgressJobsResult->fetchArray(SQLITE3_ASSOC)) {
                            echo "<tr>
                                <td>{$row['job_title']}</td>
                                <td>" . date('M d, Y', strtotime($row['posted_date'])) . "</td>
                                <td><span class='badge bg-warning text-dark'>{$row['status']}</span></td>
                                <td>{$row['NoOfbidsReceived']}</td>
                                <td>
                                    <a href='job_details.php?id={$row['id']}' class='btn btn-primary'>View Details</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Completed Jobs Tab -->
            <div id="completed-jobs" class="tab-pane fade">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Posted Date</th>
                            <th>Status</th>
                            <th>Bids Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $completedJobsResult->reset();
                        while ($row = $completedJobsResult->fetchArray(SQLITE3_ASSOC)) {
                            echo "<tr>
                                <td>{$row['job_title']}</td>
                                <td>" . date('M d, Y', strtotime($row['posted_date'])) . "</td>
                                <td><span class='badge bg-primary'>{$row['status']}</span></td>
                                <td>{$row['NoOfbidsReceived']}</td>
                                <td>
                                    <a href='job_details.php?id={$row['id']}' class='btn btn-primary'>View Details</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Post Job Popup -->
    <div id="postJobPopup" class="popup">
        <div class="container">
            <header>Post A Job</header>
            <span class="close-btn2" id="closeJobPopup">&times;</span>
            <div class="progress-bar">
                <div class="step">
                    <p>Job Details</p>
                    <div class="bullet">
                        <span>1</span>
                    </div>
                    <div class="check fas fa-check"></div>
                </div>
                <div class="step">
                    <p>Skills</p>
                    <div class="bullet">
                        <span>2</span>
                    </div>
                    <div class="check fas fa-check"></div>
                </div>
                <div class="step">
                    <p>Budget</p>
                    <div class="bullet">
                        <span>3</span>
                    </div>
                    <div class="check fas fa-check"></div>
                </div>
                <div class="step">
                    <p>Submit</p>
                    <div class="bullet">
                        <span>4</span>
                    </div>
                    <div class="check fas fa-check"></div>
                </div>
            </div>
            <div class="form-outer">
                <form action="store-job.php" method="POST" enctype="multipart/form-data">
                    <!-- Step 1: Job Details -->
                    <div class="page slide-page">
                        <div class="title">Job Details:</div>
                        <div class="field">
                            <div class="label">Job Title</div>
                            <input type="text" name="job_title" placeholder="Enter the job title" required>
                        </div>
                        <div class="field">
                            <div class="label">Job Category</div>
                            <select name="job_category" required>
                                <option value="">Select Category</option>
                                <option value="Web Development">Web Development</option>
                                <option value="Mobile Development">Mobile Development</option>
                                <option value="Graphic Design">Graphic Design</option>
                                <option value="Content Writing">Content Writing</option>
                                <option value="Digital Marketing">Digital Marketing</option>
                            </select>
                        </div>
                        <div class="field">
                            <div class="label">Job Description</div>
                            <textarea name="job_description" placeholder="Describe the job requirements" required></textarea>
                        </div>
                        <div class="field" style="margin-bottom:50px;">
                            <div class="label">Attachments</div>
                            <input type="file" name="attachments[]" multiple>
                        </div>
                        <button type="button" class="firstNext btn next" style="margin-bottom:10px;">Next</button>
                    </div>

                    <!-- Step 2: Skills -->
                    <div class="page">
                        <div class="title">Required Skills:</div>
                        <div class="field">
                            <div class="label">Primary Skill</div>
                            <input type="text" name="primary_skill" placeholder="e.g., Web Development" required>
                        </div>
                        <div class="field">
                            <div class="label">Additional Skills</div>
                            <input type="text" name="additional_skills" placeholder="e.g., JavaScript, PHP">
                        </div>
                        <div class="field">
                            <div class="label">Experience Level</div>
                            <select name="experience_level" required>
                                <option value="">Select Experience Level</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Expert">Expert</option>
                            </select>
                        </div>
                        <div class="btns">
                            <button type="button" class="prev-1 prev">Previous</button>
                            <button type="button" class="next-1 next">Next</button>
                        </div>
                    </div>

                    <!-- Step 3: Budget -->
                    <div class="page">
                        <div class="title">Budget & Timeline:</div>
                        <div class="field">
                            <div class="label">Budget (USD)</div>
                            <input type="number" name="budget" placeholder="Enter your budget" required>
                        </div>
                        <div class="field">
                            <div class="label">Deadline</div>
                            <input type="date" name="deadline" required>
                        </div>
                        <div class="btns">
                            <button type="button" class="prev-2 prev">Previous</button>
                            <button type="button" class="next-2 next">Next</button>
                        </div>
                    </div>

                    <!-- Step 4: Submit -->
                    <div class="page">
                        <div class="title">Review & Submit:</div>
                        <div class="field">
                            <div class="label">Additional Questions</div>
                            <textarea name="additional_questions" placeholder="Ask any additional questions if required"></textarea>
                        </div>
                        <div class="btns">
                            <button type="button" class="prev-3 prev">Previous</button>
                            <button type="submit" name="submit_job" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit Profile Popup
        const editProfilePopup = document.getElementById("editProfilePopup");
        const editProfileButton = document.getElementById("edit");
        const closeEditProfile = document.getElementById("closeEditProfile");

        editProfileButton.addEventListener("click", () => {
            editProfilePopup.style.display = "flex";
        });

        closeEditProfile.addEventListener("click", () => {
            editProfilePopup.style.display = "none";
        });

        window.addEventListener("click", (event) => {
            if (event.target === editProfilePopup) {
                editProfilePopup.style.display = "none";
            }
        });

        // Post Job Popup
        const postJobPopup = document.getElementById("postJobPopup");
        const postJobButton = document.getElementById("postJobButton");
        const closeJobPopup = document.getElementById("closeJobPopup");

        postJobButton.addEventListener("click", () => {
            postJobPopup.style.display = "flex";
        });

        closeJobPopup.addEventListener("click", () => {
            postJobPopup.style.display = "none";
        });

        window.addEventListener("click", (event) => {
            if (event.target === postJobPopup) {
                postJobPopup.style.display = "none";
            }
        });

        // Profile Image Preview
        const profileImageInput = document.getElementById("profile_image");
        const profileImagePreview = document.getElementById("profileImagePreview");

        profileImageInput.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profileImagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Multi-step form
        let current = 0;
        const pages = document.querySelectorAll(".page");
        const nextButtons = document.querySelectorAll(".next");
        const prevButtons = document.querySelectorAll(".prev");

        nextButtons.forEach((button, index) => {
            button.addEventListener("click", () => {
                pages[current].style.marginLeft = "-25%";
                current++;
                updateProgressBar();
            });
        });

        prevButtons.forEach((button, index) => {
            button.addEventListener("click", () => {
                pages[current].style.marginLeft = "0";
                current--;
                updateProgressBar();
            });
        });

        function updateProgressBar() {
            const bullets = document.querySelectorAll(".bullet");
            const steps = document.querySelectorAll(".step p");

            for (let i = 0; i < bullets.length; i++) {
                if (i <= current) {
                    bullets[i].classList.add("active");
                    steps[i].classList.add("active");
                } else {
                    bullets[i].classList.remove("active");
                    steps[i].classList.remove("active");
                }
            }
        }

        // Real-time notification check (every 30 seconds)
        setInterval(function() {
            fetch('check_notifications.php')
                .then(response => response.json())
                .then(data => {
                    if (data.unread_count > 0) {
                        const badge = document.querySelector('.notification-badge');
                        if (badge) {
                            badge.textContent = data.unread_count;
                        } else {
                            const icon = document.querySelector('.fa-bell').parentNode;
                            const newBadge = document.createElement('span');
                            newBadge.className = 'badge bg-danger notification-badge';
                            newBadge.textContent = data.unread_count;
                            icon.appendChild(newBadge);
                        }
                    }
                });
        }, 30000);
    </script>
</body>
</html>
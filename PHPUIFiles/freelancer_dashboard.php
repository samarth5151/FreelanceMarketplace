<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    echo "username not set";
}

// Fetch freelancer details
$sql = "SELECT * FROM freelancers WHERE username='$username'";
$result = $db->query($sql);

if ($result) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $freelancer_id = $row['id'];
        $name = $row['name'];
        $username = $row['username'];
        $password = $row['password'];
        $email = $row['email'];
        $contact = $row['contact'];
        $gender = $row['gender'];
        $dob = $row['dob'];
        $skills = $row['skills'];
        $usertype = $row['usertype'];
        $tools = $row['tools'];
        $tagline = $row['tagline'];
        $aboutMe = $row['about_me'];
        $experience = $row['experience'];
        $languages = $row['languages'];
        $availability = $row['availability'];
        $degree = $row['degree'];
        $institute = $row['institute'];
        $graduationYear = $row['graduation_year'];
        $profilePicture = $row['profile_picture'];
        $resume = $row['resume'];
    }
} else {
    echo "0 results";
}

// Fetch completed and ongoing projects count
$completedProjectsCount = 0;
$ongoingProjectsCount = 0;

$sql = "SELECT status, COUNT(*) as count FROM orders WHERE freelancer_id = (SELECT id FROM freelancers WHERE username = '$username') GROUP BY status";
$result = $db->query($sql);

if ($result) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        if ($row['status'] == 'Completed') {
            $completedProjectsCount = $row['count'];
        } elseif ($row['status'] == 'In Progress') {
            $ongoingProjectsCount = $row['count'];
        }
    }
}

// Fetch My Jobs with Job Title
$myJobs = [];
$sql = "SELECT orders.*, jobs.job_title as job_title 
        FROM orders 
        JOIN jobs ON orders.job_id = jobs.id 
        WHERE orders.freelancer_id = (SELECT id FROM freelancers WHERE username = '$username') 
        AND orders.status IN ('In Progress', 'Completed', 'Review','Submitted')";
$result = $db->query($sql);

if ($result) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $myJobs[] = $row;
    }
}

// Fetch Proposals with Job Title
$proposals = [];
$sql = "SELECT proposals.*, jobs.job_title as job_title 
        FROM proposals 
        JOIN jobs ON proposals.job_id = jobs.id 
        WHERE proposals.freelancer_id = (SELECT id FROM freelancers WHERE username = '$username') 
        AND proposals.status IN ('Pending', 'Accepted')";
$result = $db->query($sql);

if ($result) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $proposals[] = $row;
    }
}
// Replace the existing notifications fetch code with this:
    $notifications = [];
    $sql = "SELECT fn.*, j.job_title, u.username as client_name 
            FROM freelancer_notifications fn
            JOIN jobs j ON fn.job_id = j.id
            JOIN users u ON fn.client_id = u.users_id
            WHERE fn.freelancer_id = $freelancer_id 
            ORDER BY fn.created_at DESC";
    $result = $db->query($sql);
    
    if ($result) {
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $notifications[] = $row;
        }
    }
    
    // Mark notifications as read when page loads
    $unreadCount = 0;
    foreach ($notifications as $notification) {
        if (!$notification['is_read']) {
            $unreadCount++;
        }
    }
    if ($unreadCount > 0) {
        $db->exec("UPDATE freelancer_notifications SET is_read = 1 WHERE freelancer_id = $freelancer_id AND is_read = 0");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS files/freelancer_dashboard.css">
    <style>
        .nav-tabs .nav-link {
            color: #000;
        }
        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #000;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .notification-dropdown {
            max-height: 400px;
            overflow-y: auto;
            width: 350px;
        }
        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .notification-item.unread {
            background-color: #f8f9fa;
        }
        .notification-item:hover {
            background-color: #f1f1f1;
        }
        .notification-time {
            font-size: 12px;
            color: #6c757d;
        }
        .view-all-notifications {
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile-card">
        <img src="<?php echo $profilePicture; ?>" alt="Freelancer Profile">
        <h5><?php echo $username ?></h5>
        <p><span>Name:</span> <?php echo $name ?></p>
        <p><span>Tagline:</span> <?php echo $tagline ?></p>
        <p><span>Email:</span> <?php echo $email ?></p>
        <p><span>Contact:</span> <?php echo $contact ?></p>
        <a href="#edit-profile" id="edit-profile">Edit Profile</a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../Assets/logo2.png" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="notification-badge"><?php echo $unreadCount; ?></span>
                <?php endif; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsDropdown">
                <li><h6 class="dropdown-header">Notifications</h6></li>
                <?php if (empty($notifications)): ?>
                    <li><a class="dropdown-item" href="#">No notifications</a></li>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                        <li>
                            <a class="dropdown-item notification-item <?php echo !$notification['is_read'] ? 'unread' : ''; ?>" href="job_details_freelancer.php?id=<?php echo $notification['job_id']; ?>">
                                <div class="d-flex justify-content-between">
                                    <strong><?php echo htmlspecialchars($notification['client_name']); ?></strong>
                                    <small class="notification-time">
                                        <?php 
                                            $date = new DateTime($notification['created_at']);
                                            echo $date->format('M j, Y g:i a'); 
                                        ?>
                                    </small>
                                </div>
                                <div class="mt-1"><?php echo htmlspecialchars($notification['message']); ?></div>
                                <div class="text-muted small mt-1">Job: <?php echo htmlspecialchars($notification['job_title']); ?></div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <li><div class="view-all-notifications"><a href="freelancer_notifications.php">View All Notifications</a></div></li>
            </ul>
        </li>
                    <li class="nav-item"><a class="nav-link" href="./Find-Job.php">Find Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="#my-jobs">My Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="./freelancer_messages.php">Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="#logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overview Panel -->
    <div class="overview-panel">
        <h2>Welcome, <?php echo $name ?>!</h2>
        <p>Here's a summary of your profile:</p>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Completed Projects</h5>
                        <p><strong><?php echo $completedProjectsCount; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Ongoing Projects</h5>
                        <p><strong><?php echo $ongoingProjectsCount; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Overall Rating</h5>
                        <p><strong>4.8/5</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="profile-section">
        <h4>Profile Information</h4>
        <p><strong>Skills:</strong> <?php echo $skills ?></p>
        <p><strong>Tools:</strong> <?php echo $tools ?></p>
        <p><strong>Languages:</strong> <?php echo $languages ?></p>
        <p><strong>Availability:</strong> <?php echo $availability ?></p>
        <p><strong>Education:</strong> <?php echo $degree ?> at <?php echo $institute ?>-<?php echo $graduationYear ?></p>
        <button class="edit-btn">Edit Information</button>
    </div>

    <!-- Earnings Section -->
    <div class="profile-section mt-4" id="earnings">
        <h4>Earnings</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Earnings</h5>
                        <p><strong>$5,000</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Pending Payments</h5>
                        <p><strong>$500</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Section for My Jobs and Proposals -->
    <div class="profile-section mt-4">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="my-jobs-tab" data-bs-toggle="tab" data-bs-target="#my-jobs-content" type="button" role="tab" aria-controls="my-jobs-content" aria-selected="true">My Jobs</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="proposals-tab" data-bs-toggle="tab" data-bs-target="#proposals-content" type="button" role="tab" aria-controls="proposals-content" aria-selected="false">Proposals</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            
            <!-- My Jobs Tab Content -->
            <div class="tab-pane fade show active" id="my-jobs-content" role="tabpanel" aria-labelledby="my-jobs-tab">
                <?php if (!empty($myJobs)): ?>
                    <?php foreach ($myJobs as $job): ?>
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5> <?php echo $job['job_title']; ?></h5>
                                <h5> <?php echo $job['id']; ?></h5>
                                <p>Status: <span style="color: <?php echo $job['status'] == 'Completed' ? 'green' : 'blue'; ?>;"><?php echo $job['status']; ?></span></p>
                                <p>Amount: $<?php echo $job['amount']; ?></p>
                                <a href="./job_details_freelancer.php?id=<?= $job['id'] ?>&status=<?= htmlspecialchars($job['status']) ?>" class="btn btn-primary btn-sm">View Details</a>        
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No jobs found.</p>
                <?php endif; ?>
            </div>

            <!-- Proposals Tab Content -->
            <div class="tab-pane fade" id="proposals-content" role="tabpanel" aria-labelledby="proposals-tab">
                <?php if (!empty($proposals)): ?>
                    <?php foreach ($proposals as $proposal): ?>
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5><?php echo $proposal['job_title']; ?></h5>
                                <p>Status: <span style="color: <?php echo $proposal['status'] == 'Accepted' ? 'green' : 'orange'; ?>;"><?php echo $proposal['status']; ?></span></p>
                                <p>Bid Amount: $<?php echo $proposal['bid_amount']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No proposals found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" enctype="multipart/form-data">
                    <input type="hidden" name="existingProfilePicture" value="<?php echo $profilePicture; ?>">
                    <div class="text-center mb-3">
                        <img id="profilePicturePreview" src="<?php echo $profilePicture; ?>" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%; cursor: pointer;">
                        <input type="file" id="profilePicture" name="profilePicture" accept="image/*" style="display: none;">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control custom-input" id="name" name="name" value="<?php echo $name; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tagline" class="form-label">Tagline</label>
                        <input type="text" class="form-control custom-input" id="tagline" name="tagline" value="<?php echo $tagline; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control custom-input" id="email" name="email" value="<?php echo $email; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="text" class="form-control custom-input" id="contact" name="contact" value="<?php echo $contact; ?>">
                    </div>
                    <button type="submit" class="btn btn-save-changes">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Profile Information Edit Modal -->
<div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="editInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="editInfoModalLabel">Edit Professional Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="professionalInfoForm" action="update_profile_info.php" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="skills" class="form-label">Skills (comma separated)</label>
                                <input type="text" class="form-control" id="skills" name="skills" value="<?php echo htmlspecialchars($skills); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="tools" class="form-label">Tools (comma separated)</label>
                                <input type="text" class="form-control" id="tools" name="tools" value="<?php echo htmlspecialchars($tools); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="languages" class="form-label">Languages (comma separated)</label>
                                <input type="text" class="form-control" id="languages" name="languages" value="<?php echo htmlspecialchars($languages); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="availability" class="form-label">Availability</label>
                                <select class="form-select" id="availability" name="availability">
                                    <option value="Full-time" <?php echo ($availability == 'Full-time') ? 'selected' : ''; ?>>Full-time</option>
                                    <option value="Part-time" <?php echo ($availability == 'Part-time') ? 'selected' : ''; ?>>Part-time</option>
                                    <option value="Not Available" <?php echo ($availability == 'Not Available') ? 'selected' : ''; ?>>Not Available</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="degree" class="form-label">Degree</label>
                                <input type="text" class="form-control" id="degree" name="degree" value="<?php echo htmlspecialchars($degree); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="institute" class="form-label">Institute</label>
                                <input type="text" class="form-control" id="institute" name="institute" value="<?php echo htmlspecialchars($institute); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="graduation_year" class="form-label">Graduation Year</label>
                                <input type="number" class="form-control" id="graduation_year" name="graduation_year" value="<?php echo htmlspecialchars($graduationYear); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Open the modal when the "Edit Profile" button is clicked
        $('#edit-profile').click(function(e) {
            e.preventDefault();
            $('#editProfileModal').modal('show');
        });

        // Trigger file input when profile picture is clicked
        $('#profilePicturePreview').click(function() {
            $('#profilePicture').click();
        });

        // Preview the selected image
        $('#profilePicture').change(function(e) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profilePicturePreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        });

        // Handle form submission
        $('#editProfileForm').submit(function(e) {
            e.preventDefault();

            // Create FormData object to handle file upload
            var formData = new FormData(this);

            // Send AJAX request
            $.ajax({
                url: 'update_profile.php', // PHP script to handle the update
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content type
                success: function(response) {
                    // Handle success response
                    alert('Profile updated successfully!');
                    $('#editProfileModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('An error occurred while updating the profile.');
                }
            });
        });

        // Handle professional info form submission
        $('#professionalInfoForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                type: 'POST',
                url: 'update_profile_info.php',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editInfoModal').modal('hide');
                    location.reload(); // Refresh to show updated data
                },
                error: function() {
                    alert('Error updating information');
                }
            });
        });

        // Show edit info modal when button clicked
        $('.profile-section .edit-btn').click(function() {
            $('#editInfoModal').modal('show');
        });

       // Update the checkNotifications function
        function checkNotifications() {
            $.ajax({
                url: 'check_freelancer_notifications.php',
                type: 'GET',
                data: { freelancer_id: <?php echo $freelancer_id; ?> },
                success: function(response) {
                    if (response.count > 0) {
                        // Update badge count
                        $('.notification-badge').text(response.count).show();
                        // Play notification sound
                        var audio = new Audio('../Assets/notification.mp3');
                        audio.play();
                        
                        // Optionally refresh the notification dropdown
                        if (response.html) {
                            $('.notification-dropdown').html(response.html);
                        }
                    }
                }
            });
        }

        // Update the checkNotifications function
function checkNotifications() {
    $.ajax({
        url: 'check_freelancer_notifications.php',
        type: 'GET',
        data: { freelancer_id: <?php echo $freelancer_id; ?> },
        success: function(response) {
            if (response.count > 0) {
                // Update badge count
                $('.notification-badge').text(response.count).show();
                // Play notification sound
                var audio = new Audio('../Assets/notification.mp3');
                audio.play();
                
                // Optionally refresh the notification dropdown
                if (response.html) {
                    $('.notification-dropdown').html(response.html);
                }
            }
        }
    });
}

        // Check notifications every 30 seconds
        setInterval(checkNotifications, 30000);
    });
</script>
</body>
</html>
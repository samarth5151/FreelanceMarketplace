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

// Fetch user data
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $db->query($sql);
if ($result) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
            position: fixed;
            width: 350px;
        }
        .sidebar .profile-card {
            background: #ffffff;
            padding: 15px 40px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .sidebar .profile-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .profile-card p {
            text-align: left;
            color: grey;
        }
        .profile-card p span {
            color: black;
        }
        #p1 {
            margin-top: 40px;
        }
        .sidebar #logout {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #333;
            position: absolute;
            bottom: 0;
            background-color: transparent;
            color: #333;
            border: 1px solid grey;
            padding: 10px 35px;
            border-radius: 5px;
            cursor: pointer;
        }
        #edit {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            background-color: transparent;
            color: #333;
            border: 1px solid grey;
            padding: 10px 25px;
            border-radius: 5px;
            cursor: pointer;
        }
        .sidebar a:hover {
            background-color: rgb(231, 231, 231);
        }
        .main-content {
            margin-left: 370px;
            padding: 20px;
        }
        .navbar {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .jobs {
            border: 1px solid #ddd;
        }
        .overview-panel {
            background: #f1f1f1;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .post-job {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .post-job button {
            background-color: transparent;
            color: #333;
            border: 1px solid grey;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Popup Styling */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 95%;
            max-width: 600px;
            padding:20px;
            max-height: 90%;
            overflow-y: auto;
            position: relative;
        }
        .close-btn2 {
            position: absolute;
            top: 5px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
        }

        /* post job style  */
    
::selection {
  color: #fff;
  background: rgb(32 159 75);
}

.container {
    max-height: 90%;
    overflow-y: auto;
    position: relative;
  width: 100%;
  max-width: 450px; /* Reduced max width slightly */
  background: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 20px; 
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Optional enhancement */
}

.container header {
  font-size: 22px; /* Slightly reduced font size */
  font-weight: 600;
  margin: 0 0 15px 0; /* Reduced bottom margin */
}

.container .form-outer {
  width: 100%;
  overflow: hidden;
}

.container .form-outer form {
  display: flex;
  width: 400%;
}

.form-outer form .page {
  width: 25%;
  transition: margin-left 0.3s ease-in-out;
  position: relative;
  padding-bottom: 50px; /* Reduced bottom padding */
}

.form-outer form .page .title {
  text-align: left;
  font-size: 18px; /* Reduced font size */
  font-weight: 500;
  margin-bottom: 10px;
}

.form-outer form .page .field {
  width: 100%;
    margin: 8px 0;
    display: grid
;
    flex-direction: row;
    justify-content: space-between;
    /* border: 1px solid red; */
    grid-template-columns: 15% 77%;
}




form .page .field input,
form .page .field select,
form .page .field textarea {
  height: 38px; /* Reduced height */
  width: 100%;
  border: 1px solid lightgrey;
  border-radius: 5px;
  padding: 0 8px; /* Reduced padding inside input */
  font-size: 14px; /* Slightly smaller font size */
}

form .page .field textarea {
  height: 70px; /* Reduced height */
  resize: none;
}

form .page .field button {
  height: 40px; /* Reduced button height */
  border: 1px solid grey;
  background: transparent;
  margin-top: 10px;
  border-radius: 5px;
  color: #333;
  cursor: pointer;
  font-size: 16px; /* Reduced font size */
  font-weight: 500;
  letter-spacing: 1px;
  width: 100%;
  transition: 0.5s ease;
}
form .page .btns button {
  height: 40px; /* Reduced button height */
  border: 1px solid grey;
  background: transparent;
  margin-top: 10px;
  border-radius: 5px;
  color: #333;
  cursor: pointer;
  font-size: 16px; /* Reduced font size */
  font-weight: 500;
  letter-spacing: 1px;
  width: 100%;
  transition: 0.5s ease;
}

form .page .firstNext {
    width: 100%;
    color: #333;
    border: 1px solid grey;
    position: absolute;
    bottom: 0px;
    margin-top:20px;
    left: 0px;
    cursor: pointer;
}





form .page .field button:hover {
  background: transparent;
}

form .page .btns {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  display: flex;
  gap:10px;
  justify-content: space-between;

}
form .page .field .label {
  margin-bottom: 5px;
  font-weight: 500;
  text-align: left;
}


/* form .page.step-4 .btns {
  justify-content: flex-end;
} */

.container .progress-bar {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(25%, 1fr));
  margin: 10px 0; 
  user-select: none;
}

.container .progress-bar .step {
  text-align: center;
  width: 100%;
  flex:1;
  position: relative;
  margin-right: 8px; /* Reduced margin between steps */
}

.container .progress-bar .step p {
  font-weight: 500;
  font-size: 12px; /* Slightly reduced font size */
  color: #000;
  margin-bottom: 5px;
}

.progress-bar .step .bullet {
  height: 22px; /* Reduced size of bullets */
  width: 22px;
  border: 2px solid #000;
  display: inline-block;
  border-radius: 50%;
  position: relative;
  transition: 0.2s;
  font-weight: 500;
  font-size: 12px;
  line-height: 22px;
}

.progress-bar .step .bullet.active {
  border-color:rgb(32 159 75);
  background: rgb(32 159 75);
}

.progress-bar .step .bullet span {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}

.progress-bar .step .bullet.active span {
  display: none;
}

.progress-bar .step .bullet:before,
.progress-bar .step .bullet:after {
  position: absolute;
  content: '';
  bottom: 9px;
  right: -45px;
  height: 3px;
  width: 40px;
  background: #262626;
}

.progress-bar .step .bullet.active:after {
  background: rgb(32 159 75);
  transform: scaleX(0);
  transform-origin: left;
  animation: animate 0.3s linear forwards;
}

@keyframes animate {
  100% {
    transform: scaleX(1);
  }
}

.progress-bar .step:last-child .bullet:before,
.progress-bar .step:last-child .bullet:after {
  display: none;
}

.progress-bar .step p.active {
  color: rgb(32 159 75);
  transition: 0.2s linear;
}

.progress-bar .step .check {
  position: absolute;
  left: 50%;
  top: 70%;
  font-size: 14px;
  transform: translate(-50%, -50%);
  display: none;
}

.progress-bar .step .check.active {
  display: block;
  color: #fff;
}

@media (max-width: 768px) {
  .container {
    width: 90%;
    padding: 20px; /* Adjusted padding for smaller screens */
  }

  .container header {
    font-size: 20px;
  }

  .form-outer form {
    width: 500%;
  }

  .form-outer form .page .title {
    font-size: 16px;
  }

  .form-outer form .page .field {
    margin: 6px 0;
  }

  form .page .field button {
    font-size: 14px;
  }

  .progress-bar {
    margin: 8px 0;
  }
}

/* Edit Profile Popup Styling */
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.popup-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    max-height: 90%;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.popup-content h3 {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    cursor: pointer;
    color: #333;
    transition: color 0.3s ease;
}

.close-btn:hover {
    color: rgb(32, 159, 75);
}

.field {
    margin-bottom: 20px;
    display:grid;
    grid-template-columns: 35% 65%;
    font-size:16px;

}

.profileImg{
    display:flex;
    justify-content: center;
    align-items: center;
}

.field label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: black;
    font-size: 16px;
    margin-top: 5px;
}

.field input,
.field select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    color: #333;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.field input:focus,
.field select:focus {
    border-color: rgb(32, 159, 75);
    outline: none;
    box-shadow: 0 0 5px rgba(32, 159, 75, 0.5);
}

.field select {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;
}

.profile-image-upload {
    text-align: center;
    margin-bottom: 20px;
}

.profile-image-upload img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.profile-image-upload img:hover {
    border-color: rgb(32, 159, 75);
}

button[type="submit"] {
    width: 100%;
    background-color: transparent;
    padding:8px;
    color: #333;
    margin-top:40px;
    border: 1px solid grey;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #f1f1f1;
    color: #000;
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile-card">
        <img src="<?php echo $profile_path; ?>" alt="User Profile">
        <h5><?php echo $username; ?></h5>
        <p id="p1"><span>Name:</span><?php echo $users_name; ?></p>
        <p><span>Email:</span><?php echo $email; ?></p>
        <p><span>Mobile:</span><?php echo $contact; ?></p>
        <p><span>Gender:</span><?php echo $gender; ?></p>
        <p><span>DOB:</span><?php echo $dob; ?></p>
        <a href="#edit" id="edit">Edit Profile</a>
    </div>
    <a id="logout" href="logout.php">Logout</a> 
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
                    <li class="nav-item"><a class="nav-link" href="#freelancers">Freelancers</a></li>
                    <li class="nav-item"><a class="nav-link" href="#notifications">Notifications</a></li>
                    <li class="nav-item"><a class="nav-link" href="#settings">Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="#logout">Logout</a></li>
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
                        <p><strong>[Count]</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Jobs in Progress</h5>
                        <p><strong>[Count]</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Completed Jobs</h5>
                        <p><strong>[Count]</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Pending Approvals</h5>
                        <p><strong>[Count]</strong></p>
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
            <li class="nav-item"><a class="nav-link active" href="#all-jobs">All Jobs</a></li>
            <li class="nav-item"><a class="nav-link" href="#open-jobs">Open Jobs</a></li>
            <li class="nav-item"><a class="nav-link" href="#in-progress-jobs">In-Progress Jobs</a></li>
            <li class="nav-item"><a class="nav-link" href="#completed-jobs">Completed Jobs</a></li>
        </ul>
        <table class="table table-striped mt-3">
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
                <tr>
                    <td>Example Job 1</td>
                    <td>01/01/2025</td>
                    <td>Open</td>
                    <td>5</td>
                    <td><button class="btn btn-primary">View Details</button> <button class="btn btn-danger">Close Job</button></td>
                </tr>
                <!-- Repeat rows as needed -->
            </tbody>
        </table>
    </div>
    
    <!-- Popup Form -->
    <div id="postJobPopup" class="popup">

    <!-- post job popup style -->
          
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
      <form action="#">

         <!-- Step 1: Job Details -->
         <div class="page slide-page">
            <div class="title">Job Details:</div>
            <div class="field">
               <div class="label">Job Title</div>
               <input type="text" placeholder="Enter the job title">
            </div>
            <div class="field">
               <div class="label">Job Category</div>
               <select>

                  <option>Web Development</option>
                  <option>Mobile Development</option>
               
               </select>
            </div>
            <div class="field">
               <div class="label">Job Description</div>
               <textarea placeholder="Describe the job requirements"></textarea>
            </div>
            <div class="field" style="margin-bottom:50px;">
               <div class="label">Attachments</div>
               <input type="file">
            </div>
            
            <button class="firstNext btn next" style="margin-bottom:10px;">Next</button>

         </div>

         <!-- Step 2: Skills -->
         <div class="page">
            <div class="title">Required Skills:</div>
            <div class="field">
               <div class="label">Primary Skill</div>
               <input type="text" placeholder="e.g., Web Development">
            </div>
            <div class="field">
               <div class="label">Additional Skills</div>
               <input type="text" placeholder="e.g., JavaScript, PHP">
            </div>
            <div class="field">
               <div class="label">Experience Level</div>
               <select>
                  <option>Beginner</option>
                  <option>Intermediate</option>
                  <option>Expert</option>
               </select>
            </div>
            <div class=" btns">
               <button class="prev-1 prev">Previous</button>
               <button class="next-1 next">Next</button>
            </div>
         </div>

         <!-- Step 3: Budget -->
         <div class="page">
            <div class="title">Budget & Timeline:</div>
            <div class="field">
               <div class="label">Budget (USD)</div>
               <input type="number" placeholder="Enter your budget">
            </div>
            <div class="field">
               <div class="label">Deadline</div>
               <input type="date">
            </div>

            <div class=" btns">
               <button class="prev-2 prev">Previous</button>
               <button class="next-2 next">Next</button>
            </div>
         </div>

         <!-- Step 4: Submit -->
         <div class="page">
            <div class="title">Review & Submit:</div>
            <div class="field">
               <div class="label">Additional Questions</div>
               <textarea placeholder="Ask any additional questions if required"></textarea>
            </div>
            <div class=" btns">
               <button class="prev-3 prev">Previous</button>
               <button class="submit">Submit</button>
            </div>
         </div>
      </form>
   </div>
</div> 

<!-- JavaScript for Popup -->
<script>
    const postJobPopup = document.getElementById("postJobPopup");
    const postJobButton = document.getElementById("postJobButton");
    const closeBtn = document.getElementById("closeJobPopup");

    postJobButton.addEventListener("click", () => {
        postJobPopup.style.display = "flex";
    });

    closeBtn.addEventListener("click", () => {
        postJobPopup.style.display = "none";
    });

    window.addEventListener("click", (event) => {
        if (event.target === postJobPopup) {
            postJobPopup.style.display = "none";
        }
    });
</script>


<script>
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
</script>

<script>
    // Get the popup and button elements
    const editProfilePopup = document.getElementById("editProfilePopup");
    const editProfileButton = document.getElementById("edit");
    const closeEditProfile = document.getElementById("closeEditProfile");

    // Open the popup when the "Edit Profile" button is clicked
    editProfileButton.addEventListener("click", () => {
        editProfilePopup.style.display = "flex";
    });

    // Close the popup when the close button is clicked
    closeEditProfile.addEventListener("click", () => {
        editProfilePopup.style.display = "none";
    });

    // Close the popup when clicking outside the popup content
    window.addEventListener("click", (event) => {
        if (event.target === editProfilePopup) {
            editProfilePopup.style.display = "none";
        }
    });
</script>

<script>
    // Preview profile image before upload
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
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
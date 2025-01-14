<?php

session_start();

$db = new SQLite3('C:\xampp\htdocs\MegaProject\Connection\Freelance_db.db');

if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}


if(isset($_SESSION["username"])){
	$username=$_SESSION["username"];
}
else{
	$username="rushi";
	
}


// Assuming $conn is the SQLite3 connection object
$sql = "SELECT * FROM freelancers WHERE username='$username'";
$result = $db->query($sql);

if ($result) {
    // Fetch data as associative array
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {

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


 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
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
        #edit-profile {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #ddd;
            background-color: transparemt;
            color: black;
            border: 1px solid grey;
            padding: 10px 35px;
            border-radius: 5px;
            cursor: pointer;
        }
        #edit-profile:hover {
            background-color: rgba(0, 0, 0, 0.06);;
        }
        .main-content {
            margin-left: 370px;
            padding: 20px;
        }
        .navbar {
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            max-height: 40px;
            margin-right: 10px;
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
        .profile-section {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .profile-section h4 {
            margin-bottom: 20px;
        }
        .edit-btn {
            background-color: transparent;
            color: black;
            border: 1px solid #ddd;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .edit-btn:hover {
            background-color: #f0f0f0;
        }
          /* My Jobs Section */
    .profile-section .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    .profile-section .card-body h5 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    /* Notifications Section */
    .list-group-item {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 5px;
    }

    /* Earnings Section */
    .card-body h5 {
        font-size: 20px;
        color: #333;
    }
    .main-content {
    margin-left: 370px; /* Sidebar width + spacing */
    padding: 20px;
    max-width: calc(100% - 370px); /* Prevent overlap */
    overflow: hidden; /* Hide content overflow */
}


    
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile-card">
        <img src="<?php echo $profilePicture ?>" alt="Freelancer Profile">
        <h5><?php $name ?></h5>
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
                    <li class="nav-item"><a class="nav-link" href="#my-jobs">My Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="#settings">Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="#logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overview Panel -->
    <div class="overview-panel">
        <h2>Welcome,<?php echo $name ?>!</h2>
        <p>Here's a summary of your profile:</p>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Completed Projects</h5>
                        <p><strong>[Count]</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Ongoing Projects</h5>
                        <p><strong>[Count]</strong></p>
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
        <p><strong>Availability:</strong> <?php  echo $availability ?></p>
        <p><strong>Education:</strong> <?php echo $degree ?> at <?php echo $institute ?>-<?php echo $graduationYear ?></p>
        <button class="edit-btn">Edit Information</button>
    </div>


<!-- My Jobs Section -->
<div class="profile-section" id="my-jobs" >
    <h4>My Jobs</h4>
    <div class="card">
        <div class="card-body">
            <h5>Project Title 1</h5>
            <p>Client: Client Name</p>
            <p>Status: <span style="color: green;">In Progress</span></p>
            <button class="btn btn-primary btn-sm">View Details</button>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>Project Title 2</h5>
            <p>Client: Client Name</p>
            <p>Status: <span style="color: blue;">Completed</span></p>
            <button class="btn btn-primary btn-sm">View Details</button>
        </div>
    </div>
</div>

<!-- Notifications Section -->
<div class="profile-section" id="notifications">
    <h4>Notifications</h4>
    <ul class="list-group">
        <li class="list-group-item">You received a new job proposal.</li>
        <li class="list-group-item">Client "John Doe" approved your submission.</li>
        <li class="list-group-item">Your profile has been viewed 10 times this week.</li>
    </ul>
</div>

<!-- Earnings Section -->
<div class="profile-section" id="earnings">
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

<!-- Proposals Section -->
<div class="profile-section" id="proposals">
    <h4>Proposals</h4>
    <div class="card">
        <div class="card-body">
            <h5>Proposal Title 1</h5>
            <p>Status: <span style="color: orange;">Pending</span></p>
            <button class="btn btn-primary btn-sm">View Details</button>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>Proposal Title 2</h5>
            <p>Status: <span style="color: green;">Accepted</span></p>
            <button class="btn btn-primary btn-sm">View Details</button>
        </div>
    </div>
</div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

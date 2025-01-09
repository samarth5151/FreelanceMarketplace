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
            border:1px solid #ddd;
            margin-bottom: 20px;
        }
        .sidebar .profile-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .profile-card p{
            text-align: left;
            color:grey;
        }
        .profile-card p span{
            color:black;
        }
        #p1{
            margin-top:40px;
        }

        .sidebar #logout {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #333;
            position: absolute;
            bottom: 0;
            background-color: #f1f1f1;
        color: black;
        border: 1px solid grey;
        padding: 10px 35px;
        border-radius: 5px;
        cursor: pointer;

        }
         #edit {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #333;
            
            background-color: transparent;
        color: black;
        border: 1px solid #ddd;
        padding: 10px 25px;
        border-radius: 5px;
        cursor: pointer;

        }
        .sidebar a:hover {
            background-color:rgb(231, 231, 231);
            
        }
        .main-content {
            margin-left: 370px;
            padding: 20px;
        }
        .navbar {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .jobs{
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
        color: black;
        border: 1px solid #ddd;
        padding: 5px 15px;
        border-radius: 5px;
        cursor: pointer;
    }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile-card">
        <img src="https://via.placeholder.com/100" alt="User Profile">
        <h5>User Name</h5>
        <p id="p1"> <span>Email:</span> user@example.com</p>
        <p> <span>  Mobile:</span>  +1234567890</p>
        <p><span>Gender:</span>  Male</p>
        <p><span>DOB: </span> 01/01/1990</p>

        <a href="#edit" id="edit">Edit Profile</a>
    </div>
    <a id="logout" href="#logout">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img style="width:150px;" src="./Assets/logo2.png" alt=""></a>
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
        <h2>Welcome, [User's Name]!</h2>
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

<!--  post jobs section -->

    <div class="post-job">
    <h4>Post a Job</h4>
    <p>Create and manage job postings directly from this area.</p>
    <button>Post New Job</button>
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

    <!-- Add more sections like Freelancers, Analytics, Messages, etc. -->
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

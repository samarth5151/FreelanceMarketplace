<?php

include('../Connection/connection.php');

session_start();   
if(isset($_SESSION["username"])){
    $username=$_SESSION["username"];

}
else{
    // $username="samarth50";
    header("location: login.php");
}


if (!$db) {
    die("Database connection failed: " . $db->lastErrorMsg());
}

// Fetch user details
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $db->prepare($sql);
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$result = $stmt->execute();

if ($row = $result->fetchArray(SQLITE3_ASSOC)) {

     $name = $row["users_name"];
     $email = $row["users_email"];
     $contactNo = $row["users_contact"];
     $gender = $row["users_gender"];
     $birthdate = $row["users_dob"];
     $profilepath = $row["users_profile_img"];
    $address="";
} else {
    echo "0 results";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Employer profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Removed Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="awesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="../CSS files/users_dashboard.css">

    <style>
        
        .card{box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); background:#fff}

        

        * {
    box-sizing: border-box;
    padding: 0;
    margin: 0;
}

body {
    font-family: "Poppins", sans-serif;
    background-color: whitesmoke;
    color: rgba(0, 0, 0, 0.644);
    margin: 0;
    padding: 0;
}

/*navbar*/
header, .header {
    background: none;

}
nav {
    
    border-radius: 12px;
    border-radius: 12px;
    padding: 30px 20px;
    display: grid;
    grid-template-columns:45% 55%;
    align-items: center;
}
.nav-btns{
    display: flex;
    align-items: center;
}
nav .logo {
    font-size: 24px;
    font-weight: bold;
    padding-left:25px;
    color: rgba(0, 0, 0, 0.644);
}
.logo img {
    height: 40px;
}
nav ul {
    list-style-type: none;
    display: flex;

}

nav ul li {
    margin: 0 15px;
}

nav ul li a {
    text-decoration: none;
    color: rgba(0, 0, 0, 0.644);
    font-weight: 500;
    transition: color 0.3s ease;
    transition: color 0.3s ease, background-color 0.3s ease;
    padding: 20px 20px;
    display: inline-block;

}

nav ul li a:hover {
    color: black;
    

}

/* Hide menu on smaller screens */
.nav-menu {
    display: flex;
}

.menu-icon {
    display: none;
    font-size: 28px;
    cursor: pointer;
    color: #c9d1d9;
}
/* General styling */




/* Dropdown styling */
nav ul li.dropdown {
    position: relative;
}

nav ul li .dropdown-toggle {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 20px 20px;
    color:white;
    background-color:rgba(0, 0, 0, 0.81);
    transition: background-color 0.3s ease;
}

nav ul li .dropdown-toggle:hover {
    background-color:     rgba(0, 0, 0, 0.85);
    color:white;
}

nav ul li .dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #ffffff;
    min-width: 200px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0s 0.3s;
}

nav ul li:hover .dropdown-menu {
    display: block;
    opacity: 1;
    visibility: visible;
    transition: opacity 0.3s ease;
}

/* Styling for the items inside the dropdown */
nav ul li .dropdown-menu a {
    color: rgba(0, 0, 0, 0.644);
    padding: 12px 20px;
    display: block;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

nav ul li .dropdown-menu a:hover {
    background-color: #f8f9fa;
    color: black;
}

/* Optional - Create a smooth animation for the dropdown */
nav ul li.dropdown:hover .dropdown-menu {
    animation: slideIn 0.3s ease-out;
}

/* Dropdown animation */
@keyframes slideIn {
    from {
        transform: translateY(-10px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Adjusting for small screens */
@media screen and (max-width: 768px) {
    nav ul li.dropdown .dropdown-menu {
        min-width: 100%;
        top: 50px;
    }
}



/* Mobile Menu */
@media screen and (max-width: 768px) {
    .nav-menu {
        display: none;
        flex-direction: column;
        background-color: transparent;
        position: absolute;
        top: 70px;
        left: 0;
        width: 100%;
        text-align: center;
        padding: 20px 0;
    }

    .nav-menu.active {
        display: flex;
    }

    .menu-icon {
        display: block;
    }

    nav ul {
        flex-direction: column;
    }

    nav ul li {
        margin: 10px 0;
    }
}
/* Button style */
.btn-71,
.btn-71 *,
.btn-71 :after,
.btn-71 :before,
.btn-71:after,
.btn-71:before {
border: 0 solid;
box-sizing: border-box;
}
.btn-71 {
-webkit-tap-highlight-color: 
transparent;
-webkit-appearance: button;
background-color: 
#000;
background-image: none;
color: 
#fff;
cursor: pointer;
font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif,
Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
font-size: 100%;
line-height: 1;
margin: 0;
-webkit-mask-image: -webkit-radial-gradient(#000, #fff);
padding: 0;
}
.btn-71:disabled {
cursor: default;
}
.btn-71:-moz-focusring {
outline: auto;
}
.btn-71 svg {
display: block;
vertical-align: middle;
}
.btn-71 [hidden] {
display: none;
}
.btn-71 {
border: none;
border-radius: 999px;
box-sizing: border-box;
display: block;
font-weight: 700;
overflow: hidden;
padding: 0.8rem 1.4rem;
position: relative;
}
.btn-71:before {
--opacity: 0.2;
aspect-ratio: 1;
background: 
#000;
border-radius: 50%;
content: "";
left: 50%;
opacity: var(--opacity);
position: absolute;
top: 50%;
transform: translate(-50%, -50%) scale(0);
width: 100%;
z-index: -1;
}
.btn-71:hover:before {
-webkit-animation: enlarge 1s forwards;
animation: enlarge 1s forwards;
}
@-webkit-keyframes enlarge {
to {
opacity: 0;
transform: translate(-50%, -50%) scale(4);
}
}
@keyframes enlarge {
to {
opacity: 0;
transform: translate(-50%, -50%) scale(4);
}
}
.btn70{
font-weight: 700;
font-size: 17px;
background: transparent;
color: rgba(0, 0, 0, 0.644);
border: none;
cursor: pointer;
margin-right: 10px;
}

    </style>

</head>
<body>

<nav >
        <div class="logo"><a href="../index.php">  <img src="../Assets/logo2.png" style="height:50px; margin-left:8px;" ></a></div>
        <ul class="nav-menu">
                <li><a href="allJob.php">Browse all jobs</a></li>
                <li><a href="allFreelancer.php">Browse Freelancers</a></li>
                <li><a href="allEmployer.php">Browse Employers</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $username; ?>
                    </a>
                    <ul class="dropdown-menu list-group list-group-item-info">
                        <a href="employerProfile.php" class="list-group-item"><span class="glyphicon glyphicon-home"></span>  View profile</a>
                        <a href="editEmployer.php" class="list-group-item"><span class="glyphicon glyphicon-inbox"></span>  Edit Profile</a>
                        <a href="message.php" class="list-group-item"><span class="glyphicon glyphicon-envelope"></span>  Messages</a> 
                        <a href="logout.php" class="list-group-item"><span class="glyphicon glyphicon-ok"></span>  Logout</a>
                    </ul>
                </li>
        </ul>
        <div class="nav-btns">
           
        <div class="menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </nav>

       

<!--main body-->
<div style="padding:1% 3% 1% 3%;">
    <div class="row">

        <!--Column 1-->
        <div class="col-lg-3">

        <!--Main profile card-->
        <div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
            <p></p>
            <img src="<?php $profilepath ?>" alt="Profile Image">
            <h2><?php echo $name; ?></h2>
            <p><span class="glyphicon glyphicon-user"></span> <?php echo $username; ?></p>
            <ul>
                <a href="postJob.php" class="list-group-item list-group-item-info">Post a job offer</a>
                <a href="editEmployer.php" class="list-group-item list-group-item-info">Edit Profile</a>
                <a href="message.php" class="list-group-item list-group-item-info">Messages</a>
                <a href="logout.php" class="list-group-item list-group-item-info">Logout</a>
            </ul>
        </div>
        <!--End Main profile card-->

        <!--Contact Information-->
        <div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
            <div class="panel panel-success">
                <div class="panel-heading"><h4>Contact Information</h4></div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">Email</div>
                <div class="panel-body"><?php echo $email; ?></div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">Mobile</div>
                <div class="panel-body"><?php echo $contactNo; ?></div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">Address</div>
                <div class="panel-body"><?php echo $address; ?></div>
            </div>
        </div>
        <!--End Contact Information-->

        <!--Reputation-->
        <div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
            <div class="panel panel-warning">
                <div class="panel-heading"><h4>Reputation</h4></div>
            </div>
            <div class="panel panel-warning">
                <div class="panel-heading">Reviews</div>
                <div class="panel-body">Nothing to show</div>
            </div>
            <div class="panel panel-warning">
                <div class="panel-heading">Ratings</div>
                <div class="panel-body">Nothing to show</div>
            </div>
        </div>
        <!--End Reputation-->

        </div>
        <!--End Column 1-->

        <!--Column 2-->
        <div class="col-lg-7">

        <!--Employer Profile Details-->    
        <div class="card" style="padding:20px 20px 5px 20px;margin-top:20px">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3>Employer Profile Details</h3></div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">Company Name</div>
                <div class="panel-body"><h4><?php echo $company; ?></h4></div>
            </div>
            
            <div class="panel panel-primary">
                <div class="panel-heading">Profile Summery</div>
                <div class="panel-body"><h4><?php echo $profile_sum; ?></h4></div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">Current Job Offerings</div>
                <div class="panel-body"><h4>
                    <table style="width:100%">
                        <tr>
                            <td>Job Id</td>
                            <td>Title</td>
                            <td>Posted on</td>
                        </tr>
                        <?php 
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $job_id=$row["job_id"];
                                $title=$row["title"];
                                $timestamp=$row["timestamp"];

                                echo '
                                <form action="employerProfile.php" method="post">
                                <input type="hidden" name="jid" value="'.$job_id.'">
                                    <tr>
                                    <td>'.$job_id.'</td>
                                    <td><input type="submit" class="btn btn-link btn-lg" value="'.$title.'"></td>
                                    <td>'.$timestamp.'</td>
                                    </tr>
                                </form>
                                ';
                            }
                        } else {
                            echo "<tr><td>Nothing to show</td></tr>";
                        }
                       ?>
                  </table>
              </h4></div>
            </div>

            <div class="panel panel-primary">
              <div class="panel-heading">Privious Job Offerings</div>
              <div class="panel-body"><h4>
                <table style="width:100%">
                    <tr>
                        <td>Job Id</td>
                        <td>Title</td>
                        <td>Posted on</td>
                    </tr>
                    <?php 
                    $sql = "SELECT * FROM job_offer WHERE e_username='$username' and valid=0 ORDER BY timestamp DESC";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $job_id=$row["job_id"];
                            $title=$row["title"];
                            $timestamp=$row["timestamp"];

                            echo '
                            <form action="employerProfile.php" method="post">
                            <input type="hidden" name="jid" value="'.$job_id.'">
                                <tr>
                                <td>'.$job_id.'</td>
                                <td><input type="submit" class="btn btn-link btn-lg" value="'.$title.'"></td>
                                <td>'.$timestamp.'</td>
                                </tr>
                            </form>
                            ';
                        }
                    } else {
                        echo "<tr><td>Nothing to show</td></tr>";
                    }
                   ?>
                  </table>
              </h4></div>
            </div>

        </div>
        </div>
        </div>




    <script>
        const menuIcon = document.querySelector('.menu-icon');
        const navMenu = document.querySelector('.nav-menu');

        menuIcon.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    </script>
</body>
</html>

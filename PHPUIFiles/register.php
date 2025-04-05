<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100..700&family=Poppins:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: whitesmoke;
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 40%;
            max-width: 1200px;
            margin: 80px auto;
            background-color: #2c2c3d;
            padding: 30px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
            color: rgb(32, 159, 75);
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        label {
            color: #eee;
            font-size:14px;
            margin-bottom: 10px;
        }

        input, select, textarea {
            padding: 10px;
            font-size: 13px;
            border: 1px solid #555;
            border-radius: 5px;
            color: black;
            margin-bottom: 10px;
        }

        input[type="checkbox"], input[type="radio"] {
            margin-right: 10px;
        }

        .checkbox-group {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        button {
            padding: 12px;
            font-size: 16px;
            background-color: rgb(32, 159, 75);
            color: white;
            border: none;

            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #28a965;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
            min-width: 250px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .container {
                width: 90%;
                margin: 20px auto;
            }
        }

        /* Animation for form transitions */
        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }

        .fade-in {
            opacity: 1;
            transition: opacity 0.5s ease-in;
        }

        .hidden {
            display: none;
        }




.loginbtn{
    width: 100%;
    margin-bottom:20px;
    text-align: center;
    margin-top: 25px;
}
        .loginbtn a{
            text-decoration: none;
            color:white;
            cursor:pointer;
            font-size:14px;
            text-align:center;
            margin-top:25px;
            
            
        }
        .loginbtn a span{
            color:blue;
        }
    </style>
</head>
<body>

<div class="container" id="mainForm">
    <h2>Create an Account</h2>
    <form style="margin-top:40px;" action="users_store.php" method="POST" id="registrationForm" enctype="multipart/form-data">

        <!-- Personal Details -->
        <div class="form-row">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
        </div>

        <!-- Password -->
        <div class="form-row">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <label for="repassword">Re-enter Password</label>
                <input type="password" id="repassword" name="repassword" placeholder="Re-enter your password" required>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact No.</label>
                <input type="tel" id="contact" name="contact" placeholder="Enter your contact number" required>
            </div>
        </div>

        <!-- Gender and DOB -->
        <div class="form-row">
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
            </div>
        </div>

        <!-- Profile Picture -->
        <div class="form-row">
            <div class="form-group">
                <label for="profile">Profile Picture</label>
                <input type="file" name="profile" id="profile">           
            </div>
        </div>

        <!-- User Type Selection -->
        <div class="checkbox-group">
            <label><input type="radio" name="usertype" value="Client" id="client"> Client</label>
            <label><input type="radio" name="usertype" value="Freelancer" id="freelancer"> Freelancer</label>
        </div>

        <!-- Submit Button -->
        <button type="submit">Register</button>

        <div class="loginbtn">
            <a href="./login.php"> Already have an account,<span> login here.</span></a>
        </div>

    </form>
</div>

<!-- Developer Forms (hidden initially) -->
<div class="container hidden" id="developerForm1">
    <h2>Professional Expertise (Form 1)</h2>
    <form id="freelancerForm1" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="skills">Specialized Skills</label>
                <input type="text" id="skills" name="skills" placeholder="e.g., JavaScript, Python" required>
            </div>
            <div class="form-group">
                <label for="tools">Tools/Software Expertise</label>
                <input type="text" id="tools" name="tools" placeholder="e.g., Adobe Photoshop, AutoCAD" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="tagline">Tagline</label>
                <input type="text" id="tagline" name="tagline" placeholder="e.g., Expert Web Developer" required>
            </div>
            <div class="form-group">
                <label for="aboutMe">Description/About Me</label>
                <textarea id="aboutMe" name="aboutMe" rows="4" placeholder="Short summary of expertise" required></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="experience">Years of Experience</label>
                <input type="number" id="experience" name="experience" placeholder="Years of experience" required>
            </div>
        </div>

        <button type="submit">Next</button>
    </form>
</div>

<div class="container hidden" id="developerForm2">
    <h2>Developer Details (Form 2)</h2>
    <form action="freelancers_store.php" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="languages">Languages Spoken</label>
                <input type="text" id="languages" name="languages" placeholder="Languages spoken" required>
            </div>
            <div class="form-group">
                <label for="resume">Resume Upload</label>
                <input type="file" id="resume" name="resume">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="availability">Availability Status</label>
                <select id="availability" name="availability" required>
                    <option value="full-time">Full-Time</option>
                    <option value="part-time">Part-Time</option>
                    <option value="contract">Contract</option>
                </select>
            </div>
            <div class="form-group">
                <label for="degree">Degree/Program Name</label>
                <input type="text" id="degree" name="degree" placeholder="e.g., B.Tech Computer Science" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="institute">Institute</label>
                <input type="text" id="institute" name="institute" placeholder="Institute name" required>
            </div>
            <div class="form-group">
                <label for="graduationYear">Graduation Year</label>
                <input type="number" id="graduationYear" name="graduationYear" placeholder="Graduation year" required>
            </div>
    </div>


        <button type="submit">Submit</button>

        
    </form>
</div>


<script>
    // Helper to toggle visibility with animations
    function toggleVisibility(hideId, showId) {
        const hideElement = document.getElementById(hideId);
        const showElement = document.getElementById(showId);
        hideElement.classList.add('fade-out');
        setTimeout(() => {
            hideElement.classList.add('hidden');
            hideElement.setAttribute('aria-hidden', 'true');
            showElement.classList.remove('hidden');
            showElement.classList.add('fade-in');
            showElement.setAttribute('aria-hidden', 'false');
        }, 500); // Adjust based on CSS animation timing
    }

    // Main form submission
    document.getElementById('registrationForm').addEventListener('submit', function (e) {
        e.preventDefault();
        if (document.getElementById('freelancer').checked) {
            toggleVisibility('mainForm', 'developerForm1');
        } else {
            
            this.submit();
        }
    });

    // Developer form 1 submission
    document.getElementById('freelancerForm1').addEventListener('submit', function (e) {
        e.preventDefault();
        toggleVisibility('developerForm1', 'developerForm2');
    });

    // Developer form 2 submission (final step)
    document.querySelector('#developerForm2 form').addEventListener('submit', function (e) {
    e.preventDefault();

    // Create a new FormData object to collect all data
    const formData = new FormData();

    // Append data from the registration form (including files)
    const registrationForm = document.getElementById('registrationForm');
    const registrationFormData = new FormData(registrationForm);
    for (const [key, value] of registrationFormData.entries()) {
        formData.append(key, value);
    }

    // Append data from the first developer form
    const freelancerForm1 = document.getElementById('freelancerForm1');
    const freelancerForm1Data = new FormData(freelancerForm1);
    for (const [key, value] of freelancerForm1Data.entries()) {
        formData.append(key, value);
    }

    // Append data from the current developer form (form 2)
    const developerForm2Data = new FormData(this);
    for (const [key, value] of developerForm2Data.entries()) {
        formData.append(key, value);
    }

    // Submit the combined FormData using Fetch API
    fetch('freelancers_store.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            return response.text();
        }
    })
    .then(data => {
        console.log(data);
        window.location.href = 'freelancer_dashboard.php'; // Redirect on success
    })
    .catch(error => {
        console.error('Error:', error);
    });

    });
</script>

</body>
</html>

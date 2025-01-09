<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
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
            position: relative;
        }
        h2 {
            text-align: center;
            color:rgb(32 159 75);
        }
        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        label {
            color: #eee;
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
        input[type="checkbox"] {
            margin-right: 10px;
            color: white;
            background-color: #fff;
        }
        .checkbox-group {
            display: flex;
            justify-content: space-around;
        }
        button {
            padding: 11px;
            font-size: 18px;
            background-color: rgb(32 159 75);
            color: white;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        button:hover {
            background-color: rgb(32 159 75);
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
        }
        /* Animation for form transition */
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
    </style>
</head>
<body>

<div class="container" id="mainForm">
    <h2>Create an Account</h2>
    <form action="users_store.php" method="POST" id="registrationForm" enctype="multipart/form-data">

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
        <label><input type="radio" name="usertype" value="freelancer" id="freelancer"> Freelancer</label>

        </div>

        <!-- Submit Button -->
        <button type="submit">Register</button>
    </form>
</div>

<!-- Developer Forms (hidden initially) -->
<div class="container hidden" id="developerForm1">
    <h2>Professional Expertise (Form 1)</h2>
    <form action="freelancers_store.php" method="POST">
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
    <form action="your_register_backend_script.php" method="POST"  enctype="multipart/form-data" autocomplete="on">
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
    }, 500); // Adjust this based on CSS animation timing
}

// Collect data from all forms and submit to freelancer_store.php
function collectFormData() {
    const mainForm = document.getElementById('registrationForm');
    const freelancerForm1 = document.getElementById('developerForm1');
    const freelancerForm2 = document.getElementById('developerForm2');

    // Get data from the main form
    const mainFormData = new FormData(mainForm);

    // Get data from the freelancer form 1
    const freelancerForm1Data = new FormData(freelancerForm1);

    // Get data from the freelancer form 2
    const freelancerForm2Data = new FormData(freelancerForm2);

    // Combine all data into one object
    const allData = new FormData();

    // Append data from all forms
    mainFormData.forEach((value, key) => {
        allData.append(key, value);
    });
    freelancerForm1Data.forEach((value, key) => {
        allData.append(key, value);
    });
    freelancerForm2Data.forEach((value, key) => {
        allData.append(key, value);
    });

    return allData;
}

// Main form submission
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (document.getElementById('freelancer').checked) {
        // Show the first freelancer form when 'Freelancer' is selected
        toggleVisibility('mainForm', 'developerForm1');
    } else {
        // Submit the form if the user is a client
        this.action = "users_store.php"; // Client registration
        this.method = "POST";
        this.submit();
    }
});

// Developer form 1 submission
document.getElementById('developerForm1').addEventListener('submit', function(e) {
    e.preventDefault();
    // Show the second freelancer form after the first form is filled
    toggleVisibility('developerForm1', 'developerForm2');
});


// Developer form 2 submission
document.getElementById('developerForm2').addEventListener('submit', function(e) {
    e.preventDefault();

    // Collect all the data from the forms
    const allData = collectFormData();

    // Create a new form to submit the data to 'freelancers_store.php'
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'freelancers_store.php'; // Send data to freelancer_store.php

    // Append the collected data to the new form
    for (const [key, value] of allData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }

    // Append the form to the body and submit
    document.body.appendChild(form);
    form.submit();
});
</script>


</body>
</html>

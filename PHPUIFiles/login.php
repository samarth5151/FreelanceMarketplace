<?php
session_start();
// Display errors if they exist
$errors = $_SESSION['login_errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];
unset($_SESSION['login_errors']);
unset($_SESSION['old_input']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            background-color: whitesmoke;
            color: #ffffff;
            position: relative;
        }
        .container {
            display: flex;
            max-width: 800px;
            width: 100%;
            background-color: #2c2c3d;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        .left-section {
            width: 55%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-align: center;
            overflow: hidden;
            position: relative;
        }
        
        .slider {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .slide {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 100%;
            opacity: 0;
            transition: opacity 0.5s, left 0.5s;
        }
        .slide.active {
            left: 0;
            opacity: 1;
        }
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .right-section {
            width: 60%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .right-section h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color:rgb(32 159 75);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #FFFFFF;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 92%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #2c2c3d;
            color: #eee;
        }
        .checkbox-group {
            display: flex;
            gap: 15px;
            margin: 15px 0;
        }
        .checkbox-group label {
            color: #aaa;
        }
        .form-group button {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            background-color:rgb(32 159 75);
            border: none;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: rgb(32 159 75);
        }
        .left-section a{
            margin-top: 40px;
            margin-left: 90px;
        }
        .registerbtn a{
            text-decoration: none;
            color:white;
            cursor:pointer;
            font-size:14px;
        }
        .registerbtn a span{
            color:blue;
        }
        /* Validation styles */
        .error {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: 5px;
        }
        input.error-border, .error-border {
            border: 1px solid #ff6b6b !important;
        }
        .auth-error {
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="slider">
                <div class="slide active">
                    <img src="../Assets/image1.png" alt="Slide 1">
                </div>
                <div class="slide">
                    <img src="../Assets/image2.png" alt="Slide 2">
                </div>
                <div class="slide">
                    <img src="../Assets/image3.png" alt="Slide 3">
                </div>
            </div>
        </div>
        
        <div class="right-section">
            <h2>Login</h2>
            <?php if (isset($errors['auth'])): ?>
                <div class="auth-error"><?php echo $errors['auth']; ?></div>
            <?php endif; ?>
            <form method="POST" action="loginuser.php" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name='username' placeholder="Enter your username" 
                           value="<?php echo htmlspecialchars($old_input['username'] ?? ''); ?>" 
                           class="<?php echo isset($errors['username']) ? 'error-border' : ''; ?>">
                    <div id="usernameError" class="error"><?php echo $errors['username'] ?? ''; ?></div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" 
                           class="<?php echo isset($errors['password']) ? 'error-border' : ''; ?>">
                    <div id="passwordError" class="error"><?php echo $errors['password'] ?? ''; ?></div>
                </div>
                <div class="checkbox-group">
                    <label><input type="radio" name="usertype" value="freelancer" 
                        <?php echo (isset($old_input['usertype']) && $old_input['usertype'] === 'freelancer') ? 'checked' : ''; ?>> Freelancer</label>
                    <label><input type="radio" name="usertype" value="employer" 
                        <?php echo (isset($old_input['usertype']) && $old_input['usertype'] === 'employer') ? 'checked' : ''; ?>> Employer</label>
                </div>
                <div id="usertypeError" class="error"><?php echo $errors['usertype'] ?? ''; ?></div>
                <div class="form-group">
                    <button type="submit">Login</button>
                </div>
                <div class="registerbtn">
                    <a href="./register.php">Don't have an account,<span> register here.</span></a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Slider script
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
        }

        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 6000);

        // Form validation script
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.error').forEach(el => el.textContent = '');
            document.querySelectorAll('input').forEach(el => el.classList.remove('error-border'));
            
            // Validate username
            const username = document.getElementById('username').value.trim();
            if (!username) {
                document.getElementById('usernameError').textContent = 'Username is required';
                document.getElementById('username').classList.add('error-border');
                isValid = false;
            } else if (username.length < 3) {
                document.getElementById('usernameError').textContent = 'Username must be at least 3 characters';
                document.getElementById('username').classList.add('error-border');
                isValid = false;
            }
            
            // Validate password
            const password = document.getElementById('password').value.trim();
            if (!password) {
                document.getElementById('passwordError').textContent = 'Password is required';
                document.getElementById('password').classList.add('error-border');
                isValid = false;
            } else if (password.length < 6) {
                document.getElementById('passwordError').textContent = 'Password must be at least 6 characters';
                document.getElementById('password').classList.add('error-border');
                isValid = false;
            }
            
            // Validate user type
            const usertype = document.querySelector('input[name="usertype"]:checked');
            if (!usertype) {
                document.getElementById('usertypeError').textContent = 'Please select a user type';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
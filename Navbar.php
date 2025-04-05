<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <style>
/* Global Styles */
:root {
            --primary-dark: #262b40;
            --accent-green: rgb(32 159 75);
            --text-primary: rgba(0, 0, 0, 0.644);
            --text-hover: #000000;
            --bg-light: whitesmoke;
            --bg-white: #ffffff;
            --gray-icon: #c9d1d9;
            --button-hover: rgb(81, 81, 81);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        /* Navigation */
        nav {
            background-color: var(--bg-white);
            border-radius: 12px;
            padding: 20px 40px;
            margin: 20px auto;
            max-width: 1480px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .logo img {
            height: 50px;
            transition: transform 0.3s ease;
        }
        
        .logo img:hover {
            transform: translateY(-3px);
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
        }
        
        .nav-menu li {
            margin: 0 15px;
        }
        
        .nav-menu a {
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-menu a:hover {
            color: var(--text-hover);
        }
        
        .nav-menu a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--accent-green);
            transition: width 0.3s ease;
        }
        
        .nav-menu a:hover::after {
            width: 100%;
        }
        
        .nav-btns {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .btn-login {
            font-weight: 600;
            font-size: 1rem;
            background: transparent;
            color: var(--text-primary);
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            color: var(--text-hover);
            background-color: rgba(0,0,0,0.05);
        }
        
        .btn-register {
            background: var(--primary-dark);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .btn-register:hover {
            background: var(--button-hover);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .menu-icon {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-icon);
        }
        
    </style>
</head>
<body>
<nav>
        <div class="logo">
            <img src="./Assets/logo2.png" alt="Freelance Marketplace Logo">
        </div>
        
        <ul class="nav-menu">
            <li><a href="#howitworks">How it Works</a></li>
            <li><a href="./PHPUIFiles/Find-Job.php">Find Work</a></li>
            <li><a href="./PHPUIFiles/about-us.php">About Us</a></li>
            <li><a href="./PHPUIFiles/contact-us.php">Contact Us</a></li>
        </ul>
        
        <div class="nav-btns">
            <a href="PHPUIFiles/login.php">
                <button class="btn-login">Login</button>
            </a>
            <a href="../PHPUIFiles/register.php">
                <button class="btn-register">Register</button>
            </a>
        </div>
        
        <div class="menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </nav>
    
</body>
</html>
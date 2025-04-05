<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Freelance Marketplace</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        /* Hero Section */
        .contact-hero {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #3a4166 100%);
            padding: 100px 5%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .contact-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') no-repeat center center;
            background-size: cover;
            opacity: 0.15;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            color: white;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .hero-content p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 30px;
        }
        
        /* Contact Grid */
        .contact-container {
            max-width: 1400px;
            margin: 80px auto;
            padding: 0 5%;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-bottom: 80px;
        }
        
        /* Contact Info */
        .contact-info {
            background: var(--bg-white);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .contact-info:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .contact-info h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .contact-info h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-green), var(--primary-dark));
            border-radius: 2px;
        }
        
        .contact-info p {
            margin-bottom: 30px;
            color: var(--text-primary);
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        
        .info-icon {
            background-color: rgba(32, 159, 75, 0.1);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            color: var(--accent-green);
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .info-content h3 {
            color: var(--primary-dark);
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .info-content p {
            margin-bottom: 0;
            color: var(--text-primary);
        }
        
        /* Contact Form */
        .contact-form {
            background: var(--bg-white);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .contact-form:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .contact-form h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .contact-form h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-green), var(--primary-dark));
            border-radius: 2px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(32, 159, 75, 0.2);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .submit-btn {
            background: var(--accent-green);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 4px 15px rgba(32, 159, 75, 0.3);
        }
        
        .submit-btn:hover {
            background: #228a4f;
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(32, 159, 75, 0.3);
        }
        
        /* Map Section */
        .map-section {
            margin-bottom: 80px;
        }
        
        .map-section h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .map-section h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-green), var(--primary-dark));
            border-radius: 2px;
        }
        
        .map-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            height: 500px;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        /* Social Links */
        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--bg-white);
            color: var(--primary-dark);
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .social-link:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-content h1 {
                font-size: 2.8rem;
            }
            
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            nav {
                padding: 15px 20px;
            }
            
            .nav-menu {
                display: none;
                position: fixed;
                top: 80px;
                left: 0;
                width: 100%;
                background: var(--bg-white);
                flex-direction: column;
                align-items: center;
                padding: 20px 0;
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                z-index: 1000;
            }
            
            .nav-menu.active {
                display: flex;
            }
            
            .nav-menu li {
                margin: 10px 0;
            }
            
            .menu-icon {
                display: block;
            }
            
            .hero-content h1 {
                font-size: 2.2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .contact-info, .contact-form {
                padding: 30px;
            }
            
            .map-container {
                height: 350px;
            }
        }
        
        @media (max-width: 576px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }
            
            .contact-info h2, .contact-form h2, .map-section h2 {
                font-size: 1.5rem;
            }
            
            .info-item {
                flex-direction: column;
            }
            
            .info-icon {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <img src="../Assets/logo2.png" alt="Freelance Marketplace Logo">
        </div>
        
        <ul class="nav-menu">
            <li><a href="#">How it Works</a></li>
            <li><a href="./Find-Job.php">Find Work</a></li>
            <li><a href="./about-us.php">About Us</a></li>
            <li><a href="./contact-us.jpg">Contact Us</a></li>
        </ul>
        
        <div class="nav-btns">
            <a href="../PHPUIFiles/login.php">
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
    
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="hero-content">
            <h1>Get in Touch With Us</h1>
            <p>We're here to help and answer any questions you might have. We look forward to hearing from you.</p>
        </div>
    </section>
    
    <!-- Contact Grid -->
    <div class="contact-container">
        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info">
                <h2>Contact Information</h2>
                <p>Fill out the form or reach out to us through the channels below.</p>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <h3>Our Address</h3>
                        <p>Vidyanagar, Kolhapur, Maharashtra, India</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h3>Email Us</h3>
                        <p>contact@freelancemarket.com</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="info-content">
                        <h3>Call Us</h3>
                        <p>+91 9766739410</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <h3>Working Hours</h3>
                        <p>Monday - Friday: 9am - 6pm</p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form>
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" class="form-control" placeholder="What's this about?">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" class="form-control" placeholder="How can we help you?" required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="map-section">
            <h2>Our Location</h2>
            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d122283.7940067038!2d74.15646593654023!3d16.708452233416622!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc101f46018a03d%3A0xabc6d354bbb86222!2sBlueStone%20Jewellery%20Dasra%20Chowk%2C%20Kolhapur!5e0!3m2!1sen!2sin!4v1731420447846!5m2!1sen!2sin"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
        
        <!-- Social Links -->
        <div class="social-links">
            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
    
    <!-- Footer will be included here -->
    <?php include('Footer.php'); ?>
    
    <script>
        // Mobile menu toggle
        document.querySelector('.menu-icon').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
        });
        
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
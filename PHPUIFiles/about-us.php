<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Freelance Marketplace</title>
    <style>
        /* Consistent with your design system */
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
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary);
            line-height: 1.6;
            margin: 0;
            padding: 0;
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
        
        /* Main content sections */
        .section {
            padding: 80px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            font-size: 2.5rem;
            color: var(--primary-dark);
            margin-bottom: 40px;
            text-align: center;
            font-weight: 600;
        }
        
        /* About Hero Section */
        .about-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: center;
        }
        
        .about-image {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .about-content {
            background: var(--bg-white);
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .about-content h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
        }
        
        /* Features Section */
        .features {
            background: var(--bg-white);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            padding: 30px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--accent-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .feature-icon svg {
            width: 30px;
            height: 30px;
            fill: white;
        }
        
        .feature-card h3 {
            font-size: 1.4rem;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        
        /* Team Section */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .team-member {
            text-align: center;
        }
        
        .team-member img {
            width: 100%;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .team-member h4 {
            font-size: 1.2rem;
            color: var(--primary-dark);
            margin-bottom: 5px;
        }
        
        .team-member p {
            color: var(--text-primary);
            font-size: 0.9rem;
        }
        
        /* Button styles - matches your system */
        .btn {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .btn-primary {
            background: var(--primary-dark);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:hover {
            background: var(--button-hover);
            transform: translateY(-3px);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--primary-dark);
            border: 2px solid var(--primary-dark);
        }
        
        .btn-secondary:hover {
            background: rgba(0,0,0,0.05);
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .about-hero {
                grid-template-columns: 1fr;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .section {
                padding: 60px 5%;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .section {
                padding: 40px 5%;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .team-grid {
                grid-template-columns: 1fr;
            }
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
        
    </style>
    <!-- Include your existing navbar styles here -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navigation - matches your existing navbar -->
    <nav style="max-width:1480px;">
        <div class="logo">
            <img src="../Assets/logo2.png" alt="Freelance Marketplace Logo">
        </div>
        
        <ul class="nav-menu">
            <li><a href="#">How it Works</a></li>
            <li><a href="..//PHPUIFiles/Find-Job.php">Find Work</a></li>
            <li><a href="../PHPUIFiles/about-us.php">About Us</a></li>
            <li><a href="./contact-us.php">Contact Us</a></li>
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
    </nav>

    <!-- Hero Section -->
    <section class="contact-hero" style="height:200px;">
        <div class="hero-content">
            <h1>About Us</h1>
            <p>We bridge the gap between top-tier freelancers and businesses—empowering work without boundaries.</p>
        </div>
    </section>

    <!-- About Hero Section -->
    <section class="section">
        <div class="about-hero">
            <div class="about-image">
                <img src="about-us.png" alt="About Our Platform">
            </div>
            <div class="about-content">
                <h2>About Our Freelance Marketplace</h2>
                <p>We connect talented freelancers with businesses looking for quality work. Our platform is designed to make the hiring process simple, efficient, and rewarding for both clients and freelancers.</p>
                
                <div class="features">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z"/></svg>
                        </div>
                        <h3>Verified Professionals</h3>
                        <p>All freelancers on our platform undergo a rigorous verification process to ensure quality and reliability.</p>
                    </div>
                </div>
                
                <a href="#" class="btn btn-primary">Learn More</a>
            </div>
        </div>
    </section>
    
    <!-- Key Features Section -->
    <section class="section" style="background-color: var(--bg-light);">
        <h2 class="section-title">Why Choose Our Platform</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/></svg>
                </div>
                <h3>Secure Payments</h3>
                <p>Our escrow system ensures freelancers get paid and clients only pay for work they approve.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                </div>
                <h3>Project Management</h3>
                <p>Built-in tools to help you manage projects, communicate, and track progress all in one place.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                </div>
                <h3>Talent Matching</h3>
                <p>Our algorithm helps match you with the perfect freelancer or project based on your skills and needs.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zm1-11h-2v3H8v2h3v3h2v-3h3v-2h-3V8z"/></svg>
                </div>
                <h3>24/7 Support</h3>
                <p>Our dedicated support team is available around the clock to assist with any questions or issues.</p>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="section">
        <h2 class="section-title">Meet Our Team</h2>
        <p style="text-align: center; max-width: 700px; margin: 0 auto 40px;">We're a passionate team dedicated to creating the best freelance marketplace experience for both clients and freelancers.</p>
        
        <div class="team-grid">
            <div class="team-member">
                <img src="team1.jpg" alt="Omkar Patil">
                <h4>Omkar Patil</h4>
                <p>CEO & Founder</p>
            </div>
            
            <div class="team-member">
                <img src="team2.jpg" alt="Atharv Kulkarni">
                <h4>Atharv Kulkarni</h4>
                <p>CTO</p>
            </div>
            
            <div class="team-member">
                <img src="team3.jpg" alt="Samarth Patil">
                <h4>Samarth Patil</h4>
                <p>Lead Developer</p>
            </div>
            
            <div class="team-member">
                <img src="team4.jpg" alt="Rushikesh Yadav">
                <h4>Rushikesh Yadav</h4>
                <p>UX Designer</p>
            </div>
            
            <div class="team-member">
                <img src="team5.jpg" alt="Vivek Dalvi">
                <h4>Vivek Dalvi</h4>
                <p>Marketing Director</p>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="section" style="background-color: var(--primary-dark); color: white;">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center;">
            <div>
                <h3 style="font-size: 2.5rem; margin-bottom: 10px;">10,000+</h3>
                <p>Freelancers</p>
            </div>
            <div>
                <h3 style="font-size: 2.5rem; margin-bottom: 10px;">5,000+</h3>
                <p>Clients</p>
            </div>
            <div>
                <h3 style="font-size: 2.5rem; margin-bottom: 10px;">25,000+</h3>
                <p>Projects Completed</p>
            </div>
            <div>
                <h3 style="font-size: 2.5rem; margin-bottom: 10px;">$5M+</h3>
                <p>Paid to Freelancers</p>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section" style="text-align: center;">
        <h2 class="section-title">Ready to Get Started?</h2>
        <p style="max-width: 600px; margin: 0 auto 30px;">Join thousands of freelancers and businesses who are already benefiting from our platform.</p>
        <div style="display: flex; gap: 20px; justify-content: center;">
            <a href="../PHPUIFiles/register.php" class="btn btn-primary">Sign Up Now</a>
            <a href="./contact-us.php" class="btn btn-secondary">Contact Us</a>
        </div>
    </section>
    
    <script>
        // Mobile menu toggle functionality
        document.querySelector('.menu-icon').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
        });
    </script>
</body>
</html>
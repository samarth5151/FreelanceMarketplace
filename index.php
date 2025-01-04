<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelance Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css"> 
    <link rel="stylesheet" href="./CSS files/step-section.css">
    <link rel="stylesheet" href="./CSS files/call-to-action.css">
    <link rel="stylesheet" href="./CSS files/footer.css" >
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
       
    </style>
</head>
<body>
    <header  >
    <nav >
        <div class="logo"><img src="./Assets/logo2.png" style="height:50px; margin-left:8px;" ></div>
        <ul class="nav-menu">
            <li><a href="#">How it Works</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Find Work</a></li>
        </ul>
        <div class="nav-btns">
           
        <a href="PHPUIFiles/login.php">
               <button href="#"  class="btn70" style="margin-right: 15px;">Login</button>
            </a>
            <a href="PHPUIFiles/register.php">
               <button class="btn-71" style="margin-right: 15px;">Register</button>
            </a>

        </div>
        <div class="menu-icon">
            <i class="fas fa-bars"></i>
        </div>
    </nav>


<?php
    $heroimg = "./Assets/30257374.jpg";
?>
<div class="hero-section">
    <section class="content">
        <h1> <span> Find </span>Talent, </h1> 
        <h1><span> Hire </span> with Confidence,</h1> 
        <h1><span> Work</span> Seamlessly</h1>
        <p>Verified software talent and smart tools to accelerate your tech initiatives</p>
        <div class="cta-buttons">
            <button class="btn-71 btnhero12">Post a Job</button>
            <button id="btn-button119" class="btn70 btnhero11">Find jobs</button>
        </div>
    </section>
    
        <!-- Corrected to echo the image path -->
        <img class="hero-img" src="<?php echo $heroimg; ?>" alt="Hero-Image">
    
</div>


<section class="steps-section">
        <h2>How does it work?</h2>
        <h1>Talent Acquisition Made Simple</h1>
        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Find Talent Instantly</h3>
                <p>Submit your job description, and our AI-powered platform instantly provides a tailored list of verified candidates for you to meet.</p>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <h3>Hire with Confidence</h3>
                <p>Once you're ready to hire, we handle contracts, negotiate terms, and manage onboarding.</p>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <h3>Work Seamlessly</h3>
                <p>We manage ongoing billing and compliance, keeping everything smooth while your team focuses on delivering results.</p>
            </div>

            <div class="step">
                <div class="step-number">4</div>
                <h3>Optimize</h3>
                <p>Rinse and repeat for your whole engineering team.</p>
            </div>
        </div>
        <div class="btn21-con">
        <button class="btn-71 ">Start today</button>

        </div>

</section>

<?php
$image_path = "/MegaProject/Assets/2YgsRc.webp";
?>

<section style="padding: 80px 20px; color: #FFFFFF; text-align: center; position: relative;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;background-image: url('<?php echo $image_path; ?>') !important; background-attachment: fixed; background-size: cover; background-position: center;"></div>

    <div style="position: relative; z-index: 1; max-width: 800px; margin: 0 auto;">
        <h2 style="font-size: 2.5em; color: rgba(0, 0, 0, 0.644); font-weight: bold; margin-bottom: 20px; ">Join the Leading Freelance Community</h2>
        <p style="font-size: 1.2em; margin-bottom: 40px;">
            Whether you’re a client looking for skilled talent or a developer searching for exciting projects, CodeBrains has you covered. Start connecting, collaborating, and creating today!
        </p>

        <div style="display: flex; justify-content: center; gap: 20px;">
            <a href="/register-client" style="text-decoration:none;" class="btn-71 ">
                Join as Client
            </a>
            
        </div>
    </div>
</section>



<footer style="background-color: #1A1A1A; color: #999; padding: 40px; font-family: Poppins, sans-serif;">
    <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 20px;">

        <div style="flex: 1; min-width: 200px;">
            <h4 style="color:rgb(32 159 75); margin-bottom: 15px;">About Us</h4>
            <p style="line-height: 1.6; font-size:15px;color:#eee; padding-right:5px;">
                CodeBrains connects clients with skilled developers worldwide. Our secure platform, skill-based matching, and project analysis tools make us the go-to for quality freelancing.
            </p>
        </div>

        <div  style="flex: 1; min-width: 200px;">
            <h4  style="color:rgb(32 159 75); margin-bottom: 15px;">Quick Links</h4>
            <ul class="footer1" style="list-style: none; padding: 0; line-height: 1.8; color: #999;">
                <li><a href="#" style=" text-decoration: none  ">Home</a></li>
                <li><a href="#" style="  text-decoration: none;  ">About Us</a></li>
                <li><a href="#" style="  text-decoration: none;  ">Post a Job</a></li>
                <li><a href="#" style="  text-decoration: none;  ">Find Work</a></li>
                <li><a href="#" style="  text-decoration: none;  ">FAQs</a></li>
            </ul>
        </div>


        <div style="flex: 1; min-width: 200px;">
            <h4 style="color:rgb(32 159 75); margin-bottom: 15px;">Resources</h4>
            <ul class="footer1" style="list-style: none; padding: 0; line-height: 1.8;">
                <li><a href="#" style=" text-decoration: none;">Blog</a></li>
                <li><a href="#" style=" text-decoration: none;">Help Center</a></li>
                <li><a href="#" style=" text-decoration: none;">Privacy Policy</a></li>
                <li><a href="#" style=" text-decoration: none;">Terms of Service</a></li>
            </ul>
        </div>

        <div  style="flex: 1; min-width: 200px;">
            <h4 style="color:rgb(32 159 75); margin-bottom: 15px;">Contact Us</h4>
            <p style="color: rgb(192, 192, 192);">Email: <a href="mailto:support@website.com" style="color:rgb(32 159 75); text-decoration: none;">support@website.com</a></p>
            <p style="color: rgb(192, 192, 192);">Phone: <a href="tel:+123456789" style="color:rgb(32 159 75); text-decoration: none;">+123 456 789</a></p>
            <p style="color: rgb(192, 192, 192);">Follow us on:
                <a href="#" style="color:rgb(32 159 75); text-decoration: none; margin: 0 5px;">LinkedIn</a> |
                <a href="#" style="color:rgb(32 159 75); text-decoration: none; margin: 0 5px;">Twitter</a> |
                <a href="#" style="color:rgb(32 159 75); text-decoration: none; margin: 0 5px;">Instagram</a>
            </p>
        </div>
    </div>


    <div style="margin-top: 40px; text-align: center; border-top: 1px solid #333; padding-top: 20px;">
        <p style="color: rgb(192, 192, 192);">&copy; 2024 <span style="color:rgb(32 159 75);">CodeBrains</span>. All rights reserved.</p>
    </div>

</footer>



    <script>
        const menuIcon = document.querySelector('.menu-icon');
        const navMenu = document.querySelector('.nav-menu');

        menuIcon.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    </script>
</body>
</html>

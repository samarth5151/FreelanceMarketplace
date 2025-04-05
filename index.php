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
    <?php
         include('./Navbar.php');
    ?>


<?php
    $heroimg = "./Assets/freepik__upload__4798.jpg";
?>
<div class="hero-section">
    <section class="content">
        <h1> <span> Find </span>Talent, </h1> 
        <h1><span> Hire </span> with Confidence,</h1> 
        <h1><span> Work</span> Seamlessly</h1>
        <p>Verified software talent and smart tools to accelerate your tech initiatives</p>
        <div class="cta-buttons">
            <a class="btn-71 btnhero12" href="PHPUIFiles/Post-Job.php">Post a Job</a>
            <button id="btn-button119" class="btn70 btnhero11">Find jobs</button>
        </div>
    </section>
    
        <dotlottie-player
  src="https://lottie.host/02bd7189-3912-496a-be6a-02d04b83e026/rAApzAnrT8.lottie"
  background="transparent"
  speed="1"
  style="width: 550px; height: 550px;margin-left:100px;margin-top:10px;"
  loop
  autoplay
></dotlottie-player>
    
</div>


<section class="steps-section" id="howitworks">
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
$image_path = "./Assets/2YgsRc.webp";
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

<?php
         include('./PHPUIFiles/Footer.php');
    ?>

    <script>
        const menuIcon = document.querySelector('.menu-icon');
        const navMenu = document.querySelector('.nav-menu');

        menuIcon.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    </script>
<script
  src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
  type="module"
></script>

</body>
</html>

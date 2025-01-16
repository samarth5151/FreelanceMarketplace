<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <style>
        /*navbar*/
header, .header {
    background: none;
}
nav {
    border-radius: 12px;
    border-radius: 12px;
    padding: 30px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.nav-btns{
    display: flex;
    align-items: center;
}
nav .logo {
    font-size: 24px;
    font-weight: bold;
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
        <div class="logo"><img src="../Assets/logo2.png" style="height:50px; margin-left:8px;" ></div>
        <ul class="nav-menu">
            <li><a href="#">How it Works</a></li>
            <li><a href="#">Find Work</a></li>
            <li><a href="./PHPUIFiles/about-us.php">About Us</a></li>
            <li><a href="./PHPUIFiles/contact-us.php">Contact Us</a></li>
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
    
</body>
</html>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  body {
    font-family: "Poppins", sans-serif;
    background-color: whitesmoke;
    color: rgba(0, 0, 0, 0.644);
    margin: 0;
    padding: 0;
}

  
.contact-hero-section {
    background: 
        url("../Assets/Footer-banner.png") no-repeat right center, /* Position the image */
        #EBE4FF; /* Fallback background color or matching color from the image */
    background-size: auto 100%; /* Ensure the image covers height and aligns right */
    position: relative;
  
    padding: 80px 20px;
    text-align: center;
    color: white; /* Adjust text color for readability */
}

.contact-hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Black overlay with 50% opacity */
    z-index: 1;
}

.contact-hero-content {
    position: relative;
    z-index: 2; /* Ensures content is above the overlay */
}

  .contact-hero-content h2 {
    font-size: 3rem;
    color: white;
  }
  
  .contact-hero-content p {
    font-size: 1.2rem;
    color: #333;
    margin-top: 15px;
  }
  
  /* Contact Info Section */
  .contact-info-section {
    display: flex;
    flex-wrap: wrap;
    padding: 60px 20px;
    background-color: #fff;
    gap: 30px;
    justify-content: center;
  }
  
  .contact-info, .contact-form {
    flex: 1;
    min-width: 300px;
    max-width: 500px;
  }
  
  .contact-info h2, .contact-form h2 {
    color: #333;
    margin-bottom: 15px;
  }
  
  .info-items {
    margin-top:20px;
    display: grid;
    gap: 15px;
  }
  
  .info-item h3 {
    color: rgb(32 159 75);
    font-size: 1.1rem;
    margin-bottom: 5px;
  }
  
  .info-item p {
    color: #555;
  }
  
  /* Contact Form */
  .contact-form label {
    font-size: 1rem;
    color: #333;
    display: block;
    margin-top: 15px;
  }
  
  .contact-form input, .contact-form textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }
  
  .contact-form button {
    background-color: rgb(32 159 75);
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    margin-top: 15px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s;
  }
  
  .contact-form button:hover {
    background-color: rgb(32 159 75);
  }
  
  /* Map Section */
  .map-section {
    background-color: #f9f9f9;
    padding: 50px 20px;
    text-align: center;
  }
  
  .map-section h2 {
    font-size: 2.2rem;
    color: #333;
    margin-bottom: 20px;
  }
  
  .map-container {
    max-width: 100%;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
  }
  
  /* Responsive Design */
  @media (max-width: 768px) {
    .contact-hero-content h1 {
      font-size: 2.5rem;
    }
  
    .contact-info, .contact-form {
      max-width: 100%;
    }
  }
  
    </style>
    
</head>
<body>


<div class="contact-us">
  <section class="contact-hero-section">
    <div class="contact-hero-content">
      <h2>Contact Us</h2>
      <p>We'd love to hear from you! Reach out to us for any queries or support.</p>
    </div>
  </section>

  <section class="contact-info-section">
    <div class="contact-info">
      <h2>Get in Touch</h2>
      <p>If you have any questions, feel free to contact us by filling out the form or using the information below.</p>

      <div class="info-items">
        <div class="info-item">
          <h3>Address</h3>
          <p>Vidyanagar, Kolhapur, India</p>
        </div>
        <div class="info-item">
          <h3>Email</h3>
          <p>Samarth@gmail.com</p>
        </div>
        <div class="info-item">
          <h3>Phone</h3>
          <p>+91 9766739410</p>
        </div>
      </div>
    </div>

    <div class="contact-form">
      <h2>Contact Form</h2>
      <form>
        <label for="name">Name</label>
        <input type="text" id="name" placeholder="Your Name" required />

        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Your Email" required />

        <label for="message">Message</label>
        <textarea id="message" rows="4" placeholder="Your Message" required></textarea>

        <button type="submit">Send Message</button>
      </form>
    </div>
  </section>

  <section class="map-section">
    <h2>Our Location</h2>
    <div class="map-container">
      <iframe
        title="Our Location"
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d122283.7940067038!2d74.15646593654023!3d16.708452233416622!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc101f46018a03d%3A0xabc6d354bbb86222!2sBlueStone%20Jewellery%20Dasra%20Chowk%2C%20Kolhapur!5e0!3m2!1sen!2sin!4v1731420447846!5m2!1sen!2sin"
        width="100%"
        height="400"
        allowfullscreen=""
        loading="lazy"
      ></iframe>
    </div>
  </section>
</div>

</body>
</html>
<?php

include('Footer.php');

?>
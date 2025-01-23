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
    padding: 25px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color:#fff;
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
nav ul li a:active {
    color:black;
    font-weight:500;
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
#262b40;
background-image: none;
text-decoration: none;
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
grey;
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
        <div class="logo"><img src="./Assets/logo2.png" style="height:50px; margin-left:8px;" ></div>
        <ul class="nav-menu">
            <li><a href="#howitworks">How it Works</a></li>
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
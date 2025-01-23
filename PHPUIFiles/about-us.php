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
    transition: color 0.3s ease;
    font-size:1rem;
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
        <div class="logo"><img src="../Assets/logo2.png" style="height:50px; margin-left:8px;" ></div>
        <ul class="nav-menu">
            <li><a href="#">How it Works</a></li>
            <li><a href="#">Find Work</a></li>
            <li><a href="C:\xampp\htdocs\FreelanceMarketplace\PHPUIFiles\about-us.php">About Us</a></li>
            <li><a href="./contact-us.php">Contact Us</a></li>
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
<html style="font-size: 16px;" lang="en"><head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="keywords" content="About us, Our team">
    <meta name="description" content="">
    <title>Page 2</title>
    <link rel="stylesheet" href="nicepage.css" media="screen">
    <link rel="stylesheet" href="about-us.css" media="screen">
    <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
    <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
    <meta name="generator" content="Nicepage 7.2.3, nicepage.com">
    <meta name="referrer" content="origin">
    
    
    <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">
    <link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">
    <script type="application/ld+json">{
		"@context": "http://schema.org",
		"@type": "Organization",
		"name": ""
}</script>
    <meta name="theme-color" content="#478ac9">
    <meta property="og:title" content="Page 2">
    <meta property="og:type" content="website">
  <meta data-intl-tel-input-cdn-path="intlTelInput/"></head>
  <body data-path-to-root="./" data-include-products="false" class="u-body u-xl-mode" data-lang="en">
 
    <section class="u-clearfix u-section-1" id="sec-3a72">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="data-layout-selected u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-1">
                <div class="u-container-layout u-container-layout-1">
                  <img class="u-expanded-width u-image u-image-contain u-image-default u-image-1" src="about-us.png" alt="" data-image-width="1986" data-image-height="1566">
                </div>
              </div>
              <div class="u-container-align-center-sm u-container-align-center-xs u-container-style u-custom-color-5 u-layout-cell u-size-30 u-layout-cell-2">
                <div class="u-container-layout u-container-layout-2">
                  <h2 class="u-align-center-sm u-align-center-xs u-custom-font u-text u-text-default u-text-1">About us</h2>
                  <div class="u-accordion u-expanded-width u-accordion-1">
                    <div class="u-accordion-item">
                      <a class="active u-accordion-link u-active-palette-3-base u-button-style u-custom-color-7 u-custom-font u-accordion-link-1" id="link-5dc6" aria-controls="5dc6" aria-selected="true">
                        <span class="u-accordion-link-text"> Business Support</span>
                      </a>
                      <div class="u-accordion-active u-accordion-pane u-container-style u-custom-color-6 u-accordion-pane-1" id="5dc6" aria-labelledby="link-5dc6">
                        <div class="u-container-layout u-container-layout-3">
                          <p class="u-text u-text-default u-text-2"> Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.&nbsp;</p>
                        </div>
                      </div>
                    </div>
                    <div class="u-accordion-item">
                      <a class="u-accordion-link u-active-palette-3-base u-button-style u-custom-color-7 u-custom-font u-accordion-link-2" id="link-4d21" aria-controls="4d21" aria-selected="false">
                        <span class="u-accordion-link-text"> Coworking Investment</span>
                      </a>
                      <div class="u-accordion-pane u-container-style u-custom-color-6 u-accordion-pane-2" id="4d21" aria-labelledby="link-4d21">
                        <div class="u-container-layout u-container-layout-4">
                          <p class="u-text u-text-default u-text-3"> Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.&nbsp;</p>
                        </div>
                      </div>
                    </div>
                    <div class="u-accordion-item">
                      <a class="u-accordion-link u-active-palette-3-base u-button-style u-custom-color-7 u-custom-font u-accordion-link-3" id="link-cf20" aria-controls="cf20" aria-selected="false">
                        <span class="u-accordion-link-text"> Innovators Community</span>
                      </a>
                      <div class="u-accordion-pane u-container-style u-custom-color-6 u-accordion-pane-3" id="cf20" aria-labelledby="link-cf20">
                        <div class="u-container-layout u-container-layout-5">
                          <p class="u-text u-text-default u-text-4"> Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.&nbsp;</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <a href="#" class="u-align-center-sm u-align-center-xs u-border-hover-black u-border-none u-btn u-btn-round u-button-style u-custom-color-1 u-hover-white u-radius-30 u-text-body-alt-color u-text-hover-black u-btn-1">read more</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-section-2" id="sec-1fde">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-container-align-center-sm u-container-align-center-xs u-container-align-left-lg u-container-align-left-md u-container-align-left-xl u-container-style u-list-item u-repeater-item">
              <div class="u-container-layout u-similar-container u-container-layout-1">
                <span class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-file-icon u-icon u-icon-circle u-palette-3-base u-text-white u-icon-1">
                  <img src="images/818217-18483f7d.png" alt="">
                </span>
                <h5 class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-custom-font u-text u-text-default u-text-1">Fresh Coffee</h5>
                <p class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-text u-text-default u-text-2"> Article evident arrived express highest men did boy. Mistress sensible entirely am so. Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.</p>
              </div>
            </div>
            <div class="u-container-align-center-sm u-container-align-center-xs u-container-align-left-lg u-container-align-left-md u-container-align-left-xl u-container-style u-list-item u-repeater-item">
              <div class="u-container-layout u-similar-container u-container-layout-2">
                <span class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-icon u-icon-circle u-palette-3-base u-text-white u-icon-2">
                  <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 58 58" style=""><use xlink:href="#svg-2834"></use></svg>
                  <svg class="u-svg-content" viewBox="0 0 58 58" x="0px" y="0px" id="svg-2834" style="enable-background:new 0 0 58 58;"><path d="M50.688,48.222C55.232,43.101,58,36.369,58,29c0-7.667-2.996-14.643-7.872-19.834c0,0,0-0.001,0-0.001
	c-0.004-0.006-0.01-0.008-0.013-0.013c-5.079-5.399-12.195-8.855-20.11-9.126l-0.001-0.001L29.439,0.01C29.293,0.005,29.147,0,29,0
	s-0.293,0.005-0.439,0.01l-0.563,0.015l-0.001,0.001c-7.915,0.271-15.031,3.727-20.11,9.126c-0.004,0.005-0.01,0.007-0.013,0.013
	c0,0,0,0.001-0.001,0.002C2.996,14.357,0,21.333,0,29c0,7.369,2.768,14.101,7.312,19.222c0.006,0.009,0.006,0.019,0.013,0.028
	c0.018,0.025,0.044,0.037,0.063,0.06c5.106,5.708,12.432,9.385,20.608,9.665l0.001,0.001l0.563,0.015C28.707,57.995,28.853,58,29,58
	s0.293-0.005,0.439-0.01l0.563-0.015l0.001-0.001c8.185-0.281,15.519-3.965,20.625-9.685c0.013-0.017,0.034-0.022,0.046-0.04
	C50.682,48.241,50.682,48.231,50.688,48.222z M2.025,30h12.003c0.113,4.239,0.941,8.358,2.415,12.217
	c-2.844,1.029-5.563,2.409-8.111,4.131C4.585,41.891,2.253,36.21,2.025,30z M8.878,11.023c2.488,1.618,5.137,2.914,7.9,3.882
	C15.086,19.012,14.15,23.44,14.028,28H2.025C2.264,21.493,4.812,15.568,8.878,11.023z M55.975,28H43.972
	c-0.122-4.56-1.058-8.988-2.75-13.095c2.763-0.968,5.412-2.264,7.9-3.882C53.188,15.568,55.736,21.493,55.975,28z M28,14.963
	c-2.891-0.082-5.729-0.513-8.471-1.283C21.556,9.522,24.418,5.769,28,2.644V14.963z M28,16.963V28H16.028
	c0.123-4.348,1.035-8.565,2.666-12.475C21.7,16.396,24.821,16.878,28,16.963z M30,16.963c3.179-0.085,6.3-0.566,9.307-1.438
	c1.631,3.91,2.543,8.127,2.666,12.475H30V16.963z M30,14.963V2.644c3.582,3.125,6.444,6.878,8.471,11.036
	C35.729,14.45,32.891,14.881,30,14.963z M40.409,13.072c-1.921-4.025-4.587-7.692-7.888-10.835
	c5.856,0.766,11.125,3.414,15.183,7.318C45.4,11.017,42.956,12.193,40.409,13.072z M17.591,13.072
	c-2.547-0.879-4.991-2.055-7.294-3.517c4.057-3.904,9.327-6.552,15.183-7.318C22.178,5.38,19.512,9.047,17.591,13.072z M16.028,30
	H28v10.038c-3.307,0.088-6.547,0.604-9.661,1.541C16.932,37.924,16.141,34.019,16.028,30z M28,42.038v13.318
	c-3.834-3.345-6.84-7.409-8.884-11.917C21.983,42.594,24.961,42.124,28,42.038z M30,55.356V42.038
	c3.039,0.085,6.017,0.556,8.884,1.4C36.84,47.947,33.834,52.011,30,55.356z M30,40.038V30h11.972
	c-0.113,4.019-0.904,7.924-2.312,11.58C36.547,40.642,33.307,40.126,30,40.038z M43.972,30h12.003
	c-0.228,6.21-2.559,11.891-6.307,16.348c-2.548-1.722-5.267-3.102-8.111-4.131C43.032,38.358,43.859,34.239,43.972,30z
	 M9.691,47.846c2.366-1.572,4.885-2.836,7.517-3.781c1.945,4.36,4.737,8.333,8.271,11.698C19.328,54.958,13.823,52.078,9.691,47.846
	z M32.521,55.763c3.534-3.364,6.326-7.337,8.271-11.698c2.632,0.945,5.15,2.209,7.517,3.781
	C44.177,52.078,38.672,54.958,32.521,55.763z"></path></svg>
                </span>
                <h5 class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-custom-font u-text u-text-default u-text-3">Unlimited Internet</h5>
                <p class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-text u-text-default u-text-4"> Article evident arrived express highest men did boy. Mistress sensible entirely am so. Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.</p>
              </div>
            </div>
            <div class="u-container-align-center-sm u-container-align-center-xs u-container-align-left-lg u-container-align-left-md u-container-align-left-xl u-container-style u-list-item u-repeater-item">
              <div class="u-container-layout u-similar-container u-container-layout-3">
                <span class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-file-icon u-icon u-icon-circle u-palette-3-base u-text-white u-icon-3">
                  <img src="images/149412-c15d49ae.png" alt="">
                </span>
                <h5 class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-custom-font u-text u-text-default u-text-5">Conference rooms</h5>
                <p class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-text u-text-default u-text-6"> Article evident arrived express highest men did boy. Mistress sensible entirely am so. Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.</p>
              </div>
            </div>
            <div class="u-container-align-center-sm u-container-align-center-xs u-container-align-left-lg u-container-align-left-md u-container-align-left-xl u-container-style u-list-item u-repeater-item">
              <div class="u-container-layout u-similar-container u-container-layout-4">
                <span class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-icon u-icon-circle u-palette-3-base u-text-white u-icon-4">
                  <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 60 60" style=""><use xlink:href="#svg-1e58"></use></svg>
                  <svg class="u-svg-content" viewBox="0 0 60 60" x="0px" y="0px" id="svg-1e58" style="enable-background:new 0 0 60 60;"><g><path d="M30,0C13.458,0,0,13.458,0,30s13.458,30,30,30s30-13.458,30-30S46.542,0,30,0z M30,58C14.561,58,2,45.439,2,30
		S14.561,2,30,2s28,12.561,28,28S45.439,58,30,58z"></path><path d="M31,26.021V15.879c0-0.553-0.448-1-1-1s-1,0.447-1,1v10.142c-1.399,0.364-2.494,1.459-2.858,2.858H19c-0.552,0-1,0.447-1,1
		s0.448,1,1,1h7.142c0.447,1.72,2,3,3.858,3c2.206,0,4-1.794,4-4C34,28.02,32.72,26.468,31,26.021z M30,31.879c-1.103,0-2-0.897-2-2
		s0.897-2,2-2s2,0.897,2,2S31.103,31.879,30,31.879z"></path><path d="M30,9.879c0.552,0,1-0.447,1-1v-1c0-0.553-0.448-1-1-1s-1,0.447-1,1v1C29,9.432,29.448,9.879,30,9.879z"></path><path d="M30,49.879c-0.552,0-1,0.447-1,1v1c0,0.553,0.448,1,1,1s1-0.447,1-1v-1C31,50.326,30.552,49.879,30,49.879z"></path><path d="M52,28.879h-1c-0.552,0-1,0.447-1,1s0.448,1,1,1h1c0.552,0,1-0.447,1-1S52.552,28.879,52,28.879z"></path><path d="M9,28.879H8c-0.552,0-1,0.447-1,1s0.448,1,1,1h1c0.552,0,1-0.447,1-1S9.552,28.879,9,28.879z"></path><path d="M44.849,13.615l-0.707,0.707c-0.391,0.391-0.391,1.023,0,1.414c0.195,0.195,0.451,0.293,0.707,0.293
		s0.512-0.098,0.707-0.293l0.707-0.707c0.391-0.391,0.391-1.023,0-1.414S45.24,13.225,44.849,13.615z"></path><path d="M14.444,44.021l-0.707,0.707c-0.391,0.391-0.391,1.023,0,1.414c0.195,0.195,0.451,0.293,0.707,0.293
		s0.512-0.098,0.707-0.293l0.707-0.707c0.391-0.391,0.391-1.023,0-1.414S14.834,43.631,14.444,44.021z"></path><path d="M45.556,44.021c-0.391-0.391-1.023-0.391-1.414,0s-0.391,1.023,0,1.414l0.707,0.707c0.195,0.195,0.451,0.293,0.707,0.293
		s0.512-0.098,0.707-0.293c0.391-0.391,0.391-1.023,0-1.414L45.556,44.021z"></path><path d="M15.151,13.615c-0.391-0.391-1.023-0.391-1.414,0s-0.391,1.023,0,1.414l0.707,0.707c0.195,0.195,0.451,0.293,0.707,0.293
		s0.512-0.098,0.707-0.293c0.391-0.391,0.391-1.023,0-1.414L15.151,13.615z"></path>
</g></svg>
                </span>
                <h5 class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-custom-font u-text u-text-default u-text-7">24 hours a day</h5>
                <p class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-text u-text-default u-text-8"> Article evident arrived express highest men did boy. Mistress sensible entirely am so. Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-section-3" id="carousel_9f44">
      <div class="u-clearfix u-sheet u-sheet-1">
        <h2 class="u-align-center u-custom-font u-text u-text-black u-text-1">Our team</h2>
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-container-align-center u-container-style u-list-item u-repeater-item u-list-item-1">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-1">
                <img class="u-image u-image-round u-preserve-proportions u-radius-30 u-image-1" alt="" data-image-width="1500" data-image-height="1000" src="images/autiful-caucasian-woman-laughing-covering-face-with-hand-chuckle-something-funny-express-happy-positive-emotions-white-wall.jpg">
                <h6 class="u-custom-font u-text u-text-default u-text-2">Omkar Patil </h6>
              </div>
            </div>
            <div class="u-container-align-center u-container-style u-list-item u-repeater-item u-list-item-2">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-2">
                <img class="u-image u-image-round u-preserve-proportions u-radius-30 u-image-2" alt="" data-image-width="1500" data-image-height="1000" src="images/successful-old-businessman-suit-glasses-looking-confident.jpg">
                <h6 class="u-custom-font u-text u-text-default u-text-3">Atharv Kulkarni </h6>
              </div>
            </div>
            <div class="u-container-align-center u-container-style u-list-item u-repeater-item u-list-item-3">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-3">
                <img class="u-image u-image-round u-preserve-proportions u-radius-30 u-image-3" alt="" data-image-width="1500" data-image-height="1000" src="images/fashionable-young-redhead-woman-with-braid-tattoo-shoulder-having-rest-indoors.jpg">
                <h6 class="u-custom-font u-text u-text-default u-text-4">Samarth Patil </h6>
              </div>
            </div>
            <div class="u-container-align-center u-container-style u-list-item u-repeater-item u-list-item-4">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-4">
                <img class="u-image u-image-round u-preserve-proportions u-radius-30 u-image-4" alt="" data-image-width="1500" data-image-height="1000" src="images/business-sucessful-businessman-working-with-laptop-using-computer-smiling-standing.jpg">
                <h6 class="u-custom-font u-text u-text-default u-text-5">Rushikesh yadav </h6>
              </div>
            </div>
            <div class="u-container-align-center u-container-style u-list-item u-repeater-item u-list-item-5">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-5">
                <img class="u-image u-image-round u-preserve-proportions u-radius-30 u-image-5" alt="" data-image-width="1500" data-image-height="1001" src="images/woman-sitting-sofa-with-laptop-legs.jpg">
                <h6 class="u-custom-font u-text u-text-default u-text-6">Vivek Dalvi </h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-container-align-center u-custom-color-9 u-section-4" id="sec-f0c5">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-container-align-center u-container-style u-custom-color-4 u-list-item u-repeater-item u-shape-rectangle u-list-item-1">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-1">
                <span class="u-align-center u-icon u-icon-circle u-palette-3-base u-text-white u-icon-1">
                  <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 54 54" style=""><use xlink:href="#svg-8d37"></use></svg>
                  <svg class="u-svg-content" viewBox="0 0 54 54" x="0px" y="0px" id="svg-8d37" style="enable-background:new 0 0 54 54;"><g><path d="M27,8c-9.374,0-17,7.626-17,17c0,7.112,4.391,13.412,11,15.9V50c0,0.553,0.447,1,1,1h1v2c0,0.553,0.447,1,1,1h6
		c0.553,0,1-0.447,1-1v-2h1c0.553,0,1-0.447,1-1v-9.1c6.609-2.488,11-8.788,11-15.9C44,15.626,36.374,8,27,8z M30,49
		c-0.553,0-1,0.447-1,1v2h-4v-2c0-0.553-0.447-1-1-1h-1v-5h8v5H30z M31.688,39.242C31.277,39.377,31,39.761,31,40.192V42h-8v-1.808
		c0-0.432-0.277-0.815-0.688-0.95C16.145,37.214,12,31.49,12,25c0-8.271,6.729-15,15-15s15,6.729,15,15
		C42,31.49,37.855,37.214,31.688,39.242z"></path><path d="M27,6c0.553,0,1-0.447,1-1V1c0-0.553-0.447-1-1-1s-1,0.447-1,1v4C26,5.553,26.447,6,27,6z"></path><path d="M51,24h-4c-0.553,0-1,0.447-1,1s0.447,1,1,1h4c0.553,0,1-0.447,1-1S51.553,24,51,24z"></path><path d="M7,24H3c-0.553,0-1,0.447-1,1s0.447,1,1,1h4c0.553,0,1-0.447,1-1S7.553,24,7,24z"></path><path d="M43.264,7.322l-2.828,2.828c-0.391,0.391-0.391,1.023,0,1.414c0.195,0.195,0.451,0.293,0.707,0.293
		s0.512-0.098,0.707-0.293l2.828-2.828c0.391-0.391,0.391-1.023,0-1.414S43.654,6.932,43.264,7.322z"></path><path d="M12.15,38.436l-2.828,2.828c-0.391,0.391-0.391,1.023,0,1.414c0.195,0.195,0.451,0.293,0.707,0.293
		s0.512-0.098,0.707-0.293l2.828-2.828c0.391-0.391,0.391-1.023,0-1.414S12.541,38.045,12.15,38.436z"></path><path d="M41.85,38.436c-0.391-0.391-1.023-0.391-1.414,0s-0.391,1.023,0,1.414l2.828,2.828c0.195,0.195,0.451,0.293,0.707,0.293
		s0.512-0.098,0.707-0.293c0.391-0.391,0.391-1.023,0-1.414L41.85,38.436z"></path><path d="M12.15,11.564c0.195,0.195,0.451,0.293,0.707,0.293s0.512-0.098,0.707-0.293c0.391-0.391,0.391-1.023,0-1.414l-2.828-2.828
		c-0.391-0.391-1.023-0.391-1.414,0s-0.391,1.023,0,1.414L12.15,11.564z"></path><path d="M27,13c-6.617,0-12,5.383-12,12c0,0.553,0.447,1,1,1s1-0.447,1-1c0-5.514,4.486-10,10-10c0.553,0,1-0.447,1-1
		S27.553,13,27,13z"></path>
</g></svg>
                </span>
                <h6 class="u-align-center u-custom-font u-text u-text-default u-text-1"> Membership</h6>
                <h3 class="u-align-center u-custom-font u-text u-text-default u-text-2">
                  <span style="font-weight: 700;">nbsp;35</span>/monthly
                </h3>
                <p class="u-align-center u-text u-text-grey-30 u-text-3"> Article evident arrived express highest men did boy. Mistress sensible entirely am so. Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.&nbsp;</p>
                <a href="#" class="u-align-center u-border-hover-black u-btn u-btn-round u-button-style u-hover-white u-palette-3-base u-radius-30 u-text-body-alt-color u-text-hover-black u-btn-1" data-animation-name="" data-animation-duration="0" data-animation-delay="0" data-animation-direction=""> read more</a>
              </div>
            </div>
            <div class="u-container-align-center u-container-style u-custom-color-4 u-list-item u-repeater-item u-shape-rectangle u-list-item-2">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-2">
                <span class="u-align-center u-icon u-icon-circle u-palette-3-base u-icon-2">
                  <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 55.867 55.867" style=""><use xlink:href="#svg-c573"></use></svg>
                  <svg class="u-svg-content" viewBox="0 0 55.867 55.867" x="0px" y="0px" id="svg-c573" style="enable-background:new 0 0 55.867 55.867;"><path d="M11.287,54.548c-0.207,0-0.414-0.064-0.588-0.191c-0.308-0.224-0.462-0.603-0.397-0.978l3.091-18.018L0.302,22.602
	c-0.272-0.266-0.37-0.663-0.253-1.024c0.118-0.362,0.431-0.626,0.808-0.681l18.09-2.629l8.091-16.393
	c0.168-0.342,0.516-0.558,0.896-0.558l0,0c0.381,0,0.729,0.216,0.896,0.558l8.09,16.393l18.091,2.629
	c0.377,0.055,0.689,0.318,0.808,0.681c0.117,0.361,0.02,0.759-0.253,1.024L42.475,35.363l3.09,18.017
	c0.064,0.375-0.09,0.754-0.397,0.978c-0.308,0.226-0.717,0.255-1.054,0.076l-16.18-8.506l-16.182,8.506
	C11.606,54.51,11.446,54.548,11.287,54.548z M3.149,22.584l12.016,11.713c0.235,0.229,0.343,0.561,0.287,0.885L12.615,51.72
	l14.854-7.808c0.291-0.154,0.638-0.154,0.931,0l14.852,7.808l-2.836-16.538c-0.056-0.324,0.052-0.655,0.287-0.885l12.016-11.713
	l-16.605-2.413c-0.326-0.047-0.607-0.252-0.753-0.547L27.934,4.578l-7.427,15.047c-0.146,0.295-0.427,0.5-0.753,0.547L3.149,22.584z
	"></path></svg>
                </span>
                <h6 class="u-align-center u-custom-font u-text u-text-default u-text-4"> Dedicated Desk</h6>
                <h3 class="u-align-center u-custom-font u-text u-text-default u-text-5">
                  <span style="font-weight: 700;">$ 65</span>/monthly
                </h3>
                <p class="u-align-center u-text u-text-grey-30 u-text-6"> Article evident arrived express highest men did boy. Mistress sensible entirely am so. Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.&nbsp;</p>
                <a href="#" class="u-align-center u-border-hover-black u-btn u-btn-round u-button-style u-hover-white u-palette-3-base u-radius-30 u-text-body-alt-color u-text-hover-black u-btn-2" data-animation-name="" data-animation-duration="0" data-animation-delay="0" data-animation-direction="">read more</a>
              </div>
            </div>
            <div class="u-container-align-center u-container-style u-custom-color-4 u-list-item u-repeater-item u-shape-rectangle u-list-item-3">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-3">
                <span class="u-align-center u-icon u-icon-circle u-palette-3-base u-icon-3">
                  <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 54 54" style=""><use xlink:href="#svg-1d40"></use></svg>
                  <svg class="u-svg-content" viewBox="0 0 54 54" x="0px" y="0px" id="svg-1d40" style="enable-background:new 0 0 54 54;"><g><path d="M51.22,21h-5.052c-0.812,0-1.481-0.447-1.792-1.197s-0.153-1.54,0.42-2.114l3.572-3.571
		c0.525-0.525,0.814-1.224,0.814-1.966c0-0.743-0.289-1.441-0.814-1.967l-4.553-4.553c-1.05-1.05-2.881-1.052-3.933,0l-3.571,3.571
		c-0.574,0.573-1.366,0.733-2.114,0.421C33.447,9.313,33,8.644,33,7.832V2.78C33,1.247,31.753,0,30.22,0H23.78
		C22.247,0,21,1.247,21,2.78v5.052c0,0.812-0.447,1.481-1.197,1.792c-0.748,0.313-1.54,0.152-2.114-0.421l-3.571-3.571
		c-1.052-1.052-2.883-1.05-3.933,0l-4.553,4.553c-0.525,0.525-0.814,1.224-0.814,1.967c0,0.742,0.289,1.44,0.814,1.966l3.572,3.571
		c0.573,0.574,0.73,1.364,0.42,2.114S8.644,21,7.832,21H2.78C1.247,21,0,22.247,0,23.78v6.439C0,31.753,1.247,33,2.78,33h5.052
		c0.812,0,1.481,0.447,1.792,1.197s0.153,1.54-0.42,2.114l-3.572,3.571c-0.525,0.525-0.814,1.224-0.814,1.966
		c0,0.743,0.289,1.441,0.814,1.967l4.553,4.553c1.051,1.051,2.881,1.053,3.933,0l3.571-3.572c0.574-0.573,1.363-0.731,2.114-0.42
		c0.75,0.311,1.197,0.98,1.197,1.792v5.052c0,1.533,1.247,2.78,2.78,2.78h6.439c1.533,0,2.78-1.247,2.78-2.78v-5.052
		c0-0.812,0.447-1.481,1.197-1.792c0.751-0.312,1.54-0.153,2.114,0.42l3.571,3.572c1.052,1.052,2.883,1.05,3.933,0l4.553-4.553
		c0.525-0.525,0.814-1.224,0.814-1.967c0-0.742-0.289-1.44-0.814-1.966l-3.572-3.571c-0.573-0.574-0.73-1.364-0.42-2.114
		S45.356,33,46.168,33h5.052c1.533,0,2.78-1.247,2.78-2.78V23.78C54,22.247,52.753,21,51.22,21z M52,30.22
		C52,30.65,51.65,31,51.22,31h-5.052c-1.624,0-3.019,0.932-3.64,2.432c-0.622,1.5-0.295,3.146,0.854,4.294l3.572,3.571
		c0.305,0.305,0.305,0.8,0,1.104l-4.553,4.553c-0.304,0.304-0.799,0.306-1.104,0l-3.571-3.572c-1.149-1.149-2.794-1.474-4.294-0.854
		c-1.5,0.621-2.432,2.016-2.432,3.64v5.052C31,51.65,30.65,52,30.22,52H23.78C23.35,52,23,51.65,23,51.22v-5.052
		c0-1.624-0.932-3.019-2.432-3.64c-0.503-0.209-1.021-0.311-1.533-0.311c-1.014,0-1.997,0.4-2.761,1.164l-3.571,3.572
		c-0.306,0.306-0.801,0.304-1.104,0l-4.553-4.553c-0.305-0.305-0.305-0.8,0-1.104l3.572-3.571c1.148-1.148,1.476-2.794,0.854-4.294
		C10.851,31.932,9.456,31,7.832,31H2.78C2.35,31,2,30.65,2,30.22V23.78C2,23.35,2.35,23,2.78,23h5.052
		c1.624,0,3.019-0.932,3.64-2.432c0.622-1.5,0.295-3.146-0.854-4.294l-3.572-3.571c-0.305-0.305-0.305-0.8,0-1.104l4.553-4.553
		c0.304-0.305,0.799-0.305,1.104,0l3.571,3.571c1.147,1.147,2.792,1.476,4.294,0.854C22.068,10.851,23,9.456,23,7.832V2.78
		C23,2.35,23.35,2,23.78,2h6.439C30.65,2,31,2.35,31,2.78v5.052c0,1.624,0.932,3.019,2.432,3.64
		c1.502,0.622,3.146,0.294,4.294-0.854l3.571-3.571c0.306-0.305,0.801-0.305,1.104,0l4.553,4.553c0.305,0.305,0.305,0.8,0,1.104
		l-3.572,3.571c-1.148,1.148-1.476,2.794-0.854,4.294c0.621,1.5,2.016,2.432,3.64,2.432h5.052C51.65,23,52,23.35,52,23.78V30.22z"></path><path d="M27,18c-4.963,0-9,4.037-9,9s4.037,9,9,9s9-4.037,9-9S31.963,18,27,18z M27,34c-3.859,0-7-3.141-7-7s3.141-7,7-7
		s7,3.141,7,7S30.859,34,27,34z"></path>
</g></svg>
                </span>
                <h6 class="u-align-center u-custom-font u-text u-text-default u-text-7"> Private Office</h6>
                <h3 class="u-align-center u-custom-font u-text u-text-default u-text-8">
                  <span style="font-weight: 700;">$ 95</span>/monthly
                </h3>
                <p class="u-align-center u-text u-text-grey-30 u-text-9"> Article evident arrived express highest men did boy. Mistress sensible entirely am so. Quick can manor smart money hopes worth too. Comfort produce husband boy her had hearing.&nbsp;</p>
                <a href="#" class="u-align-center u-border-hover-black u-btn u-btn-round u-button-style u-hover-white u-palette-3-base u-radius-30 u-text-body-alt-color u-text-hover-black u-btn-3" data-animation-name="" data-animation-duration="0" data-animation-delay="0" data-animation-direction=""> read more</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-container-align-center-sm u-container-align-center-xs u-image u-section-5" id="sec-4a59" src="./contact-us.jpg" data-image-width="2000" data-image-height="1125">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-form u-form-1">
          <form action="https://forms.nicepagesrv.com/v2/form/process" class="u-clearfix u-form-spacing-19 u-form-vertical u-inner-form" source="email" name="form" style="padding: 0px;">
            <div class="u-form-email u-form-group">
              <label for="email-db6f" class="u-label">Email</label>
              <input type="email" placeholder="Enter a valid email address" id="email-db6f" name="email" class="u-input u-input-rectangle u-radius-10" required="">
            </div>
            <div class="u-form-group u-form-name u-form-partition-factor-2 u-form-group-2">
              <label for="name-961f" class="u-label">First Name</label>
              <input type="text" placeholder="Enter your First Name" id="name-961f" name="name-1" class="u-input u-input-rectangle u-radius-10" required="">
            </div>
            <div class="u-form-group u-form-name u-form-partition-factor-2 u-form-group-3">
              <label for="name-961f" class="u-label">Last Name</label>
              <input type="text" placeholder="Enter your Last Name" id="name-961f" name="name-2" class="u-input u-input-rectangle u-radius-10" required="">
            </div>
            <div class="u-form-group u-form-message">
              <label for="message-db6f" class="u-label">Message</label>
              <textarea placeholder="Enter your message" rows="4" cols="50" id="message-db6f" name="message" class="u-input u-input-rectangle u-radius-10" required=""></textarea>
            </div>
            <div class="u-align-right u-form-group u-form-submit">
              <a href="#" class="u-border-none u-btn u-btn-round u-btn-submit u-button-style u-palette-3-base u-radius-20 u-btn-1">Make a Reservation</a>
              <input type="submit" value="submit" class="u-form-control-hidden">
            </div>
            <div class="u-form-send-message u-form-send-success"> Thank you! Your message has been sent. </div>
            <div class="u-form-send-error u-form-send-message"> Unable to send your message. Please fix errors then try again. </div>
            <input type="hidden" value="" name="recaptchaResponse">
            <input type="hidden" name="formServices" value="13466ec3-d4d8-89bc-b784-e08ea86255ce">
          </form>
        </div>
      </div>
    </section>
    
    
    
    
    <section class="u-backlink u-clearfix u-grey-80">
      <p class="u-text">
        <span>This site was created with the </span>
        <a class="u-link" href="https://nicepage.com/" target="_blank" rel="nofollow">
          <span>Nicepage</span>
        </a>
      </p>
    </section>
  
</body></html>
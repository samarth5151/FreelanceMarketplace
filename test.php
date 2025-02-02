<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Post A Job</title>
      
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
   </head>
   <style>
     @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
* {
  margin: 0;
  padding: 0;
  outline: none;
  font-family: 'Poppins', sans-serif;
}
body {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  overflow: hidden;
  background: url("bg.png"), -webkit-linear-gradient(bottom, #0250c5, #d43f8d);
}
::selection {
  color: #fff;
  background: #d43f8d;
}
.container {
  width: 400px;
  background: #fff;
  text-align: center;
  border-radius: 5px;
  padding: 50px 35px 10px 35px;
}
.container header {
  font-size: 30px;
  font-weight: 600;
  margin: 0 0 30px 0;
}
.container .form-outer {
  width: 100%;
  overflow: hidden;
}
.container .form-outer form {
  display: flex;
  width: 400%;
}
.form-outer form .page {
  width: 25%;
  transition: margin-left 0.3s ease-in-out;
}
.form-outer form .page .title {
  text-align: left;
  font-size: 22px;
  font-weight: 500;
  margin-bottom: 15px;
}
.form-outer form .page .field {
  width: 100%;
  margin: 20px 0;
  display: flex;
  flex-direction: column;
}
form .page .field .label {
  margin-bottom: 5px;
  font-weight: 500;
  text-align: left;
}
form .page .field input,
form .page .field select,
form .page .field textarea {
  height: 40px;
  width: 100%;
  border: 1px solid lightgrey;
  border-radius: 5px;
  padding: 0 10px;
  font-size: 16px;
}
form .page .field textarea {
  height: 80px;
  resize: none;
}
form .page .field button {
  width: 100%;
  height: 45px;
  border: none;
  background: #d33f8d;
  margin-top: 10px;
  border-radius: 5px;
  color: #fff;
  cursor: pointer;
  font-size: 18px;
  font-weight: 500;
  letter-spacing: 1px;
  text-transform: uppercase;
  transition: 0.5s ease;
}
form .page .field button:hover {
  background: #000;
}
form .page .btns {
  display: flex;
  justify-content: space-between;
}
form .page .btns button {
  width: calc(50% - 5px);
}
.container .progress-bar {
  display: flex;
  margin: 30px 0;
  user-select: none;
}
.container .progress-bar .step {
  text-align: center;
  width: 100%;
  position: relative;
}
.container .progress-bar .step p {
  font-weight: 500;
  font-size: 14px;
  color: #000;
  margin-bottom: 8px;
}
.progress-bar .step .bullet {
  height: 25px;
  width: 25px;
  border: 2px solid #000;
  display: inline-block;
  border-radius: 50%;
  position: relative;
  transition: 0.2s;
  font-weight: 500;
  font-size: 14px;
  line-height: 25px;
}
.progress-bar .step .bullet.active {
  border-color: #d43f8d;
  background: #d43f8d;
}
.progress-bar .step .bullet span {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}
.progress-bar .step .bullet.active span {
  display: none;
}
.progress-bar .step .bullet:before,
.progress-bar .step .bullet:after {
  position: absolute;
  content: '';
  bottom: 11px;
  right: -51px;
  height: 3px;
  width: 44px;
  background: #262626;
}
.progress-bar .step .bullet.active:after {
  background: #d43f8d;
  transform: scaleX(0);
  transform-origin: left;
  animation: animate 0.3s linear forwards;
}
@keyframes animate {
  100% {
    transform: scaleX(1);
  }
}
.progress-bar .step:last-child .bullet:before,
.progress-bar .step:last-child .bullet:after {
  display: none;
}
.progress-bar .step p.active {
  color: #d43f8d;
  transition: 0.2s linear;
}
.progress-bar .step .check {
  position: absolute;
  left: 50%;
  top: 70%;
  font-size: 15px;
  transform: translate(-50%, -50%);
  display: none;
}
.progress-bar .step .check.active {
  display: block;
  color: #fff;
}


   </style>
   <body>
   <div class="container">
   <header>Post A Job</header>
   <div class="progress-bar">
      <div class="step">
         <p>Job Details</p>
         <div class="bullet">
            <span>1</span>
         </div>
         <div class="check fas fa-check"></div>
      </div>
      <div class="step">
         <p>Skills</p>
         <div class="bullet">
            <span>2</span>
         </div>
         <div class="check fas fa-check"></div>
      </div>
      <div class="step">
         <p>Budget</p>
         <div class="bullet">
            <span>3</span>
         </div>
         <div class="check fas fa-check"></div>
      </div>
      <div class="step">
         <p>Submit</p>
         <div class="bullet">
            <span>4</span>
         </div>
         <div class="check fas fa-check"></div>
      </div>
   </div>
   <div class="form-outer">
      <form action="#">
         <!-- Step 1: Job Details -->
         <div class="page slide-page">
            <div class="title">Job Details:</div>
            <div class="field">
               <div class="label">Job Title</div>
               <input type="text" placeholder="Enter the job title">
            </div>
            <div class="field">
               <div class="label">Job Description</div>
               <textarea placeholder="Describe the job requirements"></textarea>
            </div>

            <div class="field">
               <button class="firstNext next">Next</button>
            </div>
         </div>

         <!-- Step 2: Skills -->
         <div class="page">
            <div class="title">Required Skills:</div>
            <div class="field">
               <div class="label">Primary Skill</div>
               <input type="text" placeholder="e.g., Web Development">
            </div>
            <div class="field">
               <div class="label">Additional Skills</div>
               <input type="text" placeholder="e.g., JavaScript, PHP">
            </div>
            <div class="field btns">
               <button class="prev-1 prev">Previous</button>
               <button class="next-1 next">Next</button>
            </div>
         </div>

         <!-- Step 3: Budget -->
         <div class="page">
            <div class="title">Budget & Timeline:</div>
            <div class="field">
               <div class="label">Budget (USD)</div>
               <input type="number" placeholder="Enter your budget">
            </div>
            <div class="field">
               <div class="label">Deadline</div>
               <input type="date">
            </div>
            <div class="field btns">
               <button class="prev-2 prev">Previous</button>
               <button class="next-2 next">Next</button>
            </div>
         </div>

         <!-- Step 4: Submit -->
         <div class="page">
            <div class="title">Review & Submit:</div>
            <div class="field">
               <div class="label">Username</div>
               <input type="text" placeholder="Enter your username">
            </div>
            <div class="field">
               <div class="label">Password</div>
               <input type="password" placeholder="Enter your password">
            </div>
            <div class="field btns">
               <button class="prev-3 prev">Previous</button>
               <button class="submit">Submit</button>
            </div>
         </div>
      </form>
   </div>
</div>
<script>
    // JavaScript for Post A Job Multi-step Form

const slidePage = document.querySelector(".slide-page");
const nextBtnFirst = document.querySelector(".firstNext");
const prevBtnSec = document.querySelector(".prev-1");
const nextBtnSec = document.querySelector(".next-1");
const prevBtnThird = document.querySelector(".prev-2");
const nextBtnThird = document.querySelector(".next-2");
const prevBtnFourth = document.querySelector(".prev-3");
const submitBtn = document.querySelector(".submit");
const progressText = document.querySelectorAll(".step p");
const progressCheck = document.querySelectorAll(".step .check");
const bullet = document.querySelectorAll(".step .bullet");

let current = 1;

nextBtnFirst.addEventListener("click", function(event) {
  event.preventDefault();
  slidePage.style.marginLeft = "-25%";
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");
  current += 1;
});

nextBtnSec.addEventListener("click", function(event) {
  event.preventDefault();
  slidePage.style.marginLeft = "-50%";
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");
  current += 1;
});

nextBtnThird.addEventListener("click", function(event) {
  event.preventDefault();
  slidePage.style.marginLeft = "-75%";
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");
  current += 1;
});

submitBtn.addEventListener("click", function(event) {
  event.preventDefault();
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");

  setTimeout(function() {
    alert("Your job has been successfully posted!");
    location.reload();
  }, 800);
});

prevBtnSec.addEventListener("click", function(event) {
  event.preventDefault();
  slidePage.style.marginLeft = "0%";
  bullet[current - 2].classList.remove("active");
  progressCheck[current - 2].classList.remove("active");
  progressText[current - 2].classList.remove("active");
  current -= 1;
});

prevBtnThird.addEventListener("click", function(event) {
  event.preventDefault();
  slidePage.style.marginLeft = "-25%";
  bullet[current - 2].classList.remove("active");
  progressCheck[current - 2].classList.remove("active");
  progressText[current - 2].classList.remove("active");
  current -= 1;
});

prevBtnFourth.addEventListener("click", function(event) {
  event.preventDefault();
  slidePage.style.marginLeft = "-50%";
  bullet[current - 2].classList.remove("active");
  progressCheck[current - 2].classList.remove("active");
  progressText[current - 2].classList.remove("active");
  current -= 1;
});

</script>
   </body>
</html>
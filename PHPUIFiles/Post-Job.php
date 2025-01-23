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
  width: 100%;
  max-width: 420px; /* Reduced max width slightly */
  background: #fff;
  text-align: center;
  border-radius: 5px;
  padding: 20px 20px; /* Reduced padding */
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Optional enhancement */
}

.container header {
  font-size: 22px; /* Slightly reduced font size */
  font-weight: 600;
  margin: 0 0 15px 0; /* Reduced bottom margin */
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
  position: relative;
  padding-bottom: 50px; /* Reduced bottom padding */
}

.form-outer form .page .title {
  text-align: left;
  font-size: 18px; /* Reduced font size */
  font-weight: 500;
  margin-bottom: 10px;
}

.form-outer form .page .field {
  width: 100%;
  margin: 8px 0; /* Reduced margin between fields */
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
  height: 38px; /* Reduced height */
  width: 100%;
  border: 1px solid lightgrey;
  border-radius: 5px;
  padding: 0 8px; /* Reduced padding inside input */
  font-size: 14px; /* Slightly smaller font size */
}

form .page .field textarea {
  height: 70px; /* Reduced height */
  resize: none;
}

form .page .field button {
  height: 40px; /* Reduced button height */
  border: none;
  background: #d33f8d;
  margin-top: 10px;
  border-radius: 5px;
  color: #fff;
  cursor: pointer;
  font-size: 16px; /* Reduced font size */
  font-weight: 500;
  letter-spacing: 1px;
  text-transform: uppercase;
  transition: 0.5s ease;
}

form .page .field button:hover {
  background: #000;
}

form .page .btns {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: space-between;
}

form .page.step-4 .btns {
  justify-content: flex-end;
}

.container .progress-bar {
  display: flex;
  margin: 10px 0; /* Reduced margin */
  user-select: none;
}

.container .progress-bar .step {
  text-align: center;
  width: 100%;
  position: relative;
  margin-right: 8px; /* Reduced margin between steps */
}

.container .progress-bar .step p {
  font-weight: 500;
  font-size: 12px; /* Slightly reduced font size */
  color: #000;
  margin-bottom: 5px;
}

.progress-bar .step .bullet {
  height: 22px; /* Reduced size of bullets */
  width: 22px;
  border: 2px solid #000;
  display: inline-block;
  border-radius: 50%;
  position: relative;
  transition: 0.2s;
  font-weight: 500;
  font-size: 12px;
  line-height: 22px;
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
  bottom: 9px;
  right: -45px;
  height: 3px;
  width: 40px;
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
  font-size: 14px;
  transform: translate(-50%, -50%);
  display: none;
}

.progress-bar .step .check.active {
  display: block;
  color: #fff;
}

@media (max-width: 768px) {
  .container {
    width: 90%;
    padding: 20px; /* Adjusted padding for smaller screens */
  }

  .container header {
    font-size: 20px;
  }

  .form-outer form {
    width: 500%;
  }

  .form-outer form .page .title {
    font-size: 16px;
  }

  .form-outer form .page .field {
    margin: 6px 0;
  }

  form .page .field button {
    font-size: 14px;
  }

  .progress-bar {
    margin: 8px 0;
  }
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
               <div class="label">Job Category</div>
               <select>

                  <option>Web Development</option>
                  <option>Mobile Development</option>
               
               </select>
            </div>
            <div class="field">
               <div class="label">Job Description</div>
               <textarea placeholder="Describe the job requirements"></textarea>
            </div>
            <div class="field">
               <div class="label">Attachments</div>
               <input type="file">
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
            <div class="field">
               <div class="label">Experience Level</div>
               <select>
                  <option>Beginner</option>
                  <option>Intermediate</option>
                  <option>Expert</option>
               </select>
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
               <div class="label">Additional Questions</div>
               <textarea placeholder="Ask any additional questions if required"></textarea>
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
   let current = 0;
   const pages = document.querySelectorAll(".page");
   const nextButtons = document.querySelectorAll(".next");
   const prevButtons = document.querySelectorAll(".prev");

   nextButtons.forEach((button, index) => {
      button.addEventListener("click", () => {
         pages[current].style.marginLeft = "-25%";
         current++;
         updateProgressBar();
      });
   });

   prevButtons.forEach((button, index) => {
      button.addEventListener("click", () => {
         pages[current].style.marginLeft = "0";
         current--;
         updateProgressBar();
      });
   });

   function updateProgressBar() {
      const bullets = document.querySelectorAll(".bullet");
      const steps = document.querySelectorAll(".step p");

      for (let i = 0; i < bullets.length; i++) {
         if (i <= current) {
            bullets[i].classList.add("active");
            steps[i].classList.add("active");
         } else {
            bullets[i].classList.remove("active");
            steps[i].classList.remove("active");
         }
      }
   }
</script>

   </body>
</html>

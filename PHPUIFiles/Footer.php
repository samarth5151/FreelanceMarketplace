<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <style>

.footer1 a{
    color: rgb(192, 192, 192);
    font-size: 15px;


}

.footer1 a:hover{
    color:rgb(32 159 75);
    transition: 0.7s;
}
    </style>
</head>
<body>
    

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
                <li><a href="./index.php" style=" text-decoration: none  ">Home</a></li>
                <li><a href="./PHPUIFiles/about-us.php" style="  text-decoration: none;  ">About Us</a></li>
                <li><a href="./PHPUIFiles/Post-Job.php" style="  text-decoration: none;  ">Post a Job</a></li>
                <li><a href=".PHPUIFiles/Find-Job.php" style="  text-decoration: none;  ">Find Work</a></li>
                <li><a href="./PHPUIFiles/faq.php" style="  text-decoration: none;  ">FAQs</a></li>
            </ul>
        </div>


        <div style="flex: 1; min-width: 200px;">
            <h4 style="color:rgb(32 159 75); margin-bottom: 15px;">Resources</h4>
            <ul class="footer1" style="list-style: none; padding: 0; line-height: 1.8;">
                <li><a href="#" style=" text-decoration: none;">Blog</a></li>
                <li><a href="./contact-us.php" style=" text-decoration: none;">Help Center</a></li>
                <li><a href="#" style=" text-decoration: none;">Privacy Policy</a></li>
                <li><a href="#" style=" text-decoration: none;">Terms of Service</a></li>
            </ul>
        </div>

        <div  style="flex: 1; min-width: 200px;">
            <h4 style="color:rgb(32 159 75); margin-bottom: 15px;">Contact Us</h4>
            <p style="color: rgb(192, 192, 192);">Email: <a href="mailto:codebrains.help@gmail.com" style="color:rgb(32 159 75); text-decoration: none;">codebrains.help@gmail.com</a></p>
            <p style="color: rgb(192, 192, 192);">Phone: <a href="tel:+123456789" style="color:rgb(32 159 75); text-decoration: none;">+91 7385057833</a></p>
            <p style="color: rgb(192, 192, 192);">Follow us on:
                <a href="#" style="color:rgb(32 159 75); text-decoration: none; margin: 0 5px;">LinkedIn</a> |
                <a href="#" style="color:rgb(32 159 75); text-decoration: none; margin: 0 5px;">Twitter</a> |
                <a href="#" style="color:rgb(32 159 75); text-decoration: none; margin: 0 5px;">Instagram</a>
            </p>
        </div>
    </div>


    <div style="margin-top: 40px; text-align: center; border-top: 1px solid #333; padding-top: 20px;">
        <p style="color: rgb(192, 192, 192);">&copy; 2025 <span style="color:rgb(32 159 75);">CodeBrains</span>. All rights reserved.</p>
    </div>

</footer>

</body>
</html>
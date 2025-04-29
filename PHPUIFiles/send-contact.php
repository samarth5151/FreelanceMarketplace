<?php

require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = strip_tags(trim($_POST["message"]));
    
    // Validate data
    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact-us.php?status=error");
        exit;
    }
    
    // Create PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'athukulkarni1906@gmail.com'; // Your email address
        $mail->Password   = 'smma bwou xhsq ccfs'; // Use an app password here, not your regular password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('codebrains.help@gmail.com', 'Admin'); // Admin email
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission: ' . $subject;
        $mail->Body    = "<h2>New Contact Form Submission</h2>
                         <p><strong>Name:</strong> {$name}</p>
                         <p><strong>Email:</strong> {$email}</p>
                         <p><strong>Subject:</strong> {$subject}</p>
                         <p><strong>Message:</strong><br>{$message}</p>";
        $mail->AltBody = "Name: {$name}\nEmail: {$email}\nSubject: {$subject}\nMessage:\n{$message}";
        
        $mail->send();
        header("Location: contact-us.php?status=success");
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo); // Log the error for debugging
        header("Location: contact-us.php?status=error");
    }
} else {
    header("Location: contact-us.php");
}
?>
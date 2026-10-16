<?php
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['Name'] ?? '');
    $email = trim($_POST['Email'] ?? '');
    $message = trim($_POST['Message'] ?? '');
    $recaptcha_response = $_POST['recaptcha_response'] ?? '';

    $verify_url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret_key) . '&response=' . urlencode($recaptcha_response);
    
    $response = file_get_contents($verify_url);
    $response_keys = json_decode($response, true);

    if ($response_keys["success"] && $response_keys["score"] >= 0.5) {
        
        // ---  EMAIL TO THE OWNER  ---
        $to_owner = "roland.nagy9049@gmail.com";
        $subject_owner = "New message from your portfolio - " . $name;
        $body_owner = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers_owner = "From: " . $email . "\r\n" . "Reply-To: " . $email . "\r\n";
        
        mail($to_owner, $subject_owner, $body_owner, $headers_owner);

        // ---  CONFIRMATION EMAIL TO THE SENDER ---
        $subject_sender = "We have received your message - Nagy Roland Portfolio";
        $body_sender = "Dear $name,\n\nThank you for reaching out! I have successfully received your message and will get back to you soon.\n\nBest regards,\nNagy Roland";
        $headers_sender = "From: roland.nagy9049@gmail.com\r\n" . "Reply-To: roland.nagy9049@gmail.com\r\n";
        
        mail($email, $subject_sender, $body_sender, $headers_sender);

        echo "<script>alert('Message sent successfully!'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Security check failed (Spam suspicion). Please try again.'); window.history.back();</script>";
    }
}
?>
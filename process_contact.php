<?php
session_start();
require_once 'functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!verifyCSRFToken($_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Security token invalid']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
    exit;
}

if (!validateEmail($email)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

// Submit to database
if (submitContactForm($name, $email, $subject, $message)) {
    // Send email notification
    $personal = getPersonalInfo();
    $emailBody = "New contact message received:\n\n";
    $emailBody .= "Name: $name\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Subject: $subject\n";
    $emailBody .= "Message:\n$message";
    
    sendEmailNotification($personal['email'], "New Contact Message: $subject", $emailBody);
    
    echo json_encode(['success' => true, 'message' => 'Thank you for your message! I will get back to you soon.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again.']);
}
?>

<?php
session_start();
require_once '../repos/ContactMessages.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /clients/contact.php');
    exit();
}

// Sanitize and validate inputs
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

if (!$name || !$email || !$subject || !$message) {
    $_SESSION['contact_message'] = ['type' => 'error', 'text' => 'Please fill out all fields correctly.'];
    header('Location: /clients/contact.php');
    exit();
}

$contactRepo = new ContactMessages();
$success = $contactRepo->createMessage($name, $email, $subject, $message);

if ($success) {
    $_SESSION['contact_message'] = ['type' => 'success', 'text' => 'Thank you for your message! We will get back to you shortly.'];
} else {
    $_SESSION['contact_message'] = ['type' => 'error', 'text' => 'Sorry, there was an error sending your message. Please try again later.'];
}

header('Location: /clients/contact.php');
exit();
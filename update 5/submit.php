<?php
session_start();

// Security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die("Method not allowed");
}

// Validate session
if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
    http_response_code(403);
    die("Invalid CSRF token");
}

// Input validation and sanitization
$required_fields = ['firstName', 'lastname', 'email', 'phone', 'idNumber', 'Batch', 'Technology'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty(trim($_POST[$field] ?? ''))) {
        $errors[] = "$field is required";
    }
}

// Email validation
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

// Phone validation (Philippines format)
if (!preg_match('/^(?:\+63|0)\d{10}$/', $_POST['phone'])) {
    $errors[] = "Phone must be in Philippine format (+63XXXXXXXXX or 0XXXXXXXXX)";
}

// ID Number validation (XXXX-XX-XXX format)
if (!preg_match('/^\d{4}-\d{2}-\d{3}$/', $_POST['idNumber'])) {
    $errors[] = "ID Number must be in format XXXX-XX-XXX";
}

// Check terms agreement
if (!isset($_POST['terms']) || $_POST['terms'] !== 'on') {
    $errors[] = "You must agree to the terms and conditions";
}

// If validation errors, redirect back with errors
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['old_input'] = $_POST;
    header("Location: index.php");
    exit();
}

// Sanitize all inputs
$userData = [];
foreach ($_POST as $key => $value) {
    if (is_string($value)) {
        $userData[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    } else {
        $userData[$key] = $value;
    }
}

// Store in session
$_SESSION['userData'] = $userData;

// Clear CSRF token after successful submission
unset($_SESSION['csrf_token']);

// Redirect to confirmation
header("Location: confirmation.php");
exit();
?>
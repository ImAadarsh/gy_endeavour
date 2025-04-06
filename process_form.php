<?php
// Set proper content type for AJAX response
header('Content-Type: application/json');

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
$required_fields = ['full_name', 'email', 'phone'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields missing: ' . implode(', ', $missing_fields)]);
    exit;
}

// Get form data
$name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = isset($_POST['message']) ? $_POST['message'] : '';
$date = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR']; 

// Prepare data for CSV
$data = [
    $date,
    $name,
    $email,
    $phone,
    str_replace(["\r", "\n"], [" ", " "], $message),
    $ip
];

// Define secure directory and file path
$data_dir = '../data';
$file_path = $data_dir . '/contact_submissions.csv';

// Create directory if it doesn't exist
if (!file_exists($data_dir)) {
    if (!mkdir($data_dir, 0755, true)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create data directory']);
        exit;
    }
    
    // Create .htaccess to protect the directory
    $htaccess_content = "# Deny access to all files\nDeny from all\n";
    file_put_contents($data_dir . '/.htaccess', $htaccess_content);
}

// Append data to CSV file
$file_exists = file_exists($file_path);
$file = fopen($file_path, 'a');

if (!$file) {
    echo json_encode(['status' => 'error', 'message' => 'Could not open file for writing']);
    exit;
}

// Add headers if file is new
if (!$file_exists) {
    fputcsv($file, ['Date', 'Name', 'Email', 'Phone', 'Message', 'IP Address']);
}

// Write data
fputcsv($file, $data);
fclose($file);

// Return success response
echo json_encode(['status' => 'success', 'message' => 'Thank you for your message. We will contact you soon!']); 
<?php
// Define a hardcoded password - change this to something secure
$admin_password = "Gaurava@2024";

// Start session
session_start();

// Check if user is logged in
$is_logged_in = false;

// Handle login form submission
if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $is_logged_in = true;
    } else {
        $error_message = "Incorrect password";
    }
}

// Check if user is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $is_logged_in = true;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// File path for contact form submissions
$file_path = '../../data/contact_submissions.csv';
$submissions = [];

// Read submissions if user is logged in
if ($is_logged_in && file_exists($file_path)) {
    $file = fopen($file_path, 'r');
    if ($file) {
        $headers = fgetcsv($file);
        while (($data = fgetcsv($file)) !== false) {
            $submissions[] = array_combine($headers, $data);
        }
        fclose($file);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submissions - Admin</title>
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="../styles/output.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .error {
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .logout {
            color: #e74c3c;
            text-decoration: none;
        }
        .no-data {
            text-align: center;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!$is_logged_in): ?>
            <div class="login-container">
                <h1 class="text-2xl font-bold mb-4">Admin Login</h1>
                <?php if (isset($error_message)): ?>
                    <div class="error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit">Login</button>
                </form>
            </div>
        <?php else: ?>
            <div class="header">
                <h1 class="text-2xl font-bold">Contact Form Submissions</h1>
                <a href="?logout=1" class="logout">Logout</a>
            </div>
            
            <?php if (empty($submissions)): ?>
                <div class="no-data">
                    <p>No submissions found.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $submission): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($submission['Date']); ?></td>
                                <td><?php echo htmlspecialchars($submission['Name']); ?></td>
                                <td><?php echo htmlspecialchars($submission['Email']); ?></td>
                                <td><?php echo htmlspecialchars($submission['Phone']); ?></td>
                                <td><?php echo htmlspecialchars($submission['Message']); ?></td>
                                <td><?php echo htmlspecialchars($submission['IP Address']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html> 
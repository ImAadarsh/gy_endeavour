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
    <title>Admin Dashboard | Contact Submissions</title>
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="../styles/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #2ec4b6;
            --danger: #e63946;
            --success: #2a9d8f;
            --warning: #f9c74f;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --gray-light: #e9ecef;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* Login Page Styles */
        .login-container {
            max-width: 420px;
            margin: 100px auto;
            padding: 2.5rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 6px;
            transition: all 0.2s;
            cursor: pointer;
            width: 100%;
        }
        
        .btn-primary {
            color: #fff;
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .alert {
            padding: 0.75rem 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
            border-radius: 6px;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        /* Dashboard Styles */
        .dashboard {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
        }
        
        .navbar-actions .btn {
            width: auto;
            padding: 0.5rem 1.25rem;
        }
        
        .btn-logout {
            color: #fff;
            background-color: var(--danger);
            border-color: var(--danger);
            margin-left: 1rem;
        }
        
        .btn-logout:hover {
            opacity: 0.9;
        }
        
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
        }
        
        .dashboard-header {
            margin-bottom: 2rem;
        }
        
        .dashboard-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .dashboard-subtitle {
            color: var(--gray);
            font-size: 1rem;
        }
        
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
            background-color: #fff;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-light);
        }
        
        table th {
            font-weight: 600;
            color: var(--dark);
            background-color: rgba(0,0,0,0.02);
        }
        
        table tr:hover {
            background-color: rgba(0,0,0,0.01);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }
        
        .empty-state-icon {
            font-size: 3rem;
            color: var(--gray);
            margin-bottom: 1.5rem;
        }
        
        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .empty-state-description {
            color: var(--gray);
            max-width: 500px;
            margin: 0 auto;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
            
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .login-container {
                margin: 50px auto;
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php if (!$is_logged_in): ?>
        <div class="login-container">
            <div class="login-logo">
                <i class="fas fa-lock" style="font-size: 3rem; color: var(--primary);"></i>
            </div>
            <h1 class="login-title">Admin Dashboard</h1>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="dashboard">
            <nav class="navbar">
                <a href="#" class="navbar-logo">
                    <i class="fas fa-chart-line"></i> Admin Dashboard
                </a>
                <div class="navbar-actions">
                    <a href="?logout=1" class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </nav>
            
            <div class="container">
                <div class="dashboard-header">
                    <h1 class="dashboard-title">Contact Form Submissions</h1>
                    <p class="dashboard-subtitle">View and manage form submissions from your website</p>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">All Submissions</h2>
                        <div class="card-actions">
                            <span class="badge"><?php echo count($submissions); ?> total</span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if (empty($submissions)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h3 class="empty-state-title">No submissions yet</h3>
                                <p class="empty-state-description">When visitors submit the contact form, their messages will appear here.</p>
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
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <script>
        // Add a simple animation to the login form
        document.addEventListener('DOMContentLoaded', function() {
            const loginContainer = document.querySelector('.login-container');
            if (loginContainer) {
                loginContainer.style.opacity = '0';
                loginContainer.style.transform = 'translateY(20px)';
                loginContainer.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(function() {
                    loginContainer.style.opacity = '1';
                    loginContainer.style.transform = 'translateY(0)';
                }, 100);
            }
        });
    </script>
</body>
</html> 
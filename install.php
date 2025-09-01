<?php
// Installation script for Portfolio Website
// Run this file once to set up the database and configuration

session_start();

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

// Step 1: Check requirements
if ($step === 1) {
    $requirements = [
        'PHP Version (>= 7.4)' => version_compare(PHP_VERSION, '7.4.0', '>='),
        'PDO Extension' => extension_loaded('pdo'),
        'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
        'Session Support' => function_exists('session_start'),
        'File Write Permissions' => is_writable('.')
    ];
    
    $all_met = true;
    foreach ($requirements as $requirement => $met) {
        if (!$met) $all_met = false;
    }
    
    if ($all_met) {
        $step = 2;
    }
}

// Step 2: Database configuration
if ($step === 2 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'] ?? 'localhost';
    $user = $_POST['user'] ?? 'root';
    $pass = $_POST['pass'] ?? '';
    $dbname = $_POST['dbname'] ?? 'portfolio_db';
    
    try {
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
        $pdo->exec("USE `$dbname`");
        
        // Read and execute SQL file
        $sql = file_get_contents('database.sql');
        $pdo->exec($sql);
        
        // Create config file
        $config_content = "<?php
// Database configuration
define('DB_HOST', '$host');
define('DB_USER', '$user');
define('DB_PASS', '$pass');
define('DB_NAME', '$dbname');

// Create database connection
try {
    \$pdo = new PDO(\"mysql:host=\" . DB_HOST . \";dbname=\" . DB_NAME, DB_USER, DB_PASS);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException \$e) {
    die(\"Connection failed: \" . \$e->getMessage());
}

// Site configuration
define('SITE_NAME', 'Your Portfolio');
define('SITE_EMAIL', 'your.email@example.com');
define('SITE_PHONE', '+1 (555) 123-4567');
define('SITE_LOCATION', 'City, State, Country');
define('SITE_LINKEDIN', 'linkedin.com/in/yourprofile');
?>";
        
        file_put_contents('config.php', $config_content);
        
        $success = 'Database setup completed successfully!';
        $step = 3;
        
    } catch (PDOException $e) {
        $error = 'Database connection failed: ' . $e->getMessage();
    }
}

// Step 3: Site configuration
if ($step === 3 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $title = $_POST['title'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';
    $admin_password = $_POST['admin_password'] ?? '';
    
    if (!empty($name) && !empty($email)) {
        try {
            require_once 'config.php';
            require_once 'functions.php';
            
            // Update personal info
            $stmt = $pdo->prepare("UPDATE personal_info SET name = ?, title = ?, email = ?, phone = ?, location = ?, linkedin = ? WHERE id = 1");
            $stmt->execute([$name, $title, $email, $phone, $location, $linkedin]);
            
            // Update admin password
            $admin_content = file_get_contents('admin.php');
            $admin_content = preg_replace('/\$admin_password = \'[^\']*\';/', "\$admin_password = '$admin_password';", $admin_content);
            file_put_contents('admin.php', $admin_content);
            
            $success = 'Configuration completed! Your portfolio is ready.';
            $step = 4;
            
        } catch (Exception $e) {
            $error = 'Configuration failed: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Installation</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 2rem;
            background: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #1f2937;
            text-align: center;
            margin-bottom: 2rem;
        }
        .step {
            margin-bottom: 2rem;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
        }
        .step.active {
            border-color: #2563eb;
            background: #f0f9ff;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
        }
        .btn {
            background: #2563eb;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn:hover {
            background: #1d4ed8;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .requirement {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .requirement:last-child {
            border-bottom: none;
        }
        .status {
            font-weight: 500;
        }
        .status.ok {
            color: #059669;
        }
        .status.error {
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Portfolio Website Installation</h1>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <!-- Step 1: Requirements Check -->
        <div class="step <?= $step === 1 ? 'active' : '' ?>">
            <h3>Step 1: System Requirements</h3>
            <?php if ($step === 1): ?>
                <?php foreach ($requirements as $requirement => $met): ?>
                    <div class="requirement">
                        <span><?= htmlspecialchars($requirement) ?></span>
                        <span class="status <?= $met ? 'ok' : 'error' ?>">
                            <?= $met ? '✓ OK' : '✗ Failed' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
                
                <?php if ($all_met): ?>
                    <p>All requirements met! Proceeding to database setup...</p>
                    <script>setTimeout(() => window.location.href = '?step=2', 2000);</script>
                <?php else: ?>
                    <p>Please fix the failed requirements before proceeding.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>✓ System requirements check completed</p>
            <?php endif; ?>
        </div>
        
        <!-- Step 2: Database Setup -->
        <div class="step <?= $step === 2 ? 'active' : '' ?>">
            <h3>Step 2: Database Configuration</h3>
            <?php if ($step === 2): ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Database Host:</label>
                        <input type="text" name="host" value="localhost" required>
                    </div>
                    <div class="form-group">
                        <label>Database User:</label>
                        <input type="text" name="user" value="root" required>
                    </div>
                    <div class="form-group">
                        <label>Database Password:</label>
                        <input type="password" name="pass" value="">
                    </div>
                    <div class="form-group">
                        <label>Database Name:</label>
                        <input type="text" name="dbname" value="portfolio_db" required>
                    </div>
                    <button type="submit" class="btn">Setup Database</button>
                </form>
            <?php else: ?>
                <p>✓ Database configuration completed</p>
            <?php endif; ?>
        </div>
        
        <!-- Step 3: Site Configuration -->
        <div class="step <?= $step === 3 ? 'active' : '' ?>">
            <h3>Step 3: Site Configuration</h3>
            <?php if ($step === 3): ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Your Name:</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Professional Title:</label>
                        <input type="text" name="title" value="Web Developer & Designer" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address:</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="text" name="phone" value="+1 (555) 123-4567">
                    </div>
                    <div class="form-group">
                        <label>Location:</label>
                        <input type="text" name="location" value="City, State, Country">
                    </div>
                    <div class="form-group">
                        <label>LinkedIn Profile:</label>
                        <input type="text" name="linkedin" value="linkedin.com/in/yourprofile">
                    </div>
                    <div class="form-group">
                        <label>Admin Password:</label>
                        <input type="password" name="admin_password" value="admin123" required>
                    </div>
                    <button type="submit" class="btn">Save Configuration</button>
                </form>
            <?php else: ?>
                <p>✓ Site configuration completed</p>
            <?php endif; ?>
        </div>
        
        <!-- Step 4: Installation Complete -->
        <div class="step <?= $step === 4 ? 'active' : '' ?>">
            <h3>Step 4: Installation Complete!</h3>
            <p>Your portfolio website has been successfully installed.</p>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="index.php" class="btn" style="text-decoration: none; margin-right: 1rem;">View Portfolio</a>
                <a href="admin.php" class="btn" style="text-decoration: none; background: #059669;">Admin Panel</a>
            </div>
            <div style="margin-top: 2rem; padding: 1rem; background: #f0f9ff; border-radius: 8px;">
                <h4>Next Steps:</h4>
                <ul>
                    <li>Customize your portfolio content through the admin panel</li>
                    <li>Add your projects, skills, and experience</li>
                    <li>Update contact information</li>
                    <li>Test the contact form</li>
                    <li>Delete this install.php file for security</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

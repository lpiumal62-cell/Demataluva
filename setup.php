<?php
/**
 * Bright Future Academy - Setup Script
 * Run this script to set up the database and initial configuration
 */

// Check if already configured
if (file_exists('config/setup_complete.txt')) {
    die('Setup already completed. Delete config/setup_complete.txt to run setup again.');
}

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 1:
            // Database configuration
            $host = $_POST['db_host'] ?? 'localhost';
            $name = $_POST['db_name'] ?? 'bright_future_academy';
            $user = $_POST['db_user'] ?? 'root';
            $pass = $_POST['db_pass'] ?? '';
            
            try {
                $pdo = new PDO("mysql:host=$host", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Create database if it doesn't exist
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name`");
                $pdo->exec("USE `$name`");
                
                // Read and execute schema
                $schema = file_get_contents('database/schema.sql');
                $statements = explode(';', $schema);
                
                foreach ($statements as $statement) {
                    $statement = trim($statement);
                    if (!empty($statement)) {
                        $pdo->exec($statement);
                    }
                }
                
                // Update database config
                $config = "<?php
// Database configuration
define('DB_HOST', '$host');
define('DB_NAME', '$name');
define('DB_USER', '$user');
define('DB_PASS', '$pass');

// Create database connection
try {
    \$pdo = new PDO(\"mysql:host=\" . DB_HOST . \";dbname=\" . DB_NAME, DB_USER, DB_PASS);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException \$e) {
    die(\"Connection failed: \" . \$e->getMessage());
}
?>";
                
                file_put_contents('config/database.php', $config);
                $success = 'Database configured successfully!';
                $step = 2;
                
            } catch (PDOException $e) {
                $error = 'Database connection failed: ' . $e->getMessage();
            }
            break;
            
        case 2:
            // Admin account setup
            $username = $_POST['admin_username'] ?? 'admin';
            $password = $_POST['admin_password'] ?? '';
            $email = $_POST['admin_email'] ?? '';
            
            if (empty($password)) {
                $error = 'Password is required.';
            } else {
                try {
                    require_once 'config/database.php';
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $pdo->prepare("UPDATE admins SET username = ?, password = ?, email = ? WHERE id = 1");
                    $stmt->execute([$username, $hashedPassword, $email]);
                    
                    // Mark setup as complete
                    file_put_contents('config/setup_complete.txt', date('Y-m-d H:i:s'));
                    
                    $success = 'Setup completed successfully! You can now access the admin panel.';
                    $step = 3;
                    
                } catch (PDOException $e) {
                    $error = 'Failed to update admin account: ' . $e->getMessage();
                }
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Bright Future Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 font-inter min-h-screen flex items-center justify-center">
    <div class="max-w-2xl w-full mx-4">
        <div class="bg-gray-800 rounded-lg shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">Bright Future Academy</h1>
                <p class="text-gray-400">Setup Wizard</p>
            </div>
            
            <!-- Progress -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-400">Step <?php echo $step; ?> of 3</span>
                    <span class="text-sm text-gray-400"><?php echo round(($step / 3) * 100); ?>% Complete</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: <?php echo ($step / 3) * 100; ?>%"></div>
                </div>
            </div>
            
            <!-- Messages -->
            <?php if($error): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <?php if($success): ?>
            <div class="bg-green-600 text-white p-4 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>
            
            <!-- Step Content -->
            <?php if($step == 1): ?>
            <!-- Step 1: Database Configuration -->
            <div>
                <h2 class="text-xl font-semibold text-white mb-4">Database Configuration</h2>
                <p class="text-gray-400 mb-6">Enter your MySQL database credentials.</p>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-white font-semibold mb-2">Database Host</label>
                        <input type="text" name="db_host" value="localhost" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Database Name</label>
                        <input type="text" name="db_name" value="bright_future_academy" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Database Username</label>
                        <input type="text" name="db_user" value="root" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Database Password</label>
                        <input type="password" name="db_pass" class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300">
                        <i class="fas fa-arrow-right mr-2"></i>Next Step
                    </button>
                </form>
            </div>
            
            <?php elseif($step == 2): ?>
            <!-- Step 2: Admin Account -->
            <div>
                <h2 class="text-xl font-semibold text-white mb-4">Admin Account Setup</h2>
                <p class="text-gray-400 mb-6">Create your administrator account.</p>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-white font-semibold mb-2">Admin Username</label>
                        <input type="text" name="admin_username" value="admin" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Admin Password</label>
                        <input type="password" name="admin_password" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Admin Email</label>
                        <input type="email" name="admin_email" class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300">
                        <i class="fas fa-check mr-2"></i>Complete Setup
                    </button>
                </form>
            </div>
            
            <?php elseif($step == 3): ?>
            <!-- Step 3: Setup Complete -->
            <div class="text-center">
                <div class="bg-green-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-semibold text-white mb-4">Setup Complete!</h2>
                <p class="text-gray-400 mb-8">Your Bright Future Academy website is ready to use.</p>
                
                <div class="space-y-4">
                    <a href="index.php" class="block bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300">
                        <i class="fas fa-home mr-2"></i>Visit Website
                    </a>
                    <a href="admin/login.php" class="block bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300">
                        <i class="fas fa-cog mr-2"></i>Admin Panel
                    </a>
                </div>
                
                <div class="mt-8 p-4 bg-gray-700 rounded-lg">
                    <h3 class="text-white font-semibold mb-2">Important Notes:</h3>
                    <ul class="text-sm text-gray-300 text-left space-y-1">
                        <li>• Delete this setup file for security</li>
                        <li>• Change default admin credentials</li>
                        <li>• Configure your web server properly</li>
                        <li>• Set up regular database backups</li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

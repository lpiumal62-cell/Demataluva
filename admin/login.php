<?php
session_start();
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacherId = isset($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : 0;
    
    if ($teacherId <= 0) {
        $error = 'Please enter a valid Teacher ID.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, first_name, last_name, teacher_id, email FROM admins WHERE teacher_id = ?");
            $stmt->execute([$teacherId]);
            $admin = $stmt->fetch();
            
            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['email'];
                $_SESSION['admin_name'] = trim(($admin['first_name'] ?? '') . ' ' . ($admin['last_name'] ?? ''));
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_teacher_id'] = (int)$admin['teacher_id'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid Teacher ID.';
            }
        } catch (PDOException $e) {
            $error = 'Login failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bright Future Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-900 text-gray-100 font-inter min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-gray-800 rounded-lg shadow-2xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">DMV</h1>
                <p class="text-gray-400">Admin Login</p>
            </div>
            
            <!-- Error Message -->
            <?php if($error): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="POST" class="space-y-6">
                <div>
                    <label for="teacher_id" class="block text-white font-semibold mb-2">
                        <i class="fas fa-id-badge mr-2"></i>Teacher ID
                    </label>
                    <input type="number" id="teacher_id" name="teacher_id" required 
                           class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none form-input" 
                           placeholder="Enter your Teacher ID"
                           value="<?php echo htmlspecialchars($_POST['teacher_id'] ?? ''); ?>">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 btn-animated">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>
            
            <!-- Back to Website -->
            <div class="text-center mt-6">
                <a href="../index.php" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Website
                </a>
                <div class="mt-3">
                    <a href="register.php" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">
                        <i class="fas fa-user-plus mr-2"></i>Register an Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="../assets/js/main.js"></script>
</body>
</html>

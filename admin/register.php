<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Optional: Only allow registration if no admins exist, else require existing admin to create more
$stmt = $pdo->query('SELECT COUNT(*) AS c FROM admins');
$count = (int)$stmt->fetch()['c'];
$requireExistingAdmin = $count > 0;

if ($requireExistingAdmin && !isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = sanitizeInput($_POST['first_name'] ?? '');
    $lastName = sanitizeInput($_POST['last_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $teacherId = isset($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : 0;

    if ($firstName === '' || $lastName === '' || $email === '' || $password === '' || $confirm === '' || $teacherId <= 0) {
        $error = 'All fields (first, last, email, password, teacher ID) are required.';
    } elseif (!validateEmail($email)) {
        $error = 'Invalid email address.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            // Ensure teacher exists
            $chk = $pdo->prepare('SELECT id FROM teachers WHERE id = ?');
            $chk->execute([$teacherId]);
            if (!$chk->fetch()) {
                throw new RuntimeException('Teacher ID does not exist.');
            }

            // Store the password as provided (plaintext) per your request
            $stmt = $pdo->prepare('INSERT INTO admins (first_name, last_name, password, email, teacher_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$firstName, $lastName, $password, $email, $teacherId]);
            $message = 'Admin registered successfully. You may now log in.';
        } catch (Throwable $e) {
            $error = 'Registration failed: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register Admin - Bright Future Academy</title>
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-900 text-gray-100 font-inter min-h-screen flex items-center justify-center">
	<div class="max-w-lg w-full mx-4">
		<div class="bg-gray-800 rounded-lg shadow-2xl p-8">
			<div class="text-center mb-8">
				<div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
					<i class="fas fa-user-plus text-white text-2xl"></i>
				</div>
				<h1 class="text-2xl font-bold text-white">Register Admin</h1>
				<p class="text-gray-400"><?php echo $requireExistingAdmin ? 'Only logged-in admins can create new admins.' : 'Create the first admin account.'; ?></p>
			</div>

			<?php if ($message): ?>
				<div class="bg-green-600 text-white p-4 rounded-lg mb-6">
					<i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($message); ?>
				</div>
			<?php endif; ?>
			<?php if ($error): ?>
				<div class="bg-red-600 text-white p-4 rounded-lg mb-6">
					<i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
				</div>
			<?php endif; ?>

            <form method="POST" class="space-y-5">
				<div>
                    <label class="block text-white font-semibold mb-2">First Name</label>
                    <input name="first_name" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
				</div>
				<div>
                    <label class="block text-white font-semibold mb-2">Last Name</label>
                    <input name="last_name" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">Email</label>
					<input type="email" name="email" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
				</div>
				<div>
					<label class="block text-white font-semibold mb-2">Teacher ID</label>
					<input type="number" name="teacher_id" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" value="<?php echo htmlspecialchars($_POST['teacher_id'] ?? ''); ?>" placeholder="Enter existing teacher ID">
				</div>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
					<div>
						<label class="block text-white font-semibold mb-2">Password</label>
						<input type="password" name="password" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
					</div>
					<div>
						<label class="block text-white font-semibold mb-2">Confirm Password</label>
						<input type="password" name="confirm" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
					</div>
				</div>
				<button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300">
					<i class="fas fa-user-plus mr-2"></i>Create Admin
				</button>
			</form>

			<div class="text-center mt-6">
				<a href="login.php" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">
					<i class="fas fa-arrow-left mr-2"></i>Back to Login
				</a>
			</div>
		</div>
	</div>
</body>
</html>


<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
	header('Location: login.php');
	exit;
}

// Ensure settings table exists
try {
	$pdo->exec("CREATE TABLE IF NOT EXISTS settings (
		`key` VARCHAR(100) PRIMARY KEY,
		`value` TEXT NOT NULL,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
	)");
} catch (PDOException $e) {}

function getSetting($pdo, $key, $default = '') {
	try {
		$s = $pdo->prepare('SELECT value FROM settings WHERE `key` = ?');
		$s->execute([$key]);
		$row = $s->fetch();
		return $row ? $row['value'] : $default;
	} catch (PDOException $e) {
		return $default;
	}
}

function setSetting($pdo, $key, $value) {
	try {
		$s = $pdo->prepare('INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
		$s->execute([$key, $value]);
		return true;
	} catch (PDOException $e) {
		return false;
	}
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$schoolName = trim($_POST['school_name'] ?? '');
	$developer = trim($_POST['developer_name'] ?? '');
	$contactEmail = trim($_POST['contact_email'] ?? '');
	$smtpUse = isset($_POST['mail_use_smtp']) ? '1' : '0';
	$smtpHost = trim($_POST['smtp_host'] ?? '');
	$smtpPort = (int)($_POST['smtp_port'] ?? 587);
	$smtpUser = trim($_POST['smtp_user'] ?? '');
	$smtpPass = trim($_POST['smtp_pass'] ?? '');
	$smtpSecure = trim($_POST['smtp_secure'] ?? 'tls');

	try {
		setSetting($pdo, 'school_name', $schoolName);
		setSetting($pdo, 'developer_name', $developer);
		setSetting($pdo, 'contact_email', $contactEmail);
		setSetting($pdo, 'mail_use_smtp', $smtpUse);
		setSetting($pdo, 'smtp_host', $smtpHost);
		setSetting($pdo, 'smtp_port', (string)$smtpPort);
		setSetting($pdo, 'smtp_user', $smtpUser);
		setSetting($pdo, 'smtp_pass', $smtpPass);
		setSetting($pdo, 'smtp_secure', $smtpSecure);
		$message = 'Settings saved.';
	} catch (Throwable $e) {
		$error = 'Failed to save settings.';
	}
}

$schoolName = getSetting($pdo, 'school_name', 'Demataluva Maha Viddiyalaya');
$developer = getSetting($pdo, 'developer_name', 'BlackEagle');
$contactEmail = getSetting($pdo, 'contact_email', 'info@demataluva.mv');
$smtpUse = getSetting($pdo, 'mail_use_smtp', '0') === '1';
$smtpHost = getSetting($pdo, 'smtp_host', '');
$smtpPort = (int)getSetting($pdo, 'smtp_port', '587');
$smtpUser = getSetting($pdo, 'smtp_user', '');
$smtpPass = getSetting($pdo, 'smtp_pass', '');
$smtpSecure = getSetting($pdo, 'smtp_secure', 'tls');
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Settings - Admin</title>
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-900 text-gray-100 font-inter">
	<nav class="bg-gray-800 border-b border-gray-700">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
			<a href="dashboard.php" class="text-xl font-bold text-blue-400"><i class="fas fa-graduation-cap mr-2"></i>Admin Panel</a>
			<div class="flex items-center space-x-4">
				<a class="text-gray-300 hover:text-white" href="../index.php">View Site</a>
				<a class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded" href="logout.php">Logout</a>
			</div>
		</div>
	</nav>

	<div class="flex">
		<aside class="w-64 bg-gray-800 min-h-screen p-4">
			<nav class="space-y-2">
				<a href="dashboard.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-tachometer-alt mr-3"></i>Dashboard</a>
				<a href="students.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-users mr-3"></i>Students</a>
				<a href="teachers.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-chalkboard-teacher mr-3"></i>Teachers</a>
				<a href="classes.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-book mr-3"></i>Classes</a>
				<a href="scores.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-chart-line mr-3"></i>Test Scores</a>
				<a href="gallery.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-images mr-3"></i>Gallery</a>
				<a href="events.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-calendar-alt mr-3"></i>Events</a>
				<a href="messages.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-envelope mr-3"></i>Messages</a>
				<a href="settings.php" class="flex items-center px-4 py-2 text-blue-400 bg-blue-900 bg-opacity-20 rounded"><i class="fas fa-cog mr-3"></i>Settings</a>
			</nav>
		</aside>

		<main class="flex-1 p-8">
			<h1 class="text-3xl font-bold text-white mb-6">Site Settings</h1>
			<?php if ($message): ?><div class="bg-green-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
			<?php if ($error): ?><div class="bg-red-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

			<form method="POST" class="space-y-8">
				<div class="bg-gray-800 rounded p-6">
					<h2 class="text-xl font-semibold mb-4">General</h2>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<div>
							<label class="block text-white font-semibold mb-2">School Name</label>
							<input name="school_name" value="<?php echo htmlspecialchars($schoolName); ?>" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
						</div>
						<div>
							<label class="block text-white font-semibold mb-2">Developer Name</label>
							<input name="developer_name" value="<?php echo htmlspecialchars($developer); ?>" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
						</div>
						<div class="md:col-span-2">
							<label class="block text-white font-semibold mb-2">Contact Email</label>
							<input type="email" name="contact_email" value="<?php echo htmlspecialchars($contactEmail); ?>" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
						</div>
					</div>
				</div>

				<div class="bg-gray-800 rounded p-6">
					<h2 class="text-xl font-semibold mb-4">Email (SMTP)</h2>
					<div class="space-y-4">
						<label class="inline-flex items-center">
							<input type="checkbox" name="mail_use_smtp" class="mr-2" <?php echo $smtpUse?'checked':''; ?>> Use SMTP
						</label>
						<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
							<input name="smtp_host" value="<?php echo htmlspecialchars($smtpHost); ?>" placeholder="SMTP Host" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
							<input name="smtp_port" type="number" value="<?php echo (int)$smtpPort; ?>" placeholder="Port" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
							<input name="smtp_user" value="<?php echo htmlspecialchars($smtpUser); ?>" placeholder="Username" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
							<input name="smtp_pass" value="<?php echo htmlspecialchars($smtpPass); ?>" placeholder="Password" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
							<select name="smtp_secure" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
								<option value="tls" <?php echo $smtpSecure==='tls'?'selected':''; ?>>TLS</option>
								<option value="ssl" <?php echo $smtpSecure==='ssl'?'selected':''; ?>>SSL</option>
							</select>
						</div>
					</div>
				</div>

				<div>
					<button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded"><i class="fas fa-save mr-2"></i>Save Settings</button>
				</div>
			</form>
		</main>
	</div>

	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script src="../assets/js/main.js"></script>
</body>
</html>



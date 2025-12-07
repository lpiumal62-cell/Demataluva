<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
	header('Location: login.php');
	exit;
}

$message = '';
$error = '';

// Upload directory
$uploadDir = dirname(__DIR__) . '/uploads/teachers';
if (!is_dir($uploadDir)) {
	@mkdir($uploadDir, 0755, true);
}

function safeFileName($name) {
	$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
	$base = pathinfo($name, PATHINFO_FILENAME);
	$slug = preg_replace('/[^a-z0-9-_]+/i', '-', $base);
	$slug = trim($slug, '-');
	return $slug . '-' . uniqid() . '.' . $ext;
}

function validImage($tmp): bool {
	$info = @getimagesize($tmp);
	if ($info === false) return false;
	$allowed = ['image/jpeg','image/png','image/gif','image/webp'];
	return in_array($info['mime'], $allowed, true);
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	try {
		if ($action === 'create') {
			$name = sanitizeInput($_POST['name'] ?? '');
			$subject = sanitizeInput($_POST['subject'] ?? '');
			$bio = sanitizeInput($_POST['bio'] ?? '');
			$email = sanitizeInput($_POST['email'] ?? '');
			$phone = sanitizeInput($_POST['phone'] ?? '');
			$imagePath = null;
			if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
				if (!validImage($_FILES['image']['tmp_name'])) throw new RuntimeException('Invalid image.');
				if ($_FILES['image']['size'] > 5*1024*1024) throw new RuntimeException('Image too large.');
				$fname = safeFileName($_FILES['image']['name']);
				move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . '/' . $fname);
				$imagePath = 'uploads/teachers/' . $fname;
			}
			$stmt = $pdo->prepare('INSERT INTO teachers (name, subject, image, bio, email, phone) VALUES (?, ?, ?, ?, ?, ?)');
			$stmt->execute([$name, $subject, $imagePath, $bio, $email, $phone]);
			$message = 'Teacher added.';
		} elseif ($action === 'update') {
			$id = (int)($_POST['id'] ?? 0);
			$name = sanitizeInput($_POST['name'] ?? '');
			$subject = sanitizeInput($_POST['subject'] ?? '');
			$bio = sanitizeInput($_POST['bio'] ?? '');
			$email = sanitizeInput($_POST['email'] ?? '');
			$phone = sanitizeInput($_POST['phone'] ?? '');
			$imageSet = '';
			$params = [$name, $subject, $bio, $email, $phone, $id];
			if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
				if (!validImage($_FILES['image']['tmp_name'])) throw new RuntimeException('Invalid image.');
				if ($_FILES['image']['size'] > 5*1024*1024) throw new RuntimeException('Image too large.');
				$fname = safeFileName($_FILES['image']['name']);
				move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . '/' . $fname);
				$imageSet = ', image = ?';
				array_splice($params, 2, 0, 'uploads/teachers/' . $fname); // insert after subject
			}
			$sql = 'UPDATE teachers SET name = ?, subject = ?' . $imageSet . ', bio = ?, email = ?, phone = ? WHERE id = ?';
			$stmt = $pdo->prepare($sql);
			$stmt->execute($params);
			$message = 'Teacher updated.';
		} elseif ($action === 'delete') {
			$id = (int)($_POST['id'] ?? 0);
			if ($id <= 0) throw new RuntimeException('Invalid teacher.');
			// remove image file
			$img = $pdo->prepare('SELECT image FROM teachers WHERE id = ?');
			$img->execute([$id]);
			$row = $img->fetch();
			if ($row && $row['image']) {
				$full = dirname(__DIR__) . '/' . $row['image'];
				if (is_file($full)) @unlink($full);
			}
			$stmt = $pdo->prepare('DELETE FROM teachers WHERE id = ?');
			$stmt->execute([$id]);
			$message = 'Teacher deleted.';
		}
	} catch (Throwable $e) {
		$error = $e->getMessage();
	}
}

// Fetch teachers
try {
	$teachers = $pdo->query('SELECT * FROM teachers ORDER BY name')->fetchAll();
} catch (PDOException $e) {
	$teachers = [];
	$error = 'Failed to load teachers.';
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Teachers - Admin</title>
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
				<a href="teachers.php" class="flex items-center px-4 py-2 text-blue-400 bg-blue-900 bg-opacity-20 rounded"><i class="fas fa-chalkboard-teacher mr-3"></i>Teachers</a>
				<a href="classes.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-book mr-3"></i>Classes</a>
				<a href="scores.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-chart-line mr-3"></i>Test Scores</a>
				<a href="gallery.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-images mr-3"></i>Gallery</a>
				<a href="events.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-calendar-alt mr-3"></i>Events</a>
			</nav>
		</aside>

		<main class="flex-1 p-8">
			<h1 class="text-3xl font-bold text-white mb-6">Manage Teachers</h1>
			<?php if ($message): ?><div class="bg-green-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
			<?php if ($error): ?><div class="bg-red-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

			<div class="bg-gray-800 rounded p-6 mb-8">
				<h2 class="text-xl font-semibold mb-4">Add Teacher</h2>
				<form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
					<input type="hidden" name="action" value="create">
					<input class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="name" placeholder="Name" required>
					<input class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="subject" placeholder="Subject" required>
					<input type="file" name="image" accept="image/*" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
					<input class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="email" placeholder="Email">
					<input class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="phone" placeholder="Phone">
					<textarea class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600 md:col-span-2 lg:col-span-3" name="bio" placeholder="Bio"></textarea>
					<div class="md:col-span-2 lg:col-span-3">
						<button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded"><i class="fas fa-plus mr-2"></i>Add</button>
					</div>
				</form>
			</div>

			<div class="bg-gray-800 rounded p-6">
				<h2 class="text-xl font-semibold mb-4">Teachers (<?php echo count($teachers); ?>)</h2>
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
					<?php foreach ($teachers as $t): ?>
						<div class="bg-gray-700 rounded overflow-hidden">
							<div class="h-48 bg-black flex items-center justify-center">
								<?php if ($t['image']): ?>
									<img src="<?php echo '../' . htmlspecialchars($t['image']); ?>" class="w-full h-full object-cover">
								<?php else: ?>
									<i class="fas fa-user text-4xl text-white"></i>
								<?php endif; ?>
							</div>
							<div class="p-4 space-y-2">
								<form method="POST" enctype="multipart/form-data" class="space-y-2">
									<input type="hidden" name="action" value="update">
									<input type="hidden" name="id" value="<?php echo $t['id']; ?>">
									<input class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="name" value="<?php echo htmlspecialchars($t['name']); ?>">
									<input class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="subject" value="<?php echo htmlspecialchars($t['subject']); ?>">
									<input type="file" name="image" accept="image/*" class="bg-gray-600 text-white px-3 py-2 rounded w-full">
									<input class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="email" value="<?php echo htmlspecialchars($t['email']); ?>" placeholder="Email">
									<input class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="phone" value="<?php echo htmlspecialchars($t['phone']); ?>" placeholder="Phone">
									<textarea class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="bio" placeholder="Bio"><?php echo htmlspecialchars($t['bio']); ?></textarea>
									<div class="flex items-center justify-between">
										<button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"><i class="fas fa-save mr-1"></i>Save</button>
									</div>
								</form>
								<form method="POST" onsubmit="return confirm('Delete this teacher?');">
									<input type="hidden" name="action" value="delete">
									<input type="hidden" name="id" value="<?php echo $t['id']; ?>">
									<button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded w-full mt-2"><i class="fas fa-trash mr-1"></i>Delete</button>
								</form>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</main>
	</div>

	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script src="../assets/js/main.js"></script>
</body>
</html>



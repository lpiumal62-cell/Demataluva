<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
	header('Location: login.php');
	exit;
}

$message = '';
$error = '';

// Ensure uploads directory exists
$uploadBaseDir = dirname(__DIR__) . '/uploads/gallery';
$uploadBaseUrl = '../uploads/gallery';
if (!is_dir($uploadBaseDir)) {
	@mkdir($uploadBaseDir, 0755, true);
}

// Helpers
function generateSafeFilename(string $originalName): string {
	$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
	$basename = pathinfo($originalName, PATHINFO_FILENAME);
	$slug = preg_replace('/[^a-z0-9-_]+/i', '-', $basename);
	$slug = trim($slug, '-');
	return $slug . '-' . uniqid() . '.' . $ext;
}

function isAllowedImageMime(string $mime): bool {
	$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
	return in_array($mime, $allowed, true);
}

// Handle create/update/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	try {
		if ($action === 'create') {
			$event = sanitizeInput($_POST['event'] ?? '');
			$year = (int)($_POST['year'] ?? date('Y'));
			$description = sanitizeInput($_POST['description'] ?? '');
			$type = 'image';

			if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
				throw new RuntimeException('Please select an image to upload.');
			}

			$f = $_FILES['image'];
			$info = @getimagesize($f['tmp_name']);
			if ($info === false || !isAllowedImageMime($info['mime'])) {
				throw new RuntimeException('Unsupported image format. Allowed: JPG, PNG, GIF, WEBP.');
			}
			if ($f['size'] > 5 * 1024 * 1024) {
				throw new RuntimeException('Image too large. Max 5 MB.');
			}

			$filename = generateSafeFilename($f['name']);
			$targetPath = $uploadBaseDir . '/' . $filename;
			if (!move_uploaded_file($f['tmp_name'], $targetPath)) {
				throw new RuntimeException('Failed to move uploaded file.');
			}

			$imagePath = 'uploads/gallery/' . $filename;
			$stmt = $pdo->prepare('INSERT INTO gallery (image_path, type, event, year, description) VALUES (?, ?, ?, ?, ?)');
			$stmt->execute([$imagePath, $type, $event, $year, $description]);
			$message = 'Image added successfully.';
		} elseif ($action === 'delete') {
			$id = (int)($_POST['id'] ?? 0);
			if ($id <= 0) {
				throw new RuntimeException('Invalid item.');
			}
			// Fetch to delete file
			$stmt = $pdo->prepare('SELECT image_path FROM gallery WHERE id = ?');
			$stmt->execute([$id]);
			$it = $stmt->fetch();
			if ($it) {
				$full = dirname(__DIR__) . '/' . $it['image_path'];
				if (is_file($full)) {
					@unlink($full);
				}
			}
			$stmt = $pdo->prepare('DELETE FROM gallery WHERE id = ?');
			$stmt->execute([$id]);
			$message = 'Image deleted.';
		} elseif ($action === 'update') {
			$id = (int)($_POST['id'] ?? 0);
			$event = sanitizeInput($_POST['event'] ?? '');
			$year = (int)($_POST['year'] ?? date('Y'));
			$description = sanitizeInput($_POST['description'] ?? '');
			if ($id <= 0) {
				throw new RuntimeException('Invalid item.');
			}
			$stmt = $pdo->prepare('UPDATE gallery SET event = ?, year = ?, description = ? WHERE id = ?');
			$stmt->execute([$event, $year, $description, $id]);
			$message = 'Details updated.';
		}
	} catch (Throwable $e) {
		$error = $e->getMessage();
	}
}

// Load items
try {
	$stmt = $pdo->query('SELECT * FROM gallery ORDER BY created_at DESC');
	$items = $stmt->fetchAll();
} catch (PDOException $e) {
	$items = [];
	$error = 'Failed to load gallery.';
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Gallery - Admin Panel</title>
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-900 text-gray-100 font-inter">
	<nav class="bg-gray-800 border-b border-gray-700">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex justify-between items-center h-16">
				<div class="flex items-center">
					<a href="dashboard.php" class="text-xl font-bold text-blue-400">
						<i class="fas fa-graduation-cap mr-2"></i>Admin Panel
					</a>
				</div>
				<div class="flex items-center space-x-4">
					<span class="text-gray-300">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
					<a href="../index.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">
						<i class="fas fa-external-link-alt mr-1"></i>View Website
					</a>
					<a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
						<i class="fas fa-sign-out-alt mr-1"></i>Logout
					</a>
				</div>
			</div>
		</div>
	</nav>

	<div class="flex">
		<div class="w-64 bg-gray-800 min-h-screen">
			<div class="p-4">
				<nav class="space-y-2">
					<a href="dashboard.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
						<i class="fas fa-tachometer-alt mr-3"></i>Dashboard
					</a>
					<a href="students.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
						<i class="fas fa-users mr-3"></i>Students
					</a>
					<a href="teachers.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
						<i class="fas fa-chalkboard-teacher mr-3"></i>Teachers
					</a>
					<a href="classes.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
						<i class="fas fa-book mr-3"></i>Classes
					</a>
					<a href="scores.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
						<i class="fas fa-chart-line mr-3"></i>Test Scores
					</a>
					<a href="gallery.php" class="flex items-center px-4 py-2 text-blue-400 bg-blue-900 bg-opacity-20 rounded-lg">
						<i class="fas fa-images mr-3"></i>Gallery
					</a>
					<a href="events.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
						<i class="fas fa-calendar-alt mr-3"></i>Events
					</a>
					<a href="messages.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
						<i class="fas fa-envelope mr-3"></i>Messages
					</a>
				</nav>
			</div>
		</div>

		<div class="flex-1 p-8">
			<div class="mb-8">
				<h1 class="text-3xl font-bold text-white mb-2">Manage Gallery</h1>
				<p class="text-gray-400">Upload, edit, and delete gallery images</p>
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

			<div class="bg-gray-800 rounded-lg p-6 mb-8">
				<h2 class="text-xl font-semibold text-white mb-4">Add New Image</h2>
				<form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
					<input type="hidden" name="action" value="create">
					<div>
						<label class="block text-white font-semibold mb-2">Image *</label>
						<input type="file" name="image" accept="image/*" required class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
					</div>
					<div>
						<label class="block text-white font-semibold mb-2">Event</label>
						<input type="text" name="event" placeholder="e.g., Science Fair" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
					</div>
					<div>
						<label class="block text-white font-semibold mb-2">Year</label>
						<input type="number" name="year" value="<?php echo date('Y'); ?>" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
					</div>
					<div class="md:col-span-2 lg:col-span-3">
						<label class="block text-white font-semibold mb-2">Description</label>
						<textarea name="description" rows="3" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none"></textarea>
					</div>
					<div class="md:col-span-2 lg:col-span-3">
						<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-300">
							<i class="fas fa-upload mr-2"></i>Upload Image
						</button>
					</div>
				</form>
			</div>

			<div class="bg-gray-800 rounded-lg p-6">
				<h2 class="text-xl font-semibold text-white mb-4">Gallery Items (<?php echo count($items); ?>)</h2>
				<?php if (empty($items)): ?>
					<div class="text-center py-12">
						<i class="fas fa-images text-5xl text-gray-500 mb-4"></i>
						<p class="text-gray-300">No images uploaded yet.</p>
					</div>
				<?php else: ?>
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
						<?php foreach ($items as $it): ?>
							<div class="bg-gray-700 rounded-lg overflow-hidden">
								<div class="h-56 bg-black">
									<img src="<?php echo '../' . htmlspecialchars($it['image_path']); ?>" alt="<?php echo htmlspecialchars($it['description']); ?>" class="w-full h-full object-cover">
								</div>
								<div class="p-4 space-y-2">
									<div class="flex items-center justify-between">
										<h3 class="text-white font-semibold line-clamp-1"><?php echo htmlspecialchars($it['event'] ?: 'Untitled'); ?></h3>
										<span class="text-gray-400 text-sm"><?php echo $it['year'] ?: '—'; ?></span>
									</div>
									<p class="text-gray-300 text-sm line-clamp-2"><?php echo htmlspecialchars($it['description']); ?></p>
									<div class="flex items-center justify-between pt-2">
										<form method="POST" class="flex items-center gap-2">
											<input type="hidden" name="action" value="update">
											<input type="hidden" name="id" value="<?php echo $it['id']; ?>">
											<input name="event" value="<?php echo htmlspecialchars($it['event']); ?>" placeholder="Event" class="bg-gray-600 text-white px-2 py-1 rounded text-sm border border-gray-500 focus:border-blue-500 focus:outline-none">
											<input type="number" name="year" value="<?php echo (int)$it['year']; ?>" class="w-24 bg-gray-600 text-white px-2 py-1 rounded text-sm border border-gray-500 focus:border-blue-500 focus:outline-none">
											<button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"><i class="fas fa-save"></i></button>
										</form>
										<form method="POST" onsubmit="return confirm('Delete this image?');">
											<input type="hidden" name="action" value="delete">
											<input type="hidden" name="id" value="<?php echo $it['id']; ?>">
											<button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"><i class="fas fa-trash"></i></button>
										</form>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script src="../assets/js/main.js"></script>
</body>
</html>

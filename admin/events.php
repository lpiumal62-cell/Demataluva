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
$uploadDir = dirname(__DIR__) . '/uploads/events';
if (!is_dir($uploadDir)) {
	@mkdir($uploadDir, 0755, true);
}

function evSafeFileName($name) {
	$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
	$base = pathinfo($name, PATHINFO_FILENAME);
	$slug = preg_replace('/[^a-z0-9-_]+/i', '-', $base);
	$slug = trim($slug, '-');
	return $slug . '-' . uniqid() . '.' . $ext;
}

function evValidImage($tmp): bool {
	$info = @getimagesize($tmp);
	if ($info === false) return false;
	$allowed = ['image/jpeg','image/png','image/gif','image/webp'];
	return in_array($info['mime'], $allowed, true);
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	try {
		if ($action === 'create') {
			$title = sanitizeInput($_POST['title'] ?? '');
			$description = sanitizeInput($_POST['description'] ?? '');
			$date = sanitizeInput($_POST['date'] ?? '');
			$time = sanitizeInput($_POST['time'] ?? '');
			$location = sanitizeInput($_POST['location'] ?? '');
			$image = null;
			if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
				if (!evValidImage($_FILES['image']['tmp_name'])) throw new RuntimeException('Invalid image.');
				if ($_FILES['image']['size'] > 5*1024*1024) throw new RuntimeException('Image too large.');
				$fname = evSafeFileName($_FILES['image']['name']);
				move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . '/' . $fname);
				$image = 'uploads/events/' . $fname;
			}
			$stmt = $pdo->prepare('INSERT INTO events (title, description, image, date, time, location) VALUES (?, ?, ?, ?, ?, ?)');
			$stmt->execute([$title, $description, $image, $date, $time, $location]);
			$message = 'Event created.';
		} elseif ($action === 'update') {
			$id = (int)($_POST['id'] ?? 0);
			$title = sanitizeInput($_POST['title'] ?? '');
			$description = sanitizeInput($_POST['description'] ?? '');
			$date = sanitizeInput($_POST['date'] ?? '');
			$time = sanitizeInput($_POST['time'] ?? '');
			$location = sanitizeInput($_POST['location'] ?? '');
			$params = [$title, $description, $date, $time, $location, $id];
			$imgSet = '';
			if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
				if (!evValidImage($_FILES['image']['tmp_name'])) throw new RuntimeException('Invalid image.');
				if ($_FILES['image']['size'] > 5*1024*1024) throw new RuntimeException('Image too large.');
				$fname = evSafeFileName($_FILES['image']['name']);
				move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . '/' . $fname);
				$imgSet = ', image = ?';
				array_splice($params, 2, 0, 'uploads/events/' . $fname); // insert before date
			}
			$sql = 'UPDATE events SET title = ?, description = ?' . $imgSet . ', date = ?, time = ?, location = ? WHERE id = ?';
			$stmt = $pdo->prepare($sql);
			$stmt->execute($params);
			$message = 'Event updated.';
		} elseif ($action === 'delete') {
			$id = (int)($_POST['id'] ?? 0);
			if ($id <= 0) throw new RuntimeException('Invalid event.');
			$img = $pdo->prepare('SELECT image FROM events WHERE id = ?');
			$img->execute([$id]);
			$row = $img->fetch();
			if ($row && $row['image']) {
				$full = dirname(__DIR__) . '/' . $row['image'];
				if (is_file($full)) @unlink($full);
			}
			$stmt = $pdo->prepare('DELETE FROM events WHERE id = ?');
			$stmt->execute([$id]);
			$message = 'Event deleted.';
		}
	} catch (Throwable $e) {
		$error = $e->getMessage();
	}
}

// Fetch events
try {
	$events = $pdo->query('SELECT * FROM events ORDER BY date DESC')->fetchAll();
} catch (PDOException $e) {
	$events = [];
	$error = 'Failed to load events.';
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Events - Admin</title>
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
				<a href="events.php" class="flex items-center px-4 py-2 text-blue-400 bg-blue-900 bg-opacity-20 rounded"><i class="fas fa-calendar-alt mr-3"></i>Events</a>
			</nav>
		</aside>

		<main class="flex-1 p-8">
			<h1 class="text-3xl font-bold text-white mb-6">Manage Events</h1>
			<?php if ($message): ?><div class="bg-green-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
			<?php if ($error): ?><div class="bg-red-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

			<div class="bg-gray-800 rounded p-6 mb-8">
				<h2 class="text-xl font-semibold mb-4">Create Event</h2>
				<form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
					<input type="hidden" name="action" value="create">
					<input class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="title" placeholder="Title" required>
					<input type="date" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="date" required>
					<input type="time" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="time">
					<input class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="location" placeholder="Location">
					<input type="file" name="image" accept="image/*" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
					<textarea class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600 md:col-span-2 lg:col-span-3" name="description" placeholder="Description"></textarea>
					<div class="md:col-span-2 lg:col-span-3">
						<button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded"><i class="fas fa-plus mr-2"></i>Create</button>
					</div>
				</form>
			</div>

			<div class="bg-gray-800 rounded p-6">
				<h2 class="text-xl font-semibold mb-4">Events (<?php echo count($events); ?>)</h2>
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
					<?php foreach ($events as $ev): ?>
						<div class="bg-gray-700 rounded overflow-hidden">
							<div class="h-48 bg-black flex items-center justify-center">
								<?php if ($ev['image']): ?>
									<img src="<?php echo '../' . htmlspecialchars($ev['image']); ?>" class="w-full h-full object-cover">
								<?php else: ?>
									<i class="fas fa-calendar-alt text-4xl text-white"></i>
								<?php endif; ?>
							</div>
							<div class="p-4 space-y-2">
								<form method="POST" enctype="multipart/form-data" class="space-y-2">
									<input type="hidden" name="action" value="update">
									<input type="hidden" name="id" value="<?php echo $ev['id']; ?>">
									<input class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="title" value="<?php echo htmlspecialchars($ev['title']); ?>">
									<input type="date" class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="date" value="<?php echo htmlspecialchars($ev['date']); ?>">
									<input type="time" class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="time" value="<?php echo htmlspecialchars($ev['time']); ?>">
									<input class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="location" value="<?php echo htmlspecialchars($ev['location']); ?>" placeholder="Location">
									<input type="file" name="image" accept="image/*" class="bg-gray-600 text-white px-3 py-2 rounded w-full">
									<textarea class="bg-gray-600 text-white px-3 py-2 rounded w-full" name="description" placeholder="Description"><?php echo htmlspecialchars($ev['description']); ?></textarea>
									<div class="flex items-center justify-between">
										<button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"><i class="fas fa-save mr-1"></i>Save</button>
									</div>
								</form>
								<form method="POST" onsubmit="return confirm('Delete this event?');">
									<input type="hidden" name="action" value="delete">
									<input type="hidden" name="id" value="<?php echo $ev['id']; ?>">
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



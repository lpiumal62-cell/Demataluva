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

// Load teachers for assignment
try {
	$teachersList = $pdo->query('SELECT id, name, subject FROM teachers ORDER BY name')->fetchAll();
} catch (PDOException $e) {
	$teachersList = [];
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	try {
		if ($action === 'create') {
			$name = sanitizeInput($_POST['name'] ?? '');
			$teacherId = (int)($_POST['teacher_id'] ?? 0);
			$year = (int)($_POST['year'] ?? date('Y'));
			$number = (int)($_POST['number_of_students'] ?? 0);
			$description = sanitizeInput($_POST['description'] ?? '');
			$stmt = $pdo->prepare('INSERT INTO classes (name, teacher_id, year, number_of_students, description) VALUES (?, ?, ?, ?, ?)');
			$stmt->execute([$name, ($teacherId ?: null), $year, $number, $description]);
			$message = 'Class created.';
		} elseif ($action === 'update') {
			$id = (int)($_POST['id'] ?? 0);
			$name = sanitizeInput($_POST['name'] ?? '');
			$teacherId = (int)($_POST['teacher_id'] ?? 0);
			$year = (int)($_POST['year'] ?? date('Y'));
			$number = (int)($_POST['number_of_students'] ?? 0);
			$description = sanitizeInput($_POST['description'] ?? '');
			$stmt = $pdo->prepare('UPDATE classes SET name = ?, teacher_id = ?, year = ?, number_of_students = ?, description = ? WHERE id = ?');
			$stmt->execute([$name, ($teacherId ?: null), $year, $number, $description, $id]);
			$message = 'Class updated.';
		} elseif ($action === 'delete') {
			$id = (int)($_POST['id'] ?? 0);
			$stmt = $pdo->prepare('DELETE FROM classes WHERE id = ?');
			$stmt->execute([$id]);
			$message = 'Class deleted.';
		}
	} catch (Throwable $e) {
		$error = $e->getMessage();
	}
}

// Fetch classes joined with teacher name
try {
	$classes = $pdo->query('SELECT c.*, t.name AS teacher_name FROM classes c LEFT JOIN teachers t ON c.teacher_id = t.id ORDER BY c.year DESC, c.name')->fetchAll();
} catch (PDOException $e) {
	$classes = [];
	$error = 'Failed to load classes.';
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Classes - Admin</title>
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
				<a href="classes.php" class="flex items-center px-4 py-2 text-blue-400 bg-blue-900 bg-opacity-20 rounded"><i class="fas fa-book mr-3"></i>Classes</a>
				<a href="scores.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-chart-line mr-3"></i>Test Scores</a>
				<a href="gallery.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-images mr-3"></i>Gallery</a>
				<a href="events.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-calendar-alt mr-3"></i>Events</a>
			</nav>
		</aside>

		<main class="flex-1 p-8">
			<h1 class="text-3xl font-bold text-white mb-6">Manage Classes</h1>
			<?php if ($message): ?><div class="bg-green-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
			<?php if ($error): ?><div class="bg-red-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

			<div class="bg-gray-800 rounded p-6 mb-8">
				<h2 class="text-xl font-semibold mb-4">Add Class</h2>
				<form method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
					<input type="hidden" name="action" value="create">
					<input class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="name" placeholder="Class name (e.g., Grade 5A)" required>
					<select class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="teacher_id">
						<option value="">Assign Teacher (optional)</option>
						<?php foreach ($teachersList as $tr): ?>
							<option value="<?php echo $tr['id']; ?>"><?php echo htmlspecialchars($tr['name'] . ' - ' . $tr['subject']); ?></option>
						<?php endforeach; ?>
					</select>
					<input type="number" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="year" value="<?php echo date('Y'); ?>">
					<input type="number" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="number_of_students" placeholder="Students" min="0">
					<textarea class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600 md:col-span-2 lg:col-span-3" name="description" placeholder="Description"></textarea>
					<div class="md:col-span-2 lg:col-span-3">
						<button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded"><i class="fas fa-plus mr-2"></i>Add Class</button>
					</div>
				</form>
			</div>

			<div class="bg-gray-800 rounded p-6">
				<h2 class="text-xl font-semibold mb-4">Classes (<?php echo count($classes); ?>)</h2>
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="bg-gray-700">
							<tr>
								<th class="px-4 py-3 text-left text-white">Name</th>
								<th class="px-4 py-3 text-left text-white">Teacher</th>
								<th class="px-4 py-3 text-left text-white">Year</th>
								<th class="px-4 py-3 text-left text-white">Students</th>
								<th class="px-4 py-3 text-left text-white">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($classes as $c): ?>
								<tr class="border-b border-gray-700">
									<td class="px-4 py-3 text-white"><?php echo htmlspecialchars($c['name']); ?></td>
									<td class="px-4 py-3 text-gray-300"><?php echo htmlspecialchars($c['teacher_name'] ?: '—'); ?></td>
									<td class="px-4 py-3 text-gray-300"><?php echo (int)$c['year']; ?></td>
									<td class="px-4 py-3 text-gray-300"><?php echo (int)$c['number_of_students']; ?></td>
									<td class="px-4 py-3">
										<div class="flex items-center space-x-2">
											<button onclick="openEdit(<?php echo htmlspecialchars(json_encode($c)); ?>)" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"><i class="fas fa-edit"></i></button>
											<form method="POST" onsubmit="return confirm('Delete this class?');">
												<input type="hidden" name="action" value="delete">
												<input type="hidden" name="id" value="<?php echo $c['id']; ?>">
												<button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"><i class="fas fa-trash"></i></button>
											</form>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Edit Modal -->
			<div id="editModal" class="modal">
				<div class="modal-content max-w-2xl">
					<button class="modal-close absolute top-4 right-4 text-white text-2xl hover:text-blue-400">&times;</button>
					<div class="p-6">
						<h3 class="text-2xl font-bold text-white mb-4">Edit Class</h3>
						<form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
							<input type="hidden" name="action" value="update">
							<input type="hidden" name="id" id="editId">
							<input class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="name" id="editName" required>
							<select class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="teacher_id" id="editTeacherId">
								<option value="">Assign Teacher (optional)</option>
								<?php foreach ($teachersList as $tr): ?>
									<option value="<?php echo $tr['id']; ?>"><?php echo htmlspecialchars($tr['name'] . ' - ' . $tr['subject']); ?></option>
								<?php endforeach; ?>
							</select>
							<input type="number" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="year" id="editYear">
							<input type="number" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" name="number_of_students" id="editNumber">
							<textarea class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600 md:col-span-2" name="description" id="editDescription" placeholder="Description"></textarea>
							<div class="md:col-span-2">
								<button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded"><i class="fas fa-save mr-2"></i>Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>

		</main>
	</div>

	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script src="../assets/js/main.js"></script>
	<script>
		function openEdit(c) {
			document.getElementById('editId').value = c.id;
			document.getElementById('editName').value = c.name;
			document.getElementById('editTeacherId').value = c.teacher_id || '';
			document.getElementById('editYear').value = c.year;
			document.getElementById('editNumber').value = c.number_of_students;
			document.getElementById('editDescription').value = c.description || '';
			document.getElementById('editModal').classList.add('active');
			document.body.style.overflow = 'hidden';
		}
	</script>
</body>
</html>



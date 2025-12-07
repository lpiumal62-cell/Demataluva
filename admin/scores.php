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

// Load classes and students for filters/selects
try {
	$classes = $pdo->query('SELECT id, name FROM classes ORDER BY name')->fetchAll();
} catch (PDOException $e) { $classes = []; }

$filterClassId = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$filterYear = isset($_GET['year']) ? (int)$_GET['year'] : 0;
$filterStudentId = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

// Build student list (optionally by class)
try {
	if ($filterClassId > 0) {
		$st = $pdo->prepare('SELECT id, name FROM students WHERE class_id = ? ORDER BY name');
		$st->execute([$filterClassId]);
		$students = $st->fetchAll();
	} else {
		$students = $pdo->query('SELECT id, name FROM students ORDER BY name')->fetchAll();
	}
} catch (PDOException $e) { $students = []; }

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	try {
		if ($action === 'create') {
			$studentId = (int)($_POST['student_id'] ?? 0);
			$subject = sanitizeInput($_POST['subject'] ?? '');
			$score = (float)($_POST['score'] ?? 0);
			$maxScore = (float)($_POST['max_score'] ?? 100);
			$year = (int)($_POST['year'] ?? date('Y'));
			$semester = sanitizeInput($_POST['semester'] ?? '');
			$testType = sanitizeInput($_POST['test_type'] ?? '');
			$stmt = $pdo->prepare('INSERT INTO test_scores (student_id, subject, score, max_score, year, semester, test_type) VALUES (?, ?, ?, ?, ?, ?, ?)');
			$stmt->execute([$studentId, $subject, $score, $maxScore, $year, $semester, $testType]);
			$message = 'Score added.';
		} elseif ($action === 'update') {
			$id = (int)($_POST['id'] ?? 0);
			$studentId = (int)($_POST['student_id'] ?? 0);
			$subject = sanitizeInput($_POST['subject'] ?? '');
			$score = (float)($_POST['score'] ?? 0);
			$maxScore = (float)($_POST['max_score'] ?? 100);
			$year = (int)($_POST['year'] ?? date('Y'));
			$semester = sanitizeInput($_POST['semester'] ?? '');
			$testType = sanitizeInput($_POST['test_type'] ?? '');
			$stmt = $pdo->prepare('UPDATE test_scores SET student_id = ?, subject = ?, score = ?, max_score = ?, year = ?, semester = ?, test_type = ? WHERE id = ?');
			$stmt->execute([$studentId, $subject, $score, $maxScore, $year, $semester, $testType, $id]);
			$message = 'Score updated.';
		} elseif ($action === 'delete') {
			$id = (int)($_POST['id'] ?? 0);
			$stmt = $pdo->prepare('DELETE FROM test_scores WHERE id = ?');
			$stmt->execute([$id]);
			$message = 'Score deleted.';
		}
	} catch (Throwable $e) {
		$error = 'Action failed: ' . htmlspecialchars($e->getMessage());
	}
}

// Query scores with joins for display
$sql = 'SELECT ts.*, s.name AS student_name, c.name AS class_name
        FROM test_scores ts
        LEFT JOIN students s ON ts.student_id = s.id
        LEFT JOIN classes c ON s.class_id = c.id';
$where = [];
$params = [];
if ($filterClassId > 0) { $where[] = 's.class_id = ?'; $params[] = $filterClassId; }
if ($filterStudentId > 0) { $where[] = 's.id = ?'; $params[] = $filterStudentId; }
if ($filterYear > 0) { $where[] = 'ts.year = ?'; $params[] = $filterYear; }
if ($where) { $sql .= ' WHERE ' . implode(' AND ', $where); }
$sql .= ' ORDER BY ts.year DESC, s.name, ts.subject';

try {
	$st = $pdo->prepare($sql);
	$st->execute($params);
	$scores = $st->fetchAll();
} catch (PDOException $e) {
	$scores = [];
	$error = 'Failed to load scores.';
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Test Scores - Admin</title>
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
				<a href="scores.php" class="flex items-center px-4 py-2 text-blue-400 bg-blue-900 bg-opacity-20 rounded"><i class="fas fa-chart-line mr-3"></i>Test Scores</a>
				<a href="gallery.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-images mr-3"></i>Gallery</a>
				<a href="events.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-calendar-alt mr-3"></i>Events</a>
				<a href="messages.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded"><i class="fas fa-envelope mr-3"></i>Messages</a>
			</nav>
		</aside>

		<main class="flex-1 p-8">
			<h1 class="text-3xl font-bold text-white mb-6">Manage Test Scores</h1>
			<?php if ($message): ?><div class="bg-green-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
			<?php if ($error): ?><div class="bg-red-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

			<form method="GET" class="mb-6">
				<div class="grid grid-cols-1 md:grid-cols-4 gap-3">
					<select name="class_id" class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700" onchange="this.form.submit()">
						<option value="0">All Classes</option>
						<?php foreach ($classes as $cl): ?>
							<option value="<?php echo $cl['id']; ?>" <?php echo $filterClassId===$cl['id']?'selected':''; ?>><?php echo htmlspecialchars($cl['name']); ?></option>
						<?php endforeach; ?>
					</select>
					<select name="student_id" class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700">
						<option value="0">All Students</option>
						<?php foreach ($students as $st): ?>
							<option value="<?php echo $st['id']; ?>" <?php echo $filterStudentId===$st['id']?'selected':''; ?>><?php echo htmlspecialchars($st['name']); ?></option>
						<?php endforeach; ?>
					</select>
					<input name="year" type="number" value="<?php echo $filterYear?:''; ?>" class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700" placeholder="Year">
					<button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Filter</button>
				</div>
			</form>

			<div class="bg-gray-800 rounded p-6 mb-8">
				<h2 class="text-xl font-semibold mb-4">Add Score</h2>
				<form method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
					<input type="hidden" name="action" value="create">
					<select name="student_id" required class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
						<option value="">Select Student</option>
						<?php foreach ($students as $st): ?>
							<option value="<?php echo $st['id']; ?>"><?php echo htmlspecialchars($st['name']); ?></option>
						<?php endforeach; ?>
					</select>
					<input name="subject" required class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" placeholder="Subject">
					<input name="score" type="number" step="0.01" required class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" placeholder="Score">
					<input name="max_score" type="number" step="0.01" value="100" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" placeholder="Max Score">
					<input name="year" type="number" value="<?php echo date('Y'); ?>" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" placeholder="Year">
					<input name="semester" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" placeholder="Semester (e.g., First)">
					<input name="test_type" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" placeholder="Test Type (e.g., Midterm)">
					<div class="md:col-span-2 lg:col-span-3">
						<button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded"><i class="fas fa-plus mr-2"></i>Add</button>
					</div>
				</form>
			</div>

			<div class="bg-gray-800 rounded overflow-hidden">
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="bg-gray-700">
							<tr>
								<th class="px-4 py-3 text-left text-white">Student</th>
								<th class="px-4 py-3 text-left text-white">Class</th>
								<th class="px-4 py-3 text-left text-white">Subject</th>
								<th class="px-4 py-3 text-left text-white">Score</th>
								<th class="px-4 py-3 text-left text-white">Year</th>
								<th class="px-4 py-3 text-left text-white">Semester</th>
								<th class="px-4 py-3 text-left text-white">Type</th>
								<th class="px-4 py-3 text-left text-white">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($scores as $sc): ?>
								<tr class="border-b border-gray-700">
									<td class="px-4 py-3 text-white"><?php echo htmlspecialchars($sc['student_name']); ?></td>
									<td class="px-4 py-3 text-gray-300"><?php echo htmlspecialchars($sc['class_name']); ?></td>
									<td class="px-4 py-3 text-gray-300"><?php echo htmlspecialchars($sc['subject']); ?></td>
									<td class="px-4 py-3"><span class="text-white font-semibold"><?php echo (float)$sc['score']; ?></span> <span class="text-gray-400">/ <?php echo (float)$sc['max_score']; ?></span></td>
									<td class="px-4 py-3 text-gray-300"><?php echo (int)$sc['year']; ?></td>
									<td class="px-4 py-3 text-gray-300"><?php echo htmlspecialchars($sc['semester']); ?></td>
									<td class="px-4 py-3 text-gray-300"><?php echo htmlspecialchars($sc['test_type']); ?></td>
									<td class="px-4 py-3">
										<button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm" onclick='openEdit(<?php echo json_encode($sc, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?>)'><i class="fas fa-edit"></i></button>
										<form method="POST" class="inline" onsubmit="return confirm('Delete this score?');">
											<input type="hidden" name="action" value="delete">
											<input type="hidden" name="id" value="<?php echo $sc['id']; ?>">
											<button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"><i class="fas fa-trash"></i></button>
										</form>
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
						<h3 class="text-2xl font-bold text-white mb-4">Edit Score</h3>
						<form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
							<input type="hidden" name="action" value="update">
							<input type="hidden" name="id" id="editId">
							<select name="student_id" id="editStudentId" required class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
								<?php foreach ($students as $st): ?>
									<option value="<?php echo $st['id']; ?>"><?php echo htmlspecialchars($st['name']); ?></option>
								<?php endforeach; ?>
							</select>
							<input name="subject" id="editSubject" required class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
							<input name="score" id="editScore" type="number" step="0.01" required class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
							<input name="max_score" id="editMaxScore" type="number" step="0.01" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
							<input name="year" id="editYear" type="number" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
							<input name="semester" id="editSemester" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
							<input name="test_type" id="editTestType" class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
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
		function openEdit(sc) {
			document.getElementById('editId').value = sc.id;
			document.getElementById('editStudentId').value = sc.student_id;
			document.getElementById('editSubject').value = sc.subject;
			document.getElementById('editScore').value = sc.score;
			document.getElementById('editMaxScore').value = sc.max_score;
			document.getElementById('editYear').value = sc.year;
			document.getElementById('editSemester').value = sc.semester || '';
			document.getElementById('editTestType').value = sc.test_type || '';
			document.getElementById('editModal').classList.add('active');
			document.body.style.overflow = 'hidden';
		}
	</script>
</body>
</html>



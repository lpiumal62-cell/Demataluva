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

// Handle actions: mark read, mark replied, delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	try {
		if ($action === 'mark_read') {
			$id = (int)($_POST['id'] ?? 0);
			$pdo->prepare('UPDATE contact_messages SET status = "read" WHERE id = ?')->execute([$id]);
			$message = 'Message marked as read.';
		} elseif ($action === 'mark_replied') {
			$id = (int)($_POST['id'] ?? 0);
			$pdo->prepare('UPDATE contact_messages SET status = "replied" WHERE id = ?')->execute([$id]);
			$message = 'Message marked as replied.';
		} elseif ($action === 'delete') {
			$id = (int)($_POST['id'] ?? 0);
			$pdo->prepare('DELETE FROM contact_messages WHERE id = ?')->execute([$id]);
			$message = 'Message deleted.';
		}
	} catch (Throwable $e) {
		$error = 'Action failed: ' . htmlspecialchars($e->getMessage());
	}
}

// Filters/search
$statusFilter = $_GET['status'] ?? '';
$q = trim($_GET['q'] ?? '');

$sql = 'SELECT * FROM contact_messages';
$where = [];
$params = [];
if (in_array($statusFilter, ['new','read','replied'], true)) {
	$where[] = 'status = ?';
	$params[] = $statusFilter;
}
if ($q !== '') {
	$where[] = '(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)';
	$params[] = "%$q%"; $params[] = "%$q%"; $params[] = "%$q%"; $params[] = "%$q%";
}
if ($where) {
	$sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY created_at DESC';

try {
	$stmt = $pdo->prepare($sql);
	$stmt->execute($params);
	$rows = $stmt->fetchAll();
} catch (PDOException $e) {
	$rows = [];
	$error = 'Failed to load messages.';
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Messages - Admin</title>
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
				<a href="messages.php" class="flex items-center px-4 py-2 text-blue-400 bg-blue-900 bg-opacity-20 rounded"><i class="fas fa-envelope mr-3"></i>Messages</a>
			</nav>
		</aside>

		<main class="flex-1 p-8">
			<h1 class="text-3xl font-bold text-white mb-6">Contact Messages</h1>
			<?php if ($message): ?><div class="bg-green-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
			<?php if ($error): ?><div class="bg-red-600 text-white p-4 rounded mb-6"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

			<form method="GET" class="mb-6">
				<div class="grid grid-cols-1 md:grid-cols-3 gap-3">
					<input name="q" value="<?php echo htmlspecialchars($q); ?>" class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700" placeholder="Search (name, email, subject, message)">
					<select name="status" class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700">
						<option value="">All statuses</option>
						<option value="new" <?php echo $statusFilter==='new'?'selected':''; ?>>New</option>
						<option value="read" <?php echo $statusFilter==='read'?'selected':''; ?>>Read</option>
						<option value="replied" <?php echo $statusFilter==='replied'?'selected':''; ?>>Replied</option>
					</select>
					<button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Filter</button>
				</div>
			</form>

			<div class="bg-gray-800 rounded overflow-hidden">
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="bg-gray-700">
							<tr>
								<th class="px-4 py-3 text-left text-white">From</th>
								<th class="px-4 py-3 text-left text-white">Subject</th>
								<th class="px-4 py-3 text-left text-white">Date</th>
								<th class="px-4 py-3 text-left text-white">Status</th>
								<th class="px-4 py-3 text-left text-white">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($rows as $row): ?>
								<tr class="border-b border-gray-700">
									<td class="px-4 py-3 text-white">
										<div class="font-semibold"><?php echo htmlspecialchars($row['name']); ?></div>
										<div class="text-gray-400 text-sm"><?php echo htmlspecialchars($row['email']); ?></div>
									</td>
									<td class="px-4 py-3 text-gray-300"><?php echo htmlspecialchars($row['subject']); ?></td>
									<td class="px-4 py-3 text-gray-300"><?php echo htmlspecialchars($row['created_at']); ?></td>
									<td class="px-4 py-3">
										<span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $row['status']==='new'?'bg-blue-600 text-white':($row['status']==='read'?'bg-yellow-600 text-white':'bg-green-600 text-white'); ?>"><?php echo htmlspecialchars($row['status']); ?></span>
									</td>
									<td class="px-4 py-3">
										<div class="flex items-center space-x-2">
											<button type="button" onclick="openView(<?php echo htmlspecialchars(json_encode($row)); ?>)" class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded text-sm"><i class="fas fa-eye"></i></button>
											<form method="POST">
												<input type="hidden" name="action" value="mark_read">
												<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
												<button class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm"><i class="fas fa-envelope-open"></i></button>
											</form>
											<form method="POST">
												<input type="hidden" name="action" value="mark_replied">
												<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
												<button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"><i class="fas fa-reply"></i></button>
											</form>
											<form method="POST" onsubmit="return confirm('Delete this message?');">
												<input type="hidden" name="action" value="delete">
												<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
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

			<!-- View Modal -->
			<div id="viewModal" class="modal">
				<div class="modal-content max-w-2xl">
					<button class="modal-close absolute top-4 right-4 text-white text-2xl hover:text-blue-400">&times;</button>
					<div class="p-6">
						<h3 class="text-2xl font-bold text-white mb-4" id="viewSubject"></h3>
						<div class="space-y-2 mb-4">
							<div class="text-gray-300"><strong>From:</strong> <span id="viewFrom"></span></div>
							<div class="text-gray-300"><strong>Email:</strong> <span id="viewEmail"></span></div>
							<div class="text-gray-300"><strong>Date:</strong> <span id="viewDate"></span></div>
							<div class="text-gray-300"><strong>Status:</strong> <span id="viewStatus"></span></div>
						</div>
						<div class="bg-gray-800 rounded p-4 text-gray-200" id="viewMessage" style="white-space: pre-wrap;"></div>
					</div>
				</div>
			</div>
		</main>
	</div>

	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script src="../assets/js/main.js"></script>
	<script>
		function openView(row) {
			document.getElementById('viewSubject').textContent = row.subject || '';
			document.getElementById('viewFrom').textContent = row.name || '';
			document.getElementById('viewEmail').textContent = row.email || '';
			document.getElementById('viewDate').textContent = row.created_at || '';
			document.getElementById('viewStatus').textContent = row.status || '';
			document.getElementById('viewMessage').textContent = row.message || '';
			document.getElementById('viewModal').classList.add('active');
			document.body.style.overflow = 'hidden';
		}
	</script>
</body>
</html>



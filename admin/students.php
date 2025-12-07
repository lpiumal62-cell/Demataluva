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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = sanitizeInput($_POST['name'] ?? '');
                $class_id = (int)($_POST['class_id'] ?? 0);
                $year = (int)($_POST['year'] ?? date('Y'));
                $email = sanitizeInput($_POST['email'] ?? '');
                $phone = sanitizeInput($_POST['phone'] ?? '');
                $parent_name = sanitizeInput($_POST['parent_name'] ?? '');
                $parent_phone = sanitizeInput($_POST['parent_phone'] ?? '');
                
                if (empty($name) || $class_id <= 0) {
                    $error = 'Name and class are required.';
                } else {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO students (name, class_id, year, email, phone, parent_name, parent_phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$name, $class_id, $year, $email, $phone, $parent_name, $parent_phone]);
                        $message = 'Student added successfully.';
                    } catch (PDOException $e) {
                        $error = 'Failed to add student.';
                    }
                }
                break;
                
            case 'edit':
                $id = (int)($_POST['id'] ?? 0);
                $name = sanitizeInput($_POST['name'] ?? '');
                $class_id = (int)($_POST['class_id'] ?? 0);
                $year = (int)($_POST['year'] ?? date('Y'));
                $email = sanitizeInput($_POST['email'] ?? '');
                $phone = sanitizeInput($_POST['phone'] ?? '');
                $parent_name = sanitizeInput($_POST['parent_name'] ?? '');
                $parent_phone = sanitizeInput($_POST['parent_phone'] ?? '');
                
                if ($id <= 0 || empty($name) || $class_id <= 0) {
                    $error = 'Invalid data provided.';
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE students SET name = ?, class_id = ?, year = ?, email = ?, phone = ?, parent_name = ?, parent_phone = ? WHERE id = ?");
                        $stmt->execute([$name, $class_id, $year, $email, $phone, $parent_name, $parent_phone, $id]);
                        $message = 'Student updated successfully.';
                    } catch (PDOException $e) {
                        $error = 'Failed to update student.';
                    }
                }
                break;
                
            case 'delete':
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    try {
                        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
                        $stmt->execute([$id]);
                        $message = 'Student deleted successfully.';
                    } catch (PDOException $e) {
                        $error = 'Failed to delete student.';
                    }
                }
                break;
        }
    }
}

// Get students with class information
try {
    $stmt = $pdo->query("
        SELECT s.*, c.name as class_name 
        FROM students s 
        LEFT JOIN classes c ON s.class_id = c.id 
        ORDER BY s.name
    ");
    $students = $stmt->fetchAll();
} catch (PDOException $e) {
    $students = [];
}

// Get classes for dropdown
$classes = getClassesWithTeachers();
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-900 text-gray-100 font-inter">
    <!-- Navigation -->
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
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 min-h-screen">
            <div class="p-4">
                <nav class="space-y-2">
                    <a href="dashboard.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                    </a>
                    <a href="students.php" class="flex items-center px-4 py-2 text-blue-400 bg-blue-900 bg-opacity-20 rounded-lg">
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
                    <a href="gallery.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-images mr-3"></i>Gallery
                    </a>
                    <a href="events.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-calendar-alt mr-3"></i>Events
                    </a>
                    <a href="messages.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-envelope mr-3"></i>Messages
                    </a>
                    <a href="settings.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-cog mr-3"></i>Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Page Header -->
            <div class="mb-8" data-aos="fade-up">
                <h1 class="text-3xl font-bold text-white mb-2">Manage Students</h1>
                <p class="text-gray-400">Add, edit, and manage student information</p>
            </div>

            <!-- Messages -->
            <?php if($message): ?>
            <div class="bg-green-600 text-white p-4 rounded-lg mb-6" data-aos="fade-up">
                <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <?php if($error): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6" data-aos="fade-up">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <!-- Add Student Form -->
            <div class="bg-gray-800 rounded-lg p-6 mb-8" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-xl font-semibold text-white mb-4">Add New Student</h2>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <input type="hidden" name="action" value="add">
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Name *</label>
                        <input type="text" name="name" required class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Class *</label>
                        <select name="class_id" required class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                            <option value="">Select Class</option>
                            <?php foreach($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Year</label>
                        <input type="number" name="year" value="<?php echo date('Y'); ?>" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Email</label>
                        <input type="email" name="email" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Phone</label>
                        <input type="tel" name="phone" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Parent Name</label>
                        <input type="text" name="parent_name" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Parent Phone</label>
                        <input type="tel" name="parent_phone" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div class="md:col-span-2 lg:col-span-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>Add Student
                        </button>
                    </div>
                </form>
            </div>

            <!-- Students Table -->
            <div class="bg-gray-800 rounded-lg overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                <div class="p-6 border-b border-gray-700">
                    <h2 class="text-xl font-semibold text-white">All Students (<?php echo count($students); ?>)</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-white font-semibold">Name</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Class</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Year</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Email</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Parent</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($students as $student): ?>
                            <tr class="border-b border-gray-700 hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 text-white"><?php echo htmlspecialchars($student['name']); ?></td>
                                <td class="px-6 py-4 text-gray-300"><?php echo htmlspecialchars($student['class_name'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-gray-300"><?php echo $student['year']; ?></td>
                                <td class="px-6 py-4 text-gray-300"><?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-gray-300"><?php echo htmlspecialchars($student['parent_name'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button onclick="editStudent(<?php echo htmlspecialchars(json_encode($student)); ?>)" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-all duration-300">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteStudent(<?php echo $student['id']; ?>)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-all duration-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content max-w-2xl">
            <button class="modal-close absolute top-4 right-4 text-white text-2xl hover:text-blue-400">&times;</button>
            <div class="p-6">
                <h3 class="text-2xl font-bold text-white mb-6">Edit Student</h3>
                <form id="editForm" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editId">
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Name *</label>
                        <input type="text" name="name" id="editName" required class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Class *</label>
                        <select name="class_id" id="editClassId" required class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                            <option value="">Select Class</option>
                            <?php foreach($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Year</label>
                        <input type="number" name="year" id="editYear" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Email</label>
                        <input type="email" name="email" id="editEmail" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Phone</label>
                        <input type="tel" name="phone" id="editPhone" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Parent Name</label>
                        <input type="text" name="parent_name" id="editParentName" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Parent Phone</label>
                        <input type="tel" name="parent_phone" id="editParentPhone" class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div class="md:col-span-2">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-300">
                            <i class="fas fa-save mr-2"></i>Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../assets/js/main.js"></script>
    <script>
        function editStudent(student) {
            document.getElementById('editId').value = student.id;
            document.getElementById('editName').value = student.name;
            document.getElementById('editClassId').value = student.class_id;
            document.getElementById('editYear').value = student.year;
            document.getElementById('editEmail').value = student.email || '';
            document.getElementById('editPhone').value = student.phone || '';
            document.getElementById('editParentName').value = student.parent_name || '';
            document.getElementById('editParentPhone').value = student.parent_phone || '';
            
            document.getElementById('editModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function deleteStudent(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>

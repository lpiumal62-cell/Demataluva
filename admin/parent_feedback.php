<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM parent_feedback WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Parent feedback deleted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error deleting feedback: " . $e->getMessage();
    }
}

// Handle edit action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $parent_name = sanitizeInput($_POST['parent_name']);
    $email = sanitizeInput($_POST['email']);
    $student_name = sanitizeInput($_POST['student_name']);
    $grade = sanitizeInput($_POST['grade']);
    $rating = (int)$_POST['rating'];
    $feedback = sanitizeInput($_POST['feedback']);
    $appreciations = sanitizeInput($_POST['appreciations']);
    
    try {
        $stmt = $pdo->prepare("UPDATE parent_feedback SET parent_name = ?, email = ?, student_name = ?, grade = ?, rating = ?, feedback = ?, appreciations = ? WHERE id = ?");
        $stmt->execute([$parent_name, $email, $student_name, $grade, $rating, $feedback, $appreciations, $id]);
        $success_message = "Parent feedback updated successfully!";
    } catch (PDOException $e) {
        $error_message = "Error updating feedback: " . $e->getMessage();
    }
}

// Get all parent feedback
try {
    $stmt = $pdo->query("SELECT * FROM parent_feedback ORDER BY created_at DESC");
    $feedback_list = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error fetching feedback: " . $e->getMessage();
    $feedback_list = [];
}

// Get statistics
$total_feedback = count($feedback_list);
$avg_rating = 0;
if ($total_feedback > 0) {
    $total_rating = array_sum(array_column($feedback_list, 'rating'));
    $avg_rating = round($total_rating / $total_feedback, 1);
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Feedback Management - Admin Panel</title>
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
                        <i class="fas fa-graduation-cap mr-2"></i>DMV Admin
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>
                    <a href="../index.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>View Site
                    </a>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">
                <i class="fas fa-comments mr-3 text-blue-400"></i>Parent Feedback Management
            </h1>
            <p class="text-gray-400">Manage and review parent feedback submissions</p>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($success_message)): ?>
            <div class="bg-green-600 text-white p-4 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-800 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-600 text-white">
                        <i class="fas fa-comments text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Total Feedback</p>
                        <p class="text-2xl font-bold text-white"><?php echo $total_feedback; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-600 text-white">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Average Rating</p>
                        <p class="text-2xl font-bold text-white"><?php echo $avg_rating; ?>/5</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-600 text-white">
                        <i class="fas fa-thumbs-up text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Positive Reviews</p>
                        <p class="text-2xl font-bold text-white"><?php echo count(array_filter($feedback_list, function($f) { return $f['rating'] >= 4; })); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback List -->
        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold text-white">All Parent Feedback</h2>
            </div>
            
            <?php if (empty($feedback_list)): ?>
                <div class="p-8 text-center">
                    <i class="fas fa-comments text-4xl text-gray-500 mb-4"></i>
                    <p class="text-gray-400">No parent feedback submitted yet.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Parent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Feedback</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            <?php foreach ($feedback_list as $feedback): ?>
                            <tr class="hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                            <?php echo strtoupper(substr($feedback['parent_name'], 0, 2)); ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-white"><?php echo htmlspecialchars($feedback['parent_name']); ?></div>
                                            <div class="text-sm text-gray-400"><?php echo htmlspecialchars($feedback['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white"><?php echo htmlspecialchars($feedback['student_name']); ?></div>
                                    <div class="text-sm text-gray-400"><?php echo htmlspecialchars($feedback['grade']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $feedback['rating'] ? 'text-yellow-400' : 'text-gray-400'; ?>"></i>
                                        <?php endfor; ?>
                                        <span class="ml-2 text-sm text-gray-300"><?php echo $feedback['rating']; ?>/5</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300 max-w-xs truncate">
                                        <?php echo htmlspecialchars($feedback['feedback']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    <?php echo date('M j, Y', strtotime($feedback['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="viewFeedback(<?php echo htmlspecialchars(json_encode($feedback)); ?>)" 
                                            class="text-blue-400 hover:text-blue-300 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editFeedback(<?php echo htmlspecialchars(json_encode($feedback)); ?>)" 
                                            class="text-yellow-400 hover:text-yellow-300 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteFeedback(<?php echo $feedback['id']; ?>)" 
                                            class="text-red-400 hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- View Feedback Modal -->
    <div id="viewModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-800 rounded-lg max-w-2xl w-full max-h-96 overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white">Parent Feedback Details</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div id="feedbackDetails" class="space-y-4">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Feedback Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-800 rounded-lg max-w-2xl w-full max-h-96 overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white">Edit Parent Feedback</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-white">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <form id="editForm" method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="editId">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Parent Name</label>
                                <input type="text" name="parent_name" id="editParentName" required 
                                       class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                                <input type="email" name="email" id="editEmail" required 
                                       class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Student Name</label>
                                <input type="text" name="student_name" id="editStudentName" required 
                                       class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Grade</label>
                                <input type="text" name="grade" id="editGrade" required 
                                       class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Rating</label>
                            <select name="rating" id="editRating" required 
                                    class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Feedback</label>
                            <textarea name="feedback" id="editFeedback" rows="4" required 
                                      class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none resize-none"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Appreciations</label>
                            <input type="text" name="appreciations" id="editAppreciations" 
                                   class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none"
                                   placeholder="Teaching Quality, School Facilities, Communication">
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeEditModal()" 
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                Update Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../assets/js/main.js"></script>
    
    <script>
        function viewFeedback(feedback) {
            const modal = document.getElementById('viewModal');
            const details = document.getElementById('feedbackDetails');
            
            details.innerHTML = `
                <div class="bg-gray-700 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            ${feedback.parent_name.substring(0, 2).toUpperCase()}
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-white">${feedback.parent_name}</h4>
                            <p class="text-gray-400">${feedback.email}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-400">Student Name</p>
                            <p class="text-white font-medium">${feedback.student_name}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Grade</p>
                            <p class="text-white font-medium">${feedback.grade}</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 mb-2">Rating</p>
                        <div class="flex items-center">
                            ${Array.from({length: 5}, (_, i) => 
                                `<i class="fas fa-star ${i < feedback.rating ? 'text-yellow-400' : 'text-gray-400'}"></i>`
                            ).join('')}
                            <span class="ml-2 text-white font-medium">${feedback.rating}/5</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 mb-2">Feedback</p>
                        <p class="text-white">${feedback.feedback}</p>
                    </div>
                    
                    ${feedback.appreciations ? `
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 mb-2">Appreciated Aspects</p>
                        <p class="text-white">${feedback.appreciations}</p>
                    </div>
                    ` : ''}
                    
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Submitted</p>
                        <p class="text-white">${new Date(feedback.created_at).toLocaleDateString()}</p>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
        }
        
        function editFeedback(feedback) {
            const modal = document.getElementById('editModal');
            
            document.getElementById('editId').value = feedback.id;
            document.getElementById('editParentName').value = feedback.parent_name;
            document.getElementById('editEmail').value = feedback.email;
            document.getElementById('editStudentName').value = feedback.student_name;
            document.getElementById('editGrade').value = feedback.grade;
            document.getElementById('editRating').value = feedback.rating;
            document.getElementById('editFeedback').value = feedback.feedback;
            document.getElementById('editAppreciations').value = feedback.appreciations || '';
            
            modal.classList.remove('hidden');
        }
        
        function deleteFeedback(id) {
            if (confirm('Are you sure you want to delete this feedback?')) {
                window.location.href = `?action=delete&id=${id}`;
            }
        }
        
        function closeModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }
        
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        
        // Close modals when clicking outside
        document.getElementById('viewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>

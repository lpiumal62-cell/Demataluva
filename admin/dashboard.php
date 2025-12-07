<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get statistics for dashboard
$stats = getStatistics();

// Get recent activities
try {
    $recentStudents = $pdo->query("SELECT * FROM students ORDER BY created_at DESC LIMIT 5")->fetchAll();
    $recentMessages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
    $recentEvents = $pdo->query("SELECT * FROM events ORDER BY date DESC LIMIT 5")->fetchAll();
    $recentFeedback = $pdo->query("SELECT * FROM parent_feedback ORDER BY created_at DESC LIMIT 5")->fetchAll();
    
    // Get parent feedback statistics
    $feedbackStats = $pdo->query("SELECT COUNT(*) as total, AVG(rating) as avg_rating FROM parent_feedback")->fetch();
    $positiveFeedback = $pdo->query("SELECT COUNT(*) as count FROM parent_feedback WHERE rating >= 4")->fetch();
} catch (PDOException $e) {
    $recentStudents = [];
    $recentMessages = [];
    $recentEvents = [];
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bright Future Academy</title>
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

    <!-- Sidebar -->
    <div class="flex">
        <div class="w-64 bg-gray-800 min-h-screen">
            <div class="p-4">
                <nav class="space-y-2">
                    <a href="dashboard.php" class="flex items-center px-4 py-2 text-blue-400 bg-blue-900 bg-opacity-20 rounded-lg">
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
                    <a href="gallery.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-images mr-3"></i>Gallery
                    </a>
                    <a href="events.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-calendar-alt mr-3"></i>Events
                    </a>
                    <a href="messages.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-envelope mr-3"></i>Messages
                    </a>
                    <a href="parent_feedback.php" class="flex items-center px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-comments mr-3"></i>Parent Feedback
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
                <h1 class="text-3xl font-bold text-white mb-2">Dashboard</h1>
                <p class="text-gray-400">Welcome to the Bright Future Academy admin panel</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-200 text-sm font-medium">Total Students</p>
                            <p class="text-3xl font-bold text-white counter" data-target="<?php echo $stats['total_students']; ?>">0</p>
                        </div>
                        <div class="bg-blue-500 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-200 text-sm font-medium">Total Teachers</p>
                            <p class="text-3xl font-bold text-white counter" data-target="<?php echo $stats['total_teachers']; ?>">0</p>
                        </div>
                        <div class="bg-green-500 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-200 text-sm font-medium">Total Classes</p>
                            <p class="text-3xl font-bold text-white counter" data-target="<?php echo $stats['total_classes']; ?>">0</p>
                        </div>
                        <div class="bg-purple-500 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-book text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-200 text-sm font-medium">Total Events</p>
                            <p class="text-3xl font-bold text-white counter" data-target="<?php echo $stats['total_events']; ?>">0</p>
                        </div>
                        <div class="bg-yellow-500 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-200 text-sm font-medium">Parent Feedback</p>
                            <p class="text-3xl font-bold text-white"><?php echo $feedbackStats['total'] ?? 0; ?></p>
                            <p class="text-indigo-200 text-xs">Avg Rating: <?php echo round($feedbackStats['avg_rating'] ?? 0, 1); ?>/5</p>
                        </div>
                        <div class="bg-indigo-500 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-comments text-white text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-pink-600 to-pink-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-pink-200 text-sm font-medium">Positive Reviews</p>
                            <p class="text-3xl font-bold text-white"><?php echo $positiveFeedback['count'] ?? 0; ?></p>
                            <p class="text-pink-200 text-xs">4+ Star Ratings</p>
                        </div>
                        <div class="bg-pink-500 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-thumbs-up text-white text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-teal-200 text-sm font-medium">Contact Messages</p>
                            <p class="text-3xl font-bold text-white"><?php echo count($recentMessages); ?></p>
                            <p class="text-teal-200 text-xs">Recent Messages</p>
                        </div>
                        <div class="bg-teal-500 bg-opacity-30 rounded-full p-3">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Recent Students -->
                <div class="bg-gray-800 rounded-lg p-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Recent Students</h3>
                        <a href="students.php" class="text-blue-400 hover:text-blue-300 text-sm">View All</a>
                    </div>
                    <div class="space-y-3">
                        <?php foreach($recentStudents as $student): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-white font-medium"><?php echo htmlspecialchars($student['name']); ?></p>
                                <p class="text-gray-400 text-sm">Class <?php echo $student['class_id']; ?></p>
                            </div>
                            <span class="text-gray-400 text-xs"><?php echo date('M j', strtotime($student['created_at'])); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="bg-gray-800 rounded-lg p-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Recent Messages</h3>
                        <a href="messages.php" class="text-blue-400 hover:text-blue-300 text-sm">View All</a>
                    </div>
                    <div class="space-y-3">
                        <?php foreach($recentMessages as $message): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-white font-medium"><?php echo htmlspecialchars($message['name']); ?></p>
                                <p class="text-gray-400 text-sm"><?php echo htmlspecialchars(substr($message['subject'], 0, 30)) . '...'; ?></p>
                            </div>
                            <span class="text-gray-400 text-xs"><?php echo date('M j', strtotime($message['created_at'])); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Recent Events -->
                <div class="bg-gray-800 rounded-lg p-6" data-aos="fade-up" data-aos-delay="700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Upcoming Events</h3>
                        <a href="events.php" class="text-blue-400 hover:text-blue-300 text-sm">View All</a>
                    </div>
                    <div class="space-y-3">
                        <?php foreach($recentEvents as $event): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-white font-medium"><?php echo htmlspecialchars($event['title']); ?></p>
                                <p class="text-gray-400 text-sm"><?php echo date('M j, Y', strtotime($event['date'])); ?></p>
                            </div>
                            <span class="text-gray-400 text-xs"><?php echo date('M j', strtotime($event['date'])); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Recent Parent Feedback -->
                <div class="bg-gray-800 rounded-lg p-6" data-aos="fade-up" data-aos-delay="800">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Recent Feedback</h3>
                        <a href="parent_feedback.php" class="text-blue-400 hover:text-blue-300 text-sm">View All</a>
                    </div>
                    <div class="space-y-3">
                        <?php foreach($recentFeedback as $feedback): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-white font-medium"><?php echo htmlspecialchars($feedback['parent_name']); ?></p>
                                <div class="flex items-center">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $feedback['rating'] ? 'text-yellow-400' : 'text-gray-400'; ?> text-xs"></i>
                                    <?php endfor; ?>
                                    <span class="text-gray-400 text-xs ml-1"><?php echo $feedback['rating']; ?>/5</span>
                                </div>
                            </div>
                            <span class="text-gray-400 text-xs"><?php echo date('M j', strtotime($feedback['created_at'])); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8" data-aos="fade-up" data-aos-delay="800">
                <h3 class="text-xl font-semibold text-white mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="students.php?action=add" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-user-plus text-2xl mb-2"></i>
                        <p class="font-semibold">Add Student</p>
                    </a>
                    <a href="teachers.php?action=add" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-chalkboard-teacher text-2xl mb-2"></i>
                        <p class="font-semibold">Add Teacher</p>
                    </a>
                    <a href="events.php?action=add" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg text-center transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-calendar-plus text-2xl mb-2"></i>
                        <p class="font-semibold">Add Event</p>
                    </a>
                    <a href="gallery.php?action=add" class="bg-yellow-600 hover:bg-yellow-700 text-white p-4 rounded-lg text-center transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-image text-2xl mb-2"></i>
                        <p class="font-semibold">Add Photo</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>

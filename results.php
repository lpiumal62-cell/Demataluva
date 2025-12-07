<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : null;
$classes = getClassesWithTeachers();
$students = $class_id ? getStudentsByClass($class_id) : [];
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results - Demataluva Maha Viddiyalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-gray-100 font-inter">
    <!-- Navigation -->
    <?php include 'includes/header.php'; ?>
    <!-- Hero Section -->
    <section class="pt-16 pb-20 bg-gradient-to-r from-green-900 to-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-green-400 to-blue-400 bg-clip-text text-transparent" data-aos="fade-up">
                Student Results
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Track academic progress and achievements
            </p>
        </div>
    </section>

    <!-- Class Selection -->
    <section class="py-12 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-white mb-4">Select Class</h2>
                <p class="text-gray-400">Choose a class to view student results</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($classes as $index => $class): ?>
                <a href="results.php?class_id=<?php echo $class['id']; ?>" class="group" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="bg-gray-700 rounded-lg p-6 hover:bg-gray-600 transition-all duration-300 transform group-hover:scale-105 <?php echo $class_id == $class['id'] ? 'ring-2 ring-blue-500' : ''; ?>">
                        <div class="flex items-center mb-4">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-3 mr-4">
                                <i class="fas fa-book text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($class['name']); ?></h3>
                                <p class="text-gray-400"><?php echo htmlspecialchars($class['teacher_name']); ?></p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Students: <?php echo $class['number_of_students']; ?></span>
                            <span class="text-blue-400">Year: <?php echo $class['year']; ?></span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if($class_id && !empty($students)): ?>
    <!-- Student Results -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Student Results</h2>
                <p class="text-xl text-gray-400">Academic performance for selected class</p>
            </div>
            
            <!-- Search and Filter -->
            <div class="mb-8" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" id="searchInput" placeholder="Search students..." class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div class="flex gap-2">
                            <select id="yearFilter" class="bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                                <option value="">All Years</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                            </select>
                            <button onclick="exportResults()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-300">
                                <i class="fas fa-download mr-2"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Results Table -->
            <div class="bg-gray-800 rounded-lg overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-white font-semibold">Student Name</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Class</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Year</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Average Score</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Grade</th>
                                <th class="px-6 py-4 text-left text-white font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody">
                            <?php foreach($students as $index => $student): ?>
                            <?php 
                            $testScores = getTestScoresByStudent($student['id']);
                            $averageScore = 0;
                            if(!empty($testScores)) {
                                $totalScore = array_sum(array_column($testScores, 'score'));
                                $averageScore = $totalScore / count($testScores);
                            }
                            $grade = $averageScore >= 90 ? 'A' : ($averageScore >= 80 ? 'B' : ($averageScore >= 70 ? 'C' : ($averageScore >= 60 ? 'D' : 'F')));
                            ?>
                            <tr class="border-b border-gray-700 hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 text-white"><?php echo htmlspecialchars($student['name']); ?></td>
                                <td class="px-6 py-4 text-gray-300"><?php echo htmlspecialchars($student['class_id']); ?></td>
                                <td class="px-6 py-4 text-gray-300"><?php echo $student['year']; ?></td>
                                <td class="px-6 py-4">
                                    <span class="text-lg font-semibold <?php echo $averageScore >= 80 ? 'text-green-400' : ($averageScore >= 60 ? 'text-yellow-400' : 'text-red-400'); ?>">
                                        <?php echo number_format($averageScore, 1); ?>%
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo $grade == 'A' ? 'bg-green-600 text-white' : ($grade == 'B' ? 'bg-blue-600 text-white' : ($grade == 'C' ? 'bg-yellow-600 text-white' : ($grade == 'D' ? 'bg-orange-600 text-white' : 'bg-red-600 text-white'))); ?>">
                                        <?php echo $grade; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="showStudentDetails(<?php echo htmlspecialchars(json_encode($student)); ?>, <?php echo htmlspecialchars(json_encode($testScores)); ?>)" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm transition-all duration-300">
                                        <i class="fas fa-eye mr-1"></i>View Details
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <?php elseif($class_id && empty($students)): ?>
    <!-- No Students Message -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-gray-800 rounded-lg p-12" data-aos="fade-up">
                <i class="fas fa-users text-6xl text-gray-400 mb-6"></i>
                <h2 class="text-3xl font-bold text-white mb-4">No Students Found</h2>
                <p class="text-xl text-gray-400 mb-8">This class doesn't have any students enrolled yet.</p>
                <a href="classes.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    Back to Classes
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Performance Statistics -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Performance Overview</h2>
                <p class="text-xl text-gray-400">Academic achievements and statistics</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-trophy text-4xl text-white mb-4"></i>
                        <div class="text-3xl font-bold text-white mb-2">95%</div>
                        <div class="text-green-200">Pass Rate</div>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-star text-4xl text-white mb-4"></i>
                        <div class="text-3xl font-bold text-white mb-2">87%</div>
                        <div class="text-blue-200">Average Score</div>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-medal text-4xl text-white mb-4"></i>
                        <div class="text-3xl font-bold text-white mb-2">78%</div>
                        <div class="text-purple-200">Honor Roll</div>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-graduation-cap text-4xl text-white mb-4"></i>
                        <div class="text-3xl font-bold text-white mb-2">92%</div>
                        <div class="text-yellow-200">Graduation Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Student Details Modal -->
    <div id="studentModal" class="modal">
        <div class="modal-content max-w-4xl">
            <button class="modal-close absolute top-4 right-4 text-white text-2xl hover:text-blue-400">&times;</button>
            <div class="p-6">
                <h3 id="modalStudentName" class="text-2xl font-bold text-white mb-6"></h3>
                <div id="modalStudentDetails" class="space-y-6"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-blue-400 mb-4">
                        <i class="fas fa-graduation-cap mr-2"></i>Bright Future Academy
                    </h3>
                    <p class="text-gray-400 mb-4">Empowering minds and building futures through quality education.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="about.php" class="text-gray-400 hover:text-blue-400">About Us</a></li>
                        <li><a href="classes.php" class="text-gray-400 hover:text-blue-400">Classes</a></li>
                        <li><a href="teachers.php" class="text-gray-400 hover:text-blue-400">Teachers</a></li>
                        <li><a href="results.php" class="text-gray-400 hover:text-blue-400">Results</a></li>
                        <li><a href="gallery.php" class="text-gray-400 hover:text-blue-400">Gallery</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Contact Info</h4>
                    <div class="space-y-2 text-gray-400">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>123 Education Street, Learning City</p>
                        <p><i class="fas fa-phone mr-2"></i>+1 (555) 123-4567</p>
                        <p><i class="fas fa-envelope mr-2"></i>info@brightfutureacademy.com</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Demataluva Maha Viddiyalaya. All rights reserved. Developer: BlackEagle</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#resultsTableBody tr');
            
            rows.forEach(row => {
                const studentName = row.cells[0].textContent.toLowerCase();
                if (studentName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Year filter
        document.getElementById('yearFilter').addEventListener('change', function() {
            const selectedYear = this.value;
            const rows = document.querySelectorAll('#resultsTableBody tr');
            
            rows.forEach(row => {
                const year = row.cells[2].textContent.trim();
                if (selectedYear === '' || year === selectedYear) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function showStudentDetails(studentData, testScores) {
            const modal = document.getElementById('studentModal');
            const studentName = document.getElementById('modalStudentName');
            const studentDetails = document.getElementById('modalStudentDetails');
            
            studentName.textContent = studentData.name;
            
            let scoresHtml = '';
            if (testScores.length > 0) {
                scoresHtml = `
                    <div class="bg-gray-700 rounded-lg p-6">
                        <h4 class="text-xl font-semibold text-white mb-4">Test Scores</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-600">
                                        <th class="text-left text-white py-2">Subject</th>
                                        <th class="text-left text-white py-2">Score</th>
                                        <th class="text-left text-white py-2">Max Score</th>
                                        <th class="text-left text-white py-2">Year</th>
                                        <th class="text-left text-white py-2">Semester</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${testScores.map(score => `
                                        <tr class="border-b border-gray-600">
                                            <td class="text-gray-300 py-2">${score.subject}</td>
                                            <td class="text-white py-2 font-semibold">${score.score}</td>
                                            <td class="text-gray-300 py-2">${score.max_score}</td>
                                            <td class="text-gray-300 py-2">${score.year}</td>
                                            <td class="text-gray-300 py-2">${score.semester || 'N/A'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            } else {
                scoresHtml = `
                    <div class="bg-gray-700 rounded-lg p-6 text-center">
                        <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-300">No test scores available for this student.</p>
                    </div>
                `;
            }
            
            studentDetails.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-white mb-3">Student Information</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Name:</span>
                                <span class="text-white">${studentData.name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Class:</span>
                                <span class="text-blue-400">${studentData.class_id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Year:</span>
                                <span class="text-green-400">${studentData.year}</span>
                            </div>
                            ${studentData.email ? `
                            <div class="flex justify-between">
                                <span class="text-gray-400">Email:</span>
                                <span class="text-purple-400">${studentData.email}</span>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-white mb-3">Academic Summary</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Total Tests:</span>
                                <span class="text-white">${testScores.length}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Average Score:</span>
                                <span class="text-yellow-400 font-semibold">
                                    ${testScores.length > 0 ? (testScores.reduce((sum, score) => sum + parseFloat(score.score), 0) / testScores.length).toFixed(1) : 'N/A'}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                ${scoresHtml}
            `;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function exportResults() {
            // Simple CSV export functionality
            const table = document.querySelector('table');
            const rows = Array.from(table.querySelectorAll('tr'));
            const csvContent = rows.map(row => 
                Array.from(row.querySelectorAll('th, td')).map(cell => 
                    `"${cell.textContent.trim()}"`
                ).join(',')
            ).join('\n');
            
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'student_results.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>

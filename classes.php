<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$classes = getClassesWithTeachers();
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes & Courses - Demataluva Maha Viddiyalaya</title>
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
                Classes & Courses
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Comprehensive curriculum designed to nurture every student's potential
            </p>
        </div>
    </section>

    <!-- Classes Overview -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Our Classes</h2>
                <p class="text-xl text-gray-400">Explore our diverse range of educational programs</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach($classes as $index => $class): ?>
                <div class="bg-gray-700 rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-book text-6xl text-white"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-semibold text-white mb-2"><?php echo htmlspecialchars($class['name']); ?></h3>
                        <p class="text-gray-300 mb-4"><?php echo htmlspecialchars($class['description']); ?></p>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Teacher:</span>
                                <span class="text-blue-400 font-medium">
                                    <?php echo $class['teacher_name'] ? htmlspecialchars($class['teacher_name']) : 'TBA'; ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Subject:</span>
                                <span class="text-green-400 font-medium">
                                    <?php echo $class['subject'] ? htmlspecialchars($class['subject']) : 'Multiple'; ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Students:</span>
                                <span class="text-yellow-400 font-medium"><?php echo $class['number_of_students']; ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Year:</span>
                                <span class="text-purple-400 font-medium"><?php echo $class['year']; ?></span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="results.php?class_id=<?php echo $class['id']; ?>" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-chart-line mr-2"></i>View Results
                            </a>
                            <button onclick="showClassDetails(<?php echo htmlspecialchars(json_encode($class)); ?>)" class="bg-gray-600 hover:bg-gray-500 text-white py-2 px-4 rounded-lg transition-all duration-300">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Curriculum Highlights -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Curriculum Highlights</h2>
                <p class="text-xl text-gray-400">What makes our education special</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-brain text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Critical Thinking</h3>
                        <p class="text-blue-200">Developing analytical and problem-solving skills</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-flask text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">STEM Education</h3>
                        <p class="text-green-200">Science, Technology, Engineering, and Mathematics</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-palette text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Arts & Creativity</h3>
                        <p class="text-purple-200">Fostering creativity and artistic expression</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-globe text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Global Perspective</h3>
                        <p class="text-yellow-200">Understanding diverse cultures and global issues</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Academic Calendar -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Academic Calendar</h2>
                <p class="text-xl text-gray-400">Important dates and milestones</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-600 rounded-full p-3 mr-4">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">First Semester</h3>
                            <p class="text-gray-400">September - December</p>
                        </div>
                    </div>
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Orientation Week</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Mid-term Exams</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Parent-Teacher Conferences</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Winter Break</li>
                    </ul>
                </div>
                
                <div class="bg-gray-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-600 rounded-full p-3 mr-4">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Second Semester</h3>
                            <p class="text-gray-400">January - May</p>
                        </div>
                    </div>
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Spring Activities</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Science Fair</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Final Exams</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Graduation Ceremony</li>
                    </ul>
                </div>
                
                <div class="bg-gray-700 rounded-lg p-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-600 rounded-full p-3 mr-4">
                            <i class="fas fa-sun text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Summer Program</h3>
                            <p class="text-gray-400">June - August</p>
                        </div>
                    </div>
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Summer Camps</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Enrichment Courses</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Sports Activities</li>
                        <li><i class="fas fa-check text-green-400 mr-2"></i>Field Trips</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-green-900 to-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6" data-aos="fade-up">
                Ready to Enroll?
            </h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Join our community of learners and discover your potential with us.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                <a href="contact.php" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    Contact Us
                </a>
                <a href="results.php" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-green-900 px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    View Results
                </a>
            </div>
        </div>
    </section>

    <!-- Class Details Modal -->
    <div id="classModal" class="modal">
        <div class="modal-content max-w-2xl">
            <button class="modal-close absolute top-4 right-4 text-white text-2xl hover:text-blue-400">&times;</button>
            <div class="p-6">
                <h3 id="modalClassName" class="text-2xl font-bold text-white mb-4"></h3>
                <div id="modalClassDetails" class="space-y-4 text-gray-300"></div>
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
        function showClassDetails(classData) {
            const modal = document.getElementById('classModal');
            const className = document.getElementById('modalClassName');
            const classDetails = document.getElementById('modalClassDetails');
            
            className.textContent = classData.name;
            classDetails.innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-400">Description:</span>
                        <p class="text-white">${classData.description || 'No description available'}</p>
                    </div>
                    <div>
                        <span class="text-gray-400">Teacher:</span>
                        <p class="text-blue-400">${classData.teacher_name || 'TBA'}</p>
                    </div>
                    <div>
                        <span class="text-gray-400">Subject:</span>
                        <p class="text-green-400">${classData.subject || 'Multiple'}</p>
                    </div>
                    <div>
                        <span class="text-gray-400">Students:</span>
                        <p class="text-yellow-400">${classData.number_of_students}</p>
                    </div>
                    <div>
                        <span class="text-gray-400">Year:</span>
                        <p class="text-purple-400">${classData.year}</p>
                    </div>
                    <div>
                        <span class="text-gray-400">Created:</span>
                        <p class="text-gray-300">${new Date(classData.created_at).toLocaleDateString()}</p>
                    </div>
                </div>
            `;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    </script>
</body>
</html>

<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$teachers = getTeachers();
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Teachers - Demataluva Maha Viddiyalaya</title>
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
    <section class="pt-16 pb-20 bg-gradient-to-r from-purple-900 to-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent" data-aos="fade-up">
                Our Teachers
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Meet our dedicated educators who inspire and guide our students
            </p>
        </div>
    </section>

    <!-- Teachers Grid -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Faculty Members</h2>
                <p class="text-xl text-gray-400">Experienced educators committed to student success</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach($teachers as $index => $teacher): ?>
                <div class="bg-gray-700 rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center relative overflow-hidden">
                        <?php if($teacher['image']): ?>
                            <img src="<?php echo htmlspecialchars($teacher['image']); ?>" alt="<?php echo htmlspecialchars($teacher['name']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user text-6xl text-white"></i>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-3">
                                <h3 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($teacher['name']); ?></h3>
                                <p class="text-blue-200"><?php echo htmlspecialchars($teacher['subject']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-300 text-sm mb-4"><?php echo htmlspecialchars($teacher['bio']); ?></p>
                        
                        <div class="space-y-2 mb-6">
                            <?php if($teacher['email']): ?>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 mr-3"></i>
                                <a href="mailto:<?php echo htmlspecialchars($teacher['email']); ?>" class="text-blue-400 hover:text-blue-300">
                                    <?php echo htmlspecialchars($teacher['email']); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php if($teacher['phone']): ?>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 mr-3"></i>
                                <a href="tel:<?php echo htmlspecialchars($teacher['phone']); ?>" class="text-green-400 hover:text-green-300">
                                    <?php echo htmlspecialchars($teacher['phone']); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="showTeacherDetails(<?php echo htmlspecialchars(json_encode($teacher)); ?>)" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-info-circle mr-2"></i>View Details
                            </button>
                            <?php if($teacher['email']): ?>
                            <a href="mailto:<?php echo htmlspecialchars($teacher['email']); ?>" class="bg-gray-600 hover:bg-gray-500 text-white py-2 px-4 rounded-lg transition-all duration-300">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Teaching Philosophy -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Teaching Philosophy</h2>
                <p class="text-xl text-gray-400">Our approach to education</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-heart text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Student-Centered</h3>
                        <p class="text-blue-200">Every student is unique and deserves personalized attention</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-lightbulb text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Innovative Methods</h3>
                        <p class="text-green-200">Using modern teaching techniques and technology</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-users text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Collaborative Learning</h3>
                        <p class="text-purple-200">Encouraging teamwork and peer learning</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-star text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Excellence</h3>
                        <p class="text-yellow-200">Striving for the highest standards in education</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Teacher Qualifications -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Teacher Qualifications</h2>
                <p class="text-xl text-gray-400">Our commitment to quality education</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <h3 class="text-2xl font-bold text-white mb-6">Professional Standards</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-400 mr-3 mt-1"></i>
                            <div>
                                <h4 class="text-white font-semibold">Advanced Degrees</h4>
                                <p class="text-gray-300">All teachers hold master's degrees or higher in their subject areas</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-400 mr-3 mt-1"></i>
                            <div>
                                <h4 class="text-white font-semibold">Teaching Certification</h4>
                                <p class="text-gray-300">State-certified educators with ongoing professional development</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-400 mr-3 mt-1"></i>
                            <div>
                                <h4 class="text-white font-semibold">Experience</h4>
                                <p class="text-gray-300">Minimum 5 years of teaching experience in their field</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-400 mr-3 mt-1"></i>
                            <div>
                                <h4 class="text-white font-semibold">Continuous Learning</h4>
                                <p class="text-gray-300">Regular training and workshops to stay updated with best practices</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-left">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-8">
                        <h3 class="text-2xl font-bold text-white mb-6">Teacher Statistics</h3>
                        <div class="space-y-6">
                            <div class="flex justify-between items-center">
                                <span class="text-white">Average Experience</span>
                                <span class="text-2xl font-bold text-yellow-400">12 years</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white">Advanced Degrees</span>
                                <span class="text-2xl font-bold text-green-400">95%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white">Student Satisfaction</span>
                                <span class="text-2xl font-bold text-blue-400">98%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white">Professional Development</span>
                                <span class="text-2xl font-bold text-purple-400">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-purple-900 to-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6" data-aos="fade-up">
                Join Our Teaching Team
            </h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Are you passionate about education? We're always looking for dedicated teachers to join our team.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                <a href="contact.php" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    Apply Now
                </a>
                <a href="about.php" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-purple-900 px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Teacher Details Modal -->
    <div id="teacherModal" class="modal">
        <div class="modal-content max-w-2xl">
            <button class="modal-close absolute top-4 right-4 text-white text-2xl hover:text-blue-400">&times;</button>
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-6">
                        <i class="fas fa-user text-3xl text-white"></i>
                    </div>
                    <div>
                        <h3 id="modalTeacherName" class="text-2xl font-bold text-white"></h3>
                        <p id="modalTeacherSubject" class="text-blue-400 text-lg"></p>
                    </div>
                </div>
                <div id="modalTeacherDetails" class="space-y-4 text-gray-300"></div>
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
        function showTeacherDetails(teacherData) {
            const modal = document.getElementById('teacherModal');
            const teacherName = document.getElementById('modalTeacherName');
            const teacherSubject = document.getElementById('modalTeacherSubject');
            const teacherDetails = document.getElementById('modalTeacherDetails');
            
            teacherName.textContent = teacherData.name;
            teacherSubject.textContent = teacherData.subject;
            teacherDetails.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="text-white font-semibold mb-2">Biography</h4>
                        <p class="text-gray-300">${teacherData.bio || 'No biography available'}</p>
                    </div>
                    ${teacherData.email ? `
                    <div>
                        <h4 class="text-white font-semibold mb-2">Email</h4>
                        <a href="mailto:${teacherData.email}" class="text-blue-400 hover:text-blue-300">${teacherData.email}</a>
                    </div>
                    ` : ''}
                    ${teacherData.phone ? `
                    <div>
                        <h4 class="text-white font-semibold mb-2">Phone</h4>
                        <a href="tel:${teacherData.phone}" class="text-green-400 hover:text-green-300">${teacherData.phone}</a>
                    </div>
                    ` : ''}
                    <div>
                        <h4 class="text-white font-semibold mb-2">Subject</h4>
                        <p class="text-purple-400">${teacherData.subject}</p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-2">Joined</h4>
                        <p class="text-gray-300">${new Date(teacherData.created_at).toLocaleDateString()}</p>
                    </div>
                </div>
            `;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    </script>
</body>
</html>

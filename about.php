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
    <title>About Us - Demataluva Maha Viddiyalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-gray-100 font-inter">
<?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="pt-16 pb-20 bg-gradient-to-r from-blue-900 to-purple-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent" data-aos="fade-up">
                About Demataluva Maha Viddiyalaya
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Empowering minds and building futures through quality education
            </p>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <h2 class="text-4xl font-bold text-white mb-6">Our Mission</h2>
                    <p class="text-lg text-gray-300 mb-6">
                        To provide a nurturing and innovative learning environment that empowers every student to reach their full potential. We are committed to fostering academic excellence, character development, and lifelong learning skills that prepare our students for success in an ever-changing world.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-3"></i>
                            <span class="text-gray-300">Academic Excellence</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-3"></i>
                            <span class="text-gray-300">Character Development</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-3"></i>
                            <span class="text-gray-300">Innovation in Education</span>
                        </div>
                    </div>
                </div>
                <div data-aos="fade-left">
                    <h2 class="text-4xl font-bold text-white mb-6">Our Vision</h2>
                    <p class="text-lg text-gray-300 mb-6">
                        To be a leading educational institution that inspires and equips students to become confident, compassionate, and capable leaders of tomorrow. We envision a school where every child discovers their unique talents and develops the skills necessary to make a positive impact on society.
                    </p>
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-white mb-3">Core Values</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <i class="fas fa-heart text-red-400 text-2xl mb-2"></i>
                                <div class="text-white font-medium">Integrity</div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-lightbulb text-yellow-400 text-2xl mb-2"></i>
                                <div class="text-white font-medium">Innovation</div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-users text-blue-400 text-2xl mb-2"></i>
                                <div class="text-white font-medium">Community</div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-star text-purple-400 text-2xl mb-2"></i>
                                <div class="text-white font-medium">Excellence</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- School History Timeline -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Our Journey</h2>
                <p class="text-xl text-gray-400">A timeline of our growth and achievements</p>
            </div>
            
            <div class="relative">
                <!-- Timeline line -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-blue-500 to-purple-500"></div>
                
                <!-- Timeline items -->
                <div class="space-y-12">
                    <div class="flex items-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-1/2 pr-8 text-right">
                            <div class="bg-gray-800 rounded-lg p-6">
                                <h3 class="text-xl font-semibold text-white mb-2">2015 - Foundation</h3>
                                <p class="text-gray-300">Bright Future Academy was established with a vision to provide quality education to the community.</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 bg-blue-500 rounded-full border-4 border-gray-900 flex items-center justify-center">
                            <i class="fas fa-school text-white text-sm"></i>
                        </div>
                        <div class="w-1/2 pl-8"></div>
                    </div>
                    
                    <div class="flex items-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-1/2 pr-8"></div>
                        <div class="w-8 h-8 bg-green-500 rounded-full border-4 border-gray-900 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-sm"></i>
                        </div>
                        <div class="w-1/2 pl-8">
                            <div class="bg-gray-800 rounded-lg p-6">
                                <h3 class="text-xl font-semibold text-white mb-2">2018 - First Graduation</h3>
                                <p class="text-gray-300">Our first batch of students graduated with outstanding academic achievements.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-1/2 pr-8 text-right">
                            <div class="bg-gray-800 rounded-lg p-6">
                                <h3 class="text-xl font-semibold text-white mb-2">2020 - Digital Transformation</h3>
                                <p class="text-gray-300">We embraced technology and implemented digital learning solutions during the pandemic.</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 bg-purple-500 rounded-full border-4 border-gray-900 flex items-center justify-center">
                            <i class="fas fa-laptop text-white text-sm"></i>
                        </div>
                        <div class="w-1/2 pl-8"></div>
                    </div>
                    
                    <div class="flex items-center" data-aos="fade-up" data-aos-delay="400">
                        <div class="w-1/2 pr-8"></div>
                        <div class="w-8 h-8 bg-yellow-500 rounded-full border-4 border-gray-900 flex items-center justify-center">
                            <i class="fas fa-trophy text-white text-sm"></i>
                        </div>
                        <div class="w-1/2 pl-8">
                            <div class="bg-gray-800 rounded-lg p-6">
                                <h3 class="text-xl font-semibold text-white mb-2">2023 - Excellence Award</h3>
                                <p class="text-gray-300">Received the "Excellence in Education" award from the State Education Board.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center" data-aos="fade-up" data-aos-delay="500">
                        <div class="w-1/2 pr-8 text-right">
                            <div class="bg-gray-800 rounded-lg p-6">
                                <h3 class="text-xl font-semibold text-white mb-2">2024 - Future Ready</h3>
                                <p class="text-gray-300">Continuing to innovate and prepare students for the challenges of tomorrow.</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 bg-red-500 rounded-full border-4 border-gray-900 flex items-center justify-center">
                            <i class="fas fa-rocket text-white text-sm"></i>
                        </div>
                        <div class="w-1/2 pl-8"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Teachers Section -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Meet Our Teachers</h2>
                <p class="text-xl text-gray-400">Dedicated educators committed to student success</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach($teachers as $index => $teacher): ?>
                <div class="bg-gray-700 rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <?php if($teacher['image']): ?>
                            <img src="<?php echo htmlspecialchars($teacher['image']); ?>" alt="<?php echo htmlspecialchars($teacher['name']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user text-6xl text-white"></i>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-2"><?php echo htmlspecialchars($teacher['name']); ?></h3>
                        <p class="text-blue-400 mb-3"><?php echo htmlspecialchars($teacher['subject']); ?></p>
                        <p class="text-gray-300 text-sm mb-4"><?php echo htmlspecialchars($teacher['bio']); ?></p>
                        <div class="flex space-x-3">
                            <?php if($teacher['email']): ?>
                            <a href="mailto:<?php echo htmlspecialchars($teacher['email']); ?>" class="text-gray-400 hover:text-blue-400">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <?php endif; ?>
                            <?php if($teacher['phone']): ?>
                            <a href="tel:<?php echo htmlspecialchars($teacher['phone']); ?>" class="text-gray-400 hover:text-green-400">
                                <i class="fas fa-phone"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Awards & Certifications -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Awards & Certifications</h2>
                <p class="text-xl text-gray-400">Recognition for our commitment to excellence</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-trophy text-4xl text-white mb-4"></i>
                        <h3 class="text-lg font-semibold text-white">Excellence Award</h3>
                        <p class="text-yellow-200 text-sm">State Education Board 2023</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-medal text-4xl text-white mb-4"></i>
                        <h3 class="text-lg font-semibold text-white">Innovation Award</h3>
                        <p class="text-blue-200 text-sm">Digital Learning 2022</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-certificate text-4xl text-white mb-4"></i>
                        <h3 class="text-lg font-semibold text-white">Quality Certification</h3>
                        <p class="text-green-200 text-sm">ISO 9001:2015</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-star text-4xl text-white mb-4"></i>
                        <h3 class="text-lg font-semibold text-white">Best School</h3>
                        <p class="text-purple-200 text-sm">Community Choice 2021</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-blue-900 to-purple-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6" data-aos="fade-up">
                Ready to Join Our Community?
            </h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Discover how Bright Future Academy can help your child reach their full potential.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                <a href="contact.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    Contact Us
                </a>
                <a href="classes.php" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-blue-900 px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    Explore Classes
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-blue-400 mb-4">
                        <i class="fas fa-graduation-cap mr-2"></i>Demataluva Maha Viddiyalaya
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
</body>
</html>

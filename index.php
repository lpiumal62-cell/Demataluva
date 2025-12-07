<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get statistics for counters
$stats = getStatistics();

// Get parent feedback from database
$parentFeedback = getParentFeedback(6);
?>
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demataluva Maha Viddiyalaya - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-gray-900 text-gray-100 font-inter">
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/50 to-purple-900/50"></div>
        <div class="swiper hero-swiper absolute inset-0">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="w-full h-full bg-gradient-to-br from-blue-900 to-purple-900 flex items-center justify-center">
                        <div class="text-center" data-aos="fade-up">
                            <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                                Demataluva Maha Viddiyalaya
                            </h1>
                            <p class="text-xl md:text-2xl text-gray-300 mb-8">Empowering Minds, Building Futures</p>
                            <a href="about.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="w-full h-full bg-gradient-to-br from-green-900 to-blue-900 flex items-center justify-center">
                        <div class="text-center" data-aos="fade-up">
                            <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-green-400 to-blue-400 bg-clip-text text-transparent">
                                Excellence in Education
                            </h1>
                            <p class="text-xl md:text-2xl text-gray-300 mb-8">Where Every Student Shines</p>
                            <a href="classes.php" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                                Explore Classes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Gallery Preview -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8" data-aos="fade-up">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white">Latest Gallery</h2>
                    <p class="text-gray-400">Moments from Demataluva Maha Viddiyalaya</p>
                </div>
                <a href="gallery.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition-all duration-300">View All</a>
            </div>
            <?php $homeGallery = getGalleryImages(6); ?>
            <?php if (!empty($homeGallery)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($homeGallery as $i => $g): ?>
                        <a href="gallery.php" class="group" data-aos="fade-up" data-aos-delay="<?php echo ($i % 6 + 1) * 80; ?>">
                            <div class="rounded-lg overflow-hidden bg-gray-800">
                                <div class="h-56 bg-black">
                                    <img src="<?php echo htmlspecialchars($g['image_path']); ?>" alt="<?php echo htmlspecialchars($g['description']); ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-white font-semibold line-clamp-1"><?php echo htmlspecialchars($g['event'] ?: 'Gallery'); ?></h3>
                                        <span class="text-gray-400 text-sm"><?php echo htmlspecialchars($g['year']); ?></span>
                                    </div>
                                    <p class="text-gray-300 text-sm mt-1 line-clamp-2"><?php echo htmlspecialchars($g['description']); ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-16" data-aos="fade-up">
                    <p class="text-gray-400">No gallery items yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Events Preview -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8" data-aos="fade-up">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white">Upcoming Events</h2>
                    <p class="text-gray-400">Stay updated with school news</p>
                </div>
                <a href="events.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition-all duration-300">View All</a>
            </div>
            <?php $homeEvents = getEvents(3); ?>
            <?php if (!empty($homeEvents)): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($homeEvents as $i => $e): ?>
                        <a href="events.php" class="group" data-aos="fade-up" data-aos-delay="<?php echo ($i + 1) * 100; ?>">
                            <div class="bg-gray-900 rounded-lg overflow-hidden border border-gray-800">
                                <div class="h-44 bg-black flex items-center justify-center">
                                    <?php if (!empty($e['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($e['image']); ?>" alt="<?php echo htmlspecialchars($e['title']); ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                    <?php else: ?>
                                        <i class="fas fa-calendar-alt text-4xl text-white"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4 space-y-2">
                                    <h3 class="text-white font-semibold line-clamp-1"><?php echo htmlspecialchars($e['title']); ?></h3>
                                    <div class="flex items-center text-gray-400 text-sm space-x-3">
                                        <span><i class="fas fa-calendar mr-1"></i><?php echo htmlspecialchars($e['date']); ?></span>
                                        <?php if (!empty($e['time'])): ?><span><i class="fas fa-clock mr-1"></i><?php echo htmlspecialchars($e['time']); ?></span><?php endif; ?>
                                    </div>
                                    <p class="text-gray-300 text-sm line-clamp-2"><?php echo htmlspecialchars($e['description']); ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-16" data-aos="fade-up">
                    <p class="text-gray-400">No events scheduled.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Our Achievements</h2>
                <p class="text-xl text-gray-400">Numbers that speak for our excellence</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-users text-4xl text-white mb-4"></i>
                        <div class="text-4xl font-bold text-white counter" data-target="<?php echo $stats['total_students']; ?>">0</div>
                        <div class="text-lg text-blue-200">Total Students</div>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-chalkboard-teacher text-4xl text-white mb-4"></i>
                        <div class="text-4xl font-bold text-white counter" data-target="<?php echo $stats['total_teachers']; ?>">0</div>
                        <div class="text-lg text-green-200">Expert Teachers</div>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-book text-4xl text-white mb-4"></i>
                        <div class="text-4xl font-bold text-white counter" data-target="<?php echo $stats['total_classes']; ?>">0</div>
                        <div class="text-lg text-purple-200">Classes Offered</div>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-calendar-alt text-4xl text-white mb-4"></i>
                        <div class="text-4xl font-bold text-white counter" data-target="<?php echo $stats['total_events']; ?>">0</div>
                        <div class="text-lg text-yellow-200">Events Organized</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Links Section -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Quick Access</h2>
                <p class="text-xl text-gray-400">Navigate to important sections</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <a href="classes.php" class="group" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gray-800 rounded-lg p-8 hover:bg-gray-700 transition-all duration-300 transform group-hover:scale-105">
                        <i class="fas fa-graduation-cap text-4xl text-blue-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Classes & Courses</h3>
                        <p class="text-gray-400">Explore our comprehensive curriculum and class offerings</p>
                    </div>
                </a>
                <a href="results.php" class="group" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gray-800 rounded-lg p-8 hover:bg-gray-700 transition-all duration-300 transform group-hover:scale-105">
                        <i class="fas fa-chart-line text-4xl text-green-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Student Results</h3>
                        <p class="text-gray-400">View test scores and academic achievements</p>
                    </div>
                </a>
                <a href="gallery.php" class="group" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gray-800 rounded-lg p-8 hover:bg-gray-700 transition-all duration-300 transform group-hover:scale-105">
                        <i class="fas fa-images text-4xl text-purple-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Photo Gallery</h3>
                        <p class="text-gray-400">Browse through our school events and activities</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <!-- Parents' Opinions Section -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Parents' Opinions</h2>
                <p class="text-xl text-gray-400">What our parents say about Demataluva Maha Viddiyalaya</p>
            </div>
            <?php if (!empty($parentFeedback)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $colors = ['bg-blue-600', 'bg-green-600', 'bg-purple-600', 'bg-red-600', 'bg-yellow-600', 'bg-indigo-600'];
                foreach ($parentFeedback as $i => $feedback): 
                    $colorClass = $colors[$i % count($colors)];
                    $initials = strtoupper(substr($feedback['parent_name'], 0, 2));
                ?>
                <div class="bg-gray-800 rounded-lg p-6 hover:bg-gray-700 transition-all duration-300 transform hover:scale-105" data-aos="fade-up" data-aos-delay="<?php echo ($i + 1) * 100; ?>">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-yellow-400 text-3xl">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <div class="flex items-center">
                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                <i class="fas fa-star <?php echo $j <= $feedback['rating'] ? 'text-yellow-400' : 'text-gray-400'; ?> text-sm"></i>
                            <?php endfor; ?>
                            <span class="text-gray-400 text-sm ml-2"><?php echo $feedback['rating']; ?>/5</span>
                        </div>
                    </div>
                    <p class="text-gray-300 text-lg mb-4">"<?php echo htmlspecialchars($feedback['feedback']); ?>"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 <?php echo $colorClass; ?> rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                            <?php echo $initials; ?>
                        </div>
                        <div>
                            <div class="text-white font-semibold"><?php echo htmlspecialchars($feedback['parent_name']); ?></div>
                            <div class="text-gray-400">Parent of <?php echo htmlspecialchars($feedback['student_name']); ?> (<?php echo htmlspecialchars($feedback['grade']); ?>)</div>
                        </div>
                    </div>
                    <?php if (!empty($feedback['appreciations'])): ?>
                    <div class="mt-3 pt-3 border-t border-gray-700">
                        <p class="text-gray-400 text-sm">
                            <i class="fas fa-thumbs-up mr-1"></i>
                            Appreciates: <?php echo htmlspecialchars($feedback['appreciations']); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-16" data-aos="fade-up">
                <i class="fas fa-comments text-6xl text-gray-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-400 mb-2">No Parent Feedback Yet</h3>
                <p class="text-gray-500 mb-6">Be the first to share your experience with Demataluva Maha Viddiyalaya</p>
                <a href="parent.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-comment-plus mr-2"></i>Share Your Experience
                </a>
            </div>
            <?php endif; ?>
            <div class="text-center mt-12" data-aos="fade-up" data-aos-delay="700">
                <a href="parent.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-comments mr-2"></i>Share Your Experience
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
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
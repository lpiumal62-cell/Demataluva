<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$galleryImages = getGalleryImages();
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery - Demataluva Maha Viddiyalaya</title>
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
    <section class="pt-16 pb-20 bg-gradient-to-r from-purple-900 to-pink-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent" data-aos="fade-up">
                Photo Gallery
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Capturing moments of learning, growth, and achievement
            </p>
        </div>
    </section>

    <!-- Gallery Filter -->
    <section class="py-8 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-4" data-aos="fade-up">
                <button class="gallery-filter active bg-blue-600 text-white px-6 py-2 rounded-lg transition-all duration-300" data-filter="all">
                    All Photos
                </button>
                <button class="gallery-filter bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-300" data-filter="Science Fair">
                    Science Fair
                </button>
                <button class="gallery-filter bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-300" data-filter="Sports Day">
                    Sports Day
                </button>
                <button class="gallery-filter bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-300" data-filter="Art Exhibition">
                    Art Exhibition
                </button>
                <button class="gallery-filter bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-300" data-filter="Graduation">
                    Graduation
                </button>
                <button class="gallery-filter bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-300" data-filter="Daily Activities">
                    Daily Activities
                </button>
            </div>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if(!empty($galleryImages)): ?>
            <div class="gallery-grid">
                <?php foreach($galleryImages as $index => $image): ?>
                <div class="gallery-item" data-category="<?php echo htmlspecialchars($image['event']); ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 6 + 1) * 100; ?>">
                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($image['description']); ?>" loading="lazy">
                    <div class="gallery-overlay">
                        <div>
                            <h3 class="gallery-title text-white font-semibold text-lg mb-2"><?php echo htmlspecialchars($image['event']); ?></h3>
                            <p class="gallery-description text-gray-300 text-sm"><?php echo htmlspecialchars($image['description']); ?></p>
                            <div class="flex items-center mt-2">
                                <span class="text-gray-400 text-xs"><?php echo $image['year']; ?></span>
                                <span class="text-gray-400 text-xs mx-2">•</span>
                                <span class="text-gray-400 text-xs"><?php echo ucfirst($image['type']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-20" data-aos="fade-up">
                <i class="fas fa-images text-6xl text-gray-400 mb-6"></i>
                <h2 class="text-3xl font-bold text-white mb-4">No Photos Yet</h2>
                <p class="text-xl text-gray-400 mb-8">We're working on adding photos to our gallery. Check back soon!</p>
                <a href="events.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    View Events
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Gallery Statistics -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Gallery Statistics</h2>
                <p class="text-xl text-gray-400">Moments captured and memories made</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-camera text-4xl text-white mb-4"></i>
                        <div class="text-3xl font-bold text-white mb-2"><?php echo count($galleryImages); ?></div>
                        <div class="text-blue-200">Total Photos</div>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-calendar-alt text-4xl text-white mb-4"></i>
                        <div class="text-3xl font-bold text-white mb-2">12</div>
                        <div class="text-green-200">Events Captured</div>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-users text-4xl text-white mb-4"></i>
                        <div class="text-3xl font-bold text-white mb-2">500+</div>
                        <div class="text-purple-200">Students Featured</div>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-heart text-4xl text-white mb-4"></i>
                        <div class="text-3xl font-bold text-white mb-2">1000+</div>
                        <div class="text-yellow-200">Memories Made</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Events -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Recent Events</h2>
                <p class="text-xl text-gray-400">Latest happenings at our school</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-800 rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-flask text-6xl text-white"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-2">Annual Science Fair</h3>
                        <p class="text-gray-300 mb-4">Students showcase their innovative science projects and experiments.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-400 text-sm">March 15, 2024</span>
                            <a href="events.php" class="text-blue-400 hover:text-blue-300 text-sm">View Event →</a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="h-48 bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center">
                        <i class="fas fa-running text-6xl text-white"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-2">Sports Day</h3>
                        <p class="text-gray-300 mb-4">Annual sports competition featuring various athletic events and team games.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-green-400 text-sm">April 20, 2024</span>
                            <a href="events.php" class="text-green-400 hover:text-green-300 text-sm">View Event →</a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="h-48 bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                        <i class="fas fa-palette text-6xl text-white"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-2">Art Exhibition</h3>
                        <p class="text-gray-300 mb-4">Display of student artwork and creative projects from various art classes.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-purple-400 text-sm">May 10, 2024</span>
                            <a href="events.php" class="text-purple-400 hover:text-purple-300 text-sm">View Event →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-purple-900 to-pink-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6" data-aos="fade-up">
                Share Your Memories
            </h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Have photos from school events? We'd love to see them and add them to our gallery.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                <a href="contact.php" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    Share Photos
                </a>
                <a href="events.php" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-purple-900 px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    View Events
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
        // Initialize gallery filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.gallery-filter');
            const galleryItems = document.querySelectorAll('.gallery-item');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active button
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-blue-600');
                        btn.classList.add('bg-gray-700');
                    });
                    this.classList.add('active', 'bg-blue-600');
                    this.classList.remove('bg-gray-700');
                    
                    // Filter items
                    galleryItems.forEach(item => {
                        if (filter === 'all' || item.getAttribute('data-category') === filter) {
                            item.style.display = 'block';
                            item.classList.add('fade-in');
                        } else {
                            item.style.display = 'none';
                            item.classList.remove('fade-in');
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>

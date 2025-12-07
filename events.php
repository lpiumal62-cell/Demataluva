<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$events = getEvents();
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events & News - Demataluva Maha Viddiyalaya</title>
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
    <section class="pt-16 pb-20 bg-gradient-to-r from-orange-900 to-red-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-orange-400 to-red-400 bg-clip-text text-transparent" data-aos="fade-up">
                Events & News
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Stay updated with our latest events and school news
            </p>
        </div>
    </section>

    <!-- Events Calendar -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Upcoming Events</h2>
                <p class="text-xl text-gray-400">Mark your calendars for these exciting events</p>
            </div>
            
            <?php if(!empty($events)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach($events as $index => $event): ?>
                <div class="bg-gray-700 rounded-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <?php if($event['image']): ?>
                    <div class="h-48 bg-cover bg-center" style="background-image: url('<?php echo htmlspecialchars($event['image']); ?>');">
                        <div class="h-full bg-black bg-opacity-40 flex items-end">
                            <div class="p-4">
                                <div class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    <?php echo date('M j', strtotime($event['date'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-6xl text-white"></i>
                    </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-2"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="text-gray-300 mb-4 line-clamp-3"><?php echo htmlspecialchars($event['description']); ?></p>
                        
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-gray-400 mr-3"></i>
                                <span class="text-gray-300"><?php echo date('F j, Y', strtotime($event['date'])); ?></span>
                            </div>
                            <?php if($event['time']): ?>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-400 mr-3"></i>
                                <span class="text-gray-300"><?php echo date('g:i A', strtotime($event['time'])); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if($event['location']): ?>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-3"></i>
                                <span class="text-gray-300"><?php echo htmlspecialchars($event['location']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <button onclick="showEventDetails(<?php echo htmlspecialchars(json_encode($event)); ?>)" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-info-circle mr-2"></i>View Details
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-20" data-aos="fade-up">
                <i class="fas fa-calendar-alt text-6xl text-gray-400 mb-6"></i>
                <h2 class="text-3xl font-bold text-white mb-4">No Events Scheduled</h2>
                <p class="text-xl text-gray-400 mb-8">We're working on planning exciting events. Check back soon!</p>
                <a href="contact.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-all duration-300 transform hover:scale-105">
                    Contact Us
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Event Categories -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Event Categories</h2>
                <p class="text-xl text-gray-400">Types of events we organize</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-flask text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Academic Events</h3>
                        <p class="text-blue-200">Science fairs, competitions, and academic showcases</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-running text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Sports Events</h3>
                        <p class="text-green-200">Athletic competitions and sports day celebrations</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-palette text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Cultural Events</h3>
                        <p class="text-purple-200">Art exhibitions, music concerts, and cultural festivals</p>
                    </div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 rounded-lg p-8 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-graduation-cap text-4xl text-white mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Ceremonies</h3>
                        <p class="text-yellow-200">Graduation ceremonies and award presentations</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Latest News</h2>
                <p class="text-xl text-gray-400">Stay informed about school updates and announcements</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-700 rounded-lg p-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-600 rounded-full p-3 mr-4">
                            <i class="fas fa-newspaper text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">New Academic Program</h3>
                            <p class="text-gray-400 text-sm">March 15, 2024</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4">
                        We're excited to announce the launch of our new STEM program, designed to enhance students' skills in science, technology, engineering, and mathematics.
                    </p>
                    <a href="#" class="text-blue-400 hover:text-blue-300 font-semibold">Read More →</a>
                </div>
                
                <div class="bg-gray-700 rounded-lg p-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-600 rounded-full p-3 mr-4">
                            <i class="fas fa-trophy text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Award Recognition</h3>
                            <p class="text-gray-400 text-sm">March 10, 2024</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Bright Future Academy has been recognized as the "Best Educational Institution" by the State Education Board for our outstanding academic achievements.
                    </p>
                    <a href="#" class="text-green-400 hover:text-green-300 font-semibold">Read More →</a>
                </div>
                
                <div class="bg-gray-700 rounded-lg p-8" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-600 rounded-full p-3 mr-4">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Parent-Teacher Conference</h3>
                            <p class="text-gray-400 text-sm">March 5, 2024</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Join us for our upcoming parent-teacher conference to discuss your child's progress and academic development. Registration is now open.
                    </p>
                    <a href="#" class="text-purple-400 hover:text-purple-300 font-semibold">Register Now →</a>
                </div>
                
                <div class="bg-gray-700 rounded-lg p-8" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-600 rounded-full p-3 mr-4">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white">Summer Program</h3>
                            <p class="text-gray-400 text-sm">February 28, 2024</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Registration for our summer enrichment program is now open. Limited spots available for various courses including coding, art, and sports.
                    </p>
                    <a href="#" class="text-yellow-400 hover:text-yellow-300 font-semibold">Learn More →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Subscription -->
    <section class="py-20 bg-gradient-to-r from-orange-900 to-red-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6" data-aos="fade-up">
                Stay Updated
            </h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Subscribe to our newsletter to receive the latest news and event updates directly in your inbox.
            </p>
            <form id="newsletter-form" class="max-w-md mx-auto" data-aos="fade-up" data-aos-delay="400">
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="email" placeholder="Enter your email" required class="flex-1 bg-white bg-opacity-10 text-white placeholder-gray-300 px-4 py-3 rounded-lg border border-white border-opacity-20 focus:border-opacity-40 focus:outline-none">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-lg font-medium transition-all duration-300 transform hover:scale-105">
                        Subscribe
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Event Details Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content max-w-2xl">
            <button class="modal-close absolute top-4 right-4 text-white text-2xl hover:text-blue-400">&times;</button>
            <div class="p-6">
                <h3 id="modalEventTitle" class="text-2xl font-bold text-white mb-4"></h3>
                <div id="modalEventDetails" class="space-y-4 text-gray-300"></div>
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
        function showEventDetails(eventData) {
            const modal = document.getElementById('eventModal');
            const eventTitle = document.getElementById('modalEventTitle');
            const eventDetails = document.getElementById('modalEventDetails');
            
            eventTitle.textContent = eventData.title;
            eventDetails.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="text-white font-semibold mb-2">Description</h4>
                        <p class="text-gray-300">${eventData.description || 'No description available'}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-white font-semibold mb-2">Date</h4>
                            <p class="text-blue-400">${new Date(eventData.date).toLocaleDateString('en-US', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            })}</p>
                        </div>
                        ${eventData.time ? `
                        <div>
                            <h4 class="text-white font-semibold mb-2">Time</h4>
                            <p class="text-green-400">${new Date('1970-01-01T' + eventData.time).toLocaleTimeString('en-US', { 
                                hour: 'numeric', 
                                minute: '2-digit', 
                                hour12: true 
                            })}</p>
                        </div>
                        ` : ''}
                        ${eventData.location ? `
                        <div class="md:col-span-2">
                            <h4 class="text-white font-semibold mb-2">Location</h4>
                            <p class="text-purple-400">${eventData.location}</p>
                        </div>
                        ` : ''}
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-2">Created</h4>
                        <p class="text-gray-300">${new Date(eventData.created_at).toLocaleDateString()}</p>
                    </div>
                </div>
            `;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    </script>
</body>
</html>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-blue-400 mb-4">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        <span class="hidden sm:inline">Demataluva Maha Viddiyalaya</span>
                        <span class="sm:hidden">DMV</span>
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
                        <p><i class="fas fa-envelope mr-2"></i>info@demataluva.mv</p>
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
    
    <!-- Mobile Menu Script -->
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    
                    // Toggle icon
                    const icon = mobileMenuButton.querySelector('i');
                    if (mobileMenu.classList.contains('hidden')) {
                        icon.className = 'fas fa-bars text-xl';
                    } else {
                        icon.className = 'fas fa-times text-xl';
                    }
                });
                
                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                        const icon = mobileMenuButton.querySelector('i');
                        icon.className = 'fas fa-bars text-xl';
                    }
                });
                
                // Close mobile menu when window is resized to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024) {
                        mobileMenu.classList.add('hidden');
                        const icon = mobileMenuButton.querySelector('i');
                        icon.className = 'fas fa-bars text-xl';
                    }
                });
            }
        });
    </script>
</body>
</html>



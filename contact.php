<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
$csrf_token = generateCSRFToken();
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="pt-16 pb-20 bg-gradient-to-r from-teal-900 to-blue-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-teal-400 to-blue-400 bg-clip-text text-transparent" data-aos="fade-up">
            Contact Us
        </h1>
        <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
            Get in touch with Demataluva Maha Viddiyalaya for any questions or inquiries
        </p>
    </div>
</section>

<!-- Contact Form & Info -->
<section class="py-20 bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            
            <!-- Contact Form -->
            <div data-aos="fade-right">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4 sm:mb-6">Send us a Message</h2>
                
                <form id="contact-form" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                    <div>
                        <label class="block text-white font-semibold mb-2">Full Name</label>
                        <input type="text" name="name" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" placeholder="Enter your full name">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" placeholder="Enter your email address">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Phone Number (Optional)</label>
                        <input type="tel" name="phone" class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" placeholder="Enter your phone number">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Subject</label>
                        <input type="text" name="subject" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" placeholder="Enter the subject">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Message</label>
                        <textarea name="message" rows="6" required class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none resize-none" placeholder="Enter your message"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-paper-plane mr-2"></i>Send Message
                    </button>
                </form>
                
                <!-- Success/Error Messages -->
                <div id="message-container" class="mt-4" style="display: none;">
                    <div id="success-message" class="bg-green-600 text-white p-4 rounded-lg mb-4" style="display: none;">
                        <i class="fas fa-check-circle mr-2"></i><span id="success-text"></span>
                    </div>
                    <div id="error-message" class="bg-red-600 text-white p-4 rounded-lg mb-4" style="display: none;">
                        <i class="fas fa-exclamation-circle mr-2"></i><span id="error-text"></span>
                    </div>
                </div>

                <div id="form-response" class="mt-4 text-center text-lg font-medium"></div>
            </div>

            <!-- Contact Info -->
            <div data-aos="fade-left">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4 sm:mb-6">Get in Touch</h2>
                <div class="space-y-8">
                    <div class="flex items-start">
                        <div class="bg-blue-600 rounded-lg p-4 mr-4">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-2">Address</h3>
                            <p class="text-gray-300">Demataluva<br>Maldives<br>Indian Ocean</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="bg-green-600 rounded-lg p-4 mr-4">
                            <i class="fas fa-phone text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-2">Phone</h3>
                            <p class="text-gray-300">+960 XXX-XXXX<br>+960 XXX-XXXX</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="bg-purple-600 rounded-lg p-4 mr-4">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-2">Email</h3>
                            <p class="text-gray-300">info@demataluva.mv<br>lakshithapiumal09@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold text-white mb-4">What Parents Say</h2>
            <p class="text-xl text-gray-400">Testimonials from our community</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-700 rounded-lg p-6 text-center hover:bg-gray-600 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                <div class="text-yellow-400 text-3xl mb-4">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="text-gray-300 text-lg mb-4">"Demataluva Maha Viddiyalaya has provided excellent education for my children. The teachers are dedicated and caring."</p>
                <div class="text-white font-semibold">Ahmed Hassan</div>
                <div class="text-gray-400">Parent</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-6 text-center hover:bg-gray-600 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                <div class="text-yellow-400 text-3xl mb-4">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="text-gray-300 text-lg mb-4">"The school environment is nurturing and the academic standards are high. My daughter loves going to school every day."</p>
                <div class="text-white font-semibold">Fatima Ali</div>
                <div class="text-gray-400">Parent</div>
            </div>
            <div class="bg-gray-700 rounded-lg p-6 text-center hover:bg-gray-600 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                <div class="text-yellow-400 text-3xl mb-4">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="text-gray-300 text-lg mb-4">"The teachers are very supportive and the school provides a well-rounded education. Highly recommended!"</p>
                <div class="text-white font-semibold">Mohamed Ibrahim</div>
                <div class="text-gray-400">Parent</div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-20 bg-gray-900 text-center">
    <h2 class="text-4xl font-bold text-white mb-6">Find Us</h2>
    <a href="https://maps.google.com" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
        <i class="fas fa-map-marked-alt mr-2"></i>Open Google Maps
    </a>
</section>

<?php include 'includes/footer.php'; ?>

<!-- Form Script -->
<script>
document.getElementById('contact-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const responseDiv = document.getElementById('form-response');
    responseDiv.innerHTML = "<span class='text-blue-400'>Sending message...</span>";

    const formData = new FormData(form);

    try {
        const res = await fetch('contact_submit.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            responseDiv.innerHTML = "<span class='text-green-400'>" + data.message + "</span>";
            form.reset();
        } else {
            responseDiv.innerHTML = "<span class='text-red-400'>" + data.message + "</span>";
        }
    } catch (err) {
        responseDiv.innerHTML = "<span class='text-red-400'>Error: Unable to send message.</span>";
    }
});
</script>

<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Generate CSRF token
$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Feedback - Demataluva Maha Viddiyalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-gray-900 text-gray-100 font-inter">
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-white mb-6" data-aos="fade-up">
                <i class="fas fa-comments mr-3 text-blue-400"></i>Parent Feedback
            </h1>
            <p class="text-xl text-gray-400 mb-8" data-aos="fade-up" data-aos-delay="100">
                Share your thoughts and experiences about Demataluva Maha Viddiyalaya
            </p>
        </div>
    </section>

    <!-- Feedback Form -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-lg p-8" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-white mb-6 text-center">
                    <i class="fas fa-edit mr-2 text-blue-400"></i>Share Your Experience
                </h2>
                
                <form id="parent-feedback-form" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white font-semibold mb-2">
                                <i class="fas fa-user mr-2 text-blue-400"></i>Parent Name
                            </label>
                            <input type="text" name="parent_name" required 
                                   class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" 
                                   placeholder="Enter your full name">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-400"></i>Email Address
                            </label>
                            <input type="email" name="email" required 
                                   class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" 
                                   placeholder="Enter your email address">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white font-semibold mb-2">
                                <i class="fas fa-child mr-2 text-blue-400"></i>Student Name
                            </label>
                            <input type="text" name="student_name" required 
                                   class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none" 
                                   placeholder="Enter your child's name">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">
                                <i class="fas fa-graduation-cap mr-2 text-blue-400"></i>Grade/Class
                            </label>
                            <select name="grade" required 
                                    class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                                <option value="">Select Grade</option>
                                <option value="Grade 1">Grade 1</option>
                                <option value="Grade 2">Grade 2</option>
                                <option value="Grade 3">Grade 3</option>
                                <option value="Grade 4">Grade 4</option>
                                <option value="Grade 5">Grade 5</option>
                                <option value="Grade 6">Grade 6</option>
                                <option value="Grade 7">Grade 7</option>
                                <option value="Grade 8">Grade 8</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">
                            <i class="fas fa-star mr-2 text-blue-400"></i>Overall Rating
                        </label>
                        <div class="flex space-x-2">
                            <input type="radio" name="rating" value="5" id="rating5" class="sr-only">
                            <label for="rating5" class="text-3xl cursor-pointer rating-star" data-rating="5">★</label>
                            
                            <input type="radio" name="rating" value="4" id="rating4" class="sr-only">
                            <label for="rating4" class="text-3xl cursor-pointer rating-star" data-rating="4">★</label>
                            
                            <input type="radio" name="rating" value="3" id="rating3" class="sr-only">
                            <label for="rating3" class="text-3xl cursor-pointer rating-star" data-rating="3">★</label>
                            
                            <input type="radio" name="rating" value="2" id="rating2" class="sr-only">
                            <label for="rating2" class="text-3xl cursor-pointer rating-star" data-rating="2">★</label>
                            
                            <input type="radio" name="rating" value="1" id="rating1" class="sr-only">
                            <label for="rating1" class="text-3xl cursor-pointer rating-star" data-rating="1">★</label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">
                            <i class="fas fa-comment-alt mr-2 text-blue-400"></i>Your Feedback
                        </label>
                        <textarea name="feedback" rows="6" required 
                                  class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none resize-none" 
                                  placeholder="Please share your thoughts about your child's experience at Demataluva Maha Viddiyalaya. What do you like most? Any suggestions for improvement?"></textarea>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">
                            <i class="fas fa-check-circle mr-2 text-blue-400"></i>What aspects do you appreciate most?
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="appreciations[]" value="Teaching Quality" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                <span class="text-gray-300">Teaching Quality</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="appreciations[]" value="School Facilities" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                <span class="text-gray-300">School Facilities</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="appreciations[]" value="Communication" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                <span class="text-gray-300">Communication</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="appreciations[]" value="Student Support" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                <span class="text-gray-300">Student Support</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="appreciations[]" value="Extracurricular Activities" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                <span class="text-gray-300">Extracurricular Activities</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="appreciations[]" value="School Environment" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                <span class="text-gray-300">School Environment</span>
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-8 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Feedback
                        </button>
                    </div>
                </form>

                <!-- Success/Error Messages -->
                <div id="message-container" class="mt-6" style="display: none;">
                    <div id="success-message" class="bg-green-600 text-white p-4 rounded-lg mb-4" style="display: none;">
                        <i class="fas fa-check-circle mr-2"></i><span id="success-text"></span>
                    </div>
                    <div id="error-message" class="bg-red-600 text-white p-4 rounded-lg mb-4" style="display: none;">
                        <i class="fas fa-exclamation-circle mr-2"></i><span id="error-text"></span>
                    </div>
                </div>

                <div id="form-response" class="mt-4 text-center text-lg font-medium"></div>
            </div>
        </div>
    </section>

    <!-- Recent Feedback -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-white mb-4">Recent Parent Feedback</h2>
                <p class="text-xl text-gray-400">What other parents are saying about DMV</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-700 rounded-lg p-6 hover:bg-gray-600 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400 text-lg">
                            ★★★★★
                        </div>
                        <span class="ml-2 text-gray-300 text-sm">5.0</span>
                    </div>
                    <p class="text-gray-300 text-lg mb-4">"Excellent school with dedicated teachers. My daughter has improved significantly in her studies."</p>
                    <div class="text-white font-semibold">Ahmed Hassan</div>
                    <div class="text-gray-400 text-sm">Parent of Grade 8 Student</div>
                </div>
                <div class="bg-gray-700 rounded-lg p-6 hover:bg-gray-600 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400 text-lg">
                            ★★★★★
                        </div>
                        <span class="ml-2 text-gray-300 text-sm">5.0</span>
                    </div>
                    <p class="text-gray-300 text-lg mb-4">"Great facilities and supportive environment. Communication with parents is excellent."</p>
                    <div class="text-white font-semibold">Fatima Ali</div>
                    <div class="text-gray-400 text-sm">Parent of Grade 6 Student</div>
                </div>
                <div class="bg-gray-700 rounded-lg p-6 hover:bg-gray-600 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400 text-lg">
                            ★★★★★
                        </div>
                        <span class="ml-2 text-gray-300 text-sm">5.0</span>
                    </div>
                    <p class="text-gray-300 text-lg mb-4">"Perfect balance of academics and extracurricular activities. Highly recommended!"</p>
                    <div class="text-white font-semibold">Mohamed Ibrahim</div>
                    <div class="text-gray-400 text-sm">Parent of Grade 10 Student</div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- Form Script -->
    <script>
        // Star rating functionality
        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                document.querySelector(`input[name="rating"][value="${rating}"]`).checked = true;
                
                // Update visual stars
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('text-yellow-400');
                        s.classList.remove('text-gray-400');
                    } else {
                        s.classList.add('text-gray-400');
                        s.classList.remove('text-yellow-400');
                    }
                });
            });
        });

        // Form submission
        document.getElementById('parent-feedback-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const responseDiv = document.getElementById('form-response');
            responseDiv.innerHTML = "<span class='text-blue-400'>Submitting feedback...</span>";

            const formData = new FormData(form);

            try {
                const res = await fetch('parent_feedback_submit.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    responseDiv.innerHTML = "<span class='text-green-400'>" + data.message + "</span>";
                    form.reset();
                    // Reset star rating
                    document.querySelectorAll('.rating-star').forEach(star => {
                        star.classList.add('text-gray-400');
                        star.classList.remove('text-yellow-400');
                    });
                } else {
                    responseDiv.innerHTML = "<span class='text-red-400'>" + data.message + "</span>";
                }
            } catch (err) {
                responseDiv.innerHTML = "<span class='text-red-400'>Error: Unable to submit feedback.</span>";
            }
        });
    </script>

    <style>
        .rating-star {
            color: #6b7280;
            transition: color 0.2s ease;
        }
        .rating-star:hover {
            color: #fbbf24;
        }
    </style>
</body>
</html>

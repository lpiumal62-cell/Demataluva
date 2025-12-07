<?php
// Reusable site header for Demataluva Maha Viddiyalaya
// Usage: set $pageTitle before including, e.g. $pageTitle = 'Home';
$title = isset($pageTitle) && $pageTitle !== '' ? $pageTitle : 'Home';
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Demataluva Maha Viddiyalaya - <?php echo htmlspecialchars($title); ?></title>
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-gray-100 font-inter">
	<!-- Navigation -->
	<nav class="fixed w-full z-50 bg-gray-900/95 backdrop-blur-sm border-b border-gray-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex justify-between items-center h-16">
				<div class="flex items-center">
					<a href="index.php" class="text-xl sm:text-2xl font-bold text-blue-400">
						<i class="fas fa-graduation-cap mr-1 sm:mr-2"></i>
						<span class="hidden sm:inline">DMV</span>
						<span class="sm:hidden">DMV</span>
					</a>
				</div>
				
				<!-- Desktop Menu -->
				<div class="hidden lg:block">
					<div class="ml-10 flex items-baseline space-x-4">
						<a href="index.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Home</a>
						<a href="about.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">About</a>
						<a href="classes.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Classes</a>
						<a href="teachers.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Teachers</a>
						<a href="results.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Results</a>
						<a href="gallery.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Gallery</a>
						<a href="events.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Events</a>
						<a href="contact.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
						<a href="parent.php" class="text-gray-300 hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium">Parent Feedback</a>
					</div>
				</div>
				
				<!-- Desktop Admin Button -->
				<div class="hidden lg:flex items-center space-x-4">
					<a href="admin/login.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Admin</a>
				</div>
				
				<!-- Mobile Menu Button -->
				<div class="lg:hidden">
					<button id="mobile-menu-button" class="text-gray-300 hover:text-blue-400 focus:outline-none focus:text-blue-400">
						<i class="fas fa-bars text-xl"></i>
					</button>
				</div>
			</div>
			
			<!-- Mobile Menu -->
			<div id="mobile-menu" class="lg:hidden hidden">
				<div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-800 rounded-lg mt-2">
					<a href="index.php" class="text-gray-300 hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Home</a>
					<a href="about.php" class="text-gray-300 hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">About</a>
					<a href="classes.php" class="text-gray-300 hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Classes</a>
					<a href="teachers.php" class="text-gray-300 hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Teachers</a>
					<a href="results.php" class="text-gray-300 hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Results</a>
					<a href="gallery.php" class="text-gray-300 hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Gallery</a>
					<a href="events.php" class="text-gray-300 hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Events</a>
					<a href="contact.php" class="text-gray-300 hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Contact</a>
					<a href="parent.php" class="text-gray-300 hover:text-blue-400 block px-3 py-2 rounded-md text-base font-medium">Parent Feedback</a>
					<a href="admin/login.php" class="bg-blue-600 hover:bg-blue-700 text-white block px-3 py-2 rounded-md text-base font-medium mt-2">Admin Login</a>
				</div>
			</div>
		</div>
	</nav>



<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Bright Future Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-900 text-gray-100 font-inter min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="mb-8">
            <i class="fas fa-exclamation-triangle text-8xl text-yellow-400 mb-4"></i>
            <h1 class="text-6xl font-bold text-white mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-300 mb-4">Page Not Found</h2>
            <p class="text-gray-400 mb-8 max-w-md mx-auto">
                The page you're looking for doesn't exist or has been moved.
            </p>
        </div>
        
        <div class="space-y-4">
            <a href="index.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-home mr-2"></i>Go Home
            </a>
            <div class="text-gray-400">
                <a href="javascript:history.back()" class="hover:text-blue-400 transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Go Back
                </a>
            </div>
        </div>
        
        <div class="mt-12 text-gray-500 text-sm">
            <p>&copy; 2024 Bright Future Academy. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

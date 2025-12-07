<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/mail_helper.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Verify CSRF token
if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit;
}

// Sanitize inputs
$parent_name = sanitizeInput($_POST['parent_name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$student_name = sanitizeInput($_POST['student_name'] ?? '');
$grade = sanitizeInput($_POST['grade'] ?? '');
$rating = sanitizeInput($_POST['rating'] ?? '');
$feedback = sanitizeInput($_POST['feedback'] ?? '');
$appreciations = $_POST['appreciations'] ?? [];

// Validate
$errors = [];
if (!$parent_name) $errors[] = 'Parent name is required';
if (!$email) $errors[] = 'Email is required';
elseif (!validateEmail($email)) $errors[] = 'Invalid email format';
if (!$student_name) $errors[] = 'Student name is required';
if (!$grade) $errors[] = 'Grade is required';
if (!$rating) $errors[] = 'Rating is required';
if (!$feedback) $errors[] = 'Feedback is required';

if ($errors) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    // Convert appreciations array to string
    $appreciations_str = is_array($appreciations) ? implode(', ', $appreciations) : '';

    // Save to database
    $stmt = $pdo->prepare("INSERT INTO parent_feedback (parent_name, email, student_name, grade, rating, feedback, appreciations, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$parent_name, $email, $student_name, $grade, $rating, $feedback, $appreciations_str]);
    $feedbackId = $pdo->lastInsertId();

    // Get admin email (with fallback)
    $adminTo = MAIL_TO_CONTACT;
    $adminName = 'School Administrator';
    try {
        $adm = $pdo->query("SELECT email, first_name, last_name FROM admins WHERE email IS NOT NULL AND email <> '' ORDER BY id LIMIT 1")->fetch();
        if ($adm && filter_var($adm['email'], FILTER_VALIDATE_EMAIL)) {
            $adminTo = $adm['email'];
            $adminName = $adm['first_name'] . ' ' . $adm['last_name'];
        }
    } catch (Throwable $e) { 
        error_log('Admin email lookup failed: ' . $e->getMessage());
    }

    // Enhanced admin notification email
    $adminSubject = 'New Parent Feedback Received - Rating: ' . $rating . '/5';
    $adminBody = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9;">
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
                📝 New Parent Feedback Received
            </h2>
            
            <div style="background: #ecf0f1; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="color: #34495e; margin: 0 0 10px 0;">Feedback Details</h3>
                <p style="margin: 5px 0;"><strong>⭐ Rating:</strong> ' . $rating . '/5 stars</p>
                <p style="margin: 5px 0;"><strong>👤 Parent:</strong> ' . htmlspecialchars($parent_name) . '</p>
                <p style="margin: 5px 0;"><strong>📧 Email:</strong> <a href="mailto:' . htmlspecialchars($email) . '" style="color: #3498db;">' . htmlspecialchars($email) . '</a></p>
                <p style="margin: 5px 0;"><strong>🎓 Student:</strong> ' . htmlspecialchars($student_name) . '</p>
                <p style="margin: 5px 0;"><strong>📚 Grade:</strong> ' . htmlspecialchars($grade) . '</p>
                <p style="margin: 5px 0;"><strong>🕒 Received:</strong> ' . date('F j, Y \a\t g:i A') . '</p>
                <p style="margin: 5px 0;"><strong>🆔 Feedback ID:</strong> #' . $feedbackId . '</p>
            </div>
            
            <div style="background: #fff; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;">
                <h4 style="color: #2c3e50; margin: 0 0 10px 0;">Parent Feedback:</h4>
                <div style="line-height: 1.6; color: #555;">' . nl2br(htmlspecialchars($feedback)) . '</div>
            </div>';

    if (!empty($appreciations_str)) {
        $adminBody .= '
            <div style="background: #e8f5e8; border-left: 4px solid #27ae60; padding: 15px; margin-bottom: 20px;">
                <h4 style="color: #27ae60; margin: 0 0 10px 0;">👍 Appreciated Aspects:</h4>
                <div style="color: #555;">' . htmlspecialchars($appreciations_str) . '</div>
            </div>';
    }

    $adminBody .= '
            <div style="text-align: center; margin-top: 30px;">
                <a href="' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/admin/parent_feedback.php" 
                   style="background: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    📋 View in Admin Panel
                </a>
            </div>
            
            <hr style="border: none; border-top: 1px solid #ecf0f1; margin: 30px 0;">
            <p style="color: #7f8c8d; font-size: 12px; text-align: center; margin: 0;">
                This feedback was submitted from the Demataluva Maha Viddiyalaya parent feedback form.<br>
                You can reply directly to the parent: <a href="mailto:' . htmlspecialchars($email) . '" style="color: #3498db;">' . htmlspecialchars($email) . '</a>
            </p>
        </div>
    </div>';

    // Enhanced thank you email
    $thankYouSubject = 'Thank you for your feedback - Demataluva Maha Viddiyalaya';
    $thankYouBody = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9;">
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: #2c3e50; margin: 0; font-size: 28px;">🎓 Demataluva Maha Viddiyalaya</h1>
                <p style="color: #7f8c8d; margin: 10px 0 0 0; font-size: 16px;">Empowering minds, building futures</p>
            </div>
            
            <h2 style="color: #2c3e50; margin-bottom: 20px;">Thank You for Your Valuable Feedback!</h2>
            
            <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">
                Dear <strong>' . htmlspecialchars($parent_name) . '</strong>,
            </p>
            
            <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">
                Thank you for taking the time to share your feedback about your child <strong>' . htmlspecialchars($student_name) . '</strong>\'s experience at Demataluva Maha Viddiyalaya. Your input is invaluable to us and helps us continuously improve our educational services.
            </p>
            
            <div style="background: #e8f5e8; border-left: 4px solid #27ae60; padding: 15px; margin: 20px 0;">
                <h4 style="color: #27ae60; margin: 0 0 10px 0;">📋 Your Feedback Summary</h4>
                <ul style="color: #555; margin: 0; padding-left: 20px;">
                    <li><strong>Rating:</strong> ' . $rating . '/5 stars</li>
                    <li><strong>Student:</strong> ' . htmlspecialchars($student_name) . ' (' . htmlspecialchars($grade) . ')</li>
                    <li><strong>Submitted:</strong> ' . date('F j, Y \a\t g:i A') . '</li>
                </ul>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
                <h4 style="color: #2c3e50; margin: 0 0 15px 0;">📞 Next Steps</h4>
                <ul style="color: #555; margin: 0; padding-left: 20px;">
                    <li>Our team will review your feedback carefully</li>
                    <li>We will take appropriate action based on your suggestions</li>
                    <li>If needed, we may contact you for further discussion</li>
                    <li>Your feedback will help us enhance our educational programs</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/" 
                   style="background: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">
                    🏠 Visit Our Website
                </a>
                <a href="' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/contact.php" 
                   style="background: #27ae60; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">
                    📞 Contact Us
                </a>
            </div>
            
            <hr style="border: none; border-top: 1px solid #ecf0f1; margin: 30px 0;">
            <p style="color: #7f8c8d; font-size: 12px; text-align: center; margin: 0;">
                This is an automated response. Please do not reply to this email.<br>
                If you need immediate assistance, please contact us directly.<br><br>
                <strong>Demataluva Maha Viddiyalaya</strong><br>
                Developer: BlackEagle
            </p>
        </div>
    </div>';

    // Send emails with error handling
    $emailSuccess = true;
    $emailErrors = [];

    // Send admin notification
    $adminEmailSent = sendMailMessage($adminTo, $adminName, $adminSubject, $adminBody);
    if (!$adminEmailSent) {
        $emailSuccess = false;
        $emailErrors[] = 'Failed to send admin notification';
    }

    // Send thank you email
    $thankYouEmailSent = sendMailMessage($email, $parent_name, $thankYouSubject, $thankYouBody);
    if (!$thankYouEmailSent) {
        $emailSuccess = false;
        $emailErrors[] = 'Failed to send thank you email';
    }

    // Log email status
    if (!$emailSuccess) {
        error_log('Parent feedback email errors: ' . implode(', ', $emailErrors));
    }

    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your valuable feedback! We appreciate your input and will use it to improve our services.'
    ]);

} catch (PDOException $e) {
    error_log("Parent feedback error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
}
?>

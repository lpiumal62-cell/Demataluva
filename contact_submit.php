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

// Verify CSRF (temporarily disabled for testing)
/*
if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    error_log('CSRF Token verification failed. Session token: ' . ($_SESSION['csrf_token'] ?? 'not set') . ', Posted token: ' . ($_POST['csrf_token'] ?? 'not set'));
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit;
}
*/

// Sanitize inputs
$name = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$phone = sanitizeInput($_POST['phone'] ?? '');
$subject = sanitizeInput($_POST['subject'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');

// Validate
$errors = [];
if (!$name) $errors[] = 'Name is required';
if (!$email) $errors[] = 'Email is required';
elseif (!validateEmail($email)) $errors[] = 'Invalid email format';
if (!$subject) $errors[] = 'Subject is required';
if (!$message) $errors[] = 'Message is required';

if ($errors) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    // Save to database
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $subject, $message]);
    $messageId = $pdo->lastInsertId();

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
    $adminSubject = 'New Contact Message: ' . $subject;
    $adminBody = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9;">
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
                📧 New Contact Message Received
            </h2>
            
            <div style="background: #ecf0f1; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <h3 style="color: #34495e; margin: 0 0 10px 0;">Message Details</h3>
                <p style="margin: 5px 0;"><strong>📝 Subject:</strong> ' . htmlspecialchars($subject) . '</p>
                <p style="margin: 5px 0;"><strong>👤 Name:</strong> ' . htmlspecialchars($name) . '</p>
                <p style="margin: 5px 0;"><strong>📧 Email:</strong> <a href="mailto:' . htmlspecialchars($email) . '" style="color: #3498db;">' . htmlspecialchars($email) . '</a></p>
                ' . (!empty($phone) ? '<p style="margin: 5px 0;"><strong>📞 Phone:</strong> ' . htmlspecialchars($phone) . '</p>' : '') . '
                <p style="margin: 5px 0;"><strong>🕒 Received:</strong> ' . date('F j, Y \a\t g:i A') . '</p>
                <p style="margin: 5px 0;"><strong>🆔 Message ID:</strong> #' . $messageId . '</p>
            </div>
            
            <div style="background: #fff; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;">
                <h4 style="color: #2c3e50; margin: 0 0 10px 0;">Message Content:</h4>
                <div style="line-height: 1.6; color: #555;">' . nl2br(htmlspecialchars($message)) . '</div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/admin/messages.php" 
                   style="background: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    📋 View in Admin Panel
                </a>
            </div>
            
            <hr style="border: none; border-top: 1px solid #ecf0f1; margin: 30px 0;">
            <p style="color: #7f8c8d; font-size: 12px; text-align: center; margin: 0;">
                This email was sent from the Demataluva Maha Viddiyalaya contact form.<br>
                Please reply directly to the sender: <a href="mailto:' . htmlspecialchars($email) . '" style="color: #3498db;">' . htmlspecialchars($email) . '</a>
            </p>
        </div>
    </div>';

    // Enhanced auto-reply email
    $replySubject = 'Thank you for contacting Demataluva Maha Viddiyalaya';
    $replyBody = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9;">
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: #2c3e50; margin: 0; font-size: 28px;">🎓 Demataluva Maha Viddiyalaya</h1>
                <p style="color: #7f8c8d; margin: 10px 0 0 0; font-size: 16px;">Empowering minds, building futures</p>
            </div>
            
            <h2 style="color: #2c3e50; margin-bottom: 20px;">Thank You for Your Message!</h2>
            
            <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">
                Dear <strong>' . htmlspecialchars($name) . '</strong>,
            </p>
            
            <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">
                Thank you for reaching out to Demataluva Maha Viddiyalaya. We have successfully received your message regarding <strong>"' . htmlspecialchars($subject) . '"</strong> and our team will review it carefully.
            </p>
            
            <div style="background: #e8f5e8; border-left: 4px solid #27ae60; padding: 15px; margin: 20px 0;">
                <h4 style="color: #27ae60; margin: 0 0 10px 0;">📋 What happens next?</h4>
                <ul style="color: #555; margin: 0; padding-left: 20px;">
                    <li>Our team will review your message within 24 hours</li>
                    <li>We will respond to your inquiry as soon as possible</li>
                    <li>For urgent matters, please call us directly</li>
                </ul>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
                <h4 style="color: #2c3e50; margin: 0 0 15px 0;">📞 Contact Information</h4>
                <p style="margin: 5px 0; color: #555;"><strong>📧 Email:</strong> info@demataluva.mv</p>
                <p style="margin: 5px 0; color: #555;"><strong>📞 Phone:</strong> +960 XXX-XXXX</p>
                <p style="margin: 5px 0; color: #555;"><strong>📍 Address:</strong> Demataluva, Maldives</p>
                <p style="margin: 5px 0; color: #555;"><strong>🕒 Office Hours:</strong> Sunday - Thursday, 8:00 AM - 4:00 PM</p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/" 
                   style="background: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">
                    🏠 Visit Our Website
                </a>
                <a href="' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/about.php" 
                   style="background: #27ae60; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">
                    ℹ️ About Our School
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

    // Send auto-reply
    $replyEmailSent = sendMailMessage($email, $name, $replySubject, $replyBody);
    if (!$replyEmailSent) {
        $emailSuccess = false;
        $emailErrors[] = 'Failed to send auto-reply';
    }

    // Log email status
    if (!$emailSuccess) {
        error_log('Contact form email errors: ' . implode(', ', $emailErrors));
    }

    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your message! We will get back to you within 24 hours.'
    ]);

} catch (PDOException $e) {
    error_log("Contact form error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
}
?>

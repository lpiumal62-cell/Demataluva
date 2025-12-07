<?php
require_once __DIR__ . '/../config/mail.php';

function sendMailMessage(string $toEmail, string $toName, string $subject, string $htmlBody, string $textBody = ''): bool {
	// Try PHPMailer (local files or Composer)
	if (class_exists('PHPMailer\PHPMailer\PHPMailer') || file_exists(__DIR__ . '/../vendor/autoload.php') || file_exists(__DIR__ . '/../PHPMailer.php')) {
		// Load Composer autoloader if available
		if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
			require_once __DIR__ . '/../vendor/autoload.php';
		}
		// Load local PHPMailer files if available
		elseif (file_exists(__DIR__ . '/../PHPMailer.php')) {
			require_once __DIR__ . '/../PHPMailer.php';
			require_once __DIR__ . '/../SMTP.php';
			require_once __DIR__ . '/../Exception.php';
		}
		
		try {
			// Use appropriate PHPMailer class based on what's available
			if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
				$mail = new PHPMailer\PHPMailer\PHPMailer(true);
			} elseif (class_exists('PHPMailer')) {
				/** @var PHPMailer $mail */
				$mail = new PHPMailer();
			} else {
				throw new Exception('PHPMailer not available');
			}
			
			if (MAIL_USE_SMTP) {
				$mail->isSMTP();
				$mail->Host = MAIL_SMTP_HOST;
				$mail->Port = MAIL_SMTP_PORT;
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = MAIL_SMTP_SECURE;
				$mail->Username = MAIL_SMTP_USER;
				$mail->Password = MAIL_SMTP_PASS;
			}
			
			$mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
			$mail->addAddress($toEmail, $toName);
			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $htmlBody;
			$mail->AltBody = $textBody !== '' ? $textBody : strip_tags($htmlBody);
			$mail->send();
			return true;
		} catch (Throwable $e) {
			error_log('PHPMailer error: ' . $e->getMessage());
			// fallthrough to mail()
		}
	}

	// Fallback simple mail()
	$headers = [];
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=UTF-8';
	$headers[] = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_EMAIL . '>';
	return @mail($toEmail, $subject, $htmlBody, implode("\r\n", $headers));
}
?>
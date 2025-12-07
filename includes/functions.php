<?php
require_once __DIR__ . '/../config/database.php';

// Function to get statistics for counters
function getStatistics() {
    global $pdo;
    
    $stats = [
        'total_students' => 0,
        'total_teachers' => 0,
        'total_classes' => 0,
        'total_events' => 0
    ];
    
    try {
        // Get total students
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM students");
        $result = $stmt->fetch();
        $stats['total_students'] = $result['count'];
        
        // Get total teachers
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM teachers");
        $result = $stmt->fetch();
        $stats['total_teachers'] = $result['count'];
        
        // Get total classes
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM classes");
        $result = $stmt->fetch();
        $stats['total_classes'] = $result['count'];
        
        // Get total events
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM events");
        $result = $stmt->fetch();
        $stats['total_events'] = $result['count'];
        
    } catch(PDOException $e) {
        error_log("Error getting statistics: " . $e->getMessage());
    }
    
    return $stats;
}

// Function to get classes with teacher info
function getClassesWithTeachers() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT c.*, t.name as teacher_name, t.subject 
            FROM classes c 
            LEFT JOIN teachers t ON c.teacher_id = t.id 
            ORDER BY c.name
        ");
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error getting classes: " . $e->getMessage());
        return [];
    }
}

// Function to get teachers
function getTeachers() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT * FROM teachers ORDER BY name");
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error getting teachers: " . $e->getMessage());
        return [];
    }
}

// Function to get students by class
function getStudentsByClass($class_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE class_id = ? ORDER BY name");
        $stmt->execute([$class_id]);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error getting students: " . $e->getMessage());
        return [];
    }
}

// Function to get test scores by student
function getTestScoresByStudent($student_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM test_scores WHERE student_id = ? ORDER BY year DESC, subject");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error getting test scores: " . $e->getMessage());
        return [];
    }
}

// Function to get gallery images
function getGalleryImages($limit = null) {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM gallery ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error getting gallery: " . $e->getMessage());
        return [];
    }
}

// Function to get events
function getEvents($limit = null) {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM events ORDER BY date DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error getting events: " . $e->getMessage());
        return [];
    }
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// email
// CSRF token functions
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Simple activity log
function logActivity($action, $description) {
    $logFile = __DIR__ . '/../logs/activity.log';
    $entry = date('Y-m-d H:i:s') . " | $action | $description\n";
    file_put_contents($logFile, $entry, FILE_APPEND);
}

// Function to get parent feedback
function getParentFeedback($limit = null) {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM parent_feedback ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching parent feedback: " . $e->getMessage());
        return [];
    }
}























?>

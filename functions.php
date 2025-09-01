<?php
require_once 'config.php';

// Get personal information
function getPersonalInfo() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM personal_info LIMIT 1");
    return $stmt->fetch();
}

// Get skills by category
function getSkillsByCategory() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM skills ORDER BY category, proficiency DESC");
    $skills = $stmt->fetchAll();
    
    $grouped = [];
    foreach ($skills as $skill) {
        $grouped[$skill['category']][] = $skill;
    }
    return $grouped;
}

// Get education history
function getEducation() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM education ORDER BY start_year DESC");
    return $stmt->fetchAll();
}

// Get work experience
function getExperience() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM experience ORDER BY start_date DESC");
    return $stmt->fetchAll();
}

// Get projects
function getProjects() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

// Get applications by category
function getApplicationsByCategory() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM applications ORDER BY category, name");
    $apps = $stmt->fetchAll();
    
    $grouped = [];
    foreach ($apps as $app) {
        $grouped[$app['category']][] = $app;
    }
    return $grouped;
}

// Handle contact form submission
function submitContactForm($name, $email, $subject, $message) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Get unread contact messages count
function getUnreadMessagesCount() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
    $result = $stmt->fetch();
    return $result['count'];
}

// Mark message as read
function markMessageAsRead($id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    return $stmt->execute([$id]);
}

// Get all contact messages
function getContactMessages($limit = 50) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

// Format date for display
function formatDate($date) {
    if (!$date) return 'Present';
    return date('M Y', strtotime($date));
}

// Format year range
function formatYearRange($start, $end) {
    if (!$end) return $start . ' - Present';
    return $start . ' - ' . $end;
}

// Sanitize output
function sanitizeOutput($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Send email notification (placeholder function)
function sendEmailNotification($to, $subject, $message) {
    // This is a placeholder - implement actual email sending logic
    // You can use PHPMailer, SwiftMailer, or built-in mail() function
    return mail($to, $subject, $message);
}

// Get project technologies as array
function getProjectTechnologies($technologies) {
    return explode(',', $technologies);
}

// Truncate text
function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Get current page
function getCurrentPage() {
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    return $page;
}

// Check if page is active
function isPageActive($pageName) {
    return getCurrentPage() === $pageName;
}

// Generate pagination
function generatePagination($total, $perPage, $currentPage) {
    $totalPages = ceil($total / $perPage);
    $pagination = [];
    
    for ($i = 1; $i <= $totalPages; $i++) {
        $pagination[] = [
            'page' => $i,
            'active' => $i == $currentPage,
            'url' => '?page=' . $i
        ];
    }
    
    return $pagination;
}

// Log activity
function logActivity($action, $details = '') {
    // This is a placeholder - implement logging logic
    $log = date('Y-m-d H:i:s') . " - $action: $details\n";
    file_put_contents('activity.log', $log, FILE_APPEND);
}
?>

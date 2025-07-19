<?php
require '../includes/config.php';
require '../includes/db.php';
require '../includes/header.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Display errors if any
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}

$success = false;
$courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// Get course details
$course = $db->getRow("SELECT * FROM courses WHERE id = ?", [$courseId]);

if (!$course) {
    $_SESSION['error'] = "Course not found";
    header("Location: ../courses.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $company = trim($_POST['company']);
        $position = trim($_POST['position']);
        $employeeCount = trim($_POST['employeeCount']);
        $paymentMethod = trim($_POST['paymentOption']);
        $termsAccepted = isset($_POST['terms']) ? 1 : 0;
        
        // Validate required fields
        if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || 
            empty($company) || empty($position) || empty($paymentMethod) || !$termsAccepted) {
            throw new Exception("All required fields must be filled");
        }

        // Begin transaction
        $db->beginTransaction();
        
        // Insert enrollment
        $sql = "INSERT INTO enrollment (course_id, first_name, last_name, email, phone, company, position, employee_count, payment_method, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $params = [
            $courseId, 
            $firstName, 
            $lastName, 
            $email, 
            $phone, 
            $company, 
            $position, 
            $employeeCount, 
            $paymentMethod
        ];
        
        // Debug output
        error_log("Attempting to execute: $sql with params: ".print_r($params, true));
        
        $enrollmentId = $db->insert($sql, $params);
        
        if (!$enrollmentId) {
            throw new Exception("Failed to create enrollment record");
        }
        
        // Store in session for admin notification
        $_SESSION['new_enrollment'] = [
            'id' => $enrollmentId,
            'course' => $course['title'],
            'name' => "$firstName $lastName"
        ];
        
        $db->commit();
        $success = true;
        
    } catch (Exception $e) {
        $db->rollBack();
        error_log("Enrollment Error: ".$e->getMessage());
        $_SESSION['error'] = "An error occurred: ".$e->getMessage();
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
}
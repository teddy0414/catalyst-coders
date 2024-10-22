<?php
// Database configuration
$host = 'localhost'; // or your database host
$dbname = 'form'; // your database name
$username = 'coders'; // your database username
$password = 'F>S?2F6$K5$k'; // your database password

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log the error and send a generic message
    error_log("Connection failed: " . $e->getMessage(), 0);
    echo "There was an error processing your request. Please try again later.";
    exit;
}

// Sanitize and validate form data
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
$email = isset($_POST['email']) ? filter_var(sanitizeInput($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
$phone = isset($_POST['phone']) ? sanitizeInput($_POST['phone']) : '';
$message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';

// Check if email validation passed
if ($email === false) {
    echo "error: Invalid email address.";
    exit;
}

// Prepare and execute the SQL statement
try {
    $stmt = $conn->prepare("INSERT INTO data (name, email, phone, message) VALUES (:name, :email, :phone, :message)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':message', $message);
    
    $stmt->execute();
    // Send plain text success message
    echo "success";
} catch (PDOException $e) {
    // Log the error and send a generic message
    error_log("Insert failed: " . $e->getMessage(), 0);
    echo "There was an error processing your request. Please try again later.";
}

// Close the connection
$conn = null;
?>

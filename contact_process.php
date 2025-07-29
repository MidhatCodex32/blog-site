<?php
// Database connection
$host = 'localhost';     // Usually localhost
$db = 'your_db_name';
$user = 'your_db_user';
$pass = 'your_db_password';

// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form values
$name      = htmlspecialchars(trim($_POST['name']));
$email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$reason    = htmlspecialchars(trim($_POST['reason']));
$message   = htmlspecialchars(trim($_POST['message']));
$subscribe = isset($_POST['subscribe']) ? "Yes" : "No";

// Save to database
$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, reason, message, subscribed) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $reason, $message, $subscribe);
$stmt->execute();
$stmt->close();

// Send email notification
$to = "your@email.com";  // Replace with your email
$subject = "📨 New Contact Form Message";
$body = "You have received a new message:

Name: $name
Email: $email
Reason: $reason
Message: $message
Subscribed to Newsletter: $subscribe

";

$headers = "From: $email\r\n" .
           "Reply-To: $email\r\n" .
           "X-Mailer: PHP/" . phpversion();

$mailSuccess = mail($to, $subject, $body, $headers);

// Optional redirect
if ($mailSuccess) {
    header("Location: thank-you.html");  // Optional: Create this page
    exit;
} else {
    echo "Something went wrong while sending the email.";
}
?>

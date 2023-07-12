<?php
$email = $_GET['email'];

// Connect to MySQL database
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's health report based on email ID
$sql = "SELECT health_report FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $healthReportPath = $row['health_report'];

    // Send the PDF file to the browser
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($healthReportPath) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    @readfile($healthReportPath);
} else {
    echo "No health report found for the given email ID.";
}

$conn->close();
?>

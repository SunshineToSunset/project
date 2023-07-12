<?php
// Get form values
$name = $_POST['name'];
$age = $_POST['age'];
$weight = $_POST['weight'];
$email = $_POST['email'];

// File handling
$targetDirectory = "uploads/";
$healthReportName = basename($_FILES["healthReport"]["name"]);
$targetFilePath = $targetDirectory . $healthReportName;
$uploadOk = 1;
$fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

// Check if file is a PDF
if ($fileType != "pdf") {
    echo "Only PDF files are allowed.";
    $uploadOk = 0;
}

// Check if file already exists
if (file_exists($targetFilePath)) {
    echo "File already exists.";
    $uploadOk = 0;
}

// Check file size (max 5MB)
if ($_FILES["healthReport"]["size"] > 5 * 1024 * 1024) {
    echo "File size exceeds the maximum limit of 5MB.";
    $uploadOk = 0;
}

// Upload file if everything is ok
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["healthReport"]["tmp_name"], $targetFilePath)) {
        // Connect to MySQL database
        $servername = "localhost";
        $username = "root";
        $password = "password";
        $dbname = "users";
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Escape user input
        $name = mysqli_real_escape_string($conn, $name);
        $age = mysqli_real_escape_string($conn, $age);
        $weight = mysqli_real_escape_string($conn, $weight);
        $email = mysqli_real_escape_string($conn, $email);
        $targetFilePath = mysqli_real_escape_string($conn, $targetFilePath);

        // Insert user details and PDF file path into the database
        $sql = "INSERT INTO users (name, age, weight, email, health_report) VALUES ('$name', '$age', '$weight', '$email', '$targetFilePath')";

        if ($conn->query($sql) === true) {
            echo "User details and PDF file inserted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo "Error uploading file.";
    }
}
?>

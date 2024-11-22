<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $profilePicture = null;

    // Check if the uploads directory exists; if not, create it.
    $targetDir = __DIR__ . "/uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Handle file upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetFile = $targetDir . basename($_FILES['profile_picture']['name']);
        if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                $profilePicture = "uploads/" . basename($_FILES['profile_picture']['name']);
            } else {
                die("Failed to move uploaded file.");
            }
        } else {
            die("File upload error: " . $_FILES['profile_picture']['error']);
        }
    }

    // Check for duplicate email
    $sqlCheck = "SELECT * FROM employees WHERE email = '$email'";
    $result = $conn->query($sqlCheck);
    if ($result->num_rows > 0) {
        die("Error: The email '$email' is already in use.");
    }

    // Insert employee data into the database
    $sql = "INSERT INTO employees (name, email, department, profile_picture) VALUES ('$name', '$email', '$department', '$profilePicture')";
    if ($conn->query($sql)) {
        header("Location: index.php");
    } else {
        die("Database Error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
</head>
<body>
    <h1>Add New Employee</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Department:</label>
        <input type="text" name="department" required><br>
        <label>Profile Picture:</label>
        <input type="file" name="profile_picture" accept="image/*"><br>
        <button type="submit">Save</button>
    </form>
</body>
</html>

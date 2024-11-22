<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM employees WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $employee = $result->fetch_assoc();
    } else {
        die("Record not found!");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $profilePicture = $employee['profile_picture']; // Keep the existing picture by default.

    // Handle file upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = __DIR__ . "/uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . basename($_FILES['profile_picture']['name']);
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
            // Delete the old file if it exists
            if (file_exists(__DIR__ . "/" . $employee['profile_picture'])) {
                unlink(__DIR__ . "/" . $employee['profile_picture']);
            }
            $profilePicture = "uploads/" . basename($_FILES['profile_picture']['name']);
        } else {
            die("Failed to upload new profile picture.");
        }
    }

    // Update the record in the database
    $sqlUpdate = "UPDATE employees SET name = '$name', email = '$email', department = '$department', profile_picture = '$profilePicture' WHERE id = $id";
    if ($conn->query($sqlUpdate)) {
        header("Location: index.php");
    } else {
        die("Error updating record: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
</head>
<body>
    <h1>Edit Employee</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $employee['name']; ?>" required><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $employee['email']; ?>" required><br>
        <label>Department:</label>
        <input type="text" name="department" value="<?php echo $employee['department']; ?>" required><br>
        <label>Profile Picture:</label>
        <input type="file" name="profile_picture" accept="image/*"><br>
        <?php if (!empty($employee['profile_picture'])): ?>
            <img src="<?php echo $employee['profile_picture']; ?>" width="100" alt="Profile Picture"><br>
        <?php endif; ?>
        <button type="submit">Update</button>
    </form>
</body>
</html>

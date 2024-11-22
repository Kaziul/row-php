<?php
include 'db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM employees WHERE id = $id";
    $result = $conn->query($sql);
    $employee = $result->fetch_assoc();

    if ($employee) {
        // Define the uploads directory path
        $uploadsDir = __DIR__ . '/uploads/';

        // Delete profile picture if it exists in the uploads folder
        if (!empty($employee['profile_picture'])) {
            $filePath = $uploadsDir . basename($employee['profile_picture']);
            if (file_exists($filePath)) {
                if (!unlink($filePath)) {
                    echo "Warning: Failed to delete profile picture.";
                }
            } else {
                echo "File not found in the uploads folder.";
            }
        }

        // Delete the database record
        $deleteSql = "DELETE FROM employees WHERE id = $id";
        $deleteStmt = $conn->prepare($deleteSql);
        if ($deleteStmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Employee not found.";
    }

} else {
    echo "Invalid ID.";
}

$conn->close();

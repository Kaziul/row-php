<?php
include 'db.php';

$result = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Demo</title>
</head>
<body>
    <h1>Employees</h1>
    <a href="create.php">Add New Employee</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Profile Picture</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td>
                        <?php if ($row['profile_picture']): ?>
                            <img src="<?= $row['profile_picture'] ?>" alt="Profile Picture" width="50">
                        <?php else: ?>
                            No Picture
                        <?php endif; ?>
                    </td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['department'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

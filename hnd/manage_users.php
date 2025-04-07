<?php
include 'db_connection.php';

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%'";
$result = $conn->query($sql);

// Handle activation toggle
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $user = $conn->query("SELECT active FROM users WHERE id=$id")->fetch_assoc();
    $newStatus = $user['active'] ? 0 : 1;
    $conn->query("UPDATE users SET active=$newStatus WHERE id=$id");
    header("Location: manage_users.php");
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: manage_users.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        a { padding: 5px 10px; text-decoration: none; }
        .edit { background-color: blue; color: white; }
        .delete { background-color: red; color: white; }
        .activate { background-color: green; color: white; }
        .deactivate { background-color: gray; color: white; }
    </style>
</head>
<body>

<h2>Manage Users</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Search users..." value="<?= $search ?>">
    <button type="submit">Search</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) : ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['role'] ?></td>
        <td><?= $row['active'] ? 'Active' : 'Inactive' ?></td>
        <td>
            <a class="edit" href="edit_user.php?id=<?= $row['id'] ?>">Edit</a>
            <a class="<?= $row['active'] ? 'deactivate' : 'activate' ?>" 
               href="manage_users.php?toggle=<?= $row['id'] ?>">
                <?= $row['active'] ? 'Deactivate' : 'Activate' ?>
            </a>
            <a class="delete" href="manage_users.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

<?php
// Start session
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Database connection
$db_file = realpath(dirname(__FILE__) . '/users.db');

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve admin data and user data from the database
    $admin_id = $_SESSION['user_id'];
    $sql = "SELECT username, email FROM users WHERE id = :admin_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $admin_name = $row["username"];
        $admin_email = $row["email"];
    } else {
        // Handle error
        $error = "Error retrieving admin data.";
    }

    // Retrieve list of users
    $sql = "SELECT id, username, email FROM users";
    $stmt = $conn->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle database connection error
    $error = "Database error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="index.html" class="text-xl font-bold text-gray-800">Kynlos Login Script</a>
                </div>
                <div class="flex items-center">
                    <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Admin Panel</h2>
        <p class="text-gray-600 mb-4">Admin: <?php echo $admin_name; ?> (<?php echo $admin_email; ?>)</p>

        <h3 class="text-2xl font-bold text-gray-800 mb-2">Users List</h3>
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6">ID</th>
                        <th class="py-3 px-6">Username</th>
                        <th class="py-3 px-6">Email</th>
                        <th class="py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php foreach ($users as $row) { ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6"><?php echo $row["id"]; ?></td>
                            <td class="py-3 px-6"><?php echo $row["username"]; ?></td>
                            <td class="py-3 px-6"><?php echo $row["email"]; ?></td>
                            <td class="py-3 px-6">
                                <a href="edit_user.php?id=<?php echo $row["id"]; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <a href="delete_user.php?id=<?php echo $row["id"]; ?>" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-6 text-center">
            &copy; <?php echo date('Y'); ?> Kynlos Login Script. All rights reserved.
        </div>
    </footer>
</body>
</html>

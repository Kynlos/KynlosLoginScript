<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$db_file = realpath(dirname(__FILE__) . '/users.db');

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve messages from the database
    $sql = "SELECT username, message FROM messages ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Public Messages</title>
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
                    <a href="dashboard.php" class="mx-2 text-gray-600 hover:text-gray-800">Dashboard</a>
                    <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Public Messages</h2>

        <?php if (isset($error)) { ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php } ?>

        <?php if (!empty($messages)) { ?>
            <div class="bg-white shadow-md rounded-lg p-6">
                <?php foreach ($messages as $message) { ?>
                    <div class="mb-4">
                        <p class="font-bold"><?php echo $message['username']; ?></p>
                        <p><?php echo $message['message']; ?></p>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="text-gray-600">No messages found.</p>
        <?php } ?>
    </main>

    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-6 text-center">
            &copy; <?php echo date('Y'); ?> Kynlos Login Script. All rights reserved.
        </div>
    </footer>
</body>
</html>

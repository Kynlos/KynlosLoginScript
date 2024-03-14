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

    // Retrieve user data from the database
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT username, email FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $user_name = $row["username"];
        $user_email = $row["email"];
    } else {
        // Handle error
        $error = "Error retrieving user data.";
    }
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
    <title>User Dashboard</title>
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
                    <a href="profile.php" class="mx-2 text-gray-600 hover:text-gray-800">Profile</a>
                    <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome, <?php echo $user_name; ?>!</h2>
        <p class="text-gray-600 mb-4">Email: <?php echo $user_email; ?></p>

        <!-- Message submission form -->
        <form action="submit_message.php" method="post" class="mb-8">
            <div class="mb-4">
                <label for="message" class="block text-gray-700 font-bold mb-2">Message:</label>
                <textarea id="message" name="message" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit Message</button>
        </form>

        <?php
        // Display success message if the message was sent successfully
        if (isset($_GET['success']) && $_GET['success'] === 'true') {
            echo '<p class="text-green-500 mb-4">Your message has been sent successfully!</p>';
        }
        ?>

        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">My Messages</h3>
            <a href="user_messages.php" class="text-blue-500 hover:text-blue-700">View and Manage My Messages</a>
        </div>

        <a href="public.php" class="text-blue-500 hover:text-blue-700">View Public Messages</a>
    </main>

    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-6 text-center">
            &copy; <?php echo date('Y'); ?> Kynlos Login Script. All rights reserved.
        </div>
    </footer>
</body>
</html>

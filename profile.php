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
$conn = new SQLite3($db_file);

// Retrieve user data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email FROM users WHERE id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $user_name = $row["username"];
    $user_email = $row["email"];
} else {
    // Handle error
    $error = "Error retrieving user data.";
}

$stmt->close();

// Handle profile update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST["username"];
    $new_email = $_POST["email"];

    // Validate inputs
    if (empty($new_username) || empty($new_email)) {
        $error = "Username and email are required.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Update user data in the database
        $sql = "UPDATE users SET username = :new_username, email = :new_email WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':new_username', $new_username, SQLITE3_TEXT);
        $stmt->bindValue(':new_email', $new_email, SQLITE3_TEXT);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
            $user_name = $new_username;
            $user_email = $new_email;
        } else {
            $error = "Error updating profile: " . $conn->lastErrorMsg();
        }

        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="index.html" class="text-xl font-bold text-gray-800">My Web App</a>
                </div>
                <div class="flex items-center">
                    <a href="dashboard.php" class="mx-2 text-gray-600 hover:text-gray-800">Dashboard</a>
                    <a href="logout.php" class="mx-2 text-gray-600 hover:text-gray-800">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">User Profile</h2>

            <?php if (isset($error)) { ?>
                <p class="text-red-500 mb-4"><?php echo $error; ?></p>
            <?php } ?>

            <?php if (isset($success)) { ?>
                <p class="text-green-500 mb-4"><?php echo $success; ?></p>
            <?php } ?>

            <p class="text-gray-600 mb-4">Username: <?php echo $user_name; ?></p>
            <p class="text-gray-600 mb-4">Email: <?php echo $user_email; ?></p>

            <h3 class="text-2xl font-bold text-gray-800 mb-2">Update Profile</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $user_name; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $user_email; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-6 text-center">
            &copy; <?php echo date('Y'); ?> Kynlos Login Script. All rights reserved.
        </div>
    </footer>
</body>
</html>

<?php
session_start(); // Start the session

// Check if the user is already logged in
if (isset($_SESSION['username']) && $_SESSION['username'] == 'admin1234') {
    // If already logged in, redirect to the dashboard
    header('Location: dash.php');
    exit();
}

// Process the login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are correct
    if ($username == 'admin1234' && $password == 'adminpass') {
        // If correct, store the session
        $_SESSION['username'] = $username;
        // Redirect to the dashboard
        header('Location: dash.php');
        exit();
    } else {
        // If invalid, show an error message
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center mb-4">Admin Login</h2>
        
        <?php if (isset($error)) { ?>
            <div class="bg-red-400 text-white p-2 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <form action="index.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <div class="mb-4 text-center">
                <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Login</button>
            </div>
        </form>
    </div>

</body>
</html>

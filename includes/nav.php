<?php include('./fun/db.php'); ?>

<?php
// Start the session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Query to check promo eligibility
    $query = "SELECT promo_used FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($promoUsed);
        $stmt->fetch();
    } else {
        echo "<p>Error: User not found.</p>";
        exit;
    }
}
?>
<!-- Navigation Bar -->
<nav class="top-0 z-10 w-full flex flex-col justify-center items-center">
    <div class="w-full flex flex-col justify-center items-center">
        <!-- Promo Message -->
        <?php if (isset($promoUsed) && $promoUsed == 0): ?>
            <div class="flex items-center space-x-2 text-green-500">
                <i class="ri-check-fill text-xl"></i>
                <span class="text-sm">50% off discount promo is available! Book a futsal and get the discount.</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="w-full md:px-6 py-4 flex justify-between items-center">
        <!-- Logo Section -->
        <a class="relative" href="./index.php">
            <img src="./assets/images/logo.gif" alt="Futsal Logo" class="w-48 h-auto">
            <p class="absolute left-8 md:bottom-4 bottom-2 inset-0 flex items-center justify-center text-yellow-400 md:text-2xl text-xl font-bold">GOAL7</p>
        </a>

        <!-- Navigation Links -->
        <div class="flex items-center space-x-4 text-white">
            <!-- Login Button -->
        <a href="futsals.php" class="px-4 py-2 text-yellow-500  hover:text-yellow-900 hover:text-white transition">
            Book
        </a>
            <!-- Account Icon with Dropdown -->
            <?php if (isset($_SESSION['username'])): ?>

            <div class="relative">
                <!-- Account Button -->
                <button id="accountButton" class="flex items-center space-x-2 hover:text-yellow-300">
                    <i class="ri-account-circle-line text-2xl"></i>
                </button>

                <!-- Floating Dropdown -->
                <div id="accountDropdown" class="hidden absolute right-0 mt-2 w-40 bg-gray-700 text-white rounded-lg shadow-lg">
                    <ul class="py-2">
                            <!-- If user is logged in, show Profile, Dashboard, Logout -->
                            <li class="px-4 py-2 hover:bg-gray-600 cursor-pointer">
                                <a href="profile.php">Profile</a>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-600 cursor-pointer">
                                <a href="dash.php">Dashboard</a>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-600 cursor-pointer">
                                <a href="logout.php">Logout</a>
                            </li>
                        
                    </ul>
                </div>
            </div>
            <?php else: ?>
                <div class="flex space-x-4">
        <!-- Login Button -->
        <a href="log.php" class="px-4 py-2 text-yellow-500 border border-yellow-500 rounded hover:bg-yellow-500 hover:text-white transition">
            Login
        </a>

        <!-- Join Button -->
        <a href="join.php" class="px-4 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600 transition">
            Join
        </a>
    </div>
                        <?php endif; ?>
            <!-- Show Help and Notifications only if the user is logged in -->
            <?php if (isset($_SESSION['username'])): ?>
                <!-- Help Icon -->
                <button class="flex items-center space-x-2 hover:text-yellow-300">
                    <i class="ri-question-line text-2xl"></i>
                </button>

                <!-- Notifications Icon -->
                <button class="flex items-center space-x-2 hover:text-yellow-300">
                    <i class="ri-notification-line text-2xl"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- JavaScript for toggling dropdown -->
<script>
    // Get the button and dropdown elements
    const accountButton = document.getElementById('accountButton');
    const accountDropdown = document.getElementById('accountDropdown');

    // Toggle the visibility of the dropdown when the account button is clicked
    accountButton.addEventListener('click', () => {
        accountDropdown.classList.toggle('hidden');
    });

    // Close the dropdown if clicked outside of it
    window.addEventListener('click', (event) => {
        if (!accountButton.contains(event.target) && !accountDropdown.contains(event.target)) {
            accountDropdown.classList.add('hidden');
        }
    });
</script>

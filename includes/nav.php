
<!-- Navigation Bar -->
<nav class="top-0 z-10 w-full" >
    <div class="max-w-screen-xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo Section -->
        <a class="relative" href="./index.php">
            <!-- Futsal Logo -->
            <img src="./assets/images/logo.gif" alt="Futsal Logo" class="w-48 h-auto">
            
            <!-- Centered Text -->
            <p class="absolute left-8 bottom-4 inset-0 flex items-center justify-center text-yellow-400 text-2xl font-bold">GOAL7</p>
        </a>

        <!-- Navigation Links -->
        <div class="flex items-center space-x-6 text-white">
            <!-- Account Icon with Dropdown -->
            <div class="relative">
                <!-- Account Button -->
                <button id="accountButton" class="flex items-center space-x-2 hover:text-yellow-300">
                    <i class="ri-account-circle-line text-2xl"></i>
                </button>

                <!-- Floating Dropdown -->
                <div id="accountDropdown" class="hidden absolute right-0 mt-2 w-40 bg-gray-700 text-white rounded-lg shadow-lg">
                    <ul class="py-2">
                        <?php if (isset($_SESSION['username'])): ?>
                            <!-- If user is logged in, show Profile, Dashboard, Logout -->
                            <li class="px-4 py-2 hover:bg-gray-600 cursor-pointer">
                                <a href="profile.php">Profile</a>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-600 cursor-pointer">
                                <a href="dashboard.php">Dashboard</a>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-600 cursor-pointer">
                                <a href="logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <!-- If user is not logged in, show Login and Join -->
                            <li class="px-4 py-2 hover:bg-gray-600 cursor-pointer">
                                <a href="log.php">Login</a>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-600 cursor-pointer">
                                <a href="join.php">Join</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <?php if (isset($_SESSION['username'])): ?>
                <!-- Show Help and Notifications only if the user is logged in -->
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

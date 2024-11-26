<?php
// nav.php
session_start();

// Check if the user is logged in (session check)
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin1234') {
    // If not logged in, redirect to login page
    header('Location: ../index.php');
    exit();
}
?>

<!-- Navigation Bar -->
<nav class="bg-slate-700 text-white p-4 rounded-lg w-full">
    <div class="flex justify-between items-center">
        <a href="dash.php" class="text-xl font-bold">Admin Panel</a>
        <div class="flex space-x-6">
            <a href="customers.php" class="flex items-center space-x-2">
                <i class="ri-user-line text-2xl"></i>
                <span>Manage Customers</span>
            </a>
            <!-- <a href="payments.php" class="flex items-center space-x-2">
                <i class="ri-money-dollar-circle-line text-2xl"></i>
                <span>Manage Payments</span>
            </a> -->
            <a href="clients.php" class="flex items-center space-x-2">
                <i class="ri-user-add-line text-2xl"></i>
                <span>Manage Clients</span>
            </a>
            <a href="bookings.php" class="flex items-center space-x-2">
                <i class="ri-bookmark-line text-2xl"></i>
                <span>Manage Bookings</span>
            </a>
            <a href="logout.php" class="flex items-center space-x-2">
                <i class="ri-logout-circle-line text-2xl"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</nav>

<?php
// end of nav.php
?>

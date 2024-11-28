<?php
// nav.php
// session_start();


?>

<!-- Navigation Bar -->
<nav class="bg-slate-700 text-white p-4 rounded-lg w-full">
    <div class="flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold">Client Panel</a>
        <div class="flex space-x-6">
            <!-- <a href="payments.php" class="flex items-center space-x-2">
                <i class="ri-money-dollar-circle-line text-2xl"></i>
                <span>Manage Payments</span>
            </a> -->
            <!-- <a href="clients.php" class="flex items-center space-x-2">
                <i class="ri-user-add-line text-2xl"></i>
                <span>Manage Clients</span>
            </a> -->
            <a href="post_court.php" class="flex items-center space-x-2">
                <i class="ri-bookmark-line text-2xl"></i>
                <span>Post Futsals</span>
            </a>
            <a href="../profile.php" class="flex items-center space-x-2">
                <i class="ri-settings-line text-2xl"></i>
                <span>Profile</span>
            </a>
            <a href="../index.php" class="flex items-center space-x-2">
                <i class="ri-home-line text-2xl"></i>
                <span>Home</span>
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

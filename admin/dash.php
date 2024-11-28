<?php
include('../includes/header.php');
include('../fun/db.php');

// Fetch total bookings count (from futsal_court_slots)
$result = $conn->query("SELECT COUNT(*) AS totalBookings FROM futsal_court_slots");
$totalBookings = $result->fetch_assoc()['totalBookings'];

// Fetch total users count (from users table)
$result = $conn->query("SELECT COUNT(*) AS totalUsers FROM users");
$totalUsers = $result->fetch_assoc()['totalUsers'];

// Fetch users by role counts (client and customer)
$result = $conn->query("SELECT role, COUNT(*) AS roleCount FROM users GROUP BY role");
$roleCounts = [];
while ($row = $result->fetch_assoc()) {
    $roleCounts[$row['role']] = $row['roleCount'];
}
$clientsCount = $roleCounts['client'] ?? 0;
$customersCount = $roleCounts['customer'] ?? 0;

// Pagination setup for payments table
$limit = 10;
$page_payments = isset($_GET['page_payments']) ? (int)$_GET['page_payments'] : 1;
$offset_payments = ($page_payments - 1) * $limit;
$totalPaymentsQuery = $conn->query("SELECT COUNT(*) AS count FROM payments");
$totalPaymentsCount = $totalPaymentsQuery->fetch_assoc()['count'];
$totalPaymentPages = ceil($totalPaymentsCount / $limit);

// Fetch payments for the current page
$paymentsQuery = $conn->query("SELECT * FROM payments LIMIT $limit OFFSET $offset_payments");
?>
<?php include('./nav.php'); ?>

<!-- Main Content - Analysis Cards -->
<section class="relative md:w-3/4 flex justify-center items-center bg-slate-500 rounded-3xl p-4">
    <div class="flex md:flex-row flex-col gap-8 justify-center items-center w-full">
        <div class="w-[200px] h-[80px] p-4 flex items-center justify-center text-black">
            <p class="text-lg">Hello, Admin!</p>
        </div>
        <div class="w-[200px] h-[80px] bg-gradient-to-r from-green-500 via-yellow-500 to-orange-500 rounded-lg p-4 flex items-center justify-center text-white">
            <?php
                // Fetch total number of futsal courts from the database
                $totalFutsalsResult = $conn->query("SELECT COUNT(*) AS total FROM futsal_courts");
                $totalFutsals = $totalFutsalsResult->fetch_assoc()['total'];
                ?>
            <div class="flex flex-col items-center">
                <i class="ri-bookmark-line text-2xl"></i>
                <p class="text-lg"><?php echo $totalFutsals; ?> Futsal Courts</p>
            </div>
        </div>
        <div class="w-[200px] h-[80px] bg-gradient-to-r from-green-500 via-yellow-500 to-orange-500 rounded-lg p-4 flex items-center justify-center text-white">
            <div class="flex flex-col items-center">
                <i class="ri-bookmark-line text-2xl"></i>
                <p class="text-lg"><?php echo $totalBookings; ?> Bookings</p>
            </div>
        </div>

        <div class="w-[200px] h-[80px] bg-gradient-to-r from-blue-500 via-teal-500 to-lime-500 rounded-lg p-4 flex items-center justify-center text-white">
            <div class="flex flex-col items-center">
                <i class="ri-user-line text-2xl"></i>
                <p class="text-lg"><?php echo $totalUsers; ?> Users</p>
            </div>
        </div>

        <div class="w-[200px] h-[80px] bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 rounded-lg p-4 flex items-center justify-center text-white">
            <div class="flex flex-col items-center">
                <i class="ri-user-line text-2xl"></i>
                <p class="text-lg"><?php echo $clientsCount; ?> Clients</p>
            </div>
        </div>

        <div class="w-[200px] h-[80px] bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500 rounded-lg p-4 flex items-center justify-center text-white">
            <div class="flex flex-col items-center">
                <i class="ri-user-line text-2xl"></i>
                <p class="text-lg"><?php echo $customersCount; ?> Customers</p>
            </div>
        </div>
    </div>
</section>

<!-- Payments Table Section -->
<section class="relative w-full p-4 bg-slate-400 rounded-3xl mt-8">
    <h2 class="text-2xl font-bold text-white mb-4">Payment Details</h2>

    <div class="w-full overflow-x-auto">
        <table class="w-full table-auto text-white">
            <thead>
                <tr class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                    <th class="p-2">Payment ID</th>
                    <th class="p-2">Method</th>
                    <th class="p-2">Amount</th>
                    <th class="p-2">Username</th>
                    <th class="p-2">Futsal ID</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowColors = ['bg-gray-800', 'bg-gray-600'];
                $i = 0;
                while ($payment = $paymentsQuery->fetch_assoc()) {
                    $rowClass = $rowColors[$i % 2];
                    ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td class="p-2"><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($payment['method']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($payment['amount']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($payment['username']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($payment['futsal_id']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($payment['status']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($payment['created_at']); ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination for Payments -->
    <div class="flex justify-center mt-4">
        <nav class="flex space-x-4">
            <?php if ($page_payments > 1): ?>
                <a href="?page_payments=<?php echo $page_payments - 1; ?>" class="text-yellow-300">Previous</a>
            <?php endif; ?>

            <span class="text-white">Page <?php echo $page_payments; ?> of <?php echo $totalPaymentPages; ?></span>

            <?php if ($page_payments < $totalPaymentPages): ?>
                <a href="?page_payments=<?php echo $page_payments + 1; ?>" class="text-yellow-300">Next</a>
            <?php endif; ?>
        </nav>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

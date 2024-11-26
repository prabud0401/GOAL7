<?php
include('../includes/header.php');
include('../fun/db.php');

// Pagination setup for customers table
$page_customers = isset($_GET['page_customers']) ? (int)$_GET['page_customers'] : 1;
$offset_customers = ($page_customers - 1) * 10;
$totalCustomersQuery = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'customer'");
$totalCustomersCount = $totalCustomersQuery->fetch_assoc()['count'];
$totalCustomerPages = ceil($totalCustomersCount / 10);

// Fetch customers for the current page
$customersQuery = $conn->query("SELECT * FROM users WHERE role = 'customer' LIMIT 10 OFFSET $offset_customers");
?>
<?php include('./nav.php'); ?>

<section class="relative w-full p-4 bg-slate-400 rounded-3xl mt-8">
    <h2 class="text-2xl font-bold text-white mb-4">Customers Details</h2>

    <div class="w-full overflow-x-auto">
        <table class="w-full table-auto text-white">
            <thead>
                <tr class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                    <th class="p-2">Customer ID</th>
                    <th class="p-2">Username</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Phone</th>
                    <th class="p-2">Address</th>
                    <th class="p-2">Verified</th>
                    <th class="p-2">Creation Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($customer = $customersQuery->fetch_assoc()) {
                    ?>
                    <tr class="bg-gray-800">
                        <td class="p-2"><?php echo htmlspecialchars($customer['id']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($customer['username']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($customer['phone']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($customer['address']); ?></td>
                        <td class="p-2"><?php echo $customer['verified'] ? 'Verified' : 'Not Verified'; ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($customer['created_at']); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        <nav class="flex space-x-4">
            <?php if ($page_customers > 1): ?>
                <a href="?page_customers=<?php echo $page_customers - 1; ?>" class="text-yellow-300">Previous</a>
            <?php endif; ?>

            <span class="text-white">Page <?php echo $page_customers; ?> of <?php echo $totalCustomerPages; ?></span>

            <?php if ($page_customers < $totalCustomerPages): ?>
                <a href="?page_customers=<?php echo $page_customers + 1; ?>" class="text-yellow-300">Next</a>
            <?php endif; ?>
        </nav>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

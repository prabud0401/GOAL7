<?php
include('../includes/header.php');
include('../fun/db.php');

// Pagination setup for clients table
$page_clients = isset($_GET['page_clients']) ? (int)$_GET['page_clients'] : 1;
$offset_clients = ($page_clients - 1) * 10;
$totalClientsQuery = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'client'");
$totalClientsCount = $totalClientsQuery->fetch_assoc()['count'];
$totalClientPages = ceil($totalClientsCount / 10);

// Fetch clients for the current page
$clientsQuery = $conn->query("SELECT * FROM users WHERE role = 'client' LIMIT 10 OFFSET $offset_clients");
?>
<?php include('./nav.php'); ?>

<section class="relative w-full p-4 bg-slate-400 rounded-3xl mt-8">
    <h2 class="text-2xl font-bold text-white mb-4">Clients Details</h2>

    <div class="w-full overflow-x-auto">
        <table class="w-full table-auto text-white">
            <thead>
                <tr class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                    <th class="p-2">Client ID</th>
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
                while ($client = $clientsQuery->fetch_assoc()) {
                    ?>
                    <tr class="bg-gray-800">
                        <td class="p-2"><?php echo htmlspecialchars($client['id']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($client['username']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($client['email']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($client['phone']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($client['address']); ?></td>
                        <td class="p-2"><?php echo $client['verified'] ? 'Verified' : 'Not Verified'; ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($client['created_at']); ?></td>
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
            <?php if ($page_clients > 1): ?>
                <a href="?page_clients=<?php echo $page_clients - 1; ?>" class="text-yellow-300">Previous</a>
            <?php endif; ?>

            <span class="text-white">Page <?php echo $page_clients; ?> of <?php echo $totalClientPages; ?></span>

            <?php if ($page_clients < $totalClientPages): ?>
                <a href="?page_clients=<?php echo $page_clients + 1; ?>" class="text-yellow-300">Next</a>
            <?php endif; ?>
        </nav>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

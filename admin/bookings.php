<?php
include('../includes/header.php');
include('../fun/db.php');

// Pagination setup for bookings table (Bookings)
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$totalBookingsQuery = $conn->query("SELECT COUNT(*) AS count FROM futsal_court_slots");
$totalBookingsCount = $totalBookingsQuery->fetch_assoc()['count'];
$totalPages = ceil($totalBookingsCount / $limit);

// Fetch bookings for the current page
$bookingsQuery = $conn->query("SELECT * FROM futsal_court_slots LIMIT $limit OFFSET $offset");
?>
<?php include('./nav.php'); ?>

<section class="relative w-full p-4 bg-slate-400 rounded-3xl mt-8">
    <h2 class="text-2xl font-bold text-white mb-4">Booking Details</h2>

    <div class="w-full overflow-x-auto">
        <table class="w-full table-auto text-white">
            <thead>
                <tr class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                    <th class="p-2">Booking Code</th>
                    <th class="p-2">Username</th>
                    <th class="p-2">Slot Hour</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Payment ID</th>
                    <th class="p-2">Booking Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowColors = ['bg-gray-800', 'bg-gray-600'];
                $i = 0;
                while ($booking = $bookingsQuery->fetch_assoc()) {
                    $rowClass = $rowColors[$i % 2];
                    ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td class="p-2"><?php echo htmlspecialchars($booking['id']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($booking['booked_by']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($booking['slot_hour']); ?></td>
                        <td class="p-2"><?php echo $booking['is_booked'] ? 'Booked' : 'Available'; ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($booking['payment_id']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($booking['slot_date']); ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        <nav class="flex space-x-4">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="text-yellow-300">Previous</a>
            <?php endif; ?>

            <span class="text-white">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="text-yellow-300">Next</a>
            <?php endif; ?>
        </nav>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

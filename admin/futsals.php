<?php
include('../includes/header.php');
include('../fun/db.php');

// Pagination setup
$records_per_page = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page (default to 1 if not set)
$offset = ($page - 1) * $records_per_page; // Offset for SQL query

// Fetch total number of futsal courts
$total_results = $conn->query("SELECT COUNT(*) AS total FROM futsal_courts");
$total_rows = $total_results->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $records_per_page); // Total pages

// Fetch futsal courts for the current page
$result = $conn->query("SELECT * FROM futsal_courts LIMIT $records_per_page OFFSET $offset");
$futsals = [];
while ($row = $result->fetch_assoc()) {
    $futsals[] = $row;
}

// Handle availability status update
if (isset($_GET['update_availability']) && isset($_GET['futsal_id'])) {
    $futsalId = (int)$_GET['futsal_id'];
    $newStatus = ($_GET['update_availability'] == '1') ? 1 : 0;
    $updateQuery = $conn->prepare("UPDATE futsal_courts SET availability_status = ? WHERE id = ?");
    $updateQuery->bind_param("ii", $newStatus, $futsalId);
    $updateQuery->execute();
    header("Location: ".$_SERVER['PHP_SELF']."?page=".$page); // Redirect to current page
    exit;
}

// Handle delete request
if (isset($_GET['delete_futsal_id'])) {
    $futsalIdToDelete = (int)$_GET['delete_futsal_id'];
    $deleteQuery = $conn->prepare("DELETE FROM futsal_courts WHERE id = ?");
    $deleteQuery->bind_param("i", $futsalIdToDelete);
    $deleteQuery->execute();
    header("Location: ".$_SERVER['PHP_SELF']."?page=".$page); // Redirect to current page after deletion
    exit;
}
?>

<?php include('./nav.php'); ?>

<!-- Main Content - Futsal Courts List -->
<section class="relative md:w-3/4 flex justify-center items-center bg-slate-500 rounded-3xl p-4">
    <div class="flex md:flex-row flex-col gap-8 justify-center items-center w-full">
        <div class="w-[200px] h-[80px] p-4 flex items-center justify-center text-black">
            <p class="text-lg">Futsal Courts</p>
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
    </div>
</section>

<!-- Futsal Courts Table Section -->
<section class="relative w-full p-4 bg-slate-400 rounded-3xl mt-8">
    <h2 class="text-2xl font-bold text-white mb-4">Futsal Courts List</h2>

    <div class="w-full overflow-x-auto">
        <table class="w-full table-auto text-white">
            <thead>
                <tr class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                    <th class="p-2">Court ID</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Location</th>
                    <th class="p-2">Price/Hour</th>
                    <th class="p-2">Max Players</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Update Availability</th>
                    <th class="p-2">Delete</th> <!-- Delete column -->
                </tr>
            </thead>
            <tbody>
                <?php
                $rowColors = ['bg-gray-800', 'bg-gray-600'];
                $i = 0;
                foreach ($futsals as $futsal) {
                    $rowClass = $rowColors[$i % 2];
                    ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td class="p-2"><?php echo htmlspecialchars($futsal['id']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($futsal['name']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($futsal['location']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($futsal['price_per_hour']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($futsal['max_players']); ?></td>
                        <td class="p-2">
                            <?php echo ($futsal['availability_status'] == 1) ? 'Available' : 'Not Available'; ?>
                        </td>
                        <td class="p-2">
                            <?php if ($futsal['availability_status'] == 1): ?>
                                <a href="?update_availability=0&futsal_id=<?php echo $futsal['id']; ?>&page=<?php echo $page; ?>" class="text-red-500">Mark as Not Available</a>
                            <?php else: ?>
                                <a href="?update_availability=1&futsal_id=<?php echo $futsal['id']; ?>&page=<?php echo $page; ?>" class="text-green-500">Mark as Available</a>
                            <?php endif; ?>
                        </td>
                        <td class="p-2">
                            <a href="?delete_futsal_id=<?php echo $futsal['id']; ?>&page=<?php echo $page; ?>" class="text-red-500">Delete</a>
                        </td> <!-- Delete button -->
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Pagination Section -->
<section class="mt-8">
    <div class="flex justify-center">
        <ul class="flex gap-2">
            <!-- Previous Page Link -->
            <?php if ($page > 1): ?>
                <li><a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Previous</a></li>
            <?php else: ?>
                <li><span class="px-4 py-2 bg-gray-400 text-white rounded-lg">Previous</span></li>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li>
                    <a href="?page=<?php echo $i; ?>" class="px-4 py-2 <?php echo ($i == $page) ? 'bg-blue-600' : 'bg-blue-400'; ?> text-white rounded-lg">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Next Page Link -->
            <?php if ($page < $total_pages): ?>
                <li><a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Next</a></li>
            <?php else: ?>
                <li><span class="px-4 py-2 bg-gray-400 text-white rounded-lg">Next</span></li>
            <?php endif; ?>
        </ul>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

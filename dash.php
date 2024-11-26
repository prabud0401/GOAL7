<?php include('./includes/header.php'); ?>
<?php include('./includes/nav.php'); ?>

<?php


// Fetch user details from the database based on the session username
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    // If user data is not found, handle the error
    echo "<p>User not found!</p>";
    exit();
}

// Get total bookings (promo_count) for the user
$totalBookings = $user['promo_count'];

// Account verification status
$verificationStatus = $user['verified'] ? 'Verified' : 'Not Verified';

// User role
$userRole = $user['role'];

// Pagination Setup
$limit = 10; // Number of bookings per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get the total number of bookings
$queryTotalBookings = "SELECT COUNT(*) AS total FROM futsal_court_slots WHERE booked_by = ?";
$stmtTotal = $conn->prepare($queryTotalBookings);
$stmtTotal->bind_param("s", $username);
$stmtTotal->execute();
$totalBookingsResult = $stmtTotal->get_result()->fetch_assoc();
$totalBookingsCount = $totalBookingsResult['total'];
$stmtTotal->close();

// Fetch the user's bookings along with futsal court names, payment details
$queryBookings = "
    SELECT 
        fcs.slot_date, 
        fcs.slot_hour, 
        fcs.payment_id, 
        fcs.is_booked, 
        fc.name AS futsal_name,
        p.amount, 
        p.status AS payment_status
    FROM futsal_court_slots fcs
    JOIN futsal_courts fc ON fcs.futsal_court_id = fc.id
    LEFT JOIN payments p ON fcs.payment_id = p.payment_id
    WHERE fcs.booked_by = ? 
    LIMIT ? OFFSET ?
";
$stmtBookings = $conn->prepare($queryBookings);
$stmtBookings->bind_param("sii", $username, $limit, $offset);
$stmtBookings->execute();
$bookings = $stmtBookings->get_result();
$stmtBookings->close();

// Calculate total pages
$totalPages = ceil($totalBookingsCount / $limit);

?>


<!-- Main Content -->
 <div class="flex w-full justify-center items-center space-x-8">
    <section class="relative md:w-3/4 flex justify-center items-center bg-slate-500 rounded-3xl p-4">
        <!-- cards -->
        <div class="flex md:flex-row flex-col  gap-8 justify-center items-center w-full">
            
            <!-- Hello Card -->
            <div class="w-[200px] h-[80px]  p-4 flex items-center justify-center text-black">
                <p class="text-lg">Hello, <?php echo htmlspecialchars($user['name']); ?>!</p>
            </div>

            <!-- Total Bookings Card -->
            <div class="w-[200px] h-[80px] bg-gradient-to-r from-green-500 via-yellow-500 to-orange-500 rounded-lg p-4 flex items-center justify-center text-white">
                <div class="flex flex-col items-center">
                    <i class="ri-bookmark-line text-2xl"></i>
                    <p class="text-lg"><?php echo $totalBookings; ?> Bookings</p>
                </div>
            </div>

            <!-- Account Verification Status Card -->
            <a href="account_verifi.php?username=<?php echo urlencode($username); ?>" class="w-[200px] h-[80px] bg-gradient-to-r from-blue-500 via-teal-500 to-lime-500 rounded-lg p-4 flex items-center justify-center text-white">
                <div class="flex flex-col items-center">
                    <i class="ri-check-line text-2xl"></i>
                    <p class="text-lg"><?php echo $verificationStatus; ?></p>
                </div>
            </a>


            <!-- User Role Card -->
            <div class="w-[200px] h-[80px] bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 rounded-lg p-4 flex items-center justify-center text-white">
                <div class="flex flex-col items-center">
                    <i class="ri-user-line text-2xl"></i>
                    <p class="text-lg"><?php echo ucfirst($userRole); ?></p>
                </div>
            </div>

        </div>
    </section>
    <?php
// Check if the user role is 'client'
if ($_SESSION['role'] == 'client') :
?>
    <section class="relative md:w-1/4 flex justify-center items-center bg-slate-500 rounded-3xl p-4">
        <!-- cards -->
        <div class="flex md:flex-row flex-col gap-8 justify-center items-center w-full">
            <!-- User Role Card -->
            <a href="./client/post_court.php" class="w-[200px] h-[80px] p-4 flex items-center justify-center text-white">
                <div class="flex flex-col items-center">
                    <i class="ri-football-line text-2xl"></i>
                    <p class="text-lg">Client Side</p>
                </div>
            </a>
        </div>
    </section>
<?php
endif;
?>

</div>

<!-- Bookings Table Section -->
<section class="relative w-full p-4 bg-slate-400 rounded-3xl mt-8 ">
    <h2 class="text-2xl font-bold text-white mb-4">Your Booking Details</h2>

    <div class="w-full overflow-x-auto">

        <table class="w-full table-auto text-white md:w-full w-[800px]">
            <thead>
                <tr class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                    <th class="p-2">Futsal Name</th>
                    <th class="p-2">Slot Date</th>
                    <th class="p-2">Slot Hour</th>
                    <th class="p-2">Payment ID</th>
                    <th class="p-2">Amount</th>
                    <th class="p-2">Payment Status</th>
                    <th class="p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Alternate row colors for the table
                $rowColors = ['bg-gray-800', 'bg-gray-600'];
                $i = 0;
                while ($booking = $bookings->fetch_assoc()) {
                    // Alternate row color
                    $rowClass = $rowColors[$i % 2];
                    ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td class="p-2"><?php echo htmlspecialchars($booking['futsal_name']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($booking['slot_date']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($booking['slot_hour']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($booking['payment_id']); ?></td>
                        <td class="p-2"><?php echo htmlspecialchars($booking['amount']); ?></td>
                        <td class="p-2"><?php echo ucfirst(htmlspecialchars($booking['payment_status'])); ?></td>
                        <td class="p-2"><?php echo $booking['is_booked'] ? 'Booked' : 'Available'; ?></td>
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

<?php include('./includes/footer.php'); ?>

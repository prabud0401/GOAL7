<?php
include('../includes/header.php');
include('../fun/db.php');

// Check if ID is passed in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid court ID.";
    exit;
}

$futsalCourtId = intval($_GET['id']);

// Fetch futsal court details
$sqlCourt = "SELECT id, name, location, image, features, price_per_hour, availability_status FROM futsal_courts WHERE id = ?";
$stmtCourt = $conn->prepare($sqlCourt);
$stmtCourt->bind_param("i", $futsalCourtId);
$stmtCourt->execute();
$resultCourt = $stmtCourt->get_result();

if ($resultCourt->num_rows === 0) {
    echo "Court not found.";
    exit;
}

$court = $resultCourt->fetch_assoc();

// Fetch totals for the cards
$sqlTotalBookings = "SELECT COUNT(*) AS total_bookings FROM futsal_court_slots WHERE futsal_court_id = ?";
$sqlTotalPayments = "SELECT SUM(amount) AS total_payments FROM payments WHERE futsal_id = ?";
$sqlTotalReviews = "SELECT COUNT(*) AS total_reviews FROM futsal_reviews WHERE futsal_court_id = ?";

$stmtBookings = $conn->prepare($sqlTotalBookings);
$stmtBookings->bind_param("i", $futsalCourtId);
$stmtBookings->execute();
$totalBookings = $stmtBookings->get_result()->fetch_assoc()['total_bookings'];

$stmtPayments = $conn->prepare($sqlTotalPayments);
$stmtPayments->bind_param("i", $futsalCourtId);
$stmtPayments->execute();
$totalPayments = $stmtPayments->get_result()->fetch_assoc()['total_payments'] ?? 0;

$stmtReviews = $conn->prepare($sqlTotalReviews);
$stmtReviews->bind_param("i", $futsalCourtId);
$stmtReviews->execute();
$totalReviews = $stmtReviews->get_result()->fetch_assoc()['total_reviews'];

// Pagination variables
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;  // Current page number
$offset = ($page - 1) * $limit;  // Calculating offset

// Fetch slot bookings with pagination
$sqlSlots = "SELECT * FROM futsal_court_slots WHERE futsal_court_id = ? LIMIT ?, ?";
$stmtSlots = $conn->prepare($sqlSlots);
$stmtSlots->bind_param("iii", $futsalCourtId, $offset, $limit);
$stmtSlots->execute();
$slots = $stmtSlots->get_result();

// Fetch reviews with pagination
$sqlReviews = "SELECT * FROM futsal_reviews WHERE futsal_court_id = ? LIMIT ?, ?";
$stmtReviews = $conn->prepare($sqlReviews);
$stmtReviews->bind_param("iii", $futsalCourtId, $offset, $limit);
$stmtReviews->execute();
$reviews = $stmtReviews->get_result();

// Fetch payments with pagination
$sqlPayments = "SELECT * FROM payments WHERE futsal_id = ? LIMIT ?, ?";
$stmtPayments = $conn->prepare($sqlPayments);
$stmtPayments->bind_param("iii", $futsalCourtId, $offset, $limit);
$stmtPayments->execute();
$payments = $stmtPayments->get_result();

// Get total number of pages for each table
$sqlSlotsCount = "SELECT COUNT(*) AS total_slots FROM futsal_court_slots WHERE futsal_court_id = ?";
$stmtSlotsCount = $conn->prepare($sqlSlotsCount);
$stmtSlotsCount->bind_param("i", $futsalCourtId);
$stmtSlotsCount->execute();
$totalSlots = $stmtSlotsCount->get_result()->fetch_assoc()['total_slots'];
$totalPagesSlots = ceil($totalSlots / $limit);

$sqlReviewsCount = "SELECT COUNT(*) AS total_reviews FROM futsal_reviews WHERE futsal_court_id = ?";
$stmtReviewsCount = $conn->prepare($sqlReviewsCount);
$stmtReviewsCount->bind_param("i", $futsalCourtId);
$stmtReviewsCount->execute();
$totalReviewsCount = $stmtReviewsCount->get_result()->fetch_assoc()['total_reviews'];
$totalPagesReviews = ceil($totalReviewsCount / $limit);

$sqlPaymentsCount = "SELECT COUNT(*) AS total_payments FROM payments WHERE futsal_id = ?";
$stmtPaymentsCount = $conn->prepare($sqlPaymentsCount);
$stmtPaymentsCount->bind_param("i", $futsalCourtId);
$stmtPaymentsCount->execute();
$totalPaymentsCount = $stmtPaymentsCount->get_result()->fetch_assoc()['total_payments'];
$totalPagesPayments = ceil($totalPaymentsCount / $limit);
?>
<?php include('./nav.php'); ?>

<section class="p-8">
    <!-- Court Details -->
    <div class="bg-gray-800 text-white p-8 rounded-lg shadow-md mb-8">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($court['name']); ?></h1>
        <img src="<?= htmlspecialchars($court['image']); ?>" alt="Futsal Court" class="w-full h-64 object-cover rounded-md mb-4">
        <p class="text-lg mb-4"><strong>Location:</strong> <?= htmlspecialchars($court['location']); ?></p>
        <p class="text-lg mb-4"><strong>Features:</strong> <?= htmlspecialchars($court['features']); ?></p>
        <p class="text-lg mb-4"><strong>Price Per Hour:</strong> LKR <?= number_format($court['price_per_hour'], 2); ?></p>
        <p class="text-lg mb-4"><strong>Status:</strong> <?= $court['availability_status'] ? 'Available' : 'Not Available'; ?></p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-blue-600 text-white p-4 rounded-lg shadow-md text-center">
            <h2 class="text-xl font-bold">Total Bookings</h2>
            <p class="text-2xl"><?= $totalBookings; ?></p>
        </div>
        <div class="bg-green-600 text-white p-4 rounded-lg shadow-md text-center">
            <h2 class="text-xl font-bold">Total Payments</h2>
            <p class="text-2xl">LKR <?= number_format($totalPayments, 2); ?></p>
        </div>
        <div class="bg-yellow-600 text-white p-4 rounded-lg shadow-md text-center">
            <h2 class="text-xl font-bold">Total Reviews</h2>
            <p class="text-2xl"><?= $totalReviews; ?></p>
        </div>
    </div>

<!-- Futsal Court Slots Table -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-white mb-4">Futsal Court Slots</h2>
    <table class="w-full bg-gray-800 text-white rounded-lg">
        <thead>
            <tr>
                <th class="p-2 border border-gray-600">ID</th>
                <th class="p-2 border border-gray-600">Futsal Court ID</th>
                <th class="p-2 border border-gray-600">Slot Hour</th>
                <th class="p-2 border border-gray-600">Is Booked</th>
                <th class="p-2 border border-gray-600">Booked By</th>
                <th class="p-2 border border-gray-600">Created At</th>
                <th class="p-2 border border-gray-600">Payment ID</th>
                <th class="p-2 border border-gray-600">Slot Date</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Query to fetch futsal court slots
            $sql = "SELECT * FROM futsal_court_slots LIMIT ?, ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $start, $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            // Loop through and display data
            while ($slot = $result->fetch_assoc()): 
            ?>
                <tr>
                    <td class="p-2 border border-gray-600"><?= htmlspecialchars($slot['id']); ?></td>
                    <td class="p-2 border border-gray-600"><?= htmlspecialchars($slot['futsal_court_id']); ?></td>
                    <td class="p-2 border border-gray-600"><?= htmlspecialchars($slot['slot_hour']); ?></td>
                    <td class="p-2 border border-gray-600"><?= $slot['is_booked'] ? 'Yes' : 'No'; ?></td>
                    <td class="p-2 border border-gray-600"><?= htmlspecialchars($slot['booked_by']); ?></td>
                    <td class="p-2 border border-gray-600"><?= htmlspecialchars($slot['created_at']); ?></td>
                    <td class="p-2 border border-gray-600"><?= htmlspecialchars($slot['payment_id']); ?></td>
                    <td class="p-2 border border-gray-600"><?= htmlspecialchars($slot['slot_date']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        <a href="?page=1" class="p-2 bg-blue-600 text-white rounded-md mx-2">First</a>
        <a href="?page=<?= max(1, $page - 1) ?>" class="p-2 bg-blue-600 text-white rounded-md mx-2">Previous</a>
        <a href="?page=<?= min($totalPagesSlots, $page + 1) ?>" class="p-2 bg-blue-600 text-white rounded-md mx-2">Next</a>
        <a href="?page=<?= $totalPagesSlots ?>" class="p-2 bg-blue-600 text-white rounded-md mx-2">Last</a>
    </div>
</div>

    <!-- Reviews Table -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-4">Reviews</h2>
        <table class="w-full bg-gray-800 text-white rounded-lg">
            <thead>
                <tr>
                    <th class="p-2 border border-gray-600">ID</th>
                    <th class="p-2 border border-gray-600">Rating</th>
                    <th class="p-2 border border-gray-600">Review</th>
                    <th class="p-2 border border-gray-600">Reviewed By</th>
                    <th class="p-2 border border-gray-600">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($review = $reviews->fetch_assoc()): ?>
                    <tr>
                    <td class="p-2 border border-gray-600"><?= htmlspecialchars($review['futsal_court_id']); ?></td>
                        <td class="p-2 border border-gray-600"><?= htmlspecialchars($review['stars']); ?></td>
                        <td class="p-2 border border-gray-600"><?= htmlspecialchars($review['review_text']); ?></td>
                        <td class="p-2 border border-gray-600"><?= htmlspecialchars($review['player_name']); ?></td>
                        <td class="p-2 border border-gray-600"><?= htmlspecialchars($review['review_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="flex justify-center mt-4">
            <a href="?id=<?= $futsalCourtId ?>&page=1" class="p-2 bg-blue-600 text-white rounded-md mx-2">First</a>
            <a href="?id=<?= $futsalCourtId ?>&page=<?= max(1, $page - 1) ?>" class="p-2 bg-blue-600 text-white rounded-md mx-2">Previous</a>
            <a href="?id=<?= $futsalCourtId ?>&page=<?= min($totalPagesReviews, $page + 1) ?>" class="p-2 bg-blue-600 text-white rounded-md mx-2">Next</a>
            <a href="?id=<?= $futsalCourtId ?>&page=<?= $totalPagesReviews ?>" class="p-2 bg-blue-600 text-white rounded-md mx-2">Last</a>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-4">Payments</h2>
        <table class="w-full bg-gray-800 text-white rounded-lg">
            <thead>
                <tr>
                    <th class="p-2 border border-gray-600">Payment ID</th>
                    <th class="p-2 border border-gray-600">Amount</th>
                    <th class="p-2 border border-gray-600">Payment Method</th>
                    <th class="p-2 border border-gray-600">Paid By</th>
                    <th class="p-2 border border-gray-600">Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($payment = $payments->fetch_assoc()): ?>
                    <tr>
                        <td class="p-2 border border-gray-600"><?= htmlspecialchars($payment['id']); ?></td>
                        <td class="p-2 border border-gray-600">LKR <?= number_format($payment['amount'], 2); ?></td>
                        <td class="p-2 border border-gray-600"><?= htmlspecialchars($payment['payment_method']); ?></td>
                        <td class="p-2 border border-gray-600"><?= htmlspecialchars($payment['paid_by']); ?></td>
                        <td class="p-2 border border-gray-600"><?= htmlspecialchars($payment['payment_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="flex justify-center mt-4">
            <a href="?id=<?= $futsalCourtId ?>&page=1" class="p-2 bg-blue-600 text-white rounded-md mx-2">First</a>
            <a href="?id=<?= $futsalCourtId ?>&page=<?= max(1, $page - 1) ?>" class="p-2 bg-blue-600 text-white rounded-md mx-2">Previous</a>
            <a href="?id=<?= $futsalCourtId ?>&page=<?= min($totalPagesPayments, $page + 1) ?>" class="p-2 bg-blue-600 text-white rounded-md mx-2">Next</a>
            <a href="?id=<?= $futsalCourtId ?>&page=<?= $totalPagesPayments ?>" class="p-2 bg-blue-600 text-white rounded-md mx-2">Last</a>
        </div>
    </div>
</section>

<?php include('../includes/footer.php'); ?>
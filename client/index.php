<?php 
include('../includes/header.php');
include('../fun/db.php'); 

// Start session and check user role
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php"); // Redirect to login if not authorized
    exit;
}

// Fetch user details from the users table
$username = $_SESSION['username'];
$sqlUser = "SELECT id, name FROM users WHERE username = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows === 0) {
    echo "User not found.";
    exit;
}

$user = $resultUser->fetch_assoc();
$userId = $user['id'];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['court_id'], $_POST['availability_status'])) {
    $courtId = intval($_POST['court_id']);
    $availabilityStatus = intval($_POST['availability_status']);

    $sqlUpdate = "UPDATE futsal_courts SET availability_status = ? WHERE id = ? AND owner_id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("iii", $availabilityStatus, $courtId, $userId);
    $stmtUpdate->execute();
}

// Fetch futsal court details for the owner
$sqlCourts = "SELECT id, name, location, image, features, price_per_hour, availability_status FROM futsal_courts WHERE owner_id = ?";
$stmtCourts = $conn->prepare($sqlCourts);
$stmtCourts->bind_param("i", $userId);
$stmtCourts->execute();
$resultCourts = $stmtCourts->get_result();

$courtData = [];
while ($row = $resultCourts->fetch_assoc()) {
    $courtData[] = $row;
}
?>

<?php include('./nav.php'); ?>

<!-- Main Content -->
<section class="flex justify-center items-center md:flex-row flex-col">
    <div class="w-[200px] h-[80px] p-4 flex items-center justify-center text-black">
        <p class="text-lg">Hello, <?php echo htmlspecialchars($user['name']); ?>!</p>
    </div>

    <div class="flex md:flex-row flex-col gap-8 justify-center items-center w-full relative md:w-3/4 bg-slate-500 rounded-3xl p-4">
        <div class="w-[200px] h-[80px] bg-gradient-to-r from-green-500 via-yellow-500 to-orange-500 rounded-lg p-4 flex items-center justify-center text-white">
            <div class="flex flex-col items-center">
                <p class="text-2xl font-bold"><?php echo count($courtData); ?></p>
                <p class="text-lg">Total Courts</p>
            </div>
        </div>
    </div>
</section>

<section class="relative w-full p-4 bg-slate-400 rounded-3xl mt-8">
    <h2 class="text-2xl font-bold text-white mb-4">Futsal Court Details</h2>

    <div class="w-full overflow-x-auto">
        <table class="w-full table-auto text-white border-collapse border border-slate-600">
            <thead>
                <tr class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-left">
                    <th class="p-2 border border-slate-600">Court ID</th>
                    <th class="p-2 border border-slate-600">Name</th>
                    <th class="p-2 border border-slate-600">Location</th>
                    <th class="p-2 border border-slate-600">Status</th>
                    <th class="p-2 border border-slate-600">Features</th>
                    <th class="p-2 border border-slate-600">Price Per Hour</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($courtData) > 0): ?>
                    <?php foreach ($courtData as $court): ?>
                        <tr class="bg-slate-700">
                            <td class="p-2 border border-slate-600">
                                <a href="view_futsal.php?id=<?= $court['id']; ?>" class="text-blue-500 hover:underline">
                                    <?= htmlspecialchars($court['id']); ?>
                                </a>
                            </td>
                            <td class="p-2 border border-slate-600">
                                <a href="view_futsal.php?id=<?= $court['id']; ?>" class="text-blue-500 hover:underline">
                                    <?= htmlspecialchars($court['name']); ?>
                                </a>
                            </td>
                            <td class="p-2 border border-slate-600"><?php echo htmlspecialchars($court['location']); ?></td>
                            <td class="p-2 border border-slate-600">
                                <form method="POST" action="">
                                    <input type="hidden" name="court_id" value="<?php echo $court['id']; ?>">
                                    <select name="availability_status" class="text-black p-1 rounded" onchange="this.form.submit()">
                                        <option value="1" <?php echo $court['availability_status'] ? 'selected' : ''; ?>>Available</option>
                                        <option value="0" <?php echo !$court['availability_status'] ? 'selected' : ''; ?>>Not Available</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-2 border border-slate-600"><?php echo htmlspecialchars($court['features']); ?></td>
                            <td class="p-2 border border-slate-600">LKR <?php echo number_format($court['price_per_hour'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center p-4 border border-slate-600">No futsal courts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<!-- Card Section -->
<div class="flex flex-wrap justify-center gap-4 mt-8">
    <?php if (count($courtData) > 0): ?>
        <?php foreach ($courtData as $court): ?>
            <a href="view_futsal.php?id=<?= $court['id']; ?>" class="bg-zinc-800 rounded-lg overflow-hidden shadow-md h-[400px] w-[250px] p-4 flex flex-col justify-between space-y-4">
                <img src="<?= htmlspecialchars($court['image']); ?>" alt="Futsal Court" class="w-full h-[180px] object-cover">
                <div class="flex flex-col h-full justify-between">
                    <h3 class="font-bold text-lg h-14 overflow-x-auto scrollbar-hidden">
                        <?= htmlspecialchars($court['name']); ?>
                    </h3>
                    <p class="text-gray-300"><?= htmlspecialchars($court['features']); ?></p>
                    <p class="text-gray-300">Price: LKR <?= number_format($court['price_per_hour'], 2); ?>/hour</p>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-white">No futsal court posts available.</p>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>

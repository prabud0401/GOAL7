<?php

// Function to fetch futsal courts and sort them by the number of reviews
function getFutsalCourtsWithPagination($conn, $page, $search = '') {
    // Calculate the starting point for pagination
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // Base query to fetch futsal courts with the count of reviews
    $searchQuery = $search ? "AND (fc.name LIKE ? OR fc.features LIKE ?)" : ""; // Search condition

    $query = "
        SELECT fc.*, COUNT(fr.id) AS review_count
        FROM futsal_courts fc
        LEFT JOIN futsal_reviews fr ON fc.id = fr.futsal_court_id
        WHERE 1
        $searchQuery
        GROUP BY fc.id
        ORDER BY review_count DESC
        LIMIT ?, ?
    ";

    $stmt = $conn->prepare($query);

    // Bind parameters
    if ($search) {
        $searchTerm = "%$search%";
        $stmt->bind_param("ssii", $searchTerm, $searchTerm, $offset, $limit);
    } else {
        $stmt->bind_param("ii", $offset, $limit);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $courts = [];
    while ($court = $result->fetch_assoc()) {
        $courts[] = $court;
    }

    return $courts;
}

// Fetch the current page number (default is 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch futsal courts based on the page and search query
$courts = getFutsalCourtsWithPagination($conn, $page, $search);

// Function to generate pagination links
function generatePagination($conn, $search = '') {
    $limit = 10;
    $searchQuery = $search ? "AND (fc.name LIKE ? OR fc.features LIKE ?)" : "";
    $query = "SELECT COUNT(DISTINCT fc.id) AS total FROM futsal_courts fc
              LEFT JOIN futsal_reviews fr ON fc.id = fr.futsal_court_id
              WHERE 1 $searchQuery";

    $stmt = $conn->prepare($query);
    if ($search) {
        $searchTerm = "%$search%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total = $row['total'];

    $pages = ceil($total / $limit);
    return $pages;
}

$paginationPages = generatePagination($conn, $search);
?>

<!-- Modal for Loading/Processing -->
<div id="modal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg text-center">
        <p id="modal-message" class="text-xl text-yellow-600">Processing...</p>
    </div>
</div>

<!-- Search Section -->
<section class="mt-10 w-full flex justify-around">
    <form method="GET" action="" class="flex md:space-x-4 md:space-y-0 space-y-8 w-full md:flex-row flex-col">
        <input type="text" name="search" id="search" placeholder="Search by name or features" class="p-2 bg-zinc-700 text-white rounded-md" value="<?= htmlspecialchars($search) ?>">
        <div class="flex space-x-8 w-full">

            <!-- Search Button -->
            <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600">
                Search
            </button>

            <!-- Reset Button -->
            <button type="button" onclick="resetSearch()" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">
                Reset
            </button>
        </div>

    </form>
</section>

<script>
    function resetSearch() {
        // Redirect to the same page but with the search parameter removed
        window.location.href = window.location.pathname;
    }
</script>

<!-- Available Slots Section -->
<section id="available-slots" class="mt-6">
    <h2 class="text-2xl font-bold text-yellow-500 mb-4">Available Futsal Courts</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <?php
        // Loop through each futsal court and display it
        foreach ($courts as $court) {
            // Fetch available time slots for each court (Modify the logic as per your requirement)
            $slots = ''; // Placeholder for slots
            $slotsHtml = "<div class='flex space-x-2'>"; // Start slots section

            // Add available slots buttons
            $slotsHtml .= "<button class='bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600'>8:00 AM</button>";
            $slotsHtml .= "<button class='bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600'>9:00 AM</button>";

            $slotsHtml .= "</div>"; // End slots section
            ?>
            <!-- Wrap the entire card in an <a> tag to make it clickable -->
            <a href="view_futsal_court.php?id=<?= $court['id']; ?>" class="bg-zinc-800 rounded-lg overflow-hidden shadow-md h-[400px] w-[250px] p-4 flex flex-col justify-between space-y-4">
                <img src="<?= $court['image']; ?>" alt="Futsal Court" class="w-full h-[180px] object-cover">
                <div class="flex flex-col h-full justify-between">
                    <h3 class="font-bold text-lg h-14 overflow-x-auto scrollbar-hidden ">
                        <?= htmlspecialchars($court['name']); ?>
                    </h3>
                    <p class="text-gray-300"><?= htmlspecialchars($court['features']); ?></p>
                    <p class="text-gray-300">Price: LKR <?= number_format($court['price_per_hour'], 2); ?>/hour</p>

                    <div class="flex space-x-2 mt-4 overflow-x-auto scrollbar-hidden bg-gray-700 p-2 rounded" style="overflow-x: auto; flex-wrap: nowrap;">
                        <?= $slotsHtml; ?>
                    </div>
                    <div class="flex items-center mt-2">
                        <?php
                        $stars = $court['review_count'];
                        for ($i = 1; $i <= 5; $i++) {
                            $starClass = ($i <= $stars) ? 'text-yellow-500' : 'text-gray-400';
                            echo "<span class='fa fa-star $starClass'></span>";
                        }
                        ?>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>

    <!-- Pagination Links -->
    <div class="mt-6 flex justify-center space-x-4">
        <?php for ($i = 1; $i <= $paginationPages; $i++) { ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600"><?= $i ?></a>
        <?php } ?>
    </div>
</section>

<style>
    /* Hide scrollbar but keep the ability to scroll */
    .scrollbar-hidden::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hidden {
        -ms-overflow-style: none;  /* IE 10+ */
        scrollbar-width: none;  /* Firefox */
    }

    /* Additional styling for grayed out cards */
    .bg-gray-400 {
        background-color: #d1d5db;
    }
    .cursor-not-allowed {
        pointer-events: none;
    }
    .opacity-50 {
        opacity: 0.5;
    }
</style>

<?php
// Function to fetch all reviews for all futsal courts, along with their court names
function getAllReviews($conn) {
    // Query to fetch all reviews along with their corresponding futsal court names
    $query = "SELECT fr.*, fc.name as court_name, fc.id as futsal_court_id 
              FROM futsal_reviews fr
              JOIN futsal_courts fc ON fr.futsal_court_id = fc.id 
              ORDER BY fr.review_date DESC"; // Fetch all reviews ordered by review date
    $result = $conn->query($query);

    $reviews = [];
    while ($review = $result->fetch_assoc()) {
        $reviews[] = $review;  // Store each review in the array
    }

    return $reviews;  // Return the reviews
}

// Fetch all reviews
$reviews = getAllReviews($conn);
?>

<!-- Reviews Section -->
<section class="mt-10">
    <h2 class="text-2xl font-bold text-yellow-500 mb-4">Player Reviews</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php
        // Loop through each review and display it
        foreach ($reviews as $review) {
            // Get the star rating and ensure it's between 1 and 5
            $stars = (int)$review['stars'];
            ?>
            <div class="bg-zinc-800 rounded-lg overflow-hidden shadow-md p-4 w-[400px]">
                <div class="flex w-full justify-between">

                    <h3 class="font-bold">
                        <!-- Make the futsal court name clickable -->
                        <a href="view_futsal_court.php?id=<?= $review['futsal_court_id'] ?>" class="text-yellow-500 hover:underline">
                            <?= htmlspecialchars($review['court_name']); ?>
                        </a>
                    </h3>
                    <!-- Display Stars -->
                    <div class="text-yellow-400">
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $stars) {
                                echo "★"; // Filled star
                            } else {
                                echo "☆"; // Empty star
                            }
                        }
                        ?>
                    </div>
                </div>
                <p class="text-sm text-gray-300">Played on <?= date('M d, Y', strtotime($review['review_date'])); ?></p>
                <p class="mt-2"><?= htmlspecialchars($review['review_text']); ?></p>
            </div>
            <?php
        }
        ?>
    </div>
</section>

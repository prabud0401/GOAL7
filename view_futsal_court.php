<?php
// Start session to check if user is logged in
session_start();

// Include necessary files and database connection
include('./includes/header.php');
include('./includes/nav.php');
include('./fun/db.php');

// Check if user is logged in
$isLoggedIn = isset($_SESSION['username']);

if (isset($_GET['id'])) {
    $futsalId = $_GET['id'];

    // Fetch futsal court details
    $futsalQuery = "SELECT * FROM futsal_courts WHERE id = ?";
    $stmt = $conn->prepare($futsalQuery);
    $stmt->bind_param("i", $futsalId);
    $stmt->execute();
    $futsalResult = $stmt->get_result();
    $futsal = $futsalResult->fetch_assoc();

    // Fetch available time slots for the futsal court
    $slotQuery = "SELECT * FROM futsal_court_slots WHERE futsal_court_id = ?";
    $stmt = $conn->prepare($slotQuery);
    $stmt->bind_param("i", $futsalId);
    $stmt->execute();
    $slotsResult = $stmt->get_result();
}
?>

<main class="w-full h-auto flex justify-center p-4">
    <section class="bg-slate-500 rounded-3xl md:w-3/4 w-full h-full p-4">
        <h2 class="text-2xl font-bold text-yellow-500 mb-8 text-center"><?= htmlspecialchars($futsal['name']); ?></h2>

        <!-- Futsal Court Details -->
        <div class="bg-zinc-800 rounded-lg overflow-hidden shadow-md p-4 md:w-[400px]">
            <img src="<?= $futsal['image']; ?>" alt="Futsal Court" class="w-full h-[180px] object-cover">
            <h3 class="font-bold text-lg mt-4"><?= htmlspecialchars($futsal['name']); ?></h3>
            <p class="text-gray-300"><?= htmlspecialchars($futsal['features']); ?></p>
            <p class="text-gray-300">Price: LKR <?= number_format($futsal['price_per_hour'], 2); ?>/hour</p>
            <p class="text-gray-300">Location: <?= htmlspecialchars($futsal['location']); ?></p>
            <p class="text-gray-300">Max Players: <?= htmlspecialchars($futsal['max_players']); ?></p>
            <p class="text-gray-300">Availability: <?= $futsal['availability_status'] ? 'Available' : 'Not Available'; ?></p>
        </div>

        <!-- Available Time Slots -->
        <h3 class="text-xl font-bold text-yellow-500 mt-6">Available Time Slots</h3>
        <div id="slots" class="grid md:grid-cols-10 grid-cols-3 gap-4 mt-4">
            <?php while ($slot = $slotsResult->fetch_assoc()) { ?>
                <div class="flex items-center space-x-2">
                    <?php if ($slot['is_booked'] == 1) { ?>
                        <button class="bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed" disabled>
                            <?= htmlspecialchars($slot['slot_hour']); ?>
                        </button>
                    <?php } else { ?>
                        <button class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600" data-slot-id="<?= $slot['id']; ?>" data-futsal-id="<?= $futsal['id']; ?>" data-slot-hour="<?= htmlspecialchars($slot['slot_hour']); ?>" onclick="selectSlot(this)">
                            <?= htmlspecialchars($slot['slot_hour']); ?>
                        </button>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <!-- Booking Button -->
        <div class="mt-4">
            <button id="book-btn" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600" onclick="showConfirmationModal()">
                Book Selected Slots
            </button>
        </div>
    </section>
</main>

<!-- Modal for Confirmation -->
<div id="modal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-slate-500 p-6 rounded-lg text-center">
        <p id="modal-message" class="text-xl text-yellow-600">Processing...</p>

        <div id="slot-details" class="mt-4">
            <ul id="selected-slots"></ul>
            <p id="total-duration"></p>
            <p id="total-price"></p>
            <p id="futsal-name"></p>
            <p id="futsal-address"></p>
            <p id="username" class="text-white mt-2"></p> <!-- Username will appear here -->
        </div>

        <div class="mt-4">
            <button id="confirm-btn" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600" onclick="confirmBooking()">Confirm Booking</button>
            <button class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
    let selectedSlots = [];

    function selectSlot(button) {
        const slotId = button.getAttribute('data-slot-id');
        const slotHour = button.getAttribute('data-slot-hour');

        if (selectedSlots.some(slot => slot.slotId === slotId)) {
            selectedSlots = selectedSlots.filter(slot => slot.slotId !== slotId);
            button.classList.remove('bg-yellow-600');
        } else {
            selectedSlots.push({ slotId, slotHour });
            button.classList.add('bg-yellow-600');
        }
    }

    function showConfirmationModal() {
        // Check if user is logged in (PHP session check)
        <?php if ($isLoggedIn): ?>
            if (selectedSlots.length === 0) {
                alert("Please select at least one time slot.");
                return;
            }

            let totalDuration = selectedSlots.length;
            let totalPrice = totalDuration * <?= $futsal['price_per_hour']; ?>;
            let futsalName = "<?= htmlspecialchars($futsal['name']); ?>";
            let futsalAddress = "<?= htmlspecialchars($futsal['location']); ?>";
            let username = "<?= $_SESSION['username']; ?>"; // Get the username from session

            // Populate modal with selected slots and details
            const slotDetailsList = document.getElementById('selected-slots');
            slotDetailsList.innerHTML = '';
            selectedSlots.forEach(slot => {
                const listItem = document.createElement('li');
                listItem.textContent = slot.slotHour;
                slotDetailsList.appendChild(listItem);
            });

            document.getElementById('total-duration').textContent = `Total Duration: ${totalDuration} hour(s)`;
            document.getElementById('total-price').textContent = `Total Price: LKR ${totalPrice.toFixed(2)}`;
            document.getElementById('futsal-name').textContent = `Futsal Name: ${futsalName}`;
            document.getElementById('futsal-address').textContent = `Futsal Location: ${futsalAddress}`;
            document.getElementById('username').textContent = `Username: ${username}`; // Display the username in the modal

            // Show modal
            document.getElementById('modal').classList.remove('hidden');
        <?php else: ?>
            // If user is not logged in, show login prompt
            const modalMessage = document.getElementById('modal-message');
            modalMessage.innerHTML = ` 
                <p>You must log in first.</p>
                <a href="login.php" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Go to Login</a>
            `;
            // Show modal with login message
            document.getElementById('modal').classList.remove('hidden');
        <?php endif; ?>
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }

    function confirmBooking() {
    const slotIds = selectedSlots.map(slot => slot.slotId); // Get the selected slot IDs
    const futsalId = "<?= $futsal['id']; ?>"; // Get the futsal court ID
    const totalDuration = selectedSlots.length; // Total duration of the booking
    const totalPrice = totalDuration * <?= $futsal['price_per_hour']; ?>; // Calculate total price based on price per hour
    const username = "<?= $_SESSION['username']; ?>"; // Get the logged-in user's username

    // Construct the URL with query parameters
    const url = new URL("booking.php", window.location.href);
    url.searchParams.append('futsal_id', futsalId);
    url.searchParams.append('slot_ids', slotIds.join(',')); // Join array of slot IDs into a comma-separated string
    url.searchParams.append('total_duration', totalDuration);
    url.searchParams.append('total_price', totalPrice);
    url.searchParams.append('username', username);

    // Redirect to the booking page with the data in the URL
    window.location.href = url.toString();
}

</script>

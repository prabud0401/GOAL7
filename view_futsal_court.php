<?php
// Start session to check if user is logged in
session_start();

// Include necessary files and database connection
include('./includes/header.php');
include('./includes/nav.php');

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
}
?>

<main class="w-full h-auto flex justify-center p-4 text-black">
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

        <div class="flex w-full justify-between items-center">
            <!-- Available Time Slots -->
            <h3 class="text-xl font-bold text-yellow-500 mt-6">Available Time Slots</h3>
            <!-- Date Selector Dropdown -->
            <div class="mt-6">
                <select id="slot-date" class="mt-2 p-2 bg-white border rounded-md" onchange="fetchSlotsForDate()">
                    <option value="" disabled selected>Select Date</option>
                    <?php
                    // Get today and next 7 days dates for slot selection
                    for ($i = 0; $i < 7; $i++) {
                        $date = date('Y-m-d', strtotime("+$i days"));
                        echo "<option value='$date'>" . date('l, F j, Y', strtotime($date)) . "</option>";
                    }
                    ?>
                </select>
            </div>


        </div>

        <div id="slots" class="grid md:grid-cols-10 grid-cols-3 gap-4 mt-4">
            <!-- Available slots will be populated here -->
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

    // Fetch the available slots based on selected date
    function fetchSlotsForDate() {
        const selectedDate = document.getElementById('slot-date').value;

        if (!selectedDate) return; // Exit if no date is selected

        // Fetch available slots for the selected date using AJAX
        fetch(`fetch_slots.php?futsal_id=<?= $futsal['id']; ?>&slot_date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                const slotsContainer = document.getElementById('slots');
                slotsContainer.innerHTML = ''; // Clear previous slots

                // Check if slots are returned
                if (data.slots.length === 0) {
                    slotsContainer.innerHTML = '<p class="text-white">No available slots for this date.</p>';
                    return;
                }

                // Loop through each slot and create the corresponding button
                data.slots.forEach(slot => {
                    const button = document.createElement('button');
                    button.classList.add('bg-yellow-500', 'text-white', 'py-2', 'px-4', 'rounded-lg', 'hover:bg-yellow-600');
                    button.textContent = slot.slot_hour;
                    button.setAttribute('data-slot-id', slot.id);
                    button.setAttribute('data-slot-hour', slot.slot_hour);
                    button.setAttribute('onclick', 'selectSlot(this)');
                    
                    // Disable button if slot is booked
                    if (slot.is_booked) {
                        button.classList.add('bg-gray-400', 'cursor-not-allowed');
                        button.setAttribute('disabled', true);
                    }
                    
                    slotsContainer.appendChild(button);
                });
            })
            .catch(error => console.error('Error fetching slots:', error));
    }

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
        document.getElementById('modal').classList.remove('hidden');
    <?php endif; ?>
}

function confirmBooking() {
    // Get the selected slot IDs
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


    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
</script>

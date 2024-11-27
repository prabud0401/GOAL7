<?php
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
            <h3 class="text-xl font-bold text-yellow-500 mt-6">Available Time Slots</h3>
            <div class="mt-6">
                <select id="slot-date" class="mt-2 p-2 bg-white border rounded-md" onchange="fetchSlotsForDate()">
                    <?php
                    for ($i = 0; $i < 7; $i++) {
                        $date = date('Y-m-d', strtotime("+$i days"));
                        $selected = ($i === 0) ? 'selected' : '';
                        echo "<option value='$date' $selected>" . date('l, F j, Y', strtotime($date)) . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div id="slots" class="grid md:grid-cols-10 grid-cols-3 gap-4 mt-4">
            <!-- Available slots for the current date will be loaded here -->
        </div>

        <div class="mt-4">
            <button id="book-btn" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600" onclick="showConfirmationModal()">
                Book Selected Slots
            </button>
        </div>
    </section>
</main>

<div id="modal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-slate-500 p-6 rounded-lg text-center">
        <p id="modal-message" class="text-xl text-yellow-600">Processing...</p>
        <div id="slot-details" class="mt-4">
            <ul id="selected-slots"></ul>
            <p id="total-duration"></p>
            <p id="total-price"></p>
            <p id="futsal-name"></p>
            <p id="futsal-address"></p>
            <p id="username" class="text-white mt-2"></p>
        </div>
        <div class="mt-4">
            <button id="confirm-btn" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600" onclick="confirmBooking()">Confirm Booking</button>
            <button class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
    let selectedSlots = [];

    document.addEventListener("DOMContentLoaded", () => {
        fetchSlotsForDate(); // Automatically fetch slots for the current date on load
    });

    function fetchSlotsForDate() {
        const selectedDate = document.getElementById('slot-date').value;

        fetch(`fetch_slots.php?futsal_id=<?= $futsal['id']; ?>&slot_date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                const slotsContainer = document.getElementById('slots');
                slotsContainer.innerHTML = '';

                if (data.slots.length === 0) {
                    slotsContainer.innerHTML = '<p class="text-white">No available slots for this date.</p>';
                    return;
                }

                data.slots.forEach(slot => {
                    const button = document.createElement('button');
                    button.classList.add('py-2', 'px-4', 'rounded-lg', 'hover:bg-yellow-600');
                    button.textContent = slot.slot_hour;
                    button.dataset.slotId = slot.id;
                    button.dataset.slotHour = slot.slot_hour;

                    if (slot.is_booked) {
                        button.classList.add('bg-gray-400', 'cursor-not-allowed');
                        button.disabled = true;
                    } else {
                        button.classList.add('bg-yellow-500', 'text-white');
                        button.setAttribute('onclick', 'selectSlot(this)');
                    }

                    slotsContainer.appendChild(button);
                });
            })
            .catch(error => console.error('Error fetching slots:', error));
    }

    function selectSlot(button) {
        const slotId = button.dataset.slotId;
        const slotHour = button.dataset.slotHour;

        if (selectedSlots.some(slot => slot.slotId === slotId)) {
            selectedSlots = selectedSlots.filter(slot => slot.slotId !== slotId);
            button.classList.remove('bg-yellow-600');
        } else {
            selectedSlots.push({ slotId, slotHour });
            button.classList.add('bg-yellow-600');
        }
    }

    function showConfirmationModal() {
        <?php if ($isLoggedIn): ?>
        if (selectedSlots.length === 0) {
            alert("Please select at least one slot.");
            return;
        }

        const slotDetailsList = document.getElementById('selected-slots');
        slotDetailsList.innerHTML = selectedSlots.map(slot => `<li>${slot.slotHour}</li>`).join('');
        document.getElementById('total-duration').textContent = `Total Duration: ${selectedSlots.length} hour(s)`;
        document.getElementById('total-price').textContent = `Total Price: LKR ${(selectedSlots.length * <?= $futsal['price_per_hour']; ?>).toFixed(2)}`;
        document.getElementById('futsal-name').textContent = `Futsal Name: <?= htmlspecialchars($futsal['name']); ?>`;
        document.getElementById('futsal-address').textContent = `Futsal Location: <?= htmlspecialchars($futsal['location']); ?>`;
        document.getElementById('username').textContent = `Username: <?= $_SESSION['username']; ?>`;

        document.getElementById('modal').classList.remove('hidden');
        <?php else: ?>
        alert('You must log in first.');
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

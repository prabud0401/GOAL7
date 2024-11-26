<?php include('../includes/header.php'); ?>
<?php include('../includes/nav.php'); ?>

<!-- Modal for Loading/Processing -->
<div id="modal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg text-center">
        <p id="modal-message" class="text-xl text-yellow-600">Processing...</p>
    </div>
</div>

<!-- Main Content -->
<main class="bg-slate-500 rounded-3xl w-full h-auto flex space-x-16 md:p-16 p-4">
    <div class="w-1/4 h-full">
        <!-- Image Upload and URL Options -->
        <form id="upload-form" method="POST" enctype="multipart/form-data" class="flex flex-col items-center space-y-4">
            <label class="text-white">Choose Image Option</label>
            <div class="flex space-x-4">
                <label>
                    <input type="radio" name="image_option" value="upload" checked> Upload Image
                </label>
                <label>
                    <input type="radio" name="image_option" value="url"> Provide Image URL
                </label>
            </div>

            <!-- Image URL Section -->
            <div id="url-section" class="flex flex-col items-center space-y-4">
                <label for="image_url" class="text-white">Enter Image URL</label>
                <input type="text" id="image_url" name="image_url" class="bg-zinc-700 text-white rounded-md p-2" placeholder="Enter Image URL">
            </div>
        </form>

        <!-- Display Image if Uploaded or Provided -->
        <div id="image-preview" class="mt-4 hidden">
            <h3 class="text-white">Image Preview</h3>
            <img id="court-image-preview" src="" alt="Court Image" class="w-64 h-64 rounded-3xl object-cover">
        </div>
    </div>

    <!-- Form to Insert New Futsal Court -->
    <section id="insert-court" class="w-3/4 h-full">
        <h2 class="text-2xl font-bold text-yellow-500 mb-4">Insert New Court Details</h2>

        <form id="insert-court-form" method="POST" action="insert_futsal_court.php" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Court Name -->
            <div class="flex flex-col">
                <label for="name" class="text-white">Court Name</label>
                <input type="text" id="name" name="name" class="p-2 bg-zinc-700 text-white rounded-md" required>
            </div>

            <!-- Location -->
            <div class="flex flex-col">
                <label for="location" class="text-white">Location</label>
                <input type="text" id="location" name="location" class="p-2 bg-zinc-700 text-white rounded-md" required>
            </div>

            <!-- Start Date (Fixed, Display Only) -->
            <div class="flex flex-col">
                <label for="start_date" class="text-white">Start Date</label>
                <input type="text" id="start_date" name="start_date" class="p-2 bg-zinc-700 text-white rounded-md" readonly value="<?php echo date('Y-m-d'); ?>">
            </div>

            <!-- End Date (Selectable, but must be >= Tomorrow's date) -->
            <div class="flex flex-col">
                <label for="end_date" class="text-white">End Date</label>
                <input type="date" id="end_date" name="end_date" class="p-2 bg-zinc-700 text-white rounded-md" required min="<?php echo date('Y-m-d', strtotime('tomorrow')); ?>">
            </div>

            <!-- Features -->
            <div class="flex flex-col">
                <label for="features" class="text-white">Features</label>
                <input type="text" id="features" name="features" class="p-2 bg-zinc-700 text-white rounded-md" required>
            </div>

            <!-- Price per Hour -->
            <div class="flex flex-col">
                <label for="price_per_hour" class="text-white">Price per Hour (LKR)</label>
                <input type="number" id="price_per_hour" name="price_per_hour" class="p-2 bg-zinc-700 text-white rounded-md" required>
            </div>

            <!-- Max Players -->
            <div class="flex flex-col">
                <label for="max_players" class="text-white">Max Players</label>
                <input type="number" id="max_players" name="max_players" class="p-2 bg-zinc-700 text-white rounded-md" required>
            </div>

            <!-- Start Hour -->
            <div class="flex flex-col">
                <label for="start_hour" class="text-white">Start Hour</label>
                <input type="time" id="start_hour" name="start_hour" class="p-2 bg-zinc-700 text-white rounded-md" required>
            </div>

            <!-- End Hour -->
            <div class="flex flex-col">
                <label for="end_hour" class="text-white">End Hour</label>
                <input type="time" id="end_hour" name="end_hour" class="p-2 bg-zinc-700 text-white rounded-md" required>
            </div>

            <!-- Availability Status -->
            <div class="flex flex-col">
                <label for="availability_status" class="text-white">Availability Status</label>
                <select id="availability_status" name="availability_status" class="p-2 bg-zinc-700 text-white rounded-md" required>
                    <option value="1" selected>Available</option>
                    <option value="0">Not Available</option>
                </select>
            </div>

            <!-- Area -->
            <div class="flex flex-col">
                <label for="area_id" class="text-white">Area</label>
                <select id="area_id" name="area_id" class="p-2 bg-zinc-700 text-white rounded-md" required>
                    <option value="" disabled selected>Select Area</option>
                    <?php
                    // Fetch areas from the database and populate the dropdown
                    include('../fun/db.php');
                    $areas = $conn->query("SELECT id, name FROM areas");
                    while ($area = $areas->fetch_assoc()) {
                        echo "<option value='{$area['id']}'>{$area['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="col-span-2">
                <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 w-56">
                    Add Court
                </button>
            </div>
        </form>
    </section>
</main>

<?php include('../includes/footer.php'); ?>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Display image if URL is provided
        $('#image_url').on('input', function() {
            var imageUrl = $(this).val();
            if (imageUrl) {
                $('#court-image-preview').attr('src', imageUrl);
                $('#image-preview').removeClass('hidden');
            }
        });

        // Handle form submission via AJAX
        $('#insert-court-form').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Show loading modal
            $('#modal').removeClass('hidden');
            $('#modal-message').text('Processing...');

            // Create a FormData object and append the form data
            var formData = new FormData(this);

            // Add predefined owner_id to form data
            formData.append('owner_id', 1); // Predefine the owner_id as 1 (for now)

            // If the image URL is provided, append it directly
            var imageUrl = $("#image_url").val();
            formData.append('image', imageUrl); // Add the image URL directly here

            // AJAX request to insert futsal court data
            $.ajax({
                url: './insert_futsal_court.php', // Backend PHP to handle insertion
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = JSON.parse(response); // Parse the response JSON
                    if(result.status === 'success') {
                        $('#modal-message').text(result.message); // Display success message
                    } else {
                        $('#modal-message').text(result.message); // Display error message
                    }
                    setTimeout(function() {
                        $('#modal').addClass('hidden'); // Hide the modal after 2 seconds
                    }, 2000);
                },
                error: function() {
                    $('#modal-message').text('Error occurred, please try again');
                    setTimeout(function() {
                        $('#modal').addClass('hidden'); // Hide the modal after error
                    }, 2000);
                }
            });
        });
    });
</script>

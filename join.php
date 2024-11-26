<?php include('./includes/header.php'); ?>
<?php include('./includes/nav.php'); ?>

<!-- Modal for Loading/Processing -->
<div id="modal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg text-center">
        <p id="modal-message" class="text-xl text-yellow-600">Processing...</p>
    </div>
</div>

<!-- Main Content -->
<main class=" w-full h-auto flex justify-center ">
    <section class="w-full md:w-3/4 lg:w-1/2 h-full bg-slate-500 rounded-3xl md:p-16 p-4">
        <h2 class="text-2xl font-bold text-yellow-500 mb-8 text-center">User Registration</h2>

        <form id="user-registration-form" method="POST" action="register_user.php" class="grid md:grid-cols-2 grid-cols-1 gap-6">
            <!-- Full Name -->
            <div class="flex flex-col">
                <label for="name" class="text-white">Full Name</label>
                <input type="text" id="name" name="name" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter your full name" required>
            </div>

            <!-- Email -->
            <div class="flex flex-col">
                <label for="email" class="text-white">Email</label>
                <input type="email" id="email" name="email" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter your email" required>
            </div>

            <!-- Username -->
            <div class="flex flex-col">
                <label for="username" class="text-white">Username</label>
                <input type="text" id="username" name="username" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter your username" required>
            </div>

            <!-- Password -->
            <div class="flex flex-col">
                <label for="password" class="text-white">Password</label>
                <input type="password" id="password" name="password" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter your password" required>
            </div>

            <!-- Confirm Password -->
            <div class="flex flex-col">
                <label for="confirm_password" class="text-white">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Confirm your password" required>
            </div>

            <!-- Phone -->
            <div class="flex flex-col">
                <label for="phone" class="text-white">Phone Number</label>
                <input type="text" id="phone" name="phone" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter your phone number">
            </div>

            <!-- Address -->
            <div class="flex flex-col">
                <label for="address" class="text-white">Address</label>
                <textarea id="address" name="address" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter your address"></textarea>
            </div>

            <!-- Current Area -->
            <div class="flex flex-col">
                <label for="current_area" class="text-white">Current Area</label>
                <select id="current_area" name="current_area" class="p-2 bg-zinc-700 text-white rounded-md" required>
                    <option value="" disabled selected>Select Your Area</option>
                    <?php
                    // Fetch areas from the database and populate the dropdown
                    include('./includes/db_connection.php'); // Adjust the path as needed
                    $query = "SELECT name FROM areas ORDER BY name ASC";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Role -->
            <div class="flex flex-col">
                <label for="role" class="text-white">Role</label>
                <select id="role" name="role" class="p-2 bg-zinc-700 text-white rounded-md" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="client">Client</option>
                    <option value="customer">Customer</option>
                </select>
            </div>

            <!-- Profile Image URL -->
            <div class="flex flex-col">
                <label for="profile_image" class="text-white">Profile Image URL</label>
                <input type="url" id="profile_image" name="profile_image" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter profile image URL (optional)">
            </div>

            <!-- Submit Button -->
            <div class="col-span-1 flex md:flex-row flex-col justify-around items-center w-full">
                <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 w-full">
                    Register
                </button>
            </div>
            <a href="log.php" class="text-yellow-500 hover:text-yellow-600">already have an account? Login</a>

        </form>
    </section>
</main>

<?php include('./includes/footer.php'); ?>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle form submission via AJAX
        $('#user-registration-form').on('submit', function(e) {
            e.preventDefault();

            // Show loading modal
            $('#modal').removeClass('hidden');
            $('#modal-message').text('Processing...');

            // Create a FormData object and append the form data
            var formData = new FormData(this);

            // AJAX request to register the user
            $.ajax({
                url: './fun/register_user.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = JSON.parse(response);
                    
                    // Show the message
                    $('#modal-message').text(result.message);

                    // Hide the modal after a few seconds
                    setTimeout(function() {
                        $('#modal').addClass('hidden');
                    }, 2000);
                },
                error: function() {
                    // Handle AJAX error
                    $('#modal-message').text('Error occurred, please try again');
                    setTimeout(function() {
                        $('#modal').addClass('hidden');
                    }, 2000);
                }
            });
        });
    });
</script>


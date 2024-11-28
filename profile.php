<?php include('./includes/header.php'); ?>
<?php include('./includes/nav.php'); ?>

<?php




// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: log.php'); // Redirect to login page if not logged in
    exit();
}

// Get the logged-in username
$username = $_SESSION['username'];

// Fetch user details from the database
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $profile_image = $_POST['profile_image'] ?? '';

    // Update user details in the database
    $update_query = "UPDATE users SET name = ?, email = ?, phone = ?, address = ?, profile_image_url = ? WHERE username = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssssss", $name, $email, $phone, $address, $profile_image, $username);

    if ($update_stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating profile']);
    }
    exit();
}

// Handle account deletion
if (isset($_GET['delete']) && $_GET['delete'] == 'true') {
    $delete_query = "DELETE FROM users WHERE username = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("s", $username);

    if ($delete_stmt->execute()) {
        session_unset(); // Clear session
        session_destroy(); // Destroy session
        header('Location: log.php'); // Redirect to login after account deletion
        exit();
    } else {
        $message = "Error deleting account!";
    }
}

$conn->close();
?>


<!-- Modal for Loading/Processing -->
<div id="modal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg text-center">
        <p id="modal-message" class="text-xl text-yellow-600">Processing...</p>
    </div>
</div>

<!-- Main Content -->
<main class="bg-slate-500 rounded-3xl w-full h-auto flex justify-center md:p-16 p-4">
    <section class="w-full md:w-3/4 w-full h-full">
        <h2 class="text-2xl font-bold text-yellow-500 mb-8 text-center">Profile Management</h2>

        <form id="profile-form" method="POST" class="grid md:grid-cols-2 grid-cols-1 gap-6">
            <div class="flex flex-col justify-center items-center space-y-8">
                <!-- Profile Image -->
                <div class="flex justify-center">
                    <?php
                    // Check if the user has a profile image URL
                    $profileImage = $user['profile_image_url'] ? $user['profile_image_url'] : 'default-profile.png';
                    ?>
                    <img src="<?= $profileImage ?>" alt="Profile Image" class="rounded-full w-40 h-40">
                </div>
                <div class="flex jusitfy-center items-center space-x-8">
                    <!-- Submit Button -->
                    <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600">
                        Update Profile
                    </button>
                    <!-- Delete Account -->
                    <a href="profile.php?delete=true" onclick="return confirm('Are you sure you want to delete your account?')" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">
                        Delete Account
                    </a>
                </div>
            </div>

            <div>
                <!-- Full Name -->
                <div class="flex flex-col">
                    <label for="name" class="text-white">Full Name</label>
                    <input type="text" id="name" name="name" value="<?= $user['name'] ?>" class="p-2 bg-zinc-700 text-white rounded-md" required>
                </div>

                <!-- Email -->
                <div class="flex flex-col">
                    <label for="email" class="text-white">Email</label>
                    <input type="email" id="email" name="email" value="<?= $user['email'] ?>" class="p-2 bg-zinc-700 text-white rounded-md" required>
                </div>

                <!-- Phone -->
                <div class="flex flex-col">
                    <label for="phone" class="text-white">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?= $user['phone'] ?>" class="p-2 bg-zinc-700 text-white rounded-md">
                </div>

                <!-- Address -->
                <div class="flex flex-col">
                    <label for="address" class="text-white">Address</label>
                    <textarea id="address" name="address" class="p-2 bg-zinc-700 text-white rounded-md"><?= $user['address'] ?></textarea>
                </div>
<!-- New Image Upload -->
<!-- Profile Image Upload -->
<div class="flex flex-col">
    <label for="image_file" class="text-white">Profile Image</label>
    <input type="file" id="image_file" name="image_file" class="p-2 bg-zinc-700 text-white rounded-md">
</div>

                <!-- Profile Image URL -->
                <div class="flex flex-col">
                    <label for="profile_image" class="text-white">Profile Image URL</label>
                    <input type="url" id="profile_image" name="profile_image" value="<?= $user['profile_image_url'] ?>" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter the image URL for your profile">
                </div>
            </div>


        </form>


    </section>
</main>

<?php include('./includes/footer.php'); ?>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Handle image upload via AJAX
    $('#image_file').on('change', function(e) {
        e.preventDefault();

        // Get the file input
        var fileInput = $('#image_file')[0];
        var formData = new FormData();
        formData.append('image_file', fileInput.files[0]);

        // Show loading modal
        $('#modal').removeClass('hidden');
        $('#modal-message').text('Uploading image...');

        // AJAX request to upload image
        $.ajax({
            url: 'upload_image.php', // The upload handler PHP file
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var result = JSON.parse(response);

                if (result.status === 'success') {
                    // Set the image URL in the profile_image input field
                    $('#profile_image').val(result.url.trim());

                    // Hide the loading modal
                    $('#modal').addClass('hidden');
                } else {
                    $('#modal-message').text(result.message);
                    setTimeout(function() {
                        $('#modal').addClass('hidden');
                    }, 2000);
                }
            },
            error: function() {
                $('#modal-message').text('Error uploading image, please try again.');
                setTimeout(function() {
                    $('#modal').addClass('hidden');
                }, 2000);
            }
        });
    });
    $(document).ready(function() {
    // Handle form submission via AJAX
    $('#profile-form').on('submit', function(e) {
        e.preventDefault();

        // Show loading modal
        $('#modal').removeClass('hidden');
        $('#modal-message').text('Processing...');

        // Create a FormData object and append the form data
        var formData = new FormData(this);

        // AJAX request to update user profile
        $.ajax({
            url: 'profile.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                setTimeout(function() {
                        $('#modal').addClass('hidden');
                        // Open a new URL after successful profile update
                        window.location.href = "profile.php";  // Replace 'somepage.php' with your target URL
                    }, 1000);
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    $('#modal-message').text('Profile updated successfully!');
                    
                } else {
                    $('#modal-message').text('Error updating profile!');
                    setTimeout(function() {
                        $('#modal').addClass('hidden');
                    }, 2000);
                }
            },
            error: function() {
                $('#modal-message').text('Error occurred, please try again');
                setTimeout(function() {
                    $('#modal').addClass('hidden');
                }, 2000);
            }
        });
    });
});

</script>

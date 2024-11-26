<?php include('./includes/header.php'); ?>
<?php include('./includes/nav.php'); ?>

<!-- Modal for Loading/Processing -->
<div id="modal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg text-center">
        <p id="modal-message" class="text-xl text-yellow-600">Processing...</p>
    </div>
</div>

<!-- Main Content -->
<main class="w-full h-full flex justify-center p-4 w-full">
    <section class="w-full md:w-1/4 h-full bg-slate-500 rounded-3xl p-4">
        <h2 class="text-2xl font-bold text-yellow-500 mb-8 text-center">User Login</h2>

        <form id="login-form" method="POST" action="login_user.php" class="grid grid-cols-1 gap-6">
            <!-- Username or Email -->
            <div class="flex flex-col">
                <label for="username_or_email" class="text-white">Username or Email</label>
                <input type="text" id="username_or_email" name="username_or_email" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter your username or email" required>
            </div>

            <!-- Password -->
            <div class="flex flex-col">
                <label for="password" class="text-white">Password</label>
                <input type="password" id="password" name="password" class="p-2 bg-zinc-700 text-white rounded-md" placeholder="Enter your password" required>
            </div>

            <!-- Submit Button -->
            <div class="col-span-1">
                <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 w-full">
                    Login
                </button>
            </div>
        </form>

        <!-- Links for Forgot Password and Sign Up -->
        <div class="mt-4 text-center flex flex-col ">
            <a href="forgot_password.php" class="text-yellow-500 hover:text-yellow-600">Forgot Password?</a>
            <a href="join.php" class="text-yellow-500 hover:text-yellow-600">Don't have an account? Sign up</a>
        </div>
    </section>
</main>

<?php include('./includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle form submission via AJAX
        $('#login-form').on('submit', function(e) { // Corrected form ID
            e.preventDefault();

            // Show loading modal
            $('#modal').removeClass('hidden');
            $('#modal-message').text('Processing...');

            // Create a FormData object and append the form data
            var formData = new FormData(this);

            // AJAX request to login the user
            $.ajax({
                url: './fun/login_user.php', // Adjust the PHP file path as needed
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        $('#modal-message').text(result.message);
                        setTimeout(() => {
                            window.location.href = './index.php'; // Redirect to index.php after successful login
                        }, 2000); // Wait for 2 seconds before redirect
                    } else {
                        $('#modal-message').text(result.message);
                    }
                    setTimeout(function() {
                        $('#modal').addClass('hidden');
                    }, 2000);
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


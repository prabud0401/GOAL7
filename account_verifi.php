<?php include('./includes/nav.php'); ?>

<?php
include('./includes/header.php');

// Check if the 'username' parameter exists in the URL
if (!isset($_GET['username'])) {
    header('Location: log.php');
    exit();
}

// Retrieve the username from the URL
$username = $_GET['username'];

// Fetch user details from the database (example query, adjust as necessary)
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "<p>User not found!</p>";
    exit();
}

$email = $user['email'];
?>

<section class="relative md:w-3/4 flex flex-col justify-center items-center bg-slate-500 rounded-3xl p-8 mt-4 text-white">
    <h2 class="text-2xl font-bold mb-4">Account Verification</h2>

    <div class="w-full max-w-md p-6">
        <p class="text-lg mb-2">Username: <span class="font-semibold"><?php echo htmlspecialchars($username); ?></span></p>
        <p class="text-lg mb-4">Email: <span class="font-semibold"><?php echo htmlspecialchars($email); ?></span></p>

        <!-- Send OTP Button -->
        <button id="sendOtpBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Send OTP
        </button>

        <!-- OTP Input -->
        <div id="otpInputSection" class="hidden mt-4">
            <label for="otp" class="block text-sm font-medium">Enter OTP:</label>
            <input type="text" id="otp" name="otp" 
                   class="mt-1 w-full px-3 py-2 bg-gray-900 text-white rounded-lg border border-gray-700 focus:outline-none">
            <button id="verifyOtpBtn" class="w-full mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Verify OTP
            </button>
        </div>
    </div>

    <!-- Modal -->
    <div id="responseModal" class="hidden fixed top-0 left-0 w-full h-full flex justify-center items-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6 w-1/3">
            <p id="modalMessage" class="text-black text-lg"></p>
            <button id="closeModalBtn" class="mt-4 w-full bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Close</button>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Send OTP via AJAX
        $('#sendOtpBtn').click(function() {
            // Show modal with "Sending OTP..." message before AJAX starts
            showModal('Sending OTP...');

            $.ajax({
                type: 'POST',
                url: './otp/send_otp.php',
                data: { email: '<?php echo $email; ?>', action: 'sendOtp' },
                success: function(response) {
                    let data = JSON.parse(response);
                    if (data.success) {
                        $('#otpInputSection').removeClass('hidden');
                        showModal('OTP sent successfully! Please check your email.');
                    } else {
                        showModal('Failed to send OTP: ' + data.message);
                    }
                },
                error: function() {
                    showModal('Error occurred while sending OTP.');
                }
            });
        });

        // Verify OTP via AJAX
        $('#verifyOtpBtn').click(function() {
            let otp = $('#otp').val();
            $.ajax({
                type: 'POST',
                url: './otp/send_otp.php',
                data: { otp: otp, action: 'verifyOtp', username: '<?php echo $username; ?>' },  // Include username
                success: function(response) {
                    let data = JSON.parse(response);
                    if (data.success) {
                        showModal('OTP verified successfully! Your account is now verified.');
                    } else {
                        showModal('Invalid OTP!');
                    }
                }
            });
        });

        // Modal close button
        $('#closeModalBtn').click(function() {
            $('#responseModal').addClass('hidden');
        });

        // Show modal function
        function showModal(message) {
            $('#modalMessage').text(message);
            $('#responseModal').removeClass('hidden');
        }
    });
</script>

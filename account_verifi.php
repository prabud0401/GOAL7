<?php
include('./fun/db.php');
include('./includes/header.php');



// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: log.php');
    exit();
}

$username = $_SESSION['username'];

// Fetch user details
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

<?php include('./includes/nav.php'); ?>

<!-- Main Content -->
<section class="relative md:w-3/4 flex flex-col justify-center items-center bg-slate-500 rounded-3xl p-8 mt-4 text-white">
    <h2 class="text-2xl font-bold mb-4">Account Verification</h2>

    <!-- User Info Card -->
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const otpInputSection = document.getElementById('otpInputSection');
        const verifyOtpBtn = document.getElementById('verifyOtpBtn');
        const responseModal = document.getElementById('responseModal');
        const modalMessage = document.getElementById('modalMessage');
        const closeModalBtn = document.getElementById('closeModalBtn');

        // Show OTP input when Send OTP is clicked
        sendOtpBtn.addEventListener('click', () => {
            fetch('./otp/send_verification.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ username: '<?php echo $username; ?>' }),
            })
            .then(response => response.json())
            .then(data => {
                modalMessage.textContent = data.message;
                responseModal.classList.remove('hidden');

                if (data.success) {
                    otpInputSection.classList.remove('hidden');
                }
            })
            .catch(error => {
                modalMessage.textContent = 'An error occurred. Please try again later.';
                responseModal.classList.remove('hidden');
            });
        });

        // Close modal on button click
        closeModalBtn.addEventListener('click', () => {
            responseModal.classList.add('hidden');
        });

        // Verify OTP
        verifyOtpBtn.addEventListener('click', () => {
            const otp = document.getElementById('otp').value;

            fetch('./otp/verify_otp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ username: '<?php echo $username; ?>', otp }),
            })
            .then(response => response.json())
            .then(data => {
                modalMessage.textContent = data.message;
                responseModal.classList.remove('hidden');
            })
            .catch(error => {
                modalMessage.textContent = 'An error occurred. Please try again later.';
                responseModal.classList.remove('hidden');
            });
        });
    });
</script>

<?php include('./includes/footer.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal Booking Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Remix Icon CDN -->
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body class="bg-gradient-to-b from-slate-700 to-slate-500 text-white min-h-screen flex flex-col justify-between items-center">
    <!-- Loading Animation -->
<div id="loading-screen" class="fixed inset-0 flex justify-center items-center bg-black z-50  text-yellow-300">
    <div class="text-center text-white flex flex-col justify-center items-center">
        <a class="relative" >
            <!-- Futsal Logo -->
            <img src="./assets/images/logo.gif" alt="Futsal Logo" class="w-48 h-auto">
            
            <!-- Centered Text -->
            <p class="absolute left-8 bottom-4 inset-0 flex items-center justify-center text-2xl font-bold">GOAL7</p>
        </a>
        <p id="loading-text" class="text-5xl text-yellow-300"><i class="ri-football-line"></i></p>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loadingScreen = document.getElementById('loading-screen');
        const loadingText = document.getElementById('loading-text');
        const pageContent = document.querySelectorAll('#page-content, #page-content-main');

        let ballCount = 1;
        let increasing = true;

        // Check if the loading screen has been shown already
        if (sessionStorage.getItem('loadingShown')) {
            // Skip loading animation if already displayed
            loadingScreen.style.display = 'none'; // Hide loading screen
            pageContent.forEach(section => section.classList.remove('hidden')); // Show page content
            return;
        }

        // Mark the loading screen as shown
        sessionStorage.setItem('loadingShown', 'true');

        // Update ⚽ dynamically
        const loadingAnimation = setInterval(() => {
            if (increasing) {
                ballCount++;
                if (ballCount === 7) increasing = false;
            } else {
                ballCount--;
                if (ballCount === 1) increasing = true;
            }
            loadingText.innerHTML = `${'<i class="ri-football-line text-5xl"></i>'.repeat(ballCount)}`;
        }, 300);

        // Hide loading screen after 3 seconds
        setTimeout(() => {
            clearInterval(loadingAnimation);
            loadingScreen.style.display = 'none'; // Hide loading screen
            pageContent.forEach(section => section.classList.remove('hidden')); // Show page content
        }, 3000);
    });
</script>


    <section class="md:w-3/4 w-full h-full flex flex-col justify-between items-center space-y-16 p-4">
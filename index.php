<?php include('./includes/header.php'); ?>
<?php
session_start(); // Make sure the session is started
?>

<?php include('./includes/nav.php'); ?>


<!-- Page Content -->
<section class="relative w-3/4 h-[60vh] hidden" id="page-content">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center rounded-3xl w-full h-full" style="background-image: url('https://evolvesoccerla.com/wp-content/uploads/2019/05/FutsalOrIndoor1a.jpg');"></div>

    <!-- Welcome Text and Button -->
    <div class="absolute inset-0 flex flex-col justify-center items-center text-center bg-black bg-opacity-50 rounded-3xl">
        <h1 class="text-4xl text-white font-bold mb-4">Welcome to Our GOAL7</h1>
        <p class="text-lg text-white mb-6">Book your futsal courts with ease and convenience!</p>
        <a href="./futsals.php" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 transition">Book Now</a>
    </div>
</section>

<!-- Main Content -->
<main class="max-w-screen-xl mx-auto mt-8 px-6 hidden" id="page-content-main">
    <?php include('./index/futsal_posts.php'); ?>

    <?php include('./index/futsal_reviews.php'); ?>

</main>

<?php include('./includes/footer.php'); ?>


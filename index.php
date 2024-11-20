<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal Booking Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-zinc-700 to-zinc-500 text-white">

    <!-- Navigation Bar -->
    <nav class="bg-yellow-500 shadow-md">
        <div class="max-w-screen-xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-4">
                <img src="https://via.placeholder.com/150x50?text=Futsal+Booking" alt="Futsal Logo" class="w-24">
            </div>
            
            <!-- Area Selection Dropdown -->
            <div class="flex items-center space-x-4">
                <select class="p-2 bg-zinc-600 text-white border border-yellow-300 rounded-md">
                    <option value="all">All Areas</option>
                    <option value="colombo">Colombo</option>
                    <option value="kandy">Kandy</option>
                    <option value="nuwara-eliya">Nuwara Eliya</option>
                    <option value="galle">Galle</option>
                    <option value="matara">Matara</option>
                    <option value="kurunegala">Kurunegala</option>
                    <option value="jaffna">Jaffna</option>
                    <option value="anuradhapura">Anuradhapura</option>
                    <option value="badulla">Badulla</option>
                    <option value="trincomalee">Trincomalee</option>
                    <!-- Add more districts as needed -->
                </select>
                <button class="text-white hover:text-yellow-200">Sign In</button>
            </div>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="w-2/3 mx-auto px-6 mt-6 flex space-x-2 items-center">
        <input type="date" class="p-2 border rounded-lg shadow-md bg-zinc-600 text-white" placeholder="Select Date">
        <input type="time" class="p-2 border rounded-lg shadow-md bg-zinc-600 text-white" placeholder="Select Time">
        <input type="number" placeholder="Number of Players" class="p-2 w-40 border rounded-lg shadow-md bg-zinc-600 text-white">
        <input type="text" placeholder="Location or Venue Name" class="p-2 flex-grow rounded-lg shadow-md bg-zinc-600 text-white">
        <button class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600">Search</button>
    </div>

    <!-- Main Content -->
    <main class="max-w-screen-xl mx-auto mt-8 px-6">
        <!-- Available Slots -->
        <section>
            <h2 class="text-2xl font-bold text-yellow-500 mb-4">Available Futsal Courts</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Futsal Owner's Post -->
                <div class="bg-zinc-800 border rounded-lg overflow-hidden shadow-md">
                    <img src="https://via.placeholder.com/300x200?text=Futsal+Court" alt="Futsal Court" class="w-full">
                    <div class="p-4">
                        <h3 class="font-bold text-lg">Green Turf Futsal - Colombo</h3>
                        <p class="text-gray-300">Synthetic Turf · Night Lights · Indoor Facility</p>
                        <p class="text-gray-300 mt-2">Price: LKR 2,000/hour</p>
                        <div class="flex space-x-2 mt-4">
                            <button class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">6:00 PM</button>
                            <button class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">7:00 PM</button>
                            <button class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">8:00 PM</button>
                        </div>
                    </div>
                </div>
                <div class="bg-zinc-800 border rounded-lg overflow-hidden shadow-md">
                    <img src="https://via.placeholder.com/300x200?text=Futsal+Court" alt="Futsal Court" class="w-full">
                    <div class="p-4">
                        <h3 class="font-bold text-lg">Pro Play Futsal - Kandy</h3>
                        <p class="text-gray-300">Artificial Grass · Covered Court</p>
                        <p class="text-gray-300 mt-2">Price: LKR 1,800/hour</p>
                        <div class="flex space-x-2 mt-4">
                            <button class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">5:00 PM</button>
                            <button class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">6:30 PM</button>
                            <button class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">8:00 PM</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Reviews Section -->
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-yellow-500 mb-4">Player Reviews</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-zinc-800 border rounded-lg overflow-hidden shadow-md p-4">
                    <h3 class="font-bold">John D.</h3>
                    <p class="text-sm text-gray-300">Played on Nov 15, 2024</p>
                    <p class="mt-2">Great court! Smooth turf and well-lit for evening matches.</p>
                </div>
                <div class="bg-zinc-800 border rounded-lg overflow-hidden shadow-md p-4">
                    <h3 class="font-bold">Emily R.</h3>
                    <p class="text-sm text-gray-300">Played on Nov 10, 2024</p>
                    <p class="mt-2">Highly recommend the Kandy venue—friendly staff and top-notch facilities.</p>
                </div>
                <div class="bg-zinc-800 border rounded-lg overflow-hidden shadow-md p-4">
                    <h3 class="font-bold">Ahmed S.</h3>
                    <p class="text-sm text-gray-300">Played on Nov 8, 2024</p>
                    <p class="mt-2">Perfect place to enjoy futsal with friends. Would book again!</p>
                </div>
            </div>
        </section>

        <!-- Explore Locations -->
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-yellow-500 mb-4">Explore Locations</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <ul>
                    <li class="font-bold">Colombo</li>
                    <li class="text-gray-300">Green Turf Futsal</li>
                    <li class="text-gray-300">Active Futsal Club</li>
                </ul>
                <ul>
                    <li class="font-bold">Kandy</li>
                    <li class="text-gray-300">Pro Play Futsal</li>
                </ul>
                <ul>
                    <li class="font-bold">Galle</li>
                    <li class="text-gray-300">South Coast Futsal</li>
                </ul>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-yellow-500 text-white mt-16 py-8">
        <div class="max-w-screen-xl mx-auto px-6 text-sm text-center">
            <p>&copy; 2024 Futsal Booking Platform. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

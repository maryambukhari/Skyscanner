<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelX - Flight & Hotel Search</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 1200px;
            width: 90%;
            text-align: center;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 2rem;
            animation: fadeIn 1s ease-in;
        }
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .tab {
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.1);
            margin: 0 1rem;
            cursor: pointer;
            border-radius: 10px;
            transition: background 0.3s;
        }
        .tab:hover, .tab.active {
            background: #fff;
            color: #1e3c72;
        }
        .search-box {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: slideUp 1s ease-in;
        }
        .search-box input, .search-box select {
            padding: 0.8rem;
            margin: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .search-box button {
            padding: 0.8rem 2rem;
            background: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .search-box button:hover {
            background: #e55a50;
        }
        .auth-links {
            margin-top: 1rem;
        }
        .auth-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 1rem;
            font-size: 1.1rem;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @media (max-width: 768px) {
            h1 { font-size: 2rem; }
            .search-box input, .search-box select { width: 100%; }
            .tab { padding: 0.5rem 1rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>TravelX - Explore the World</h1>
        <div class="tabs">
            <div class="tab active" onclick="showTab('flight')">Flights</div>
            <div class="tab" onclick="showTab('hotel')">Hotels</div>
        </div>
        <div class="search-box" id="flight-search">
            <form id="flight-form" method="POST" action="search_results.php">
                <input type="text" name="origin" placeholder="From (e.g., NYC)" required>
                <input type="text" name="destination" placeholder="To (e.g., LAX)" required>
                <input type="date" name="departure" required>
                <input type="date" name="return">
                <select name="passengers">
                    <option value="1">1 Passenger</option>
                    <option value="2">2 Passengers</option>
                    <option value="3">3 Passengers</option>
                    <option value="4">4 Passengers</option>
                </select>
                <input type="hidden" name="search_type" value="flight">
                <button type="submit">Search Flights</button>
            </form>
        </div>
        <div class="search-box" id="hotel-search" style="display: none;">
            <form id="hotel-form" method="POST" action="search_results.php">
                <input type="text" name="destination" placeholder="Destination (e.g., Paris)" required>
                <input type="date" name="check_in" required>
                <input type="date" name="check_out" required>
                <select name="guests">
                    <option value="1">1 Guest</option>
                    <option value="2">2 Guests</option>
                    <option value="3">3 Guests</option>
                    <option value="4">4 Guests</option>
                </select>
                <input type="hidden" name="search_type" value="hotel">
                <button type="submit">Search Hotels</button>
            </form>
        </div>
        <div class="auth-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="signup.php">Sign Up</a> | <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>

<?php
// search_results.php
session_start();
require 'db.php';

// Dummy data for demonstration (replace with API integration)
$flights = [
    ['provider' => 'Airline A', 'price' => 200, 'duration' => '5h', 'stops' => 0, 'rating' => 4.5, 'details' => 'Non-stop, Economy'],
    ['provider' => 'Airline B', 'price' => 150, 'duration' => '6h', 'stops' => 1, 'rating' => 4.0, 'details' => '1 Stop, Economy'],
    ['provider' => 'Airline C', 'price' => 300, 'duration' => '4h', 'stops' => 0, 'rating' => 4.8, 'details' => 'Non-stop, Business'],
];
$hotels = [
    ['provider' => 'Hotel X', 'price' => 100, 'rating' => 4.2, 'details' => 'City Center, Breakfast Included'],
    ['provider' => 'Hotel Y', 'price' => 120, 'rating' => 4.5, 'details' => 'Near Airport, Free Wi-Fi'],
    ['provider' => 'Hotel Z', 'price' => 80, 'rating' => 3.8, 'details' => 'Downtown, Parking Available'],
];

// Save search if user is logged in and form data is present
if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_type = $_POST['search_type'];
    $origin = isset($_POST['origin']) ? $_POST['origin'] : null;
    $destination = $_POST['destination'];
    $check_in = $_POST['check_in'] ?? $_POST['departure'];
    $check_out = $_POST['check_out'] ?? $_POST['return'] ?? null;
    $guests = $_POST['guests'] ?? $_POST['passengers'] ?? 1;

    try {
        $stmt = $pdo->prepare("INSERT INTO saved_searches (user_id, search_type, origin, destination, check_in_date, check_out_date, passengers) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $search_type, $origin, $destination, $check_in, $check_out, $guests]);
    } catch (PDOException $e) {
        $error = "Error saving search: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - TravelX</title>
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
            padding: 2rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeIn 1s ease-in;
        }
        .filters {
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .filters select, .filters input {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .results {
            display: grid;
            gap: 1rem;
        }
        .result-card {
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            color: #1e3c72;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideUp 0.5s ease-in;
        }
        .result-card button {
            padding: 0.5rem 1rem;
            background: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .result-card button:hover {
            background: #e55a50;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
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
            .filters { flex-direction: column; }
            .result-card { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Search Results</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <div class="filters">
            <select onchange="sortResults(this.value)">
                <option value="price">Sort by Price</option>
                <option value="duration">Sort by Duration</option>
                <option value="rating">Sort by Rating</option>
            </select>
            <input type="number" placeholder="Max Price" oninput="filterResults(this.value)">
            <select onchange="filterStops(this.value)">
                <option value="all">All Stops</option>
                <option value="0">Non-stop</option>
                <option value="1">1 Stop</option>
            </select>
        </div>
        <div class="results" id="results">
            <?php if (!isset($_POST['search_type'])): ?>
                <p class="error">No search parameters provided.</p>
            <?php elseif ($_POST['search_type'] == 'flight'): ?>
                <?php foreach ($flights as $flight): ?>
                    <div class="result-card">
                        <div>
                            <h3><?php echo $flight['provider']; ?></h3>
                            <p>Price: $<?php echo $flight['price']; ?></p>
                            <p>Duration: <?php echo $flight['duration']; ?></p>
                            <p>Stops: <?php echo $flight['stops']; ?></p>
                            <p>Rating: <?php echo $flight['rating']; ?>/5</p>
                            <p>Details: <?php echo $flight['details']; ?></p>
                        </div>
                        <form method="POST" action="booking.php">
                            <input type="hidden" name="provider" value="<?php echo $flight['provider']; ?>">
                            <input type="hidden" name="type" value="flight">
                            <input type="hidden" name="price" value="<?php echo $flight['price']; ?>">
                            <input type="hidden" name="details" value="<?php echo $flight['details']; ?>">
                            <button type="submit">Book Now</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($hotels as $hotel): ?>
                    <div class="result-card">
                        <div>
                            <h3><?php echo $hotel['provider']; ?></h3>
                            <p>Price: $<?php echo $hotel['price']; ?>/night</p>
                            <p>Rating: <?php echo $hotel['rating']; ?>/5</p>
                            <p>Details: <?php echo $hotel['details']; ?></p>
                        </div>
                        <form method="POST" action="booking.php">
                            <input type="hidden" name="provider" value="<?php echo $hotel['provider']; ?>">
                            <input type="hidden" name="type" value="hotel">
                            <input type="hidden" name="price" value="<?php echo $hotel['price']; ?>">
                            <input type="hidden" name="details" value="<?php echo $hotel['details']; ?>">
                            <button type="submit">Book Now</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a href="index.php">Back to Search</a>
    </div>
    <script src="script.js"></script>
</body>
</html>

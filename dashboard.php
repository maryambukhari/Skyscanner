<?php
// dashboard.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM saved_searches WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$searches = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TravelX</title>
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
        .card {
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            color: #1e3c72;
            margin-bottom: 1rem;
            animation: slideUp 0.5s ease-in;
        }
        a {
            color: #ff6f61;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 1rem;
        }
        a:hover {
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
            .card { padding: 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <h3>Saved Searches</h3>
        <?php foreach ($searches as $search): ?>
            <div class="card">
                <p>Type: <?php echo ucfirst($search['search_type']); ?></p>
                <?php if ($search['search_type'] == 'flight'): ?>
                    <p>From: <?php echo $search['origin']; ?> To: <?php echo $search['destination']; ?></p>
                <?php else: ?>
                    <p>Destination: <?php echo $search['destination']; ?></p>
                <?php endif; ?>
                <p>Dates: <?php echo $search['check_in_date']; ?> to <?php echo $search['check_out_date']; ?></p>
                <p>Guests/Passengers: <?php echo $search['passengers']; ?></p>
            </div>
        <?php endforeach; ?>
        <h3>Booking History</h3>
        <?php foreach ($bookings as $booking): ?>
            <div class="card">
                <p>Type: <?php echo ucfirst($booking['type']); ?></p>
                <p>Provider: <?php echo $booking['provider']; ?></p>
                <p>Price: $<?php echo $booking['price']; ?></p>
                <p>Details: <?php echo $booking['details']; ?></p>
                <p>Date: <?php echo $booking['booking_date']; ?></p>
            </div>
        <?php endforeach; ?>
        <a href="index.php">Back to Home</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

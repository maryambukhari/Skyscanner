<?php
// booking.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $provider = $_POST['provider'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $details = $_POST['details'];

    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, type, provider, details, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $type, $provider, $details, $price]);
        $message = "Booking saved successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - TravelX</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            color: #1e3c72;
            animation: slideUp 1s ease-in;
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        p {
            margin: 0.5rem 0;
        }
        .message {
            color: green;
            text-align: center;
            margin-bottom: 1rem;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }
        button {
            padding: 0.8rem 2rem;
            background: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            display: block;
            margin: 1rem auto;
        }
        button:hover {
            background: #e55a50;
        }
        a {
            color: #ff6f61;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        a:hover {
            text-decoration: underline;
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @media (max-width: 768px) {
            .container { padding: 1rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Booking Confirmation</h2>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($_POST['provider'])): ?>
            <p>Provider: <?php echo htmlspecialchars($_POST['provider']); ?></p>
            <p>Type: <?php echo ucfirst($_POST['type']); ?></p>
            <p>Price: $<?php echo htmlspecialchars($_POST['price']); ?></p>
            <p>Details: <?php echo htmlspecialchars($_POST['details']); ?></p>
            <button onclick="completeBooking('<?php echo htmlspecialchars($_POST['provider']); ?>', '<?php echo htmlspecialchars($_POST['type']); ?>')">Proceed to Provider</button>
        <?php else: ?>
            <p class="error">No booking details provided.</p>
        <?php endif; ?>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <script src="script.js"></script>
</body>
</html>

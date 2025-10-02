<?php
// price_alert.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_id = $_POST['search_id'];
    $target_price = $_POST['target_price'];

    try {
        $stmt = $pdo->prepare("INSERT INTO price_alerts (user_id, search_id, target_price) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $search_id, $target_price]);
        $message = "Price alert set successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch user's saved searches and active alerts
$stmt = $pdo->prepare("SELECT * FROM saved_searches WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$searches = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT pa.*, s.search_type, s.destination FROM price_alerts pa JOIN saved_searches s ON pa.search_id = s.id WHERE pa.user_id = ? AND pa.status = 'active'");
$stmt->execute([$_SESSION['user_id']]);
$alerts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Alerts - TravelX</title>
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
        .form-container, .alert-card {
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            color: #1e3c72;
            margin-bottom: 1rem;
            animation: slideUp 0.5s ease-in;
        }
        .form-container select, .form-container input {
            padding: 0.5rem;
            margin: 0.5rem 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        .form-container button {
            padding: 0.5rem 1rem;
            background: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .form-container button:hover {
            background: #e55a50;
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
            .form-container, .alert-card { padding: 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Price Alerts</h2>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <div class="form-container">
            <h3>Set New Price Alert</h3>
            <?php if (empty($searches)): ?>
                <p class="error">No saved searches found. Perform a search first.</p>
            <?php else: ?>
                <form method="POST">
                    <select name="search_id" required>
                        <option value="">Select Saved Search</option>
                        <?php foreach ($searches as $search): ?>
                            <option value="<?php echo $search['id']; ?>">
                                <?php echo ucfirst($search['search_type']) . ' to ' . $search['destination'] . ' (' . $search['check_in_date'] . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="target_price" placeholder="Target Price (USD)" required>
                    <button type="submit">Set Alert</button>
                </form>
            <?php endif; ?>
        </div>
        <h3>Active Alerts</h3>
        <?php if (empty($alerts)): ?>
            <p>No active price alerts.</p>
        <?php else: ?>
            <?php foreach ($alerts as $alert): ?>
                <div class="alert-card">
                    <p>Type: <?php echo ucfirst($alert['search_type']); ?></p>
                    <p>Destination: <?php echo $alert['destination']; ?></p>
                    <p>Target Price: $<?php echo $alert['target_price']; ?></p>
                    <p>Status: <?php echo ucfirst($alert['status']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

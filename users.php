<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=web;charset=utf8", "root", "Meena@2005");

if (isset($_GET['logout']) && isset($_SESSION['session_id'])) {
    $stmt = $pdo->prepare("UPDATE sessions SET session_end = NOW(), duration = TIMESTAMPDIFF(SECOND, session_start, NOW()) WHERE id = :session_id");
    $stmt->execute(['session_id' => $_SESSION['session_id']]);
    session_destroy();
    header("Location: users.php");  // Redirect to users.php to view session data
    exit;
}

// Retrieve all session data ordered by the latest session first
$sessions = $pdo->query("SELECT u.username, s.session_start, s.session_end, s.duration FROM users u JOIN sessions s ON u.id = s.user_id ORDER BY s.session_start DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Sessions</title>
    <style>
        /* General Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            width: 80%;
            max-width: 800px;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 0.75rem;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #ff9a9e;
            color: #fff;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        td {
            color: #555;
        }

        /* Back Button Styling */
        .button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            color: white;
            background-color: #ff9a9e;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            margin-top: 1.5rem;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #fa7d8c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Sessions</h1>
        <table>
            <tr>
                <th>Username</th>
                <th>Login Time</th>
                <th>Logout Time</th>
                <th>Session Duration (seconds)</th>
            </tr>
            <?php foreach ($sessions as $session): ?>
                <tr>
                    <td><?php echo htmlspecialchars($session['username']); ?></td>
                    <td><?php echo htmlspecialchars($session['session_start']); ?></td>
                    <td><?php echo htmlspecialchars($session['session_end']); ?></td>
                    <td><?php echo htmlspecialchars($session['duration']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="app.php" class="button">Back to Login</a>
    </div>
</body>
</html>

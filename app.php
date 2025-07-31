<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=web;charset=utf8", "root", "Meena@2005");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (isset($_POST['register'])) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
        if ($stmt->execute(['username' => $username, 'password_hash' => $password_hash])) {
            echo "Registered successfully. Please login.";
        } else {
            echo "Registration failed.";
        }
    } elseif (isset($_POST['login'])) {
        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login_time'] = time();

            $stmt = $pdo->prepare("INSERT INTO sessions (user_id, session_start) VALUES (:user_id, NOW())");
            $stmt->execute(['user_id' => $user['id']]);
            $_SESSION['session_id'] = $pdo->lastInsertId();

            header("Location: book.php");
            exit;
        } else {
            echo "Invalid credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register or Login</title>
    <style>
        /* CSS styling */
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
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .form-container {
            width: 100%;
        }

        .form-box {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        h2 {
            font-size: 1.5rem;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #6e8efb;
            outline: none;
        }

        button[type="submit"] {
            padding: 0.75rem;
            font-size: 1rem;
            background-color: #6e8efb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #5a73d4;
        }

        p {
            font-size: 0.9rem;
            color: #555;
        }

        p a {
            color: #6e8efb;
            text-decoration: none;
            font-weight: bold;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Register or Login</h1>
            <?php if (isset($_GET['login'])): ?>
                <!-- Login form -->
                <form method="POST" class="form-box">
                    <h2>Login</h2>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
            <?php else: ?>
                <!-- Registration form -->
                <form method="POST" class="form-box">
                    <h2>Register</h2>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register">Register</button>
                    <p>Already have an account? <a href="app.php?login">Login</a></p>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

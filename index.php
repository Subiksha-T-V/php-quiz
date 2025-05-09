<?php
// Start session to keep track of user info
session_start();

// Always prompt for username and genre selection
if (isset($_POST['username']) && isset($_POST['genre'])) {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['genre'] = $_POST['genre'];

    // Redirect to quiz page after form submission
    header('Location: quiz.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, red, orange, yellow, green, blue, indigo, violet);
            background-size: 1400% 1400%;
            animation: rainbow 10s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #fff;
        }

        @keyframes rainbow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 90%;
            text-align: center;
        }

        .container h1 {
            color: #6a0dad;
            margin-bottom: 20px;
        }

        input[type="text"], select {
            width: 90%;
            padding: 12px;
            margin: 15px 0;
            border: 2px solid #6a0dad;
            border-radius: 10px;
            font-size: 16px;
            outline: none;
            background: #f4f1ff;
            color: #333;
            transition: 0.3s ease;
        }

        select {
            background-color: #f0e6ff;
            font-weight: bold;
            color: #6a0dad;
        }

        input[type="text"]:focus, select:focus {
            border-color: #9b5de5;
            background-color: #fdf8ff;
        }

        button {
            background-color: #a14cc6;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s ease-in-out;
        }

        button:hover {
            background-color: #6a0dad;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Quiz App!</h1>

        <!-- Always ask for username and genre -->
        <form action="index.php" method="post">
            <input type="text" name="username" placeholder="Enter your name" required>
            <select name="genre" required>
                <option value="Tamil Movies">Tamil Movies</option>
                <option value="IPL">IPL</option>
                <option value="General Knowledge on India">General Knowledge on India</option>
                <option value="Science">Science</option>
                <option value="History">History</option>
            </select>
            <button type="submit">Start Quiz</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
include('db.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['genre']) || !isset($_SESSION['answers'])) {
    header('Location: index.php');
    exit();
}

$username = $_SESSION['username'];
$genre = $_SESSION['genre'];
$answers = $_SESSION['answers'];
$score = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 30px 10px;
            background: #f7f8fc;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            max-width: 850px;
            width: 100%;
            background: #fff;
            padding: 30px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .score-box {
            background:rgb(246, 41, 41);
            color: #fff;
            text-align: center;
            font-size: 1.4em;
            padding: 12px;
            border-radius: 12px;
            margin: 20px 0;
        }

        .flip-card {
            background-color: transparent;
            width: 100%;
            perspective: 1000px;
            margin-bottom: 25px;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            min-height: 180px;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 95%;
            backface-visibility: hidden;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            color: #333;
        }

        .flip-card-front {
            background: var(--front-bg);
        }

        .flip-card-back {
            background: var(--back-bg);
            transform: rotateY(180deg);
        }

        .question-title {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .answer-text {
            font-size: 1em;
            margin-bottom: 8px;
        }

        .correct {
            color: #27ae60;
            font-weight: bold;
        }

        .wrong {
            color: #c0392b;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Quiz Results for <?php echo $username; ?> on <?php echo $genre; ?></h1>

    <?php
    // Calculate score
    foreach ($answers as $answer) {
        $question_id = $answer['question_id'];
        $selected_answer = $answer['selected'];
        $sql = "SELECT correct_answer FROM questions_new WHERE id = '$question_id'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($selected_answer == $row['correct_answer']) {
                $score++;
            }
        }
    }
    ?>

    <div class="score-box">Your Total Score: <?php echo $score; ?> / 10</div>
    <h3>Review Your Answers:</h3>

    <?php
    // Mild pastel gradients
    $pastelGradients = [
        ["#fceabb", "#f8b500"],
        ["#fbd3e9", "#bb377d"],
        ["#d4fc79", "#96e6a1"],
        ["#cfd9df", "#e2ebf0"],
        ["#fbc2eb", "#a6c1ee"],
        ["#ffecd2", "#fcb69f"],
        ["#e0c3fc", "#8ec5fc"],
        ["#f6d365", "#fda085"],
        ["#d9a7c7", "#fffcdc"],
        ["#a1c4fd", "#c2e9fb"]
    ];

    $i = 0;

    foreach ($answers as $answer) {
        $question_id = $answer['question_id'];
        $selected_answer = $answer['selected'];

        $sql = "SELECT * FROM questions_new WHERE id = '$question_id'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $question = $result->fetch_assoc();
            $correct_answer = $question['correct_answer'];

            $option_map = [
                'A' => $question['option_a'],
                'B' => $question['option_b'],
                'C' => $question['option_c'],
                'D' => $question['option_d']
            ];

            $front_bg = $pastelGradients[$i % count($pastelGradients)][0];
            $back_bg = $pastelGradients[$i % count($pastelGradients)][1];

            echo "<div class='flip-card' style='--front-bg: linear-gradient(135deg, $front_bg, $back_bg); --back-bg: linear-gradient(135deg, $back_bg, $front_bg);'>";
            echo "<div class='flip-card-inner'>";

            echo "<div class='flip-card-front'>";
            echo "<p class='question-title'>Q: " . $question['question'] . "</p>";
            echo "<p class='answer-text'><strong>Your Answer:</strong> " . $option_map[$selected_answer] . "</p>";
            echo $selected_answer == $correct_answer
                ? "<p class='correct'>✔ Correct</p>"
                : "<p class='wrong'>✘ Incorrect</p>";
            echo "</div>";

            echo "<div class='flip-card-back'>";
            echo "<p class='question-title'>Correct Answer:</p>";
            echo "<p class='answer-text'>" . $option_map[$correct_answer] . "</p>";
            echo "</div>";

            echo "</div></div>";
            $i++;
        } else {
            echo "<p>Question with ID $question_id not found.</p>";
        }
    }
    ?>
</div>

</body>
</html>

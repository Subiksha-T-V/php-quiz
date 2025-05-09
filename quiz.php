<?php
session_start();

// Check if the user is already logged in and has selected a genre
if (!isset($_SESSION['username']) || !isset($_SESSION['genre'])) {
    header('Location: index.php'); // Redirect to the home page if not logged in
    exit();
}

$username = $_SESSION['username'];
$genre = $_SESSION['genre'];

include('db.php');

// Fetch questions based on the selected genre
$sql = "SELECT * FROM questions_new WHERE genre = '$genre' ORDER BY RAND() LIMIT 10";
$result = $conn->query($sql);

// Initialize answers and correct answers arrays
$answers = [];
$correct_answers = [];
$question_order = []; // Store the question order

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Loop through each question and save the user's selected answer
    for ($i = 1; $i <= 10; $i++) {
        if (isset($_POST['q' . $i])) {
            $selected_answer = $_POST['q' . $i];
            $answers[] = [
                'question_id' => $i,
                'selected' => $selected_answer
            ];
        }
    }

    // Fetch correct answers from the database and store them
    while ($row = $result->fetch_assoc()) {
        $correct_answers[] = $row['correct_answer'];  // Assuming 'correct_answer' column contains the right option
        $question_order[] = $row['question_id']; // Store the question ID
    }

    // Store the answers, correct answers, and question order in the session
    $_SESSION['answers'] = $answers;
    $_SESSION['correct_answers'] = $correct_answers;
    $_SESSION['question_order'] = $question_order; // Store the question order

    // Redirect to the result page after storing the answers
    header('Location: result.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz: <?php echo $genre; ?></title>
    <style>
        /* Reset and base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg,rgb(233, 95, 233) 0%,rgb(116, 75, 132) 100%);
    color: #333;
    display: flex;
    justify-content: center;
    padding: 40px 20px;
    background-size: cover;
    background-position: center;
}

.container {
    background: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    width: 100%;
    text-align: center;
}

h1 {
    font-size: 2.5em;
    margin-bottom: 20px;
    color:rgb(96, 50, 50);
}

h2, h3 {
    color: #444;
    margin-bottom: 15px;
}

form {
    margin-top: 20px;
    text-align: left;
}

input[type="text"], select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 2px solid #ddd;
    border-radius: 10px;
    font-size: 1em;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
select:focus {
    border-color: #ff6b6b;
    outline: none;
}

button {
    background-color: #ff6b6b;
    color: #fff;
    border: none;
    padding: 12px 24px;
    font-size: 1.1em;
    border-radius: 30px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 15px;
}

button:hover {
    background-color: #e95a5a;
}

.question {
    margin-bottom: 25px;
    padding: 15px 20px;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    background-color: #f9f9f9;
}

.question:nth-child(odd) {
    background-color:rgb(203, 232, 43);
}

.question:nth-child(even) {
    background-color:rgb(38, 217, 248);
}

.answer-options {
    margin-top: 10px;
}

.answer-options label {
    display: block;
    margin: 8px 0;
    font-size: 1em;
    cursor: pointer;
}

/* Removing custom radio button styles, using default ones */
input[type="radio"] {
    margin-right: 10px;
}

hr {
    margin: 30px 0;
    border: none;
    height: 1px;
    background: #ddd;
}

/* Background images based on genre */
body[data-genre="Tamil Movies"] {
    background-image: url('/images/images.jpg');
}

body[data-genre="IPL"] {
    background-image: url('path/to/ipl-bg.jpg');
}

body[data-genre="General Knowledge on India"] {
    background-image: url('path/to/india-gk-bg.jpg');
}

body[data-genre="Science"] {
    background-image: url('path/to/science-bg.jpg');
}

body[data-genre="History"] {
    background-image: url('path/to/history-bg.jpg');
}

    </style>

</head>
<body>
    <div class="container">
        <h1> Let's start the quiz on <?php echo $genre; ?>.</h1>

        <form action="quiz.php" method="post">
            <?php
            $count = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<div class='question'>
                        <p>$count. " . $row['question'] . "</p>
                        <div class='answer-options'>
                            <input type='radio' name='q$count' value='A' required> " . $row['option_a'] . "<br>
                            <input type='radio' name='q$count' value='B'> " . $row['option_b'] . "<br>
                            <input type='radio' name='q$count' value='C'> " . $row['option_c'] . "<br>
                            <input type='radio' name='q$count' value='D'> " . $row['option_d'] . "<br>
                        </div>
                      </div>";
                $count++;
            }
            ?>
            <button type="submit">Submit Answers</button>
        </form>
    </div>
</body>
</html>

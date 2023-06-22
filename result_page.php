<!DOCTYPE html>
<html>

<head>
    <title>MCQ Test Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1,
        h2 {
            margin-top: 20px;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-top: 30px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    require 'db.php';

    // Retrieve the student ID from the query string
    $studentId = $_SESSION['student_id'];

    // Retrieve the student information
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = :student_id");
    $stmt->bindParam(':student_id', $studentId);
    $stmt->execute();
    $student = $stmt->fetch();




    // Retrieve the subject-wise statistics
    $stmt = $conn->prepare("SELECT subjects.name AS subject_name, COUNT(DISTINCT test_results.question_id) AS attempt_count, COUNT(CASE WHEN answers.is_correct = 1 THEN 1 END) AS correct_answers, COUNT(test_results.question_id) AS total_questions,
                            (COUNT(CASE WHEN answers.is_correct = 1 THEN 1 END) / 10) * 100 AS percentage
                            FROM test_results
                            INNER JOIN questions ON test_results.question_id = questions.id
                            INNER JOIN subjects ON questions.subject_id = subjects.id
                            LEFT JOIN answers ON test_results.answer_id = answers.id AND answers.is_correct = 1
                            WHERE test_results.student_id = :student_id
                            GROUP BY subjects.id");


    $stmt->bindParam(':student_id', $studentId);
    $stmt->execute();
    $subjectStats = $stmt->fetchAll();

    // Calculate the total percentage and pass/fail status
    $totalPercentage = 0;
    $totalPassFail = 'Fail';
    if (!empty($subjectStats)) {
        $totalPercentage = array_sum(array_column($subjectStats, 'percentage')) / count($subjectStats);
        $totalPassFail = ($totalPercentage >= 35) ? 'Pass' : 'Fail';
    }
    ?>

    <h1>MCQ Test Result</h1>
    <h2>Student Information</h2>
    <p>Name: <?php echo $student['name']; ?></p>
    <p>Roll Number: <?php echo $student['roll_no']; ?></p>

    <h2>Subject-wise Statistics</h2>
    <table>
        <tr>
            <th>Subject</th>
            <th>Attempt </th>
            <th>Correct </th>
            <th>Incorrect </th>
            <th>Total Questions</th>
            <th>Percentage</th>
        </tr>
        <?php foreach ($subjectStats as $stats) { ?>
            <tr>
                <td>
                    <center>
                        <?php echo $stats['subject_name']; ?>
                </td>
                </center>
                <td>
                    <center>
                        <?php echo $stats['attempt_count']; ?>
                </td>
                </center>
                <td>
                    <center>
                        <?php echo $stats['correct_answers']; ?>
                </td>
                </center>
                <td>
                    <center>
                        <?php echo (10 - $stats['correct_answers']); ?>
                </td>
                </center>
                <td>
                    <center>
                        <?php echo 10; ?>
                    </center>
                </td>
                <td>
                    <center>
                        <?php echo $stats['percentage']; ?>%
                </td>
                </center>
            </tr>
        <?php } ?>
    </table>

    <h2>Total Percentage: <?php echo $totalPercentage; ?>%</h2>
    <h2>Pass/Fail: <?php echo $totalPassFail; ?></h2>

    <form action="download_result.php" method="POST" id="quizForm" onsubmit="stopTimer()">
        <input type="hidden" name="student_id" value="<?php echo $studentId; ?>">
        <button type="submit">Download Result as CSV</button>
    </form>
    <script>
        function stopTimer() {
            clearInterval(timer); // Stop the countdown timer
            sessionStorage.removeItem('timeLimit'); // Remove the timer value from session storage
        }
    </script>
</body>

</html>
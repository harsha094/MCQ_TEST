<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mcq_Test</title>
</head>

<body>
    <!-- Countdown Timer and Form Submission Handling -->
    <div id="timer"></div>
</body>

</html>

<?php
session_start();
require 'db.php';
require 'timer_count.php';
$name = $_SESSION['name'];
$roll_number = $_SESSION['roll_number'];

// Retrieve the student ID from the query string
$query = $conn->prepare("SELECT id FROM students");

$studentId = $_SESSION['student_id'];
// var_dump($studentId);
// die;

// Retrieve the first subject and its questions from the database
$stmt = $conn->prepare("SELECT * FROM subjects LIMIT 1");
$stmt->execute();
$subject = $stmt->fetch();

$stmt = $conn->prepare("SELECT * FROM questions WHERE subject_id = :subject_id");
$stmt->bindParam(':subject_id', $subject['id']);
$stmt->execute();
$questions = $stmt->fetchAll();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store the student's responses in the database
    foreach ($_POST['answer'] as $questionId => $answerId) {
        $stmt = $conn->prepare("INSERT INTO test_results (student_id, question_id, answer_id) VALUES (:student_id, :question_id, :answer_id)");
        $stmt->bindParam(':student_id', $studentId);
        $stmt->bindParam(':question_id', $questionId);
        $stmt->bindParam(':answer_id', $answerId);
        $stmt->execute();
    }

    // Redirect to the next quiz page
    header("Location: mcq_test2.php");
    exit();
}
include "mcq_layout.php";
?>

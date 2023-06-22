<?php
session_start();
require 'db.php';
require 'timer_count.php';
// Retrieve the student ID from the query string
$studentId = $_SESSION['student_id'];

// Retrieve the first subject and its questions from the database
$stmt = $conn->prepare("SELECT * FROM subjects LIMIT 2,1");
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
    header("Location: mcq_test4.php");
    exit();
}

include "mcq_layout.php";
?>

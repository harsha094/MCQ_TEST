<?php
session_start();
require 'db.php';
$name=$_SESSION['name'];
$roll_no=$_SESSION['roll_number'];

// Retrieve the student ID from the form submission
$studentId = $_POST['student_id'];

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

    // $totalPercentage = 0;
    // $totalPassFail = 'Fail';
    // if (!empty($subjectStats)) {
    //     $totalPercentage = array_sum(array_column($subjectStats, 'percentage')) / count($subjectStats);
    //     $totalPassFail = ($totalPercentage >= 35) ? 'Pass' : 'Fail';
    // }
// Prepare the CSV data
//$csvData_student= "Student Name = .$name., Roll no = .$roll_no.";
$csvData = "Student Name, Roll Number\n";
$csvData .= $student['name'] . ',' . $student['roll_no'] . "\n";

$csvData .= "Subject, Attempt, Correct, Incorrect, Total Questions, Percentage\n";
foreach ($subjectStats as $stats) {
    $csvData .=$stats['subject_name'] . ',' . $stats['attempt_count'] . ',' . $stats['correct_answers'] . ',' . (10 - $stats['correct_answers']) . ',' . 10 . ',' . $stats['percentage'] . "\n";
}
// $csvData = "student name, roll number, Subject, Attempt, Correct, Incorrect, Total Questions,Percentage\n";
// foreach ($subjectStats as $stats) {
//     $csvData .= $name . ',' . $roll_no . ',' . $stats['subject_name'] . ',' . $stats['attempt_count'] . ',' . $stats['correct_answers'] . ',' . (10 - $stats['correct_answers']) . ',' . 10 . ',' . $stats['percentage'] . "\n";
// }

// Set the appropriate headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="result.csv"');

// Output the CSV data
//echo ($csvData_student);
echo $csvData;
exit();
?>

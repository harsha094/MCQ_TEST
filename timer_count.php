<?php
require 'db.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCQ</title>
</head>

<body>
    <!-- Countdown Timer and Form Submission Handling -->
    <div id="timer"></div>
    <script>
        var timeLimit = sessionStorage.getItem('timeLimit') || 600; // Retrieve the timer value from session storage or set it to 10 minutes (600 seconds)
        var timer;

        function startTimer() {
            timer = setInterval(updateTimer, 1000); // Update the timer every second
        }

        function updateTimer() {
            var minutes = Math.floor(timeLimit / 60);
            var seconds = timeLimit % 60;

            var timerElement = document.getElementById("timer");
            timerElement.textContent = minutes.toString().padStart(2, '0') + "m " + seconds.toString().padStart(2, '0') + "s";

            if (timeLimit <= 0) {
                clearInterval(timer);
                alert("Your time is up!");
                document.getElementById("quizForm").submit(); // Submit the quiz form
            }

            timeLimit--;
            sessionStorage.setItem('timeLimit', timeLimit); // Store the updated timer value in session storage
        }

        function stopTimer() {
            clearInterval(timer); // Stop the countdown timer
            sessionStorage.removeItem('timeLimit'); // Remove the timer value from session storage
        }
    </script>
</body>

</html>
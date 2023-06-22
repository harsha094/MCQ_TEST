<!DOCTYPE html>
<html>

<head>
    <title>MCQ Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .question {
            margin-bottom: 20px;
            /* text-align: center; */
            /* Center align the question */
        }

        h1 {
            background-color: #ffcccc;
            text-align: center;
            /* Center align the subject title */
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* Center align the container content */
        }

        #timer {
            font-size: 24px;
            text-align: center;
            /* Center align the timer */
            margin-bottom: 20px;
        }

        .btn-primary {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div id="timer"></div>

            <h1><?php echo $subject['name']; ?></h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <?php foreach ($questions as $question) { ?>
                    <div class="question">
                        <h3><?php echo $question['question_text']; ?></h3>
                        <?php
                        // Retrieve the options for the current question
                        $stmt = $conn->prepare("SELECT * FROM answers WHERE question_id = :question_id");
                        $stmt->bindParam(':question_id', $question['id']);
                        $stmt->execute();
                        $options = $stmt->fetchAll();
                        ?>
                        <?php foreach ($options as $option) { ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer[<?php echo $question['id']; ?>]" value="<?php echo $option['id']; ?>">
                                <label class="form-check-label">
                                    <?php echo $option['answer_text']; ?>
                                </label>
                            </div>
                        <?php } ?>

                    </div>
                <?php } ?>
        </div>
        <div class="col-md-2"></div>
        <button type="submit" class="btn btn-primary">Next</button>
        </form>
    </div>

    <!-- <script src="timer.js"></script> -->

    <script>
        window.onload = function() {
            startTimer();
            document.getElementById("quizForm").addEventListener("submit", stopTimer);
        };
    </script>





    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
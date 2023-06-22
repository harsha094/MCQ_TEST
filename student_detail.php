<?php
session_start();
require 'db.php';

// Define variables and set to empty values
$name = $rollNumber = "";
$nameErr = $rollNumberErr = "";

// Function to validate input data
function validateInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate name
function validateName($name)
{
    if (empty($name)) {
        return "Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        return "Only letters and white space allowed";
    } elseif (strlen($name) > 50) {
        return "Name should not exceed 50 characters";
    }
    return "";
}

// Function to validate roll number
function validateRollNumber($rollNumber)
{
    if (empty($rollNumber)) {
        return "Roll number is required";
    } elseif (!preg_match("/^[A-Z]{2}\d{4}$/", $rollNumber)) {
        return "Invalid roll number format. It should start with two capital letters followed by four digits (e.g., IN1234)";
    }
    return "";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = validateInput($_POST["name"]);
    $rollNumber = validateInput($_POST["rollNumber"]);

    $nameErr = validateName($name);
    $rollNumberErr = validateRollNumber($rollNumber);

    // If there are no errors, save user login in session and redirect to mcq.php
    if (empty($nameErr) && empty($rollNumberErr)) {
         // Insert student data into the students table
        
         //var_dump($rollNumber);
         $stmt = $conn->prepare("INSERT INTO students (name, roll_no) VALUES (:name, :roll_number)");
         $stmt->bindParam(':name', $name);
         $stmt->bindParam(':roll_number', $rollNumber);
         $stmt->execute();
 
         // Get the inserted student's ID
         $studentId = $conn->lastInsertId();
         //var_dump($name);
         // Save student ID in the session
         $_SESSION["student_id"] = $studentId;
         $_SESSION['name']= $name;
         $_SESSION['roll_number']=$rollNumber;

         header("Location: mcq_test1.php");
         exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Detail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h2 class="mt-5 text-center">Enter the Student Details</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $name; ?>">
                <span class="text-danger"><?php echo $nameErr; ?></span><br>
            </div>
            <div class="mb-3">
                <label for="rollNumber" class="form-label">Roll Number:</label>
                <input type="text" name="rollNumber" id="rollNumber" class="form-control" value="<?php echo $rollNumber; ?>">
                <span class="text-danger"><?php echo $rollNumberErr; ?></span>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Take Test</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
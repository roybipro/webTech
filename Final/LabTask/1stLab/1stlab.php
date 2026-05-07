<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
$marks = array(88, 76, 90, 85, 92, 45);


$total = 0;
$passed = 0;

foreach($marks as $mark){
    echo $mark . "<br>";
    
    $total += $mark;
    
    if($mark >= 50){
        $passed++;
        }
        }
        
        $average = $total / count($marks);
        $minimum = min($marks);
        $maximum = max($marks);
        
        echo "Total: " . $total . "<br>";
        echo "Average: " . $average . "<br>";
        echo "Minimum: " . $minimum . "<br>";
        echo "Maximum: " . $maximum . "<br>";
        echo "Passed: " . $passed . "<br>";


        $studentInfo = array(
            "name" => "salfi",
            "ID" => 23666,
            "CGPA" => 4.00,
        );
        foreach($studentInfo as $key => $value){
            echo $key . ": " . $value . "<br>";
        }
        function passedStudents($marks){
            $passed = 0;
            foreach($marks as $mark){
                if($mark >= 50){
                    $passed++;
                }
            }
            return $passed;
        }
        $passedCount = passedStudents($marks);
        echo "Number of passed students: " . $passedCount . "<br>";

        $fName = "Dipto";
        $lName = "Roy";
        echo "Full Name: " . $fName . " " . $lName . "<br>";
        strtoupper($fName);
        strtolower($lName);
        echo "Full Name: " . strtoupper($fName) . " " . strtolower($lName) . "<br>";
        echo "Length of first name: " . strlen($fName) . "<br>";

        echo "Number of students: " . count($marks) . "<br>";
        sort($marks);
        echo "Sorted marks: ";
        foreach($marks as $mark){
            echo $mark . " ";
        }

        if (isset($_GET["student_name"]) && $_GET["student_name"] != "") {
            echo "Student name entered: " . $_GET["student_name"];
        } else {
            echo "No student name entered.";
        }
        
?>
<form method="get">
        Enter student name:
        <input type="text" name="student_name">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
<?php
header("Content-Type: application/json");

$students = [
    ["name" => "Bipro Roy", "id" => "101", "dept" => "CSE", "cgpa" => "3.80"],
    ["name" => "Ayesha Khan", "id" => "102", "dept" => "EEE", "cgpa" => "3.75"],
    ["name" => "Rahim Ahmed", "id" => "103", "dept" => "BBA", "cgpa" => "3.60"]
];

echo json_encode($students);
?>
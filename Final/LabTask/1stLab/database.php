<?php

$conn = mysqli_connect("localhost", "root"); // Connect to MySQL database

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); // Stop if connection fails
} else {
    echo "Connected <br>"; // Connection successful message
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS myDatabase";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>"; // Success message
} else {
    die("Error creating database: " . $conn->error); // Stop if error
}

// Select the database
mysqli_select_db($conn, 'myDatabase');

// Create table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS profile (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    myname VARCHAR(30) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Table created successfully<br>"; // Table created successfully
} else {
    echo "Error creating table: " . mysqli_error($conn); // Show error if table creation fails
}

// Insert new record when form is submitted
if (isset($_POST["mysubmit"])) {

    $name = $_POST["myjname"]; // Get name from form

    // SQL query to insert new name into table
    $tabesql = "INSERT INTO profile (myname) VALUES ('$name')";

    if ($conn->query($tabesql) === true) {
        echo "New record created successfully"; // Success message
    } else {
        echo "Error: " . $tabesql . "<br>" . $conn->error; // Show error
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP CRUD Operation using OOP</title>
</head>
<body>

<?php if (isset($message)) echo "<p>$message</p>"; // Show message if exists ?>

<!-- Form to add new name -->
<form action="" method="post">
    <label for="name">Name</label>
    <input type="text" name="myjname" id="name">
    <button type="submit" name="mysubmit">Save</button>
</form>

<?php

// Handle delete request
if (isset($_POST['delete'])) {
    $id = $_POST['id']; // Get the ID to delete
    $delete_sql = "DELETE FROM profile WHERE id = $id"; // SQL delete query

    if ($conn->query($delete_sql) === true) {
        echo "Record deleted successfully"; // Success message
    } else {
        echo "Error deleting record: " . $conn->error; // Show error
    }
}

// Handle update request
if (isset($_POST['update'])) {
    $id = $_POST['id']; // Get the ID to update
    $new_name = $conn->real_escape_string($_POST['new_name']); // Get new name safely
    $update_sql = "UPDATE profile SET myname = '$new_name' WHERE id = $id"; // SQL update query

    if ($conn->query($update_sql) === true) {
        echo "Record updated successfully"; // Success message
    } else {
        echo "Error updating record: " . $conn->error; // Show error
    }
}

// Fetch all records from table
$sql = "SELECT id, myname FROM profile";
$result = $conn->query($sql);

// Display records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <p>My name is <?= htmlspecialchars($row['myname']) ?></p>

        <!-- Delete button form -->
        <form action="" method="POST" style="display:inline-block;">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button type="submit" name="delete">Delete</button>
        </form>

        <!-- Update button form -->
        <form action="" method="POST" style="display:inline-block;">
            <input type="text" name="new_name" placeholder="Enter new name">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button type="submit" name="update">Update</button>
        </form>
        <br><br>
        <?php
    }
} else {
    echo "No records found."; // Show if table is empty
}

?>

</body>
</html>

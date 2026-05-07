<?php
session_start();

$timeout = 30; // 1 minute

// check login
if (!isset($_SESSION['user'])) {
    header("Location: s_html.php");
    exit();
}

// check time
if (time() - $_SESSION['start_time'] > $timeout) {

    session_unset();
    session_destroy();

    header("Location: s_html.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Welcome, <?php echo $user; ?></h2>

<p>You will be logged out after 1 minute automatically.</p>

<a href="logout.php">Logout</a>

</body>
</html>
<?php

declare(strict_types=1);

$successMessage = '';
$errorMessage = '';
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $errorMessage = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $errorMessage = 'Password must be at least 6 characters.';
    } else {
        require_once 'database.php';

        $checkStatement = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');

        if (!$checkStatement) {
            $errorMessage = 'Database error: ' . $conn->error;
        } else {
            $checkStatement->bind_param('s', $email);
            $checkStatement->execute();
            $checkStatement->store_result();

            if ($checkStatement->num_rows > 0) {
                $errorMessage = 'This email is already registered.';
                $checkStatement->close();
            } else {
                $checkStatement->close();

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $insertStatement = $conn->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');

                if (!$insertStatement) {
                    $errorMessage = 'Database error: ' . $conn->error;
                } else {
                    $insertStatement->bind_param('sss', $name, $email, $passwordHash);

                    if ($insertStatement->execute()) {
                        $successMessage = 'Registration successful. You can now log in.';
                        $name = '';
                        $email = '';
                    } else {
                        $errorMessage = 'Registration failed: ' . $insertStatement->error;
                    }

                    $insertStatement->close();
                }
            }

            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
<h2>Register Page</h2>

<?php if ($errorMessage !== ''): ?>
    <p><?php echo htmlspecialchars($errorMessage); ?></p>
<?php endif; ?>

<?php if ($successMessage !== ''): ?>
    <p><?php echo htmlspecialchars($successMessage); ?></p>
<?php endif; ?>

<form method="post" action="">
    <input type="text" id="name" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

    <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

    <input type="password" id="password" name="password" placeholder="Password" required><br><br>

    <button type="submit">Register</button>
</form>

<p>
    Already have an account? <a href="s_html.php">Login here</a>
</p>
</body>
</html>

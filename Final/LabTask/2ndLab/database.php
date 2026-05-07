<?php

declare(strict_types=1);

$conn = new mysqli('localhost', 'root', '');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$createDbSql = "CREATE DATABASE IF NOT EXISTS webtech_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if (!$conn->query($createDbSql)) {
    die('Database creation failed: ' . $conn->error);
}

if (!$conn->select_db('webtech_auth')) {
    die('Database selection failed: ' . $conn->error);
}

$createUsersTableSql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
";

if (!$conn->query($createUsersTableSql)) {
    die('Users table creation failed: ' . $conn->error);
}

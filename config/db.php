<?php
/**
 * Database connection.
 * Fill in your Hostinger MySQL credentials below.
 * Find them in hPanel > Databases > Management.
 */
$DB_HOST = 'localhost';
$DB_NAME = 'u123456789_tca_db';      // replace with your actual DB name
$DB_USER = 'u123456789_tca_user';    // replace with your actual DB user
$DB_PASS = 'your_database_password'; // replace with your actual DB password

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed. Please check config/db.php credentials.');
}

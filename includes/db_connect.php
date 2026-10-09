<?php
define('DB_ENABLED', true);
define('DB_HOST', 'fdb1029.awardspace.net');
define('DB_USER', '4795301_netball');
define('DB_PASS', 'password@merit1.');
define('DB_NAME', '4795301_netball');

function get_db_connection() {
    if (!DB_ENABLED) {
        return null;
    }

    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        error_log('Dolphin Netball: Database connection failed: ' . $e->getMessage());
        return null;
    }
}

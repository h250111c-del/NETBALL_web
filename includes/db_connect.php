<?php
function get_db_connection(): ?PDO
{
    $host = getenv('DB_HOST');
    $name = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');

    if ($host === false || $host === '' || $name === false || $name === ''
        || $user === false || $user === '' || $pass === false || $pass === '') {
        error_log('Dolphin Netball: Database configuration is incomplete.');
        return null;
    }

    try {
        $port = getenv('DB_PORT');
        $dsn = 'mysql:host=' . $host . ';port=' . ($port === false || $port === '' ? '3306' : $port)
            . ';dbname=' . $name . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        error_log('Dolphin Netball: Database connection failed: ' . $e->getMessage());
        return null;
    }
}

<?php
function get_db_connection(): ?PDO
{
    $path = getenv('SQLITE_PATH');
    if ($path === false || $path === '') {
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'dolphin_netball.sqlite';
    }

    if ($path !== ':memory:') {
        $directory = dirname($path);
        if (!is_dir($directory) && !mkdir($directory, 0770, true) && !is_dir($directory)) {
            error_log('Dolphin Netball: Could not create the SQLite database directory.');
            return null;
        }
    }

    try {
        $db = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $db->exec('PRAGMA busy_timeout = 5000');
        $db->exec(
            "CREATE TABLE IF NOT EXISTS submissions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                full_name TEXT NOT NULL,
                email TEXT NOT NULL,
                phone TEXT NOT NULL DEFAULT '',
                reason TEXT NOT NULL CHECK (reason IN ('General Inquiry', 'Join', 'Sponsorship')),
                message TEXT,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            )"
        );
        $db->exec('CREATE INDEX IF NOT EXISTS idx_submissions_email ON submissions (email)');
        $db->exec('CREATE INDEX IF NOT EXISTS idx_submissions_created_at ON submissions (created_at)');

        return $db;
    } catch (PDOException $e) {
        error_log('Dolphin Netball: SQLite database connection failed: ' . $e->getMessage());
        return null;
    }
}

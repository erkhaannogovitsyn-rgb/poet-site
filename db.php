<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('PGHOST');
    $port = getenv('PGPORT') ?: '5432';
    $database = getenv('PGDATABASE');
    $user = getenv('PGUSER');
    $password = getenv('PGPASSWORD');

    if ($host && $database && $user !== false && $password !== false) {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;sslmode=prefer',
            $host,
            $port,
            $database
        );
        $pdo = new PDO($dsn, $user, $password);
    } else {
        $databaseUrl = getenv('DATABASE_URL');
        if (!$databaseUrl) {
            throw new RuntimeException('Database connection is not configured.');
        }

        $parts = parse_url($databaseUrl);
        if (!is_array($parts) || empty($parts['host']) || empty($parts['path'])) {
            throw new RuntimeException('Database connection string is invalid.');
        }
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;sslmode=prefer',
            $parts['host'],
            $parts['port'] ?? 5432,
            ltrim($parts['path'], '/')
        );
        $pdo = new PDO(
            $dsn,
            rawurldecode($parts['user'] ?? ''),
            rawurldecode($parts['pass'] ?? '')
        );
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $pdo;
}

function ensureAdminAccount(PDO $pdo): void
{
    $password = getenv('ADMIN_PASSWORD');
    if ($password === false || $password === '') {
        $existing = $pdo->prepare('SELECT 1 FROM users WHERE username = :username');
        $existing->execute(['username' => 'admin']);
        if ($existing->fetchColumn()) {
            return;
        }
        throw new RuntimeException('ADMIN_PASSWORD must be configured before creating the administrator account.');
    }

    $stmt = $pdo->prepare(
        "INSERT INTO users (username, password_hash, role)
         VALUES ('admin', :password_hash, 'super_admin')
         ON CONFLICT (username) DO NOTHING"
    );
    $stmt->execute(['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
}

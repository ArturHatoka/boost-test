<?php

$db = require __DIR__ . '/db.php';
$env = require __DIR__ . '/env.php';

$isAbsolutePath = static function (string $path): bool {
    return (bool) preg_match('/^([A-Za-z]:[\\\\\\/]|\/|\\\\\\\\)/', $path);
};

$resolvePath = static function (string $path) use ($isAbsolutePath): string {
    if ($path === ':memory:' || $isAbsolutePath($path)) {
        return $path;
    }

    return dirname(__DIR__) . DIRECTORY_SEPARATOR . ltrim($path, '\\/');
};

$driver = strtolower((string) $env('TEST_DB_DRIVER', $env('DB_DRIVER', 'sqlite')));

if (in_array($driver, ['pgsql', 'postgres', 'postgresql'], true)) {
    $db['dsn'] = sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        $env('TEST_DB_HOST', $env('DB_HOST', '127.0.0.1')),
        $env('TEST_DB_PORT', '5432'),
        $env('TEST_DB_DATABASE', 'yii2basic_test')
    );
    $db['username'] = $env('TEST_DB_USERNAME', $env('DB_USERNAME', 'postgres'));
    $db['password'] = $env('TEST_DB_PASSWORD', $env('DB_PASSWORD', ''));
    $db['charset'] = $env('TEST_DB_CHARSET', $env('DB_CHARSET', 'utf8'));

    return $db;
}

if ($driver === 'mysql') {
    $db['dsn'] = sprintf(
        'mysql:host=%s;port=%s;dbname=%s',
        $env('TEST_DB_HOST', $env('DB_HOST', '127.0.0.1')),
        $env('TEST_DB_PORT', '3306'),
        $env('TEST_DB_DATABASE', 'yii2basic_test')
    );
    $db['username'] = $env('TEST_DB_USERNAME', $env('DB_USERNAME', 'root'));
    $db['password'] = $env('TEST_DB_PASSWORD', $env('DB_PASSWORD', ''));
    $db['charset'] = $env('TEST_DB_CHARSET', $env('DB_CHARSET', 'utf8'));

    return $db;
}

$sqlitePath = $resolvePath((string) $env('TEST_DB_DATABASE', 'runtime/test.db'));
$db['dsn'] = 'sqlite:' . str_replace('\\', '/', $sqlitePath);
$db['username'] = '';
$db['password'] = '';
$db['charset'] = 'utf8';

return $db;

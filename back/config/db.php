<?php

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

$driver = strtolower((string) $env('DB_DRIVER', 'sqlite'));

$db = [
    'class' => 'yii\db\Connection',
];

if ($driver === 'mysql') {
    $db['dsn'] = sprintf(
        'mysql:host=%s;port=%s;dbname=%s',
        $env('DB_HOST', '127.0.0.1'),
        $env('DB_PORT', '3306'),
        $env('DB_DATABASE', 'yii2basic')
    );
    $db['username'] = $env('DB_USERNAME', 'root');
    $db['password'] = $env('DB_PASSWORD', '');
    $db['charset'] = $env('DB_CHARSET', 'utf8');

    return $db;
}

if (in_array($driver, ['pgsql', 'postgres', 'postgresql'], true)) {
    $db['dsn'] = sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        $env('DB_HOST', '127.0.0.1'),
        $env('DB_PORT', '5432'),
        $env('DB_DATABASE', 'yii2basic')
    );
    $db['username'] = $env('DB_USERNAME', 'postgres');
    $db['password'] = $env('DB_PASSWORD', '');
    $db['charset'] = $env('DB_CHARSET', 'utf8');

    return $db;
}

$sqlitePath = $resolvePath((string) $env('DB_DATABASE', 'runtime/app.db'));
$db['dsn'] = 'sqlite:' . str_replace('\\', '/', $sqlitePath);
$db['username'] = '';
$db['password'] = '';
$db['charset'] = 'utf8';

return $db;

<?php


use App\Core\Application;

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

$config = [
    'smtp' =>
        [
            'host' => $_ENV['SMTP_HOST'],
            'port' => $_ENV['SMTP_PORT'],
            'username' => $_ENV['SMTP_USERNAME'],
            'password' => $_ENV['SMTP_PASSWORD'],
            'from' => $_ENV['SMTP_FROM'],
            'name' => $_ENV['SMTP_FROM_NAME'],
        ],
    'userClass' => \App\Model\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],

    ]
];

$app = new Application(__DIR__, $config);

$app->db->applyMigrations();

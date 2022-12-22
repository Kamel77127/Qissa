<?php

use App\Controllers\AdminController;
use App\Core\Application;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ShopController;
use App\Controllers\SiteController;

require dirname(__DIR__) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) . '/');
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

$app = new Application(dirname(__DIR__), $config);

$app->router->get("/", [\App\Controllers\BlogController::class, 'index']);
$app->router->post("/", [\App\Controllers\BlogController::class, 'index']);

$app->router->get('/blog-page/{id:\d+}', [\App\Controllers\BlogController::class, 'articlePage']);
$app->router->get("/page/{page:\d+}", [\App\Controllers\BlogController::class, 'index']);


$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);


// REGISTER \\
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/email-validation/{id:\d+}/{token:\w+}', [AuthController::class, 'emailValidationPage']);


// LOGIN \\
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/login/{id:\d+}', [AuthController::class, 'login']);

$app->router->get('/logout', [AuthController::class, 'logout']);


// USER INTERFACE \\
$app->router->get('/profile', [AuthController::class, 'profile']);
$app->router->get('/profile/{id:\d+}/{username}', [AuthController::class, 'profile']);


// ADMIN \\
// articles \\
$app->router->get('/admin/voir-articles', [\App\Controllers\AdminController::class, 'showArticles']);
$app->router->get('/admin/voir-articles/page/{page:\d+}', [\App\Controllers\AdminController::class, 'showArticles']);

$app->router->get('/admin/create-blog-article', [\App\Controllers\AdminController::class, 'createBlogArticle']);
$app->router->post('/admin/create-blog-article', [\App\Controllers\AdminController::class, 'createBlogArticle']);
$app->router->get('/admin/modifier-article/{id:\d+}', [\App\Controllers\AdminController::class, 'updateArticle']);
$app->router->post('/admin/modifier-article/{id:\d+}', [\App\Controllers\AdminController::class, 'updateArticle']);
$app->router->get('/admin/delete-article/{id:\d+}', [\App\Controllers\AdminController::class, 'deleteArticle']);
$app->router->get('/admin/archive-article', [\App\Controllers\AdminController::class, 'archivePage']);
$app->router->get('/admin/restore-article/{id:\d+}', [\App\Controllers\AdminController::class, 'restoreArticle']);
$app->router->get('/admin/voir-commandes', [AdminController::class, 'showOrders']);


//Products\\

$app->router->get('/admin/create-product', [\App\Controllers\AdminController::class, 'createProduct']);
$app->router->post('/admin/create-product', [\App\Controllers\AdminController::class, 'createProduct']);
$app->router->get('/admin/voir-produits', [\App\Controllers\AdminController::class, 'showProducts']);
$app->router->get('/admin/voir-produits/page/{page:\d+}', [\App\Controllers\AdminController::class, 'showProducts']);
$app->router->get('/admin/archive-produits', [\App\Controllers\AdminController::class, 'archiveProducts']);
$app->router->get('/admin/modifier-produit/{id:\d+}', [\App\Controllers\AdminController::class, 'updateProduct']);
$app->router->post('/admin/modifier-produit/{id:\d+}', [\App\Controllers\AdminController::class, 'updateProduct']);
$app->router->get('/admin/supprimer-produit/{id:\d+}', [\App\Controllers\AdminController::class, 'deleteProduct']);
$app->router->get('/admin/restore-product/{id:\d+}', [\App\Controllers\AdminController::class, 'restoreProduct']);

/// USER LIST \\
$app->router->get('/admin/voir-utilisateurs', [\App\Controllers\AdminController::class, 'showMembers']);
$app->router->get('/admin/archive-utilisateurs', [\App\Controllers\AdminController::class, 'showMembers']);
$app->router->get('/admin/modifier-utilisateur/{id:\d+}', [\App\Controllers\AdminController::class, 'updateMembers']);
$app->router->post('/admin/modifier-utilisateur/{id:\d+}', [\App\Controllers\AdminController::class, 'updateMembers']);



// BOUTIQUE \\
$app->router->get('/boutique', [\App\Controllers\ShopController::class, 'shop']);
$app->router->post('/boutique', [\App\Controllers\ShopController::class, 'shop']);
$app->router->get("/boutique/page/{page:\d+}", [\App\Controllers\ShopController::class, 'shop']);
$app->router->post('/boutique/page/{page:\d+}', [\App\Controllers\ShopController::class, 'shop']);


$app->router->get('/boutique/page-produit/{id:\d+}', [\App\Controllers\ShopController::class, 'pageProduct']);
$app->router->post('/boutique/page-produit/{id:\d+}', [\App\Controllers\ShopController::class, 'pageProduct']);

$app->router->get('/panier', [\App\Controllers\ShopController::class, 'cart']);
$app->router->get('/panier/{idProd:\w+}', [\App\Controllers\ShopController::class, 'cart']);
$app->router->post('/panier', [\App\Controllers\ShopController::class, 'cart']);
$app->router->get('/panier/paypal-checkout' , [ShopController::class , 'paypalCheckout']);
$app->router->post('/panier/paypal-checkout' , [ShopController::class , 'paypalCheckout']);




$app->run();

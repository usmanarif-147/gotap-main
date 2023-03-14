<?php
$allowedRoutes = ['/login'];

use app\Controllers\Dashboard\AuthController;
use app\Controllers\Dashboard\PlatformController;
use app\Controllers\Dashboard\UserController;
use app\Controllers\Dashboard\CategoryController;
use app\Controllers\Dashboard\CardController;

//Auth
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);

$router->get('/', [AuthController::class, 'dashboard']);

//users
$router->get('/users', [UserController::class, 'index']);
$router->get('/users/edit/{num}', [UserController::class, 'edit']);
$router->post('/users/edit/{num}', [UserController::class, 'update']);
$router->get('/users/delete/{num}', [UserController::class, 'destroy']);
$router->get('/users/suspendNotification/{num}', [UserController::class, 'suspendNotification']);
$router->get('/users/changeSuspendStatus/{num}', [UserController::class, 'changeSuspendStatus']);
$router->get('/users/changeUserStatus/{num}', [UserController::class, 'changeUserStatus']);

//PLATFROMS
$router->get('/platforms', [PlatformController::class, 'index']);
$router->get('/platforms/add', [PlatformController::class, 'showCreateForm']);
$router->post('/platforms/add', [PlatformController::class, 'store']);
$router->get('/platforms/edit/{num}', [PlatformController::class, 'edit']);
$router->post('/platforms/edit/{num}', [PlatformController::class, 'update']);
$router->get('/platforms/delete/{num}', [PlatformController::class, 'destroy']);


//Categories
$router->get('/categories', [CategoryController::class, 'index']);
$router->get('/categories/add', [CategoryController::class, 'showCreateForm']);
$router->post('/categories/add', [CategoryController::class, 'store']);
$router->get('/categories/edit/{num}', [CategoryController::class, 'edit']);
$router->post('/categories/edit/{num}', [CategoryController::class, 'update']);

//Cards
$router->get('/cards', [CardController::class, 'index']);
$router->get('/cards/change/{num}', [CardController::class, 'changeStatus']);
$router->get('/cards/add', [CardController::class, 'showCreateForm']);
$router->post('/cards/add', [CardController::class, 'store']);
$router->get('/cards/edit/{num}', [CardController::class, 'edit']);
$router->post('/cards/edit/{num}', [CardController::class, 'update']);
$router->get('/cards/export', [CardController::class, 'downloadCsv']);

if (defined('MATCHED_ROUTE'))
    $ROUTE = MATCHED_ROUTE;
if (!in_array($ROUTE, $allowedRoutes)) {
    if (!isset($_SESSION[PRE_FIX . 'admin_id'])) {
        redirect('/dashboard/login');
    }
}

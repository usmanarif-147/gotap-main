<?php
$allowedRoutes=['/login','/register','/forgetPassword','/resetPassword','/otpVerification', '/recoverAccount'];

use app\Helpers\JWT;

use app\Controllers\Api\AuthController;
use app\Controllers\Api\UserController;
use app\Controllers\Api\PlatformController;
use app\Controllers\Api\GroupController;
use app\Controllers\Api\CardController;

//AUTH 
$router->post('/login', [AuthController::class, 'login']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/forgetPassword', [AuthController::class, 'forgetPassword']);
$router->post('/resetPassword', [AuthController::class, 'resetPassword']);
$router->post('/otpVerification', [AuthController::class, 'otpVerify']);

//USER
$router->get('/profile', [UserController::class, 'profile']);
$router->get('/userDirect', [UserController::class, 'userDirect']);
$router->post('/updateProfile', [UserController::class, 'update']);
$router->get('/connect', [UserController::class, 'connect']);
$router->get('/search', [UserController::class, 'search']);
$router->post('/privateProfile', [UserController::class, 'privateProfile']);

$router->post('/recoverAccount', [AuthController::class, 'recoverAccount']);
$router->get('/deactivateAccount', [UserController::class, 'deactivateAccount']);

//Platform
$router->post('/addPlatform', [PlatformController::class, 'add']);
$router->get('/removePlatform', [PlatformController::class, 'destroy']);
$router->post('/swapOrder', [PlatformController::class, 'swap']);
$router->post('/addPhoneContact', [PlatformController::class, 'add_phone_contact']);
$router->post('/updatePhoneContact/{num}', [PlatformController::class, 'update_phone_contact']);
$router->get('/phoneContacts', [PlatformController::class, 'phone_contacts']);
$router->get('/phoneContact/{num}', [PlatformController::class, 'phone_contact']);
$router->post('/removeContact', [PlatformController::class, 'destroy_contact']);

//Group
$router->get('/groups', [GroupController::class, 'index']);
$router->get('/groupDetail/{num}', [GroupController::class, 'groupDetail']);
$router->post('/addGroup', [GroupController::class, 'add']);
$router->post('/updateGroup/{num}', [GroupController::class, 'update']);
$router->post('/removeGroup', [GroupController::class, 'destroy']);
$router->post('/addUserIntoGroup', [GroupController::class, 'addUser']);
$router->post('/addContactIntoGroup', [GroupController::class, 'addContact']);
$router->post('/removeUserFromGroup', [GroupController::class, 'removeUser']);
$router->post('/removeContactFromGroup', [GroupController::class, 'removeContact']);

//Cards
$router->get('/cards', [CardController::class, 'index']);
$router->post('/cardProfileDetail', [CardController::class, 'cardProfileDetail']);
$router->post('/activateCard', [CardController::class, 'activateCard']);
$router->post('/changeCardStatus', [CardController::class, 'changeCardStatus']);


JWT::validateRequest($ROUTE,$allowedRoutes);
?>
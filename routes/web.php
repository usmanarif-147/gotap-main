<?php
$allowedRoutes=['/','/login','/register','/{str}','/contact/create/{any}','/addToContact/{num}'];

use app\Controllers\IndexController;
use app\Controllers\VcardController;


$router->get('/{str}', [IndexController::class, 'profile']);

//VCARD
$router->get('/contact/create/{any}', [VcardController::class, 'create']);
$router->get('/addToContact/{num}', [VcardController::class, 'addToContact']);



if(defined('MATCHED_ROUTE'))
    $ROUTE=MATCHED_ROUTE;
if(!in_array($ROUTE,$allowedRoutes)){
    if(!isset($_SESSION[PRE_FIX.'user_id'])){
        redirect('login');
    }
}

?>
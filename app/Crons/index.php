<?php namespace app\Crons;

require_once __DIR__."/../../config/config.php";
require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../../config/builtin_functions.php";

use app\Controllers\DeleteUsers;

$controller= new DeleteUsers();
$controller->deleteAccount();

echo "cron is working";
<?php

require_once __DIR__."/../config/config.php";
require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../config/builtin_functions.php";

use app\Helpers\Router;
use app\Helpers\Response;


$ROUTE=$_SERVER['REQUEST_URI'] ?? '/';
if(strpos($ROUTE,'?')!==false)
	$ROUTE=substr($ROUTE, 0 , strpos($ROUTE,'?') );

$ROUTE=str_replace("/".strtolower(APP_TITLE)."/public","",$ROUTE); 
define('PRE_ROUTE_API','api');
define('PRE_ROUTE_ADMIN','dashboard');

$router = new Router();
	
if( $ROUTE==="/".PRE_ROUTE_ADMIN  ||  strpos($ROUTE,'/'.PRE_ROUTE_ADMIN .'/') !== false )
{
	$ROUTE=str_replace(PRE_ROUTE_ADMIN,"",$ROUTE);
	$ROUTE=str_replace("//","/",$ROUTE);
	define('ROUTE',$ROUTE);
	require_once "../routes/dashboard.php";
}
else if($ROUTE==="/".PRE_ROUTE_API || strpos($ROUTE,'/'.PRE_ROUTE_API.'/') !== false)
{
	$ROUTE=str_replace(PRE_ROUTE_API,"",$ROUTE);
	$ROUTE=str_replace("//","/",$ROUTE);
	define('ROUTE',$ROUTE);
	require_once "../routes/api.php";
}
else
{
	define('ROUTE',$ROUTE);
	require_once '../routes/web.php';
}

$router->resolve();

		
?>
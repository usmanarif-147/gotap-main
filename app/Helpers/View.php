<?php

namespace app\Helpers;

/**
 * Class Router
 *
 * @author  Talha tahir <talhabhatti0257@gmail.com>
 * @package app
 */
class View
{
    public function __construct()
    {
      
    }
	
	 public static function render($view, $params = [],$layout="web"){	
        $errors=$_SESSION['ot_errors'] ?? [];
        $error=$_SESSION['ot_error'] ?? null;
        if(isset($_SESSION['ot_errors']))
           unset($_SESSION['ot_errors']);
        if(isset($_SESSION['ot_error']))
           unset($_SESSION['ot_error']);
            
        foreach ($params as $key => $value) {
            $$key = $value;	
        }
		
        ob_start();
        include __DIR__."/../../Views/$view.php";
        $content = ob_get_clean();
		
        include __DIR__."/../../Views/layouts/$layout.php";
        die;
    }

    public function load($view, $params = []){
        $errors=$_SESSION['ot_errors'] ?? [];
        $error=$_SESSION['ot_error'] ?? null;
        if(isset($_SESSION['ot_errors']))
           unset($_SESSION['ot_errors']);
        if(isset($_SESSION['ot_error']))
           unset($_SESSION['ot_error']);
        
           
       foreach ($params as $key => $value) {
           $$key = $value;	
       }
        include __DIR__."/../../Views/$view.php";
    }

}
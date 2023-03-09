<?php namespace app\Helpers;

use app\Helpers\Request;

/**
 * Class Router
 *
 * @author  Talha tahir <talhabhatti0257@gmail.com>
 * @package app
 */
class Router
{
    public  $getRoutes = [];
    public  $postRoutes = [];
	public  $putRoutes = [];
	public  $deleteRoutes = [];
	public  $matchedRoute = null;
	public $matchedMethod=null;
	public $request;
    public function __construct(){
		$this->request=new Request();
    }
	
	public function getID(){
		$indexes=explode('/', ROUTE);
		if(sizeof($indexes)<2)
			return null;
		return $indexes[sizeof($indexes)-1];
	}
	
	public function variableRoute($url){
		$var=null;
		if(strpos($url,'/{')!==false) {
			if(!$value=$this->getID())
					return $url;
					
			if(strpos($url,'{num}')!==false && is_numeric($value) )
				$var='num';
				
			elseif(strpos($url,'{str}')!==false && is_string($value) )
				$var='str';
				
			elseif(strpos($url,'{any}')!==false )
				$var='any';

			if($var)
				$url=str_replace('/{'.$var.'}',"",$url)."/$value";
		}
		return $url;
	}

    public function get($url, $fn)
    { 
		if($this->matchedRoute && $this->matchedMethod=="get")
			return;	
			
		$definedRoute=$url;
		$url=$this->variableRoute($url);
		
		if($url==ROUTE){
			$this->matchedRoute=$url;
			$this->matchedMethod="get";
			if(!defined('MATCHED_ROUTE'))
				define('MATCHED_ROUTE',$definedRoute);
		}
			
        $this->getRoutes[$url] = $fn;
		if($definedRoute!=$url)
			$this->getRoutes[$url]['id'] = $this->getID();
		
    }

    public function post($url, $fn){ 
		if($this->matchedRoute && $this->matchedMethod=="post")
			return;	
			
		$definedRoute=$url;
		$url=$this->variableRoute($url);
		
		if($url==ROUTE){
			$this->matchedRoute=$url;
			$this->matchedMethod="post";
			if(!defined('MATCHED_ROUTE'))
				define('MATCHED_ROUTE',$definedRoute);
		}
			
        $this->postRoutes[$url] = $fn;
		
		if($definedRoute!=$url)
			$this->postRoutes[$url]['id'] = $this->getID();
	}
	
	public function put($url, $fn)
    { 
		if($this->matchedRoute && $this->matchedMethod=="put")
			return;	
			
		$definedRoute=$url;
		$url=$this->variableRoute($url);
		
		if($url==ROUTE){
			$this->matchedRoute=$url;
			$this->matchedMethod='put';
			if(!defined('MATCHED_ROUTE'))
				define('MATCHED_ROUTE',$definedRoute);
		}
			
        $this->putRoutes[$url] = $fn;
		
		if($definedRoute!=$url)
			$this->putRoutes[$url]['id'] = $this->getID();
	}
	

	public function delete($url, $fn)
    { 
		if($this->matchedRoute)
			return;	
			
		$definedRoute=$url;
		$url=$this->variableRoute($url);
		
		if($url==ROUTE){
			$this->matchedRoute=$url;
			$this->matchedMethod="delete";
			if(!defined('MATCHED_ROUTE'))
				define('MATCHED_ROUTE',$definedRoute);
		}
			
        $this->deleteRoutes[$url] = $fn;
		
		if($definedRoute!=$url)
			$this->deleteRoutes[$url]['id'] = $this->getID();
    }

    public function resolve($ROUTE=ROUTE)
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $url = $ROUTE ?? '/';
		
        if ($method === 'get') {
            $fn = $this->getRoutes[$url] ?? null;
        } else if( strtolower($method)=== 'post' ) {
            $fn = $this->postRoutes[$url] ?? null;
		}
		else if( strtolower($method)=== 'put' ) {
            $fn = $this->putRoutes[$url] ?? null;
		}
		else if( strtolower($method)=== 'delete' ) {
            $fn = $this->deleteRoutes[$url] ?? null;
		}
		
        if (!$fn) {
            echo 'Page not found';
            exit;
		}
		
		if(isset($fn['id']))
		{
			$id=$fn['id'];
			unset($fn['id']);
			if($method === 'get'){
				$obj=new $fn[0];
				$obj->{$fn[1]}($id);
				//print_r(call_user_func($fn, $id));
			}
			else {
				$obj=new $fn[0];	
				$obj->{$fn[1]}($id,$this->request); die;
				//print_r(call_user_func($fn,$this,$id));
			}
		}
		else{
			$obj=new $fn[0];	
			$obj->{$fn[1]}($this->request); die;
			//print_r(call_user_func($fn, $this));
		}
		
		
    }
}
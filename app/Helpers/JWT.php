<?php namespace app\Helpers;

/**
 * Class JWT
 *
 * @author  Talha tahir <talhabhatti0257@gmail.com>
 * @package app
 */ 
 
 use app\Helpers\Response;
 use ReallySimpleJWT\Token;
 class JWT
{
	function __construct()
	{ 
        
	} 
	
	 public static function generateToken($data)
	 {
	     if(empty($data))
	        die(" Input Data Cannot be empty in JWT::generateToken() ");
		$expiration = time() + 9999999;
		$issuer = 'localhost';
		if(is_array($data))
		{
    		$payload = [
                'iat' => time(),
                'exp' => $expiration,
                'iss' => $issuer
            ];
            $payload=array_merge($data,$payload);
            return Token::customPayload($payload,JWT_SECRET);
		}
		  return Token::create($data,JWT_SECRET,$expiration,$issuer);
	 }
	  public static function validateToken($token)
	 {
		return Token::validate($token,JWT_SECRET);
	 }
	 
	  public static function getPayload()
	  {
		  return Token::getPayload(JWT_TOKEN,JWT_SECRET);
		 
	  }
	   public function getHeader()
	  {
		 return Token::getHeader(JWT_TOKEN,JWT_SECRET);
	  }
	  
	 public function refreshToken()
	 {
	     $payload=$this->getPayload();
	     if(isset($payload['iat']))
	         unset($payload['iat']);
	     if(isset($payload['exp']))
	           unset($payload['exp']);
	     if(isset($payload['iss']))
	           unset($payload['iss']);
		 return  $this->generateToken($payload);
	 }
	 
	 public function manualTokenRefresh(){
	     $payload=$this->getPayload();
	     if($payload && $payload['exp'] && ($payload['exp']-time())<3409600)
	        return $this->refreshToken();
	   else return JWT_TOKEN;
	 }
	 
	 public static function user()
	 {
	     $payload=JWT::getPayload();
		 return $payload['id'] ?? $payload['user_id'];
	 }
	 
	 public static function validateRequest($ROUTE,$allowedRoutes){
		 
		if(defined('MATCHED_ROUTE'))
			$ROUTE=MATCHED_ROUTE;
			
		 for($i=0; $i<sizeof($allowedRoutes); $i++)
			 $allowedRouteswithSlash[$i]='/'.$allowedRoutes[$i];
		 // To VALIDATE JSON WEB TOKEN
        if(!in_array($ROUTE, $allowedRoutes) && !in_array($ROUTE, $allowedRouteswithSlash))
        {
            $headers = apache_request_headers();
            if(isset($headers['Authorization']) && strpos($headers['Authorization'], 'Bearer ')  !== false ) { 
				$headers['Authorization']=substr($headers['Authorization'],7);
				define('JWT_TOKEN',$headers['Authorization']);
				unset($headers['Authorization']);
			}
            elseif(isset($_GET['token']) )
            { 
				 define('JWT_TOKEN',$_GET['token']); 
				 unset($_GET['token']);
			}
            elseif(isset($_POST['token']))
			{ 
				define('JWT_TOKEN',$_POST['token']); 
				unset($_POST['token']);
			}
			else
				die(Response::json(['status'=>404,'message'=>'Authorization Token Missing']));
       
    	    if(empty(JWT_TOKEN))
				die(Response::json(['status'=>404,'message'=>'Authorization Token Missing']));
				
			else if(strlen(JWT_TOKEN)<64)
				die(Response::json(['status'=>404,'message'=>'Authorization Token is Invalid']));
				
        	if(!JWT::validateToken(JWT_TOKEN))
				die(Response::json(['status'=>403,'message'=>'Authorization Token is Invalid or Expired,Permission Denied']));
        	
			define('LOGGED_USER',JWT::user());
        }
		
	 }
	 
}

// Read About this library from https://github.com/RobDWaller/ReallySimpleJWT

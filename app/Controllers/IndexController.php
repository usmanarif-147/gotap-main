<?php namespace app\Controllers;
 
use app\Controllers\Controller;
class IndexController extends Controller
{

	public function index(){
		$data["title"]="I am Title";
		$data["description"]="I am Description";
		
		$this->view->render('index', [
            'data' => $data
        ]);
	}
	public function profile($username){
	   
		if(strlen($username)>15 || strpos($username,'/')!==false){
			http_response_code(404);
		}
		$attr='username';
		if(is_numeric($username)){
		    $attr='id';
		}
		$user=$this->db->where($attr,$username)->first('users'); 
		if(!$user){
			http_response_code(404);
			$this->view->render('404',[],'404');
		}
		define('LOGGED_USER',$user->id);
		if($user->private==0)
			$categories=$this->custom->returnPlatforms()['categories'] ?? [];
		else 
			$categories=[];
		
		$this->view->render('profile', [
			'user' => $user,
            'categories' => $categories
        ]);
	}
}

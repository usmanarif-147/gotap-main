<?php namespace app\Models;
use app\Models\DB;
class Custom
{
    protected $db;
	function __construct()
	{
	    $this->db=new DB();
	    $this->conn=$this->db->getConnection();
	}
	
	public function returnPlatforms($id=null,$type="platform"){
		
		$AND='';
		$WHERE='WHERE platforms.status=1';
		
		if (!defined('LOGGED_USER'))
			$WHERE='WHERE platforms.status<2';
		if($id && is_numeric($id))
		{
			if($type=="platform")
				$AND=" AND platforms.id=$id ";
		}
		
		$query="SELECT platforms.*,categories.name as category,categories.name_sv as category_sv FROM platforms 
		INNER JOIN categories ON  categories.id=platforms.category_id
		$WHERE  $AND
		";
		
		$platforms= $this->db->getDataWithQuery($query);
		$direct=0;
		
		
		if($id==="default")
			return $platforms;
			
		// GET SELECTED PLATFORMS OF USER AND  PUSH IN PLATFORMS
		if (defined('LOGGED_USER'))
		{
		    $userID=LOGGED_USER;
		    if($id && $type=="user")
		        $userID=$id;
		       
			$userPlatfroms=$this->db->where('user_id',$userID)->get('user_platforms');
			for($i=0;$i<sizeof($platforms);$i++){
				$platforms[$i]->path=null;
				$platforms[$i]->saved=0;	
				foreach($userPlatfroms as $plat){
					if($plat->platform_id==$platforms[$i]->id){
						$platforms[$i]->path=$plat->path;
						$platforms[$i]->label=$plat->label;
						$platforms[$i]->direct=$plat->direct;
						$platforms[$i]->platform_order = $plat->platform_order;
						if($platforms[$i]->direct==1){
						    $direct=1;
						}
						$platforms[$i]->saved=1;						
					}	
				}
			}
			
		}else{
			for($i=0;$i<sizeof($platforms);$i++){
				$platforms[$i]->path=null;
				$platforms[$i]->saved=0;	
			}
		}
		
			
		// GET CATEGORIES AND  PUSH PLATFORMS IN CATEGORIES SO THEY ARE ARRANGED IN  CATEGORIES
		$categories=[];
		if(isset($platforms[0]))
		{
			$categoryIDs=[]; $categoryNames=[];
			foreach($platforms as $platform)
			{
				if(!in_array($platform->category_id,$categoryIDs)){
				    $categoryIDs[]=$platform->category_id;
				    $categoryNames[]=$platform->category;
				    $categoryNamesSV[]=$platform->category_sv;
				}
			
			}
			
			for($i=0;$i<sizeof($categoryIDs);$i++)
			{
				$categories[$i]['id']=$categoryIDs[$i];
				$categories[$i]['name']=$categoryNames[$i];
				$categories[$i]['title_en']=$categoryNames[$i];
				$categories[$i]['title_sv']=$categoryNamesSV[$i];
				$categories[$i]['platforms']=[];
				foreach($platforms as $platform)
				{
					if($platform->category_id==$categories[$i]['id']){
					    $platform->id=(int) $platform->id;
						$categories[$i]['platforms'][]=$platform;
					}
					//type casting
					$categories[$i]['id']=(int)$categories[$i]['id'];
				}
			
			}
		}
		
		$res['categories']=	$categories;
		$res['direct']=$direct;
		return $res;
	}
	
    public function searchUsers($key, $id = LOGGED_USER)
	{
		$AND = '';
		if (!empty($key)) {
			$AND = " AND ( name LIKE ? OR username LIKE ? ) ";
			// LEFT JOIN connects on connects.connected_id=users.id
		}
		$query = "SELECT users.*,connected_id,connecting_id
			FROM connects 
			INNER JOIN users on users.id=connected_id
			WHERE connecting_id=$id AND users.status=1 AND featured = 0
			$AND
			GROUP BY users.id
		";
	
		if (!empty($key)) {
			$key = addslashes($key);
			$vals[0] = "%$key%";
			$vals[1] = $vals[0];
			$users = $this->db->getDataWithQuery($query, $vals, 'ss');
		} else {
			$users = $this->db->getDataWithQuery($query);
		}
		foreach ($users as $user) {
			$user->id = (int)$user->id;
			$user->connecting_id = (int)$user->connecting_id;
			$user->connected_id = (int)$user->connected_id;
			$user->tiks = (int)$user->tiks;
			$user->gender = (int)$user->gender;
			$user->private = (int)$user->private;
			$user->status = (int)$user->status;
			$user->featured = (int)$user->featured;
			$user->verified = (int)$user->verified;
			$user->connected = "1";
		}
		return $users;
	}
}

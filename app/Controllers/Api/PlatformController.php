<?php namespace app\Controllers\Api;

use app\Controllers\Api\Controller;
use app\Helpers\Request;

class PlatformController extends Controller
{	
	public function add(Request $request){
		$request->validate([
			'path' => ['str','min:2'],
			'label' => ['max:25'],  
			'platform_id' => ['req','num','min:1'],
			'direct' => ['in:0,1']
		]);
		$request->user_id=LOGGED_USER;

		$platform=$this->db->where('platform_id',$request->platform_id)
			->where('user_id',LOGGED_USER)->first('user_platforms');
		if(isset($request->direct) && $request->direct==1){
			$this->db->table('user_platforms')->where('user_id',LOGGED_USER)->update(['direct'=>0]);
		}
		if($platform){
			$user_platform=$this->db->table('user_platforms')->where('id',$platform->id)->update($request->all());
			$user_platform->platform_id=strval($user_platform->platform_id);
			if($user_platform)
				$this->response->json(["status"=>200,"message"=>"Platform updated successfully",'data'=>$user_platform]);	
		}else{
			$user_platform=$this->db->table('user_platforms')->insert($request->all() + ['platform_order' => 1]);
			if(isset($user_platform->direct))
			    $user_platform->direct=(int) $user_platform->direct;
			if($user_platform)
				$this->response->json(["status"=>200,"message"=>"Platform added successfully",'data'=>$user_platform]);
		}
		$this->response->json(["status"=>400,"message"=>"Ooops platform could not be added"]);
	}

	public function destroy(Request $request){
		$request->validate([ 
			'platform_id' => ['req','num','min:1'],
		]);
		$deleted=$this->db->table('user_platforms')->where('user_id',LOGGED_USER)
			->where('platform_id',$request->platform_id)->delete();
		if($deleted)
			$this->response->json(["status"=>200,"message"=>"Platform removed successfully"]);
		$this->response->json(["status"=>400,"message"=>"Ooops platform could not be removed"]);
	}


	public function swap(Request $request)
	{
		$request->validate([ 
			'orderList' => ['req'],
		]);
        if(!is_array($request->orderList)){
            return $this->response->json(["status"=>400,"message"=>"order list must be an array"]);
        }
		$userId=LOGGED_USER;
        $orderList=json_decode(json_encode($request->orderList));
        
        foreach($orderList as $platform)
        {
            $this->db->table('user_platforms')->where('user_id',$userId)->where('platform_id',(int)$platform->id)->update(['platform_order'=>(int)$platform->order]);
        }
       return $this->response->json(["status"=>200,"message"=>"Order swapped successfully"]);
    }

	public function phone_contacts()
	{
		$contacts=$this->db->where('user_id',LOGGED_USER)->get('phone_contacts');
		$this->response->json(["status"=>200,"message"=>"Phone Contacts",'data'=>$contacts]);
	}

	public function phone_contact($id)
	{
		$contact=$this->db->where('user_id',LOGGED_USER)->where('id',$id)->get('phone_contacts');
		$this->response->json(["status"=>200,"message"=>"Phone Contacts",'data'=>$contact]);
	}

	public function add_phone_contact(Request $request)
	{
		$request->validate([ 
            'first_name' => ['req', 'min:2', 'max:30'],
            'last_name'  => ['min:2', 'max:30'],
            'email' 	 => ['email', 'max:50'],
            'work_email' => ['email', 'max:50'],
            'company_name' => ['min:3', 'max:20'],
            'job_title'  => ['max:500'],
            'address'  	 => ['min:3', 'max:110'],
            'phone' 	 => ['req','min:9','max:20'],
            'work_phone' => ['min:11', 'max:15'],
            'website' 	 => [],
		]);
        $request->user_id = LOGGED_USER;
		if($request->hasFile('photo')){
			$request->photo=$this->helper->uploadFile($request->photo,'uploads/users','image');
		}
		$data=$this->db->table('phone_contacts')->insert($request->all());
		$this->response->json(["status"=>200,"message"=>"Contact added successfully",'data'=>$data]);
	}

	public function update_phone_contact($id,Request $request)
	{
		$request->validate([ 
            'first_name' => ['min:2', 'max:30'],
            'last_name'  => ['min:2', 'max:30'],
            'email' 	 => ['email', 'max:50'],
            'work_email' => ['email', 'max:50'],
            'company_name' => ['min:3', 'max:20'],
            'job_title'  => ['max:500'],
            'address'  	 => ['min:3', 'max:110'],
            'phone' 	 => ['min:9','max:15'],
            'work_phone' => ['min:11', 'max:15'],
            'website' 	 => [],
		]);
		$contact=$this->db->where('user_id',LOGGED_USER)->where('id',$id)->first('phone_contacts');
		$oldPhoto=$contact->photo??'';
		if($request->hasFile('photo'))
			$request->photo=$this->helper->uploadFile($request->photo,'uploads/users','image');

		$contact=$this->db->table('phone_contacts')->where('user_id',LOGGED_USER)->where('id',$id)->update($request->all());
		if($contact){
			if($oldPhoto && file_exists($oldPhoto) && $request->hasFile('photo') )
			{
				unlink($oldPhoto);
			}
			$this->response->json(["status"=>200,"message"=>"Contact Updated Successfully",'data'=>$contact]);
		}
		$this->response->json(["status"=>400,"message"=>"Ooops error while updating",'data'=>(object)[]]);

	}

	public function destroy_contact(Request $request){
		$request->validate([ 
			'id' => ['req'],
		]);
		$deleted=$this->db->table('phone_contacts')->where('user_id',LOGGED_USER)
		->where('id',$request->id)->delete();
		if($deleted){
			$this->response->json(["status"=>200,"message"=>"Phone Contact removed successfully"]);
		}
		$this->response->json(["status"=>400,"message"=>"Ooops Phone Contact could not be removed"]);
	}
}
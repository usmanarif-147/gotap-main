<?php namespace app\Controllers\Api;

use app\Controllers\Api\Controller;
use app\Helpers\Request;

class GroupController extends Controller
{	
    public function index()
    {
         $user = $this->db->table('users')->where('id', LOGGED_USER)
            ->first();
        if (!$user->status) {
            return $this->response->json(["status" => 200, "message" => "Your account is not activated"]);
        }
        
        $groups=$this->db->table('groups')
        ->where('user_id', LOGGED_USER)
        ->get();

        $groupIds=[];

        foreach($groups as $group){
            $groupIds[]=$group->id;
            $group->members= [];
        }
            $members= $this->db->table('users')
            ->select('users.id,photo,name,group_id')
            ->join('user_group','user_id','users.id')
            ->where('group_id','IN',$groupIds)
            // ->where('status', 1)
            ->get();

            // $contacts= $this->db->table('phone_contacts')
            // ->select('phone_contacts.id,photo,first_name,group_id')
            // ->join('group_contacts','contact_id','phone_contacts.id')
            // ->where('group_id','IN',$groupIds)->get();

        foreach($groups as $group){
            $group->members= $this->groupMembers($members,$group);
        }

        $this->response->json(["status"=>200,"message"=>"All Groups",'data'=>$groups]);
    }

    private function groupMembers(array $members,$group){
        $groupMembers=[];
        foreach($members as $member){
            if($member->group_id == $group->id){
                $groupMembers[]=$member;
            }
        }

        return $groupMembers;
    }

    public function groupDetail($id)
    {
        // die("working");
        $groupExist=$this->db->table('groups')->where('id',$id)->get();
        if($groupExist)
    {  
        $users=$this->db->table('user_group')
        ->select('users.*')
        ->join('users','users.id','user_id')
        ->where('group_id',$id)
        ->where('status', 1)
        ->get();

        $contacts=$this->db->table('group_contacts')
        ->select('phone_contacts.*')
        ->join('phone_contacts','phone_contacts.id','contact_id')
        ->where('group_id',$id)->get();
        
		$this->response->json(["status"=>200,"message"=>"Group Detail with user Details",'users'=>$users,'Contacts'=>$contacts]);
    }
        $this->response->json(["status"=>200,"message"=>"Requested Group is not exist"]);
    }

	public function add(Request $request){
		$request->validate([
			'title' => ['req','str','min:2'],
			// 'icon' => ['image'],
			'active' => ['in:0,1']
		]);

		$request->user_id=LOGGED_USER;

        $isExist=$this->db->where('user_id',LOGGED_USER)
        ->where('title',$request->title)->get('groups');

        if(!$isExist){
            if($request->hasFile('icon')){
                $path=$this->helper->uploadFile($request->icon,'uploads/groups','image');
                $request->icon=$path;
                }
            $created=$this->db->table('groups')->insert([
                'title'=> $request->title,
                'icon'=> $request->icon ?? '',
                'active'=> $request->active?? 0,
                'user_id'=> $request->user_id,
                ]);
            $this->response->json(["status"=>200,"message"=>"Group created successfully"]);
        }else{
                $this->response->json(["status"=>400,"message"=>"Group with same title is already exist"]);
            }
	}

    public function update($id,Request $request)
    {
        $request->validate([
			'title' => ['str','min:2'],
            // 'icon' => ['image'],
			'active' => ['in:0,1']
		    ]);
		$group=$this->db->where('id',$id)->first('groups');

        if($group->user_id!=LOGGED_USER){
            $this->response->json(["status"=>400,"message"=>"You are not Authorise for this action"]);
            }
		$oldIcon=$group->icon;

		if($request->hasFile('icon')){
			$icon=$this->helper->uploadFile($request->icon,'uploads/groups','image');
            }
        $group=$this->db->table('groups')->where('id',$id)->update($request->validated()+[
            'icon'=>$request->hasFile('icon') ? $icon : $oldIcon
            ]);

        if($group){
            if($oldIcon && file_exists($oldIcon) && $request->hasFile('icon') ){
                unlink($oldIcon);
            }
        $this->response->json(["status"=>200,"message"=>"Group Updated Successfully",'data'=>$group]);
            }
        $this->response->json(["status"=>400,"message"=>"Ooops error while updating",'data'=>($group)]);
		
    }

    public function destroy(Request $request)
    {
        $request->validate([ 
			'id' => ['req'],
		]);

		$groupData=$this->db->table('groups')->where('user_id',LOGGED_USER)
		    ->where('id',$request->id)->get();
        if($groupData[0]->title != 'favourites' && $groupData[0]->title != 'scanned card')
        {
        $deleted=$this->db->table('groups')->where('user_id',LOGGED_USER)
		    ->where('id',$request->id)->delete();

        if($deleted){
			$this->response->json(["status"=>200,"message"=>"Group removed successfully"]);
		    }   
        }
		$this->response->json(["status"=>400,"message"=>"Ooops this group could not be removed"]);
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'user_id'=>['req'],
            'group_id'=>['req']
            ]);
        
		$group=$this->db->where('user_id',LOGGED_USER)
            ->where('id',$request->group_id)->first('groups');

        if(!($group))
            {
                $this->response->json(["status"=>400,"message"=>"You are not Authorise for this action"]);  
            }

        $userExist=$this->db->where('user_id',$request->user_id)
            ->where('group_id',$request->group_id)->first('user_group');

        if(!($userExist)){   
        $this->db->table('groups')->where('id',$group->id)->update([
            'total_members'=>$group->total_members+1
            ]);
            $this->db->table('user_group')->insert([
                'user_id'=>$request->user_id,
                'group_id'=>$request->group_id
                ]);
			$this->response->json(["status"=>200,"message"=>"User add to the group successfully"]);
            }
		$this->response->json(["status"=>400,"message"=>"This user is already exist in this group"]);
    }

    public function removeUser(Request $request)
    {
        $request->validate([
            'user_id'=>['req'],
            'group_id'=>['req']
            ]);
        
		$group=$this->db->where('user_id',LOGGED_USER)
            ->where('id',$request->group_id)->first('groups');
        if($group)
        {
            $deleted=$this->db->table('user_group')->where('user_id',$request->user_id)
                ->where('group_id',$request->group_id)->delete();

            $this->db->table('groups')->where('id',$group->id)->update([
                'total_members'=>$group->total_members-1
                ]);

            if($deleted){
                $this->response->json(["status"=>400,"message"=>"User Removed Successfully!!!"]);
                }
		    $this->response->json(["status"=>400,"message"=>"Ooops user could not be removed"]);
            }
		$this->response->json(["status"=>400,"message"=>"You are not Authorised for this action"]);
    }

    public function removeContact(Request $request)
    {
        $request->validate([
            'contact_id'=>['req'],
            'group_id'=>['req']
            ]);
        
		$group=$this->db->where('user_id',LOGGED_USER)
            ->where('id',$request->group_id)->first('groups');
        if($group)
        {
            $deleted=$this->db->table('group_contacts')->where('contact_id',$request->contact_id)
                ->where('group_id',$request->group_id)->delete();

                $this->db->table('groups')->where('id',$group->id)->update([
                    'total_contacts'=>$group->total_contacts-1
                    ]);

            if($deleted){
                $this->response->json(["status"=>400,"message"=>"Contact Removed Successfully!!!"]);
                }
		    $this->response->json(["status"=>400,"message"=>"Ooops Contact could not be removed"]);
            }
		$this->response->json(["status"=>400,"message"=>"You are not Authorised for this action"]);

    }

    public function addContact(Request $request)
    {
        $request->validate([
            'contact_id'=>['req'],
            'group_id'=>['req']
            ]);

            $contact=$this->db->where('id',$request->contact_id)->first('phone_contacts');

            if(!$contact)
            {
                $this->response->json(["status"=>400,"message"=>"This contact is not exists"]);    
            }
        
		$group=$this->db->where('user_id',LOGGED_USER)
            ->where('id',$request->group_id)->first('groups');

        if(!($group))
            {
                $this->response->json(["status"=>400,"message"=>"You are not Authorise for this action"]);  
            }

        $contactExist=$this->db->where('contact_id',$request->contact_id)
            ->where('group_id',$request->group_id)->first('group_contacts');

        if(!($contactExist)){     
            $this->db->table('groups')->where('id',$group->id)->update([
                'total_contacts'=>$group->total_contacts+1
                ]);
            $this->db->table('group_contacts')->insert([
                'contact_id'=>$request->contact_id,
                'group_id'=>$request->group_id
                ]);
			$this->response->json(["status"=>200,"message"=>"Contact add to the group successfully",'data'=>$contact]);
            }
		$this->response->json(["status"=>400,"message"=>"This contact is already exist in this group"]);

    }
	 
}
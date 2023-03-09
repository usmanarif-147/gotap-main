<?php namespace app\Controllers\Dashboard;
use app\Controllers\Dashboard\Controller;
use app\Helpers\Request;
use app\Helpers\Response;

class PlatformController extends Controller
{
	
	public function index(){
		$platforms=$this->custom->returnPlatforms('default');
		$this->view->render('admin/platforms/index', [
            'platforms' => $platforms
        ],'admin');
	}
	
	public function store(Request $request){
		$request->validate([
			'title' => ['req','min:2','uniq:platforms'],
			'category_id' => ['req','num','min:1'],
			'input' => ['req'],
			'placeholder_en' => ['str','min:3','max:48'],
			'placeholder_sv' => ['str','min:3','max:48'],
			'description_en' => ['str','min:3','max:191'],
			'description_sv' => ['str','min:3','max:191'],
			'icon' => ['req']
		]);
		
		$this->helper->validateCSRF();
		if($request->hasFile('icon')){
			 $request->icon=$this->helper->uploadFile($request->icon,'uploads/platforms/');
		}
		
		$platform=$this->db->table('platforms')->insert($request->all());
		if($platform)
			redirect('/dashboard/platforms?success=created');
		else
			redirect('/dashboard/platforms/add?error=not_created');
	}
	public function edit($id){
		$platform=$this->custom->returnPlatforms($id);
	
		$platform=$platform['categories'][0]['platforms'][0] ?? null;
		
		if(!$platform)
			Die('Invalid ID');
		$categories=$this->db->where('status',1)->get('categories');
		
		$this->view->render('admin/platforms/edit', [
            'platform' => $platform,
			'categories' => $categories
        ],'admin');
	}
	
	public function update($id, Request $request){
		$request->validate([
			'title' => ['req','min:2'],
			'category_id' => ['req','num','min:1'],
			'input' => ['req'],
			'placeholder_en' => ['str','min:3','max:48'],
			'placeholder_sv' => ['str','min:3','max:48'],
			'description_en' => ['str','min:3','max:191'],
			'description_sv' => ['str','min:3','max:191'],
		]);
		$this->helper->validateCSRF();
		$platform=$this->db->where('id',$id)->first('platforms');
		if(!$platform){
			die("platformm not found");
		}
		$oldIcon=$platform->icon;
		if($request->hasFile('icon')){
			 $request->icon=$this->helper->uploadFile($_FILES['icon'],'uploads/platforms/');
		}else{
		    $request->unset('icon');
		}
		$platform=$this->db->table('platforms')->where('id',$id)->update($request->all());
		if($platform){
			if( $oldIcon && file_exists($oldIcon) && $request->hasFile('icon') && $platform->icon   ){
				unlink($oldIcon);
			}
			redirect('/dashboard/platforms');
		}
		else
			redirect('/dashboard/platform?error=not_updated');
	}
	
	public function destroy($id){
        $response=new Response();
        $platform=$this->db->where('id',$id)->first('platforms');
        if(!$platform){
            $response->json(['status'=>400,'message'=>'Ooops platforms not found']);
        }
        $this->db->where('platform_id',$id)->delete('user_platforms');
        if($this->db->where('id',$id)->delete('platforms')){
            if($platform->icon && file_exists($platform->icon))
                unlink($platform->icon);
            $response->json(['status'=>200,'message'=>'platform deleted successfully']);
        }else 
            $response->json(['status'=>400,'message'=>'Ooops platform could not be deleted']);
    }

	public function showCreateForm(){
		$categories=$this->db->where('status',1)->get('categories');
		$this->view->render('admin/platforms/add', [
			'categories' => $categories
		],'admin');
	}
	
	
	
}

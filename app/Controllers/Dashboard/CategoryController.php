<?php namespace app\Controllers\Dashboard;
use app\Controllers\Dashboard\Controller;
use app\Helpers\Request;

class CategoryController extends Controller
{
	
	public function index(){
		$categories=$this->db->get('categories');
		$this->view->render('admin/categories/index', [
            'categories' => $categories
        ],'admin');
	}
	
	public function store(Request $request){
		$request->validate([
			'name' => ['req','min:2'],
			'name_sv' => ['req','min:2'],
			'status' => ['req','in:0,1']
		]);
		$this->helper->validateCSRF();
		$category=$this->db->table('categories')->insert($request->validated());
		if($category)
			redirect('/dashboard/categories?success=created');
		else
			redirectBackWithError('Ooops Category could not be added');
	}
	public function edit($id){
		$category=$this->db->where('id',$id)->first('categories');

		if(!$category)
			Die('Invalid ID');
	
		$this->view->render('admin/categories/edit', [
            'category' => $category,
        ],'admin');
	}
	
	public function update($id, Request $request){
		$request->validate([
			'name' => ['req','min:2'],
			'name_sv' => ['req','min:2'],
			'status' => ['req','in:0,1']
		]);
		$this->helper->validateCSRF();
		$category=$this->db->where('id',$id)->first('categories');
		if(!$category){
			die("Category not found");
		}
		
		$category=$this->db->table('categories')->where('id',$id)->update($request->validated());
		if($category){
			redirect('/dashboard/categories');
		}
		else
			redirect('/dashboard/categories?error=not_updated');
			
	}

	public function showCreateForm(){
		$this->view->render('admin/categories/add', [],'admin');
	}
	
	
	
}

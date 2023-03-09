<?php namespace app\Controllers\Api;

use app\Models\DB;
use app\Helpers\Helper;
use app\Helpers\Response;
use app\Models\Custom;

class Controller
{
	
	protected $db;
	protected $helper;
	protected $response;
	protected $custom;
	function __construct()
	{		
		$this->db=new DB();
		$this->helper=new Helper();
		$this->response=new Response();
		$this->custom=new Custom();
	}
	
}

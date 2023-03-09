<?php

namespace app\Controllers\Dashboard;

use app\Controllers\Dashboard\Controller;
use app\Helpers\Request;
use app\Helpers\View;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view->load('admin/login');
            die;
        } else {
            $request->validate([
                'email' => ['req', 'email'],
                'password' => ['req', 'min:6'],
            ]);
            $password = sha1($request->password);
            $admin = $this->db->where('email', $request->email)->where('password', $password)->first('admins');
            if (!$admin) {
                $_SESSION['ot_error'] = 'Invalid credentials';
                $this->view->load('admin/login');
            } else {
                $_SESSION[PRE_FIX . 'admin_id'] = $admin->id;
                redirect('/dashboard');
            }
        }
    }

    public function dashboard()
    {
        $data['total_users'] = $this->db->table("users")->count();
        $data['total_platforms'] = $this->db->table("platforms")->count();
        $data['total_scans'] = $this->db->table("scan_visits")->count();
        $data['active_platforms'] = $this->db->table("platforms")->where('status', 1)->count();
        $data['active_cards'] = $this->db->table('cards')->where('status', 1)->count();

        $this->view->render('admin/index', [
            'data' => $data
        ], 'admin');
    }
}

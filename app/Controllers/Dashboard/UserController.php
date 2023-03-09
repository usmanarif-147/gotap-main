<?php

namespace app\Controllers\Dashboard;

use app\Controllers\Dashboard\Controller;
use app\Helpers\Request;
use app\Helpers\Response;
use app\Helpers\Email;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        $users = $this->db->table('users')
            ->select('users.*, COUNT(user_cards.user_id) AS card_count')
            ->leftJoin('user_cards', 'user_cards.user_id', 'users.id')
            ->groupBy('users.id')
            ->get();

        // $user_cards = 
        $this->view->render('admin/users/index', [
            'users' => $users
        ], 'admin');
    }

    public function edit($id)
    {
        $user = $this->db->where('id', $id)->first('users');
        if (!$user)
            die('Invalid ID');
        $this->view->render('admin/users/edit', [
            'user' => $user,
        ], 'admin');
    }
    public function hiddenUsers()
    {
        $users = $this->db->table('users')->where('showinalluser', 0)->get();
        $this->view->render('admin/users/index', [
            'users' => $users
        ], 'admin');
    }
    public function update($id, Request $request)
    {
        $request->validate([
            'name' => ['req', 'min:3', 'max:30', 'str'],
            'email' => ['req', 'email'],
            'username' => ['req', 'min:3'],
            'phone' => ['req', 'min:11'],
        ]);
        $this->helper->validateCSRF();
        $user = $this->db->where('id', $id)->first('users');

        if (!$user) {
            die("platform not found");
        }
        if (isset($request->email) && $request->email != $user->email) {
            $duplicate = $this->db->where('email', $request->email)->first('users');
            if ($duplicate) {
                redirectBackWithError("Email is already taken");
            }
        }
        if (isset($request->username) && $request->username != $user->username) {
            $duplicate = $this->db->where('username', $request->username)->first('users');
            if ($duplicate) {
                redirectBackWithError("Username is already taken");
            }
        }
        $oldPhoto = $user->photo;

        if ($request->hasFile('photo')) {
            $request->photo = $request->upload('photo', 'uploads/users', 'image');
        } else {
            $request->unset('photo');
        }

        $user = $this->db->table('users')->where('id', $id)->update($request->all());
        if ($user) {
            if ($oldPhoto && file_exists($oldPhoto) && $request->hasFile('photo') && $user->photo) {
                unlink($oldPhoto);
            }
            redirect('/dashboard/users');
        } else
            redirectBackWithError("Ooops user could not be updated");
    }

    public function suspendNotification($id)
    {
        $user = $this->db->where('id', $id)->first('users');
        $email = new Email();
        $message = 'We regret to inform you that your account will be suspend if gotap team did not find any activity from your account.';
        $email->isSuspendNotification($user->email, 'Account Suspend Notification', $message);
        echo json_encode(['message' => 'Suspend notification has been sent successfully']);
    }

    public function changeSuspendStatus($id)
    {
        $user = $this->db->where('id', $id)->first('users');

        $user = $this->db->table('users')->where('id', $id)->update([
            'is_suspended' => $user->is_suspended ? 0 : 1,
        ]);

        $user = $this->db->where('id', $id)->first('users');
        if (!$user->is_suspended) {
            $email = new Email();
            $message = 'Your account is resumed.';
            $email->isSuspendNotification($user->email, 'Account Resumed Notification', $message);
            echo json_encode(['message' => 'User account resumed successfully']);
        }
        if ($user->is_suspended) {
            $email = new Email();
            $message = 'Your account is suspended.';
            $email->isSuspendNotification($user->email, 'Account Suspended Notification', $message);
            echo json_encode(['message' => 'User account suspended successfully']);
        }
        exit();
        // 		return $this->response->json(['message' => 'Statuse Updated successfully']);
    }


    public function destroy($id)
    {
        $response = new Response();
        $user = $this->db->where('id', $id)->first('users');
        if (!$user) {
            $response->json(['status' => 400, 'message' => 'Ooops user not found']);
        }
        $this->db->where('user_id', $id)->delete('user_platforms');
        $this->db->where('visited_id', $id)->orWhere('visiting_id', $id)->delete('scan_visits');
        $this->db->where('connected_id', $id)->orWhere('connecting_id', $id)->delete('connects');
        if ($this->db->where('id', $id)->delete('users')) {
            if ($user->photo && file_exists($user->photo))
                unlink($user->photo);
            $response->json(['status' => 200, 'message' => 'user deleted successfully']);
        } else
            $response->json(['status' => 400, 'message' => 'Ooops user could not be deleted']);
    }

    public function showCreateForm()
    {
        $this->view->render('admin/platforms/add', [], 'admin');
    }
}

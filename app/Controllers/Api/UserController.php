<?php

namespace app\Controllers\Api;

use app\Controllers\Api\Controller;
use app\Helpers\Request;
use app\Helpers\Email;
use Exception;

class UserController extends Controller
{
	public function profile()
	{
		$username = $_GET['username'] ?? LOGGED_USER;

		$attr = 'id';
		if (!is_numeric($username))
			$attr = 'username';
		$res['user'] = $this->db->where($attr, $username)->first('users');
		if (!$res['user'])
			$this->response->error("Profile not found");
		if (!$res['user']->status) {
			$this->response->json(["message" => "Sorry, Account is deactivated"]);
		}
		if ($res['user']->is_suspended) {
			$this->response->json(["message" => "Sorry, Account is suspended"]);
		}


		$res['user']->connected = 0;
		if ($res['user']->id != LOGGED_USER) {
			$connected = $this->db->where('connecting_id', LOGGED_USER)
				->where('connected_id', $res['user']->id)->first('connects');
			if ($connected) {
				$res['user']->connected = 1;
			}
		}

		$categories = $this->custom->returnPlatforms($res['user']->id, 'user');
		$res['categories'] = $categories['categories'];
		$res['user']->direct = $categories['direct'];


		if ($res['user'] && isset($_GET['source']) && $_GET['source'] == 'gotap') {
			$this->db->table('users')->where('id', $res['user']->id)->increment('tiks');
			// SAVE IF NEW VISIT
			$res['user']->connected = 1;
			$visited = $this->db->where('visiting_id', LOGGED_USER)
				->where('visited_id', $res['user']->id)->first('scan_visits');
			if (!$visited) {
				$visit['visiting_id'] = LOGGED_USER;
				$visit['visited_id'] = $res['user']->id;
				$this->db->table('scan_visits')->insert($visit);
			}
			$connected = $this->db->where('connecting_id', LOGGED_USER)
				->where('connected_id', $res['user']->id)->first('connects');
			if (!$connected) {
				$connect['connecting_id'] = LOGGED_USER;
				$connect['connected_id'] = $res['user']->id;
				$this->db->table('connects')->insert($connect);
			}
		}
		$this->response->json(["status" => 200, "message" => "User Profile", 'data' => $res]);
	}

	public function update(Request $request)
	{
		$request->validate([
			'email' => ['email'],
			'password' => ['str', 'min:6'],
			'name' => ['str', 'min:3', 'max:40'],
			'address' => ['str', 'min:3', 'max:60'],
			'work_position' => ['min:2', 'max:20'],
			// 'cover_photo' =>['req'],
			//'gender' =>['in:1,2,3,4']
		]);
		$request->unset('password');
		$user = $this->db->where('id', LOGGED_USER)->first('users');
		$oldPhoto = $user->photo;
		$oldCover_photo = $user->cover_photo;

		if (isset($request->email) && $request->email != $user->email) {
			$duplicate = $this->db->where('email', $request->email)->first('users');
			if ($duplicate) {
				$this->response->json(["status" => 400, "message" => "Email is already taken", 'data' => (object)[]]);
			}
		}

		if (isset($request->username) && $request->username != $user->username) {
			$duplicate = $this->db->where('username', $request->username)->first('users');
			if ($duplicate) {
				$this->response->json(["status" => 400, "message" => "Username is already taken", 'data' => (object)[]]);
			}
		}


		if ($request->hasFile('cover_photo'))
			$request->cover_photo = $this->helper->uploadFile($request->cover_photo, 'uploads/users', 'image');

		if ($request->hasFile('photo'))
			$request->photo = $this->helper->uploadFile($request->photo, 'uploads/users', 'image');

		$user = $this->db->table('users')->where('id', LOGGED_USER)->update($request->all());
		if ($user) {
			if ($oldCover_photo && file_exists($oldCover_photo) && $request->hasFile('photo'))
				unlink($oldCover_photo);
			if ($oldPhoto && file_exists($oldPhoto) && $request->hasFile('photo'))
				unlink($oldPhoto);
			$this->response->json(["status" => 200, "message" => "User Updated Successfully", 'data' => $user]);
		}
		$this->response->json(["status" => 400, "message" => "Ooops error while updating", 'data' => (object)[]]);
	}

	public function connect(Request $request)
	{
		$request->validate([
			'id' => ['req', 'num', 'min:1'],
		]);
		$connect['connecting_id'] = LOGGED_USER;
		$connect['connected_id'] = $_GET['id'];
		$connected = $this->db->where('connected_id', $connect['connected_id'])
			->where('connecting_id', $connect['connecting_id'])->first('connects');
		if ($connected) {
			$deleted = $this->db->table('connects')->where('connected_id', $connect['connected_id'])
				->where('connecting_id', $connect['connecting_id'])->delete();
			if ($deleted)
				$this->response->json(["status" => 200, "message" => "Connection removed successfully", 'data' => (object)[]]);
			$this->response->json(["status" => 200, "message" => "Ooops Could not be removed", 'data' => (object)[]]);
		}
		$connect = $this->db->table('connects')->insert($connect);
		if ($connect)
			$this->response->success("Connected successfully");
		$this->response->json(["status" => 400, "message" => "Ooops  could not be conneted", 'data' => (object)[]]);
	}
	public function search()
	{
		$q = $_GET['q'] ?? '';
		$res['connected'] = $this->custom->searchUsers($q);
		$res['featured'] = $this->db->table('users')->where('featured', 1)->get();
		$this->response->success("Searched Profiles", $res);
	}

	public function privateProfile()
	{
		$userId = LOGGED_USER;
		$user = $this->db->table('users')->where('id', LOGGED_USER)->first();

		$updated = $this->db->table('users')->where('id', $userId)->update(['private' => $user->private ? 0 : 1]);

		$status = "private";
		if ($updated->private == 0) {
			$status = "public";
		}

		$this->response->json(["status" => 200, "message" => "Profile is set to " . $status, 'data' => $updated]);
	}

	public function userDirect()
	{
		$userId = LOGGED_USER;
		$user = $this->db->table('users')->where('id', LOGGED_USER)->first();

		$updated = $this->db->table('users')->where('id', $userId)->update(['user_direct' => $user->user_direct ? 0 : 1]);

		return $this->response->json(['data' => $updated]);
	}

	// deactivate account
	public function deactivateAccount()
	{
		$userId = LOGGED_USER;

		$user_groups = $this->db->table('user_group')
			->where('user_id', $userId)
			->get();


		foreach ($user_groups as $user_group) {

			$group = $this->db->table('groups')->where('id', $user_group->group_id)->first();

			// return $this->response->json(['status' => 200,  'message' => $group]);

			$this->db->table('groups')->where('id', $group->id)->update(
				[
					'total_members' => (int)$group->total_members - 1
				]
			);
		}

		//   $user_groups = $this->db->table('groups')
		//     ->where('user_id', $userId)
		//     ->get();  

		$user = $this->db->table('users')->where('id', $userId)->first();
		$updated = $this->db->table('users')->where('id', $userId)->update(
			[
				'status' => 0
			]
		);
		if ($updated) {
			// 			$email = new Email();
			$message = 'You have 2 weeks to recover your account, otherwise your account will be deleted.';
			// 			$check = $email->accountDeactivatedEmail($user->email, $message);
			return $this->response->json(['status' => 200,  'message' => $message]);
		}
		return $this->response->json(['message' => 'Something went wrong']);
	}
}

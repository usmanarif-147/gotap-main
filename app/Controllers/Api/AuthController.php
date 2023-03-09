<?php

namespace app\Controllers\Api;

use app\Controllers\Api\Controller;
use app\Helpers\Request;
use app\Helpers\JWT;
use app\Helpers\Email;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$request->validate([
			'email' => ['req', 'email'],
			'password' => ['str', 'min:6', 'max:18']
		]);
		$request->password = sha1($request->password);
		$user = $this->db->where('email', $request->email)
			->where('password', $request->password)->first('users');
		// 			$this->response->json(["status"=>400,"message"=>$user]);
		if (!$user)
			$this->response->json(["status" => 400, "message" => "Credentials are incorrect"]);
		if (!$user->status) {
			$this->response->json(["message" => "Sorry, Account is deactivated"]);
		}
		if ($user->is_suspended) {
			$this->response->json(["message" => "Sorry, Account is suspended"]);
		}
		$user->token = JWT::generateToken($user->id);
		$this->response->json(["status" => 200, "message" => "Logged in successfully", 'data' => $user]);
	}

	public function register(Request $request)
	{
		$request->validate([
			'username' => ['str', 'min:3', 'max:15', 'regex:/^[ A-Za-z0-9_.]*$/', 'uniq:users'],
			'name' => ['str', 'min:3', 'max:15'],
			'email' => ['req', 'email', 'uniq:users'],
			'phone' => ['req', 'min:10', 'max:14'],
			'password' => ['req', 'str', 'min:6', 'confirmed']
		]);

		$request->password = sha1($request->password);
		$request->unset(['password_confirmation', 'tiks', 'status']);

		$user = $this->db->table('users')->insert($request->all());
		$this->db->table('groups')->insert([
			'user_id' => $user->id,
			'title' => "favourites"
		]);
		$this->db->table('groups')->insert([
			'user_id' => $user->id,
			'title' => "scanned card"
		]);
		if (!$user)
			$this->response->json(["status" => 400, "message" => "Ooops user could not be created"]);
		$user->token = JWT::generateToken($user->id);
		$this->response->json(["status" => 200, "message" => "Registered successfully", 'data' => $user]);
	}


	public function forgetPassword(Request $request)
	{
		$request->validate([
			'email' => ['req', 'email']
		]);
		$user = $this->db->table('users')->where('email', $request->email);
		if (!$user)
			$this->response->json(["status" => 400, "message" => "This Email is not registered in our application"]);
		$email = new Email();
		$email->sendForgetPasswordEmail($request->email);
		$this->response->json(["status" => 200, "message" => "Email with OTP has been sent,pleasecheck your mail"]);
	}


	public function resetPassword(Request $request)
	{
		$request->validate([
			'email' => ['req', 'email'],
			'otp' => ['min:6'],
			'password' => ['req', 'min:6']
		]);

		$token = $this->db->where('email', $request->email)->first('reset_tokens');
		if (!$token)
			$this->response->error("You are unauthorized to change password");

		if (time() > $token->expiry + 1200)
			$this->response->error("OTP is Expired, You can only update password within 20 minutes");

		if ($token->token != $request->otp)
			$this->response->error("OTP is incorrect");

		$update['password'] = sha1($request->password);
		if ($user = $this->db->table('users')->where('email', $request->email)->update($update)); {
			$this->db->table('reset_tokens')->where('id', $token->id);
			$this->response->successMessage("Password Reset Successful");
		}
		$this->response->error("Ooops error while resetting password");
	}

	public function otpVerify(Request $request)
	{
		$request->validate([
			'email' => ['req', 'email'],
			'otp' => ['req', 'min:6'],
		]);

		$token = $this->db->where('email', $request->email)->first('reset_tokens');
		if (!$token)
			$this->response->error("You are unauthorized to change password");

		if (time() > $token->expiry + 1200)
			$this->response->error("OTP is Expired, You can only update password within 20 minutes");

		if ($token->token != $request->otp)
			$this->response->error("OTP is incorrect");

		$this->response->successMessage("OTP is verified Successfully!!!");
	}

	// recover account
	public function recoverAccount(Request $request)
	{
		$request->validate([
			'email' => ['email'],
		]);

		$user = $this->db->table('users')->where('email', $request->email)->first();
		if (!$user) {
			return $this->response->json(['message' => 'Email is not registered']);
		}

		if ($user->status == 0) {
			$updated = $this->db->table('users')->where('email', $request->email)->update(
				[
					'status' => 1
				]
			);
			if ($updated) {
				return $this->response->json(['message' => 'Account recovered successfully']);
			}
		} else {
			return $this->response->json(['message' => 'Account is already activated']);
		}

		return $this->response->json(['message' => 'Something went wrong']);
	}
}

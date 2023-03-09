<?php

namespace app\Controllers;

use app\Controllers\Controller;
use app\Helpers\Email;
use app\Helpers\Request;
use Exception;

class DeleteUsers extends Controller
{

    // delete Account
    public function deleteAccount()
    {
        
        $email = new Email();
        $message = 'Account deleted successfully';
        // $email->accountDeactivatedEmail('usmanarif.9219@gmail.com', $message);
        
        return "working";

        // $userId = LOGGED_USER;

        // $user = $this->db->where('id', $userId)->first('users');

        // if (!$user) {
        //     $this->response->json(['status' => 400, 'message' => 'Ooops user not found']);
        // }
        // try {
        //     $this->db->where('user_id', $userId)->delete('user_cards');
        //     $this->db->where('user_id', $userId)->delete('user_platforms');
        //     $this->db->where('user_id', $userId)->delete('user_group');
        //     $this->db->where('user_id', $userId)->delete('groups');
        //     $this->db->where('visited_id', $userId)->orWhere('visiting_id', $userId)->delete('scan_visits');
        //     $this->db->where('connected_id', $userId)->orWhere('connecting_id', $userId)->delete('connects');
        //     if ($this->db->where('id', $userId)->delete('users')) {
        //         if ($user->photo && file_exists($user->photo)) {
        //             unlink($user->photo);
        //         }
        //     }
        //     $this->response->json(['status' => 200, 'message' => 'user deleted successfully']);
        // } catch (Exception $ex) {
        //     $this->response->json(['status' => 400, 'message' => $ex->getMessage()]);
        // }
    }
}

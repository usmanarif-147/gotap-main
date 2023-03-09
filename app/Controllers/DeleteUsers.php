<?php

namespace app\Controllers;

use app\Controllers\Controller;
use app\Helpers\Email;
use app\Helpers\Request;
use DateTime;
use Exception;

class DeleteUsers extends Controller
{

    // delete Account
    // public function deleteAccount()
    // {

    //     $users = $this->db->table('users')->where('deactivated_at', '!=', 'null')->get();

    //     // die(date_default_timezone_get());
    //     // echo '<pre>';
    //     // print_r($users);
    //     // echo '</pre>';
    //     // die();

    //     // calculate deactivated_at time with today's date time
    //     $date = date('Y-m-d H:i:s');
    //     $send_email_to_users = [];
    //     $delete_users = [];
    //     $time_left = [];
    //     foreach ($users as $user) {

    //         $start_date = strtotime($date);
    //         // $end_date = strtotime(date('Y-m-d', strtotime($user->deactivated_at)));
    //         $end_date = strtotime($user->deactivated_at);

    //         $difference = $start_date - $end_date;

    //         // $time = floor($difference / (60 * 60));
    //         $time = floor($difference / 60);

    //         array_push($time_left, ['current_date' => $date, 'deactivation_date' => $user->deactivated_at, 'hours_left' => $time]);

    //         // if ($hours == 24) {
    //         //     array_push($send_email_to_users, $user);
    //         // }
    //         // if ($hours == 0) {
    //         //     array_push($delete_users, $user);
    //         // }
    //     }

    //     echo '<pre>';
    //     print_r($time_left);
    //     echo '</pre>';
    //     echo '<pre>';
    //     print_r($send_email_to_users);
    //     echo '</pre>';
    //     echo '<pre>';
    //     print_r($delete_users);
    //     echo '</pre>';
    //     die();
    //     // if difference remains only 1 day then send email to all users

    //     // if difference remains 0 then delete account and send email

    //     echo '<pre>';
    //     print_r($users);
    //     echo '</pre>';

    //     die();

    //     // $email = new Email();
    //     // $message = 'Account deleted successfully';
    //     // $email->accountDeactivatedEmail('usmanarif.9219@gmail.com', $message);

    //     // return "working";

    //     // $userId = LOGGED_USER;

    //     // $user = $this->db->where('id', $userId)->first('users');

    //     // if (!$user) {
    //     //     $this->response->json(['status' => 400, 'message' => 'Ooops user not found']);
    //     // }
    //     // try {
    //     //     $this->db->where('user_id', $userId)->delete('user_cards');
    //     //     $this->db->where('user_id', $userId)->delete('user_platforms');
    //     //     $this->db->where('user_id', $userId)->delete('user_group');
    //     //     $this->db->where('user_id', $userId)->delete('groups');
    //     //     $this->db->where('visited_id', $userId)->orWhere('visiting_id', $userId)->delete('scan_visits');
    //     //     $this->db->where('connected_id', $userId)->orWhere('connecting_id', $userId)->delete('connects');
    //     //     if ($this->db->where('id', $userId)->delete('users')) {
    //     //         if ($user->photo && file_exists($user->photo)) {
    //     //             unlink($user->photo);
    //     //         }
    //     //     }
    //     //     $this->response->json(['status' => 200, 'message' => 'user deleted successfully']);
    //     // } catch (Exception $ex) {
    //     //     $this->response->json(['status' => 400, 'message' => $ex->getMessage()]);
    //     // }
    // }

    public function deleteAccount()
    {

        $email = new Email();
        $message = 'Account deleted successfully';
        // $email->accountDeactivatedEmail('usmanarif.9219@gmail.com', $message);
        echo "working";
    }
}

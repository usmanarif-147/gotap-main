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

    //     // $email = new Email();
    //     // $message = 'Account deleted successfully';
    //     // $email->accountDeactivatedEmail('cotiy99733@orgria.com', $message);
    //     // die("working");


    //     $users = $this->db->table('users')->where('deactivated_at', '!=', 'null')->get();

    //     // calculate deactivated_at time with today's date time
    //     $date = date('Y-m-d H:i:s');
    //     $before_delete_email_to_users = [];
    //     $after_delete_email_to_users = [];
    //     // $time_left = [];
    //     foreach ($users as $user) {

    //         $start_date = strtotime($date);
    //         $end_date = strtotime($user->deactivated_at);

    //         $difference = $start_date - $end_date;

    //         // minutes
    //         // $time = floor($difference / 60);

    //         // hours
    //         // $time = floor($difference / (60 * 60));

    //         // days
    //         $time = floor($difference / (60 * 60 * 24));

    //         // array_push($time_left, ['current_date' => $date, 'deactivation_date' => $user->deactivated_at, 'time' => $time, 'user' => $user->email]);
    //         if ($time == 24) {
    //             array_push($before_delete_email_to_users, ['email' => $user->email, 'name' => $user->name, 'username' => $user->username]);
    //         }
    //         if ($time == 0) {
    //             array_push($after_delete_email_to_users, ['email' => $user->email, 'name' => $user->name, 'username' => $user->username]);
    //         }

    //         // if ($time == 1056) {
    //         //     array_push($before_delete_email_to_users, ['email' => $user->email, 'name' => $user->name, 'username' => $user->username]);
    //         // }
    //         // if ($time == 1057) {
    //         //     array_push($after_delete_email_to_users, ['email' => $user->email, 'name' => $user->name, 'username' => $user->username]);
    //         // }
    //     }

    //     // echo '<pre>';
    //     // print_r($time_left);
    //     // echo '</pre>';

    //     // die();

    //     $this->beforeDeleteAccountEmailNotification($before_delete_email_to_users);
    //     $this->afterDeleteAccountEmailNotification($after_delete_email_to_users);

    //     // echo "done";
    //     // die();

    //     // echo '<pre>';
    //     // print_r($before_delete_email_to_users);
    //     // echo '</pre>';
    //     // echo '<pre>';
    //     // print_r($after_delete_email_to_users);
    //     // echo '</pre>';
    //     // die();

    //     // echo '<pre>';
    //     // print_r($users);
    //     // echo '</pre>';

    //     // die();

    //     // return "working";

    //     // $userId = LOGGED_USER;
    // }

    // delete Account
    public function deleteAccount()
    {
        // $email = new Email();
        // $message = 'Account deletetion notification';
        // $email->accountDelete('usmanarif.9219@gmail.com', 'Account Deletion Notification', $message);

        // die("working");

        $users = $this->db->table('users')->where('deactivated_at', '!=', 'null')->get();

        $date = date('Y-m-d H:i:s');
        $before_delete_email_to_users = [];
        $after_delete_email_to_users = [];
        $time_left = [];

        foreach ($users as $user) {

            $start_date = strtotime($date);
            $end_date = strtotime($user->deactivated_at);

            $difference = $start_date - $end_date;

            // days
            $time = floor($difference / (60 * 60 * 24));

            //minutes
            // $time = floor($difference / 60);

            array_push($time_left, ['current_date' => $date, 'deactivation_date' => $user->deactivated_at, 'time' => $time, 'user' => $user->email]);

            if ($time == 24 && $user->is_email_sent == 0) {
                array_push($before_delete_email_to_users, ['email' => $user->email, 'name' => $user->name, 'username' => $user->username]);
            }
            if ($time == 0 && $user->is_email_sent == 1) {
                array_push($after_delete_email_to_users, ['email' => $user->email, 'name' => $user->name, 'username' => $user->username]);
            }
        }

        $this->beforeDeleteAccountEmailNotification($before_delete_email_to_users);
        $this->afterDeleteAccountEmailNotification($after_delete_email_to_users);
    }




    public function beforeDeleteAccountEmailNotification($users)
    {
        if (count($users) > 0) {
            for ($i = 0; count($users); $i++) {
                $this->db->table('users')->where('email', $users[$i]['email'])->update([
                    'is_email_sent' => 1
                ]);
                $email = new Email();
                $message = 'Yout Account will be deleted after 1 day.';
                $email->accountDelete($users[$i]['email'], 'Account Deletion Notification', $message);
            }
        }
        return;
    }

    public function afterDeleteAccountEmailNotification($users)
    {
        if (count($users) > 0) {

            for ($i = 0; count($users); $i++) {

                $user = $this->db->where('email', $users[$i]['email'])->first('users');

                $this->db->where('user_id', $user->id)->delete('user_cards');
                $this->db->where('user_id', $user->id)->delete('user_platforms');
                $this->db->where('user_id', $user->id)->delete('user_group');
                $this->db->where('user_id', $user->id)->delete('groups');
                $this->db->where('visited_id', $user->id)->orWhere('visiting_id', $user->id)->delete('scan_visits');
                $this->db->where('connected_id', $user->id)->orWhere('connecting_id', $user->id)->delete('connects');
                if ($this->db->where('id', $user->id)->delete('users')) {
                    if ($user->photo && file_exists($user->photo)) {
                        unlink($user->photo);
                    }
                }

                $email = new Email();
                $message = 'Yout Account is deleted successfully';
                $email->accountDelete($users[$i]['email'], 'Account Deleted', $message);
            }
        }
        return;
    }
}

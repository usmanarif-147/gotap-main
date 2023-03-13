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
    public function deleteAccount()
    {

        $email = new Email();
        $message = 'Account deleted successfully';
        $email->accountDeactivatedEmail('usmanarif.9219@gmail.com', $message);
        die("working");


        $users = $this->db->table('users')->where('deactivated_at', '!=', 'null')->get();

        // calculate deactivated_at time with today's date time
        $date = date('Y-m-d H:i:s');
        $before_delete_email_to_users = [];
        $after_delete_email_to_users = [];
        $time_left = [];
        foreach ($users as $user) {

            $start_date = strtotime($date);
            $end_date = strtotime($user->deactivated_at);

            $difference = $start_date - $end_date;

            // minutes
            $time = floor($difference / 60);

            // hours
            // $time = floor($difference / (60 * 60));

            // days
            // $time = floor($difference / (60 * 60 * 24));

            array_push($time_left, ['current_date' => $date, 'deactivation_date' => $user->deactivated_at, 'time' => $time, 'user' => $user->email]);
            // if ($hours == 24) {
            //     array_push($send_email_to_users, $user);
            // }
            // if ($hours == 0) {
            //     array_push($delete_users, $user);
            // }

            if ($time == 953) {
                array_push($before_delete_email_to_users, ['email' => $user->email, 'name' => $user->name, 'username' => $user->username]);
            }
            if ($time == 954) {
                array_push($after_delete_email_to_users, ['email' => $user->email, 'name' => $user->name, 'username' => $user->username]);
            }
        }

        echo '<pre>';
        print_r($time_left);
        echo '</pre>';

        // die();

        $this->beforeDeleteAccountEmailNotification($before_delete_email_to_users);
        $this->afterDeleteAccountEmailNotification($after_delete_email_to_users);

        echo "done";
        die();

        echo '<pre>';
        print_r($before_delete_email_to_users);
        echo '</pre>';
        echo '<pre>';
        print_r($after_delete_email_to_users);
        echo '</pre>';
        die();
        // if difference remains only 1 day then send email to all users

        // if difference remains 0 then delete account and send email

        echo '<pre>';
        print_r($users);
        echo '</pre>';

        die();

        // return "working";

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

    public function beforeDeleteAccountEmailNotification($users)
    {
        if (count($users) > 0) {
            for ($i = 0; count($users); $i++) {
                $email = new Email();
                $message = 'Yout Account will be deleted after 1 day.';
                $email->accountDeactivatedEmail($users[$i]['email'], $message);
            }
        }
        return;
    }

    public function afterDeleteAccountEmailNotification($users)
    {
        if (count($users) > 0) {

            for ($i = 0; count($users); $i++) {
                $email = new Email();
                $message = 'Yout Account is deleted successfully';
                $email->accountDeactivatedEmail($users[$i]['email'], $message);
            }
        }
        return;
    }
}

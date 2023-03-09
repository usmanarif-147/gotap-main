<?php

namespace app\Controllers\Api;

use app\Controllers\Api\Controller;
use app\Helpers\Request;

class CardController extends Controller
{
    public function index()
    {
        $cards = $this->db->table('user_cards')
            ->select('cards.id, cards.uuid, cards.description, user_cards.status, user_cards.created_at')
            ->join('cards', 'cards.id', 'user_cards.card_id')
            ->where('user_id', LOGGED_USER)
            ->get();

        $this->response->json(["status" => 200, "data" => $cards]);
    }

    public function cardProfileDetail(Request $request)
    {
        $request->validate([
            'card_uuid' => ['req'],
        ]);

        $card = $this->db->where('uuid', $request->card_uuid)->first('cards');
        if (!$card) {
            return  $this->response->json(["status" => 422, 'message' => 'Card not found']);
        }

        if (!$card->status) {
            $this->response->json(["status" => 200, "message" => "Card not activated"]);
        }

        $checkCard = $this->db->table('user_cards')
            ->select('user_cards.user_id')
            ->where('card_id', $card->id)
            ->where('status', 1)
            ->first();

        if (!$checkCard) {
            $this->response->json(["status" => 200, "message" => "User profile not accessible"]);
        }

        $res['user'] = $this->db->where('id', $checkCard->user_id)->first('users');
        if (!$res['user'])
            $this->response->error("Profile not found");
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

    public function activateCard(Request $request)
    {
        $request->validate([
            'card_uuid' => ['req'],
        ]);

        // check card exist
        $card = $this->db->where('uuid', $request->card_uuid)->first('cards');
        if (!$card) {
            $this->response->json(["status" => 422, "message" => "Card not found"]);
        }

        if ($card->status) {
            $this->response->json(["status" => 200, "message" => "Card is already activated"]);
        }

        $this->db->table('user_cards')->insert([
            'card_id' => $card->id,
            'user_id' => LOGGED_USER,
            'status' => 1
        ]);

        $this->db->table('cards')->where('id', $card->id)->update([
            'status' => 1
        ]);

        $this->response->json(["status" => 200, "message" => "Card activated successfully"]);
    }

    public function changeCardStatus(Request $request)
    {
        $request->validate([
            'card_uuid' => ['req'],
        ]);

        $card = $this->db->where('uuid', $request->card_uuid)->first('cards');
        if (!$card) {
            return  $this->response->json(["status" => 422, 'message' => 'Card not found']);
        }
        $checkCard = $this->db->table('user_cards')
            ->where('user_id', LOGGED_USER)
            ->where('card_id', $card->id)
            ->get();
        if (!$checkCard) {
            return $this->response->json(["status" => 200, 'message' => 'Not authenticated user']);
        }

        $user_card = $this->db->table('user_cards')
            ->where('user_id', LOGGED_USER)
            ->where('card_id', $card->id)
            ->first();

        $this->db->table('user_cards')
            ->where('user_id', LOGGED_USER)
            ->where('card_id', $card->id)
            ->update(['status' => $user_card->status ? 0 : 1]);

        $user_card = $this->db->table('user_cards')
            ->where('user_id', LOGGED_USER)
            ->where('card_id', $card->id)
            ->first();

        if ($user_card->status) {
            return $this->response->json(['status' => 200, 'message' => 'User card activated successfully']);
        }
        return $this->response->json(['status' => 200, 'message' => 'User card deactivated successfully']);
    }
}

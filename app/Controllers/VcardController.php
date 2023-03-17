<?php

namespace app\Controllers;

use app\Controllers\Api\Controller;
use app\Helpers\Request;
use JeroenDesloovere\VCard\VCard;

class VcardController extends Controller
{
	public function create($prop)
	{

		die("working");
		$prop = explode('-', $prop);
		if (!is_numeric($prop[0]) || $prop[0] < 1 || !isset($prop[1]))
			die('Invalid Request');

		$id = $prop[0];
		$phone = $prop[1];
		$user = $this->db->where('id', $id)->first('users');
		if (!$user)
			die("User Not Found");
		// define vcard
		$vcard = new VCard();
		// add personal data
		$vcard->addName($user->username);

		if ($user->email)
			$vcard->addEmail($user->email);
		if ($user->phone)
			$vcard->addPhoneNumber($phone, 'PREF;WORK');
		$vcard->addNote('Hard press on the profile picture above to save this contact to your phone!');

		return $vcard->download();
	}


	public function addToContact($id)
	{
		$user = $this->db->where('id', $id)->first('users');
		if (!$user)
			die("User Not Found");

		$vcard = new VCard();
		$vcard->addName($user->username);
		if ($user->email)
			$vcard->addEmail($user->email);
		if ($user->phone)
			$vcard->addPhoneNumber($user->phone, 'PREF;WORK');
		$url = 'https://app.gocoompany.com/' . $user->username;
		$vcard->addURL('https://app.gocoompany.com/' . $user->username);
		$vcard->addNote('Hard press on the profile picture above to save this contact to your phone!');
		if ($user->photo) {
			$vcard->addURL('https://app.gocoompany.com/' . $user->photo);
		}

		return $vcard->download();
	}
}

<?php

namespace app\Controllers\Dashboard;

use app\Controllers\Dashboard\Controller;
use app\Helpers\Request;

class CardController extends Controller
{

	public function index()
	{
		$cards = $this->db->get('cards');

		$this->view->render('admin/cards/index', [
			'cards' => $cards
		], 'admin');
	}


public function changeStatus($id)
	{
		$card = $this->db->where('id', $id)->first('cards');

		$card = $this->db->table('cards')->where('id', $id)->update([
			'is_assigned' => $card->is_assigned ? 0 : 1,
		]);
		
		echo json_encode(['message' => 'status updated successfully']);
		exit();
// 		return $this->response->json(['message' => 'Statuse Updated successfully']);
	}

	public function showCreateForm()
	{
		$this->view->render('admin/cards/add', [], 'admin');
	}

	public function store(Request $request)
	{

		$request->validate([
			'description' => ['req'],
			'quantity' => ['req', 'min:1'],
		]);

		$this->helper->validateCSRF();

		for ($i = 0; $i < $request->all()['quantity']; $i++) {
			$this->db->table('cards')->insert(['uuid' => uuid(), 'description' => $request->all()['description'], 'status' => 0]);
		}

		redirect('/dashboard/cards?success=created');
	}

	public function edit($id)
	{
		$card = $this->db->where('id', $id)->first('cards');

		if (!$card)
			die('Invalid ID');

		$this->view->render('admin/cards/edit', [
			'card' => $card,
		], 'admin');
	}

	public function update($id, Request $request)
	{
		$request->validate([
			'description' => ['req'],
		]);
		$this->helper->validateCSRF();
		$card = $this->db->where('id', $id)->first('cards');
		if (!$card) {
			die("Card not found");
		}

		$card = $this->db->table('cards')->where('id', $id)->update($request->validated());
		if ($card) {
			redirect('/dashboard/cards');
		} else
			redirect('/dashboard/cards?error=not_updated');
	}

	public function downloadCsv()
	{
		$url = $_SERVER['HTTP_REFERER'];
		$explod = explode('/', $url);
		$new_url = $explod[0] . '//' . $explod[1] . $explod[2] . '/';

		$cards = $this->db->table('cards')->where('status', 0)->get();
		// Set the headers to force a download of the CSV file
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="data.csv"');

		// Open a file pointer to php://output
		$fp = fopen('php://output', 'w');

		fputcsv($fp, ['UUID', 'Status', 'URL']);
		// Loop through the data and write it to the file pointer
		foreach ($cards as $row) {
			$data = json_decode(json_encode($row), true);
			fputcsv($fp, [$data['uuid'], $data['status'] ? 'Active' : 'Inactive',  $new_url .'card_id/'. $data['uuid']]);
		}

		// Close the file pointer
		fclose($fp);
	}
}

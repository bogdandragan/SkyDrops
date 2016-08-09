<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use Mail;
use App\FileComment as FileComment;

class FileCommentController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$fc = FileComment::firstOrCreate(array(
			'user_id' => $_POST['user_id'],
			'file_id' => $_POST['file_id'],
			'text' => $_POST['text']
		));
		
		$aMails = json_decode($_POST['aEmails']);
		foreach ($aMails as &$sMail) {
			Mail::send('emails.mention', ['text' => $_POST['text'], 'link' => $_POST['link'] ], function($message) use ($sMail)
			{
				$message->from('skydrops@skypro.ch', 'SKyDrops');
				$message->subject(Auth::user()->firstname . ' ' . Auth::user()->lastname . ' mentioned you on a file');
				$message->to($sMail);
			});
		}

		$fileComments = FileComment::select('fileComments.id as fileID', 'fileComments.text', 'fileComments.created_at', 'firstname', 'lastname')
						->where('fileComments.id', '=', $fc->id)
						->leftJoin('users', 'users.id', '=', "fileComments.user_id")
						->first();

		return json_encode($fileComments);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Auth::check()){
			return "Test";
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}

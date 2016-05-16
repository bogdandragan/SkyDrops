<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Drop as Drop;
use App\File as File;
use Response;
use DB;
use Mail;
use Auth;

class DropController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//return Drop::all();
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
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$drop = Drop::select('*', 'drops.id as dropsid', 'drops.created_at as dropscreated_at')
				->where('hash', '=', $id)
				->leftJoin('users', 'users.id', '=', 'drops.user_id')
				->leftJoin(DB::raw('(SELECT drop_id, group_concat(tags.name) as tags FROM dropTags LEFT JOIN tags ON tags.id = dropTags.tag_id GROUP BY drop_id)postTags'), 'drops.id', '=', 'postTags.drop_id')
				->first();

		if(!$drop) abort(404);
		
		$files = File::where('drop_id', '=', $drop->dropsid)->get();
		
		return view('drop', array(
			'drop'		=> $drop,
			'files'		=> $files
		));
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
	public function destroy($hash_id)
	{
		$drop = Drop::where('hash', '=', $hash_id)->first();
		$files = File::where('drop_id', '=', $drop->id)->delete();
		$drop = $drop->delete();
		$response = "false";
		if($drop && $files){
			$response = "true";
		}
		
		return $response;
	}
	
	public function download($id)
	{
		$file= "/var/www/skydrops/storage/app/3/8/logo_black.png";
        $headers = array(
              'Content-Type: image/png',
            );
        return Response::download($file, 'logo_black.png', $headers);
	}
	
	public function share($hash)
	{

		//Mail
		Mail::send('emails.drop', ['drop_hash' => $hash, 'mailMessage' => (string)$_POST['message']], function($message)
		{
			$message->from('skydrops@skypro.ch', 'SKyDrops');
			$message->subject(Auth::user()->firstname . ' ' . Auth::user()->lastname . ' shared a Drop with you');
			$message->to(json_decode($_POST['contacts']));
		});
	}

}

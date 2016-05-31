<?php namespace App\Http\Controllers;

use App\DropStatistic;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\SharedUploads;
use Illuminate\Http\Request;
use App\Drop as Drop;
use App\File as File;
use Illuminate\Support\Facades\Config;
use League\Flysystem\Filesystem;
use Response;
use DB;
use Mail;
use Auth;
use ZipArchive;


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
		if(Auth::check()){
			$drop = Drop::select('*', 'drops.id as dropsid', 'drops.created_at as dropscreated_at')
				->where('hash', '=', $id)
				->leftJoin('users', 'users.id', '=', 'drops.user_id')
				->leftJoin(DB::raw('(SELECT drop_id, group_concat(tags.name) as tags FROM dropTags LEFT JOIN tags ON tags.id = dropTags.tag_id GROUP BY drop_id)postTags'), 'drops.id', '=', 'postTags.drop_id')
				->first();

			if(!$drop) abort(404);
				$files = File::where('drop_id', '=', $drop->dropsid)->get();

				$drop->totalSize = 0;
				foreach ($files as $file){
				$drop->totalSize += $file->size;
			}
			return view('drop', array(
				'drop'		=> $drop,
				'files'		=> $files
			));
		}
		else{
			return view('home');
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
		//$drop = Drop::where('hash', '=', $hash_id)->first();
	}

	public function updateValidity($hash_id)
	{
		$drop = Drop::where('hash', '=', $hash_id)->first();
		$drop->expires_at = $_POST['newDate'];
		$drop->save();

		return $drop->hash;
	}

	public function shareForUpload($hash_id)
	{
		$drop = Drop::where('hash', '=', $hash_id)->first();

		$sharedDrop = new SharedUploads;
		$sharedDrop->drop_id = $drop->id;
		$sharedDrop->save();

		return $sharedDrop->drop_id;
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
		$files = File::where('drop_id', '=', $drop->id);
		$drop = $drop->delete();

		foreach ($files->get() as $file){
			$filepath = Config::get('app.file_storage') . strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1)) .'/'. $file->hash . '.' . pathinfo($file->name, PATHINFO_EXTENSION);
			\Illuminate\Support\Facades\File::delete($filepath);
		}

		$files->delete();

		$response = "false";
		if($drop && $files){
			$response = "true";
		}
		
		return $response;
	}

	public function downloadZip($hash_id){
		if(!Auth::check()){
			abort('403');
		}

		$drop = Drop::where('hash', '=', $hash_id)->first();

		if(!$drop){
			abort('404');
		}

		$files = File::where('drop_id', '=', $drop->id)->get();

		$zip = new ZipArchive();
		$zipname = "";
		if($drop->title){
			$zipname = $drop->title . ".zip";
		}else{
			$zipname = $drop->hash . ".zip";
		}

		$res = $zip->open(storage_path() . '/app/'.$zipname, ZipArchive::CREATE);

		if($res === TRUE){
			foreach ($files as $file) {
				$filename = storage_path() .'/app/'. strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1)) .'/'. $file->hash . '.' . pathinfo($file->name, PATHINFO_EXTENSION);
				if(file_exists($filename))
				{
					$zip->addFile($filename,$file->name);

				}
			}

			$zip->close();
		}


		$headers = array(
			'Content-Type' => 'application/octet-stream',
		);

		//add drop statistic
		$dropStatistic = new DropStatistic;
		$dropStatistic->drop_id = $drop->id;
		$dropStatistic->userAgent = $_SERVER['HTTP_USER_AGENT'];
		$dropStatistic->ip = $_SERVER['REMOTE_ADDR'];
		$dropStatistic->save();

		return Response::download(storage_path() . '/app/'.$zipname, $zipname, $headers)->deleteFileAfterSend(true);

	}
	
	public function download($id)
	{
		$file= Config::get('app.file_storage')+"3/8/logo_black.png";
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

	public function sharedForUpload($hash_id){
		$drop = Drop::select('*', 'drops.id as dropsid', 'drops.created_at as dropscreated_at')
			->where('hash', '=', $hash_id)
			->leftJoin('users', 'users.id', '=', 'drops.user_id')
			->leftJoin(DB::raw('(SELECT drop_id, group_concat(tags.name) as tags FROM dropTags LEFT JOIN tags ON tags.id = dropTags.tag_id GROUP BY drop_id)postTags'), 'drops.id', '=', 'postTags.drop_id')
			->first();

		$shared = SharedUploads::where('drop_id', '=', $drop->id)->get();

		if(!$drop || !$shared) abort(404);
		$files = File::where('drop_id', '=', $drop->dropsid)->get();

		$drop->totalSize = 0;
		foreach ($files as $file){
			$drop->totalSize += $file->size;
		}
		return view('drop', array(
			'drop'		=> $drop,
			'files'		=> $files
		));
	}

}

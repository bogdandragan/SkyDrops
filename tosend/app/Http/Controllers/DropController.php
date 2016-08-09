<?php namespace App\Http\Controllers;

use App\DropStatistic;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\SharedDrops;
use App\SharedUploads;
use App\User;
use App\UsersCoins;
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
use Crypt;


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

		$isOwner = 0;
		if(Auth::check()){
			$user = User::where('id', '=', Auth::user()->id)->first();
			if($drop->user_id == $user->id){
				$isOwner = 1;
			}
		}

		$files = File::where('drop_id', '=', $drop->dropsid)->get();

		$drop->totalSize = 0;

		$sharedWith = SharedDrops::select('email')
			->where('drop_id', '=', $drop->dropsid)
			->groupby('email')
			->get();

		foreach ($files as $file){
			$drop->totalSize += $file->size;
		}

		return view('drop', array(
			'drop'		=> $drop,
			'files'		=> $files,
			'sharedWith' => $sharedWith,
			'owner' => $isOwner
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
		//$drop = Drop::where('hash', '=', $hash_id)->first();
	}

	public function updateValidity($hash_id)
	{
		$user = User::where('id', '=', Auth::user()->id)->first();

		if(!empty($_POST['diffDays'])){

			$diffDays = $_POST['diffDays'];

			if($diffDays <= 30){
				$totalCost = 0;
			}
			else{
				$totalCost = 1;
			}

			if($user->coins - $totalCost < 0){
				return (new \Illuminate\Http\Response)->setStatusCode(403, 'You dont have coins. Please buy more coins');
			}
		}

		$drop = Drop::where('hash', '=', $hash_id)->first();
		$drop->expires_at = $_POST['newDate'];
		$drop->save();

		if(!UserController::checkSKyUser($user)){
			$userCoins = new UsersCoins;
			$userCoins->user_id = $user->id;
			$userCoins->drop_id = $drop->id;
			$userCoins->amount = $totalCost;
			$userCoins->save();

			$user->coins -= $totalCost;
			$user->save();
		}

		return $drop->hash;
	}

	public function updateTitle($hash_id)
	{
		$drop = Drop::where('hash', '=', $hash_id)->first();
		$drop->title = $_POST['newTitle'];
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
		$response = "false";
		if(!$drop){
			return $response;
		}

		$files = File::where('drop_id', '=', $drop->id);
		$drop = $drop->delete();

		foreach ($files->get() as $file){
			$filepath = Config::get('filesystems.disks.local.root') . strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1)) .'/'. $file->hash . '.' . pathinfo($file->name, PATHINFO_EXTENSION);
			\Illuminate\Support\Facades\File::delete($filepath);
		}

		$files->delete();

		if($drop && $files){
			$response = "true";
		}
		
		return $response;
	}

	public static function sendFile($path, $contentType = 'application/octet-stream')
	{
		ignore_user_abort(true);

		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' .
			basename($path) . "\";");
		header("Content-Type: $contentType");

		$res = array(
			'status' => true,
			'errors' =>array(),
			'readfileStatus' =>null,
			'aborted' =>false
		);

		$res['readfileStatus'] = readfile($path);
		if ($res['readfileStatus'] === false) {
			$res['errors'][] = 'readfile failed.';
			$res['status'] = false;
		}

		if (connection_aborted()) {
			$res['errors'][] = 'Connection aborted.';
			$res['aborted'] = true;
			$res['status'] = false;
		}

		return $res;
	}

	public function downloadZip($hash_id){
		$drop = Drop::where('hash', '=', $hash_id)->first();

		if(!$drop || !$drop->wasSaved){
			abort('404');
		}

		$files = File::where('drop_id', '=', $drop->id)->get();

		$zip = new ZipArchive();
		$zipname = "";
		if($drop->title){
			$zipname = $drop->title.$drop->hash. ".zip";
		}else{
			$zipname = $drop->hash . ".zip";
		}

		$res = $zip->open(\Config::get('filesystems.disks.local.root').$zipname, ZipArchive::CREATE);

		if($res === TRUE){
			foreach ($files as $file) {
				$filename = \Config::get('filesystems.disks.local.root'). strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1)) .'/'. $file->hash . '.' . pathinfo($file->name, PATHINFO_EXTENSION);
				if(file_exists($filename))
				{
					$decryptedFileName = \Config::get('filesystems.disks.local.root'). strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1)) .'/'.'d'.$file->hash . '.' . pathinfo($file->name, PATHINFO_EXTENSION);

					$decryptedContent = Crypt::decrypt(\Illuminate\Support\Facades\File::get($filename));
					\Illuminate\Support\Facades\File::put($decryptedFileName, $decryptedContent);

					$zip->addFile($decryptedFileName,$file->name);

				}
			}

			$zip->close();

			foreach ($files as $file){
				$decryptedFileName = \Config::get('filesystems.disks.local.root'). strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1)) .'/'.'d'.$file->hash . '.' . pathinfo($file->name, PATHINFO_EXTENSION);
				\Illuminate\Support\Facades\File::delete($decryptedFileName);
			}
		}


		$headers = array(
			'Content-Type' => 'application/octet-stream',
		);

		$res = $this->sendFile(\Config::get('filesystems.disks.local.root').$zipname, 'application/octet-stream');

		if ($res['status']) {
			if(!Auth::check() || $drop->user_id != Auth::user()->id){
				$drop->wasDownloaded = true;
				$drop->save();
			}
			//add drop statistic
			$dropStatistic = new DropStatistic;
			$dropStatistic->drop_id = $drop->id;
			$dropStatistic->userAgent = $_SERVER['HTTP_USER_AGENT'];
			$dropStatistic->ip = $_SERVER['REMOTE_ADDR'];
			$dropStatistic->save();
			\Illuminate\Support\Facades\File::delete(\Config::get('filesystems.disks.local.root').$zipname);
		} else {
			\Illuminate\Support\Facades\File::delete(\Config::get('filesystems.disks.local.root').$zipname);
		}
		//return Response::download(storage_path() . '/app/'.$zipname, $zipname, $headers)->deleteFileAfterSend(true)->withCookie(cookie('name', 'value'));

	}
	
	public function download($id)
	{
		$file= Config::get('filesystems.disks.local.root')+"3/8/logo_black.png";
        $headers = array(
              'Content-Type: image/png',
            );
        return Response::download($file, 'logo_black.png', $headers);
	}
	
	public function share($hash)
	{
		$drop = Drop::where('hash', '=', $hash)->first();

		foreach (explode(",",$_POST['contacts']) as $contact){
			$shared = new SharedDrops;
			$shared->drop_id = $drop->id;
			$email = trim($contact, "[");
			$email = trim($email, "]");
			$email = trim($email, '"');
			$shared->email = $email;
			$shared->message = $_POST['message'];
			$shared->save();
		}

		//Mail
		Mail::send('emails.drop', ['drop_hash' => $hash, 'mailMessage' => (string)$_POST['message']], function($message)
		{
			$message->from('skydrop@skypro.eu', 'SKyDrops');
			$message->subject(Auth::user()->firstname . ' ' . Auth::user()->lastname . ' shared a File Exchange with you');
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

		$isOwner = 0;
		if(Auth::check()){
			$user = User::where('id', '=', Auth::user()->id)->first();
			if($drop->user_id == $user->id){
				$isOwner = 1;
			}
		}

		$sharedWith = SharedDrops::select('email')
			->where('drop_id', '=', $drop->dropsid)
			->groupby('email')
			->get();

		return view('drop', array(
			'drop'		=> $drop,
			'files'		=> $files,
			'sharedWith' => $sharedWith,
			'owner' => $isOwner
		));
	}

}

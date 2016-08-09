<?php namespace App\Http\Controllers;

use App\Drop;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\File as File;
use App\FileComment as FileComment;
use App\FileStatistic as FileStatistic;
use Illuminate\Support\Facades\Storage;
use Response;
use Auth;
use Crypt;

class FileController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//return File::all();
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
		$file = File::where('hash', '=', $id)->first();
		$filePath = \Config::get('filesystems.disks.local.root') . strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1));
		preg_match('/\.[^\.]+$/i',$file->name,$fileName);
		$fileName = $file->hash . $fileName[0];

		header('Content-Description: File Transfer');
		header('Content-Type: ' . $file->content_type);
		header('Content-Disposition: attachment; filename='.$file->name);
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . $file->size);

		if(true || substr($file->content_type, 0, 5) == "image"){

			$decryptedContent = Crypt::decrypt(\Illuminate\Support\Facades\File::get($filePath . "/" . $fileName));
			\Illuminate\Support\Facades\File::put($filePath . "/" . 'd'.$fileName, $decryptedContent);

			$downloadRes = DropController::sendFile($filePath . "/" . 'd'.$fileName, $file->content_type);

			if ($downloadRes['status']) {
				\Illuminate\Support\Facades\File::delete($filePath . "/" . 'd'.$fileName);
			} else {
				\Illuminate\Support\Facades\File::delete($filePath . "/" . 'd'.$fileName);
			}

			//return readfile($filePath . "/" . $fileName);
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
	public function destroy($hash_id)
	{
		$file = File::where('hash', '=', $hash_id)->first();

		if($hash_id != 'undefined' && $hash_id != null){
			$filepath = \Config::get('filesystems.disks.local.root') . strtolower(substr($hash_id, 0, 1)) . '/' . strtolower (substr($hash_id, 1, 1)) .'/'. $file->hash . '.' . pathinfo($file->name, PATHINFO_EXTENSION);
		}

		if($file){
			$file = $file->delete();
			\Illuminate\Support\Facades\File::delete($filepath);
		}

		$response = "false";
		if($file){
			$response = "true";
		}

		return $response;
	}
	
	public function download($id)
	{
		$file = File::where('hash', '=', $id)->first();
		$drop = Drop::where('id', '=', $file->drop_id)->first();

		if(!$drop || !$drop->wasSaved){
			abort('404');
		}

		$filePath =  \Config::get('filesystems.disks.local.root') . strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1));
		preg_match('/\.[^\.]+$/i',$file->name,$fileName);
		$fileName = $file->hash . $fileName[0];

        $headers = array(
              'Content-Type: ' . $file->content_type,
            );

		$decryptedContent = Crypt::decrypt(\Illuminate\Support\Facades\File::get($filePath . "/" . $fileName));
		\Illuminate\Support\Facades\File::put($filePath . "/" . 'd'.$fileName, $decryptedContent);

		$downloadRes = DropController::sendFile($filePath . "/" . 'd'.$fileName, $file->content_type);

		if ($downloadRes['status']) {
			if(!Auth::check() || $drop->user_id != Auth::user()->id){
				$drop->wasDownloaded = true;
				$drop->save();
			}
			//add file statistic
			$fileStatistic = new FileStatistic;
			$fileStatistic->file_id = $file->id;
			$fileStatistic->userAgent = $_SERVER['HTTP_USER_AGENT'];
			$fileStatistic->ip = $_SERVER['REMOTE_ADDR'];
			$fileStatistic->save();
			\Illuminate\Support\Facades\File::delete($filePath . "/" . 'd'.$fileName);
		} else {
			\Illuminate\Support\Facades\File::delete($filePath . "/" . 'd'.$fileName);
		}

		//return Response::download($filePath . "/" . $fileName, $file->name, $headers);
	}

	public function getComments($id) {
		$fileComments = FileComment::where('file_id', '=', $id)
					->leftJoin('users', 'users.id', '=', 'fileComments.user_id')
					->get();
		
		return json_encode($fileComments);
	}

}

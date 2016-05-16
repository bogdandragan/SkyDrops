<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\File as File;
use App\FileComment as FileComment;
use Response;
use Auth;

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
                $filePath = storage_path() . "/app/" . strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1));
                preg_match('/\.[^\.]+$/i',$file->name,$fileName);
                $fileName = $file->hash . $fileName[0];

		header('Content-Description: File Transfer');
		header('Content-Type: ' . $file->content_type);
		header('Content-Disposition: attachment; filename='.$file->name);
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . $file->size);
		if(true || substr($file->content_type, 0, 5) == "image") return readfile($filePath . "/" . $fileName);
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
	
	public function download($id)
	{
		$file = File::where('hash', '=', $id)->first();
		$filePath = storage_path() . "/app/" . strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1));
		preg_match('/\.[^\.]+$/i',$file->name,$fileName);
		$fileName = $file->hash . $fileName[0];

        $headers = array(
              'Content-Type: ' . $file->content_type,
            );
		return Response::download($filePath . "/" . $fileName, $file->name, $headers);
	}

	public function getComments($id) {
		$fileComments = FileComment::where('file_id', '=', $id)
					->leftJoin('users', 'users.id', '=', 'fileComments.user_id')
					->get();
		
		return json_encode($fileComments);
	}

}
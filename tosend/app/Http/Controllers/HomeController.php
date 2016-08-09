<?php namespace App\Http\Controllers;

use App\User;
use Auth;
use Mail;
use DB;
use App\Drop as Drop;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('home');
	}

	public function home(){
		if(Auth::check()){

			$drops = Drop::where('user_id', '=', Auth::user()->id)->where('wasSaved','=','1')
				->leftJoin(DB::raw('(SELECT drop_id, SUM(files.size) as dropSize, group_concat(files.name) as dropFiles, group_concat(files.hash) as dropFilesHash, group_concat(files.content_type) as dropFilesContenttype FROM files WHERE isTmp <> \'1\' OR isTmp IS NULL  GROUP BY drop_id)files'), 'drops.id', '=', 'files.drop_id')
				->leftJoin(DB::raw('(SELECT drop_id, group_concat(tags.name) as tags FROM dropTags LEFT JOIN tags ON tags.id = dropTags.tag_id GROUP BY drop_id)postTags'), 'drops.id', '=', 'postTags.drop_id')
				->orderBy('created_at', 'desc')
				->get();

			return view('home-loggedin', array(
				'drops'		=> $drops
			));
		} else{
			return redirect("/");
		}
	}

	public function upload(){
		if(Auth::check()){
			$user = User::where('id', '=', Auth::user()->id)->first();
			return view('upload', array(
				'coins'	=> $user->coins,
				'hash' => md5(uniqid(mt_rand(), true))
			));
		}
		else{
			return redirect("/");
		}
	}

	public function shop(){
		if(Auth::check()){
			return view('shop');
		}
		else{
			return redirect("/");
		}
	}

	public function contact()
	{
		//Mail
		Mail::send('emails.contact', [], function($message)
		{
			$message->from('skydrops@skypro.ch', 'SKyDrops');
			$message->subject("Thank you for contacting us");
			$message->to($_POST['contacts']);
		});

		Mail::send('emails.admin-contact', ['email' => $_POST['contacts']], function($message)
		{
			$message->from('skydrops@skypro.ch', 'SKyDrops');
			$message->subject("[ADMIN] Contact form");
			$message->to("dario@skypro.ch");
		});
	}

}

<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\UsersCoins;
use Illuminate\Http\Request;
use Input;
use App\Drop as Drop;
use App\File as File;
use App\DropTag as DropTag;
use App\Tag as Tag;
use App\User as User;
use App\Group as Group;
use App\UserGroup as UserGroup;
use DB;
use Mail;
use Auth;
use Hash;
use Redirect;
use Log;
use Config;
use Crypt;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		/*if(Auth::check()){
			return User::get();
		}*/
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
		$user = User::find($id);
		
		return view('user', array(
			'user'		=> $user
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
	public function destroy($id)
	{
		//
	}
	
	//Custom
	
	public function upload(Request $request)
	{
		$user = User::where('id', '=', Auth::user()->id)->first();
		$drop = Drop::where('hash', '=', $_POST['hash'])->first();

		if(!empty($_POST['totalSize']) && !empty($_POST['validity'])){
			$totalCost = $this->getCost($_POST['totalSize'], $_POST['validity']);
			if(!$this->checkSKyUser($user) && $user->coins - $totalCost[0] < 0){
				//return $drop->hash;
				return (new \Illuminate\Http\Response)->setStatusCode(422, 'You dont have coins. Please buy more coins');
			}
		}

		//Create Drop or add to existed
		if(!$drop){
			$drop = new Drop;
			$drop->hash = $_POST['hash'];
		}

		if(!empty($_POST['title']))
			$drop->title = $_POST['title'];
		else
			$drop->title = "New drop";
		$drop->user_id = Auth::user()->id;
		if(!empty($_POST['expires_at'])) $drop->expires_at = $_POST['expires_at'];
		$drop->save();
		
		//Create DropTag
		foreach(json_decode($_POST['tags']) as $tagName){
			$tag = Tag::firstOrCreate(array('name' => $tagName));
			$dt = DropTag::firstOrCreate(array('drop_id' => $drop->id, 'tag_id' => $tag->id));
		}
		
		//Create File
		if($request->hasFile('file')){
			foreach($request->file('file') as $file){
				$originalFilename = $file->getClientOriginalName();
				$originalFilename = str_replace(";","",$originalFilename);
				$originalFilename = str_replace(",","",$originalFilename);
				$originalFilename = str_replace(" ","",$originalFilename);

				$hash = md5(uniqid(mt_rand(), true));
				//setcookie($file->getClientOriginalName(), $hash, time()+60*60*2);
				\Cookie::queue(\Cookie::make('unsigned::'.$originalFilename, $hash, 120, null, null, false, false));

				//Copy in storage
				$extension = $file->getClientOriginalExtension();
				$filename = $hash . "." .  $extension;
				
				$filepath = Config::get('filesystems.disks.local.root') . strtolower(substr($hash, 0, 1)) . '/' . strtolower (substr($hash, 1, 1));
				
				//Create file in DB
				$dbFile = new File;
				$dbFile->drop_id = $drop->id;
				$dbFile->hash = $hash;
				$dbFile->name = $originalFilename;
				$dbFile->size = $file->getSize();
				$dbFile->content_type = $file->getMimeType();
				$dbFile->save();

				$file->move($filepath, $filename);

				$encryptedContent = Crypt::encrypt(\Illuminate\Support\Facades\File::get($filepath.'/'.$filename));
				\Illuminate\Support\Facades\File::put($filepath.'/'.$filename, $encryptedContent);
			}
		}

		return $drop->hash;
	}

	public function saveUpload(Request $request)
	{
		$user = User::where('id', '=', Auth::user()->id)->first();
		$drop = Drop::where('hash', '=', $_POST['hash'])->first();

		if(!empty($_POST['validity'])){
			$totalCost = $this->getCost($_POST['totalSize'], $_POST['validity']);
			if(!$this->checkSKyUser($user) && $user->coins - $totalCost[0] < 0){
				return (new \Illuminate\Http\Response)->setStatusCode(403, 'You dont have coins. Please buy more coins');
			}
		}

		if(!empty($_POST['title']))
			$drop->title = $_POST['title'];
		else
			$drop->title = "New drop";
		$drop->user_id = Auth::user()->id;
		if(!empty($_POST['expires_at'])) $drop->expires_at = $_POST['expires_at'];
		$drop->wasSaved = true;
		$drop->save();

		if(!$this->checkSKyUser($user)){
			Mail::send('emails.drop', ['drop_hash' => $drop->hash, 'mailMessage' => (string)$_POST['message']], function($message)
			{
				$message->from('skydrop@skypro.eu', 'SKyDrops');
				$message->subject(Auth::user()->firstname . " " . Auth::user()->lastname . ' shared a Drop with you');
				$message->to(json_decode($_POST['contacts']));
			});

			$userCoins = new UsersCoins;
			$userCoins->user_id = $user->id;
			$userCoins->drop_id = $drop->id;
			$userCoins->amount = $totalCost[0];
			$userCoins->save();

			$user->coins-=$totalCost[0];
			$user->save();
		}

		return $drop->hash;
	}

	public function uploadEmpty(Request $request)
	{
		$hash = md5(uniqid(mt_rand(), true));
		$user = User::where('id', '=', Auth::user()->id)->first();

		$totalCost = $this->getCost($_POST['totalSize'], $_POST['validity']);

		if(!$this->checkSKyUser($user) && $user->coins - $totalCost[0] < 0){
			return (new \Illuminate\Http\Response)->setStatusCode(403, 'You dont have coins. Please buy more coins');
		}

		//Create Drop
		$drop = new Drop;
		$drop->hash = $hash;
		$drop->forUpload = true;
		$drop->wasSaved = true;
		$drop->sizeLimit = $_POST['totalSize'];
		if(!empty($_POST['title']))
			$drop->title = $_POST['title'];
		else
			$drop->title = "NewFE";
		$drop->user_id = Auth::user()->id;
		if(!empty($_POST['expires_at'])) $drop->expires_at = $_POST['expires_at'];
		$drop->save();

		//Create DropTag
		foreach(json_decode($_POST['tags']) as $tagName){
			$tag = Tag::firstOrCreate(array('name' => $tagName));
			$dt = DropTag::firstOrCreate(array('drop_id' => $drop->id, 'tag_id' => $tag->id));
		}

		if(!$this->checkSKyUser($user)){
			$user->coins -= $totalCost[0];
			$user->save();

			$userCoins = new UsersCoins;
			$userCoins->user_id = $user->id;
			$userCoins->drop_id = $drop->id;
			$userCoins->amount = $totalCost[0];
			$userCoins->save();
		}

		return $drop->hash;
	}

	public static function checkSKyUser($user){
		$domains = explode("@", $user->email);

		$domain = end($domains);

		$userGroup = UserGroup::where('user_id', '=', $user->id)->first();

		if($userGroup || $domain == "skypro.ch"){
			return true;
		}
		else{
			return false;
		}
	}

	public function getCost($size, $validity){

		$user = User::where('id', '=', Auth::user()->id)->first();

		$totalCost = 1;

		if($validity > 30){
			$totalCost += 1;
		}
		if($size <= 1024*1024*1024){
			$totalCost += 0;
		}
		if($size > 1024*1024*1024 && $size <= 1024*1024*1024*5){
			$totalCost += 1;
		}
		if($size > 1024*1024*1024*5 && $size <= 1024*1024*1024*10){
			$totalCost += 2;
		}

		if($user->coins - $totalCost >= 0){
			return array($totalCost, 1);
		}else{
			return array($totalCost, 0);
		}
	}

	public function getFECost(Request $request){

		$user = User::where('id', '=', Auth::user()->id)->first();

		$totalCost = 1;
		$validity = $_POST['validity'];
		$size = $_POST['size'];

		if($validity > 30){
			$totalCost += 1;
		}
		if($size <= 1024*1024*1024){
			$totalCost += 0;
		}
		if($size > 1024*1024*1024 && $size <= 1024*1024*1024*5){
			$totalCost += 1;
		}
		if($size > 1024*1024*1024*5 && $size <= 1024*1024*1024*10){
			$totalCost += 2;
		}

		if($user->coins - $totalCost >= 0){
			return array($totalCost, 1);
		}else{
			return array($totalCost, 0);
		}
	}

	public function addFile(Request $request)
	{
		if($_POST['dropHash']){
			$dropHash = $_POST['dropHash'];
			$drop = Drop::where('hash', '=', $dropHash)->first();
			$drop_id = $drop->id;

			if($drop->wasDownloaded && !$drop->forUpload){
				abort('404');
			}


			//Create File
			if($request->hasFile('file')){
				
				//check if drop for upload and then check size limit
				if($drop->forUpload){
					$existedFiles = File::where('drop_id', '=', $drop_id)->sum('size');

					foreach($request->file('file') as $file){
						$existedFiles += $file->getSize();
						if($existedFiles > $drop->sizeLimit){
							return (new \Illuminate\Http\Response)->setStatusCode(403, 'File exchange size limit error');
						}
					}
				}

				foreach($request->file('file') as $file){
					$hash = md5(uniqid(mt_rand(), true));

					$originalFilename = $file->getClientOriginalName();
					$originalFilename = str_replace(";","",$originalFilename);
					$originalFilename = str_replace(",","",$originalFilename);
					$originalFilename = str_replace(" ","",$originalFilename);

					\Cookie::queue(\Cookie::make('unsigned::'.$originalFilename, $hash, 120, null, null, false, false));

					//Copy in storage
					$extension = $file->getClientOriginalExtension();
					$filename = $hash . "." .  $extension;

					$filepath = Config::get('filesystems.disks.local.root') . strtolower(substr($hash, 0, 1)) . '/' . strtolower (substr($hash, 1, 1));

					//Create file in DB
					$dbFile = new File;
					$dbFile->drop_id = $drop_id;
					$dbFile->hash = $hash;
					$dbFile->name = $originalFilename;
					$dbFile->size = $file->getSize();
					$dbFile->content_type = $file->getMimeType();
					$dbFile->isTmp = true;
					$dbFile->save();

					$file->move($filepath, $filename);

					$encryptedContent = Crypt::encrypt(\Illuminate\Support\Facades\File::get($filepath.'/'.$filename));
					\Illuminate\Support\Facades\File::put($filepath.'/'.$filename, $encryptedContent);
				}
			}
			$files = File::where('drop_id', '=', $drop_id)->get();

		}

		$drop->totalSize = 0;
		foreach ($files as $file){
			$drop->totalSize += $file->size;
		}

		return array(
			'drop'		=> $drop,
			'files'		=> $files
		);
	}

	public function saveAddFile(Request $request)
	{
		$response = "false";
		foreach ($_POST['files'] as $file){
			$fileDb = File::where('hash', '=', $file)->first();

			if($fileDb){
				$fileDb->isTmp = false;
				$fileDb->save();
				$response = "true";
			}
		}

		return $response;
	}

	public function deleteTmpAddFile(Request $request)
	{
		$response = "false";
		foreach ($_POST['files'] as $file){
			$fileDb = File::where('hash', '=', $file)->first();

			if($file != 'undefined' && $file != null){
				$filepath = \Config::get('filesystems.disks.local.root') . strtolower(substr($file, 0, 1)) . '/' . strtolower (substr($file, 1, 1)) .'/'. $fileDb->hash . '.' . pathinfo($fileDb->name, PATHINFO_EXTENSION);
			}

			if($fileDb){
				$fileDb->delete();
				\Illuminate\Support\Facades\File::delete($filepath);
				$response = "true";
			}
		}

		return $response;
	}
	
	
	public function ldap(Request $request)
	{
		$username = $request->username;
		$password = $request->password;
		
		$bind = self::authenticate($username, $password);
		
		if($bind){
			$aGroups = self::getSKYD($bind[0]['groupmembership']);
			//dd($aGroups);
			//return "test";
			//Check if in Group
			if ($aGroups) {
				
				//Create or get user
				$user = User::firstOrNew(array('email' => strtolower($bind[0]['mail'][0])));
				$user->username = $bind[0]['cn'][0];
				$user->firstname = $bind[0]['givenname'][0];
				$user->lastname = $bind[0]['sn'][0];
				$user->save();
				
				//Delete all UserGroups
				UserGroup::where('user_id', '=', $user->id)->delete();
				
				foreach($aGroups as $sGroup){
					$group = Group::where('name', '=', $sGroup)->first();
					if($group){
						//Set User to Group
						$userGroup = UserGroup::create(array('user_id' => $user->id, 'group_id' => $group->id));
					} else{
						return $sGroup . " does not exist.";
					}
				}
				
				//Log in user
				Auth::login( $user );
				
				return $user;
			
			} else{
				return (new \Illuminate\Http\Response)->setStatusCode(401, "Your user need to be in a <strong>"+\Config::get('app.sky_group_name')+"-*+</strong> group.");
			}
		} else{

			if (!Auth::attempt(array(
				'username'     => $username,
				'password'  => $password
			))) {
				return (new \Illuminate\Http\Response)->setStatusCode(401, 'Username or password do not match');
			}

			$user = User::where('username', '=', $username)->first();

			/*if(!$user){
				return (new \Illuminate\Http\Response)->setStatusCode(401, 'Username or password do not match');
			}*/

			$userGroup = UserGroup::where('user_id', '=', $user->id)->first();

			if($userGroup || $user->verification_code != ""){
				return (new \Illuminate\Http\Response)->setStatusCode(401, 'Username or password do not match');
			}

			Auth::login($user);

			return $user;
		}
	}
	
	private static function authenticate($username, $password)
	{
		if(empty($username) or empty($password))
		{
			Log::error('Error binding to LDAP: username or password empty');
			return false;
		}
		$ldapRdn = self::getLdapRdn($username);

		$ldapconn = ldap_connect( Config::get('auth.ldap_server') ) or die("Could not connect to LDAP server.");

		$result = false;
		
		if ($ldapconn)
		{
			//for local environment
			$ldapbind = ldap_bind($ldapconn, "cn=admin,ou=users,o=system", "WEto655NiXbi7w") or die ("Error trying to bind: ".ldap_error($ldapconn));
						
			$aContext = explode("|", Config::get('auth.ldap_tree'));
		
			foreach ($aContext as &$sContext) {
				if(!$result){
					//Get result
					$sr = ldap_search($ldapconn, $sContext, "cn=".$username);
					Log::error("cn=".$username.",".$sContext);
					//Get Email if defined(else empty)
					$entries = ldap_get_entries($ldapconn, $sr);
					//Log::error("Entries: ". implode(" # ", $entries));
					if($entries["count"] > 0){
						$result = $entries;
						Log::error($result);
					}
				}
			}
			
			if($result){
				$ldapbind = @ldap_bind($ldapconn, $result[0]['dn'], $password);

				if (!$ldapbind)
				{
					$result = false;
					Log::error('Error binding to LDAP server.');
				}
			} else{
				Log::error('User not found.');
			}
			
			ldap_unbind($ldapconn);

		} else {
			Log::error('Error connecting to LDAP.');
		}

		return $result;

	}

	private static function getLdapRdn($username)
	{
		return str_replace('[username]', $username, 'CN=[username],' . Config::get('auth.ldap_tree'));
	}
	
	private static function getSKYD($aGroups){
		$nGroups = array();
		foreach($aGroups as $sGroup){
			Log::error($sGroup);
			preg_match("/cn=([^,]+)/", $sGroup, $treffer);
			if($treffer){
				if(substr($treffer[1],0,4) == \Config::get('app.sky_group_name'))
				{
					array_push($nGroups, $treffer[1]);
				}
			}
		}	
		return $nGroups;
	}

	public function getContacts() {
		if(Auth::check()){
			$new_array = array();
			$userGroup = UserGroup::where('user_id', '=', Auth::user()->id)->first();
			if($userGroup){
				$ldapconn = ldap_connect( Config::get('auth.ldap_server') ) or die("Could not connect to LDAP server.");

				$result = false;

				if ($ldapconn)
				{
					$aContext = explode("|", Config::get('auth.ldap_tree'));
					foreach ($aContext as &$sContext) {
						if(!$result){
							//Get result
							$sr = ldap_search($ldapconn, $sContext, "(objectClass=Person)", array("givenname", "sn", "mail"));
							ldap_sort ( $ldapconn, $sr, 'givenname' ) ;

							//Get Email if defined(else empty)
							$entries = ldap_get_entries($ldapconn, $sr);
							if($entries["count"] > 0){
								for($i=0;$i<$entries["count"];$i++){
									if(isset($entries[$i]["givenname"]) && isset($entries[$i]["sn"]) && isset($entries[$i]["mail"])) {
										$o = new \stdClass();
										$o->name = $entries[$i]['givenname'][0] . " " . $entries[$i]['sn'][0];
										$o->email = $entries[$i]['mail'][0];
										array_push($new_array, $o);
									}
								}
							}
						}
					}

				} else {
					Log::error('Error connecting to LDAP.');
				}
				return json_encode($new_array);
			}
			else{
				return json_encode($new_array);
			}
		}
	}
	
	public function profile()
	{
		if(Auth::check()){
			$drops = Drop::where('user_id', '=', Auth::user()->id)
						->leftJoin(DB::raw('(SELECT drop_id, group_concat(files.name) as dropFiles FROM files GROUP BY drop_id)files'), 'drops.id', '=', 'files.drop_id')
						->leftJoin(DB::raw('(SELECT drop_id, group_concat(tags.name) as tags FROM dropTags LEFT JOIN tags ON tags.id = dropTags.tag_id GROUP BY drop_id)postTags'), 'drops.id', '=', 'postTags.drop_id')
						->get();
			
			return view('profile', array(
				'drops'		=> $drops
			));
		} else{
			return redirect('/');
		}
		
	}
	
	public function groups()
	{
		if(Auth::check()){
			$groups = UserGroup::select('*', 'groups.id as groupsid')
						->where('user_id', '=', Auth::user()->id)
						->leftJoin('groups', 'groups.id', '=', 'userGroups.group_id')
						->get();
			
			return view('group', array(
				'groups'		=> $groups
			));
		} else{
			return redirect('/');
		}
		
	}

	public function adminDashboard()
	{
		$users = User::select('*')->get();

		return view('dashboard', array('users' => $users));

	}

	public function adminStatistic()
	{
		$users = User::select('*')->get();
		return view('statistic');
	}

	public function restorePassword(Request $request)
	{
		$email = $request->email;

		$user = User::where('email', '=', $email)->first();

		if(!$user){
			return (new \Illuminate\Http\Response)->setStatusCode(401, 'Email not found');
		}

		$userGroup = UserGroup::where('user_id', '=', $user->id)->first();

		if($userGroup){
			return (new \Illuminate\Http\Response)->setStatusCode(422, 'Redirect to http://reset.skypro.ch/');
		}

		$restoreCode = str_random(30);
		$user->restore_code = $restoreCode;
		$user->save();

		\Mail::send('emails.password', ['code' => $restoreCode, 'username' => $user->username], function($message) use($email)
		{
			$message->from('skydrop@skypro.eu', 'SKyDrops');
			$message->subject('SKyDrops restore password');
			$message->to($email);
		});

		return $user;
	}

	public function restorePasswordConfirm($code){
		$user = User::where('restore_code', '=', $code)->first();

		if(!$user)
			abort('404');

		return view('newPassword', array('code' => $code));

	}

	public function setNewPassword(Request $request){
		$validator = \Validator::make($request->all(), [
			'password' => 'required|confirmed|min:6',
		]);

		if ($validator->fails())
		{
			//return $validator->errors()->toArray();
			$this->throwValidationException(
				$request, $validator
			);
		}

		$user = User::where('restore_code', '=', $request['code'])->first();

		if(!$user){
			return (new \Illuminate\Http\Response)->setStatusCode(403, 'Restore code is not valid');
		}

		$user->restore_code = "";
		$user->password = bcrypt($request['password']);
		$user->save();

		Auth::login($user);

		return $user;
	}

	public function getAvailableCoins(){
		if(Auth::check()){
			$user = User::where('id', '=', Auth::user()->id)->first();
		}
		else{
			abort('403');
		}

		$availableCoins = $user->coins;

		return array($availableCoins);
	}

	public function coinStatistics(){
		if(Auth::check()){
			$coins = UsersCoins::where('usersCoins.user_id', '=', Auth::user()->id)
				->leftJoin('drops', 'usersCoins.drop_id', '=', 'drops.id')
				->select('usersCoins.*', 'drops.hash')
				->orderBy('created_at', 'desc')->get();

			return view('coinStatistics', array('coins' => $coins));
		}
		else{
			return view('home');
		}
	}

}

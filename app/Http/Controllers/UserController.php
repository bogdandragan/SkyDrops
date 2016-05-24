<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
		$hash = md5(uniqid(mt_rand(), true));
		
		//Create Drop
		$drop = new Drop;
		$drop->hash = $hash;
		if($_POST['title'])
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
				$hash = md5(uniqid(mt_rand(), true));
			
				//Copy in storage
				$extension = $file->getClientOriginalExtension();
				$filename = $hash . "." .  $extension;
				
				$filepath = Config::get('app.file_storage') . strtolower(substr($hash, 0, 1)) . '/' . strtolower (substr($hash, 1, 1));
				
				//Create file in DB
				$dbFile = new File;
				$dbFile->drop_id = $drop->id;
				$dbFile->hash = $hash;
				$dbFile->name = $file->getClientOriginalName();
				$dbFile->size = $file->getSize();
				$dbFile->content_type = $file->getMimeType();
				$dbFile->save();
				
				$file->move($filepath, $filename);
				
			}
		}
		
		//Mail
		Mail::send('emails.drop', ['drop_hash' => $drop->hash, 'mailMessage' => (string)$_POST['message']], function($message)
		{
			$message->from('skydrops@skypro.ch', 'SKyDrops');
			$message->subject(Auth::user()->firstname . " " . Auth::user()->lastname . ' shared a Drop with you');
			$message->to(json_decode($_POST['contacts']));
		});
		
		return $drop->hash;
	}

	public function addFile(Request $request)
	{
		if($_POST['dropHash']){
			$dropHash = $_POST['dropHash'];
			$drop = Drop::where('hash', '=', $dropHash)->first();
			$drop_id = $drop->id;

			//Create File
			if($request->hasFile('file')){
				foreach($request->file('file') as $file){
					$hash = md5(uniqid(mt_rand(), true));

					//Copy in storage
					$extension = $file->getClientOriginalExtension();
					$filename = $hash . "." .  $extension;

					$filepath = Config::get('app.file_storage') . strtolower(substr($hash, 0, 1)) . '/' . strtolower (substr($hash, 1, 1));

					//Create file in DB
					$dbFile = new File;
					$dbFile->drop_id = $drop_id;
					$dbFile->hash = $hash;
					$dbFile->name = $file->getClientOriginalName();
					$dbFile->size = $file->getSize();
					$dbFile->content_type = $file->getMimeType();
					$dbFile->save();

					$file->move($filepath, $filename);
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
				
				return Redirect::intended('/');
			
			} else{
				return "Your user need to be in a <strong>"+\Config::get('app.sky_group_name')+"-*+</strong> group.";
			}
		} else{
			return "Authentication failed";
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
			echo "<pre>";
			print_r($new_array);
			echo "</pre>";



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
			return redirect('login');
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
			return redirect('login');
		}
		
	}



}

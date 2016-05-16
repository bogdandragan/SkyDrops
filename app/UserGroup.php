<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model {

	protected $table = 'userGroups';
	protected $fillable = array('user_id' , 'group_id');

}

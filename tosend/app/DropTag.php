<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DropTag extends Model {

	protected $table = 'dropTags';
	protected $fillable = array('drop_id' , 'tag_id');

}

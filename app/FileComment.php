<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class FileComment extends Model {

	protected $table = 'fileComments';

	protected $fillable = ['user_id', 'file_id', 'text'];

}

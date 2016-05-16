<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Drop extends Model {

	//
	public function clear()
	{
		$mytime = Carbon::now();
		$drops = Drop::where('expires_at', '<', $mytime)->delete();
		return "Cleared expired Drops";
	}

}

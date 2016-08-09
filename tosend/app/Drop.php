<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Drop extends Model {

	//
	public function clear()
	{
		$mytime = Carbon::now();



		$drops = Drop::where('expires_at', '<', $mytime);

		foreach ($drops->get() as $drop){
			$files = File::where('drop_id', '=', $drop->id);
			foreach ($files->get() as $file){
				$filepath = \Config::get('filesystems.disks.local.root') . strtolower(substr($file->hash, 0, 1)) . '/' . strtolower (substr($file->hash, 1, 1)) .'/'. $file->hash . '.' . pathinfo($file->name, PATHINFO_EXTENSION);
				\Illuminate\Support\Facades\File::delete($filepath);
			}
			$drop->delete();
			$files->delete();
		}

		return "Cleared expired Drops";
	}

}

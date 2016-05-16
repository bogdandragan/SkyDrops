<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class GroupTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		App\Group::create([
			'name'		=> 'SKYD-Admins',
			'maxSpace'	=> 10000
		]);
		
		App\Group::create([
			'name'		=> 'SKYD-Users',
			'maxSpace'	=> 200
		]);
	}

}

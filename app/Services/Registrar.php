<?php namespace App\Services;

use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'username' => 'required|max:255|unique:users',
			'first_name' => 'required|max:255',
			'last_name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		$verificationCode = str_random(30);
		$user = User::create([
			'username' => $data['username'],
			'firstname' => $data['first_name'],
			'lastname' => $data['last_name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'verification_code' => $verificationCode,
			'coins' => \Config::get('app.startCoins')
		]);

		//Mail
		\Mail::send('emails.registration', ['code' => $verificationCode], function($message) use($user)
		{
			$message->from('skydrops@skypro.ch', 'SKyDrops');
			$message->subject('Welcome to SKyDrops');
			$message->to($user->email);
		});


		return $user;
	}

}

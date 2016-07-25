<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class SharedDrops extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sharedDrops';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['drop_id', 'email', 'message'];

}

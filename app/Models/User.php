<?php

namespace App\Models;

use JWTAuth;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\EmailConfirmationMail;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'uuid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier() 
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims() 
    {
        return [];
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected static function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected static function create(array $data)
    {   
        $user = new User();
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->uuid = Str::uuid();
        $user->save();
        return $user;
    }

    protected static function validateUserExistance(Array $data)
    {
        $email = $data['email'];
        $username = $data['username'];
        $userWithEmail = User::where('email', $email)->first();
        $userWithUsername = User::where('username', $username)->first();
        if ($userWithEmail || $userWithUsername) {

            return true;
        }
        return false;

    }

    protected static function sendEmailConfirmationEmail(User $user)
    {   $token = JWTAuth::fromUser($user);
        $domain = env('ACTIVATION_URL');
        $activation_url = $domain . 'verify/' . $token . '/confirmation';
        return \Mail::to($user)->send(new EmailConfirmationMail($user, $activation_url));
    }

    public static function verifyEmail()
    {
        $token = JWTAuth::getToken();
        $email = JWTAuth::authenticate($token)->email;
        return $email;
    }
}

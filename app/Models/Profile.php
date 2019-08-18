<?php

namespace App\Models;

use App\Helpers\ErrorHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Profile extends Model
{
    //
    protected $fillable = ['first_name', 'last_name', 'image' ];

    protected $appends = ['isFollowing'];
    //TODO add isFollowingAttribute;

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    public function getIsFollowingAttribute()
    {
        if (request()->user) {
            return UserFollow::checkIfIsFollowing(request()->user->uuid, $this->user->uuid);
        }
        return false;
    }

    protected static function getProfiles()
    {
        return Profile::with('user')->orderBy('created_at', 'ASC')->paginate(10);
    }

    protected static function getSingleProfile (string $username)
    {
        return Profile::with('user')->where('username', $username)->first();
    }

    protected static function validator($data)
    {
        return Validator::make($data,[
            'first_name' => ['string', 'min:3'],
            'last_name' => ['string', 'min:3'],
            'image'  => ['string', 'min:3']
        ]);
    }

    protected static function updateProfile(string $username, Array $data)
    {
        if (count($data) === 0) {
            return [
                'errors' => 'Please enter at least one value of first_name, last_name, image'
            ];
        }

        $validator = Profile::validator(request()->all());

        if ($validator->fails()) {
            return [
                'errors' => ErrorHelper::formatErrors($validator)
            ];
        }
        return Profile::where('username', $username)
                        ->update($data);
    }
}

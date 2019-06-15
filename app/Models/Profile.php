<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
    
    protected static function getProfiles()
    {
        return Profile::with('user')->orderBy('created_at', 'ASC')->paginate(10);
    }

    protected static function getSingleProfile (string $username)
    {
        return Profile::with('user')->where('username', $username)->first();
    }
}

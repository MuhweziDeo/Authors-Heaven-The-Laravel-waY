<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserFollow extends Model
{
    //
    protected $fillable = ['follower', 'followee'];

    protected $rules = [];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower', 'uuid');
    }

    public function followee()
    {
        return $this->belongsTo(User::class, 'followee', 'uuid');
    }
    
    protected static function followUser($data)
    {
        return UserFollow::create($data);
    }

    protected static function checkIfCanFollow(string $followerUuid, string $followeeUuid)
    {
        $hasFollowed = UserFollow::where('follower', $followerUuid)
                                    ->where('followee', $followeeUuid)
                                    ->first();

        $isTryingToFollowSelf = $followerUuid === $followeeUuid;

        if ($hasFollowed || $isTryingToFollowSelf) {
            return false;
        }
        return true;

    }

    protected static function checkIfIsFollowing($followerUuid, $followeeUuid)
    {
        $hasFollowed = UserFollow::where('follower', $followerUuid)
                                    ->where('followee', $followeeUuid)
                                    ->first();
        if ($hasFollowed) {
            return true;
        }
        return false;
    }

    protected static function findUserFollow(string $followerUuid, $followeeUuid)
    {
        return UserFollow::where('follower', $followerUuid)
                            ->where('followee',$followeeUuid )->first();
    }

    protected static function getUserFollowers($followeeUuid)
    {
        return UserFollow::with('follower.profile')->where('followee', $followeeUuid)->get();
    }

    protected static function getUserFollowing($followerUuid)
    {
        return UserFollow::with('followee.profile')->where('follower', $followerUuid)->get();

    }

    protected static function unfollowUser(UserFollow $follow)
    {
        return $follow->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\UserFollow;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class UserFollowController extends Controller
{
    //

    protected function follow($followee)
    {
        $data['follower'] = request()->user->uuid;
        $data['followee'] = $followee;
        $follow = UserFollow::followUser($data);

        return response()->json([
            'message' => 'Successfully followed user',
            'data' => $follow
        ],Response::HTTP_OK);
    }

    protected function followers($followee)
    { 
        // get users followers
        $followers = UserFollow::getUserFollowers($followee);
        return response()->json([
            'followers' => $followers
        ]);
    }

    protected function following($follower)
    {
        // gets users a user follows
        $following = UserFollow::getUserFollowing($follower);
        return response()->json([
            'following' => $following
        ]);
    }

    protected function unfollow($followee)
    {
        $follow = UserFollow::findUserFollow(request()->user->uuid, $followee);
        if (!$follow) {
            return response()->json([
                'message' => 'You have not followed user before',
                'sucess' => false
            ],Response::HTTP_NOT_ACCEPTABLE);
        }
        $unFollow = UserFollow::unfollowUser($follow);

        if ($unFollow) {
            return response()->json([
                'message' => 'You have successfully unfollowed user',
                'sucess' => true
            ]); 
        }
        return response()->json([
            'message' => 'Unfollowing user was unsuccessful',
            'success' => false
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Response;


class ProfileController extends Controller
{
    //
    protected function index()
    {
        $profiles = Profile::getProfiles();
        return response()->json($profiles);
    }

    protected function show(string $username)
    {
        $profile = Profile::getSingleProfile($username);
        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found',
                'success' => false
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'data' => $profile,
            'success' => true
        ],Response::HTTP_OK);
    }

    protected function update($username)
    {   
        
        if (request()->user->username !== $username) {
            return response()->json([
                'message' => 'Permission Denied',
                'success' => false
            ],Response::HTTP_FORBIDDEN);
        }

        $profile = Profile::updateProfile($username,
            request()->only('first_name', 'last_name', 'image'));
        if ($profile['errors']) {
            return response()->json([
                'errors' => $profile['errors'],
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'message' => 'Profile update successfully',
            'success' => true
        ], Response::HTTP_OK);
      
    }

}

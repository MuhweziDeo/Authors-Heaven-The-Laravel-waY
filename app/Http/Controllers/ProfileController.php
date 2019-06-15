<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
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
}

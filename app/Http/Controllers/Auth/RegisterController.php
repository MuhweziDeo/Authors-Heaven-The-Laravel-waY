<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    protected function register()
    {
        $validator = User::validator(request()->all());
        if ($validator->fails()) {
            $errors = \App\Helpers\ErrorHelper::formatErrors($validator);
            return response()->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }
        $existingUser = User::validateUserExistance(request()->all());

        if (!$existingUser) {
            $user = User::create(request()->all());
            User::sendEmailConfirmationEmail($user);
            return response()->json([ 'data' => $user,
            'success' => true,
            'message' => 'Email verification link has been sent to your email check your email to complete registration'], Response::HTTP_CREATED);
        }

        return response()->json([
            'message' => 'username or email already taken',
            'success' => false ], Response::HTTP_CONFLICT);

    }

    protected function emailConfirmation()
    {
        $email = JWTAuth::parseToken()->authenticate()->email;

        $user = User::where('email', $email)->first();

        if ($user->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'email already verified'
            ], Response::HTTP_OK);
        }
        $user->is_verified = true;
        $user->email_verified_at = Carbon::now();
        $user->save();
        $access_token = JWTAuth::fromUser($user);
        return response()->json([
            'message' => 'email successfully verified',
            'sucess' => 'true',
            'data' => $user,
            'token' => $access_token
        ], Response::HTTP_OK);
    }
}

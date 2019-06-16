<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ResetsPasswords;
use JWTAuth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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

    public function resetPasswordRequest()
    {   $validator = Validator::make(request()->only('email'),[
                'email' => ['required', 'string', 'email']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please provide a valid email',
                'success' => false
            ], Response::HTTP_BAD_REQUEST);
        }
        $user = User::findUserByEmail(request('email'));
        if (!$user) return response()->json([
            'message' => 'No user with email found',
            'success' => false
        ], Response::HTTP_NOT_FOUND);

        $user->sendResetPasswordEmail($user);
        return response()->json([
            'message' => 'Password reset email sent to your email',
            'success' => true
        ], Response::HTTP_OK);
    }

    public function resetPasswordConfirm()
    {
        try {
            $email = JWTAuth::parseToken()->authenticate()->email;
            $user = User::findUserByEmail($email);

            if (!$user) {
                return response()->json([
                    'message' => 'User with email not found',
                    'success' => false
                ], Response::HTTP_NOT_FOUND);
            }
            $updatePassword = User::setNewPassword($email, request()->all());

            if($updatePassword['errors']) {
                return response()->json([
                    'errors' => $updatePassword['errors']
                ], Response::HTTP_BAD_REQUEST);
            }
            return response()->json([
                'message' => 'password reset successfully',
                'success' => true,
                'data' => $updatePassword,
                'token' => JWTAuth::fromUser($updatePassword)
            ], Response::HTTP_OK);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e ) {
            return response()->json([
                'message' => 'Couldnot complete request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
}

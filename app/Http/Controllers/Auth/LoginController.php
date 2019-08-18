<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function login()
    {
        $credentails = request()->only('email', 'password');
        try {
           $token = JWTAuth::attempt($credentails);
           if (!$token) {
               return response()->json([
                   'message' => 'Invalid login credentails',
                   'success' => false
               ], Response::HTTP_BAD_REQUEST);
           } 
           return response()->json([
               'token' => $token,
               'username' => request('username'),
               'success' => true
           ]);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'message' => 'Unable to create token',
                'success' => false,
                'error'  => $e->getMessage()
            ]);
        }
        return response()->json($credentails);
    }
}

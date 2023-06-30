<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    // protected function authenticated(Request $request, $user)
    // {
    //     if(Auth::user()->actif != 1){
    //         Auth::logout();
    //         return back()->withErrors(['email' => 'Votre compte n\'est plus actif']);
    //     }
    //     // if(!Auth::user()->email_verified_at){
    //     //     Auth::user()->sendEmailVerificationNotification();
    //     // }
    // }



    public function login(Request $request)
    {
        if(\Auth::attempt(['email' => strtolower($request->email), 'password' => $request->password])){ 
            $user = \Auth::user();

            if($user->actif == 1){
                return response()->json([
                    'error' => false,
                    'url' => url('/')
                ]);  
            }else{
                \Auth::logout();
               return response()->json([
                    'error' => true,
                    'message' => [['Votre compte n\'est plus actif']]
                ]); 
            }
        }else{ 
            return response()->json([
                'error' => true,
                'message' => [['Ces identifiants ne correspondent pas à nos enregistrements']]
            ]); 
        }
    }

}

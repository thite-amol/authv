<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Auth\AuthenticatesUsers;
use App\ActivationService;

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

    protected $activationService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ActivationService $activationService)
    {
    	$this->middleware('guest', ['except' => 'logout']);
    	$this->activationService = $activationService;
    }

    public function authenticated(Request $request, $user)
    {
    	if (!$user->activated) {
    		$this->activationService->sendActivationMail($user);
    		auth()->logout();
    		return back()->with('warning', 'You need to confirm your account. We have sent you an activation code, please check your email.');
    	}
    	return redirect()->intended($this->redirectPath());
    }

    public function activateUser($token)
    {
    	if ($user = $this->activationService->activateUser($token)) {
    		auth()->login($user);
    		return redirect($this->redirectPath());
    	}
    	abort(404);
    }
}

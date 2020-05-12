<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ConfirmPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

    use ConfirmsPasswords;

    /**
     * Where to redirect users when the intended url fails.
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
        $this->middleware('auth');
    }

    public function showConfirmForm(){
        $SecurityQuestion = auth()->user()->securityQuestion;
        return view('auth.passwords.confirm', compact('SecurityQuestion'));
    }

    public function confirm(Request $request)
    {
        $passwordMatch = Hash::check($request->password, auth()->user()->password) ? true : false;
        // dd($passwordMatch);
        if ($request->input('answer')) {
            if (auth()->user()->security_answer != $request->input('answer') || $passwordMatch == false) {
                return back()->withInput()->withErrors(['security_answer' => 'Sorry, wrong answer or password']);
            }
        } else {
            $request->validate($this->rules(), $this->validationErrorMessages());
        }

        $this->resetPasswordConfirmationTimeout($request);

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect()->intended($this->redirectPath());
    }
}

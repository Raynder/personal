<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        // validar recaptcha
        // if ($request->has('recaptcha')) {
        //     // Build POST request:
        //     $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        //     $recaptcha_secret = env('G_SECRET_KEY');
        //     $recaptcha_response = $_POST['recaptcha'];

        //     // Make and decode POST request:
        //     $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        //     $recaptcha = json_decode($recaptcha);

        //     if ($recaptcha->success != true) {
        //         RateLimiter::hit($request->throttleKey());
        //         throw ValidationException::withMessages([
        //             'email' => "Captcha não confirmado. Favor tentar novamente.",
        //         ]);
        //     }
        // }

        $request->authenticate();

        $request->session()->regenerate();
        Auth::logoutOtherDevices(request('password'));
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

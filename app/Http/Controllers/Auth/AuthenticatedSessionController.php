<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    /*public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        session()->flash('success', 'Content de vous revoir!');

        return redirect()->intended(RouteServiceProvider::HOME); //dashboard
    }*/

    //By jean-yves
    public function store(LoginRequest $request): RedirectResponse
    {
        // Tentative d'authentification de l'utilisateur
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'Les informations d\'identification fournies sont incorrectes.',
            ]);
        }

        $user = Auth::user();
        if (($user->role !== 'admin' && $user->role !== 'organizer') || $user->status !== 'actif') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Vous n\'êtes pas autorisé à vous connecter avec ce compte.',
            ]);
        }

        $request->session()->regenerate();

        session()->flash('success', 'Content de vous revoir!');

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->flash('success', 'Au revoir à bientôt!');

        return redirect('/');
    }
}

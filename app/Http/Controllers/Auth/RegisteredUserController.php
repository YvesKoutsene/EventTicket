<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'number' => 'required|string|max:15',
            'profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        try {
            $profilePath = null;
            if ($request->hasFile('profile')) {
                $profile = $request->file('profile');
                $profileName = time() . '_' . str_replace(' ', '_', $profile->getClientOriginalName());
                $profileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $profileName);
                $profilePath = $profile->storeAs('public/pictures/profiles', $profileName);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'organizer',
                'number' => $request->number,
                'profile' => $profilePath ? Storage::url($profilePath) : 'default_profile_url',
                'status' => 'actif',
            ]);

            return redirect()->route('login')->with('success', "Compte créé avec succès, veuillez-vous connecter");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.');
        }
    }

}

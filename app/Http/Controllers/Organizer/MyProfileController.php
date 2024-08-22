<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class MyProfileController extends Controller
{
    //Fonction permettant de voir le profil d'un organisateur
    public  function myProfile()
    {
        return view('organizer.pages.user.profile');

    }

    //Fonction de renvoie de la page de guide utilisateur
    public  function userGuide()
    {
        return view('organizer.pages.user.guide');

    }

    //Fonction de renvoie de la page de mis à jour profil
    public  function myProfileEdit(Request $request){
        return view('organizer.pages.user.updatemyprofile', [
            'user' => $request->user(),
        ]);
    }

    // Fonction pour mettre à jour le compte organisateur
    public function updateOrganizerInfo(Request $request, $id): RedirectResponse
    {
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'number' => 'required|string|max:15',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validateUser->fails()) {
            return redirect()->back()
                ->withErrors($validateUser)
                ->withInput();
        }

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->number = $request->number;

        if ($request->hasFile('profile')) {
            if ($user->profile) {
                $oldProfilePath = str_replace('storage/', '', $user->profile);
                Storage::disk('public')->delete($oldProfilePath);
            }

            $profile = $request->file('profile');
            $profileName = time() . '_' . str_replace(' ', '_', $profile->getClientOriginalName());
            $profileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $profileName);
            $profilePath = $profile->storeAs('public/pictures/profiles', $profileName);
            $user->profile = Storage::url($profilePath);
        }

        $user->save();

        return back()->with('success', "Votre compte a été mis à jour avec succès.");
    }

    // Fonction pour mettre à jour le mot de passe de l'admin
    public function updateOrganizerPassword(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }

}

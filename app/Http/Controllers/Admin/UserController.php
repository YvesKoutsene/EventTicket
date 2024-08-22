<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    //Fonction de renvoie de la page ajout d'utilisateur
    public function  indexUser()
    {
        return view('admin.pages.user.adduser');
    }

    //Fonction de renvoie de la liste d'utilisateur
    public function userList(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $user = User::where('role', '!=', 'admin')->paginate($perPage);
        return view('admin.pages.user.userlist', ['user' => $user]);
    }

    // Fonction d'enregistrement d'utilisateur
    public function storeUser(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'confirmed|required|string|min:8',
            'number' => 'required|string|max:15',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validateUser->fails()) {
            return redirect()->back()
                ->withErrors($validateUser)
                ->withInput();
        }

        $profilePath = null;

        if ($request->hasFile('profile')) {
            $profile = $request->file('profile');
            $profileName = time() . '_' . str_replace(' ', '_', $profile->getClientOriginalName());
            $profileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $profileName);
            $profilePath = $profile->storeAs('public/pictures/profiles', $profileName);

            if (isset($user->profile)) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile));
            }
        }

        $user = User::updateOrCreate(
            ['email' => $request->email], // Condition pour trouver l'utilisateur existant
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'user',
                'number' => $request->number,
                'profile' => $profilePath ? Storage::url($profilePath) : ($user->profile ?? 'default_profile_url'),
                'status' => 'actif',
            ]
        );

        return redirect()->route('user')
            ->with('success', "Utilisateur enregistré avec succès.");
    }

    //Fonction pour activer un utilisateur
    public function activateUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->status !== 'inactif') {
            return redirect()->route('uer')->with('error', 'Seuls les utilisateurs inactifs peuvent être activés.');
        }

        $user->status = 'actif';
        $user->save();

        return redirect()->route('user')->with('success', 'Utilisateur activé avec succès.');
    }

    //Fonction pour desactiver un utilisateur
    public function desactivateUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->status !== 'actif') {
            return redirect()->route('user')->with('error', 'Seuls les utilisateurs actifs peuvent être désactivés.');
        }

        $user->status = 'inactif';
        $user->save();

        return redirect()->route('user')->with('success', 'Utilisateur désactivé avec succès.');
    }

    //Fonction de renvoie la page de mise à jour utilisateur
    public function  indexUpdateUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'organizer') {
            return redirect()->route('user')->with('error', 'Seuls les organisateurs peuvent être mis à jour.');
        }
        return view('admin.pages.user.updateuser', compact('user'));
    }

    //Fonction pour mettre à jour un utilisateur
    public function updateUser(Request $request, $id)
    {
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
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

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

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

        if ($user->role !== 'organizer') {
            return redirect()->route('user')->with('error', 'Seuls les organisateurs peuvent être mis à jour.');
        }

        $user->save();

        return redirect()->route('user')
            ->with('success', "Utilisateur mis à jour avec succès.");
    }

    // Fonction pour mettre à jour le compte de l'admin
    public function updateAdminInfo(Request $request, $id): RedirectResponse
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

        return back()->with('success', "Compte admin mis à jour avec succès.");
    }

    // Fonction pour mettre à jour le mot de passe de l'admin
    public function updateAdminPassword(Request $request, $id): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Mot de passe admin mis à jour avec succès.');
    }

}

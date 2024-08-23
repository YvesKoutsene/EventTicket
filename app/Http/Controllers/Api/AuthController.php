<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

//Ecrit par jean-yves
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    //register
    public function register(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'number' => 'required|string|max:15',
            'profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 422);
        }

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
                'role' => 'user',
                'number' => $request->number,
                'profile' => $profilePath ? Storage::url($profilePath) : 'default_profile_url',
                'status' => 'actif',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken("auth_token")->plainTextToken,
                'token_type' => 'Bearer',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    // Login
    public function login(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();

        if (!$user || !Auth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        if ($user->status === 'inactif') {
            return response()->json([
                'status' => false,
                'message' => 'Votre compte est inactif. Veuillez contacter l\'administrateur.'
            ], 403);
        }

        if ($user->role === 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Vous n\'êtes pas autorisé à vous connecter avec ce compte.'
            ], 403); // Forbidden
        }

        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            'token' => $user->createToken("auth_token")->plainTextToken,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'number' => $user->number,
                'role'=>$user->role,
                'creation'=>$user->created_at,
                'edition'=>$user->updated_at,
                'profile' => url($user->profile),
            ]
        ], 200); // OK
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200); // OK
    }

}

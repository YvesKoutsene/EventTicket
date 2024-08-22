<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favoris;
use Illuminate\Support\Facades\Auth;

class FavorisControrller extends Controller
{
    //Ajouter un évènement à  ses favoris
    public function addFavoris(Request $request)
    {
        $request->validate([
            'eve_id' => 'required|exists:evenements,id',
        ]);

        $userId = Auth::id();
        $eveId = $request->input('eve_id');

        $user = Auth::user();
        // Vérifier le rôle de l'utilisateur
        if ($user->role === 'organizer') {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à ajouter cet événement à vos favoris'], 403);
        }

        // Vérifier si le favori existe déjà
        $existingFavoris = Favoris::where('user_id', $userId)
            ->where('eve_id', $eveId)
            ->first();

        if ($existingFavoris) {
            return response()->json(['message' => 'Cet événement est déjà dans vos favoris'], 409);
        }

        $favoris = Favoris::create([
            'user_id' => $userId,
            'eve_id' => $eveId,
        ]);

        return response()->json($favoris, 201);
    }



    //Supprimer un évènement de ses favoris
    public function removeFavoris($id)
    {
        $favoris = Favoris::where('eve_id', $id)->where('user_id', Auth::id())->first();

        // Vérifier si l'entrée existe
        if ($favoris) {
            $favoris->delete();
            return response()->json(['message' => 'Événement supprimé des favoris'], 200);
        } else {
            return response()->json(['message' => 'Événement non trouvé dans les favoris'], 404);
        }
    }

    //Consulter ses favoris
    public function listFavoris()
    {
        $favoris = Favoris::where('user_id', Auth::id())
            ->with(['evenement.categorie', 'evenement.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($favoris, 200);
    }


}

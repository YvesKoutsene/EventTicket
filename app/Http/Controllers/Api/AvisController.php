<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Avis;
use App\Models\FactureCommande;
use App\Models\Ticket;
use App\Models\Billet;
use App\Models\Evenement;

class AvisController extends Controller
{
    // Permettre à un utilisateur de donner un avis
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'organizer') {
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à commenter cet évènement'], 403);
        }

        $request->validate([
            'eve_id' => 'required|exists:evenements,id',
            'note' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);

        // Vérifier si l'utilisateur a un ticket pour cet évènement
        $ticketExists = Ticket::whereHas('factureCommande', function ($query) use ($user, $request) {
            $query->where('user_id', $user->id)
                ->whereHas('billet', function ($q) use ($request) {
                    $q->where('eve_id', $request->eve_id);
                });
        })->exists();

        if (!$ticketExists) {
            return response()->json(['error' => 'Vous devez être participant de cet évènement.'], 403);
        }

        $avis = Avis::create([
            'eve_id' => $request->eve_id,
            'note' => $request->note,
            'comment' => $request->comment,
            'user_id' => Auth::id(),
            'status' => 'actif',
        ]);

        return response()->json($avis, 201);
    }


    // Permettre à un utilisateur de supprimer son avis
    public function destroy($id)
    {
        $avis = Avis::find($id);

        if (!$avis) {
            return response()->json(['message' => 'Avis non trouvé'], 404);
        }

        if ($avis->user_id !== Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $avis->delete();

        return response()->json(['message' => 'Avis supprimé'], 200);
    }

    // Afficher tous les avis d'un événement donné
    public function index($evenementId)
    {
        $avis = Avis::where('eve_id', $evenementId)
            ->where('status', 'actif')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($avis, 200);
    }


}

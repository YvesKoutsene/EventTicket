<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evenement;
use App\Models\Billet;
use App\Models\FactureCommande;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyEventOderController extends Controller
{

    //Fonction permettant de ramener les commandes des évènements d'un organisateur
    public function myEventOrderList()
    {
        $userId = Auth::id();

        // Récupérer les événements de l'utilisateur
        $evenementsIds = Evenement::where('user_id', $userId)->pluck('id');

        if ($evenementsIds->isEmpty()) {
            return view('organizer.pages.order.myeventoderlist', ['commandes' => collect()]);
        }

        $commandes = FactureCommande::with([
            'billet.evenement.categorie',
            'billet.typeBillet',
            'tickets',
            'user'
        ])
            ->whereHas('billet', function($query) use ($evenementsIds) {
                // Filtrer les billets par les IDs des événements
                $query->whereIn('eve_id', $evenementsIds);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($commandes as $commande) {
            foreach ($commande->tickets as $ticket) {
                if ($ticket->status === 'actif' && Carbon::now()->greaterThan(Carbon::parse($ticket->dateExpiration))) {
                    $ticket->status = 'expiré';
                    $ticket->save();
                }
            }
        }

        return view('organizer.pages.order.myeventoderlist', compact('commandes'));
    }

}

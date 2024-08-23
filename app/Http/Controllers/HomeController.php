<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\FactureCommande;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Billet;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Avis;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->check()) {
            $role = auth()->user()->role;
            if ($role === 'admin') {
                // Calcul des statistiques pour l'admin
                $totalUsers = User::where('role', '<>', 'admin')->count();
                $totalEvents = Evenement::count();
                $totalOrders = FactureCommande::count();
                $totalRevenue = FactureCommande::sum('prixTotal');

                return view('admin.pages.home', compact('totalUsers', 'totalEvents', 'totalOrders', 'totalRevenue'));

            } elseif ($role === 'organizer') {
                $userId = Auth::id();
                $evenementsIds = Evenement::where('user_id', $userId)->pluck('id');

                $totalEvenements = Evenement::where('user_id', $userId)->count();
                $totalBillets = Billet::whereIn('eve_id', $evenementsIds)->count();
                $totalAvis = Avis::whereIn('eve_id', $evenementsIds)->count();
                $totalCommandes = FactureCommande::whereHas('billet', function($query) use ($evenementsIds) {
                    $query->whereIn('eve_id', $evenementsIds);
                })->count();
                $totalRevenus = FactureCommande::whereHas('billet', function($query) use ($evenementsIds) {
                    $query->whereIn('eve_id', $evenementsIds);
                })->sum('prixTotal');

                // Récupérer les commandes du mois en cours
                $commandes = FactureCommande::with([
                    'billet.evenement.categorie',
                    'billet.typeBillet',
                    'tickets',
                    'user'
                ])
                    ->whereHas('billet', function($query) use ($evenementsIds) {
                        $query->whereIn('eve_id', $evenementsIds);
                    })
                    ->whereMonth('created_at', Carbon::now()->week) //month
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

                return view('organizer.pages.home', compact('commandes', 'totalEvenements', 'totalBillets', 'totalAvis', 'totalCommandes', 'totalRevenus'));
            }
        }
        abort(403, 'Unauthorized action.');
    }

}

<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Avis;
use App\Models\Evenement;


class MyEventNoticeController extends Controller
{
    // Fonction de renvoi de la page la liste des avis des évènements d'un organisateur
    public function myEventNoticeList()
    {
        // Obtenir l'ID de l'utilisateur authentifié
        $userId = Auth::id();

        $events = Evenement::where('user_id', $userId)
            ->with(['avis.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Passer les données à la vue
        return view('organizer.pages.notice.myeventnoticelist', compact('events'));

    }

    // Fonction pour bloquer un avis
    public function blockNotice($id)
    {
        $avis = Avis::findOrFail($id);

        if ($avis->evenement->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à gérer cet avis.');
        }

        $avis->status = 'bloqué';
        $avis->save();

        return redirect()->back()->with('success', 'Avis bloqué avec succès');
    }

    // Fonction pour débloquer un avis
    public function unBlockNotice($id)
    {
        $avis = Avis::findOrFail($id);

        if ($avis->evenement->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à gérer cet avis.');
        }

        $avis->status = 'actif';
        $avis->save();

        return redirect()->back()->with('success', 'Avis débloqué avec succès');
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorieEvenement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Evenement;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    //Fonction de renvoie de la liste d'évènement
    public function eventList(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $filter = $request->input('filter', 'all');

        $query = Evenement::with(['categorie', 'user', 'billets'])
            ->orderBy('created_at', 'desc');

        switch ($filter) {
            case 'category':
                $query->orderBy('cat_id');
                break;
            case 'organizer':
                $query->orderBy('user_id');
                break;
            default:
                break;
        }
        $event = $query->paginate($perPage);

        return view('admin.pages.event.eventlist', ['event' => $event, 'filter' => $filter]);
    }

    //Fonction de renvoie de la page information d'évènement
    public function indexShowEvent($id)
    {
        $event = Evenement::with(['categorie', 'user', 'billets.typeBillet'])->findOrFail($id);

        return view('admin.pages.event.showevent', compact('event'));
    }

    //Fonction pour approuver un évènement

    public function approvedEvent($id)
    {
        $event = Evenement::findOrFail($id);

        if ($event->status !== 'en cours') {
            return redirect()->route('event')->with('error', 'Seuls les évènements en cours peuvent être approuvés.');
        }

        $event->status = 'actif';
        $event->datePublication = now();
        $event->save();

        foreach ($event->billets as $billet) {
            $billet->status = 'ouvert';
            $billet->save();
        }

        return redirect()->route('event')->with('success', 'Evènement approuvé et publié avec succès.');
    }

    //Fonction pour désapprouver un évènement
    /*public function disapprovedEvent($id)
    {
        $event = Evenement::findOrFail($id);

        if ($event->status !== 'en cours') {
            return redirect()->route('event')->with('error', 'Seuls les évènements en cours peuvent être rejétés.');
        }
        $event->status = 'rejeté';
        $event->datePublication = now();
        $event->save();

        foreach ($event->billets as $billet) {
            $billet->status = 'annulé';
            $billet->save();
        }

        return redirect()->route('event')->with('success', 'Evènement désapprouvé avec succès.');
    }*/

    // Fonction pour désapprouver un évènement
    public function disapprovedEvent(Request $request, $id)
    {
        $event = Evenement::findOrFail($id);

        if ($event->status !== 'en cours') {
            return redirect()->route('event')->with('error', 'Seuls les évènements en cours peuvent être rejetés.');
        }

        $request->validate([
            'motif' => 'required|string|max:500',
        ]);

        $event->status = 'rejeté';
        $event->motif = $request->motif;
        $event->datePublication = now();
        $event->save();

        foreach ($event->billets as $billet) {
            $billet->status = 'annulé';
            $billet->save();
        }

        return redirect()->route('event')->with('success', 'Evènement désapprouvé avec succès.');
    }


}

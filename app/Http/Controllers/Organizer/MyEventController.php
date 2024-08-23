<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\CategorieEvenement;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\TypeBillet;
use Carbon\Carbon;
use App\Models\FactureCommande;


class MyEventController extends Controller
{
    // Fonction de renvoi de la liste des événements d'un organisateur
    public function myEventList(Request $request)
    {
        $events = Evenement::with(['categorie', 'billets'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($events as $event) {
            $tomorrowAfterEventEnd = Carbon::parse($event->dateFin)->addDay()->startOfDay();

            if (Carbon::now()->greaterThanOrEqualTo($tomorrowAfterEventEnd)) {
                $event->status = 'terminé';
                $event->save();

                foreach ($event->billets as $billet) {
                    if ($billet->status === 'ouvert') {
                        $billet->status = 'fermé';
                        $billet->save();
                    }
                }
            }
        }

        return view('organizer.pages.event.myeventlist', ['events' => $events]);
    }

    //Fonction de renvoie de la page ajout d'évènements
    public function indexMyEvent()
    {
        $type = TypeBillet::where('nom', '!=', 'Gratuit')->get();
        $categories = CategorieEvenement::all();
        return view('organizer.pages.event.addmyevent', compact('categories','type'));
    }

    public function storeMyEvent(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => [
                'required',
                'string',
                'max:254',
                Rule::unique('evenements'),
            ],
            'lieu' => 'required|string|max:254',
            'description' => 'required|string|max:254',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'heure' => 'required',
            'categorie_evenement_id' => 'required|exists:categories_evenements,id',
            'type_event' => 'required|in:gratuit,payant',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'place' => 'required|numeric|min:1',
        ], [
            'place.min' => 'Le nombre de places doit être strictement supérieur à 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
            $imageName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $imageName);
            $imagePath = $image->storeAs('public/pictures/events', $imageName);
        }

        $dateDebut = Carbon::createFromFormat('d M Y', $request->dateDebut)->format('Y-m-d');
        $dateFin = Carbon::createFromFormat('d M Y', $request->dateFin)->format('Y-m-d');

        $placeRestant = 0;
        $billetQuota = 0;

        // Si l'événement est gratuit
        if ($request->type_event == 'gratuit') {
            $placeRestant = 0;
            $billetQuota = $request->place;
        }
        // Si l'événement est payant
        else if ($request->type_event == 'payant') {
            $placeRestant = $request->place;
        }

        // Créer l'événement
        $evenement = Evenement::create([
            'nom' => $request->nom,
            'lieu' => $request->lieu,
            'place' => $request->place,
            'placeRestant' => $placeRestant,
            'motif' => 'aucun',
            'description' => $request->description,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'heure' => $request->heure,
            'cat_id' => $request->categorie_evenement_id,
            'image' => $imagePath ? Storage::url($imagePath) : 'default_image_url',
            'status' => 'inactif',
            'type' => $request->type_event,
            'user_id' => Auth::id(),
        ]);

        // Créer un billet gratuit si l'événement est gratuit
        if ($request->type_event == 'gratuit') {
            $typeBilletGratuit = TypeBillet::where('nom', 'Gratuit')->firstOrFail();

            Billet::create([
                'eve_id' => $evenement->id,
                'typ_id' => $typeBilletGratuit->id,
                'nombre' => 1,
                'prix' => 0,
                'quota' => $billetQuota,
                'rest' => $billetQuota,
                'status' => 'inactif',
            ]);
        }

        return redirect()->route('myEvent')
            ->with('success', "Votre événement a été créé avec succès.");
    }

    // Fonction de renvoie de la page de mise à jour d'évènement
    public function indexUpdateMyEvent($id)
    {
        $categories = CategorieEvenement::all();
        $event = Evenement::with(['categorie'])->findOrFail($id);

        if ($event->user_id !== auth()->id()) {
            return redirect()->route('myEvent')->with('error', 'Vous n\'êtes pas autorisé à mettre à jour cet événement.');
        }

        if ($event->status !== 'inactif') {
            return redirect()->route('myEvent')->with('error', 'Seuls les événements inactifs peuvent être mis à jour.');
        }

        $event->dateDebut = Carbon::parse($event->dateDebut)->format('d M Y');
        $event->dateFin = Carbon::parse($event->dateFin)->format('d M Y');

        return view('organizer.pages.event.updatemyevent', compact('event', 'categories'));
    }


    public function updateMyEvent(Request $request, $id)
    {
        $event = Evenement::findOrFail($id);

        if ($event->user_id !== auth()->id()) {
            return redirect()->route('myEvent')->with('error', 'Vous n\'êtes pas autorisé à mettre à jour cet événement.');
        }

        if ($event->status !== 'inactif') {
            return redirect()->back()->with('error', 'Seuls les événements inactifs peuvent être mis à jour.');
        }

        $validator = Validator::make($request->all(), [
            'nom' => [
                'required',
                'string',
                'max:254',
                Rule::unique('evenements')->ignore($event->id),
            ],
            'lieu' => 'required|string|max:254',
            'description' => 'required|string|max:254',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'heure' => 'required',
            'categorie_evenement_id' => 'required|exists:categories_evenements,id',
            'place' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dateDebut = Carbon::createFromFormat('d M Y', $request->dateDebut)->format('Y-m-d');
        $dateFin = Carbon::createFromFormat('d M Y', $request->dateFin)->format('Y-m-d');

        $event->nom = $request->nom;
        $event->lieu = $request->lieu;
        $event->description = $request->description;
        $event->dateDebut = $dateDebut;
        $event->dateFin = $dateFin;
        $event->heure = $request->heure;
        $event->cat_id = $request->categorie_evenement_id;

        $oldPlace = $event->place;
        $newPlace = $request->input('place');
        $difference = $newPlace - $oldPlace;

        if ($event->type === 'gratuit') {
            if ($difference > 0) {
                $billetGratuit = $event->billets()->whereHas('typeBillet', function($query) {
                    $query->where('nom', 'Gratuit');
                })->first();

                if ($billetGratuit) {
                    $billetGratuit->quota += $difference;
                    $billetGratuit->rest += $difference;
                    $billetGratuit->save();
                }
            } elseif ($difference < 0) {
                $billetGratuit = $event->billets()->whereHas('typeBillet', function($query) {
                    $query->where('nom', 'Gratuit');
                })->first();

                if ($billetGratuit && ($billetGratuit->quota + $difference) >= 0) {
                    $billetGratuit->quota += $difference;
                    $billetGratuit->rest += $difference;
                    $billetGratuit->save();
                } else {
                    return redirect()->back()->with('error', 'La réduction dépasse le quota disponible.');
                }
            }
        } else {
            if ($difference > 0) {
                $event->placeRestant += $difference;
            } elseif ($difference < 0) {
                if ($event->placeRestant >= abs($difference)) {
                    $event->placeRestant += $difference;
                } else {
                    return redirect()->back()->with('error', 'La réduction dépasse le nombre de places restantes à mettre en oeuvre.');
                }
            }
        }

        $event->place = $newPlace;

        if ($request->hasFile('image')) {
            if ($event->image) {
                $oldImagePath = str_replace('storage/', '', $event->image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
            $imageName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $imageName);
            $imagePath = $image->storeAs('public/pictures/events', $imageName);
            $event->image = Storage::url($imagePath);
        }

        $event->save();

        return redirect()->route('myEvent')->with('success', "Votre évènement {$event->nom} a été mis à jour avec succès.");
    }


    // Fonction de suppression d'évènement
    public function deleteMyEvent($id)
    {
        $event = Evenement::findOrFail($id);

        if ($event->user_id !== auth()->id()) {
            return redirect()->route('myEvent')->with('error', 'Vous n\'êtes pas autorisé à supprimer cet événement.');
        }

        if ($event->status !== 'inactif') {
            return redirect()->route('myEvent')->with('error', 'Seuls les événements inactifs peuvent être supprimés.');
        }

        $event->billets()->delete();

        if ($event->image) {
            $imagePath = str_replace('storage/', '', $event->image);
            Storage::disk('public')->delete($imagePath);
        }

        $event->delete();

        return redirect()->route('myEvent')->with('success', "Votre évènement {$event->nom} et les billets associés ont été supprimés avec succès.");
    }

    //Fonction de renvoie de billets pour un évènement
    public function getMyEventBillets($eventId)
    {
        $event = Evenement::findOrFail($eventId);

        if ($event->user_id !== auth()->id()) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à accéder à ces billets.'
            ], 403);
        }

        $billets = Billet::where('eve_id', $eventId)
            ->where('status', 'inactif')
            ->with('typeBillet')
            ->get();

        return response()->json([
            'billets' => $billets
        ]);
    }

    // Fonction pour publier un évènement (soumettre à la validation de l'admin)
    public function publishMyEvent($eventId)
    {
        $event = Evenement::find($eventId);

        if ($event->user_id !== auth()->id()) {
            return redirect()->route('myEvent')->with('error', 'Vous n\'êtes pas autorisé à publier cet événement.');
        }

        if ($event->status !== 'inactif') {
            return redirect()->route('myEvent')->with('error', "Impossible de publier cet événement car il n'est pas 'inactif'.");
        }

        if ($event->billets->count() === 0) {
            return redirect()->route('myEvent')->with('error', "Impossible de publier cet événement car il n'a pas de billet créé.");
        }

        foreach ($event->billets as $billet) {
            if ($billet->status !== 'inactif') {
                return redirect()->route('myEvent')->with('error', "Impossible de publier cet événement car un des billets n'est pas 'inactif'.");
            }
        }

        // Vérification de placeRestant
        if ($event->placeRestant != 0) {
            return redirect()->route('myEvent')->with('error', "Impossible de publier cet événement car il reste {$event->placeRestant} place(s) encore.");
        }

        $event->status = 'actif';
        $event->datePublication = now();
        $event->save();

        foreach ($event->billets as $billet) {
            $billet->status = 'ouvert';
            $billet->save();
        }

        return redirect()->route('myEvent')->with('success', "Votre événement {$event->nom} vient d'être publié.");
    }

    //Fonction permettant d'annluer un évènement avec ces billets
    public function canceledEvent($id)
    {
        // Récupérer l'événement
        $event = Evenement::findOrFail($id);

        // Vérification des autorisations
        if ($event->user_id !== auth()->id()) {
            return redirect()->route('myEvent')->with('error', 'Vous n\'êtes pas autorisé à annuler cet événement.');
        }

        // Vérification du statut de l'événement
        if ($event->status !== 'actif') {
            return redirect()->route('myEvent')->with('error', 'Seuls les événements actifs peuvent être annulés.');
        }

        // Annuler l'événement
        $event->status = 'annulé';
        $event->save();

        // Annuler tous les billets et les tickets associés de l'événement
        foreach ($event->billets as $billet) {
            if($billet->status === 'ouvert'){
                $billet->status = 'annulé';
                $billet->save();
            }

            // Récupérer toutes les factures associées au billet
            $factures = FactureCommande::where('bil_id', $billet->id)->get();

            foreach ($factures as $facture) {
                // Annuler tous les tickets associés à la facture
                foreach ($facture->tickets as $ticket) {
                    if ($ticket->status === 'actif') {
                        $ticket->status = 'annulé';
                        $ticket->save();
                    }
                }
            }
        }

        // Redirection avec message de succès
        return redirect()->route('myEvent')->with('success', 'Votre événement, tous les billets et les tickets associés ont été annulés avec succès.');
    }



}

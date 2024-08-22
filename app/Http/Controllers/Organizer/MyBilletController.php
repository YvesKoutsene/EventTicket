<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Evenement;
use App\Models\TypeBillet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class MyBilletController extends Controller
{
    //Fonction permettant de ramener les billets d'un organisateur
    public function myBilletList(Request $request)
    {
        $billets = Billet::whereHas('evenement', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['typeBillet', 'evenement'])
            ->orderBy('created_at', 'desc')
            ->get();

        $evenementIds = [];
        foreach ($billets as $billet) {
            $event = $billet->evenement;

            if (!in_array($event->id, $evenementIds)) {
                $allBilletsForEvent = Billet::where('eve_id', $event->id)->get();

                $allSold = $allBilletsForEvent->every(function ($billet) {
                    return $billet->status === 'vendu';
                });

                if ($allSold) {
                    $event->status = 'fermé';
                    $event->save();
                }

                $evenementIds[] = $event->id;
            }
        }

        return view('organizer.pages.billet.mybilletlist', compact('billets'));
    }

    //Fonction pour ramener la page d'ajout de billet avec tous les types de billets et tous les évènements du user
    public function indexMyBillet()
    {
        $type = TypeBillet::where('nom', '!=', 'Gratuit')->get();

        $event = Evenement::where('status', 'inactif')
            ->where('user_id', Auth::id())
            ->whereDoesntHave('billets.typeBillet', function ($query) {
                $query->where('nom', 'Gratuit');
            })
            ->get();

        return view('organizer.pages.billet.addmybillet', compact('type', 'event'));
    }

    //Fonction d'enregistrement de billet
    public function storeMyBillet(Request $request)
    {
        // Validation des entrées
        $validator = Validator::make($request->all(), [
            'eve_id' => 'required|exists:evenements,id',
            'typ_id' => 'required|exists:types_billets,id',
            'prix' => 'required|numeric|min:1',
            'quota' => 'required|integer|min:1',
        ], [
            'prix.min' => 'Le prix doit être strictement supérieur à 0.',
            'quota.min' => 'Le quota doit être strictement supérieur à 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Récupération de l'événement
        $event = Evenement::findOrFail($request->input('eve_id'));

        // Vérification de l'autorisation de l'utilisateur
        if ($event->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous n\'êtes pas autorisé à ajouter un billet pour cet événement.');
        }

        // Vérification si le billet existe déjà
        $existingBillet = Billet::where('eve_id', $request->input('eve_id'))
            ->where('typ_id', $request->input('typ_id'))
            ->first();

        if ($existingBillet) {
            return redirect()->back()
                ->with('error', 'Ce billet a déjà été ajouté pour cet événement.')
                ->withInput();
        }

        // Récupération du type de billet
        $typeBillet = TypeBillet::findOrFail($request->input('typ_id'));

        // Validation du nombre d'individus pour les billets de type famille ou groupe
        if (($typeBillet->nom === 'Groupe' || $typeBillet->nom === 'Famille') && $request->input('nombre') < 2) {
            return redirect()->back()
                ->with('error', 'Le nombre d\'individus doit être supérieur ou égal à 2 pour les billets de type famille ou groupe.')
                ->withInput();
        }

        // Par défaut, nombre = 1 si non saisi pour les autres types de billets
        if ($typeBillet->nom !== 'Groupe' && $typeBillet->nom !== 'Famille') {
            $request->merge(['nombre' => 1]);
        }

        // Vérification du quota par rapport aux places restantes
        if ($request->input('quota') > $event->placeRestant) {
            return redirect()->back()
                ->with('error', 'Le quota saisi dépasse le nombre de places restantes prevues pour cet événement.')
                ->withInput();
        }

        // Création du billet
        $billet = new Billet();
        $billet->eve_id = $request->input('eve_id');
        $billet->typ_id = $request->input('typ_id');
        $billet->nombre = $request->input('nombre');
        $billet->prix = $request->input('prix');
        $billet->quota = $request->input('quota');
        $billet->rest = $request->input('quota');
        $billet->status = 'inactif';
        $billet->save();

        // Mise à jour des places restantes pour l'événement
        $event->placeRestant -= $request->input('quota');
        $event->save();

        return redirect()->route('myBillet')->with('success', 'Votre billet a été ajouté avec succès à l\'événement ' . $event->nom . '.');
    }

    // Fonction de renvoie de la page de mise à jour de billet
    public function indexUpdateMyBillet($id)
    {
        $type = TypeBillet::where('nom', '!=', 'Gratuit')->get();

        $billet = Billet::with(['typeBillet', 'evenement'])->findOrFail($id);

        if ($billet->evenement->user_id !== Auth::id()) {
            return redirect()->route('myBillet')->with('error', 'Vous n\'êtes pas autorisé à mettre à jour ce billet.');
        }

        if ($billet->evenement->status !== 'inactif' || $billet->status !== 'inactif') {
            return redirect()->route('myBillet')->with('error', 'Ce billet d\'évènement est inactif.');
        }

        if ($billet->typeBillet->nom === 'Gratuit') {
            return redirect()->route('myBillet')->with('error', 'Vous ne pouvez pas mettre à jour un billet Gratuit.');
        }

        $event = Evenement::where('status', 'inactif')
            ->where('user_id', Auth::id())
            ->whereDoesntHave('billets.typeBillet', function ($query) {
                $query->where('nom', 'Gratuit');
            })
            ->get();

        return view('organizer.pages.billet.updatemybillet', compact('billet', 'type', 'event'));
    }

    //Fonction pour modifier un billet d'un user
    public function updateMyBillet(Request $request, $id)
    {
        $request->validate([
            'eve_id' => 'required|exists:evenements,id',
            'typ_id' => 'required|exists:types_billets,id',
            'prix' => 'required|numeric|min:1',
            'quota' => 'required|integer|min:1',
        ], [
            'prix.min' => 'Le prix doit être strictement supérieur à 0.',
            'quota.min' => 'Le quota doit être strictement supérieur à 0.',
        ]);

        $billet = Billet::findOrFail($id);
        $evenement = $billet->evenement;

        if ($evenement->user_id !== Auth::id()) {
            return redirect()->route('myBillet')->with('error', 'Vous n\'êtes pas autorisé à mettre à jour ce billet.');
        }

        if ($evenement->status !== 'inactif' || $billet->status !== 'inactif') {
            return redirect()->route('myBillet')->with('error', 'Seuls les billets inactifs avec un évènement inactif peuvent être mis à jour.');
        }

        if ($billet->typeBillet->nom === 'Gratuit') {
            return redirect()->route('myBillet')->with('error', 'Vous ne pouvez pas mettre à jour un billet Gratuit.');
        }

        $existingBillet = Billet::where('eve_id', $request->input('eve_id'))
            ->where('typ_id', $request->input('typ_id'))
            ->where('id', '!=', $id)
            ->first();

        if ($existingBillet) {
            return redirect()->back()
                ->with('error', 'Ce billet a été déjà ajouté pour cet événement.')
                ->withInput();
        }

        $typeBillet = TypeBillet::findOrFail($request->input('typ_id'));

        if (($typeBillet->nom === 'Groupe' || $typeBillet->nom === 'Famille') && $request->input('nombre') < 2) {
            return redirect()->back()
                ->with('error', 'Le nombre d\'individus doit être supérieur ou égal à 2 pour les billets de type famille ou groupe.')
                ->withInput();
        }

        if ($typeBillet->nom !== 'Groupe' && $typeBillet->nom !== 'Famille' ) {
            $request->merge(['nombre' => 1]);
        }

        $newQuota = $request->input('quota');
        $quotaDifference = $newQuota - $billet->quota;

        if ($quotaDifference > 0) {
            if ($evenement->placeRestant < $quotaDifference) {
                return redirect()->back()
                    ->with('error', 'Pas assez de places restantes pour augmenter le quota.')
                    ->withInput();
            }
            $evenement->placeRestant -= $quotaDifference;
        } else { // On veut diminuer le quota
            $evenement->placeRestant += abs($quotaDifference);
        }

        // Mettre à jour les attributs du billet
        $billet->eve_id = $request->input('eve_id');
        $billet->typ_id = $request->input('typ_id');
        $billet->prix = $request->input('prix');
        $billet->quota = $newQuota;
        $billet->nombre = $request->input('nombre');
        $billet->rest = $newQuota;

        $billet->save();
        $evenement->save();

        return redirect()->route('myBillet')->with('success', 'Votre billet a été mis à jour avec succès.');
    }

    //Fonction de supression de billet d'un user
    public function deleteMyBillet($id)
    {
        $billet = Billet::findOrFail($id);
        $evenement = $billet->evenement;

        if ($evenement->user_id !== Auth::id()) {
            return redirect()->route('myBillet')->with('error', 'Vous n\'êtes pas autorisé à supprimer ce billet.');
        }

        if ($evenement->status !== 'inactif' || $billet->status !== 'inactif') {
            return redirect()->route('myBillet')->with('error', 'Vous ne pouvez pas supprimer un billet déjà disponible.');
        }

        if ($billet->typeBillet->nom === 'Gratuit') {
            return redirect()->route('myBillet')->with('error', 'Vous ne pouvez pas supprimer un billet Gratuit.');
        }

        $quota = $billet->quota;

        $evenement->placeRestant += $quota;
        $evenement->save();

        $billet->delete();

        return redirect()->route('myBillet')->with('success', 'Votre billet a été supprimé avec succès.');
    }

    //Fonction de renvoie de la page de scanner d'évent
    public  function scannerPage()
    {
        return view('organizer.pages.billet.scannerticket');
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategorieEvenement;
use App\Models\Evenement;
use App\Models\Billet;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    //Fonction pour obtenir la liste de catégorie
    public function getCategories()
    {
        $categories = CategorieEvenement::all();
        return response()->json($categories);
    }

    // Fonction permettant de ramener tous les évènements
    public function getAllEvents()
    {
        $events = Evenement::whereIn('status', ['actif', 'fermé', 'terminé'])
            ->with(['user', 'categorie', 'billets.typeBillet'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($events as $event) {
            $this->updateEventAndTicketsIfNecessary($event);
        }

        return response()->json($events);
    }

    // Fonction permettant de faire le tri par catégories d'évènements
    public function getEventsByCategory($categoryId)
    {
        $events = Evenement::whereIn('status', ['actif', 'fermé', 'terminé'])
            ->where('cat_id', $categoryId)
            ->with(['user', 'categorie', 'billets.typeBillet'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($events as $event) {
            $this->updateEventAndTicketsIfNecessary($event);
        }

        return response()->json($events);
    }

    // Fonction pour évènements qui sont les nouveaux évènements
    public function getBestEvents()
    {
        $events = Evenement::whereIn('status', ['actif', 'fermé', 'terminé'])
            ->with(['user', 'categorie', 'billets.typeBillet'])
            ->orderBy('datePublication', 'desc')
            ->get();

        foreach ($events as $event) {
            $this->updateEventAndTicketsIfNecessary($event);
        }

        return response()->json($events);
    }

    private function updateEventAndTicketsIfNecessary($event)
    {
        $tomorrowAfterEventEnd = Carbon::parse($event->dateFin)->addDay()->startOfDay();

        if (Carbon::now()->greaterThanOrEqualTo($tomorrowAfterEventEnd)) {
            $event->status = 'terminé';
            $event->save();

            $billets = Billet::where('eve_id', $event->id)->where('status', 'ouvert')->get();
            foreach ($billets as $billet) {
                $billet->status = 'fermé';
                $billet->save();
            }
        }
    }

}

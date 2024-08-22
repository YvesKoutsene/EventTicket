<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\TypeBillet;
use Illuminate\Http\Request;
use App\Models\Billet;
use Illuminate\Support\Facades\Validator;

class BilletController extends Controller
{
    //Fonction de renvoie de la page ajout de billet
    public function indexBillet()
    {
        $type = TypeBillet::all();
        $event = Evenement::where('status', 'inactif')->get();
        return view('admin.pages.billet.addbillet', compact('type', 'event'));
    }

    //Fonction d'enregistrement de billet
    public function storeBillet(Request $request)
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

        $billet = new Billet();
        $billet->eve_id = $request->input('eve_id');
        $billet->typ_id = $request->input('typ_id');
        $billet->prix = $request->input('prix');
        $billet->quota = $request->input('quota');
        $billet->rest = $request->input('quota');
        $billet->status = 'ouvert';
        $billet->save();

        return redirect()->route('billet')->with('success', 'Billet a été créé avec succès.');
    }

    //Fonction de renvoie de la liste de billet
    public function billetList(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $filter = $request->input('filter', 'all');

        $query = Billet::with(['typeBillet', 'evenement'])
            ->orderBy('created_at', 'desc');

        switch ($filter) {
            case 'type':
                $query->orderBy('typ_id');
                break;
            case 'event':
                $query->orderBy('eve_id');
                break;
            default:
                break;
        }

        $billet = $query->paginate($perPage);

        return view('admin.pages.billet.billetlist', ['billet' => $billet, 'filter' => $filter]);
    }

    //Fonction de renvoie de la page details de billet
    public function indexShowBillet($id)
    {
        $billet = Billet::with(['typeBillet', 'evenement'])->findOrFail($id);

        return view('admin.pages.billet.showbillet', compact('billet'));
    }

}

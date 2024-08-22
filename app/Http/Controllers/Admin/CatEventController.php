<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorieEvenement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatEventController extends Controller
{
    //Fonction de la page ajout de catégorie d'évènements
    public function  indexCatEvent()
    {
        return view('admin.pages.catevent.addcatevent');
    }

    //Fonction de renvoie de la liste de catégorie d'évènement
    public function catEventList(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $categorie = CategorieEvenement::paginate($perPage);

        $query = CategorieEvenement::with(['evenements'])
            ->orderBy('created_at', 'desc');

        return view('admin.pages.catevent.cateventlist', ['categorie' => $categorie]);
    }

    //Fonction d'enregistrement de catégorie d'évènement
    public function storeCatEvent(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|unique:categories_evenements',
            'description' => 'required|string',
        ]);

        $categorie = new CategorieEvenement;
        $categorie->nom = $request->nom;
        $categorie->description = $request->description;
        $categorie->save();

        return redirect()->route('categorie')
            ->with('success', "Categorie d'évènement ajoutée avec succès.");
    }

    //Fonction de renvoie de la ppage mise à jour categorie évènement
    public function  indexUpdateCatEvent($id)
    {
        $categorie = CategorieEvenement::findOrFail($id);
        if ($categorie->evenements()->exists()) {
            return back()
                ->with('error', "Cette catégorie est non modifiable car elle est associée à des évènements.");
        }
        return view('admin.pages.catevent.updatecatevent', compact('categorie'));
    }

    //Fonction de mise à jour de categorie d'évènement
    public function updateCatEvent(Request $request, $id)
    {
        $categorie = CategorieEvenement::findOrFail($id);

        $request->validate([
            'nom' => [
                'required',
                'string',
                Rule::unique('categories_evenements')->ignore($categorie->id),
            ],
            'description' => 'required|string',
        ]);

        if ($categorie->evenements()->exists()) {
            return back()
                ->with('error', "Cette catégorie est non modifiable car elle est associée à des évènements.");
        }
        $categorie->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categorie')
            ->with('success', "Categorie d'évènement mise à jour avec succès.");
    }

    //Fonction de suppression  de categorie d'évènement
    public function deleteCatEvent($id)
    {
        $categorie = CategorieEvenement::findOrFail($id);

        if ($categorie->evenements()->exists()) {
            return redirect()->route('categorie')
                ->with('error', "Cette catégorie est insupprimable car elle est associée à des évènements.");
        }

        $categorie->delete();

        return redirect()->route('categorie')
            ->with('success', "Catégorie d'évènement supprimée avec succès.");
    }

}

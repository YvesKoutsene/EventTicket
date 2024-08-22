<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TypeBillet;
use Illuminate\Validation\Rule;

class TypeBilletController extends Controller
{
    //Fonction de la page ajout du type de billet
    public function  indexTypeBillet()
    {
        return view('admin.pages.typebillet.addtypebillet');
    }

    //Fonction de renvoie de la liste de type billet
    public function typeBilletList(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = TypeBillet::with(['billets']);

        $type = TypeBillet::paginate($perPage);
        return view('admin.pages.typebillet.typebilletlist', ['type' => $type]);
    }

    //Fonction d'enregistrement du type de billet
    public function storeTypeBillet(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|unique:types_billets',
            'description' => 'required|string',
        ]);

        $type = new TypeBillet;
        $type->nom = $request->nom;
        $type->description = $request->description;
        $type->save();

        return redirect()->route('type')
            ->with('success', "Type de billet ajouté avec succès.");
    }

    //Fonction de renvoie de la page de mise à jour type billet
    public function  indexUpdateTypeBillet($id)
    {
        $type = TypeBillet::findOrFail($id);
        if ($type->billets()->exists()) {
            return back()
                ->with('error', "Ce type est non modifiable car il est associé à des billets.");
        }
        return view('admin.pages.typebillet.updatetypebillet', compact('type'));
    }

    //Fonction de mise à jour de type de billet
    public function updateTypeBillet(Request $request, $id)
    {
        $type = TypeBillet::findOrFail($id);

        $request->validate([
            'nom' => [
                'required',
                'string',
                Rule::unique('types_billets')->ignore($type->id),
            ],
            'description' => 'required|string',
        ]);

        if ($type->billets()->exists()) {
            return back()
                ->with('error', "Ce type est non modifiable car il est associé à des billets.");
        }

        $type->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('type')
            ->with('success', "Type de billet mise à jour avec succès.");
    }

    //Fonction de suppression de type de billet
    public function deleteTypeBillet($id)
    {
        $type = TypeBillet::findOrFail($id);

        if ($type->billets()->exists()) {
            return back()
                ->with('error', "Ce type est insupprimable car il est associé à des billets.");
        }
        $type->delete();

        return redirect()->route('type')
            ->with('success', "Type de billet supprimé avec succès.");
    }

}

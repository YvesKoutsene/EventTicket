<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;

class OtherController extends Controller
{
    //Fonction pour obtenir les avis des évènements
    public function eventNoticeList(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $filter = $request->input('filter', 'all');

        $query = Evenement::with(['avis.user'])->orderBy('created_at', 'desc');

        switch ($filter) {
            case 'evenement':
                $query->orderBy('id');
                break;
            case 'participant':
                $query->orderBy('user_id');
                break;
            default:
                break;
        }

        $events = $query->paginate($perPage);

        return view('admin.pages.notice.eventnoticelist', ['events' => $events, 'filter' => $filter]);
    }

    //Fonction de renvoie de la liste de commande des évènements
    public  function  eventOrderList()
    {
     return view('admin.pages.order.eventorderlist');
    }

}

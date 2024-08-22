<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PaymentLinkEmail;
use App\Services\FedaPayService;
use Illuminate\Http\Request;
use App\Models\Evenement;
use App\Models\Billet;
use App\Models\FactureCommande;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevel;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use FedaPay\FedaPay;
use FedaPay\Transaction;

use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentLinkMail;

use FedaPay\Charge;
use FedaPay\Customer;


class BilletController extends Controller
{
    //Fonction de renvoie des billets d'un évènement
    public function getBilletsByEvenement($id)
    {
        $evenement = Evenement::find($id);

        if (!$evenement) {
            return response()->json(['message' => 'Événement non trouvé'], 404);
        }

        if ($evenement->status != 'actif') {
            return response()->json(['message' => 'Cet événement est non actif'], 403);
        }


        $billets = $evenement->billets()->where('status', 'ouvert')->with('typeBillet')->get(); //Where status ouvert à réflechir dessus

        return response()->json($billets);
    }

    //Fonction permettant de ramener les billets d'un évènements
    public function getEvenementBillets($id)
    {
        $evenement = Evenement::find($id);

        if (!$evenement) {
            return response()->json(['message' => 'Événement non trouvé'], 404);
        }

        if ($evenement->status != 'actif') {
            return response()->json(['message' => 'Cet événement est non actif'], 403);
        }

        $billets = $evenement->billets()->where('status', ['ouvert', 'vendu', 'fermé'])->with('typeBillet')->get();

        return response()->json($billets);
    }

    //Fonction permettant d'obtenir un billet gratuit
    public function generateFreeTicketForEvent(Request $request)
    {
        $userId = Auth::id();
        $eventId = $request->input('eventId');

        DB::beginTransaction();

        try {
            $billet = Billet::with(['evenement', 'evenement.categorie'])
                ->where('eve_id', $eventId)
                ->firstOrFail();
            $evenement = $billet->evenement;
            $categorie = $evenement->categorie;

            if ($billet->status !== 'ouvert') {
                return response()->json(['error' => 'Billet non disponible.'], 400);
            }

            $existingTicket = Ticket::whereHas('factureCommande', function ($query) use ($billet, $userId) {
                $query->where('bil_id', $billet->id)
                    ->where('user_id', $userId);
            })->exists();

            if ($existingTicket) {
                return response()->json(['error' => 'Vous avez déjà un billet gratuit pour cet événement.'], 400);
            }

            // Mise à jour du reste des billets
            $billet->rest -= 1;
            if ($billet->rest === 0) {
                $billet->status = 'vendu';
            }
            $billet->save();

            $dateExpiration = Carbon::parse($evenement->dateFin)->addWeek()->format('Y-m-d H:i:s');
            $eventDateBegin = Carbon::parse($evenement->dateDebut);
            $eventDateEnd = Carbon::parse($evenement->dateFin);
            $eventDateExp = Carbon::parse($dateExpiration);

            $facture = FactureCommande::create([
                'bil_id' => $billet->id,
                'nombreTicket' => 1,
                'prixTotal' => 0,
                'user_id' => $userId,
            ]);

            $eventPrefix = strtoupper(substr($evenement->nom, 0, 2));

            do {
                $randomPart = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
                $ticketNumber = $eventPrefix . $randomPart;
            } while (Ticket::where('numero', $ticketNumber)->exists());

            $qrData = [
                'Informations sur le Ticket' => [
                    'Numero du Ticket' => $ticketNumber,
                    'Details de l\'Evenement' => [
                        'Nom' => $evenement->nom,
                        'Type' => $evenement->type,
                        'Categorie' => $categorie->nom,
                        'Date' => [
                            'Debut' => $eventDateBegin->format('d M Y'),
                            'Fin' => $eventDateEnd->format('d M Y'),
                        ],
                        'Heure' => substr($evenement->heure, 0, 5),
                    ],
                ],
                'Informations sur l\'Utilisateur' => [
                    'ID' => $userId,
                    'Nom' => User::find($userId)->name,
                    'Email' => User::find($userId)->email,
                ],
                'Details du Ticket' => [
                    'ID du Billet' => $billet->id,
                    'Billet' => $billet->typeBillet->nom,
                    'Prix' => 0,
                    'Date d\'Expiration' => $eventDateExp->format('d M Y'),
                ],
                'ID de la Facture' => $facture->id
            ];

            \Log::info('QR Data: ', $qrData);

            $qrCode = Builder::create()
                ->writer(new PngWriter())
                ->data(json_encode($qrData))
                ->encoding(new Encoding('UTF-8'))
                ->size(300)
                ->margin(10)
                ->foregroundColor(new Color(0, 0, 0))
                ->backgroundColor(new Color(255, 255, 255))
                ->build();

            $fileName = 'ticket_' . $ticketNumber . '.png';
            $filePath = '/pictures/qrcodes/' . $fileName;

            Storage::disk('public')->put($filePath, $qrCode->getString());

            Ticket::create([
                'fac_id' => $facture->id,
                'numero' => $ticketNumber,
                'status' => 'actif',
                'codeQr' => $filePath,
                'dateExpiration' => $dateExpiration,
            ]);

            DB::commit();

            return response()->json(['message' => 'Billet gratuit généré avec succès'], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur s\'est produite.'], 500);
        }
    }

    // Fonction permettant de passer une commande avec paiement
    public function createOrderAndGenerateTickets(Request $request) {
        $billetId = $request->input('billetId');
        $numberOfTickets = $request->input('numberOfTickets');
        $from_account = $request->input('paymentNumber');
        $secret_code = $request->input('secretCode');
        $to_account = "93816766";
        $amount = $request->input('amount');
        $payment_service = $request->input('paymentProvider');

        DB::beginTransaction();

        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json(['error' => 'Utilisateur non authentifié.'], 401);
            }

            $user = Auth::user();
            // Vérifier le rôle de l'utilisateur
            if ($user->role === 'organizer') {
                return response()->json(['error' => 'Vous n\'êtes pas autorisé à acheter ce billet'], 403);
            }

            $billet = Billet::with(['evenement', 'evenement.categorie'])->findOrFail($billetId);
            $evenement = $billet->evenement;
            $categorie = $evenement->categorie;

            if ($billet->status !== 'ouvert') {
                return response()->json(['error' => 'Billet non disponible.'], 400);
            }

            if ($numberOfTickets > $billet->rest) {
                return response()->json(['error' => 'Nombre de tickets demandé dépasse la disponibilité.'], 400);
            }

            $response = Http::post('http://127.0.0.1:2003/api/simulate-payment', [
                "from_account" => $from_account,
                "secret_code" => $secret_code,
                "to_account" => $to_account,
                "amount" => $amount,
                "payment_service" => $payment_service,
            ]);

            \Log::info('Response from payment API: ' . $response->body());

            // Vérifiez le statut de la réponse
            if ($response->status() !== 200) {
                DB::rollBack();
                $errorResponse = $response->json();

                // Renvoyer les messages d'erreur spécifiques
                return response()->json([
                    'error' => $errorResponse['error'] ?? 'Echec de paiement.'
                ], $response->status());
            }

            $dateExpiration = Carbon::parse($evenement->dateFin)->addWeek()->format('Y-m-d H:i:s');
            $eventDateBegin = Carbon::parse($evenement->dateDebut);
            $eventDateEnd = Carbon::parse($evenement->dateFin);
            $eventDateExp = Carbon::parse($dateExpiration);

            // Création de la facture commande
            $facture = FactureCommande::create([
                'bil_id' => $billetId,
                'nombreTicket' => $numberOfTickets,
                'prixTotal' => $numberOfTickets * $billet->prix,
                'user_id' => $userId,
            ]);

            $billet->rest -= $numberOfTickets;
            if ($billet->rest === 0) {
                $billet->status = 'vendu';
            }
            $billet->save();

            // Générer les tickets
            for ($i = 0; $i < $numberOfTickets; $i++) {
                $eventPrefix = strtoupper(substr($evenement->nom, 0, 2));

                do {
                    $randomPart = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
                    $ticketNumber = $eventPrefix . $randomPart;
                } while (Ticket::where('numero', $ticketNumber)->exists());

                $qrData = [
                    'Informations sur le Ticket' => [
                        'Numero du Ticket' => $ticketNumber,
                        'Details de l\'Evenement' => [
                            'Nom' => $evenement->nom,
                            'Type' => $evenement->type,
                            'Categorie' => $categorie->nom,
                            'Date' => [
                                'Debut' => $eventDateBegin->format('d M Y'),
                                'Fin' => $eventDateEnd->format('d M Y'),
                            ],
                            'Heure' => substr($evenement->heure, 0, 5),
                        ],
                    ],
                    'Informations sur l\'Utilisateur' => [
                        'ID' => $userId,
                        'Nom' => Auth::user()->name,
                        'Email' => Auth::user()->email,
                    ],
                    'Details du Ticket' => [
                        'ID du Billet' => $billetId,
                        'Billet' => $billet->typeBillet->nom,
                        'Prix' => $billet->prix,
                        'Date d\'Expiration' => $eventDateExp->format('d M Y'),
                    ],
                    'ID de la Facture' => $facture->id
                ];

                $qrCode = Builder::create()
                    ->writer(new PngWriter())
                    ->data(json_encode($qrData))
                    ->encoding(new Encoding('UTF-8'))
                    ->size(300)
                    ->margin(10)
                    ->foregroundColor(new Color(0, 0, 0))
                    ->backgroundColor(new Color(255, 255, 255))
                    ->build();

                $fileName = 'ticket_' . $ticketNumber . '.png';
                $filePath = '/pictures/qrcodes/' . $fileName;

                Storage::disk('public')->put($filePath, $qrCode->getString());

                Ticket::create([
                    'fac_id' => $facture->id,
                    'numero' => $ticketNumber,
                    'status' => 'actif',
                    'codeQr' => $filePath,
                    'dateExpiration' => $dateExpiration,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Commande effectuée avec succès'], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur s\'est produite.'], 500);
        }
    }

    //Fonction permettant d'afficher les tickets d'un utilisateur
    public function getUserTicketsWithDetails()
    {
        $userId = auth()->id();

        $tickets = Ticket::with([
            'factureCommande.billet.evenement.categorie',
            'factureCommande.billet.typeBillet'
        ])
            ->whereHas('factureCommande', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $currentDate = now();

        foreach ($tickets as $ticket) {
            if ($ticket->status === 'actif' && $ticket->dateExpiration < $currentDate) {
                $ticket->status = 'expiré';
                $ticket->save();
            }
        }

        return response()->json($tickets);
    }

    //Essaie
   /* public function createOrderAndGenerateTickets(Request $request) {
        $billetId = $request->input('billetId');
        $numberOfTickets = $request->input('numberOfTickets');
        $amount = $request->input('amount');

        DB::beginTransaction();

        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json(['error' => 'Utilisateur non authentifié.'], 401);
            }

            $billet = Billet::with(['evenement', 'evenement.categorie'])->findOrFail($billetId);
            $evenement = $billet->evenement;
            $categorie = $evenement->categorie;

            if ($billet->status !== 'ouvert') {
                return response()->json(['error' => 'Billet non disponible.'], 400);
            }

            if ($numberOfTickets > $billet->rest) {
                return response()->json(['error' => 'Nombre de tickets demandé dépasse la disponibilité.'], 400);
            }

            // Création de la facture commande
            $facture = FactureCommande::create([
                'bil_id' => $billetId,
                'nombreTicket' => $numberOfTickets,
                'prixTotal' => $numberOfTickets * $billet->prix,
                'user_id' => $userId,
            ]);

            $billet->rest -= $numberOfTickets;
            if ($billet->rest === 0) {
                $billet->status = 'vendu';
            }
            $billet->save();

            // Générer les tickets
            for ($i = 0; $i < $numberOfTickets; $i++) {
                $eventPrefix = strtoupper(substr($evenement->nom, 0, 2));

                do {
                    $randomPart = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
                    $ticketNumber = $eventPrefix . $randomPart;
                } while (Ticket::where('numero', $ticketNumber)->exists());

                $qrData = [
                    'Informations sur le Ticket' => [
                        'Numero du Ticket' => $ticketNumber,
                        'Details de l\'Evenement' => [
                            'Nom' => $evenement->nom,
                            'Type' => $evenement->type,
                            'Categorie' => $categorie->nom,
                            'Date' => [
                                'Debut' => $eventDateBegin->format('d M Y'),
                                'Fin' => $eventDateEnd->format('d M Y'),
                            ],
                            'Heure' => substr($evenement->heure, 0, 5),
                        ],
                    ],
                    'Informations sur l\'Utilisateur' => [
                        'ID' => $userId,
                        'Nom' => Auth::user()->name,
                        'Email' => Auth::user()->email,
                    ],
                    'Details du Ticket' => [
                        'ID du Billet' => $billetId,
                        'Billet' => $billet->typeBillet->nom,
                        'Prix' => $billet->prix,
                        'Date d\'Expiration' => $eventDateExp->format('d M Y'),
                    ],
                    'ID de la Facture' => $facture->id
                ];

                $qrCode = Builder::create()
                    ->writer(new PngWriter())
                    ->data(json_encode($qrData))
                    ->encoding(new Encoding('UTF-8'))
                    ->size(300)
                    ->margin(10)
                    ->foregroundColor(new Color(0, 0, 0))
                    ->backgroundColor(new Color(255, 255, 255))
                    ->build();

                $fileName = 'ticket_' . $ticketNumber . '.png';
                $filePath = '/pictures/qrcodes/' . $fileName;

                Storage::disk('public')->put($filePath, $qrCode->getString());

                Ticket::create([
                    'fac_id' => $facture->id,
                    'numero' => $ticketNumber,
                    'status' => 'actif',
                    'codeQr' => $filePath,
                    'dateExpiration' => $dateExpiration,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Commande effectuée avec succès.'], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur s\'est produite.'], 500);
        }
    }*/

    //Un

    //Fonction permettant de se faire rembourser
    public function refundTicket(Request $request)
    {
        $ticketNumber = $request->input('ticketNumber');
        $refundAccount = $request->input('refundAccount');
        $payment_service = 'Tmoney';
        $to_account = "98811314";
        $secret_code = "45601";

        DB::beginTransaction();

        try {
            $ticket = Ticket::where('numero', $ticketNumber)->first();

            if (!$ticket) {
                return response()->json(['error' => 'Ticket non trouvé.'], 404);
            }

            if ($ticket->status == 'remboursé') {
                return response()->json(['error' => 'Ce ticket déjà remboursé.'], 400);
            }

            if ($ticket->status !== 'annulé') {
                return response()->json(['error' => 'Ce ticket n\'est pas annulé.'], 400);
            }

            // Récupération du billet associé
            $billet = $ticket->factureCommande->billet;

            // Vérifier si le billet est gratuit
            if ($billet->prix == 0) {
                return response()->json(['error' => 'Impossible de rembourser un ticket gratuit.'], 400);
            }

            $response = Http::post('http://127.0.0.1:2003/api/simulate-payment', [
                "payment_service" => $payment_service,
                "from_account" => $to_account,
                "secret_code" => $secret_code,
                "to_account" => $refundAccount,
                "amount" => $billet->prix,
            ]);

            \Log::info('Response from refund API: ' . $response->body());

            // Vérifiez le statut de la réponse
            if ($response->status() !== 200) {
                DB::rollBack();
                $errorResponse = $response->json();

                // Renvoyer les messages d'erreur spécifiques
                return response()->json([
                    'error' => $errorResponse['error'] ?? 'Échec du remboursement.'
                ], $response->status());
            }

            // Mise à jour du statut du ticket à "remboursé"
            $ticket->status = 'remboursé';
            $ticket->save();

            DB::commit();

            return response()->json(['message' => 'Remboursement effectué avec succès'], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur s\'est produite.'], 500);
        }
    }


}

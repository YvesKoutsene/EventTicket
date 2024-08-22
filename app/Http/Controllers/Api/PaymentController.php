<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
namespace App\Http\Controllers;

use App\Services\FedaPayService;
use Illuminate\Http\Request;
use FedaPay\FedaPay;

class PaymentController extends Controller
{
    // Configurez l'API Key et l'environnement
    private $apiKey = "sk_live_1tMPXe6n0SGH7Rv0egp7GYAj";
    private $environment = 'live'; // ou 'live'

    public function __construct()
    {
        // Configurez l'API Key et l'environnement pour FedaPay
        FedaPay::setApiKey($this->apiKey);
        FedaPay::setEnvironment($this->environment);
    }

    /**
     * Crée une nouvelle transaction.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTransaction(Request $request)
    {
        $description = $request->input('description');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $callbackUrl = $request->input('callback_url');
        $customer = $request->input('customer');

        $transaction = \FedaPay\Transaction::create([
            "description" => $description,
            "amount" => $amount,
            "currency" => ["iso" => $currency],
            "callback_url" => $callbackUrl,
            "customer" => $customer
        ]);

        return response()->json($transaction);
    }

    /**
     * Génère le lien de paiement pour une transaction donnée.
     *
     * @param string $transactionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generatePaymentLink($transactionId)
    {
        $transaction = \FedaPay\Transaction::retrieve($transactionId);
        $token = $transaction->generateToken();

        return redirect($token->url);
    }

    /**
     * Récupère les détails d'une transaction.
     *
     * @param string $transactionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionDetails($transactionId)
    {
        $transaction = \FedaPay\Transaction::retrieve($transactionId);

        return response()->json($transaction);
    }

}

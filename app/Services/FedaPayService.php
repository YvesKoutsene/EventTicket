<?php
namespace App\Services;

use GuzzleHttp\Client;

class FedaPayService
{
    protected $client;
    protected $publicKey;
    protected $secretKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->publicKey = env('FEDAPAY_PUBLIC_KEY');
        $this->secretKey = env('FEDAPAY_SECRET_KEY');
    }

    public function createPayment($amount, $currency, $description)
    {
        $response = $this->client->post('https://api.fedapay.com/v1/charges', [
            'json' => [
                'amount' => $amount,
                'currency' => $currency,
                'description' => $description,
                'public_key' => $this->publicKey,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->secretKey,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}

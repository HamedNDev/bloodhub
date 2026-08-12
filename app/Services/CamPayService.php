<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CamPayService
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = config('campay.base_url');
        $this->token   = config('campay.token');
    }

    private function http()
    {
        return Http::withoutVerifying()->withHeaders([
            'Authorization' => "Token {$this->token}",
            'Content-Type'  => 'application/json',
        ]);
    }

    /**
     * Initier une demande de paiement (collect)
     */
    public function collect(array $data): array
    {
        $response = $this->http()->post("{$this->baseUrl}/collect/", [
            'amount'             => (string) $data['amount'],
            'currency'           => 'XAF',
            'from'               => $data['phone'],
            'description'        => $data['description'],
            'external_reference' => $data['reference'],
        ]);

        if ($response->failed()) {
            throw new \Exception('CamPay collect échoué: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Générer un lien de paiement
     */
    public function getPaymentLink(array $data): array
    {
        $response = $this->http()->post("{$this->baseUrl}/get_payment_link/", [
            'amount'               => (string) $data['amount'],
            'currency'             => 'XAF',
            'description'          => $data['description'],
            'external_reference'   => $data['reference'],
            'first_name'           => $data['first_name'] ?? '',
            'last_name'            => $data['last_name'] ?? '',
            'email'                => $data['email'] ?? '',
            'from'                 => $data['phone'] ?? '',
            'redirect_url'         => config('campay.redirect_url'),
            'failure_redirect_url' => config('campay.failure_url'),
            'payment_options'      => 'MOMO',
        ]);

        if ($response->failed()) {
            throw new \Exception('CamPay payment link échoué: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Vérifier le statut d'une transaction
     */
    public function getTransactionStatus(string $reference): array
    {
        $response = $this->http()->get("{$this->baseUrl}/transaction/{$reference}/");

        if ($response->failed()) {
            throw new \Exception('CamPay status échoué: ' . $response->body());
        }

        return $response->json();
    }
}


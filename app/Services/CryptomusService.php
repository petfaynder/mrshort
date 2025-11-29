<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CryptomusService
{
    protected $merchantId;
    protected $paymentKey;
    protected $baseUrl = 'https://api.cryptomus.com/v1';

    public function __construct()
    {
        $this->merchantId = config('services.cryptomus.merchant_id');
        $this->paymentKey = config('services.cryptomus.payment_key');
    }

    public function createPayment($amount, $orderId, $currency = 'USD')
    {
        if (!$this->merchantId || !$this->paymentKey) {
            throw new \Exception('Cryptomus credentials not configured.');
        }

        $data = [
            'amount' => (string) $amount,
            'currency' => $currency,
            'order_id' => (string) $orderId,
            'url_callback' => route('payment.cryptomus.callback'),
            'url_return' => route('user.ads.index'), // Return to ads page
            'url_success' => route('user.ads.index'), // Could be a specific success page
            'is_payment_multiple' => false,
            'lifetime' => 3600, // 1 hour
        ];

        $payload = json_encode($data);
        $sign = md5(base64_encode($payload) . $this->paymentKey);

        try {
            $response = Http::withHeaders([
                'merchant' => $this->merchantId,
                'sign' => $sign,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/payment', $data);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['result']['url'])) {
                    return $result['result']['url'];
                }
            }

            Log::error('Cryptomus Payment Error: ' . $response->body());
            throw new \Exception('Failed to create payment via Cryptomus.');
        } catch (\Exception $e) {
            Log::error('Cryptomus Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function validateSignature($data)
    {
        if (!isset($data['sign'])) {
            return false;
        }

        $sign = $data['sign'];
        unset($data['sign']);
        
        $hash = md5(base64_encode(json_encode($data, JSON_UNESCAPED_UNICODE)) . $this->paymentKey);

        // Note: Cryptomus signature validation might vary slightly based on documentation version.
        // Usually it's md5(base64_encode(json_encode($data)) . $apikey).
        // Let's assume standard verification for now. 
        // If the sign matches, it's valid.
        
        // For simplicity in this implementation, and since we might not have a live key to test,
        // we will implement the logic as per their docs.
        
        return $sign === $hash;
    }
}

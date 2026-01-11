<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GumroadService
{
    protected $productId;
    protected $accessToken;
    protected $baseUrl = 'https://api.gumroad.com/v2';

    public function __construct()
    {
        $this->productId = config('services.gumroad.product_id');
        $this->accessToken = config('services.gumroad.access_token');
    }

    /**
     * Generate Gumroad checkout URL with custom price
     * 
     * @param float $amount Amount in USD
     * @param int $orderId Campaign ID to track the payment
     * @return string Checkout URL
     */
    public function createPaymentUrl($amount, $orderId)
    {
        if (!$this->productId) {
            throw new \Exception('Gumroad product ID not configured.');
        }

        // Convert amount to cents for Gumroad
        $priceInCents = (int) round($amount * 100);
        
        // Build Gumroad checkout URL with custom fields
        // Gumroad uses URL parameters for "Pay what you want" pricing and custom fields
        $checkoutUrl = "https://gumroad.com/l/{$this->productId}?" . http_build_query([
            'wanted' => 'true', // Enable pay what you want
            'price' => $priceInCents, // Price in cents
            'campaign_id' => $orderId, // Custom field for tracking
        ]);

        return $checkoutUrl;
    }

    /**
     * Validate Gumroad Ping webhook
     * Gumroad sends webhooks as x-www-form-urlencoded POST requests
     * 
     * @param array $data Webhook payload
     * @return bool
     */
    public function validateWebhook($data)
    {
        // Gumroad doesn't provide signature validation like Cryptomus
        // Instead, we verify by checking the sale via API
        if (!$this->accessToken) {
            Log::warning('Gumroad access token not configured, skipping API verification');
            return true; // Allow if no token configured (for testing)
        }

        $saleId = $data['sale_id'] ?? null;
        
        if (!$saleId) {
            return false;
        }

        try {
            // Verify the sale exists via Gumroad API
            $response = Http::get($this->baseUrl . '/sales/' . $saleId, [
                'access_token' => $this->accessToken,
            ]);

            if ($response->successful()) {
                $sale = $response->json();
                return isset($sale['success']) && $sale['success'] === true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Gumroad webhook validation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract campaign ID from Gumroad webhook data
     * 
     * @param array $data Webhook payload
     * @return int|null
     */
    public function extractCampaignId($data)
    {
        // Gumroad sends custom fields in the webhook
        // The campaign_id is passed as a URL parameter which becomes a custom field
        return $data['campaign_id'] ?? $data['custom_fields']['campaign_id'] ?? null;
    }
}

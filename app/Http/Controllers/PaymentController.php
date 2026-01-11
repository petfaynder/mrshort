<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CryptomusService;
use App\Services\GumroadService;
use App\Models\AdCampaign;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $cryptomusService;
    protected $gumroadService;

    public function __construct(CryptomusService $cryptomusService, GumroadService $gumroadService)
    {
        $this->cryptomusService = $cryptomusService;
        $this->gumroadService = $gumroadService;
    }

    public function cryptomusCallback(Request $request)
    {
        $data = $request->all();

        // Validate Signature - CRITICAL for production security
        if (!$this->cryptomusService->validateSignature($data)) {
            Log::warning('Cryptomus invalid signature', $data);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $orderId = $data['order_id'] ?? null;
        $status = $data['status'] ?? null; // paid, paid_over, wrong_amount, process, fail, cancel, system_fail, refund_process, refund_fail, paid_late

        if (!$orderId) {
            return response()->json(['error' => 'Order ID missing'], 400);
        }

        $campaign = AdCampaign::find($orderId);

        if (!$campaign) {
            Log::error("Campaign not found for order ID: $orderId");
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        if (in_array($status, ['paid', 'paid_over'])) {
            $campaign->update([
                'payment_status' => 'paid',
                'payment_provider' => 'cryptomus',
                'external_payment_id' => $data['uuid'] ?? null,
                // 'is_active' => false // Still waiting for admin approval, or set to true if auto-approve
            ]);

            // Notify Admin or User if needed
            Log::info("Campaign #{$campaign->id} paid via Cryptomus.");
        } elseif (in_array($status, ['fail', 'cancel', 'system_fail'])) {
             $campaign->update([
                'payment_status' => 'failed',
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Handle Gumroad Ping webhook
     * Gumroad sends webhooks as x-www-form-urlencoded POST requests
     */
    public function gumroadCallback(Request $request)
    {
        $data = $request->all();

        Log::info('Gumroad webhook received', $data);

        // Extract campaign ID from custom fields
        $campaignId = $this->gumroadService->extractCampaignId($data);

        if (!$campaignId) {
            Log::error('Gumroad webhook: Campaign ID missing', $data);
            return response()->json(['error' => 'Campaign ID missing'], 400);
        }

        $campaign = AdCampaign::find($campaignId);

        if (!$campaign) {
            Log::error("Gumroad webhook: Campaign not found for ID: $campaignId");
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        // Check if this is a sale event (not refund, dispute, etc.)
        $refunded = $data['refunded'] ?? false;
        $disputed = $data['disputed'] ?? false;
        $chargebacked = $data['chargebacked'] ?? false;

        if ($refunded || $disputed || $chargebacked) {
            $campaign->update([
                'payment_status' => 'failed',
                'payment_provider' => 'gumroad',
            ]);
            Log::warning("Campaign #{$campaign->id} payment issue via Gumroad: refunded=$refunded, disputed=$disputed, chargebacked=$chargebacked");
            return response()->json(['status' => 'ok']);
        }

        // Validate the webhook (optional - verify via API)
        if (!$this->gumroadService->validateWebhook($data)) {
            Log::warning('Gumroad webhook validation failed', $data);
            // Continue anyway for now, but log the warning
        }

        // Update campaign as paid
        $campaign->update([
            'payment_status' => 'paid',
            'payment_provider' => 'gumroad',
            'external_payment_id' => $data['sale_id'] ?? $data['purchase_id'] ?? null,
        ]);

        Log::info("Campaign #{$campaign->id} paid via Gumroad.");

        return response()->json(['status' => 'ok']);
    }
}


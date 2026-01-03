<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CryptomusService;
use App\Models\AdCampaign;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $cryptomusService;

    public function __construct(CryptomusService $cryptomusService)
    {
        $this->cryptomusService = $cryptomusService;
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
}

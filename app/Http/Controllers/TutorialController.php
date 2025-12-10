<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TutorialController extends Controller
{
    /**
     * Mark the tutorial as completed for the authenticated user.
     */
    public function complete(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if ($user) {
            $user->tutorial_completed_at = now();
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Tutorial completed successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated'
        ], 401);
    }
}

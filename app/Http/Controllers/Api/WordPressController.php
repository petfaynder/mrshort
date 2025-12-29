<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WordPressController extends Controller
{
    /**
     * Get link data for WordPress plugin.
     * GET /api/wp/link/{code}
     */
    public function getLink(Request $request, string $code)
    {
        // Validate API token
        $token = $request->header('X-WP-Token');
        $validToken = setting('wordpress_api_token');
        
        if (!$validToken || $token !== $validToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API token'
            ], 401);
        }
        
        $link = Link::where('code', $code)->first();
        
        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Link not found'
            ], 404);
        }
        
        // Get WordPress settings
        $pagesCount = (int) setting('wordpress_pages_count', 2);
        $waitTime = (int) setting('wordpress_wait_time', 5);
        
        return response()->json([
            'success' => true,
            'link' => [
                'code' => $link->code,
                'original_url' => $link->original_url,
                'title' => $link->title,
            ],
            'settings' => [
                'pages_count' => $pagesCount,
                'wait_time' => $waitTime,
            ],
            'redirect_to' => url('/' . $link->code),
        ]);
    }
    
    /**
     * Record click from WordPress.
     * POST /api/wp/click
     */
    public function recordClick(Request $request)
    {
        // Validate API token
        $token = $request->header('X-WP-Token');
        $validToken = setting('wordpress_api_token');
        
        if (!$validToken || $token !== $validToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API token'
            ], 401);
        }
        
        $request->validate([
            'code' => 'required|string',
            'ip' => 'nullable|ip',
            'country' => 'nullable|string|max:10',
            'pages_viewed' => 'nullable|integer',
        ]);
        
        $link = Link::where('code', $request->input('code'))->first();
        
        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Link not found'
            ], 404);
        }
        
        // Log WordPress click for analytics
        Log::info('WordPress click received', [
            'code' => $request->input('code'),
            'ip' => $request->input('ip'),
            'country' => $request->input('country'),
            'pages_viewed' => $request->input('pages_viewed'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Click recorded',
        ]);
    }
    
    /**
     * Test connection from WordPress plugin.
     * GET /api/wp/test
     */
    public function testConnection(Request $request)
    {
        // Validate API token
        $token = $request->header('X-WP-Token');
        $validToken = setting('wordpress_api_token');
        
        if (!$validToken || $token !== $validToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API token'
            ], 401);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Connection successful',
            'settings' => [
                'pages_count' => (int) setting('wordpress_pages_count', 2),
                'wait_time' => (int) setting('wordpress_wait_time', 5),
            ],
        ]);
    }
}

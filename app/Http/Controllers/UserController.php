<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function fullPageScript()
    {
        // Kullanıcının kimliğini doğrula
        if (!Auth::check()) {
            abort(403, 'Unauthorized'); // Veya başka bir hata/yönlendirme
        }

        // Full Page Script view'ını döndür
        return view('user.tools.full-page-script');
    }

    public function bookmarkletScript()
    {
        // Kullanıcının kimliğini doğrula
        if (!Auth::check()) {
            abort(403, 'Unauthorized'); // Veya başka bir hata/yönlendirme
        }

        // Bookmarklet Script view'ını döndür
        return view('user.tools.bookmarklet-script');
    }
}

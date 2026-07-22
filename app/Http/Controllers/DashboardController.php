<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Eksekutif.
     */
    public function index(): View | RedirectResponse
    {
        $user = Auth::guard('web')->user();

        /*
         * Proteksi tambahan apabila dashboard diakses
         * tanpa session login.
         */
        if ($user === null) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Silakan login terlebih dahulu.',
                ]);
        }

        return view('dashboard', [
            'user' => $user,
        ]);
    }
}
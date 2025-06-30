<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reseller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ResellerAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Session::has('reseller_kode')) {
            return redirect()->route('dashboard');
        }

        return view('auth.reseller-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'kode' => 'required|string',
            'pin'  => 'required|string',
            'g-recaptcha-response' => 'required',
        ],[
    'g-recaptcha-response.required' => 'Captcha wajib diisi.',
]);

        // ✅ Validasi captcha manual tanpa package
        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (! $captchaResponse->json('success')) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Captcha tidak valid atau expired.'
            ])->withInput();
        }

        // ✅ Rate limiting
        $key = $this->throttleKey($request);
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'login' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."
            ]);
        }

        // ✅ Validasi kode dan pin
        $reseller = Reseller::where('kode', $request->kode)->first();

        if ($reseller && $reseller->pin === $request->pin) {
            RateLimiter::clear($key); // reset percobaan
            Session::put('reseller_kode', $reseller->kode);
            return redirect()->route('dashboard');
        }

        RateLimiter::hit($key, 60); // simpan gagal login 60 detik
        return back()->withErrors(['login' => 'Kode atau PIN salah'])->withInput();
    }

    public function logout()
    {
        Session::forget('reseller_kode');
        return redirect()->route('reseller.login');
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('kode')) . '|' . $request->ip();
    }
}

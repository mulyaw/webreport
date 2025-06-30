<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Reseller - Paynara</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <style>
    body {
      background: linear-gradient(135deg, #6EE7B7, #3B82F6, #9333EA);
      background-size: 300% 300%;
      animation: bgFlow 20s ease infinite;
    }

    @keyframes bgFlow {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    @keyframes fadeSlideUp {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    .animate-in {
      animation: fadeSlideUp 0.7s ease forwards;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-8px); }
      50% { transform: translateX(8px); }
      75% { transform: translateX(-4px); }
    }

    .shake {
      animation: shake 0.4s ease-in-out;
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen font-sans">

  <div
    x-data="{ shake: false, shakeCaptcha: false, loading: false }"
    x-init="
      @if($errors->has('login')) shake = true; setTimeout(() => shake = false, 500); @endif
      @if($errors->has('g-recaptcha-response')) shakeCaptcha = true; setTimeout(() => shakeCaptcha = false, 500); @endif
    "
    :class="{ 'shake': shake || shakeCaptcha }"
    class="bg-white shadow-xl rounded-xl w-full max-w-sm p-6 animate-in"
  >

    <!-- Logo -->
    <div class="flex justify-center mb-4 opacity-0 animate-in" style="animation-delay: 0.3s; animation-fill-mode: forwards;">
      <img src="/images/logo.png" alt="Paynara Logo" class="w-16 h-16 object-contain" onerror="this.style.display='none'">
    </div>

    <h2 class="text-xl font-semibold text-center text-gray-800 mb-4 animate-in" style="animation-delay: 0.5s; animation-fill-mode: forwards;">Login Reseller</h2>

    @if($errors->has('login'))
      <div class="bg-red-100 text-red-600 text-sm p-2 rounded mb-4 text-center animate-in" style="animation-delay: 0.6s; animation-fill-mode: forwards;">
        {{ $errors->first('login') }}
      </div>
    @endif

    <form method="POST" action="{{ route('reseller.login.submit') }}" @submit="loading = true" class="space-y-4">
      @csrf

      <!-- Kode Reseller -->
      <div class="relative animate-in" style="animation-delay: 0.7s; animation-fill-mode: forwards;">
        <input
          type="text"
          name="kode"
          id="kode"
          required
          maxlength="10"
          placeholder=" "
          class="peer w-full px-3 pt-5 pb-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
        >
        <label for="kode"
          class="absolute left-3 top-2 text-gray-500 text-xs transition-all
                 peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400
                 peer-focus:top-2 peer-focus:text-xs peer-focus:text-blue-600">
          Kode Reseller
        </label>
      </div>

      <!-- PIN -->
      <div class="relative animate-in" style="animation-delay: 0.8s; animation-fill-mode: forwards;">
        <input
          type="password"
          name="pin"
          id="pin"
          required
          maxlength="10"
          placeholder=" "
          class="peer w-full px-3 pt-5 pb-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
        >
        <label for="pin"
          class="absolute left-3 top-2 text-gray-500 text-xs transition-all
                 peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400
                 peer-focus:top-2 peer-focus:text-xs peer-focus:text-blue-600">
          PIN
        </label>
      </div>

      <!-- Google reCAPTCHA -->
      <div class="animate-in" style="animation-delay: 0.9s; animation-fill-mode: forwards;">
        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
        @if ($errors->has('g-recaptcha-response'))
          <div class="text-sm text-red-500 mt-2">
            {{ $errors->first('g-recaptcha-response') }}
          </div>
        @endif
      </div>

      <!-- Tombol Login -->
      <button type="submit"
        :disabled="loading"
        class="w-full flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-1.5 text-sm rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed animate-in"
        style="animation-delay: 1s; animation-fill-mode: forwards;"
      >
        <template x-if="!loading">
          <span>LOGIN</span>
        </template>
        <template x-if="loading">
          <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
          </svg>
        </template>
      </button>
    </form>

  </div>

</body>
</html>

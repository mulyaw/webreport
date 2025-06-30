<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Reseller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white p-6 rounded-2xl shadow-md w-full max-w-xl text-center">
        <h1 class="text-2xl font-bold text-blue-600 mb-4">Selamat Datang!</h1>
        <p class="text-gray-700 mb-6">Anda berhasil login sebagai reseller.</p>

        <form method="POST" action="{{ route('reseller.logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                Logout
            </button>
        </form>
    </div>
</body>
</html>

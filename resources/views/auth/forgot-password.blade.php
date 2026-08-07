<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password - SIPRAS</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { ink: '#12302C', paper: '#F6F4EF', brand: { DEFAULT: '#0F766E', dark: '#0B5D57' } },
            fontFamily: { serif: ['Fraunces', 'serif'], sans: ['Inter', 'sans-serif'] }
        }}}
    </script>
</head>
<body class="bg-paper min-h-screen flex items-center justify-center p-4 font-sans text-ink">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        <div class="w-12 h-12 bg-brand/10 text-brand rounded-full flex items-center justify-center mb-4">
            <i class="ti ti-lock-question text-2xl"></i>
        </div>
        <h2 class="font-serif text-2xl font-semibold mb-1">Lupa Password</h2>
        <p class="text-gray-500 mb-6 text-sm">Masukkan username/NIM Anda, sistem akan membuatkan link reset password.</p>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label class="block text-sm font-medium mb-1">Username / NIM</label>
            <input type="text" name="username" value="{{ old('username') }}" placeholder="Contoh: 2021010234"
                class="w-full border rounded-lg px-4 py-2 mb-6 focus:outline-none focus:ring-2 focus:ring-brand" required autofocus>

            <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white font-semibold py-2.5 rounded-lg transition">
                Buat Link Reset
            </button>
        </form>
        <a href="{{ route('login') }}" class="block text-center text-sm text-gray-500 hover:text-brand mt-4">← Kembali ke Login</a>
    </div>
</body>
</html>
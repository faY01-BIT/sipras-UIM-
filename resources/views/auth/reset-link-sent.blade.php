<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Link Reset Dibuat - SIPRAS</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { ink: '#12302C', paper: '#F6F4EF', brand: { DEFAULT: '#0F766E', dark: '#0B5D57', light: '#DCEEEC' } },
            fontFamily: { serif: ['Fraunces', 'serif'], sans: ['Inter', 'sans-serif'] }
        }}}
    </script>
</head>
<body class="bg-paper min-h-screen flex items-center justify-center p-4 font-sans text-ink">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-14 h-14 bg-brand-light text-brand-dark rounded-full flex items-center justify-center mx-auto mb-4">
            <x-icon name="mail-check" size="24" />
        </div>
        <h2 class="font-serif text-2xl font-semibold mb-1">Link Reset Siap</h2>
        <p class="text-gray-500 mb-6 text-sm">Karena sistem berjalan secara lokal, link reset ditampilkan langsung di sini (bukan dikirim email).</p>

        <a href="{{ route('password.reset', $token) }}" class="block w-full bg-brand hover:bg-brand-dark text-white font-semibold py-2.5 rounded-lg transition mb-3">
            Lanjutkan Reset Password →
        </a>
        <a href="{{ route('login') }}" class="block text-center text-sm text-gray-500 hover:text-brand">Batal, kembali ke Login</a>
    </div>
</body>
</html>
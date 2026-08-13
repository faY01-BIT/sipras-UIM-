<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPRAS</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: {
                ink: '#12302C', paper: '#F6F4EF',
                brand: { DEFAULT: '#0F766E', dark: '#0B5D57', light: '#DCEEEC' },
                gold: { DEFAULT: '#F2C230', text: '#8A6417', bg: '#FCF0CE', border: '#EFD9A8' },
            },
            fontFamily: {
                serif: ['Fraunces', 'serif'], sans: ['Inter', 'sans-serif'], mono: ['"IBM Plex Mono"', 'monospace'],
            }
        }}}
    </script>
</head>
<body class="bg-paper min-h-screen flex items-center justify-center p-4 font-sans text-ink">
    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
        <div class="md:w-1/2 bg-ink text-white p-10 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-gold rounded-lg flex items-center justify-center">
                        <x-icon name="flame" size="18" class="text-ink" />
                    </div>
                    <div>
                        <div class="font-serif font-semibold">SIPRAS</div>
                        <div class="text-xs text-white/50">Sistem Inventaris Sarana & Prasarana</div>
                    </div>
                </div>
                <h1 class="font-serif text-3xl font-semibold mb-4 leading-tight">Satu Platform untuk <span class="text-gold">Semua</span> Inventaris</h1>
                <p class="text-white/70 mb-8">Kelola barang, pantau peminjaman, dan buat laporan secara real-time dari satu tempat.</p>
            </div>
            <ul class="space-y-3 text-sm text-white/80">
                <li class="flex items-center gap-2"><x-icon name="box" class="text-gold" /> Manajemen Inventaris Real-time</li>
                <li class="flex items-center gap-2"><x-icon name="clipboard-list" class="text-gold" /> Pengajuan & Konfirmasi Peminjaman</li>
                <li class="flex items-center gap-2"><x-icon name="file-report" class="text-gold" /> Laporan Otomatis & Terstruktur</li>
            </ul>
        </div>

        <div class="md:w-1/2 p-10">
            <h2 class="font-serif text-2xl font-semibold mb-1">Masuk ke Akun</h2>
            <p class="text-gray-500 mb-6 text-sm">Masukkan username/NIM dan password Anda</p>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg flex items-center gap-2">
                    <x-icon name="alert-circle" /> {{ $errors->first() }}
                </div>
            @endif
            @if (session('success'))
                <div class="mb-4 p-3 bg-brand-light text-brand-dark text-sm rounded-lg flex items-center gap-2">
                    <x-icon name="circle-check" /> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label class="block text-sm font-medium mb-1">Username / NIM</label>
                <input type="text" name="username" placeholder="Contoh: 2021010234" value="{{ old('username') }}"
                    class="w-full border rounded-lg px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-brand" required autofocus>

                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium">Password</label>
                    <a href="{{ route('password.request') }}" class="text-xs text-brand hover:underline">Lupa password?</a>
                </div>
                <input type="password" name="password" placeholder="Masukkan password Anda"
                    class="w-full border rounded-lg px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-brand" required>

                <label class="block text-sm font-medium mb-1">Kode Keamanan</label>
                <div class="flex items-center gap-3 mb-6">
                    <div class="select-none px-4 py-2 bg-gold-bg border border-gold-border rounded-lg font-mono text-lg tracking-[6px] text-gold-text">
                        @foreach(str_split(session('captcha', 'XXXXX')) as $i => $char)
                            <span style="display:inline-block; transform: rotate({{ [-8,6,-4,9,-6][$i % 5] }}deg);">{{ $char }}</span>
                        @endforeach
                    </div>
                    <a href="{{ route('login') }}" title="Muat ulang kode" class="p-2 rounded-lg border hover:bg-gray-50">
                        <x-icon name="refresh" />
                    </a>
                    <input type="text" name="captcha" placeholder="Ketik kode"
                        class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brand" required autocomplete="off">
                </div>

                <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white font-semibold py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                    Masuk ke Sistem <x-icon name="arrow-right" />
                </button>
            </form>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin') - SIPRAS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#12302C',
                        paper: '#F6F4EF',
                        brand: { DEFAULT: '#0F766E', dark: '#0B5D57', light: '#DCEEEC' },
                        gold: { DEFAULT: '#F2C230', text: '#8A6417', bg: '#FCF0CE', border: '#EFD9A8' },
                    },
                    fontFamily: {
                        serif: ['Fraunces', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-paper flex min-h-screen font-sans text-ink">
    <aside class="w-64 bg-ink text-white flex flex-col">
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gold rounded-lg flex items-center justify-center">
                    <x-icon name="flame" size="18" class="text-ink" />
                </div>
                <div>
                    <div class="font-serif font-semibold text-sm tracking-wide">SIPRAS</div>
                    <div class="text-xs text-white/50">Admin Panel</div>
                </div>
            </div>
        </div>
        <nav class="flex-1 p-4 space-y-1 text-sm">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                    ['route' => 'kategori-barang.index', 'label' => 'Kategori Barang', 'icon' => 'category'],
                    ['route' => 'barang.index', 'label' => 'Barang', 'icon' => 'box'],
                    ['route' => 'admin.peminjaman.index', 'label' => 'Peminjaman', 'icon' => 'clipboard-list'],
                    ['route' => 'admin.pengembalian.index', 'label' => 'Pengembalian', 'icon' => 'rotate'],
                    ['route' => 'admin.laporan.index', 'label' => 'Laporan', 'icon' => 'file-report'],
                    ['route' => 'admin.pemeliharaan.index', 'label' => 'Pemeliharaan', 'icon' => 'tool'],
                ];
            @endphp
            @foreach($navItems as $item)
                @php $active = request()->routeIs(explode('.index', $item['route'])[0].'*'); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $active ? 'bg-brand text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                    <x-icon :name="$item['icon']" size="16" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="p-4 border-t border-white/10">
            @csrf
            <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/5 text-red-300 text-sm">
                <x-icon name="logout" /> Keluar
            </button>
        </form>
    </aside>
    <main class="flex-1 p-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-brand-light text-brand-dark rounded-lg text-sm flex items-center gap-2">
                <x-icon name="circle-check" /> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm flex items-center gap-2">
                <x-icon name="alert-circle" /> {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
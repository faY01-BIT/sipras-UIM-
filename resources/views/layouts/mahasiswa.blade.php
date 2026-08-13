<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mahasiswa') - SIPRAS</title>
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
    <!-- Top bar mobile: cuma muncul di layar kecil -->
    <div class="md:hidden fixed top-0 left-0 right-0 h-14 bg-ink text-white flex items-center justify-between px-4 z-30">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-gold rounded-lg flex items-center justify-center">
                <x-icon name="flame" size="14" class="text-ink" />
            </div>
            <span class="font-serif font-semibold text-sm tracking-wide">SIPRAS</span>
        </div>
        <button id="sidebar-toggle" class="p-1.5 hover:bg-white/10 rounded-lg" aria-label="Buka menu">
            <x-icon name="menu" size="22" />
        </button>
    </div>

    <!-- Overlay: nutupin konten pas sidebar mobile lagi kebuka -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/40 z-30 md:hidden"></div>

    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-ink text-white flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-200">
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gold rounded-lg flex items-center justify-center">
                    <x-icon name="flame" size="18" class="text-ink" />
                </div>
                <div>
                    <div class="font-serif font-semibold text-sm tracking-wide">SIPRAS</div>
                    <div class="text-xs text-white/50">{{ auth()->user()->nama_lengkap }}</div>
                </div>
                <button id="sidebar-close" class="md:hidden ml-auto p-1 hover:bg-white/10 rounded-lg" aria-label="Tutup menu">
                    <x-icon name="x" size="18" />
                </button>
            </div>
        </div>
        <nav class="flex-1 p-4 space-y-1 text-sm">
            @php
                $navItems = [
                    ['route' => 'mahasiswa.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                    ['route' => 'mahasiswa.barang.index', 'label' => 'Lihat Inventaris', 'icon' => 'box'],
                    ['route' => 'mahasiswa.peminjaman.index', 'label' => 'Peminjaman Saya', 'icon' => 'clipboard-list'],
                    ['route' => 'mahasiswa.pengembalian.index', 'label' => 'Pengembalian', 'icon' => 'rotate'],
                ];
            @endphp
            @foreach($navItems as $item)
                @php $active = request()->routeIs(str_replace('.index', '', $item['route']).'*'); @endphp
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
    <main class="flex-1 p-4 pt-20 md:p-8 md:pt-8 min-w-0">
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

    <script>
        (function () {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var openBtn = document.getElementById('sidebar-toggle');
            var closeBtn = document.getElementById('sidebar-close');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
            openBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);
        })();
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('images/IconLogo.png') }}" type="image/png">
    <title>Sistem Peminjaman Buku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-slate-100 text-slate-800">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-slate-900 px-5 py-6 text-white">
            <img src="{{ asset('images/LogoPerpus.png') }}" alt="Dashboard Icon" class="mb-4 h-12 w-25 object-contain">

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="block rounded-lg px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-200 hover:bg-slate-700' }}">
                    Dashboard
                </a>

                <a href="{{ route('buku.index') }}" class="block rounded-lg px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('buku.*') ? 'bg-blue-600 text-white' : 'text-slate-200 hover:bg-slate-700' }}">
                    Data Buku
                </a>

                <a href="{{ route('transaksi.create') }}" class="block rounded-lg px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('transaksi.create') ? 'bg-blue-600 text-white' : 'text-slate-200 hover:bg-slate-700' }}">
                    Peminjaman
                </a>

                <a href="{{ route('transaksi.pengembalian') }}"
                    class="block rounded-lg px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('transaksi.pengembalian') ? 'bg-blue-600 text-white' : 'text-slate-200 hover:bg-slate-700' }}">
                    Pengembalian
                </a>

                <a href="{{ route('transaksi.laporan') }}" class="block rounded-lg px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('transaksi.laporan') ? 'bg-blue-600 text-white' : 'text-slate-200 hover:bg-slate-700' }}">
                    Laporan
                </a>
            </nav>
            <form action="{{ route('logout') }}" method="POST" class="mt-6">
                @csrf
                <button type="submit"
                    class="w-full rounded-lg px-4 py-3 text-left text-sm font-medium text-white transition hover:bg-red-600">
                    Logout
                </button>
            </form>
            </nav>
        </aside>

        {{-- Content --}}
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</body>

</html>
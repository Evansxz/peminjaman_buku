@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">
            Ringkasan sistem peminjaman buku.
        </p>
    </div>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Jumlah Judul Buku</p>
            <h2 class="mt-3 text-3xl font-bold text-slate-900">{{ $totalJudulBuku }}</h2>
            <p class="mt-2 text-xs text-slate-400">Total data buku yang terdaftar.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Stok Buku</p>
            <h2 class="mt-3 text-3xl font-bold text-slate-900">{{ $totalStokBuku }}</h2>
            <p class="mt-2 text-xs text-slate-400">Jumlah semua stok buku tersedia.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Buku Sedang Dipinjam</p>
            <h2 class="mt-3 text-3xl font-bold text-amber-600">{{ $bukuDipinjam }}</h2>
            <p class="mt-2 text-xs text-slate-400">Buku yang belum dikembalikan.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Transaksi Aktif</p>
            <h2 class="mt-3 text-3xl font-bold text-blue-600">{{ $transaksiAktif }}</h2>
            <p class="mt-2 text-xs text-slate-400">Transaksi dengan status dipinjam.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Transaksi Selesai</p>
            <h2 class="mt-3 text-3xl font-bold text-green-600">{{ $transaksiSelesai }}</h2>
            <p class="mt-2 text-xs text-slate-400">Transaksi yang sudah selesai.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Transaksi</p>
            <h2 class="mt-3 text-3xl font-bold text-slate-900">{{ $totalTransaksi }}</h2>
            <p class="mt-2 text-xs text-slate-400">Seluruh transaksi peminjaman.</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Lima transaksi terakhir yang tercatat di sistem.
                </p>
            </div>

            <a href="{{ route('transaksi.laporan') }}"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                Lihat Laporan
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full min-w-[900px] border-collapse bg-white text-sm">
                <thead class="bg-slate-50 text-left text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">Peminjam</th>
                        <th class="px-4 py-3 font-semibold">Kelas</th>
                        <th class="px-4 py-3 font-semibold">Buku</th>
                        <th class="px-4 py-3 font-semibold">Tanggal Pinjam</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Petugas</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($transaksiTerbaru as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-4">{{ $loop->iteration }}</td>

                            <td class="px-4 py-4 font-medium text-slate-900">
                                {{ $item->nama_peminjam }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $item->kelas }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="space-y-1">
                                    @foreach($item->detailTransaksi as $detail)
                                        <div>
                                            {{ $detail->buku->kode_buku ?? '-' }} -
                                            {{ $detail->buku->judul ?? 'Buku tidak ditemukan' }}
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                {{ $item->tanggal_pinjam }}
                            </td>

                            <td class="px-4 py-4">
                                @if($item->status === 'dipinjam')
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                        Dipinjam
                                    </span>
                                @else
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-4">
                                {{ $item->user->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
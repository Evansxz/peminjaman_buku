@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Laporan Transaksi</h1>
        <p class="mt-1 text-sm text-slate-500">
            Riwayat peminjaman dan pengembalian buku.
        </p>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Data Transaksi</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Menampilkan seluruh transaksi peminjaman buku.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full min-w-[1100px] border-collapse bg-white text-sm">
                <thead class="bg-slate-50 text-left text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">Foto</th>
                        <th class="px-4 py-3 font-semibold">Peminjam</th>
                        <th class="px-4 py-3 font-semibold">Kelas</th>
                        <th class="px-4 py-3 font-semibold">Buku</th>
                        <th class="px-4 py-3 font-semibold">Tanggal Pinjam</th>
                        <th class="px-4 py-3 font-semibold">Tanggal Kembali</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Petugas</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($transaksi as $item)
                        <tr class="align-top hover:bg-slate-50">
                            <td class="px-4 py-4">
                                {{ $transaksi->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-4">
                                @if($item->foto_peminjam)
                                    <img src="{{ asset($item->foto_peminjam) }}"
                                         alt="Foto Peminjam"
                                         class="h-16 w-16 rounded-xl object-cover ring-1 ring-slate-200">
                                @else
                                    <span class="text-slate-400">Tidak ada</span>
                                @endif
                            </td>

                            <td class="px-4 py-4 font-medium text-slate-900">
                                {{ $item->nama_peminjam }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $item->kelas }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="space-y-2">
                                    @foreach($item->detailTransaksi as $detail)
                                        <div class="rounded-lg bg-slate-100 px-3 py-2">
                                            <div class="font-medium text-slate-900">
                                                {{ $detail->buku->kode_buku ?? '-' }} - {{ $detail->buku->judul ?? 'Buku tidak ditemukan' }}
                                            </div>

                                            <div class="mt-1 text-xs text-slate-500">
                                                Status:
                                                @if($detail->status === 'dipinjam')
                                                    <span class="font-semibold text-amber-600">Dipinjam</span>
                                                @else
                                                    <span class="font-semibold text-green-600">Kembali</span>
                                                @endif

                                                @if($detail->tanggal_dikembalikan)
                                                    | Dikembalikan: {{ $detail->tanggal_dikembalikan }}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                {{ $item->tanggal_pinjam }}
                            </td>

                            <td class="px-4 py-4">
                                {{ $item->tanggal_kembali }}
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
                            <td colspan="9" class="px-4 py-8 text-center text-slate-500">
                                Belum ada data transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $transaksi->links() }}
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="rounded-2xl bg-white p-6 shadow-sm">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Data Buku</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data buku perpustakaan.</p>
        </div>

        <a href="{{ route('buku.create') }}"
           class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
            Tambah Buku
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-100 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-200">
        <table class="w-full border-collapse bg-white text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3 font-semibold">No</th>
                    <th class="px-4 py-3 font-semibold">Kode Buku</th>
                    <th class="px-4 py-3 font-semibold">Judul</th>
                    <th class="px-4 py-3 font-semibold">Stok</th>
                    <th class="px-4 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($buku as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $item->kode_buku }}</td>
                        <td class="px-4 py-3">{{ $item->judul }}</td>
                        <td class="px-4 py-3">{{ $item->stok }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('buku.edit', $item->id) }}"
                                   class="rounded-lg bg-amber-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-600">
                                    Edit
                                </a>

                                <form action="{{ route('buku.destroy', $item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                            Belum ada data buku.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
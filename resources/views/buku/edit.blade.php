@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-sm">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Buku</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui data buku.</p>
        </div>

        <a href="{{ route('buku.index') }}"
           class="rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
            Kembali
        </a>
    </div>

    <form action="{{ route('buku.update', $buku->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="kode_buku" class="mb-2 block text-sm font-semibold text-slate-700">
                Kode Buku / Barcode
            </label>

            <input type="text"
                   id="kode_buku"
                   name="kode_buku"
                   value="{{ old('kode_buku', $buku->kode_buku) }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">

            @error('kode_buku')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="judul" class="mb-2 block text-sm font-semibold text-slate-700">
                Judul Buku
            </label>

            <input type="text"
                   id="judul"
                   name="judul"
                   value="{{ old('judul', $buku->judul) }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">

            @error('judul')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="stok" class="mb-2 block text-sm font-semibold text-slate-700">
                Stok
            </label>

            <input type="number"
                   id="stok"
                   name="stok"
                   value="{{ old('stok', $buku->stok) }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">

            @error('stok')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            Update
        </button>
    </form>
</div>
@endsection
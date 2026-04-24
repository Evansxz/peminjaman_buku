<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::latest()->get();

        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|unique:buku,kode_buku',
            'judul' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        Buku::create([
            'kode_buku' => $request->kode_buku,
            'judul' => $request->judul,
            'stok' => $request->stok,
        ]);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'kode_buku' => 'required|unique:buku,kode_buku,' . $buku->id,
            'judul' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        $buku->update([
            'kode_buku' => $request->kode_buku,
            'judul' => $request->judul,
            'stok' => $request->stok,
        ]);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil dihapus.');
    }
}
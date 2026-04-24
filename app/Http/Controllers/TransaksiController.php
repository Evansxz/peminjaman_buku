<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function create()
    {
        return view('transaksi.create');
    }

    public function cariBuku($kode_buku)
    {
        $buku = Buku::where('kode_buku', $kode_buku)->first();

        if (!$buku) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku tidak ditemukan.'
            ], 404);
        }

        if ($buku->stok <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok buku habis.'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $buku->id,
                'kode_buku' => $buku->kode_buku,
                'judul' => $buku->judul,
                'stok' => $buku->stok,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'foto_peminjam' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'buku_ids' => 'required|array|min:1',
            'buku_ids.*' => 'exists:buku,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $fotoPath = null;

                if ($request->foto_peminjam) {
                    $folderPath = public_path('foto-peminjam');

                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true);
                    }

                    $image = $request->foto_peminjam;
                    $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
                    $image = str_replace(' ', '+', $image);

                    $fileName = 'foto-peminjam/' . time() . '-' . uniqid() . '.png';

                    file_put_contents(public_path($fileName), base64_decode($image));

                    $fotoPath = $fileName;
                }

                $transaksi = Transaksi::create([
                    'user_id' => auth()->id(),
                    'nama_peminjam' => $request->nama_peminjam,
                    'kelas' => $request->kelas,
                    'foto_peminjam' => $fotoPath,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => $request->tanggal_kembali,
                    'status' => 'dipinjam',
                ]);

                $bukuIds = array_unique($request->buku_ids);

                foreach ($bukuIds as $bukuId) {
                    $buku = Buku::where('id', $bukuId)->lockForUpdate()->firstOrFail();

                    if ($buku->stok <= 0) {
                        throw new \Exception('Stok buku "' . $buku->judul . '" habis.');
                    }

                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'buku_id' => $buku->id,
                        'status' => 'dipinjam',
                        'tanggal_dikembalikan' => null,
                    ]);

                    $buku->decrement('stok');
                }
            });

            return redirect()->route('transaksi.create')->with('success', 'Peminjaman berhasil disimpan.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
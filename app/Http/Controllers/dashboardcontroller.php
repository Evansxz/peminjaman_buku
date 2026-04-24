<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;

class Dashboardcontroller extends Controller
{
    public function index()
    {
        $totalJudulBuku = Buku::count();
        $totalStokBuku = Buku::sum('stok');

        $bukuDipinjam = DetailTransaksi::where('status', 'dipinjam')->count();

        $transaksiAktif = Transaksi::where('status', 'dipinjam')->count();
        $transaksiSelesai = Transaksi::where('status', 'selesai')->count();
        $totalTransaksi = Transaksi::count();

        $transaksiTerbaru = Transaksi::with(['user', 'detailTransaksi.buku'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalJudulBuku',
            'totalStokBuku',
            'bukuDipinjam',
            'transaksiAktif',
            'transaksiSelesai',
            'totalTransaksi',
            'transaksiTerbaru'
        ));
    }
}
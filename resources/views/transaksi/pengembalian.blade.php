@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Pengembalian Buku</h1>
        <p class="mt-1 text-sm text-slate-500">
            Scan barcode buku untuk mencari data peminjaman aktif.
        </p>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-100 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg bg-red-100 px-4 py-3 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-bold text-slate-900">Scan Barcode Buku</h2>

        <div class="grid gap-4 md:grid-cols-[1fr_auto]">
            <input type="text"
                   id="kode_buku"
                   class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                   placeholder="Scan barcode buku di sini lalu tekan Enter"
                   autocomplete="off"
                   autofocus>

            <button type="button"
                    id="btnCari"
                    class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                Cari Peminjaman
            </button>
        </div>

        <p id="pesan" class="mt-3 text-sm text-slate-500">
            Arahkan scanner ke barcode buku. Sistem akan mencari data peminjaman aktif.
        </p>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Data Peminjaman Aktif</h2>
                <p id="infoBuku" class="mt-1 text-sm text-slate-500">
                    Belum ada buku yang dicari.
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full border-collapse bg-white text-sm">
                <thead class="bg-slate-50 text-left text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">Foto</th>
                        <th class="px-4 py-3 font-semibold">Peminjam</th>
                        <th class="px-4 py-3 font-semibold">Kelas</th>
                        <th class="px-4 py-3 font-semibold">Tanggal Pinjam</th>
                        <th class="px-4 py-3 font-semibold">Tanggal Kembali</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody id="hasilPeminjaman" class="divide-y divide-slate-200">
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const kodeInput = document.getElementById('kode_buku');
    const btnCari = document.getElementById('btnCari');
    const pesan = document.getElementById('pesan');
    const hasilPeminjaman = document.getElementById('hasilPeminjaman');
    const infoBuku = document.getElementById('infoBuku');

    async function cariPeminjaman() {
        const kode = kodeInput.value.trim();

        if (!kode) {
            pesan.textContent = 'Kode buku belum diisi.';
            pesan.className = 'mt-3 text-sm text-red-600';
            return;
        }

        try {
            const response = await fetch(`{{ url('/pengembalian/cari') }}/${encodeURIComponent(kode)}`);
            const result = await response.json();

            if (!response.ok) {
                pesan.textContent = result.message || 'Data peminjaman tidak ditemukan.';
                pesan.className = 'mt-3 text-sm text-red-600';

                infoBuku.textContent = 'Tidak ada data.';
                hasilPeminjaman.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                            Tidak ada data peminjaman aktif.
                        </td>
                    </tr>
                `;

                return;
            }

            pesan.textContent = 'Data peminjaman ditemukan.';
            pesan.className = 'mt-3 text-sm text-green-600';

            infoBuku.textContent = `${result.buku.kode_buku} - ${result.buku.judul}`;

            renderHasil(result.data);

            kodeInput.value = '';
            kodeInput.focus();
        } catch (error) {
            pesan.textContent = 'Terjadi kesalahan saat mencari data.';
            pesan.className = 'mt-3 text-sm text-red-600';
        }
    }

    function renderHasil(data) {
        hasilPeminjaman.innerHTML = '';

        if (data.length === 0) {
            hasilPeminjaman.innerHTML = `
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                        Tidak ada data peminjaman aktif.
                    </td>
                </tr>
            `;
            return;
        }

        data.forEach((item, index) => {
            const foto = item.foto_peminjam
                ? `<img src="${item.foto_peminjam}" class="h-14 w-14 rounded-lg object-cover ring-1 ring-slate-200" alt="Foto Peminjam">`
                : `<span class="text-slate-400">Tidak ada</span>`;

            hasilPeminjaman.innerHTML += `
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">${index + 1}</td>
                    <td class="px-4 py-3">${foto}</td>
                    <td class="px-4 py-3 font-medium text-slate-900">${item.nama_peminjam}</td>
                    <td class="px-4 py-3">${item.kelas}</td>
                    <td class="px-4 py-3">${item.tanggal_pinjam}</td>
                    <td class="px-4 py-3">${item.tanggal_kembali}</td>
                    <td class="px-4 py-3">
                        <form action="{{ url('/pengembalian') }}/${item.id}" method="POST" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">
                            @csrf
                            <button type="submit"
                                    class="rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-700">
                                Kembalikan
                            </button>
                        </form>
                    </td>
                </tr>
            `;
        });
    }

    btnCari.addEventListener('click', cariPeminjaman);

    kodeInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            cariPeminjaman();
        }
    });
</script>
@endsection
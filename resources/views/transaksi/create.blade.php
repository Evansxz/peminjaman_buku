@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Peminjaman Buku</h1>
        <p class="mt-1 text-sm text-slate-500">
            Isi data peminjam, ambil foto, lalu scan barcode buku.
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

    @if($errors->any())
        <div class="rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Data Peminjam --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-bold text-slate-900">Data Peminjam</h2>

                <div class="space-y-5">
                    <div>
                        <label for="nama_peminjam" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama Peminjam
                        </label>
                        <input type="text"
                               id="nama_peminjam"
                               name="nama_peminjam"
                               value="{{ old('nama_peminjam') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                               placeholder="Masukkan nama peminjam">
                    </div>

                    <div>
                        <label for="kelas" class="mb-2 block text-sm font-semibold text-slate-700">
                            Kelas
                        </label>
                        <input type="text"
                               id="kelas"
                               name="kelas"
                               value="{{ old('kelas') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                               placeholder="Contoh: XI RPL 1">
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="tanggal_pinjam" class="mb-2 block text-sm font-semibold text-slate-700">
                                Tanggal Pinjam
                            </label>
                            <input type="date"
                                   id="tanggal_pinjam"
                                   name="tanggal_pinjam"
                                   value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                                   class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        </div>

                        <div>
                            <label for="tanggal_kembali" class="mb-2 block text-sm font-semibold text-slate-700">
                                Tanggal Kembali
                            </label>
                            <input type="date"
                                   id="tanggal_kembali"
                                   name="tanggal_kembali"
                                   value="{{ old('tanggal_kembali') }}"
                                   class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Webcam --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-bold text-slate-900">Foto Peminjam</h2>

                <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                    <video id="video" autoplay playsinline class="h-64 w-full object-cover"></video>
                </div>

                <canvas id="canvas" class="hidden"></canvas>

                <input type="hidden" id="foto_peminjam" name="foto_peminjam">

                <div class="mt-4 flex gap-3">
                    <button type="button"
                            id="btnAmbilFoto"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Ambil Foto
                    </button>

                    <span id="statusFoto" class="flex items-center text-sm text-slate-500">
                        Belum ada foto.
                    </span>
                </div>

                <img id="previewFoto" class="mt-4 hidden h-40 w-40 rounded-xl object-cover ring-2 ring-slate-200" alt="Preview Foto">
            </div>
        </div>

        {{-- Scan Buku --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-bold text-slate-900">Scan Barcode Buku</h2>

            <div class="grid gap-4 md:grid-cols-[1fr_auto]">
                <input type="text"
                       id="kode_buku_scan"
                       class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                       placeholder="Scan barcode buku di sini lalu tekan Enter"
                       autocomplete="off">

                <button type="button"
                        id="btnTambahBuku"
                        class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Tambah Buku
                </button>
            </div>

            <p id="pesanScan" class="mt-3 text-sm text-slate-500">
                Arahkan scanner ke barcode buku. Kode buku akan masuk otomatis ke input.
            </p>

            <div class="mt-6 overflow-hidden rounded-xl border border-slate-200">
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
                    <tbody id="daftarBuku" class="divide-y divide-slate-200">
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                                Belum ada buku yang discan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="inputBukuContainer"></div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="rounded-lg bg-green-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
                Simpan Peminjaman
            </button>
        </div>
    </form>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const fotoInput = document.getElementById('foto_peminjam');
    const btnAmbilFoto = document.getElementById('btnAmbilFoto');
    const previewFoto = document.getElementById('previewFoto');
    const statusFoto = document.getElementById('statusFoto');

    const kodeBukuInput = document.getElementById('kode_buku_scan');
    const btnTambahBuku = document.getElementById('btnTambahBuku');
    const pesanScan = document.getElementById('pesanScan');
    const daftarBuku = document.getElementById('daftarBuku');
    const inputBukuContainer = document.getElementById('inputBukuContainer');

    let selectedBooks = [];

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            });

            video.srcObject = stream;
        } catch (error) {
            statusFoto.textContent = 'Kamera tidak bisa diakses.';
            statusFoto.className = 'flex items-center text-sm text-red-600';
        }
    }

    btnAmbilFoto.addEventListener('click', function () {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL('image/png');

        fotoInput.value = imageData;
        previewFoto.src = imageData;
        previewFoto.classList.remove('hidden');

        statusFoto.textContent = 'Foto berhasil diambil.';
        statusFoto.className = 'flex items-center text-sm text-green-600';
    });

    async function tambahBukuDariBarcode() {
        const kode = kodeBukuInput.value.trim();

        if (!kode) {
            pesanScan.textContent = 'Kode buku belum diisi.';
            pesanScan.className = 'mt-3 text-sm text-red-600';
            return;
        }

        try {
            const response = await fetch(`{{ url('/cari-buku') }}/${encodeURIComponent(kode)}`);
            const result = await response.json();

            if (!response.ok) {
                pesanScan.textContent = result.message || 'Buku tidak ditemukan.';
                pesanScan.className = 'mt-3 text-sm text-red-600';
                return;
            }

            const buku = result.data;

            const sudahAda = selectedBooks.some(item => item.id === buku.id);

            if (sudahAda) {
                pesanScan.textContent = 'Buku sudah masuk daftar peminjaman.';
                pesanScan.className = 'mt-3 text-sm text-red-600';
                kodeBukuInput.value = '';
                return;
            }

            selectedBooks.push(buku);
            renderDaftarBuku();

            pesanScan.textContent = `Buku "${buku.judul}" berhasil ditambahkan.`;
            pesanScan.className = 'mt-3 text-sm text-green-600';

            kodeBukuInput.value = '';
            kodeBukuInput.focus();
        } catch (error) {
            pesanScan.textContent = 'Terjadi kesalahan saat mencari buku.';
            pesanScan.className = 'mt-3 text-sm text-red-600';
        }
    }

    function renderDaftarBuku() {
        daftarBuku.innerHTML = '';
        inputBukuContainer.innerHTML = '';

        if (selectedBooks.length === 0) {
            daftarBuku.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                        Belum ada buku yang discan.
                    </td>
                </tr>
            `;
            return;
        }

        selectedBooks.forEach((buku, index) => {
            daftarBuku.innerHTML += `
                <tr>
                    <td class="px-4 py-3">${index + 1}</td>
                    <td class="px-4 py-3 font-medium text-slate-900">${buku.kode_buku}</td>
                    <td class="px-4 py-3">${buku.judul}</td>
                    <td class="px-4 py-3">${buku.stok}</td>
                    <td class="px-4 py-3">
                        <button type="button"
                                onclick="hapusBuku(${buku.id})"
                                class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-700">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;

            inputBukuContainer.innerHTML += `
                <input type="hidden" name="buku_ids[]" value="${buku.id}">
            `;
        });
    }

    function hapusBuku(id) {
        selectedBooks = selectedBooks.filter(buku => buku.id !== id);
        renderDaftarBuku();
    }

    btnTambahBuku.addEventListener('click', tambahBukuDariBarcode);

    kodeBukuInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            tambahBukuDariBarcode();
        }
    });

    startCamera();
</script>
@endsection
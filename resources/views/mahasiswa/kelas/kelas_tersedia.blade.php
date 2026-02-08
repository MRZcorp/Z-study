<x-header>Kelas Tersedia</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

<!-- SUB NAVBAR -->
<div class="mb-6">
  <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
    <a href="{{ route('mahasiswa.kelas_saya') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">
      Kelas Saya
    </a>
    <a href="{{ route('mahasiswa.kelas_tersedia') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">
      Kelas Tersedia
    </a>
    <a href="{{ route('mahasiswa.kelas_riwayat') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">
      Riwayat Kelas
    </a>
  </div>
</div>


  
<div class="p-6 bg-gray-100 min-h-screen">
        <div class="grid gap-6 justify-center [grid-template-columns:repeat(auto-fill,minmax(260px,260px))]">
   

        @foreach ($pilih_kelas as $kelas)
          @php
            $myPivot = $kelas->mahasiswas->first()?->pivot;
            $statusKrs = $myPivot?->status ?? null;
          @endphp
            
        
      <!-- CARD -->
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
  
        <!-- HEADER (BACKGROUND UPLOADABLE) -->
        <div
          class="relative h-28 bg-cover bg-center"
          style="background-image: url({{ $kelas->bg_image ? asset('storage/' . $kelas->bg_image) : asset('img/Logo_Zstudy.png') }});"
        >
        <div class="absolute inset-0 bg-black/30"></div>
      
        <!-- SKS TAG -->
        <div
          class="absolute top-1 left-0 z-9 flex items-center gap-1 
                 bg-amber-50 text-gray-800 text-sm font-semibold
                 px-2 py-1 rounded-r-full shadow"
        >
          <span class="material-symbols-rounded text-base text-blue-600">
            attach_file
          </span>
          {{$kelas->mataKuliah->sks ?? '-'}}
        </div>
        <button
          type="button"
          class="btn-view-peserta absolute top-1 right-2 z-10 flex items-center gap-1 rounded-full bg-white/90 text-blue-600 text-xs font-semibold px-2 py-1 shadow hover:bg-white"
          data-kelas-nama="{{ $kelas->mataKuliah->mata_kuliah ?? '-' }} - {{ $kelas->nama_kelas }}"
          data-dosen="{{ $kelas->dosens->user->name ?? '-' }}"
          data-dosen-foto="{{ $kelas->dosens && $kelas->dosens->poto_profil ? asset('storage/' . $kelas->dosens->poto_profil) : asset('img/default_profil.jpg') }}"
          data-participants='@json($kelas->mahasiswas->map(fn($mhs) => [
              "name" => $mhs->user->name ?? "-",
              "foto" => $mhs->poto_profil ? asset("storage/" . $mhs->poto_profil) : asset("img/Logo_Zstudy.png"),
           ])->values())'
        >
          <span class="material-symbols-rounded text-base">visibility</span>
        </button>

          <div class="absolute inset-0 bg-black/30"></div>
  
          <div class="absolute bottom-3 left-2 text-white z-10">
            
            <h3 class="text-sm font-semibold">{{ $kelas->mataKuliah->mata_kuliah ?? '-' }}</h3>
          </div>
  
          <!-- AVATAR -->
          <img
            src="{{ $kelas->dosens && $kelas->dosens->poto_profil
                ? asset('storage/' . $kelas->dosens->poto_profil)
                : asset('img/default_profil.jpg') }}"
            class="absolute -bottom-10 right-4 w-20 h-20 rounded-full border-4 border-white object-cover z-10"
            alt="Avatar"
          />
        </div>
  
        <!-- BODY -->
        <div class="pt-4 px-4 pb-4 space-y-1 text-sm text-gray-700">
          <p>Kelas {{$kelas->nama_kelas}}</p>
          <p>{{$kelas->jadwal_kelas}}</p>
  
          <!-- spacer tanpa titik -->
          <div class="h-2"></div>
  
          <p>{{$kelas->hari_kelas}}</p>
          <p>{{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }}
 - {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}</p>
  
          <div class="h-2"></div>
  
          <p class="font-medium text-gray-900">{{$kelas->dosens->user->name ?? '-' }} {{$kelas->dosens->gelar ?? ''}}</p>
        </div>
  
        <!-- FOOTER -->
        <div class="flex items-center justify-between px-4 py-3 border-t">
  
          <!-- PEOPLE + KUOTA -->
          <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-600 text-lg">
              people
            </span>
  
            <!-- KUOTA (DINAMIS) -->
            <span class="text-sm font-semibold text-green-600">
              {{ $kelas->mahasiswas_count ?? 0 }} / {{ $kelas->kuota_maksimal }}
            </span>
          </div>

          <div class="flex items-center gap-2">
<form action="{{ route('mahasiswa.kelas.ikuti', $kelas->id) }}" method="POST">
            @csrf
            @if (($kelas->mahasiswas_count ?? 0) >= $kelas->kuota_maksimal)
            <button disabled class="px-4 py-1.5 rounded-full bg-gray-400 text-white">
                Penuh
            </button>
        @elseif ($statusKrs === 'menunggu')
            <button disabled class="flex items-center gap-1 rounded-full bg-gray-300 px-4 py-1.5 text-sm font-semibold text-gray-700">
              <span class="material-symbols-rounded text-base">hourglass_top</span>
              Menunggu
            </button>
        @elseif ($statusKrs === 'disetujui')
            <button disabled class="flex items-center gap-1 rounded-full bg-green-500 px-4 py-1.5 text-sm font-semibold text-white">
              <span class="material-symbols-rounded text-base">check_circle</span>
              Disetujui
            </button>
        @elseif ($statusKrs === 'ditolak')
            <button type="button" class="btn-krs-ditolak flex items-center gap-1 rounded-full bg-red-100 px-4 py-1.5 text-sm font-semibold text-red-700">
              <span class="material-symbols-rounded text-base">block</span>
              Ditolak
            </button>
        @else
         <!-- tombol ikuti -->
            <button
              type="submit"
              class="flex items-center gap-1 rounded-full bg-gradient-to-r 
                     from-blue-500 to-purple-500 px-4 py-1.5 text-sm 
                     font-semibold text-white transition 
                     hover:-translate-y-2 hover:shadow-lg"
            >
              <span class="material-symbols-rounded text-base">add_circle</span>
              Ikuti
            </button>
           
   
@endif
          </form>
          </div>
  
      </div>
      </div>
      <!-- END CARD -->
      @endforeach

    </div>
  </div>

  <div id="pesertaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl animate-scaleIn">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 id="pesertaModalTitle" class="text-lg font-semibold text-gray-800">Peserta Kelas</h3>
        <button id="btnClosePeserta" type="button" class="text-gray-400 hover:text-gray-600">X</button>
      </div>
      <div class="p-5 space-y-6 max-h-[70vh] overflow-y-auto">
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase mb-3">Dosen Pengampu</p>
          <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50 border">
            <img id="dosenFoto" src="" class="w-11 h-11 rounded-full object-cover" alt="Foto Dosen">
            <div>
              <p id="dosenNama" class="font-semibold text-gray-800"></p>
              <p class="text-xs text-gray-500">Host</p>
            </div>
          </div>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase mb-3">Mahasiswa Peserta</p>
          <div id="pesertaList" class="space-y-2"></div>
        </div>
      </div>
    </div>
  </div>

  <style>
    @keyframes scaleIn {
      from { transform: scale(.95); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    .animate-scaleIn {
      animation: scaleIn .2s ease-out;
    }
  </style>

  <script>
    const pesertaModal = document.getElementById('pesertaModal');
    const pesertaList = document.getElementById('pesertaList');
    const pesertaTitle = document.getElementById('pesertaModalTitle');
    const btnClosePeserta = document.getElementById('btnClosePeserta');
    const dosenNama = document.getElementById('dosenNama');
    const dosenFoto = document.getElementById('dosenFoto');

    const closePesertaModal = () => {
      pesertaModal.classList.add('hidden');
      pesertaModal.classList.remove('flex');
    };

    document.querySelectorAll('.btn-view-peserta').forEach((btn) => {
      btn.addEventListener('click', () => {
        const kelasNama = btn.dataset.kelasNama;
        pesertaTitle.textContent = kelasNama ? `Peserta Kelas - ${kelasNama}` : 'Peserta Kelas';

        dosenNama.textContent = btn.dataset.dosen || '-';
        dosenFoto.src = btn.dataset.dosenFoto || '';

        const participants = JSON.parse(btn.dataset.participants || '[]');
        pesertaList.innerHTML = '';

        if (!participants.length) {
          const empty = document.createElement('div');
          empty.className = 'text-sm text-gray-500';
          empty.textContent = 'Belum ada peserta terdaftar.';
          pesertaList.appendChild(empty);
        } else {
          participants.forEach((item) => {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 transition';

            const img = document.createElement('img');
            img.src = item.foto;
            img.className = 'w-9 h-9 rounded-full object-cover';
            img.alt = item.name || 'Mahasiswa';

            const name = document.createElement('span');
            name.className = 'text-sm text-gray-800';
            name.textContent = item.name || '-';

            row.appendChild(img);
            row.appendChild(name);
            pesertaList.appendChild(row);
          });
        }

        pesertaModal.classList.remove('hidden');
        pesertaModal.classList.add('flex');
      });
    });

    btnClosePeserta?.addEventListener('click', closePesertaModal);
    pesertaModal?.addEventListener('click', (e) => {
      if (e.target === pesertaModal) {
        closePesertaModal();
      }
    });
  </script>

  <div id="krsDitolakModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl animate-scaleIn">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">KRS Ditolak</h3>
        <button id="btnCloseKrsDitolak" type="button" class="text-gray-400 hover:text-gray-600">X</button>
      </div>
      <div class="p-5 space-y-4 text-sm text-gray-700">
        <p>Hubungi dosen wali terkait untuk informasi lebih lanjut.</p>
        <div class="rounded-xl border bg-gray-50 p-4 space-y-1">
          <p class="font-semibold text-gray-800">{{ $dosenWaliKontak?->dosen?->user?->name ?? '-' }}</p>
          <p class="text-xs text-gray-500">Email: {{ $dosenWaliKontak?->dosen?->user?->email ?? $dosenWaliKontak?->dosen?->email ?? '-' }}</p>
          <p class="text-xs text-gray-500">No. HP: {{ $dosenWaliKontak?->dosen?->no_hp ?? '-' }}</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    const krsDitolakModal = document.getElementById('krsDitolakModal');
    const btnCloseKrsDitolak = document.getElementById('btnCloseKrsDitolak');

    document.querySelectorAll('.btn-krs-ditolak').forEach((btn) => {
      btn.addEventListener('click', () => {
        if (!krsDitolakModal) return;
        krsDitolakModal.classList.remove('hidden');
        krsDitolakModal.classList.add('flex');
      });
    });

    btnCloseKrsDitolak?.addEventListener('click', () => {
      krsDitolakModal?.classList.add('hidden');
      krsDitolakModal?.classList.remove('flex');
    });

    krsDitolakModal?.addEventListener('click', (e) => {
      if (e.target === krsDitolakModal) {
        krsDitolakModal.classList.add('hidden');
        krsDitolakModal.classList.remove('flex');
      }
    });
  </script>


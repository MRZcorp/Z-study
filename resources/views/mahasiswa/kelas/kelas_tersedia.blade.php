<x-header>Kelas Tersedia</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>
<style>
  @media (max-width: 640px) {
    #profileCard .profile-stat-grid {
      display: flex !important;
      flex-wrap: wrap !important;
      gap: 6px !important;
    }
    #profileCard .profile-stat-item {
      flex: 0 0 calc(50% - 3px) !important;
      max-width: calc(50% - 3px) !important;
      min-width: 0 !important;
      padding: 6px 6px !important;
    }
    #profileCard .profile-stat-item p {
      line-height: 1.15 !important;
    }
  }

  @media (min-width: 1024px) {
    .kelas-card-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    }

    .sidebar.collapsed ~ .main-content .kelas-card-grid {
      grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }
  }
</style>

@if (session('error'))
  <div class="mx-6 mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">
    {{ session('error') }}
  </div>
@endif
@if (session('success'))
  <div class="mx-6 mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 border border-green-200">
    {{ session('success') }}
  </div>
@endif
@if (session('info'))
  <div class="mx-6 mb-4 rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-700 border border-blue-200">
    {{ session('info') }}
  </div>
@endif

<div class="p-1">
  <!-- PROFIL MAHASISWA -->
  <div
    id="profileCard"
    class="relative mb-6 rounded-xl shadow overflow-hidden cursor-pointer group"
    style="background-image: {{ $bg ? "url('".asset('storage/' . $bg)."')" : 'none' }}; background-color: #ffffff; background-size: cover; background-position: center;"
  >
    <!-- OVERLAY -->
    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition"></div>

    <!-- UPLOAD FORM (HIDDEN) -->
    <form id="bgUploadForm" action="{{ route('mahasiswa.bg') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input
        type="file"
        id="bgUpload"
        name="bg"
        accept="image/*"
        class="hidden"
      >
    </form>

    <!-- CONTENT -->
    <div class="relative p-4 sm:p-5 text-white">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <!-- KIRI -->
        <div class="flex items-center gap-3 sm:gap-4">
          <img
            src="{{ $foto 
              ? asset('storage/' . $foto) 
              : asset('img/default_profil.jpg') }}"
            class="w-20 h-20 sm:w-24 sm:h-24 md:w-26 md:h-26 rounded-full object-cover border-2 border-white"
            alt="Foto Mahasiswa"
          >

          <div>
            <h2 class="text-base sm:text-lg font-semibold">
              {{$nama}}
            </h2>
            <p class="text-xs sm:text-sm text-white">NIM: {{$id_user}} </p>
            <p class="text-xs sm:text-sm text-white">Fakultas {{$fakultas}} · {{$prodi}} · {{ $jenjang ? strtoupper($jenjang) : '-' }}</p>
          </div>
        </div>

        <!-- KANAN -->
        <div class="profile-stat-grid grid grid-cols-2 sm:grid-cols-3 gap-1.5 sm:gap-3 text-[11px] sm:text-sm text-gray-800">
          <div class="profile-stat-item rounded-lg px-2 py-1.5 sm:px-3 sm:py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-[11px] sm:text-sm">Tahun Ajar</p>
            <p class="font-semibold text-white text-sm sm:text-lg">{{ $tahunAjarAktif ?? '-' }}</p>
          </div>
          <div class="profile-stat-item rounded-lg px-2 py-1.5 sm:px-3 sm:py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-[11px] sm:text-sm">Semester</p>
            @php
              $semesterNum = (int) ($semesterAktifMhs ?? 1);
              $semesterLabel = $semesterNum % 2 === 0 ? 'Genap' : 'Ganjil';
            @endphp
            <p class="font-semibold text-white text-sm sm:text-lg">{{ $semesterLabel }} : {{ $semesterNum }}</p>
          </div>
          <div class="profile-stat-item rounded-lg px-2 py-1.5 sm:px-3 sm:py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-[11px] sm:text-sm">Dosen Wali</p>
            <p class="font-semibold text-white text-sm sm:text-lg">{{ $namaDosenWali ?? '-' }}</p>
          </div>
          <div class="profile-stat-item rounded-lg px-2 py-1.5 sm:px-3 sm:py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-[11px] sm:text-sm">IPK</p>
            <p class="font-semibold text-white text-sm sm:text-lg">{{ number_format((float) ($ipsTerakhir ?? 0), 2) }}</p>
          </div>
          <div class="profile-stat-item rounded-lg px-2 py-1.5 sm:px-3 sm:py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-[11px] sm:text-sm">SKS Ditempuh</p>
            <p class="font-semibold text-white text-sm sm:text-lg">{{ $sksDitempuh ?? 0 }} / {{ $sksMaks ?? 0 }}</p>
          </div>
          <div class="profile-stat-item rounded-lg px-2 py-1.5 sm:px-3 sm:py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-[11px] sm:text-sm">Max SKS</p>
            @php
              $sksLimit = (int) ($sksMaksIps ?? 24);
              $sksSemesterRaw = (int) ($sksDiambilSemester ?? 0);
              $sksSemesterDisplay = max(0, min($sksSemesterRaw, $sksLimit));
            @endphp
            <p class="font-semibold text-white text-sm sm:text-lg">{{ $sksSemesterDisplay }} / {{ $sksLimit }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
        <div class="kelas-card-grid grid grid-cols-2 gap-3 sm:gap-6">
   

        @foreach ($pilih_kelas as $kelas)
          @php
            $myPivot = $kelas->mahasiswas->first()?->pivot;
            $statusKrs = $myPivot?->status ?? null;
          @endphp
            
        
      <!-- CARD -->
      <div class="relative bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
  
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
              "foto" => $mhs->poto_profil ? asset("storage/" . $mhs->poto_profil) : asset("img/default_profil.jpg"),
           ])->values())'
        >
          <span class="material-symbols-rounded text-base">visibility</span>
        </button>

          <div class="absolute inset-0 bg-black/30"></div>
  
          <div class="absolute bottom-3 left-2 right-2 text-white z-10 pr-24">
            
            <h3 class="text-sm font-semibold leading-tight break-words">
              {{ $kelas->mataKuliah->mata_kuliah ?? '-' }}
            </h3>
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
        @if (!($isKrsActive ?? false))
          <button
            type="button"
            class="btn-krs-locked absolute inset-0 z-20 flex items-center justify-center bg-red-900/35 backdrop-blur-[1px]"
            title="KRS Nonaktif"
          >
            <span
              class="material-symbols-rounded text-red-600 drop-shadow-[0_2px_4px_rgba(0,0,0,0.35)]"
              style="font-size: 150px; line-height: 1;"
            >lock</span>
          </button>
        @endif
  
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
          <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-600 text-base sm:text-lg">people</span>
            <span class="text-xs sm:text-sm font-semibold text-green-600">
            {{ $kelas->mahasiswas_count ?? 0 }} / {{ $kelas->kuota_maksimal }}
            </span>
          </div>

          <div class="flex items-center gap-2">
            @if ($statusKrs === 'disetujui')
              @php
                $dosenMatkulDiampu = collect($pilih_kelas)
                  ->where('dosen_id', $kelas->dosen_id)
                  ->pluck('mataKuliah.mata_kuliah')
                  ->filter()
                  ->unique()
                  ->values()
                  ->implode(', ');
                if (!$dosenMatkulDiampu) {
                  $dosenMatkulDiampu = ($kelas->mataKuliah->mata_kuliah ?? '-');
                }
                $chatUserMap = collect([
                  (string) ($kelas->dosens->user_id ?? '') => [
                    'name' => ($kelas->dosens->user->name ?? '-'),
                    'foto' => ($kelas->dosens && $kelas->dosens->poto_profil ? asset('storage/' . $kelas->dosens->poto_profil) : asset('img/default_profil.jpg')),
                    'phone' => ($kelas->dosens->no_hp ?? '-'),
                    'role' => 'dosen',
                    'gelar' => ($kelas->dosens->gelar ?? ''),
                    'homebase' => ($kelas->dosens->fakultas->fakultas ?? '-'),
                    'mata_kuliah' => $dosenMatkulDiampu,
                  ],
                ])->merge(
                  $kelas->mahasiswas->mapWithKeys(fn ($mhs) => [
                    (string) ($mhs->user_id ?? '') => [
                      'name' => ($mhs->user->name ?? '-'),
                      'foto' => ($mhs->poto_profil ? asset('storage/' . $mhs->poto_profil) : asset('img/default_profil.jpg')),
                      'phone' => '-',
                      'role' => 'mahasiswa',
                      'gelar' => '',
                      'homebase' => '-',
                      'mata_kuliah' => '-',
                      'nim' => ($mhs->nim ?? '-'),
                      'fakultas' => ($mhs->fakultas->fakultas ?? '-'),
                      'prodi' => ($mhs->programStudi->nama_prodi ?? '-'),
                    ],
                  ])
                );
              @endphp
              <button
                type="button"
                onclick="openChatModal(this)"
                data-kelas-id="{{ $kelas->id }}"
                data-kelas-nama="{{ $kelas->mataKuliah->mata_kuliah ?? '-' }} - {{ $kelas->nama_kelas }}"
                data-user-map='@json($chatUserMap)'
                data-dosen-name="{{ $kelas->dosens->user->name ?? '-' }}"
                data-dosen-foto="{{ $kelas->dosens && $kelas->dosens->poto_profil ? asset('storage/' . $kelas->dosens->poto_profil) : asset('img/default_profil.jpg') }}"
                data-dosen-phone="{{ $kelas->dosens->no_hp ?? '-' }}"
                data-dosen-gelar="{{ $kelas->dosens->gelar ?? '' }}"
                data-dosen-homebase="{{ $kelas->dosens->fakultas->fakultas ?? '-' }}"
                data-dosen-matkul="{{ $dosenMatkulDiampu }}"
                class="flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1.5 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-200"
              >
                <span class="material-symbols-rounded text-sm sm:text-base">chat</span>
              </button>
            @endif
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
         @if (($isKrsActive ?? false))
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
   
@endif
          </form>
          </div>
  
      </div>
      </div>
      <!-- END CARD -->
      @endforeach

    </div>
  </div>

  @include('mahasiswa.kelas.partials.chat_modal')

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
    document.addEventListener('DOMContentLoaded', () => {
      const krsDitolakModal = document.getElementById('krsDitolakModal');
      const btnCloseKrsDitolak = document.getElementById('btnCloseKrsDitolak');
      const krsLockedModal = document.getElementById('krsLockedModal');
      const btnCloseKrsLocked = document.getElementById('btnCloseKrsLocked');

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

      document.querySelectorAll('.btn-krs-locked').forEach((btn) => {
        btn.addEventListener('click', () => {
          krsLockedModal?.classList.remove('hidden');
          krsLockedModal?.classList.add('flex');
        });
      });
      btnCloseKrsLocked?.addEventListener('click', () => {
        krsLockedModal?.classList.add('hidden');
        krsLockedModal?.classList.remove('flex');
      });
      krsLockedModal?.addEventListener('click', (e) => {
        if (e.target === krsLockedModal) {
          krsLockedModal.classList.add('hidden');
          krsLockedModal.classList.remove('flex');
        }
      });
    });
  </script>

  <div id="krsLockedModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl animate-scaleIn">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">KRS Terkunci</h3>
        <button id="btnCloseKrsLocked" type="button" class="text-gray-400 hover:text-gray-600">X</button>
      </div>
      <div class="p-5 text-sm text-gray-700">
        Harap konsultasi ke bagian akademik atau dosen wali untuk membuka akses KRS Anda.
      </div>
    </div>
  </div>

  <script>
    const card = document.getElementById('profileCard');
    const upload = document.getElementById('bgUpload');
    const uploadForm = document.getElementById('bgUploadForm');

    card?.addEventListener('click', () => {
      upload?.click();
    });

    upload?.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = () => {
        if (card) card.style.backgroundImage = `url('${reader.result}')`;
      };
      reader.readAsDataURL(file);

      uploadForm?.submit();
    });
  </script>


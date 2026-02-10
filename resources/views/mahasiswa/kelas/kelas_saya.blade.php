<x-header>Kelas Mahasiswa</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

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
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3 text-xs sm:text-sm text-gray-800">
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">Tahun Ajar</p>
            <p class="font-semibold text-white text-base sm:text-lg">{{ $tahunAjarAktif ?? '-' }}</p>
          </div>
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">Semester</p>
            <p class="font-semibold text-white text-base sm:text-lg">{{ $semesterAktif ?? '-' }} : {{ $semesterAktifMhs ?? 1 }}</p>
          </div>
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">Dosen Wali</p>
            <p class="font-semibold text-white text-base sm:text-lg">{{ $namaDosenWali ?? '-' }}</p>
          </div>
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">IPK</p>
            <p class="font-semibold text-white text-base sm:text-lg">{{ number_format((float) ($ipsTerakhir ?? 0), 2) }}</p>
          </div>
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">SKS Ditempuh</p>
            <p class="font-semibold text-white text-base sm:text-lg">{{ $sksDitempuh ?? 0 }} / {{ $sksMaks ?? 0 }}</p>
          </div>
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">Max SKS</p>
            <p class="font-semibold text-white text-base sm:text-lg">{{ $sksDitempuh ?? 0 }} / {{ $sksMaksIps ?? 24 }}</p>
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
       class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">
      Kelas Saya
    </a>
    <a href="{{ route('mahasiswa.kelas_tersedia') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">
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

        @if ($pilih_kelas->isEmpty())
          <div class="col-span-full text-center text-gray-500">
            Belum ada kelas yang kamu ikuti.
          </div>
        @endif

        @foreach ($pilih_kelas as $kelas)
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
            <div class="absolute inset-0 bg-black/30"></div>
    
            <div class="absolute bottom-3 left-2 text-white z-10">
              
              <h3 
                    class="text-sm font-semibold leading-snug 
                          max-w-[70%] 
                          line-clamp-2"
                  >
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
    
            <p class="font-medium text-gray-900">{{$kelas->dosens->user->name ?? '-' }} {{$kelas->dosens->gelar ?? ''}} </p>
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

            <button
              type="button"
              class="btn-view-peserta flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
              data-kelas-nama="{{ $kelas->mataKuliah->mata_kuliah ?? '-' }} - {{ $kelas->nama_kelas }}"
              data-dosen="{{ $kelas->dosens->user->name ?? '-' }}"
              data-dosen-foto="{{ $kelas->dosens && $kelas->dosens->poto_profil ? asset('storage/' . $kelas->dosens->poto_profil) : asset('img/default_profil.jpg') }}"
              data-participants='@json($kelas->mahasiswas->map(fn($mhs) => [
                "name" => $mhs->user->name ?? "-",
                "foto" => $mhs->poto_profil ? asset("storage/" . $mhs->poto_profil) : asset("img/default_profil.jpg"),
              ])->values())'
            >
              <span class="material-symbols-rounded text-base">visibility</span>
              Lihat
            </button>
  
          </div>
        </div>
        <!-- END CARD -->
        @endforeach
  
  
  </div>

  <div id="pesertaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl animate-scaleIn">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 id="pesertaModalTitle" class="text-lg font-semibold text-gray-800">Peserta Kelas</h3>
        <button id="btnClosePeserta" type="button" class="text-gray-400 hover:text-gray-600">×</button>
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


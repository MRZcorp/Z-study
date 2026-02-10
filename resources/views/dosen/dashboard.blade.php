<x-header>Dashboard</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

<div class="p-6">
  <!-- PROFIL Dosen -->
  <!-- CARD PROFILE WITH BACKGROUND -->
  <div
    id="profileCard"
    class="relative mb-6 rounded-xl shadow overflow-hidden cursor-pointer group"
    style="background-image: {{ $bg ? "url('".asset('storage/' . $bg)."')" : 'none' }};
           background-color: #ffffff;
           background-size: cover;
           background-position: center;"
  >
    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition"></div>

    <form id="bgUploadForm" action="{{ route('dosen.kelas.bg') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input 
        type="file" 
        id="bgUpload"
        name="bg"
        accept="image/*"
        class="hidden"
      >
    </form>

    <div class="relative p-4 sm:p-6 text-white">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
          <img 
          src="{{ $foto 
          ? asset('storage/' . $foto) 
          : asset('img/default_profil.jpg') }}"
            class="w-26 h-26 rounded-full object-cover border-2 border-white"
            alt="Foto Profil"
          >

          <div>
            <h2 class="text-lg font-semibold">
              {{$nama}}
            </h2>
            <p class="text-sm text-white">NIDN: {{$id_user}}</p>
            <p class="text-sm text-white">Homebase : {{ $homebaseFakultas ?? '-' }}</p>
          </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 gap-3 text-sm text-gray-800">
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">Tahun Ajar</p>
            <p class="font-semibold text-white text-lg">{{ $tahunAjarAktif ?? '-' }}</p>
          </div>
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">Semester</p>
            <p class="font-semibold text-white text-lg">{{ $semesterAktif ?? '-' }}</p>
          </div>
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">Jumlah Kelas</p>
            <p class="font-semibold text-white text-lg">{{ $jumlahKelas ?? 0 }}</p>
          </div>
          <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
            <p class="text-white/70 text-sm">Jumlah SKS</p>
            <p class="font-semibold text-white text-lg">{{ $totalSks ?? 0 }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
        <!-- STATISTIK ATAS -->
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
          {{-- <div class="stats-container"> --}}
          <!-- Jumlah Kelas -->
          <div class="flex items-center p-4 sm:p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-blue-200 text-blue-600">
              <span class="material-symbols-rounded text-3xl">Co_Present</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $jumlahKelas ?? 0 }}</h4>
              <p class="text-gray-500">Jumlah Kelas</p>
            </div>
          </div>
      
                    <!-- Total Materi -->

          <div class="flex items-center p-4 sm:p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-orange-200 text-orange-600 ">
              <span class="material-symbols-rounded text-3xl">assignment</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $totalMateri ?? 0 }}</h4>
              <p class="text-gray-500">Total Materi</p>
            </div>
          </div>
      
          
          <!-- Tugas Aktif -->
          <div class="flex items-center p-4 sm:p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-pink-200 text-pink-600">
              <span class="material-symbols-rounded text-3xl">Contract_Edit</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $totalTugas ?? 0 }}</h4>
              <p class="text-gray-500">Total Tugas</p>
            </div>
          </div>
      
          <!-- Kuis / Ujian -->
          <div class="flex items-center p-4 sm:p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-green-200 text-green-600">
              <span class="material-symbols-rounded text-3xl">quiz</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $totalUjian ?? 0 }}</h4>
              <p class="text-gray-500">Total Kuis & Ujian</p>
            </div>
          </div>
      
        </div>
      
        <!-- KONTEN BAWAH -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-4 sm:gap-6 mt-6">
      
          <!-- PENGUMUMAN -->
<div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
  <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
      <span class="material-symbols-rounded text-blue-600">campaign</span>
      Pengumuman
  </h3>




  <!-- SCROLL AREA -->
<ul class="space-y-3 sm:space-y-4 max-h-100 overflow-y-auto pr-2">
  @foreach ($pengumuman as $info)
  @php
      $tipe = strtolower($info->tipe);
      $icon = match ($tipe) {
          'info' => 'info',
          'peringatan' => 'warning',
          'event' => 'event',
          default => 'info',
      };
  
      $bgicon = match ($icon) {
          'info' => 'bg-blue-100 text-blue-600',
          'warning' => 'bg-yellow-100 text-yellow-600',
          'event' => 'bg-green-100 text-green-600',
          default => 'bg-slate-100 text-slate-700',
      };
  @endphp
  
  <li class="flex items-start p-3 sm:p-4 bg-slate-50 rounded-lg border border-slate-200">
      <div class="p-2 rounded-lg {{ $bgicon }}">
          <span class="material-symbols-rounded">{{ $icon }}</span>
      </div>
  
      <div class="ml-3 sm:ml-4">
          <p
              class="text-sm sm:text-base font-semibold text-gray-800 cursor-pointer hover:underline btn-preview-announcement"
              data-judul="{{ $info->judul }}"
              data-isi="{{ $info->isi }}"
              data-tipe="{{ $info->tipe }}"
              data-status="{{ $info->is_active ? 'Publish' : 'Draft' }}"
              data-berkas="{{ $info->file_name ?? '' }}"
              data-berkas-url="{{ $info->file_path ? asset('storage/' . $info->file_path) : '' }}"
              data-tanggal-display="{{ $info->tanggal_publish ? \Illuminate\Support\Str::lower(\Carbon\Carbon::parse($info->tanggal_publish)->locale('id')->translatedFormat('d F Y')) : '-' }}"
              data-created="{{ $info->created_at }}"
          >
              {{ $info->judul }}
          </p>
  
          <p class="text-xs sm:text-sm text-gray-500 line-clamp-2">
              {{ $info->isi }}
          </p>
  
          <p class="text-[11px] sm:text-xs text-gray-400 mt-1">
              {{ $info->tanggal_publish ? \Illuminate\Support\Str::lower(\Carbon\Carbon::parse($info->tanggal_publish)->locale('id')->translatedFormat('d F Y')) : '-' }}
          </p>
      </div>
  </li>
  @endforeach
  </div>
      
          <!-- Kelas TERDEKAT -->
          <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">
              Jadwal Kelas
            </h3>
      
            <ul class="space-y-4">
              @forelse (($jadwalKelas ?? collect()) as $kelas)
                <li class="flex items-start p-3 sm:p-4 bg-slate-50 rounded-lg border border-slate-200">
                  <div class="p-2 rounded-lg bg-blue-200 text-blue-600 mt-1">
                    <span class="material-symbols-rounded">Co_Present</span>
                  </div>

                  <div class="ml-3 sm:ml-4">
                    <p class="text-sm sm:text-base font-semibold text-gray-800">
                      {{ $kelas->mataKuliah->mata_kuliah ?? 'Mata Kuliah' }} - {{ $kelas->nama_kelas ?? '-' }}
                    </p>
                    <p class="text-xs sm:text-sm text-gray-500">
                      {{ $kelas->jadwal_kelas ?? '-' }}
                    </p>
                    <p class="text-xs sm:text-sm text-red-500 flex items-center mt-1">
                      <span class="material-symbols-rounded text-base mr-1">schedule</span>
                      {{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }}
                      - {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}
                      ({{ $kelas->hari_kelas ?? '-' }})
                    </p>
                  </div>
                </li>
              @empty
                <li class="p-4 text-sm text-gray-500 bg-slate-50 rounded-lg border border-slate-200">
                  Tidak ada jadwal kelas untuk hari {{ $hariIni ?? '-' }}.
                </li>
              @endforelse
            </ul>
              
          </div>
      
        </div>
      
      </div>
    </main>




  
<x-footer></x-footer>

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

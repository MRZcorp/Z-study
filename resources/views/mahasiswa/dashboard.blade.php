<x-header>Dashboard</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>



    <div class="p-1">
       <!-- PROFIL MAHASISWA -->
<div 
  id="profileCard"
  class="relative mb-6 rounded-xl shadow overflow-hidden cursor-pointer group"
  style="background-image: url('{{ $bg ? asset('storage/' . $bg) : asset('img/Logo_Zstudy.png') }}'); background-size: cover; background-position: center;"
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
        : asset('img/Logo_Zstudy.png') }}"
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
          <p class="font-semibold text-white text-base sm:text-lg">{{ $semesterAktif ?? '-' }}</p>
        </div>
        <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
          <p class="text-white/70 text-sm">Dosen Wali</p>
          <p class="font-semibold text-white text-base sm:text-lg">{{ $namaDosenWali ?? '-' }}</p>
        </div>
        <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
          <p class="text-white/70 text-sm">IPK</p>
          <p class="font-semibold text-white text-base sm:text-lg"> 3.75</p>
        </div>
        <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
          <p class="text-white/70 text-sm">SKS Ditempuh</p>
          <p class="font-semibold text-white text-base sm:text-lg">{{ $sksDitempuh ?? 0 }} / {{ $sksMaks ?? 0 }}</p>
        </div>
        <div class="rounded-lg px-3 py-2 text-center bg-black/40 backdrop-blur-sm border border-white/20">
          <p class="text-white/70 text-sm">Max SKS</p>
          <p class="font-semibold text-white text-base sm:text-lg"> 0 / 24</p>
        </div>
      </div>
    </div>
  </div>
</div>

  
        <!-- STATISTIK ATAS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
          {{-- <div class="stats-container"> --}}
            


          <!-- Jumlah Kelas -->
          <div class="flex items-center p-4 sm:p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-blue-200 text-blue-600">
              <span class="material-symbols-rounded text-2xl sm:text-3xl">Co_Present</span>
            </div>
            <div class="ml-3 sm:ml-4">
              <h4 class="text-2xl sm:text-3xl font-semibold text-gray-700">{{ $jumlahKelas ?? 0 }}</h4>
              <p class="text-sm sm:text-base text-gray-500">Jumlah Kelas</p>
            </div>
          </div>
      
          <!-- Tugas Aktif -->
          <div class="flex items-center p-4 sm:p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-orange-200 text-orange-600 ">
              <span class="material-symbols-rounded text-2xl sm:text-3xl">assignment</span>
            </div>
            <div class="ml-3 sm:ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $tugasAktif ?? 0 }}</h4>
              <p class="text-sm sm:text-base text-gray-500">Tugas Aktif</p>
            </div>
          </div>
      
          <!-- Kuis / Ujian -->
          <div class="flex items-center p-4 sm:p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-pink-200 text-pink-600">
              <span class="material-symbols-rounded text-2xl sm:text-3xl">Contract_Edit</span>
            </div>
            <div class="ml-3 sm:ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $ujianAktif ?? 0 }}</h4>
              <p class="text-sm sm:text-base text-gray-500">Kuis / Ujian Aktif</p>
            </div>
          </div>
      
          <!-- Rata-rata Nilai -->
          <div class="flex items-center p-4 sm:p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-green-200 text-green-600">
              <span class="material-symbols-rounded text-2xl sm:text-3xl">grade</span>
            </div>
            <div class="ml-3 sm:ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $rataNilai ?? 0 }}</h4>
              <p class="text-sm sm:text-base text-gray-500">Rata-rata Nilai</p>
            </div>
          </div>

        </div>
      
        <!-- KONTEN BAWAH -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6 mt-6">
      



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
  </ul>

    


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
      
          <!-- TUGAS / UJIAN TERDEKAT -->
          <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-4">
              Tugas / Ujian Terdekat
            </h3>
      
            <ul class="space-y-4">
              @forelse(($terdekat ?? collect()) as $item)
                @php
                  $isUjian = strtolower($item['tipe'] ?? '') === 'ujian';
                  $icon = $isUjian ? 'quiz' : 'assignment';
                  $badge = $isUjian ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600';
                  $deadline = $item['deadline'] ? \Carbon\Carbon::parse($item['deadline'])->locale('id')->translatedFormat('d F Y') : '-';
                @endphp
                <li class="flex items-start p-3 sm:p-4 bg-slate-50 rounded-lg border border-slate-200">
                  <div class="p-2 rounded-lg {{ $badge }} mt-1">
                    <span class="material-symbols-rounded">{{ $icon }}</span>
                  </div>
                  <div class="ml-3 sm:ml-4">
                    <p class="text-sm sm:text-base font-semibold text-gray-800">
                      {{ $item['tipe'] ?? 'Tugas' }}: {{ $item['judul'] ?? '-' }}
                    </p>
                    <p class="text-xs sm:text-sm text-gray-500">
                      {{ $item['matkul'] ?? '-' }}
                    </p>
                    <p class="text-xs sm:text-sm text-red-500 flex items-center mt-1">
                      <span class="material-symbols-rounded text-base mr-1">event</span>
                      {{ \Illuminate\Support\Str::lower($deadline) }}
                    </p>
                  </div>
                </li>
              @empty
                <li class="p-4 text-sm text-gray-500 bg-slate-50 rounded-lg border border-slate-200">
                  Tidak ada tugas atau ujian terdekat.
                </li>
              @endforelse
            </ul>
              
          </div>
      
        </div>
      
      </div>
      
    </main>

<!-- MODAL PREVIEW PENGUMUMAN -->
<div id="previewModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden w-[92vw] sm:w-[85vw] max-w-[1100px] h-[85vh] sm:h-[75vh]">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div>
        <h3 class="text-lg font-semibold text-slate-800">Detail Pengumuman</h3>
        <p id="previewSubTitle" class="text-sm text-slate-500">-</p>
      </div>
      <div class="flex items-center gap-2">
        <a id="previewDownload" href="#" target="_blank" class="rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
          <span class="material-symbols-rounded text-base">download</span>
        </a>
        <button type="button" class="btn-close text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-4 p-4 sm:p-5" style="height:calc(75vh - 64px);">
      <div class="w-full lg:w-[75%] h-64 lg:h-full">
        <div id="previewContainer" class="w-full h-full rounded-xl border bg-slate-50 flex items-center justify-center text-sm text-slate-500">
          Tidak ada file.
        </div>
      </div>
      <div class="w-full lg:w-[25%] flex flex-col gap-3 h-full text-sm text-slate-700 break-words">
        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700">
            <span class="material-symbols-rounded text-sm">event</span>
            <span id="previewTanggal">-</span>
          </span>
        </div>
        <div>
          <p class="text-xs text-slate-500">Deskripsi</p>
          <p id="previewIsi">-</p>
        </div>
        <div class="mt-auto">
          <p class="text-xs text-slate-500">File</p>
          <div id="previewFileList" class="mt-1 flex flex-col gap-1 text-sm text-blue-700 max-h-40 overflow-y-auto pr-1"></div>
        </div>
        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
          <span id="previewTipeBadge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
            <span class="material-symbols-rounded text-sm">flag</span>
            <span id="previewTipe">-</span>
          </span>
          <span id="previewStatusBadge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
            <span class="material-symbols-rounded text-sm">task_alt</span>
            <span id="previewStatus">-</span>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const previewModal = document.getElementById('previewModal');
  const previewContainer = document.getElementById('previewContainer');
  const previewDownload = document.getElementById('previewDownload');
  const previewFileList = document.getElementById('previewFileList');
  const previewSubTitle = document.getElementById('previewSubTitle');

  const renderPreview = (url, ext) => {
    const lower = (ext || '').toLowerCase();
    if (!previewContainer) return;
    if (!url) {
      previewContainer.innerHTML = 'Tidak ada file.';
      return;
    }

    if (['mp4', 'webm', 'ogg'].includes(lower)) {
      previewContainer.innerHTML = `<video src="${url}" controls class="w-full h-full rounded-xl bg-black"></video>`;
      return;
    }

    if (['pdf'].includes(lower)) {
      previewContainer.innerHTML = `<iframe src="${url}" class="w-full h-full rounded-xl"></iframe>`;
      return;
    }

    if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(lower)) {
      previewContainer.innerHTML = `<div class="text-center text-slate-500 text-sm">Preview tidak tersedia untuk file ini. Silakan download.</div>`;
      return;
    }

    previewContainer.innerHTML = `<iframe src="${url}" class="w-full h-full rounded-xl"></iframe>`;
  };

  const setActiveFile = (file) => {
    if (!file) {
      renderPreview('', '');
      if (previewDownload) previewDownload.href = '#';
      return;
    }
    renderPreview(file.url, file.ext);
    if (previewDownload) previewDownload.href = file.url || '#';
  };

  const closePreview = () => {
    previewModal?.classList.add('hidden');
    previewModal?.classList.remove('flex');
    if (previewContainer) previewContainer.innerHTML = 'Tidak ada file.';
    if (previewFileList) previewFileList.innerHTML = '';
    if (previewDownload) previewDownload.href = '#';
  };

  document.querySelectorAll('.btn-preview-announcement').forEach((btn) => {
    btn.addEventListener('click', () => {
      if (!previewModal) return;
      const berkasName = btn.dataset.berkas || '';
      const berkasUrl = btn.dataset.berkasUrl || '';
      const cleanUrl = berkasUrl ? berkasUrl.split('?')[0].toLowerCase() : '';
      const ext = cleanUrl ? cleanUrl.split('.').pop() : '';
      const files = berkasUrl
        ? [{ name: berkasName || 'Berkas', url: berkasUrl, ext }]
        : [];

      const tipeValue = (btn.dataset.tipe || '-').toLowerCase();
      const statusValue = (btn.dataset.status || '-').toLowerCase();
      document.getElementById('previewTipe').textContent = btn.dataset.tipe || '-';
      document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
      document.getElementById('previewTanggal').textContent = btn.dataset.tanggalDisplay || '-';
      document.getElementById('previewIsi').textContent = btn.dataset.isi || '-';
      if (previewSubTitle) {
        previewSubTitle.textContent = btn.dataset.judul || '-';
      }

      const tipeBadge = document.getElementById('previewTipeBadge');
      if (tipeBadge) {
        tipeBadge.classList.remove('bg-blue-100', 'text-blue-700', 'bg-green-100', 'text-green-700', 'bg-yellow-100', 'text-yellow-700', 'bg-slate-100', 'text-slate-700', 'text-slate-600');
        if (tipeValue === 'info') {
          tipeBadge.classList.add('bg-blue-100', 'text-blue-700');
        } else if (tipeValue === 'event') {
          tipeBadge.classList.add('bg-green-100', 'text-green-700');
        } else if (tipeValue === 'peringatan') {
          tipeBadge.classList.add('bg-yellow-100', 'text-yellow-700');
        } else {
          tipeBadge.classList.add('bg-slate-100', 'text-slate-700');
        }
      }

      const statusBadge = document.getElementById('previewStatusBadge');
      if (statusBadge) {
        statusBadge.classList.remove('bg-green-100', 'text-green-700', 'bg-slate-200', 'text-slate-600', 'bg-slate-100');
        if (statusValue === 'publish') {
          statusBadge.classList.add('bg-green-100', 'text-green-700');
        } else if (statusValue === 'draft') {
          statusBadge.classList.add('bg-slate-200', 'text-slate-600');
        } else {
          statusBadge.classList.add('bg-slate-100', 'text-slate-600');
        }
      }

      if (previewFileList) {
        previewFileList.innerHTML = '';
        if (files.length === 0) {
          previewFileList.innerHTML = '<span class="text-slate-400 text-sm">Tidak ada file.</span>';
          setActiveFile(null);
        } else {
          files.forEach((file, idx) => {
            const btnFile = document.createElement('button');
            btnFile.type = 'button';
            btnFile.className = 'text-left hover:underline';
            btnFile.textContent = file.name || `File ${idx + 1}`;
            btnFile.addEventListener('click', () => setActiveFile(file));
            previewFileList.appendChild(btnFile);
          });
          setActiveFile(files[0]);
        }
      }

      previewModal.classList.remove('hidden');
      previewModal.classList.add('flex');
    });
  });

  previewModal?.querySelectorAll('.btn-close').forEach((btn) => {
    btn.addEventListener('click', closePreview);
  });

  previewModal?.addEventListener('click', (e) => {
    if (e.target === previewModal) closePreview();
  });
</script>

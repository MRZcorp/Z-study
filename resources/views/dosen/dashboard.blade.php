<x-header>Dashboard</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

<!-- PROFIL Dosen -->
<!-- CARD PROFILE WITH BACKGROUND -->
<div
  id="profileCard"
  class="relative mb-6 rounded-xl shadow overflow-hidden cursor-pointer group"
  style="background-image: url('{{ $bg 
      ? asset('storage/' . $bg) 
      : asset('img/Logo_Zstudy.png') }}');
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

  <div class="relative p-5 text-white">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
      <div class="flex items-center gap-4">
        <img 
        src="{{ $foto 
        ? asset('storage/' . $foto) 
        : asset('img/Logo_Zstudy.png') }}"
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


    <div class="p-6">
  
  
        <!-- STATISTIK ATAS -->
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-6">
          {{-- <div class="stats-container"> --}}
          <!-- Jumlah Kelas -->
          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-blue-200 text-blue-600">
              <span class="material-symbols-rounded text-3xl">Co_Present</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $jumlahKelas ?? 0 }}</h4>
              <p class="text-gray-500">Jumlah Kelas</p>
            </div>
          </div>
      
                    <!-- Total Materi -->

          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-orange-200 text-orange-600 ">
              <span class="material-symbols-rounded text-3xl">assignment</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $totalMateri ?? 0 }}</h4>
              <p class="text-gray-500">Total Materi</p>
            </div>
          </div>
      
          
          <!-- Tugas Aktif -->
          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-pink-200 text-pink-600">
              <span class="material-symbols-rounded text-3xl">Contract_Edit</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">{{ $totalTugas ?? 0 }}</h4>
              <p class="text-gray-500">Total Tugas</p>
            </div>
          </div>
      
          <!-- Kuis / Ujian -->
          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
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
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-6 mt-6">
      
          <!-- PENGUMUMAN -->
<div class="bg-white rounded-lg shadow-sm p-6">
  <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
      <span class="material-symbols-rounded text-blue-600">campaign</span>
      Pengumuman
  </h3>




  <!-- SCROLL AREA -->
<ul class="space-y-4 max-h-100 overflow-y-auto pr-2">
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
  
  <li class="flex items-start p-4 bg-slate-50 rounded-lg border border-slate-200">
      <div class="p-2 rounded-lg {{ $bgicon }}">
          <span class="material-symbols-rounded">{{ $icon }}</span>
      </div>
  
      <div class="ml-4">
          <p
              class="font-semibold text-gray-800 cursor-pointer hover:underline btn-preview-announcement"
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
  
          <p class="text-sm text-gray-500 line-clamp-2">
              {{ $info->isi }}
          </p>
  
          <p class="text-xs text-gray-400 mt-1">
              {{ $info->tanggal_publish ? \Illuminate\Support\Str::lower(\Carbon\Carbon::parse($info->tanggal_publish)->locale('id')->translatedFormat('d F Y')) : '-' }}
          </p>
      </div>
  </li>
  @endforeach
  </div>
      
          <!-- Kelas TERDEKAT -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
              Jadwal Kelas
            </h3>
      
            <ul class="space-y-4">
              @forelse (($jadwalKelas ?? collect()) as $kelas)
                <li class="flex items-start p-4 bg-slate-50 rounded-lg border border-slate-200">
                  <div class="p-2 rounded-lg bg-blue-200 text-blue-600 mt-1">
                    <span class="material-symbols-rounded">Co_Present</span>
                  </div>

                  <div class="ml-4">
                    <p class="font-semibold text-gray-800">
                      {{ $kelas->mataKuliah->mata_kuliah ?? 'Mata Kuliah' }} - {{ $kelas->nama_kelas ?? '-' }}
                    </p>
                    <p class="text-sm text-gray-500">
                      {{ $kelas->jadwal_kelas ?? '-' }}
                    </p>
                    <p class="text-sm text-red-500 flex items-center mt-1">
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

<!-- MODAL PREVIEW PENGUMUMAN -->
<div id="previewModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden" style="width:70vw; max-width:1100px; height:75vh;">
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

    <div class="flex gap-4 p-5" style="height:calc(75vh - 64px);">
      <div class="w-[75%] h-full">
        <div id="previewContainer" class="w-full h-full rounded-xl border bg-slate-50 flex items-center justify-center text-sm text-slate-500">
          Tidak ada file.
        </div>
      </div>
      <div class="w-[25%] flex flex-col gap-3 h-full text-sm text-slate-700 break-words">
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
 

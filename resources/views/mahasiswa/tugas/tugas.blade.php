<x-header>Tugas Mahasiswa</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

@php
  $now = \Carbon\Carbon::now();
  $tugas_aktif = $tugas_kelas->filter(function ($tugas) use ($now) {
      $deadline = $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline) : null;
      $submittedAt = optional(($tugas->pengumpulan ?? collect())->first())->submitted_at;
      if ($submittedAt) {
          return false;
      }
      return $deadline ? $deadline->isFuture() : true;
  });

  if (request('matkul_id')) {
      $tugas_aktif = $tugas_aktif->filter(function ($tugas) {
          return (string) ($tugas->mata_kuliah_id ?? '') === (string) request('matkul_id');
      });
  }

  $matkulList = ($tugas_kelas ?? collect())->map(fn($t) => $t->mataKuliah)->filter()->unique('id')->values();
  $tugasIndexMap = [];
  foreach ($tugas_kelas as $item) {
      $tugasIndexMap[$item->id] = $item->tugas_ke ?? 1;
  }
@endphp

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Tugas Aktif</h2>
      <p class="text-sm text-slate-500">Daftar tugas yang harus kamu kerjakan.</p>
    </div>
    <form method="GET" class="flex items-center gap-2">
      <select name="matkul_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" onchange="this.form.submit()">
        <option value="">Semua Mata Kuliah</option>
        @foreach($matkulList as $matkul)
          <option value="{{ $matkul->id }}" @selected(request('matkul_id') == $matkul->id)>{{ $matkul->mata_kuliah }}</option>
        @endforeach
      </select>
    </form>
  </div>

  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
      <span class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">Ditugaskan</span>
      <a href="{{ url('/mahasiswa/tugas_selesai') }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">Selesai</a>
    </div>
  </div>

  <div class="space-y-4">
    @if ($tugas_aktif->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada tugas.
      </div>
    @endif

    @foreach ($tugas_aktif as $tugas)
      <div class="bg-white rounded-xl border p-5 flex flex-col gap-4 relative">
        <div class="flex items-start justify-between gap-3">
          <div class="inline-flex items-center gap-2 text-xs font-semibold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full w-fit">
            {{ $tugas->mataKuliah->mata_kuliah ?? '-' }}
          </div>
          <div class="flex items-center gap-2 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700">
              <span class="material-symbols-rounded text-sm">schedule</span>
              {{ $tugas->mulai_tugas ? \Carbon\Carbon::parse($tugas->mulai_tugas)->format('d M Y H:i') : '-' }}
            </span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-red-50 text-red-700">
              <span class="material-symbols-rounded text-sm">event</span>
              {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('d M Y H:i') : '-' }}
            </span>
          </div>
        </div>

        <div>
          <h3 class="font-semibold text-slate-800">{{ $tugas->nama_tugas }}</h3>
          <p class="text-sm text-slate-500 mt-1">{{ $tugas->detail_tugas ?? '-' }}</p>
          <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
              <span class="material-symbols-rounded text-sm">school</span>
              Kelas {{ $tugas->kelas->nama_kelas ?? '-' }}
              <span class="text-blue-600 font-semibold">Tugas ke: {{ $tugasIndexMap[$tugas->id] ?? 1 }}</span>
            </span>
          </div>
        </div>

        @php
          $fileRows = $tugas->files ?? collect();
          if ($fileRows->isEmpty() && isset($tugasFileRows)) {
              $fileRows = $tugasFileRows->get($tugas->id, collect());
          }
          if ($fileRows->isEmpty()) {
              $fileRows = \App\Models\TugasFile::where('tugas_id', $tugas->id)->get();
          }
          $files = $fileRows->map(function ($f) {
              return [
                  'name' => $f->file_name,
                  'url' => asset('storage/' . $f->file_path),
                  'ext' => strtolower($f->file_type ?? pathinfo($f->file_path, PATHINFO_EXTENSION)),
              ];
          })->values();

          if ($files->isEmpty() && !empty($tugas->file_tugas)) {
              $files = collect([[
                  'name' => basename($tugas->file_tugas),
                  'url' => asset('storage/' . $tugas->file_tugas),
                  'ext' => strtolower(pathinfo($tugas->file_tugas, PATHINFO_EXTENSION)),
              ]]);
          }
        @endphp

        @php
          $pengumpulan = ($tugas->pengumpulan ?? collect())->first();
          $pengumpulanUrl = $pengumpulan && $pengumpulan->file_path ? asset('storage/' . $pengumpulan->file_path) : '';
        @endphp
        <script type="application/json" id="tugas-files-{{ $tugas->id }}">
          @json($files)
        </script>
        @php
          $kelasRef = $tugas->kelas;
          $dosenRef = $kelasRef?->dosens;
          $chatUserMap = collect();
          if ($dosenRef && $dosenRef->user_id) {
              $chatUserMap[(string) $dosenRef->user_id] = [
                  'name' => $dosenRef->user->name ?? '-',
                  'foto' => $dosenRef->poto_profil ? asset('storage/' . $dosenRef->poto_profil) : asset('img/default_profil.jpg'),
                  'phone' => $dosenRef->no_hp ?? '-',
                  'role' => 'dosen',
                  'gelar' => $dosenRef->gelar ?? '',
                  'homebase' => $dosenRef->fakultas->fakultas ?? '-',
                  'mata_kuliah' => $tugas->mataKuliah->mata_kuliah ?? '-',
                  'fakultas' => $dosenRef->fakultas->fakultas ?? '-',
                  'prodi' => $dosenRef->programStudi->nama_prodi ?? '-',
              ];
          }
          foreach (($kelasRef?->mahasiswas ?? collect()) as $mhsRef) {
              $chatUserMap[(string) ($mhsRef->user_id ?? '')] = [
                  'name' => $mhsRef->user->name ?? '-',
                  'foto' => $mhsRef->poto_profil ? asset('storage/' . $mhsRef->poto_profil) : asset('img/default_profil.jpg'),
                  'phone' => $mhsRef->no_hp ?? '-',
                  'role' => 'mahasiswa',
                  'nim' => $mhsRef->nim ?? '-',
                  'fakultas' => $mhsRef->fakultas->fakultas ?? '-',
                  'prodi' => $mhsRef->programStudi->nama_prodi ?? '-',
              ];
          }
        @endphp
        <div class="absolute bottom-4 right-4 flex items-center gap-2">
          <button
            type="button"
            onclick="openChatModal(this)"
            data-kelas-id="{{ $tugas->id }}"
            data-kelas-nama="{{ $tugas->nama_tugas ?? 'Tugas' }}"
            data-user-map='@json($chatUserMap)'
            class="rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
          >
            <span class="material-symbols-rounded text-base">chat</span>
          </button>
          <button
            type="button"
            class="btn-preview-tugas rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700"
            data-tugas-id="{{ $tugas->id }}"
            data-matkul="{{ $tugas->mataKuliah->mata_kuliah ?? '-' }}"
            data-nama="{{ $tugas->nama_tugas }}"
            data-deskripsi="{{ $tugas->detail_tugas ?? '-' }}"
            data-kelas="Kelas {{ $tugas->kelas->nama_kelas ?? '-' }}"
            data-mulai="{{ $tugas->mulai_tugas ? \Carbon\Carbon::parse($tugas->mulai_tugas)->format('d M Y H:i') : '-' }}"
            data-deadline="{{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('d M Y H:i') : '-' }}"
            data-mulai-iso="{{ $tugas->mulai_tugas ? \Carbon\Carbon::parse($tugas->mulai_tugas)->setTimezone(config('app.timezone'))->format('Y-m-d\TH:i:sP') : '' }}"
            data-deadline-iso="{{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->setTimezone(config('app.timezone'))->format('Y-m-d\TH:i:sP') : '' }}"
            data-pengumpulan-deskripsi="{{ $pengumpulan->deskripsi ?? '' }}"
            data-pengumpulan-file="{{ $pengumpulan->file_name ?? '' }}"
            data-pengumpulan-url="{{ $pengumpulanUrl }}"
            data-pengumpulan-submitted="{{ $pengumpulan && $pengumpulan->submitted_at ? '1' : '0' }}"
          >
            <span class="material-symbols-rounded text-base">visibility</span>
          </button>
        </div>

      </div>
    @endforeach
  </div>
</div>

<!-- MODAL PREVIEW TUGAS -->
@php
  $savePengumpulanUrl = route('mahasiswa.tugas.pengumpulan.save');
  $submitPengumpulanUrl = route('mahasiswa.tugas.pengumpulan.submit');
@endphp
<div id="previewTugasModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-[70vw] h-[70vh] bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div>
        <h3 id="previewMatkul" class="text-lg font-semibold text-slate-800">Mata Kuliah</h3>
        <p id="previewKelas" class="text-sm text-slate-500">-</p>
      </div>
      <div class="flex items-center gap-2">
        <span id="previewCountdown" class="text-xs font-semibold text-red-600"></span>
        <a id="previewDownload" href="#" target="_blank" class="rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
          <span class="material-symbols-rounded text-base">download</span>
        </a>
        <button id="btnUploadMode" type="button" class="rounded-full bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-emerald-700">
          <span class="material-symbols-rounded text-base">upload</span>
        </button>
        <button id="btnClosePreview" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-5 h-[calc(70vh-64px)] min-h-0">
      <div class="lg:col-span-2 h-full min-h-0">
        <div id="previewContainer" class="w-full h-full rounded-xl border bg-slate-50 flex items-center justify-center text-sm text-slate-500">
          Tidak ada file.
        </div>
        <form id="uploadForm" data-save-url="{{ $savePengumpulanUrl }}" data-submit-url="{{ $submitPengumpulanUrl }}" action="{{ $savePengumpulanUrl }}" method="POST" enctype="multipart/form-data" class="hidden w-full h-full min-h-0 rounded-xl border bg-white p-2 overflow-y-auto">
          @csrf
          <input type="hidden" name="tugas_id" id="uploadTugasId" value="">
          <h4 class="font-semibold text-slate-800 mb-2">Upload Tugas</h4>
          <div class="space-y-3">
            <label class="block text-sm font-medium text-slate-700">Deskripsi</label>
            <textarea id="uploadDeskripsi" name="deskripsi" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="Tulis deskripsi tugasmu..."></textarea>
          </div>
          <div class="space-y-3 mt-4">
            <label class="block text-sm font-medium text-slate-700">File Tugas</label>
            <input id="uploadFileInput" type="file" name="file_tugas" class="w-full rounded-lg border border-slate-300 px-3 py-2">
            <p id="uploadedFileInfo" class="text-xs text-slate-500"></p>
          </div>
          <div id="uploadSaveWrap" class="hidden mt-4 w-full flex items-center justify-end">
            <button id="btnSaveUpload" type="button" class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white hover:bg-blue-700">Save</button>
          </div>
          <div id="uploadSelesaiWrap" class="hidden mt-2 w-full flex items-center justify-end">
            <div class="flex items-center gap-2">
              <a id="uploadViewBtn" href="#" target="_blank" class="hidden rounded-full px-3 py-1 text-xs font-semibold text-blue-700 hover:underline">
                <span class="material-symbols-rounded text-base">visibility</span>
              </a>
              <button id="uploadSelesaiBtn" type="button" class="rounded-full bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700">Selesai</button>
            </div>
          </div>
        </form>
      </div>
      <div id="rightPanel" class="lg:col-span-1 flex flex-col gap-3 h-full min-h-0">
        <div id="uploadActions" class="hidden"></div>
        <div id="previewNamaWrap">
          <p id="previewNama" class="font-semibold text-slate-800">-</p>
        </div>
        <div id="previewMeta">
          <p class="text-xs text-slate-500">Deskripsi</p>
          <p id="previewDeskripsi" class="text-sm text-slate-700">-</p>
        </div>
        <div id="previewFileSection" class="mt-auto">
          <div class="flex items-center justify-between">
            <p class="text-xs text-slate-500">File</p>
          </div>
          <div id="previewFileList" class="mt-1 flex flex-col gap-1 text-sm text-blue-700 max-h-40 overflow-y-auto pr-1"></div>
        </div>
        <div class="mt-auto flex flex-col gap-2">
          <div id="timePanel" class="flex items-center justify-end gap-2 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700">
              <span class="material-symbols-rounded text-sm">schedule</span>
              <span id="previewMulai">-</span>
            </span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-red-50 text-red-700">
              <span class="material-symbols-rounded text-sm">event</span>
              <span id="previewDeadline">-</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div id="uploadActionsSpacer" class="hidden"></div>
  </div>
</div>

<!-- MODAL KONFIRMASI SELESAI -->
<div id="confirmSubmitModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl p-6">
    <h4 class="text-lg font-semibold text-slate-800">Apakah anda yakin mengumpulkan Tugas Sekarang?</h4>
    <div class="mt-4 space-y-3 text-sm text-slate-700">
      <div>
        <p class="text-xs text-slate-500">Deskripsi</p>
        <p id="confirmDeskripsi" class="font-medium">-</p>
      </div>
      <div>
        <p class="text-xs text-slate-500">File</p>
        <ul id="confirmFileList" class="list-disc ml-5 text-slate-700"></ul>
      </div>
    </div>
    <div class="mt-6 flex items-center justify-end gap-2">
      <button id="confirmNo" type="button" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Tidak</button>
      <button id="confirmYes" type="button" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Ya</button>
    </div>
  </div>
</div>

<script>
  window.__chatContextType = 'tugas';
  window.__chatBaseUrlTemplate = @json(route('mahasiswa.tugas.diskusi.index', ['tugas' => '__CTX_ID__']));
  window.__chatMessageUrlTemplate = @json(route('mahasiswa.tugas.diskusi.update', ['tugas' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']));
</script>
@include('mahasiswa.kelas.partials.chat_modal')

<!-- MODAL SUKSES UPLOAD -->
<div id="uploadSuccessModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center">
    <p class="text-base font-semibold text-slate-800">Tugas berhasil di upload</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-emerald-600">check_circle</span>
    </div>
  </div>
</div>

<script>
  const previewModal = document.getElementById('previewTugasModal');
  const btnClosePreview = document.getElementById('btnClosePreview');
  const previewContainer = document.getElementById('previewContainer');
  const previewMatkul = document.getElementById('previewMatkul');
  const previewNama = document.getElementById('previewNama');
  const previewDeskripsi = document.getElementById('previewDeskripsi');
  const previewKelas = document.getElementById('previewKelas');
  const previewMulai = document.getElementById('previewMulai');
  const previewDeadline = document.getElementById('previewDeadline');
  const previewDownload = document.getElementById('previewDownload');
  const previewFileList = document.getElementById('previewFileList');
  const previewCountdown = document.getElementById('previewCountdown');
  const btnUploadMode = document.getElementById('btnUploadMode');
  const uploadForm = document.getElementById('uploadForm');
  const uploadContainer = uploadForm;
  const uploadDeskripsi = document.getElementById('uploadDeskripsi');
  const uploadTugasId = document.getElementById('uploadTugasId');
  const uploadFileInput = document.getElementById('uploadFileInput');
  const uploadedFileInfo = document.getElementById('uploadedFileInfo');
  const btnSaveUpload = document.getElementById('btnSaveUpload');
  const rightPanel = document.getElementById('rightPanel');
  const uploadActions = document.getElementById('uploadActions');
  const uploadActionsSpacer = document.getElementById('uploadActionsSpacer');
  const previewMeta = document.getElementById('previewMeta');
  const previewFileSection = document.getElementById('previewFileSection');
  const previewNamaWrap = document.getElementById('previewNamaWrap');
  const uploadSaveWrap = document.getElementById('uploadSaveWrap');
  const uploadSelesaiBtn = document.getElementById('uploadSelesaiBtn');
  const uploadSelesaiWrap = document.getElementById('uploadSelesaiWrap');
  const uploadViewBtn = document.getElementById('uploadViewBtn');
  const confirmSubmitModal = document.getElementById('confirmSubmitModal');
  const confirmDeskripsi = document.getElementById('confirmDeskripsi');
  const confirmFileList = document.getElementById('confirmFileList');
  const confirmNo = document.getElementById('confirmNo');
  const confirmYes = document.getElementById('confirmYes');
  const uploadSuccessModal = document.getElementById('uploadSuccessModal');
  let countdownTimer = null;
  let isUploadMode = false;

  const enterUploadMode = () => {
    if (!uploadContainer || !previewContainer) return;
    uploadContainer.classList.remove('hidden');
    previewContainer.classList.add('hidden');
    rightPanel?.classList.remove('hidden');
    uploadActions?.classList.add('hidden');
    uploadSaveWrap?.classList.remove('hidden');
    uploadSelesaiBtn?.classList.remove('hidden');
    uploadSelesaiWrap?.classList.remove('hidden');
    if (uploadViewBtn) {
      const existingUrl = uploadedFileInfo?.dataset.fileUrl || '';
      if (existingUrl) {
        uploadViewBtn.href = existingUrl;
        uploadViewBtn.classList.remove('hidden');
      } else {
        uploadViewBtn.href = '#';
        uploadViewBtn.classList.add('hidden');
      }
    }
    uploadActionsSpacer?.classList.add('hidden');
    isUploadMode = true;
  };

  const exitUploadMode = () => {
    if (!uploadContainer || !previewContainer) return;
    uploadContainer.classList.add('hidden');
    previewContainer.classList.remove('hidden');
    rightPanel?.classList.remove('hidden');
    uploadActions?.classList.add('hidden');
    uploadSaveWrap?.classList.add('hidden');
    uploadSelesaiBtn?.classList.add('hidden');
    uploadSelesaiWrap?.classList.add('hidden');
    uploadActionsSpacer?.classList.add('hidden');
    isUploadMode = false;
  };

  const parseFilesFromButton = (btn) => {
    const tugasId = btn?.dataset?.tugasId || '';
    const filesEl = tugasId ? document.getElementById(`tugas-files-${tugasId}`) : null;
    const rawScript = filesEl?.textContent?.trim();
    if (rawScript) {
      try {
        const parsed = JSON.parse(rawScript);
        return Array.isArray(parsed) ? parsed : [];
      } catch (err) {
        console.warn('Gagal parse files JSON', err);
      }
    }
    return [];
  };

  const closePreview = () => {
    previewModal.classList.add('hidden');
    previewModal.classList.remove('flex');
    previewContainer.innerHTML = 'Tidak ada file.';
    previewFileList.innerHTML = '';
    uploadContainer.classList.add('hidden');
    previewContainer.classList.remove('hidden');
    rightPanel.classList.remove('hidden');
    previewNamaWrap.classList.remove('hidden');
    previewMeta.classList.remove('hidden');
    previewFileSection.classList.remove('hidden');
    uploadActions.classList.add('hidden');
    uploadSaveWrap.classList.add('hidden');
    uploadSelesaiBtn.classList.add('hidden');
    uploadSelesaiWrap.classList.add('hidden');
    uploadActionsSpacer.classList.add('hidden');
    isUploadMode = false;
    if (countdownTimer) {
      clearInterval(countdownTimer);
      countdownTimer = null;
    }
    if (previewCountdown) {
      previewCountdown.textContent = '';
    }
    if (confirmSubmitModal) {
      confirmSubmitModal.classList.add('hidden');
      confirmSubmitModal.classList.remove('flex');
    }
    if (uploadSuccessModal) {
      uploadSuccessModal.classList.add('hidden');
      uploadSuccessModal.classList.remove('flex');
    }
  };

  const setSaveState = () => {
    if (!btnSaveUpload || !uploadedFileInfo) return;
    const hasDesc = (uploadDeskripsi?.value || '').trim().length > 0;
    const hasFile = uploadFileInput?.files && uploadFileInput.files.length > 0;
    const hasExisting = uploadedFileInfo.dataset.hasExisting === '1';
    const canSubmit = hasDesc || hasFile || hasExisting;
    btnSaveUpload.disabled = !canSubmit;
    uploadSelesaiBtn.disabled = !canSubmit;
    btnSaveUpload.classList.toggle('opacity-50', !canSubmit);
    btnSaveUpload.classList.toggle('cursor-not-allowed', !canSubmit);
    uploadSelesaiBtn.classList.toggle('opacity-50', !canSubmit);
    uploadSelesaiBtn.classList.toggle('cursor-not-allowed', !canSubmit);
    btnSaveUpload.classList.toggle('bg-blue-600', !hasExisting);
    btnSaveUpload.classList.toggle('hover:bg-blue-700', !hasExisting);
    btnSaveUpload.classList.toggle('bg-green-600', hasExisting);
    btnSaveUpload.classList.toggle('hover:bg-green-700', hasExisting);
  };

  const renderPreview = (url, ext) => {
    const lower = (ext || '').toLowerCase();
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
      previewDownload.href = '#';
      previewDownload.classList.add('hidden');
      return;
    }
    renderPreview(file.url, file.ext);
    previewDownload.href = file.url || '#';
    previewDownload.classList.toggle('hidden', !file.url);
  };

  document.querySelectorAll('.btn-preview-tugas').forEach((btn) => {
    btn.addEventListener('click', () => {
      const files = parseFilesFromButton(btn);
      const mulaiIso = btn.dataset.mulaiIso || '';
      const deadlineIso = btn.dataset.deadlineIso || '';

      previewMatkul.textContent = btn.dataset.matkul || '-';
      previewNama.textContent = btn.dataset.nama || '-';
      previewDeskripsi.textContent = btn.dataset.deskripsi || '-';
      previewKelas.textContent = btn.dataset.kelas || '-';
      previewMulai.textContent = btn.dataset.mulai || '-';
      previewDeadline.textContent = btn.dataset.deadline || '-';
      if (uploadTugasId) {
        uploadTugasId.value = btn.dataset.tugasId || '';
      }
      if (uploadDeskripsi) {
        uploadDeskripsi.value = btn.dataset.pengumpulanDeskripsi || '';
      }
      if (uploadedFileInfo) {
        const existingFile = btn.dataset.pengumpulanFile || '';
        const existingUrl = btn.dataset.pengumpulanUrl || '';
        uploadedFileInfo.textContent = existingFile ? `File tersimpan: ${existingFile}` : '';
        uploadedFileInfo.dataset.hasExisting = existingFile ? '1' : '0';
        uploadedFileInfo.dataset.fileUrl = existingUrl;
        if (uploadViewBtn) {
          if (existingUrl) {
            uploadViewBtn.href = existingUrl;
            uploadViewBtn.classList.remove('hidden');
          } else {
            uploadViewBtn.href = '#';
            uploadViewBtn.classList.add('hidden');
          }
        }
      }
      if (uploadFileInput) {
        uploadFileInput.value = '';
      }
      if (uploadForm?.dataset?.saveUrl) {
        uploadForm.action = uploadForm.dataset.saveUrl;
      }
      setSaveState();
      uploadContainer.classList.add('hidden');
      previewContainer.classList.remove('hidden');
      rightPanel.classList.remove('hidden');
      previewNamaWrap.classList.remove('hidden');
      previewMeta.classList.remove('hidden');
      previewFileSection.classList.remove('hidden');
      uploadActions.classList.add('hidden');
      uploadSaveWrap.classList.add('hidden');
      uploadSelesaiBtn.classList.add('hidden');
      uploadSelesaiWrap.classList.add('hidden');
      uploadActionsSpacer.classList.add('hidden');
      isUploadMode = false;

      const renderCountdown = () => {
        if (!previewCountdown) return;
        if (!deadlineIso) {
          previewCountdown.textContent = '';
          return;
        }

        const now = new Date();
        const mulai = mulaiIso ? new Date(mulaiIso) : null;
        const deadline = new Date(deadlineIso);

        if (mulai && now < mulai) {
          const diffStart = Math.max(0, mulai - now);
          const totalMinutesStart = Math.floor(diffStart / 60000);
          const daysStart = Math.floor(totalMinutesStart / (60 * 24));
          const hoursStart = Math.floor((totalMinutesStart % (60 * 24)) / 60);
          const minutesStart = totalMinutesStart % 60;

          const partsStart = [];
          if (daysStart > 0) partsStart.push(`${daysStart} hari`);
          if (hoursStart > 0 || daysStart > 0) partsStart.push(`${hoursStart} jam`);
          partsStart.push(`${minutesStart} menit`);

          previewCountdown.textContent = `Mulai dalam ${partsStart.join(' ')}`;
          return;
        }

        let diff = Math.max(0, deadline - now);
        const totalMinutes = Math.floor(diff / 60000);
        const days = Math.floor(totalMinutes / (60 * 24));
        const hours = Math.floor((totalMinutes % (60 * 24)) / 60);
        const minutes = totalMinutes % 60;

        const parts = [];
        if (days > 0) parts.push(`${days} hari`);
        if (hours > 0 || days > 0) parts.push(`${hours} jam`);
        parts.push(`${minutes} menit`);

        previewCountdown.textContent = parts.join(' ');
      };

      if (countdownTimer) {
        clearInterval(countdownTimer);
        countdownTimer = null;
      }
      renderCountdown();
      countdownTimer = setInterval(renderCountdown, 1000);

      previewFileList.innerHTML = '';
      if (files.length === 0) {
        previewFileList.innerHTML = '<span class="text-slate-400 text-sm">Tidak ada file.</span>';
        setActiveFile(null);
      } else {
        files.forEach((file, idx) => {
          const row = document.createElement('div');
          row.className = 'flex items-center justify-between gap-2';
          const btnFile = document.createElement('button');
          btnFile.type = 'button';
          btnFile.className = 'text-left hover:underline';
          btnFile.textContent = file.name || `File ${idx + 1}`;
          btnFile.addEventListener('click', () => setActiveFile(file));
          const link = document.createElement('a');
          link.href = file.url || '#';
          link.target = '_blank';
          link.rel = 'noopener';
          link.className = 'text-xs text-blue-600 hover:underline';
          link.textContent = 'Link';
          if (!file.url) {
            link.classList.add('hidden');
          }
          row.appendChild(btnFile);
          row.appendChild(link);
          previewFileList.appendChild(row);
        });
        setActiveFile(files[0]);
      }

      previewModal.classList.remove('hidden');
      previewModal.classList.add('flex');
    });
  });

  btnUploadMode?.addEventListener('click', () => {
    enterUploadMode();
  });

  document.addEventListener('click', (e) => {
    const target = e.target.closest('#btnUploadMode');
    if (target) {
      e.preventDefault();
      enterUploadMode();
    }
  });

  uploadDeskripsi?.addEventListener('input', setSaveState);
  uploadFileInput?.addEventListener('change', setSaveState);

  btnSaveUpload?.addEventListener('click', async () => {
    if (!uploadForm || btnSaveUpload.disabled) return;
    const formData = new FormData(uploadForm);
    try {
      const res = await fetch(uploadForm.dataset.saveUrl || uploadForm.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: formData,
      });
      if (!res.ok) {
        throw new Error('Upload gagal.');
      }
      const data = await res.json();
      if (uploadSuccessModal) {
        uploadSuccessModal.classList.remove('hidden');
        uploadSuccessModal.classList.add('flex');
        setTimeout(() => {
          uploadSuccessModal.classList.add('hidden');
          uploadSuccessModal.classList.remove('flex');
        }, 1000);
      }
      if (uploadedFileInfo && data.file_name) {
        uploadedFileInfo.textContent = `File tersimpan: ${data.file_name}`;
        uploadedFileInfo.dataset.hasExisting = '1';
        if (data.file_path) {
          uploadedFileInfo.dataset.fileUrl = `${window.location.origin}/storage/${data.file_path}`;
        }
      }
      if (uploadViewBtn && uploadedFileInfo?.dataset.fileUrl) {
        uploadViewBtn.href = uploadedFileInfo.dataset.fileUrl;
        uploadViewBtn.classList.remove('hidden');
      }
      if (uploadFileInput) {
        uploadFileInput.value = '';
      }
      setSaveState();
    } catch (err) {
      alert('Upload gagal. Coba lagi.');
    }
  });

  uploadSelesaiBtn?.addEventListener('click', () => {
    if (uploadSelesaiBtn.disabled) return;
    if (!confirmSubmitModal) return;
    const desc = (uploadDeskripsi?.value || '').trim();
    confirmDeskripsi.textContent = desc !== '' ? desc : '-';
    if (confirmFileList) {
      confirmFileList.innerHTML = '';
      const files = uploadFileInput?.files ? Array.from(uploadFileInput.files) : [];
      if (files.length > 0) {
        files.forEach((file) => {
          const li = document.createElement('li');
          li.textContent = file.name;
          confirmFileList.appendChild(li);
        });
      } else if (uploadedFileInfo?.dataset.hasExisting === '1') {
        const existingText = uploadedFileInfo.textContent.replace('File tersimpan: ', '').trim();
        const li = document.createElement('li');
        li.textContent = existingText || '-';
        confirmFileList.appendChild(li);
      } else {
        const li = document.createElement('li');
        li.textContent = '-';
        confirmFileList.appendChild(li);
      }
    }
    confirmSubmitModal.classList.remove('hidden');
    confirmSubmitModal.classList.add('flex');
  });

  confirmNo?.addEventListener('click', () => {
    confirmSubmitModal?.classList.add('hidden');
    confirmSubmitModal?.classList.remove('flex');
  });

  confirmYes?.addEventListener('click', async () => {
    if (!uploadForm) return;
    const formData = new FormData(uploadForm);
    try {
      const res = await fetch(uploadForm.dataset.submitUrl || uploadForm.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: formData,
      });
      if (!res.ok) {
        throw new Error('Submit gagal.');
      }
      confirmSubmitModal?.classList.add('hidden');
      confirmSubmitModal?.classList.remove('flex');
      window.location.href = "{{ url('/mahasiswa/tugas_selesai') }}";
    } catch (err) {
      alert('Submit gagal. Coba lagi.');
    }
  });

  previewDownload?.addEventListener('click', (e) => {
    if (isUploadMode) {
      e.preventDefault();
      exitUploadMode();
    }
  });

  btnClosePreview?.addEventListener('click', closePreview);
  previewModal?.addEventListener('click', (e) => {
    if (e.target === previewModal) closePreview();
  });
  confirmSubmitModal?.addEventListener('click', (e) => {
    if (e.target === confirmSubmitModal) {
      confirmSubmitModal.classList.add('hidden');
      confirmSubmitModal.classList.remove('flex');
    }
  });
</script>

<x-header>Data Tugas</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

@php
  $now = \Carbon\Carbon::now();
  $tugas_aktif = $tugas_kelas->filter(function ($tugas) use ($now) {
      $deadline = $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline) : null;
      return $deadline ? $deadline->isFuture() : true;
  });
  $tugasIndexMap = [];
  foreach ($tugas_kelas as $item) {
      $tugasIndexMap[$item->id] = $item->tugas_ke ?? 1;
  }
@endphp

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Tugas</h2>
      <p class="text-sm text-slate-500">Kelola tugas aktif dan lihat tugas yang sudah selesai.</p>
    </div>
    <button id="btnOpenTugas" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 px-4 py-2 text-sm font-semibold text-white">
      <span class="material-symbols-rounded text-base">add</span>
      Buat Tugas
    </button>
  </div>

  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
      <span class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">Tugas Aktif</span>
      <a href="{{ url('/dosen/tugas_selesai') }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">Tugas Selesai</a>
    </div>
  </div>

  <div class="space-y-4">
    @if ($tugas_aktif->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada tugas aktif.
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
          @if ($files->isNotEmpty())
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
              data-mulai-iso="{{ $tugas->mulai_tugas ? \Carbon\Carbon::parse($tugas->mulai_tugas)->setTimezone(config('app.timezone'))->format('Y-m-d\\TH:i:sP') : '' }}"
              data-deadline-iso="{{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->setTimezone(config('app.timezone'))->format('Y-m-d\\TH:i:sP') : '' }}"
            >
              <span class="material-symbols-rounded text-base">visibility</span>
            </button>
          @endif
          <button
            type="button"
            class="btn-edit-tugas rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
            data-id="{{ $tugas->id }}"
            data-tugas-id="{{ $tugas->id }}"
            data-kelas-id="{{ $tugas->kelas->id ?? '' }}"
            data-matkul-id="{{ $tugas->mataKuliah->id ?? '' }}"
            data-matkul="{{ $tugas->mataKuliah->mata_kuliah ?? '' }}"
            data-nama="{{ $tugas->nama_tugas }}"
            data-deskripsi="{{ $tugas->detail_tugas ?? '' }}"
            data-mulai="{{ $tugas->mulai_tugas ? \Carbon\Carbon::parse($tugas->mulai_tugas)->format('Y-m-d\\TH:i') : '' }}"
            data-deadline="{{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('Y-m-d\\TH:i') : '' }}"
            data-files='@json($files)'
          >
            <span class="material-symbols-rounded text-base">edit</span>
          </button>
          <form action="{{ route('dosen.tugas.destroy', $tugas->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-full bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-700 hover:bg-red-200">
              <span class="material-symbols-rounded text-base">delete</span>
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- MODAL PREVIEW TUGAS -->
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
        <button id="btnClosePreview" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-5 h-[calc(70vh-64px)]">
      <div class="lg:col-span-2 h-full">
        <div id="previewContainer" class="w-full h-full rounded-xl border bg-slate-50 flex items-center justify-center text-sm text-slate-500">
          Tidak ada file.
        </div>
      </div>
      <div class="lg:col-span-1 flex flex-col gap-3 h-full">
        <div>
          <p id="previewNama" class="font-semibold text-slate-800">-</p>
        </div>
        <div>
          <p class="text-xs text-slate-500">Deskripsi</p>
          <p id="previewDeskripsi" class="text-sm text-slate-700">-</p>
        </div>
        <div class="mt-auto">
          <p class="text-xs text-slate-500">File</p>
          <div id="previewFileList" class="mt-1 flex flex-col gap-1 text-sm text-blue-700 max-h-40 overflow-y-auto pr-1"></div>
        </div>
        <div class="flex items-center justify-between gap-2 text-xs text-slate-500">
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
</div>

<script>
  window.__chatContextType = 'tugas';
  window.__chatBaseUrlTemplate = @json(route('dosen.tugas.diskusi.index', ['tugas' => '__CTX_ID__']));
  window.__chatMessageUrlTemplate = @json(route('dosen.tugas.diskusi.update', ['tugas' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']));
</script>
@include('dosen.kelas.partials.chat_modal')

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
  let countdownTimer = null;

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
    if (countdownTimer) {
      clearInterval(countdownTimer);
      countdownTimer = null;
    }
    if (previewCountdown) {
      previewCountdown.textContent = '';
    }
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

  btnClosePreview?.addEventListener('click', closePreview);
  previewModal?.addEventListener('click', (e) => {
    if (e.target === previewModal) closePreview();
  });
</script>

<!-- MODAL EDIT TUGAS -->
<div id="editTugasModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[80vh] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Edit Tugas</h3>
      <button id="btnCloseEditTugas" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <form id="editTugasForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 overflow-y-auto max-h-[calc(80vh-64px)]">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
        <select id="editKelasSelect" name="nama_kelas_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
          <option value="">Pilih Kelas</option>
          @foreach(($kelas_dosen ?? []) as $kelas)
            @if (($kelas->status ?? '') === 'selesai')
              @continue
            @endif
            @php $lastTugasKe = $tugasCountByKelas[$kelas->id] ?? 0; @endphp
            <option value="{{ $kelas->id }}" data-matkul-id="{{ $kelas->mataKuliah->id ?? '' }}" data-matkul="{{ $kelas->mataKuliah->mata_kuliah ?? '' }}" data-tugas-next="{{ $lastTugasKe + 1 }}">
              {{ $kelas->mataKuliah->mata_kuliah ?? '-' }} - Kelas {{ $kelas->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Mata Kuliah</label>
        <input type="text" id="editMatkulText" class="w-full rounded-lg border border-slate-300 px-4 py-2 bg-slate-50" readonly>
        <input type="hidden" id="editMatkulId" name="mata_kuliah_id">
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Tugas</label>
        <input type="text" name="nama_tugas" id="editNamaTugas" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
        <textarea name="detail_tugas" id="editDeskripsiTugas" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Deskripsi tugas"></textarea>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Mulai Tugas</label>
          <input type="datetime-local" name="mulai_tugas" id="editMulaiTugas" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Deadline</label>
          <input type="datetime-local" name="deadline" id="editDeadlineTugas" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Tambah File (opsional)</label>
        <div id="editFileList" class="mb-2 space-y-1 text-sm text-slate-600"></div>
        <input type="file" name="file_tugas[]" multiple class="w-full rounded-lg border border-slate-300 px-4 py-2">
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" id="btnCancelEditTugas" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:opacity-90">Simpan</button>
      </div>
    </form>
  </div>
</div>
<!-- MODAL BUAT TUGAS -->
<div id="tugasModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[80vh] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Buat Tugas</h3>
      <button id="btnCloseTugas" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <form action="{{ route('dosen.tugas.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 overflow-y-auto max-h-[calc(80vh-64px)]">
      @csrf

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
        <select id="kelasSelect" name="nama_kelas_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
          <option value="">Pilih Kelas</option>
          @foreach(($kelas_dosen ?? []) as $kelas)
            @if (($kelas->status ?? '') === 'selesai')
              @continue
            @endif
            <option value="{{ $kelas->id }}" data-matkul-id="{{ $kelas->mataKuliah->id ?? '' }}" data-matkul="{{ $kelas->mataKuliah->mata_kuliah ?? '' }}">
              {{ $kelas->mataKuliah->mata_kuliah ?? '-' }} - Kelas {{ $kelas->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Mata Kuliah</label>
        <input type="text" id="matkulText" class="w-full rounded-lg border border-slate-300 px-4 py-2 bg-slate-50" readonly>
        <input type="hidden" id="matkulId" name="mata_kuliah_id">
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Tugas</label>
        <input type="text" name="nama_tugas" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        <p id="tugasKeInfo" class="mt-1 text-xs text-blue-600 font-semibold">Tugas ke: -</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
        <textarea name="detail_tugas" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Deskripsi tugas"></textarea>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Mulai Tugas</label>
          <input type="datetime-local" name="mulai_tugas" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Deadline</label>
          <input type="datetime-local" name="deadline" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">File Tugas (opsional)</label>
        <ul id="tugasFileList" class="mb-2 space-y-1 text-sm text-slate-600"></ul>
        <input id="tugasFileInput" type="file" name="file_tugas[]" multiple class="w-full rounded-lg border border-slate-300 px-4 py-2">
        <p class="mt-1 text-xs text-slate-500">Kamu bisa pilih file berkali-kali, daftar akan terkumpul di atas.</p>
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" id="btnCancelTugas" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:opacity-90">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  const tugasModal = document.getElementById('tugasModal');
  const btnOpenTugas = document.getElementById('btnOpenTugas');
  const btnCloseTugas = document.getElementById('btnCloseTugas');
  const btnCancelTugas = document.getElementById('btnCancelTugas');
  const kelasSelect = document.getElementById('kelasSelect');
  const matkulText = document.getElementById('matkulText');
  const matkulId = document.getElementById('matkulId');
  const tugasFileInput = document.getElementById('tugasFileInput');
  const tugasFileList = document.getElementById('tugasFileList');
  const tugasFilesStore = new DataTransfer();
  const tugasKeInfo = document.getElementById('tugasKeInfo');
  const editTugasModal = document.getElementById('editTugasModal');
  const btnCloseEditTugas = document.getElementById('btnCloseEditTugas');
  const btnCancelEditTugas = document.getElementById('btnCancelEditTugas');
  const editTugasForm = document.getElementById('editTugasForm');
  const editKelasSelect = document.getElementById('editKelasSelect');
  const editMatkulText = document.getElementById('editMatkulText');
  const editMatkulId = document.getElementById('editMatkulId');
  const editNamaTugas = document.getElementById('editNamaTugas');
  const editDeskripsiTugas = document.getElementById('editDeskripsiTugas');
  const editMulaiTugas = document.getElementById('editMulaiTugas');
  const editDeadlineTugas = document.getElementById('editDeadlineTugas');
  const editFileList = document.getElementById('editFileList');

  const closeTugasModal = () => {
    tugasModal.classList.add('hidden');
    tugasModal.classList.remove('flex');
  };

  btnOpenTugas?.addEventListener('click', () => {
    tugasModal.classList.remove('hidden');
    tugasModal.classList.add('flex');
    if (kelasSelect && kelasSelect.value) {
      const opt = kelasSelect.options[kelasSelect.selectedIndex];
      const next = Number(opt?.dataset?.tugasNext || 1);
      if (tugasKeInfo) {
        tugasKeInfo.textContent = `Tugas ke: ${next}`;
      }
    }
  });

  btnCloseTugas?.addEventListener('click', closeTugasModal);
  btnCancelTugas?.addEventListener('click', closeTugasModal);
  tugasModal?.addEventListener('click', (e) => {
    if (e.target === tugasModal) {
      closeTugasModal();
    }
  });

  kelasSelect?.addEventListener('change', (e) => {
    const opt = kelasSelect.options[kelasSelect.selectedIndex];
    const matkul = opt?.dataset?.matkul || '';
    const matkulIdVal = opt?.dataset?.matkulId || '';
    const next = Number(opt?.dataset?.tugasNext || 1);
    matkulText.value = matkul;
    matkulId.value = matkulIdVal;
    if (tugasKeInfo) {
      tugasKeInfo.textContent = `Tugas ke: ${next}`;
    }
  });

  const closeEditTugasModal = () => {
    editTugasModal.classList.add('hidden');
    editTugasModal.classList.remove('flex');
  };

  btnCloseEditTugas?.addEventListener('click', closeEditTugasModal);
  btnCancelEditTugas?.addEventListener('click', closeEditTugasModal);
  editTugasModal?.addEventListener('click', (e) => {
    if (e.target === editTugasModal) closeEditTugasModal();
  });

  document.querySelectorAll('.btn-edit-tugas').forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const filesEl = btn.dataset.tugasId ? document.getElementById(`tugas-files-${btn.dataset.tugasId}`) : null;
      const files = filesEl ? JSON.parse(filesEl.textContent || '[]') : JSON.parse(btn.dataset.files || '[]');
      editTugasForm.action = `/dosen/tugas/${id}`;
      const kelasId = btn.dataset.kelasId || '';
      const matkulIdVal = btn.dataset.matkulId || '';
      const matkul = btn.dataset.matkul || '';

      editNamaTugas.value = btn.dataset.nama || '';
      editDeskripsiTugas.value = btn.dataset.deskripsi || '';
      editMulaiTugas.value = btn.dataset.mulai || '';
      editDeadlineTugas.value = btn.dataset.deadline || '';

      editKelasSelect.value = kelasId;
      editMatkulText.value = matkul;
      editMatkulId.value = matkulIdVal;

      if (editFileList) {
        editFileList.innerHTML = '';
        if (!files.length) {
          editFileList.innerHTML = '<span class="text-slate-400 text-sm">Belum ada file.</span>';
        } else {
          files.forEach((file) => {
            const row = document.createElement('div');
            row.className = 'flex items-center justify-between rounded-md border border-slate-200 px-3 py-1.5';
            const name = document.createElement('span');
            name.textContent = file.name || 'File';
            name.className = 'truncate';
            const link = document.createElement('a');
            link.href = file.url || '#';
            link.target = '_blank';
            link.className = 'text-blue-600 text-xs hover:underline';
            link.textContent = 'Lihat';
            row.appendChild(name);
            row.appendChild(link);
            editFileList.appendChild(row);
          });
        }
      }

      editTugasModal.classList.remove('hidden');
      editTugasModal.classList.add('flex');
    });
  });

  editKelasSelect?.addEventListener('change', () => {
    const opt = editKelasSelect.options[editKelasSelect.selectedIndex];
    const matkul = opt?.dataset?.matkul || '';
    const matkulIdVal = opt?.dataset?.matkulId || '';
    editMatkulText.value = matkul;
    editMatkulId.value = matkulIdVal;
  });

  const renderTugasFileList = () => {
    if (!tugasFileList) return;
    tugasFileList.innerHTML = '';
    Array.from(tugasFilesStore.files).forEach((file) => {
      const li = document.createElement('li');
      li.textContent = file.name;
      tugasFileList.appendChild(li);
    });
  };

  tugasFileInput?.addEventListener('change', (e) => {
    const files = Array.from(tugasFileInput.files || []);
    files.forEach((file) => tugasFilesStore.items.add(file));
    try {
      tugasFileInput.files = tugasFilesStore.files;
    } catch (err) {
      // Fallback: some browsers block assigning FileList
    }
    renderTugasFileList();
  });
</script>

@php
  $tugasIndexMap = [];
  foreach (($tugas_selesai ?? collect()) as $item) {
      $tugasIndexMap[$item->id] = $item->tugas_ke ?? 1;
  }
  $showTabs = $showTabs ?? true;
@endphp

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Tugas Selesai</h2>
      <p class="text-sm text-slate-500">Daftar tugas yang sudah melewati deadline.</p>
    </div>
  </div>

  @if ($showTabs)
    <div class="mb-6 flex items-center justify-between">
      <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
        <a href="{{ url('/dosen/tugas') }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">Tugas Aktif</a>
        <span class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">Tugas Selesai</span>
      </div>
    </div>
  @endif

  <div class="space-y-4">
    @if (($tugas_selesai ?? collect())->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada tugas selesai.
      </div>
    @endif

    @foreach (($tugas_selesai ?? []) as $tugas)
      @php
        $files = ($tugas->files ?? collect())->map(function ($f) {
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

        @php
          $kuotaKelasLabel = $tugas->kelas->mahasiswas_count ?? 0;
          $kumpulLabel = ($tugas->pengumpulan ?? collect())->count();
        @endphp
        <div>
          <h3 class="font-semibold text-slate-800">{{ $tugas->nama_tugas }}</h3>
          <p class="text-sm text-slate-500 mt-1">{{ $tugas->detail_tugas ?? '-' }}</p>
          <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-500">
            <span class="inline-flex items-center gap-2 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
              <span class="material-symbols-rounded text-sm">school</span>
              <span>Kelas {{ $tugas->kelas->nama_kelas ?? '-' }}</span>
              <span class="text-blue-600 font-semibold">Tugas ke: {{ $tugasIndexMap[$tugas->id] ?? 1 }}</span>
              <span class="text-green-600 font-semibold">{{ $kumpulLabel }} / {{ $kuotaKelasLabel }}</span>
            </span>
          </div>
        </div>

        @php
          $kuotaKelas = $tugas->kelas->mahasiswas_count ?? 0;
          $pengumpulanList = $tugas->pengumpulan ?? collect();
          $kumpulCount = $pengumpulanList->count();
          $dinilaiCount = $pengumpulanList->whereNotNull('nilai')->count();
          $persenKoreksi = $kumpulCount > 0 ? (int) round(($dinilaiCount / $kumpulCount) * 100) : 0;
          $btnKoreksiClass = $persenKoreksi >= 100 ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-amber-100 text-amber-700 hover:bg-amber-200';
        $mahasiswaList = $pengumpulanList->map(function ($row) {
            $foto = $row->mahasiswa->poto_profil ?? '';
            return [
                'nama' => $row->mahasiswa->user->name ?? '-',
                'foto' => $foto ? asset('storage/' . $foto) : asset('img/default_profil.jpg'),
                'submitted_at' => $row->submitted_at ? \Carbon\Carbon::parse($row->submitted_at)->toIso8601String() : null,
            ];
        })->values();
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
        <div class="absolute bottom-4 right-4 flex flex-col items-end gap-2">
          <div class="flex flex-col items-end gap-2">
            <div class="flex items-center gap-2">
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
              <a href="{{ url('/dosen/koreksi_tugas') }}?matkul_id={{ $tugas->mata_kuliah_id }}&kelas_id={{ $tugas->nama_kelas_id }}&tugas_id={{ $tugas->id }}"
                 class="inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-sm font-semibold {{ $btnKoreksiClass }}">
                <span class="material-symbols-rounded text-base">fact_check</span>
                {{ $persenKoreksi }}%
              </a>
              <button
                type="button"
                class="btn-lihat-mahasiswa rounded-full bg-amber-100 px-3 py-1.5 text-sm font-semibold text-amber-700 hover:bg-amber-200"
                data-tugas="{{ $tugas->nama_tugas }}"
                data-kuota="{{ $kuotaKelas }}"
                data-kumpul="{{ $kumpulCount }}"
                data-start="{{ $tugas->mulai_tugas ? \Carbon\Carbon::parse($tugas->mulai_tugas)->toIso8601String() : '' }}"
                data-mahasiswa='@json($mahasiswaList)'
              >
                <span class="material-symbols-rounded text-base">group</span>
              </button>
              @if ($files->isNotEmpty())
                <button
                  type="button"
                  class="btn-preview-tugas rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700"
                  data-files='@json($files)'
                  data-matkul="{{ $tugas->mataKuliah->mata_kuliah ?? '-' }}"
                  data-nama="{{ $tugas->nama_tugas }}"
                  data-deskripsi="{{ $tugas->detail_tugas ?? '-' }}"
                  data-kelas="Kelas {{ $tugas->kelas->nama_kelas ?? '-' }}"
                >
                  <span class="material-symbols-rounded text-base">visibility</span>
                </button>
              @endif
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1"></div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- MODAL LIHAT MAHASISWA -->
<div id="lihatMahasiswaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div class="flex items-center gap-3">
        <h3 class="text-lg font-semibold text-gray-800">Mahasiswa Mengumpulkan</h3>
        <span id="kuotaMahasiswa" class="text-sm font-semibold text-green-600">0 / 0</span>
      </div>
      <button id="btnCloseMahasiswa" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <div class="p-5 text-sm text-slate-600">
      <p id="judulTugasMahasiswa" class="font-semibold text-slate-800 mb-3">-</p>
      <div id="listMahasiswa" class="space-y-2"></div>
      <div id="emptyMahasiswa" class="rounded-lg border border-dashed p-4 text-center text-slate-500">
        Belum ada data pengumpulan.
      </div>
    </div>
  </div>
 </div>

<!-- MODAL PREVIEW (SIMPLE) -->
<div id="previewTugasModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-[70vw] h-[70vh] bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div>
        <h3 id="previewMatkul" class="text-lg font-semibold text-slate-800">Mata Kuliah</h3>
        <p id="previewKelas" class="text-sm text-slate-500">-</p>
      </div>
      <div class="flex items-center gap-2">
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
      </div>
    </div>
  </div>
</div>

<script>
  (() => {
  const lihatMahasiswaModal = document.getElementById('lihatMahasiswaModal');
  const btnCloseMahasiswa = document.getElementById('btnCloseMahasiswa');
  const judulTugasMahasiswa = document.getElementById('judulTugasMahasiswa');
  const kuotaMahasiswa = document.getElementById('kuotaMahasiswa');
  const listMahasiswa = document.getElementById('listMahasiswa');
  const emptyMahasiswa = document.getElementById('emptyMahasiswa');

  const closeMahasiswaModal = () => {
    lihatMahasiswaModal.classList.add('hidden');
    lihatMahasiswaModal.classList.remove('flex');
  };

  document.querySelectorAll('.btn-lihat-mahasiswa').forEach((btn) => {
    btn.addEventListener('click', () => {
      judulTugasMahasiswa.textContent = btn.dataset.tugas || '-';
      const kuota = btn.dataset.kuota || '0';
      const kumpul = btn.dataset.kumpul || '0';
      kuotaMahasiswa.textContent = `${kumpul} / ${kuota}`;

      const startIso = btn.dataset.start || '';
      const startAt = startIso ? Date.parse(startIso) : null;

      const formatDurasi = (startMs, submitMs) => {
        if (!startMs || !submitMs || Number.isNaN(startMs) || Number.isNaN(submitMs)) return '-';
        const diffMs = Math.max(0, submitMs - startMs);
        const totalMin = Math.round(diffMs / 60000);
        if (totalMin < 60) return `${totalMin} menit`;
        const jam = Math.floor(totalMin / 60);
        const menit = totalMin % 60;
        return menit ? `${jam} jam ${menit} menit` : `${jam} jam`;
      };

      const mhs = JSON.parse(btn.dataset.mahasiswa || '[]');
      listMahasiswa.innerHTML = '';
      if (mhs.length === 0) {
        emptyMahasiswa.classList.remove('hidden');
      } else {
        emptyMahasiswa.classList.add('hidden');
        mhs.forEach((item) => {
          const submitAt = item.submitted_at ? Date.parse(item.submitted_at) : null;
          const durasi = formatDurasi(startAt, submitAt);
          const row = document.createElement('div');
          row.className = 'flex items-center gap-3 p-2 rounded-lg border';
          row.innerHTML = `
            <img src="${item.foto || ''}" class="w-10 h-10 rounded-full object-cover border" alt="Foto">
            <div class="text-sm text-slate-700">${item.nama || '-'}</div>
            <div class="ml-auto text-xs text-slate-500">Mengumpulkan dalam ${durasi}</div>
          `;
          listMahasiswa.appendChild(row);
        });
      }
      lihatMahasiswaModal.classList.remove('hidden');
      lihatMahasiswaModal.classList.add('flex');
    });
  });

  btnCloseMahasiswa?.addEventListener('click', closeMahasiswaModal);
  lihatMahasiswaModal?.addEventListener('click', (e) => {
    if (e.target === lihatMahasiswaModal) closeMahasiswaModal();
  });

  const previewModal = document.getElementById('previewTugasModal');
  const btnClosePreview = document.getElementById('btnClosePreview');
  const previewContainer = document.getElementById('previewContainer');
  const previewMatkul = document.getElementById('previewMatkul');
  const previewNama = document.getElementById('previewNama');
  const previewDeskripsi = document.getElementById('previewDeskripsi');
  const previewKelas = document.getElementById('previewKelas');
  const previewDownload = document.getElementById('previewDownload');
  const previewFileList = document.getElementById('previewFileList');

  const closePreview = () => {
    previewModal.classList.add('hidden');
    previewModal.classList.remove('flex');
    previewContainer.innerHTML = 'Tidak ada file.';
    previewFileList.innerHTML = '';
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
      return;
    }
    renderPreview(file.url, file.ext);
    previewDownload.href = file.url || '#';
  };

  document.querySelectorAll('.btn-preview-tugas').forEach((btn) => {
    btn.addEventListener('click', () => {
      const files = JSON.parse(btn.dataset.files || '[]');

      previewMatkul.textContent = btn.dataset.matkul || '-';
      previewNama.textContent = btn.dataset.nama || '-';
      previewDeskripsi.textContent = btn.dataset.deskripsi || '-';
      previewKelas.textContent = btn.dataset.kelas || '-';

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

      previewModal.classList.remove('hidden');
      previewModal.classList.add('flex');
    });
  });

  btnClosePreview?.addEventListener('click', closePreview);
  previewModal?.addEventListener('click', (e) => {
    if (e.target === previewModal) closePreview();
  });
  })();
</script>

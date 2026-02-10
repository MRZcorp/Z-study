@php
  $tugas_selesai = $tugas_selesai ?? collect();
@endphp

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Tugas Selesai</h2>
      <p class="text-sm text-slate-500">Daftar tugas yang sudah melewati deadline.</p>
    </div>
  </div>

  <div class="space-y-4">
    @if ($tugas_selesai->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada tugas selesai.
      </div>
    @endif

    @foreach ($tugas_selesai as $tugas)
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

        $pengumpulan = ($tugas->pengumpulan ?? collect())->first();
        $pengumpulanFile = $pengumpulan && $pengumpulan->file_path ? asset('storage/' . $pengumpulan->file_path) : '';
        $pengumpulanName = $pengumpulan->file_name ?? '';
        $pengumpulanDesc = $pengumpulan->deskripsi ?? '';
        $pengumpulanSubmitted = $pengumpulan && $pengumpulan->submitted_at
            ? \Carbon\Carbon::parse($pengumpulan->submitted_at)->format('H:i d M Y')
            : '';
        $nilai = $pengumpulan->nilai ?? null;
        $nilaiLabel = is_null($nilai) ? '?' : $nilai;
        $nilaiClass = 'text-slate-400';
        if (!is_null($nilai)) {
            if ($nilai < 60) {
                $nilaiClass = 'text-red-600';
            } elseif ($nilai < 70) {
                $nilaiClass = 'text-amber-600';
            } elseif ($nilai < 90) {
                $nilaiClass = 'text-blue-600';
            } else {
                $nilaiClass = 'text-emerald-600';
            }
        }
        $tidakMengumpulkan = !$pengumpulan || !$pengumpulan->submitted_at;
        if ($tidakMengumpulkan) {
            $nilaiLabel = 0;
            $nilaiClass = 'text-red-600';
        }

        $nilaiKecepatan = 0;
        $nilaiInput = 0;
        $waktuMengumpulkan = 0;
        if (!$tidakMengumpulkan && $tugas->mulai_tugas && $tugas->deadline && $pengumpulan && $pengumpulan->submitted_at) {
            $start = \Carbon\Carbon::parse($tugas->mulai_tugas);
            $deadline = \Carbon\Carbon::parse($tugas->deadline);
            $nilaiInput = max(0, $start->diffInMinutes($deadline));
            $submit = \Carbon\Carbon::parse($pengumpulan->submitted_at);
            $waktuMengumpulkan = max(0, $start->diffInMinutes($submit));

            $nilaiTercepat = 3.5;
            $nilaiPoin = 100;
            $totalLoop = 51;
            $hasilNilai = 0;
            if ($nilaiInput > 0) {
                $prevLimit = 0;
                for ($loop = 0; $loop < $totalLoop; $loop++) {
                    $nilaiTercepatLoop = $nilaiTercepat - ($loop * 0.05);
                    $nilaiPoinLoop = $nilaiPoin - $loop;
                    if ($nilaiTercepatLoop <= 0) {
                        continue;
                    }
                    $limit = $nilaiInput / $nilaiTercepatLoop;
                    if ($waktuMengumpulkan > $prevLimit && $waktuMengumpulkan <= $limit) {
                        $hasilNilai = $nilaiPoinLoop;
                        break;
                    }
                    $prevLimit = $limit;
                }
            }
            $nilaiKecepatan = $hasilNilai;
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

        <div>
          <h3 class="font-semibold text-slate-800 pr-40">{{ $tugas->nama_tugas ?? 'Nama Tugas' }}</h3>
          <p class="text-sm text-slate-500 mt-1">{{ $tugas->detail_tugas ?? '-' }}</p>
          <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
              <span class="material-symbols-rounded text-sm">school</span>
              Kelas {{ $tugas->kelas->nama_kelas ?? '-' }}
              <span class="text-blue-600 font-semibold">Tugas ke: {{ $tugas->tugas_ke ?? 1 }}</span>
            </span>
          </div>
        </div>

        @if ($files->isNotEmpty() || $pengumpulanFile)
          <div class="absolute bottom-4 right-4 flex items-center gap-3">
            @if ($tidakMengumpulkan)
              <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">
                Tidak Mengumpulkan
              </span>
            @endif
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-3xl font-bold {{ $nilaiClass }}">
              {{ $nilaiLabel }}
            </div>
            <button
              type="button"
              class="btn-nilai-kecepatan w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center text-xl font-semibold text-yellow-800 hover:bg-yellow-200"
              data-nilai="{{ $nilaiKecepatan }}"
              data-waktu="{{ $waktuMengumpulkan }}"
              data-nilai-input="{{ $nilaiInput }}"
            >
              {{ $nilaiKecepatan }}
            </button>
            <button
              type="button"
              class="btn-preview-tugas rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700"
              data-files='@json($files)'
              data-matkul="{{ $tugas->mataKuliah->mata_kuliah ?? '-' }}"
              data-nama="{{ $tugas->nama_tugas }}"
              data-deskripsi="{{ $tugas->detail_tugas ?? '-' }}"
              data-kelas="Kelas {{ $tugas->kelas->nama_kelas ?? '-' }}"
              data-mhs-file="{{ $pengumpulanFile }}"
              data-mhs-name="{{ $pengumpulanName }}"
              data-mhs-desc="{{ $pengumpulanDesc }}"
              data-mhs-submitted="{{ $pengumpulanSubmitted }}"
              data-mulai="{{ $tugas->mulai_tugas ? \Carbon\Carbon::parse($tugas->mulai_tugas)->format('d M Y H:i') : '-' }}"
              data-deadline="{{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('d M Y H:i') : '-' }}"
            >
              <span class="material-symbols-rounded text-base">visibility</span>
            </button>
          </div>
        @endif
      </div>
    @endforeach
  </div>
</div>

<!-- MODAL NILAI KECEPATAN -->
<div id="nilaiKecepatanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-xl bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-slate-800">Detail Nilai Kecepatan</h3>
      <button id="btnCloseNilaiKecepatan" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <div class="p-5 space-y-4 text-sm text-slate-700">
      <div class="rounded-lg bg-yellow-50 border border-yellow-100 px-3 py-2 text-yellow-800 font-semibold space-y-1">
        <div>Waktu mengerjakan = <span id="nilaiKecepatanInput">-</span></div>
        <div>Waktu mengumpulkan = <span id="nilaiKecepatanWaktu">-</span></div>
      </div>
      <div class="rounded-lg border bg-slate-50 p-4">
        <div class="text-xs font-semibold text-slate-600 mb-2">Detail Poin Kecepatan (51)</div>
        <div id="nilaiKecepatanLoop" class="max-h-64 overflow-y-auto text-xs text-slate-700 space-y-1"></div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PREVIEW (READ ONLY) -->
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
        <button id="btnToggleMhs" type="button" class="rounded-full bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-emerald-700">
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
      </div>
      <div class="lg:col-span-1 flex flex-col gap-3 h-full min-h-0">
        <div>
          <p id="previewNama" class="font-semibold text-slate-800">-</p>
        </div>
        <div>
          <p class="text-xs text-slate-500">Deskripsi</p>
          <p id="previewDeskripsi" class="text-sm text-slate-700">-</p>
        </div>
        <div id="previewSubmittedWrap">
          <p class="text-xs text-slate-500">Dikumpulkan</p>
          <p id="previewSubmitted" class="text-sm text-slate-700">-</p>
        </div>
        <div class="mt-auto">
          <p class="text-xs text-slate-500">File</p>
          <div id="previewFileList" class="mt-1 flex flex-col gap-1 text-sm text-blue-700 max-h-40 overflow-y-auto pr-1"></div>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-500">
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
  (() => {
    const nilaiKecepatanModal = document.getElementById('nilaiKecepatanModal');
    const btnCloseNilaiKecepatan = document.getElementById('btnCloseNilaiKecepatan');
    const nilaiKecepatanInput = document.getElementById('nilaiKecepatanInput');
    const nilaiKecepatanWaktu = document.getElementById('nilaiKecepatanWaktu');
    const nilaiKecepatanLoop = document.getElementById('nilaiKecepatanLoop');

    document.querySelectorAll('.btn-nilai-kecepatan').forEach((btn) => {
      btn.addEventListener('click', () => {
        const waktuMengumpulkan = Number(btn.dataset.waktu || 0);
        const nilaiInput = Number(btn.dataset.nilaiInput || 0);
        if (nilaiKecepatanInput) {
          nilaiKecepatanInput.textContent = `${nilaiInput.toFixed(2)} menit`;
        }
        if (nilaiKecepatanWaktu) {
          nilaiKecepatanWaktu.textContent = `${waktuMengumpulkan} menit`;
        }
        if (nilaiKecepatanLoop) {
          nilaiKecepatanLoop.innerHTML = '';
          const totalLoop = 51;
          let prevLimit = 0;
          for (let i = 0; i < totalLoop; i++) {
            const nilaiTercepatLoop = 3.5 - (i * 0.05);
            const nilaiPoinLoop = 100 - i;
            if (nilaiTercepatLoop <= 0 || nilaiInput <= 0) continue;
            const limit = nilaiInput / nilaiTercepatLoop;
            const active = waktuMengumpulkan > prevLimit && waktuMengumpulkan <= limit;
            const row = document.createElement('div');
            row.className = `rounded-md px-2 py-1 ${active ? 'bg-yellow-100 text-yellow-800 font-semibold' : ''}`;
            row.innerHTML = `<span>${i + 1}. Nilai ${nilaiPoinLoop} = ${prevLimit.toFixed(2)} - ${limit.toFixed(2)} menit</span>`;
            nilaiKecepatanLoop.appendChild(row);
            prevLimit = limit;
          }
        }
        nilaiKecepatanModal?.classList.remove('hidden');
        nilaiKecepatanModal?.classList.add('flex');
      });
    });

    btnCloseNilaiKecepatan?.addEventListener('click', () => {
      nilaiKecepatanModal?.classList.add('hidden');
      nilaiKecepatanModal?.classList.remove('flex');
    });

    nilaiKecepatanModal?.addEventListener('click', (e) => {
      if (e.target === nilaiKecepatanModal) {
        nilaiKecepatanModal.classList.add('hidden');
        nilaiKecepatanModal.classList.remove('flex');
      }
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
    const previewSubmitted = document.getElementById('previewSubmitted');
    const previewSubmittedWrap = document.getElementById('previewSubmittedWrap');
    const previewMulai = document.getElementById('previewMulai');
    const previewDeadline = document.getElementById('previewDeadline');
    const btnToggleMhs = document.getElementById('btnToggleMhs');

    let showingMhs = false;
    let lastFiles = [];
    let lastMhs = null;

    const closePreview = () => {
      previewModal?.classList.add('hidden');
      previewModal?.classList.remove('flex');
      if (previewContainer) previewContainer.innerHTML = 'Tidak ada file.';
      if (previewFileList) previewFileList.innerHTML = '';
      if (previewDownload) previewDownload.href = '#';
      showingMhs = false;
      lastFiles = [];
      lastMhs = null;
    };

    const renderPreview = (url, ext) => {
      const lower = (ext || '').toLowerCase();
      if (!url) {
        if (previewContainer) previewContainer.innerHTML = 'Tidak ada file.';
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

    const setFileList = (files) => {
      if (!previewFileList) return;
      previewFileList.innerHTML = '';
      if (!files.length) {
        previewFileList.innerHTML = '<span class="text-slate-400 text-sm">Tidak ada file.</span>';
        setActiveFile(null);
        return;
      }
      files.forEach((file, idx) => {
        const btnFile = document.createElement('button');
        btnFile.type = 'button';
        btnFile.className = 'text-left hover:underline';
        btnFile.textContent = file.name || `File ${idx + 1}`;
        btnFile.addEventListener('click', () => setActiveFile(file));
        previewFileList.appendChild(btnFile);
      });
      setActiveFile(files[0]);
    };

    const showFiles = () => {
      showingMhs = false;
      if (btnToggleMhs) btnToggleMhs.classList.remove('opacity-70');
      if (previewSubmittedWrap) previewSubmittedWrap.classList.remove('hidden');
      setFileList(lastFiles);
    };

    const showMhsFile = () => {
      showingMhs = true;
      if (btnToggleMhs) btnToggleMhs.classList.add('opacity-70');
      if (previewSubmittedWrap) previewSubmittedWrap.classList.remove('hidden');
      if (!lastMhs || !lastMhs.url) {
        setFileList([]);
        if (previewSubmitted) previewSubmitted.textContent = '-';
        return;
      }
      setFileList([lastMhs]);
    };

    btnToggleMhs?.addEventListener('click', () => {
      if (showingMhs) {
        showFiles();
      } else {
        showMhsFile();
      }
    });

    document.querySelectorAll('.btn-preview-tugas').forEach((btn) => {
      btn.addEventListener('click', () => {
        const files = JSON.parse(btn.dataset.files || '[]');
        lastFiles = files;
        lastMhs = btn.dataset.mhsFile
          ? { name: btn.dataset.mhsName || 'File Mahasiswa', url: btn.dataset.mhsFile, ext: (btn.dataset.mhsFile || '').split('.').pop() }
          : null;

        previewMatkul.textContent = btn.dataset.matkul || '-';
        previewNama.textContent = btn.dataset.nama || '-';
        previewDeskripsi.textContent = btn.dataset.deskripsi || '-';
        previewKelas.textContent = btn.dataset.kelas || '-';
        previewMulai.textContent = btn.dataset.mulai || '-';
        previewDeadline.textContent = btn.dataset.deadline || '-';
        previewSubmitted.textContent = btn.dataset.mhsSubmitted || '-';

        showFiles();
        if (previewSubmittedWrap) {
          previewSubmittedWrap.classList.toggle('hidden', !btn.dataset.mhsFile);
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

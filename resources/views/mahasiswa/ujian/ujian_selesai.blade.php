<x-header>Ujian Selesai</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

@php
  $ujian_kelas = $ujian_kelas ?? collect();
@endphp

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Ujian Selesai</h2>
      <p class="text-sm text-slate-500">Daftar ujian yang sudah melewati deadline.</p>
    </div>
  </div>

  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
      <a href="{{ url('/mahasiswa/ujian') }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">Ujian Aktif</a>
      <span class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">Ujian Selesai</span>
    </div>
    @php
      $matkulOptions = $ujian_kelas->map(function ($u) {
        return $u->mataKuliah->mata_kuliah ?? null;
      })->filter()->unique()->values();
    @endphp
    <div class="flex items-center gap-2">
      <label for="filterMatkul" class="text-sm text-slate-500">Filter</label>
      <select id="filterMatkul" class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-700">
        <option value="">Semua Mata Kuliah</option>
        @foreach ($matkulOptions as $matkul)
          <option value="{{ $matkul }}">{{ $matkul }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="space-y-4">
    @if ($ujian_kelas->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada ujian selesai.
      </div>
    @endif

    @foreach ($ujian_kelas as $ujian)
      @php
        $jawabanUjian = $jawabanMap[$ujian->id] ?? [];
        $nilai = $nilaiMap[$ujian->id] ?? null;
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
        $soalPreview = ($ujian->soals ?? collect())->map(function ($s) use ($jawabanUjian) {
            $jawab = $jawabanUjian[$s->id] ?? null;
            return [
                'tipe' => $s->tipe,
                'pertanyaan' => $s->pertanyaan,
                'bobot' => $s->bobot,
                'options' => $s->options,
                'pg_correct' => $s->pg_correct,
                'jawaban_pg' => $jawab['jawaban_pg'] ?? null,
                'jawaban_text' => $jawab['jawaban_text'] ?? null,
            ];
        })->values();

        $nilaiKecepatan = 0;
        $nilaiInput = 0;
        $waktuMengumpulkan = 0;
        $hasilUjianRow = ($ujian->hasilUjian ?? collect())->first();
        if ($ujian->mulai_ujian && $ujian->deadline && $hasilUjianRow?->submitted_at) {
            $start = \Carbon\Carbon::parse($ujian->mulai_ujian);
            $deadline = \Carbon\Carbon::parse($ujian->deadline);
            $nilaiInput = max(0, $start->diffInMinutes($deadline));
            $submit = \Carbon\Carbon::parse($hasilUjianRow->submitted_at);
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
      <div class="ujian-card bg-white rounded-xl border p-5 flex flex-col gap-4 relative" data-matkul="{{ $ujian->mataKuliah->mata_kuliah ?? '-' }}">
        <div class="absolute top-4 right-4 flex items-center gap-2 text-xs text-slate-500">
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700">
            <span class="material-symbols-rounded text-sm">schedule</span>
            {{ $ujian->mulai_ujian ? \Carbon\Carbon::parse($ujian->mulai_ujian)->format('d M Y H:i') : '-' }}
          </span>
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-red-50 text-red-700">
            <span class="material-symbols-rounded text-sm">event</span>
            {{ $ujian->deadline ? \Carbon\Carbon::parse($ujian->deadline)->format('d M Y H:i') : '-' }}
          </span>
        </div>

        <div class="flex items-start justify-between gap-3">
          <div class="inline-flex items-center gap-2 text-xs font-semibold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full w-fit">
            {{ $ujian->mataKuliah->mata_kuliah ?? '-' }}
          </div>
        </div>

        <div>
          <h3 class="font-semibold text-slate-800 pr-40">{{ $ujian->nama_ujian ?? 'Nama Ujian' }}</h3>
          <p class="text-sm text-slate-500 mt-1">{{ $ujian->deskripsi ?? '-' }}</p>
          <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-500">
            <span class="inline-flex items-center gap-2 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
              <span class="material-symbols-rounded text-sm">school</span>
              <span>Kelas {{ $ujian->kelas->nama_kelas ?? '-' }}</span>
              <span class="text-blue-600 font-semibold">Ujian ke: {{ $ujian->ujian_ke ?? 1 }}</span>
            </span>
          </div>
        </div>

        <div class="absolute bottom-4 right-4 flex items-center gap-2">
          <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-lg font-bold {{ $nilaiClass }}">
            {{ $nilaiLabel }}
          </div>
          <button
            type="button"
            class="btn-nilai-kecepatan w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-base font-semibold text-yellow-800 hover:bg-yellow-200"
            data-nilai="{{ $nilaiKecepatan }}"
            data-waktu="{{ $waktuMengumpulkan }}"
            data-nilai-input="{{ $nilaiInput }}"
          >
            {{ $nilaiKecepatan }}
          </button>
          <button
            type="button"
            class="btn-preview-ujian inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            data-matkul="{{ $ujian->mataKuliah->mata_kuliah ?? '-' }}"
            data-nama="{{ $ujian->nama_ujian ?? 'Ujian' }}"
            data-deskripsi="{{ $ujian->deskripsi ?? '-' }}"
            data-kelas="Kelas {{ $ujian->kelas->nama_kuliah ?? '-' }}"
            data-mulai="{{ $ujian->mulai_ujian ? \Carbon\Carbon::parse($ujian->mulai_ujian)->format('d M Y H:i') : '-' }}"
            data-deadline="{{ $ujian->deadline ? \Carbon\Carbon::parse($ujian->deadline)->format('d M Y H:i') : '-' }}"
            data-soals='@json($soalPreview)'
          >
            <span class="material-symbols-rounded text-base">visibility</span>
            Preview
          </button>
        </div>
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

<!-- MODAL PREVIEW UJIAN (JAWABAN MAHASISWA) -->
<div id="previewUjianModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div>
        <h3 id="previewMatkul" class="text-lg font-semibold text-slate-800">Ujian</h3>
        <p id="previewKelas" class="text-sm text-slate-500">-</p>
      </div>
      <button id="btnClosePreview" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
      <div class="flex items-start justify-between gap-3">
        <div>
          <p id="previewNama" class="font-semibold text-slate-800">-</p>
          <p class="text-sm text-slate-500 mt-1" id="previewDeskripsi">-</p>
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
      <div class="border-t pt-4">
        <h4 class="text-sm font-semibold text-slate-700 mb-3">Preview Soal</h4>
        <div id="previewSoalList" class="space-y-3"></div>
      </div>
    </div>
  </div>
</div>

<script>
  const filterMatkul = document.getElementById('filterMatkul');
  const ujianCards = document.querySelectorAll('.ujian-card');

  const applyFilter = () => {
    const selected = (filterMatkul?.value || '').toLowerCase();
    ujianCards.forEach((card) => {
      const matkul = (card.dataset.matkul || '').toLowerCase();
      if (!selected || matkul === selected) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });
  };

  filterMatkul?.addEventListener('change', applyFilter);

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

  const previewModal = document.getElementById('previewUjianModal');
  const btnClosePreview = document.getElementById('btnClosePreview');
  const previewMatkul = document.getElementById('previewMatkul');
  const previewNama = document.getElementById('previewNama');
  const previewDeskripsi = document.getElementById('previewDeskripsi');
  const previewKelas = document.getElementById('previewKelas');
  const previewMulai = document.getElementById('previewMulai');
  const previewDeadline = document.getElementById('previewDeadline');
  const previewSoalList = document.getElementById('previewSoalList');

  const closePreview = () => {
    previewModal.classList.add('hidden');
    previewModal.classList.remove('flex');
    if (previewSoalList) previewSoalList.innerHTML = '';
  };

  const renderSoalPreview = (soals) => {
    if (!previewSoalList) return;
    previewSoalList.innerHTML = '';
    if (!Array.isArray(soals) || soals.length === 0) {
      previewSoalList.innerHTML = '<div class="text-sm text-slate-500">Belum ada soal.</div>';
      return;
    }
    soals.forEach((soal, idx) => {
      const tipe = (soal?.tipe || 'essay').toLowerCase();
      const badgeClass = tipe === 'pg' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600';
      const badgeText = tipe === 'pg' ? 'PG' : 'Essay';
      const item = document.createElement('div');
      item.className = 'rounded-xl border bg-white p-4';
      const optionsHtml = (tipe === 'pg' && Array.isArray(soal.options))
        ? `<div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-slate-600">
            ${soal.options.map((opt, i) => {
              const letter = String.fromCharCode(65 + i);
              const isAnswered = (soal.jawaban_pg || '').toUpperCase() === letter;
              const isCorrect = (soal.pg_correct || '').toUpperCase() === letter;
              const isAnsweredCorrect = isAnswered && isCorrect;
              return `
                <div class="flex items-center gap-2">
                  <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold ${isAnswered ? 'bg-blue-600 text-white' : (isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600')}">
                    ${letter}
                  </span>
                  <span class="${isAnswered ? 'text-blue-600 font-semibold' : (isCorrect ? 'text-emerald-600 font-semibold' : '')}">${opt}</span>
                  ${isAnsweredCorrect ? '<span class="material-symbols-rounded text-emerald-600 text-base">check_circle</span>' : ''}
                </div>
              `;
            }).join('')}
          </div>`
        : '';
      const essayHtml = (tipe === 'essay')
        ? `<div class="mt-3 text-sm text-slate-700 rounded-lg border bg-slate-50 px-3 py-2">${soal.jawaban_text || '<span class="text-slate-400">Belum dijawab.</span>'}</div>`
        : '';
      item.innerHTML = `
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-semibold">${idx + 1}</span>
            <div class="text-sm font-semibold text-slate-800">${soal.pertanyaan || '-'}</div>
          </div>
          <div class="flex items-center gap-2 text-xs">
            <span class="font-semibold text-emerald-600">+${soal.bobot ?? 0}</span>
            <span class="px-2 py-0.5 rounded-md font-semibold uppercase ${badgeClass}">${badgeText}</span>
          </div>
        </div>
        ${optionsHtml}
        ${essayHtml}
      `;
      previewSoalList.appendChild(item);
    });
  };

  document.querySelectorAll('.btn-preview-ujian').forEach((btn) => {
    btn.addEventListener('click', () => {
      previewMatkul.textContent = btn.dataset.matkul || '-';
      previewNama.textContent = btn.dataset.nama || '-';
      previewDeskripsi.textContent = btn.dataset.deskripsi || '-';
      previewKelas.textContent = btn.dataset.kelas || '-';
      previewMulai.textContent = btn.dataset.mulai || '-';
      previewDeadline.textContent = btn.dataset.deadline || '-';
      const soals = JSON.parse(btn.dataset.soals || '[]');
      renderSoalPreview(soals);

      previewModal.classList.remove('hidden');
      previewModal.classList.add('flex');
    });
  });

  btnClosePreview?.addEventListener('click', closePreview);
  previewModal?.addEventListener('click', (e) => {
    if (e.target === previewModal) closePreview();
  });
</script>

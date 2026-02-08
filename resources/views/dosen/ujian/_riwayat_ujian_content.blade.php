@php
  $now = \Carbon\Carbon::now();
  $ujian_kelas = $ujian_kelas ?? collect();
  $ujian_selesai = $ujian_selesai ?? $ujian_kelas->filter(function ($ujian) use ($now) {
      $deadline = $ujian->deadline ?? null;
      return $deadline ? \Carbon\Carbon::parse($deadline)->isPast() : false;
  });
@endphp

<!-- MODAL LIHAT MAHASISWA -->
<div id="lihatMahasiswaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div class="flex items-center gap-3">
        <h3 class="text-lg font-semibold text-gray-800">Peserta Ujian</h3>
        <span id="kuotaMahasiswa" class="text-sm font-semibold text-green-600">0 / 0</span>
      </div>
      <button id="btnCloseMahasiswa" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <div class="p-5 text-sm text-slate-600">
      <p id="judulUjianMahasiswa" class="font-semibold text-slate-800 mb-3">-</p>
      <div id="listMahasiswa" class="space-y-2"></div>
      <div id="emptyMahasiswa" class="rounded-lg border border-dashed p-4 text-center text-slate-500">
        Belum ada data peserta.
      </div>
    </div>
  </div>
</div>

<!-- MODAL PREVIEW UJIAN -->
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

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Ujian Selesai</h2>
      <p class="text-sm text-slate-500">Daftar ujian yang sudah melewati deadline.</p>
    </div>
  </div>

  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
      <a href="{{ url('/dosen/ujian') }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">Ujian Aktif</a>
      <span class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">Ujian Selesai</span>
    </div>
  </div>

  <div class="space-y-4">
    @if ($ujian_selesai->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada ujian selesai.
      </div>
    @endif

    @foreach ($ujian_selesai as $ujian)
      @php
        $mahasiswaList = ($ujian->hasilUjian ?? collect())->map(function ($row) {
            $mhs = $row->mahasiswa ?? null;
            $foto = $mhs?->poto_profil ?? '';
            return [
                'nama' => $mhs?->user?->name ?? '-',
                'foto' => $foto ? asset('storage/' . $foto) : asset('img/default_profil.jpg'),
                'waktu' => $row->submitted_at ? \Carbon\Carbon::parse($row->submitted_at)->format('d M Y H:i') : '-',
            ];
        })->values();
        $kuotaKelas = $ujian->kelas->mahasiswas_count ?? ($ujian->kelas->mahasiswas()->count() ?? 0);
        $kumpulCount = ($ujian->hasilUjian ?? collect())->whereNotNull('submitted_at')->count();
        $dinilaiCount = ($ujian->hasilUjian ?? collect())->whereNotNull('nilai')->count();
        $persenKoreksi = $kumpulCount > 0 ? (int) round(($dinilaiCount / $kumpulCount) * 100) : 0;
        $btnKoreksiClass = $persenKoreksi >= 100 ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-amber-100 text-amber-700 hover:bg-amber-200';
        $soalPreview = ($ujian->soals ?? collect())->map(function ($s) {
            return [
                'tipe' => $s->tipe,
                'pertanyaan' => $s->pertanyaan,
                'bobot' => $s->bobot,
                'options' => $s->options,
                'pg_correct' => $s->pg_correct,
            ];
        })->values();
      @endphp
      <div class="bg-white rounded-xl border p-5 flex flex-col gap-4 relative">
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
          <div class="flex items-start justify-between gap-3">
            <h3 class="font-semibold text-slate-800">{{ $ujian->nama_ujian ?? 'Nama Ujian' }}</h3>
          </div>
          <p class="text-sm text-slate-500 mt-1">{{ $ujian->deskripsi ?? '-' }}</p>
          <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-500">
            <span class="inline-flex items-center gap-2 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
              <span class="material-symbols-rounded text-sm">school</span>
              <span>Kelas {{ $ujian->kelas->nama_kelas ?? '-' }}</span>
              <span class="text-green-600 font-semibold">{{ $kumpulCount }} / {{ $kuotaKelas }}</span>
              <span class="text-blue-600 font-semibold">Ujian ke: {{ $ujian->ujian_ke ?? 1 }}</span>
            </span>
          </div>
        </div>

        <div class="absolute bottom-4 right-4 flex items-center gap-2">
          <a href="{{ url('/dosen/koreksi_ujian/' . $ujian->id) }}" class="inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-sm font-semibold {{ $btnKoreksiClass }}">
            <span class="material-symbols-rounded text-base">fact_check</span>
            {{ $persenKoreksi }}%
          </a>
          <button
            type="button"
            class="btn-lihat-mahasiswa rounded-full bg-amber-100 px-3 py-1.5 text-sm font-semibold text-amber-700 hover:bg-amber-200"
            data-ujian="{{ $ujian->nama_ujian ?? 'Ujian' }}"
            data-kuota="{{ $kuotaKelas }}"
            data-kumpul="{{ $kumpulCount }}"
            data-mahasiswa='@json($mahasiswaList)'
          >
            <span class="material-symbols-rounded text-base">group</span>
          </button>
          <button
            type="button"
            class="btn-preview-ujian rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700"
            data-matkul="{{ $ujian->mataKuliah->mata_kuliah ?? '-' }}"
            data-nama="{{ $ujian->nama_ujian ?? 'Ujian' }}"
            data-deskripsi="{{ $ujian->deskripsi ?? '-' }}"
            data-kelas="Kelas {{ $ujian->kelas->nama_kelas ?? '-' }}"
            data-mulai="{{ $ujian->mulai_ujian ? \Carbon\Carbon::parse($ujian->mulai_ujian)->format('d M Y H:i') : '-' }}"
            data-deadline="{{ $ujian->deadline ? \Carbon\Carbon::parse($ujian->deadline)->format('d M Y H:i') : '-' }}"
            data-soals='@json($soalPreview)'
          >
            <span class="material-symbols-rounded text-base">visibility</span>
          </button>
        </div>
      </div>
    @endforeach
  </div>
</div>

<script>
  const lihatMahasiswaModal = document.getElementById('lihatMahasiswaModal');
  const btnCloseMahasiswa = document.getElementById('btnCloseMahasiswa');
  const judulUjianMahasiswa = document.getElementById('judulUjianMahasiswa');
  const kuotaMahasiswa = document.getElementById('kuotaMahasiswa');
  const listMahasiswa = document.getElementById('listMahasiswa');
  const emptyMahasiswa = document.getElementById('emptyMahasiswa');

  const closeMahasiswaModal = () => {
    lihatMahasiswaModal.classList.add('hidden');
    lihatMahasiswaModal.classList.remove('flex');
  };

  document.querySelectorAll('.btn-lihat-mahasiswa').forEach((btn) => {
    btn.addEventListener('click', () => {
      judulUjianMahasiswa.textContent = btn.dataset.ujian || '-';
      const kuota = btn.dataset.kuota || '0';
      const kumpul = btn.dataset.kumpul || '0';
      kuotaMahasiswa.textContent = `${kumpul} / ${kuota}`;
      const mhs = JSON.parse(btn.dataset.mahasiswa || '[]');
      listMahasiswa.innerHTML = '';
      if (mhs.length === 0) {
        emptyMahasiswa.classList.remove('hidden');
      } else {
        emptyMahasiswa.classList.add('hidden');
        mhs.forEach((item) => {
          const row = document.createElement('div');
          row.className = 'flex items-center gap-3 p-2 rounded-lg border';
          row.innerHTML = `
            <img src="${item.foto || ''}" class="w-10 h-10 rounded-full object-cover border" alt="Foto">
            <div class="text-sm text-slate-700">${item.nama || '-'}</div>
            <div class="ml-auto text-xs text-slate-500">${item.waktu || '-'}</div>
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
              const isCorrect = (soal.pg_correct || '').toUpperCase() === letter;
              return `
                <div class="flex items-center gap-2">
                  <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold ${isCorrect ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600'}">
                    ${letter}
                  </span>
                  <span>${opt}</span>
                </div>
              `;
            }).join('')}
          </div>`
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

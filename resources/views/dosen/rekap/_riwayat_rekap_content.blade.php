
@php
  $kelas = $kelas ?? null;
  $tugasList = $tugasList ?? collect();
  $ujianList = $ujianList ?? collect();
  $pengumpulanMap = $pengumpulanMap ?? collect();
  $hasilUjianMap = $hasilUjianMap ?? collect();
  $rekapMap = $rekapMap ?? collect();
  $showBack = $showBack ?? false;
@endphp

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center gap-3">
    @if ($showBack)
      <a href="{{ url('/dosen/rekap') }}"
         class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white shadow hover:bg-blue-700">
        <span class="material-symbols-rounded text-base">chevron_left</span>
      </a>
    @endif
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Rekap Nilai</h2>
      <p class="text-sm text-slate-500">Kelas {{ $kelas->nama_kelas ?? '-' }} • {{ $kelas->mataKuliah->mata_kuliah ?? '-' }}</p>
    </div>
  </div>

  <div class="rounded-xl border bg-white overflow-hidden">
    <!-- HEADER -->
<div class="px-4 py-3 border-b">
  <div class="flex items-center justify-between gap-3">
    <div>
      <h3 class="font-semibold text-slate-800">Tabel Rekap</h3>
      <span class="text-xs text-slate-500">
        {{ ($kelas->mahasiswas ?? collect())->count() }} mahasiswa
      </span>
    </div>
    <div class="flex items-center gap-2">
      <button id="btnColPrev" type="button"
        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">
        <span class="material-symbols-rounded text-sm">chevron_left</span>
      </button>
      <button id="btnColNext" type="button"
        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">
        <span class="material-symbols-rounded text-sm">chevron_right</span>
      </button>
    </div>
  </div>
  <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
    <div class="w-full sm:w-[260px]">
      <div class="relative">
        <span class="material-symbols-rounded text-base text-slate-400 absolute left-3 top-1/2 -translate-y-1/2">search</span>
        <input id="searchRekapInput" type="text" placeholder="Cari nama / NIM..."
          class="w-full pl-9 pr-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
      </div>
    </div>
  </div>
</div>

<!-- TABLE WRAPPER -->
<div class="overflow-x-auto relative">
  <table class="w-max text-sm table-fixed border-collapse">

    <!-- THEAD -->
    <thead class="bg-slate-50 text-slate-600">
      <tr>
        <th class="px-2 py-2 text-center rekap-fixed whitespace-nowrap"
            style="width:40px;min-width:40px;">No.</th>

        <th class="px-2 py-2 text-left rekap-fixed whitespace-nowrap"
            style="width:200px;min-width:200px;">Nama Mahasiswa</th>

        <th class="px-2 py-2 text-center rekap-fixed whitespace-nowrap"
            style="width:120px;min-width:120px;">NIM</th>

        @php $colIndex = 0; @endphp

        @foreach ($tugasList as $tugas)
          <th class="px-2 py-2 text-left rekap-col whitespace-nowrap"
              style="width:120px;min-width:120px;"
              data-col-index="{{ $colIndex }}">
            <div class="flex flex-col">
              <span>{{ $tugas->nama_tugas ?? 'Tugas' }}</span>
              <span class="text-xs text-slate-400">
                Tugas ke: {{ $tugas->tugas_ke ?? '-' }}
              </span>
            </div>
          </th>
          @php $colIndex++; @endphp
        @endforeach

        @foreach ($ujianList as $ujian)
          <th class="px-2 py-2 text-left rekap-col whitespace-nowrap"
              style="width:120px;min-width:120px;"
              data-col-index="{{ $colIndex }}">
            <div class="flex flex-col">
              <span>{{ $ujian->nama_ujian ?? 'Ujian' }}</span>
              <span class="text-xs text-slate-400">
                Ujian ke: {{ $ujian->ujian_ke ?? '-' }}
              </span>
            </div>
          </th>
          @php $colIndex++; @endphp
        @endforeach

        <th class="px-2 py-2 text-center rekap-col whitespace-nowrap"
            style="width:120px;min-width:120px;"
            data-col-index="{{ $colIndex }}">
          Rata-rata Tugas
        </th>
        @php $colIndex++; @endphp

        <th class="px-2 py-2 text-center rekap-col whitespace-nowrap"
            style="width:120px;min-width:120px;"
            data-col-index="{{ $colIndex }}">
          Rata-rata Ujian
        </th>
        @php $colIndex++; @endphp

        <th class="px-2 py-2 text-center rekap-col whitespace-nowrap"
            style="width:120px;min-width:120px;"
            data-col-index="{{ $colIndex }}">
          Keaktifan
        </th>
        @php $colIndex++; @endphp

        <th class="px-2 py-2 text-center rekap-col whitespace-nowrap"
            style="width:120px;min-width:120px;"
            data-col-index="{{ $colIndex }}">
          Absensi
        </th>
        @php $colIndex++; @endphp
        @for ($i = 0; $i < 6; $i++)
          <th class="px-2 py-2 text-center rekap-placeholder whitespace-nowrap hidden"
              style="width:120px;min-width:120px;"
              data-placeholder-index="{{ $i }}">
          </th>
        @endfor
      </tr>
    </thead>

    <!-- TBODY -->
    <tbody>
      @forelse(($kelas->mahasiswas ?? collect()) as $i => $mhs)
        <tr class="border-t hover:bg-slate-50 rekap-row"
            data-mahasiswa-id="{{ $mhs->id }}"
            data-kelas-id="{{ $kelas->id }}"
            data-name="{{ $mhs->user->name ?? '' }}"
            data-nim="{{ $mhs->nim ?? '' }}">

          <td class="px-2 py-2 text-center rekap-fixed whitespace-nowrap" style="width:40px;min-width:40px;max-width:40px;">
            {{ $i + 1 }}
          </td>

          <td class="px-2 py-2 rekap-fixed whitespace-nowrap rekap-name" style="width:200px;min-width:200px;max-width:200px;">
            {{ $mhs->user->name ?? '-' }}
          </td>

          <td class="px-2 py-2 text-center rekap-fixed whitespace-nowrap rekap-nim" style="width:120px;min-width:120px;max-width:120px;">
            {{ $mhs->nim ?? '-' }}
          </td>

          @php $colIndex = 0; @endphp

          @foreach ($tugasList as $tugas)
            @php
              $pengumpulan = $pengumpulanMap[$mhs->id][$tugas->id][0] ?? null;
              $nilai = $pengumpulan->nilai ?? 0;
              $nilaiKecepatan = $pengumpulan->nilai_kecepatan ?? 0;
            @endphp
            <td class="px-2 py-2 rekap-col"
                style="width:120px;min-width:120px;"
                data-col-index="{{ $colIndex }}">
              <div class="flex items-center gap-1">
                <div class="w-9 h-9 rounded-md bg-slate-100 flex items-center justify-center font-semibold">
                  {{ $nilai }}
                </div>
                <div class="w-9 h-9 rounded-md bg-yellow-100 flex items-center justify-center font-semibold text-yellow-800">
                  {{ $nilaiKecepatan }}
                </div>
              </div>
            </td>
            @php $colIndex++; @endphp
          @endforeach

          @foreach ($ujianList as $ujian)
            @php
              $hasil = $hasilUjianMap[$mhs->id][$ujian->id][0] ?? null;
              $nilaiUjian = $hasil->nilai ?? 0;
              $nilaiKecepatanUjian = $hasil->nilai_kecepatan ?? 0;
            @endphp
            <td class="px-2 py-2 rekap-col"
                style="width:120px;min-width:120px;"
                data-col-index="{{ $colIndex }}">
              <div class="flex items-center gap-1">
                <div class="w-9 h-9 rounded-md bg-slate-100 flex items-center justify-center font-semibold">
                  {{ $nilaiUjian }}
                </div>
                <div class="w-9 h-9 rounded-md bg-yellow-100 flex items-center justify-center font-semibold text-yellow-800">
                  {{ $nilaiKecepatanUjian }}
                </div>
              </div>
            </td>
            @php $colIndex++; @endphp
          @endforeach

          @php
            $tugasTotal = 0;
            $tugasKecepatanTotal = 0;
            $tugasCount = $tugasList->count() ?? 0;
            foreach ($tugasList as $tugas) {
              $pengumpulan = $pengumpulanMap[$mhs->id][$tugas->id][0] ?? null;
              $tugasTotal += (float) ($pengumpulan->nilai ?? 0);
              $tugasKecepatanTotal += (float) ($pengumpulan->nilai_kecepatan ?? 0);
            }
            $rataTugas = $tugasCount > 0 ? round($tugasTotal / $tugasCount, 2) : 0;
            $rataKecepatanTugas = $tugasCount > 0 ? round($tugasKecepatanTotal / $tugasCount, 2) : 0;

            $ujianTotal = 0;
            $ujianKecepatanTotal = 0;
            $ujianCount = $ujianList->count() ?? 0;
            foreach ($ujianList as $ujian) {
              $hasil = $hasilUjianMap[$mhs->id][$ujian->id][0] ?? null;
              $ujianTotal += (float) ($hasil->nilai ?? 0);
              $ujianKecepatanTotal += (float) ($hasil->nilai_kecepatan ?? 0);
            }
            $rataUjian = $ujianCount > 0 ? round($ujianTotal / $ujianCount, 2) : 0;
            $rataKecepatanUjian = $ujianCount > 0 ? round($ujianKecepatanTotal / $ujianCount, 2) : 0;
          @endphp
          <td class="px-2 py-2 rekap-col whitespace-nowrap text-center rekap-avg-tugas" style="width:120px;min-width:120px;max-width:120px;" data-col-index="{{ $colIndex }}" data-value="{{ $rataTugas }}" data-speed="{{ $rataKecepatanTugas }}">
            <div class="flex items-center justify-center gap-1">
              <div class="w-9 h-9 rounded-md bg-slate-100 flex items-center justify-center font-semibold text-slate-700">
                {{ $rataTugas }}
              </div>
              <div class="w-9 h-9 rounded-md bg-yellow-100 flex items-center justify-center font-semibold text-yellow-800">
                {{ $rataKecepatanTugas }}
              </div>
            </div>
          </td>
          @php $colIndex++; @endphp

          <td class="px-2 py-2 rekap-col whitespace-nowrap text-center rekap-avg-ujian" style="width:120px;min-width:120px;max-width:120px;" data-col-index="{{ $colIndex }}" data-value="{{ $rataUjian }}" data-speed="{{ $rataKecepatanUjian }}">
            <div class="flex items-center justify-center gap-1">
              <div class="w-9 h-9 rounded-md bg-slate-100 flex items-center justify-center font-semibold text-slate-700">
                {{ $rataUjian }}
              </div>
              <div class="w-9 h-9 rounded-md bg-yellow-100 flex items-center justify-center font-semibold text-yellow-800">
                {{ $rataKecepatanUjian }}
              </div>
            </div>
          </td>
          @php $colIndex++; @endphp

          @php
            $rekap = $rekapMap[$mhs->id] ?? null;
            $nilaiKeaktifan = $rekap->keaktifan ?? 50;
            $nilaiAbsensi = $rekap->absensi ?? 100;
          @endphp
          <td class="px-2 py-2 rekap-col whitespace-nowrap text-center rekap-keaktifan cursor-pointer" style="width:120px;min-width:120px;max-width:120px;" data-col-index="{{ $colIndex }}" data-value="{{ $nilaiKeaktifan }}">
            <div class="w-9 h-9 rounded-md bg-blue-100 flex items-center justify-center font-semibold text-blue-700 mx-auto">
              {{ $nilaiKeaktifan }}
            </div>
          </td>
          @php $colIndex++; @endphp

          <td class="px-2 py-2 rekap-col whitespace-nowrap text-center rekap-absensi" style="width:120px;min-width:120px;max-width:120px;" data-col-index="{{ $colIndex }}" data-value="{{ $nilaiAbsensi }}">
            <div class="w-9 h-9 rounded-md bg-emerald-100 flex items-center justify-center font-semibold text-emerald-700 mx-auto">
              {{ $nilaiAbsensi }}
            </div>
          </td>
          @for ($i = 0; $i < 6; $i++)
            <td class="px-2 py-2 rekap-placeholder whitespace-nowrap hidden"
                style="width:120px;min-width:120px;max-width:120px;"
                data-placeholder-index="{{ $i }}">
            </td>
          @endfor

        </tr>
      @empty
        <tr>
          <td class="px-4 py-6 text-center text-slate-500"
              colspan="{{ 3 + ($tugasList->count() ?? 0) + ($ujianList->count() ?? 0) + 4 }}">
            Belum ada data mahasiswa.
          </td>
        </tr>
      @endforelse
    </tbody>

  </table>
</div>

      
  </div>
</div>

<div id="keaktifanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40">
  <div class="bg-white rounded-xl w-full max-w-md p-5 relative shadow-lg">
    <button id="closeKeaktifanModal" type="button"
      class="absolute right-4 top-4 text-slate-500 hover:text-slate-700">
      <span class="material-symbols-rounded text-2xl">close</span>
    </button>
    <h3 class="text-lg font-semibold text-slate-800">Ubah Keaktifan</h3>
    <p id="keaktifanModalName" class="text-sm text-slate-500 mt-1">-</p>

    <div class="mt-4">
      <p class="text-sm font-medium text-slate-700">Nilai Keaktifan</p>
      <p id="keaktifanCurrentValue" class="text-2xl font-semibold text-slate-800 mt-1">0</p>
    </div>

    <div class="mt-5 grid grid-cols-3 sm:grid-cols-6 gap-2">
      <button type="button" class="keaktifan-add-btn px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 text-sm" data-add="1">+1</button>
      <button type="button" class="keaktifan-add-btn px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 text-sm" data-add="2">+2</button>
      <button type="button" class="keaktifan-add-btn px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 text-sm" data-add="3">+3</button>
      <button type="button" class="keaktifan-add-btn px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 text-sm" data-add="4">+4</button>
      <button type="button" class="keaktifan-add-btn px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 text-sm" data-add="5">+5</button>
      <button type="button" class="keaktifan-add-btn px-3 py-2 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200 text-sm" data-add="-5">-5</button>
    </div>

    <div class="mt-5 flex items-center justify-end gap-2">
      <button id="cancelKeaktifanModal" type="button"
        class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 text-sm">
        Tutup
      </button>
    </div>
  </div>
</div>

<div id="absensiModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40">
  <div class="bg-white rounded-xl w-full max-w-md p-5 relative shadow-lg">
    <button id="closeAbsensiModal" type="button"
      class="absolute right-4 top-4 text-slate-500 hover:text-slate-700">
      <span class="material-symbols-rounded text-2xl">close</span>
    </button>
    <h3 class="text-lg font-semibold text-slate-800">Ubah Absensi</h3>
    <p id="absensiModalName" class="text-sm text-slate-500 mt-1">-</p>

    <div class="mt-4">
      <p class="text-sm font-medium text-slate-700">Keterangan</p>
      <p class="text-sm text-slate-500 mt-1">Telat = -2 &nbsp; Sakit = -5 &nbsp; Izin = -10 &nbsp; Alfa = -15</p>
      <p id="absensiCurrentValue" class="text-2xl font-semibold text-slate-800 mt-2">0</p>
    </div>

    <div class="mt-5 grid grid-cols-2 sm:grid-cols-5 gap-2">
      <button type="button" class="absensi-add-btn px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 text-sm" data-add="1">+1</button>
      <button type="button" class="absensi-add-btn px-3 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 text-sm" data-add="-2">Telat</button>
      <button type="button" class="absensi-add-btn px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 text-sm" data-add="-5">Sakit</button>
      <button type="button" class="absensi-add-btn px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 text-sm" data-add="-10">Izin</button>
      <button type="button" class="absensi-add-btn px-3 py-2 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200 text-sm" data-add="-15">Alfa</button>
    </div>

    <div class="mt-5 flex items-center justify-end gap-2">
      <button id="cancelAbsensiModal" type="button"
        class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 text-sm">
        Tutup
      </button>
    </div>
  </div>
</div>

<script>
  const cols = Array.from(document.querySelectorAll('.rekap-col'));
  const placeholders = Array.from(document.querySelectorAll('.rekap-placeholder'));
  const btnPrev = document.getElementById('btnColPrev');
  const btnNext = document.getElementById('btnColNext');
  const visibleCount = 6;
  let startIndex = 0;

  const applyColWindow = () => {
    let visibleInWindow = 0;
    cols.forEach((col) => {
      const idx = Number(col.dataset.colIndex || 0);
      if (idx >= startIndex && idx < startIndex + visibleCount) {
        col.classList.remove('hidden');
        visibleInWindow += 1;
      } else {
        col.classList.add('hidden');
      }
    });

    const missing = Math.max(0, visibleCount - visibleInWindow);
    placeholders.forEach((ph) => {
      const idx = Number(ph.dataset.placeholderIndex || 0);
      if (idx < missing) {
        ph.classList.remove('hidden');
      } else {
        ph.classList.add('hidden');
      }
    });

    if (btnPrev) btnPrev.disabled = startIndex <= 0;
    if (btnNext) {
      const maxIdx = cols.length ? Math.max(...cols.map(c => Number(c.dataset.colIndex || 0))) + 1 : 0;
      btnNext.disabled = startIndex + visibleCount >= maxIdx;
    }
  };

  btnPrev?.addEventListener('click', () => {
    startIndex = Math.max(0, startIndex - visibleCount);
    applyColWindow();
  });
  btnNext?.addEventListener('click', () => {
    startIndex = startIndex + visibleCount;
    applyColWindow();
  });

  applyColWindow();
</script>

<script>
  const searchInput = document.getElementById('searchRekapInput');
  const normalize = (value) => (value || '').toString().toLowerCase();

  const applyRowFilters = () => {
    const query = normalize(searchInput?.value).trim();
    document.querySelectorAll('tbody tr').forEach((row) => {
      const name = normalize(row.dataset.name);
      const nim = normalize(row.dataset.nim);
      const matchesSearch = !query || name.includes(query) || nim.includes(query);
      row.style.display = matchesSearch ? '' : 'none';
    });
  };

  searchInput?.addEventListener('input', applyRowFilters);
  searchInput?.addEventListener('keyup', applyRowFilters);
  applyRowFilters();
</script>

<script>
  const syncUrl = "{{ route('dosen.rekap.sync', $kelas->id) }}";
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

  const buildRecords = () => {
    return Array.from(document.querySelectorAll('.rekap-row')).map((row) => {
      const rataTugasEl = row.querySelector('.rekap-avg-tugas');
      const rataUjianEl = row.querySelector('.rekap-avg-ujian');
      const keaktifanEl = row.querySelector('.rekap-keaktifan');
      const absensiEl = row.querySelector('.rekap-absensi');

      return {
        mahasiswa_id: Number(row.dataset.mahasiswaId || 0),
        rata_tugas: Number(rataTugasEl?.dataset.value || 0),
        rata_kecepatan_tugas: Number(rataTugasEl?.dataset.speed || 0),
        rata_ujian: Number(rataUjianEl?.dataset.value || 0),
        rata_kecepatan_ujian: Number(rataUjianEl?.dataset.speed || 0),
        keaktifan: (keaktifanEl?.dataset.value || keaktifanEl?.textContent || '').toString().trim(),
        absensi: Number(absensiEl?.dataset.value || absensiEl?.textContent || 0),
      };
    });
  };

  const syncRekap = async () => {
    const records = buildRecords();
    if (!records.length) return;

    try {
      await fetch(syncUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ records }),
      });
    } catch (err) {
      console.error('Gagal sync rekap:', err);
    }
  };

  window.addEventListener('load', () => {
    setTimeout(syncRekap, 200);
  });
</script>

<script>
  const keaktifanModal = document.getElementById('keaktifanModal');
  const keaktifanModalName = document.getElementById('keaktifanModalName');
  const keaktifanCurrentValue = document.getElementById('keaktifanCurrentValue');
  const closeKeaktifanModal = document.getElementById('closeKeaktifanModal');
  const cancelKeaktifanModal = document.getElementById('cancelKeaktifanModal');
  const keaktifanAddButtons = Array.from(document.querySelectorAll('.keaktifan-add-btn'));
  let activeKeaktifanCell = null;

  const absensiModal = document.getElementById('absensiModal');
  const absensiModalName = document.getElementById('absensiModalName');
  const absensiCurrentValue = document.getElementById('absensiCurrentValue');
  const closeAbsensiModal = document.getElementById('closeAbsensiModal');
  const cancelAbsensiModal = document.getElementById('cancelAbsensiModal');
  const absensiAddButtons = Array.from(document.querySelectorAll('.absensi-add-btn'));
  let activeAbsensiCell = null;

  const openKeaktifanModal = (cell) => {
    activeKeaktifanCell = cell;
    const row = cell.closest('.rekap-row');
    const name = row?.dataset?.name || '-';
    keaktifanModalName.textContent = name;
    keaktifanCurrentValue.textContent = cell.dataset.value || 0;
    keaktifanModal.classList.remove('hidden');
    keaktifanModal.classList.add('flex');
  };

  const closeModal = () => {
    keaktifanModal.classList.add('hidden');
    keaktifanModal.classList.remove('flex');
    activeKeaktifanCell = null;
  };

  const openAbsensiModal = (cell) => {
    activeAbsensiCell = cell;
    const row = cell.closest('.rekap-row');
    const name = row?.dataset?.name || '-';
    absensiModalName.textContent = name;
    absensiCurrentValue.textContent = cell.dataset.value || 0;
    absensiModal.classList.remove('hidden');
    absensiModal.classList.add('flex');
  };

  const closeAbsensi = () => {
    absensiModal.classList.add('hidden');
    absensiModal.classList.remove('flex');
    activeAbsensiCell = null;
  };

  document.querySelectorAll('.rekap-keaktifan').forEach((cell) => {
    cell.addEventListener('click', () => openKeaktifanModal(cell));
  });
  document.querySelectorAll('.rekap-absensi').forEach((cell) => {
    cell.addEventListener('click', () => openAbsensiModal(cell));
  });

  keaktifanModal.addEventListener('click', (event) => {
    if (event.target === keaktifanModal) closeModal();
  });
  absensiModal.addEventListener('click', (event) => {
    if (event.target === absensiModal) closeAbsensi();
  });

  closeKeaktifanModal?.addEventListener('click', closeModal);
  cancelKeaktifanModal?.addEventListener('click', closeModal);
  closeAbsensiModal?.addEventListener('click', closeAbsensi);
  cancelAbsensiModal?.addEventListener('click', closeAbsensi);

  keaktifanAddButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      if (!activeKeaktifanCell) return;
      const add = Number(btn.dataset.add || 0);
      const current = Number(activeKeaktifanCell.dataset.value || 0);
      const next = current + add;
      activeKeaktifanCell.dataset.value = String(next);
      const box = activeKeaktifanCell.querySelector('div');
      if (box) box.textContent = next;
      keaktifanCurrentValue.textContent = String(next);
      syncRekap();
    });
  });

  absensiAddButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      if (!activeAbsensiCell) return;
      const add = Number(btn.dataset.add || 0);
      const current = Number(activeAbsensiCell.dataset.value || 0);
      const next = current + add;
      activeAbsensiCell.dataset.value = String(next);
      const box = activeAbsensiCell.querySelector('div');
      if (box) box.textContent = next;
      absensiCurrentValue.textContent = String(next);
      syncRekap();
    });
  });
</script>


@php
  $kelas = $kelas ?? null;
  $tugasList = $tugasList ?? collect();
  $ujianList = $ujianList ?? collect();
  $pengumpulanMap = $pengumpulanMap ?? collect();
  $hasilUjianMap = $hasilUjianMap ?? collect();
  $rekapMap = $rekapMap ?? collect();
  $showBack = $showBack ?? false;
  $bobot = $bobot ?? null;

  $normalizeUjianTipe = function ($value) {
      $text = strtolower(trim((string) $value));
      if ($text === '') {
          return '';
      }
      if (str_contains($text, 'uts') || str_contains($text, 'tengah')) {
          return 'uts';
      }
      if (str_contains($text, 'uas') || str_contains($text, 'akhir')) {
          return 'uas';
      }
      if (str_contains($text, 'quiz')) {
          return 'quiz';
      }
      if (str_contains($text, 'ujian')) {
          return 'ujian';
      }
      return $text;
  };

  $ujianQuizList = $ujianList->filter(fn($u) => in_array($normalizeUjianTipe($u->deskripsi ?? ''), ['ujian', 'quiz'], true))->values();
  $ujianUtsList = $ujianList->filter(fn($u) => $normalizeUjianTipe($u->deskripsi ?? '') === 'uts')->values();
  $ujianUasList = $ujianList->filter(fn($u) => $normalizeUjianTipe($u->deskripsi ?? '') === 'uas')->values();
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
    <section class="flex flex-wrap items-center gap-2 relative rounded-lg border bg-slate-50 px-3 py-2">
      <span class="text-sm font-semibold text-slate-700">Komponen :</span>
      <span class="relative group inline-flex items-center gap-1">
        <span class="text-xs text-slate-600">Harian</span>
        <aside class="pointer-events-none absolute left-0 bottom-full mb-2 hidden w-[220px] rounded-lg border bg-white px-3 py-2 text-xs text-slate-600 shadow-lg group-hover:block z-10">
          Rata-rata tugas + ujian.
        </aside>
      </span>
      <span class="inline-flex items-center gap-0"><input id="bobotTugasUjian" type="number" min="0" step="0.01" class="w-14 rounded-md border px-2 py-1 text-xs text-slate-700" disabled><span class="text-xs text-slate-500">%</span><span class="inline-block h-4 border-l border-slate-300 ml-2"></span></span>
      <span class="text-xs text-slate-600">Keaktifan</span>
      <span class="inline-flex items-center gap-0"><input id="bobotKeaktifan" type="number" min="0" step="0.01" class="w-14 rounded-md border px-2 py-1 text-xs text-slate-700" disabled><span class="text-xs text-slate-500">%</span><span class="inline-block h-4 border-l border-slate-300 ml-2"></span></span>
      <span class="text-xs text-slate-600">Kecepatan</span>
      <span class="inline-flex items-center gap-0"><input id="bobotKecepatan" type="number" min="0" step="0.01" class="w-14 rounded-md border px-2 py-1 text-xs text-slate-700" disabled><span class="text-xs text-slate-500">%</span><span class="inline-block h-4 border-l border-slate-300 ml-2"></span></span>
      <span class="text-xs text-slate-600">Absensi</span>
      <span class="inline-flex items-center gap-0"><input id="bobotAbsensi" type="number" min="0" step="0.01" class="w-14 rounded-md border px-2 py-1 text-xs text-slate-700" disabled><span class="text-xs text-slate-500">%</span><span class="inline-block h-4 border-l border-slate-300 ml-2"></span></span>
      <span class="text-xs text-slate-600">UTS</span>
      <span class="inline-flex items-center gap-0"><input id="bobotUts" type="number" min="0" step="0.01" class="w-14 rounded-md border px-2 py-1 text-xs text-slate-700" disabled><span class="text-xs text-slate-500">%</span><span class="inline-block h-4 border-l border-slate-300 ml-2"></span></span>
      <span class="text-xs text-slate-600">UAS</span>
      <span class="inline-flex items-center gap-0"><input id="bobotUas" type="number" min="0" step="0.01" class="w-14 rounded-md border px-2 py-1 text-xs text-slate-700" disabled><span class="text-xs text-slate-500">%</span><span class="inline-block h-4 border-l border-slate-300 ml-2"></span></span>
      <button id="btnEditBobot" type="button" class="rounded-full bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
        Edit
      </button>
      <span class="text-xs text-slate-600">Total : <span id="bobotTotalValue">0</span>%</span>
    </section>
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

        <th class="px-2 py-2 text-center rekap-col whitespace-nowrap"
            style="width:140px;min-width:140px;"
            data-col-index="{{ $colIndex }}">
          Rata-rata Tugas + Ujian
        </th>
        @php $colIndex++; @endphp

        <th class="px-2 py-2 text-center rekap-col whitespace-nowrap"
            style="width:90px;min-width:90px;"
            data-col-index="{{ $colIndex }}">
          UTS
        </th>
        @php $colIndex++; @endphp

        <th class="px-2 py-2 text-center rekap-col whitespace-nowrap"
            style="width:90px;min-width:90px;"
            data-col-index="{{ $colIndex }}">
          UAS
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

        <th class="px-2 py-2 text-center rekap-col whitespace-nowrap"
            style="width:140px;min-width:140px;"
            data-col-index="{{ $colIndex }}">
          <span class="relative group inline-flex items-center gap-1">
            <span>Indeks Prestasi</span>
            <aside class="pointer-events-none absolute left-1/2 -translate-x-1/2 bottom-full mb-2 hidden w-[260px] rounded-lg border bg-white px-3 py-2 text-xs text-slate-600 shadow-lg group-hover:block z-10">
              <table class="w-full text-[10px]">
                <thead>
                  <tr class="text-slate-500">
                    <th class="text-left py-1">Nilai Angka</th>
                    <th class="text-left py-1">Nilai Huruf</th>
                    <th class="text-left py-1">Bobot</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td class="py-0.5">85–100</td><td class="py-0.5">A</td><td class="py-0.5">4.00</td></tr>
                  <tr><td class="py-0.5">80–84</td><td class="py-0.5">A-</td><td class="py-0.5">3.75</td></tr>
                  <tr><td class="py-0.5">75–79</td><td class="py-0.5">B+</td><td class="py-0.5">3.50</td></tr>
                  <tr><td class="py-0.5">70–74</td><td class="py-0.5">B</td><td class="py-0.5">3.00</td></tr>
                  <tr><td class="py-0.5">65–69</td><td class="py-0.5">B-</td><td class="py-0.5">2.75</td></tr>
                  <tr><td class="py-0.5">60–64</td><td class="py-0.5">C+</td><td class="py-0.5">2.50</td></tr>
                  <tr><td class="py-0.5">55–59</td><td class="py-0.5">C</td><td class="py-0.5">2.00</td></tr>
                  <tr><td class="py-0.5">40–54</td><td class="py-0.5">D</td><td class="py-0.5">1.00</td></tr>
                  <tr><td class="py-0.5">&lt; 40</td><td class="py-0.5">E</td><td class="py-0.5">0.00</td></tr>
                </tbody>
              </table>
            </aside>
            <div class="pointer-events-none fixed inset-0 hidden items-center justify-center bg-black/40 backdrop-blur-[1px] group-hover:flex z-40">
              <div class="w-full max-w-md rounded-xl border bg-white px-4 py-3 text-xs text-slate-700 shadow-xl">
                <div class="text-sm font-semibold text-slate-800 mb-2">Konversi Indeks Prestasi</div>
                <table class="w-full text-[11px]">
                  <thead>
                    <tr class="text-slate-500">
                      <th class="text-left py-1">Nilai Angka</th>
                      <th class="text-left py-1">Nilai Huruf</th>
                      <th class="text-left py-1">Bobot</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td class="py-0.5">85–100</td><td class="py-0.5">A</td><td class="py-0.5">4.00</td></tr>
                    <tr><td class="py-0.5">80–84</td><td class="py-0.5">A-</td><td class="py-0.5">3.75</td></tr>
                    <tr><td class="py-0.5">75–79</td><td class="py-0.5">B+</td><td class="py-0.5">3.50</td></tr>
                    <tr><td class="py-0.5">70–74</td><td class="py-0.5">B</td><td class="py-0.5">3.00</td></tr>
                    <tr><td class="py-0.5">65–69</td><td class="py-0.5">B-</td><td class="py-0.5">2.75</td></tr>
                    <tr><td class="py-0.5">60–64</td><td class="py-0.5">C+</td><td class="py-0.5">2.50</td></tr>
                    <tr><td class="py-0.5">55–59</td><td class="py-0.5">C</td><td class="py-0.5">2.00</td></tr>
                    <tr><td class="py-0.5">40–54</td><td class="py-0.5">D</td><td class="py-0.5">1.00</td></tr>
                    <tr><td class="py-0.5">&lt; 40</td><td class="py-0.5">E</td><td class="py-0.5">0.00</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </span>
        </th>
        @php $colIndex++; @endphp

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

        @foreach ($ujianQuizList as $ujian)
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
            $ujianCount = $ujianQuizList->count() ?? 0;
            foreach ($ujianQuizList as $ujian) {
              $hasil = $hasilUjianMap[$mhs->id][$ujian->id][0] ?? null;
              $ujianTotal += (float) ($hasil->nilai ?? 0);
              $ujianKecepatanTotal += (float) ($hasil->nilai_kecepatan ?? 0);
            }
            $rataUjian = $ujianCount > 0 ? round($ujianTotal / $ujianCount, 2) : 0;
            $rataKecepatanUjian = $ujianCount > 0 ? round($ujianKecepatanTotal / $ujianCount, 2) : 0;

            $totalCount = $tugasCount + $ujianCount;
            $rataTugasUjian = $totalCount > 0 ? round(($tugasTotal + $ujianTotal) / $totalCount, 2) : 0;
            $rataKecepatanTotal = $totalCount > 0 ? round(($tugasKecepatanTotal + $ujianKecepatanTotal) / $totalCount, 2) : 0;

            $utsCount = $ujianUtsList->count() ?? 0;
            $uasCount = $ujianUasList->count() ?? 0;
            $utsTotal = 0;
            foreach ($ujianUtsList as $ujian) {
              $hasil = $hasilUjianMap[$mhs->id][$ujian->id][0] ?? null;
              $utsTotal += (float) ($hasil->nilai ?? 0);
            }
            $uasTotal = 0;
            foreach ($ujianUasList as $ujian) {
              $hasil = $hasilUjianMap[$mhs->id][$ujian->id][0] ?? null;
              $uasTotal += (float) ($hasil->nilai ?? 0);
            }
            $nilaiUts = $utsCount > 0 ? round($utsTotal / $utsCount, 2) : null;
            $nilaiUas = $uasCount > 0 ? round($uasTotal / $uasCount, 2) : null;
          @endphp
          <td class="px-2 py-2 rekap-col whitespace-nowrap text-center rekap-avg-total" style="width:120px;min-width:120px;max-width:120px;" data-col-index="{{ $colIndex }}" data-value="{{ $rataTugasUjian }}" data-speed="{{ $rataKecepatanTotal }}" data-tugas="{{ $rataTugas }}" data-ujian="{{ $rataUjian }}" data-speed-tugas="{{ $rataKecepatanTugas }}" data-speed-ujian="{{ $rataKecepatanUjian }}">
            <div class="flex items-center justify-center gap-1">
              <div class="w-9 h-9 rounded-md bg-slate-100 flex items-center justify-center font-semibold text-slate-700">
                {{ $rataTugasUjian }}
              </div>
              <span class="relative group inline-flex">
                <div class="w-9 h-9 rounded-md bg-yellow-100 flex items-center justify-center font-semibold text-yellow-800">
                  {{ $rataKecepatanTotal }}
                </div>
                <aside class="pointer-events-none absolute left-1/2 -translate-x-1/2 bottom-full mb-2 hidden rounded-lg border bg-white px-2 py-1 text-[10px] text-slate-600 shadow-lg group-hover:block z-10">
                  Kecepatan mengerjakan
                </aside>
              </span>
            </div>
          </td>
          @php $colIndex++; @endphp

          <td class="px-2 py-2 rekap-col whitespace-nowrap text-center rekap-uts" style="width:90px;min-width:90px;max-width:90px;" data-col-index="{{ $colIndex }}" data-value="{{ is_null($nilaiUts) ? '' : $nilaiUts }}">
            <div class="w-9 h-9 rounded-md bg-slate-100 flex items-center justify-center font-semibold text-slate-700 mx-auto">
              {{ is_null($nilaiUts) ? '-' : $nilaiUts }}
            </div>
          </td>
          @php $colIndex++; @endphp

          <td class="px-2 py-2 rekap-col whitespace-nowrap text-center rekap-uas" style="width:90px;min-width:90px;max-width:90px;" data-col-index="{{ $colIndex }}" data-value="{{ is_null($nilaiUas) ? '' : $nilaiUas }}">
            <div class="w-9 h-9 rounded-md bg-slate-100 flex items-center justify-center font-semibold text-slate-700 mx-auto">
              {{ is_null($nilaiUas) ? '-' : $nilaiUas }}
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
          @php $colIndex++; @endphp

          <td class="px-2 py-2 rekap-col whitespace-nowrap text-center rekap-ip" style="width:140px;min-width:140px;max-width:140px;" data-col-index="{{ $colIndex }}">
            <div class="flex items-center justify-center gap-1">
              <div class="w-10 h-9 rounded-md bg-slate-100 flex items-center justify-center font-semibold text-slate-700 ip-angka">0.00</div>
              <div class="w-10 h-9 rounded-md bg-blue-100 flex items-center justify-center font-semibold text-blue-700 ip-huruf">E</div>
            </div>
          </td>
          @php $colIndex++; @endphp

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
                <span class="relative group inline-flex">
                  <div class="w-9 h-9 rounded-md bg-yellow-100 flex items-center justify-center font-semibold text-yellow-800">
                    {{ $nilaiKecepatan }}
                  </div>
                  <aside class="pointer-events-none absolute left-1/2 -translate-x-1/2 bottom-full mb-2 hidden rounded-lg border bg-white px-2 py-1 text-[10px] text-slate-600 shadow-lg group-hover:block z-10">
                    Kecepatan mengerjakan
                  </aside>
                </span>
              </div>
            </td>
            @php $colIndex++; @endphp
          @endforeach

          @foreach ($ujianQuizList as $ujian)
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
                <span class="relative group inline-flex">
                  <div class="w-9 h-9 rounded-md bg-yellow-100 flex items-center justify-center font-semibold text-yellow-800">
                    {{ $nilaiKecepatanUjian }}
                  </div>
                  <aside class="pointer-events-none absolute left-1/2 -translate-x-1/2 bottom-full mb-2 hidden rounded-lg border bg-white px-2 py-1 text-[10px] text-slate-600 shadow-lg group-hover:block z-10">
                    Kecepatan mengerjakan
                  </aside>
                </span>
              </div>
            </td>
            @php $colIndex++; @endphp
          @endforeach
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
              colspan="{{ 3 + ($tugasList->count() ?? 0) + ($ujianQuizList->count() ?? 0) + 6 }}">
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

    const canPrev = startIndex > 0;
    if (btnPrev) {
      btnPrev.disabled = !canPrev;
      btnPrev.classList.toggle('bg-blue-600', canPrev);
      btnPrev.classList.toggle('text-white', canPrev);
      btnPrev.classList.toggle('hover:bg-blue-700', canPrev);
      btnPrev.classList.toggle('bg-slate-100', !canPrev);
      btnPrev.classList.toggle('text-slate-600', !canPrev);
      btnPrev.classList.toggle('hover:bg-slate-200', !canPrev);
    }
    if (btnNext) {
      const maxIdx = cols.length ? Math.max(...cols.map(c => Number(c.dataset.colIndex || 0))) + 1 : 0;
      const canNext = startIndex + visibleCount < maxIdx;
      btnNext.disabled = !canNext;
      btnNext.classList.toggle('bg-blue-600', canNext);
      btnNext.classList.toggle('text-white', canNext);
      btnNext.classList.toggle('hover:bg-blue-700', canNext);
      btnNext.classList.toggle('bg-slate-100', !canNext);
      btnNext.classList.toggle('text-slate-600', !canNext);
      btnNext.classList.toggle('hover:bg-slate-200', !canNext);
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
  const bobotDefaults = {
    tugas_ujian: {{ $bobot?->harian ?? 15 }},
    keaktifan: {{ $bobot?->keaktifan ?? 6.25 }},
    kecepatan: {{ $bobot?->kecepatan ?? 3.75 }},
    absensi: {{ $bobot?->absensi ?? 5 }},
    uts: {{ $bobot?->uts ?? 30 }},
    uas: {{ $bobot?->uas ?? 40 }},
  };
  const kelasId = "{{ $kelas->id ?? 'global' }}";
  const bobotInputs = {
    tugas_ujian: document.getElementById('bobotTugasUjian'),
    keaktifan: document.getElementById('bobotKeaktifan'),
    kecepatan: document.getElementById('bobotKecepatan'),
    absensi: document.getElementById('bobotAbsensi'),
    uts: document.getElementById('bobotUts'),
    uas: document.getElementById('bobotUas'),
  };
  const btnEditBobot = document.getElementById('btnEditBobot');
  const bobotTotalValue = document.getElementById('bobotTotalValue');
  let isEditingBobot = false;

  const loadBobot = () => ({ ...bobotDefaults });

  const setBobotInputs = (data) => {
    Object.keys(bobotInputs).forEach((key) => {
      if (bobotInputs[key]) bobotInputs[key].value = data[key];
    });
  };

  const setInputsDisabled = (disabled) => {
    Object.values(bobotInputs).forEach((input) => {
      if (input) input.disabled = disabled;
    });
  };

  const saveBobot = async () => {
    const data = {};
    Object.keys(bobotInputs).forEach((key) => {
      const val = Number(bobotInputs[key]?.value || 0);
      data[key] = Number.isNaN(val) ? 0 : val;
    });
    try {
      await fetch("{{ route('dosen.rekap.bobot.save', $kelas->id) }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          harian: data.tugas_ujian,
          keaktifan: data.keaktifan,
          kecepatan: data.kecepatan,
          absensi: data.absensi,
          uts: data.uts,
          uas: data.uas,
        }),
      });
    } catch (e) {}
  };

  const updateTotal = () => {
    const total = Object.keys(bobotInputs).reduce((sum, key) => {
      const val = Number(bobotInputs[key]?.value || 0);
      return sum + (Number.isNaN(val) ? 0 : val);
    }, 0);
    if (bobotTotalValue) {
      bobotTotalValue.textContent = total % 1 === 0 ? String(total) : total.toFixed(2);
    }
  };

  const getGradeInfo = (nilai) => {
    if (nilai >= 85) return { huruf: 'A', bobot: 4.0 };
    if (nilai >= 80) return { huruf: 'A-', bobot: 3.75 };
    if (nilai >= 75) return { huruf: 'B+', bobot: 3.5 };
    if (nilai >= 70) return { huruf: 'B', bobot: 3.0 };
    if (nilai >= 65) return { huruf: 'B-', bobot: 2.75 };
    if (nilai >= 60) return { huruf: 'C+', bobot: 2.5 };
    if (nilai >= 55) return { huruf: 'C', bobot: 2.0 };
    if (nilai >= 40) return { huruf: 'D', bobot: 1.0 };
    return { huruf: 'E', bobot: 0.0 };
  };

  const calcIndeksPrestasi = () => {
    const bobot = {
      tugas_ujian: Number(bobotInputs.tugas_ujian?.value || 0),
      keaktifan: Number(bobotInputs.keaktifan?.value || 0),
      kecepatan: Number(bobotInputs.kecepatan?.value || 0),
      absensi: Number(bobotInputs.absensi?.value || 0),
      uts: Number(bobotInputs.uts?.value || 0),
      uas: Number(bobotInputs.uas?.value || 0),
    };

    document.querySelectorAll('.rekap-row').forEach((row) => {
      const rataEl = row.querySelector('.rekap-avg-total');
      const utsEl = row.querySelector('.rekap-uts');
      const uasEl = row.querySelector('.rekap-uas');
      const keaktifanEl = row.querySelector('.rekap-keaktifan');
      const absensiEl = row.querySelector('.rekap-absensi');
      const ipAngka = row.querySelector('.ip-angka');
      const ipHuruf = row.querySelector('.ip-huruf');

      const nilaiHarian = Number(rataEl?.dataset.value || 0);
      const nilaiKecepatan = Number(rataEl?.dataset.speed || 0);
      const nilaiUts = Number(utsEl?.dataset.value || 0);
      const nilaiUas = Number(uasEl?.dataset.value || 0);
      const nilaiKeaktifan = Number(keaktifanEl?.dataset.value || 0);
      const nilaiAbsensi = Number(absensiEl?.dataset.value || 0);

      const nilaiTotal =
        (nilaiHarian * bobot.tugas_ujian) / 100 +
        (nilaiKeaktifan * bobot.keaktifan) / 100 +
        (nilaiKecepatan * bobot.kecepatan) / 100 +
        (nilaiAbsensi * bobot.absensi) / 100 +
        (nilaiUts * bobot.uts) / 100 +
        (nilaiUas * bobot.uas) / 100;

      const info = getGradeInfo(nilaiTotal);
      if (ipAngka) ipAngka.textContent = info.bobot.toFixed(2);
      if (ipHuruf) ipHuruf.textContent = info.huruf;
    });
  };

  const toggleEditBobot = () => {
    isEditingBobot = !isEditingBobot;
    setInputsDisabled(!isEditingBobot);
    if (btnEditBobot) {
      btnEditBobot.textContent = isEditingBobot ? 'Terapkan' : 'Edit';
      btnEditBobot.classList.toggle('bg-blue-600', !isEditingBobot);
      btnEditBobot.classList.toggle('hover:bg-blue-700', !isEditingBobot);
      btnEditBobot.classList.toggle('bg-emerald-600', isEditingBobot);
      btnEditBobot.classList.toggle('hover:bg-emerald-700', isEditingBobot);
    }
    if (!isEditingBobot) {
      saveBobot();
    }
  };

  setBobotInputs(loadBobot());
  updateTotal();
  setInputsDisabled(true);
  if (btnEditBobot) {
    btnEditBobot.classList.add('bg-blue-600', 'hover:bg-blue-700');
    btnEditBobot.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
  }
  btnEditBobot?.addEventListener('click', toggleEditBobot);
  Object.values(bobotInputs).forEach((input) => {
    input?.addEventListener('input', () => {
      updateTotal();
      calcIndeksPrestasi();
    });
  });
  window.addEventListener('load', () => {
    calcIndeksPrestasi();
  });
</script>

<script>
  const syncUrl = "{{ route('dosen.rekap.sync', $kelas->id) }}";
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

  const buildRecords = () => {
    return Array.from(document.querySelectorAll('.rekap-row')).map((row) => {
      const rataTotalEl = row.querySelector('.rekap-avg-total');
      const keaktifanEl = row.querySelector('.rekap-keaktifan');
      const absensiEl = row.querySelector('.rekap-absensi');

      return {
        mahasiswa_id: Number(row.dataset.mahasiswaId || 0),
        rata_tugas: Number(rataTotalEl?.dataset.tugas || 0),
        rata_kecepatan_tugas: Number(rataTotalEl?.dataset.speedTugas || 0),
        rata_ujian: Number(rataTotalEl?.dataset.ujian || 0),
        rata_kecepatan_ujian: Number(rataTotalEl?.dataset.speedUjian || 0),
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

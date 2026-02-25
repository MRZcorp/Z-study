<x-header> </x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

@php
  $nilaiRows = $nilaiRows ?? collect();
@endphp

<div class="space-y-2">
  <div class="flex items-center justify-between">
    
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl shadow p-4">
      @php
        $ipkVal = (float) ($ipkValue ?? 0);
        $ipkClass = 'text-slate-400';
        if ($ipkVal < 2) {
            $ipkClass = 'text-red-600';
        } elseif ($ipkVal < 2.75) {
            $ipkClass = 'text-amber-600';
        } elseif ($ipkVal < 3.75) {
            $ipkClass = 'text-blue-600';
        } else {
            $ipkClass = 'text-emerald-600';
        }
      @endphp
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-slate-800">Grafik Indeks Prestasi Semester</h3>
        <span class="text-xs font-semibold text-slate-800">
          IPK :
          <span class="{{ $ipkClass }}">{{ number_format($ipkVal, 2) }}</span>
        </span>
      </div>
      <div class="relative w-full h-56">
        <canvas id="nilaiChart"></canvas>
      </div>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-slate-800">Statistik Mahasiswa</h3>
        <div class="flex items-center gap-2">
          <select
            id="radar_semester_filter"
            class="h-8 rounded-md border border-slate-200 bg-white px-2 text-xs text-slate-700"
          >
            <option value="">Semua Semester</option>
            @foreach (($semesterOptions ?? []) as $sem)
              <option value="{{ $sem['value'] }}">{{ $sem['label'] }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="relative w-full h-56">
        <canvas id="nilaiRadar"></canvas>
      </div>
    </div>
  </div>
  <div class="bg-white rounded-lg shadow-md overflow-hidden w-full">
    <div class="px-4 py-3 border-b flex items-center justify-between gap-3">
      <h3 class="text-sm font-semibold text-slate-800">Indeks Prestasi Semester</h3>
      <button
        type="button"
        id="btnToggleIpTable"
        class="inline-flex items-center gap-1 rounded-md border px-3 py-1.5 text-xs font-semibold transition bg-blue-100 text-blue-700 border-blue-200"
        title="Sembunyikan/Tampilkan tabel Indeks Prestasi Semester"
      >
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        </svg>
      </button>
    </div>
    <div class="px-4 py-3 border-b">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
          <label for="ip_semester_filter" class="text-sm text-slate-800">Semester</label>
          <select
            name="ip_semester_filter"
            id="ip_semester_filter"
            class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800"
          >
            <option value="">Semua Semester</option>
            @foreach (($semesterOptions ?? []) as $sem)
              <option value="{{ $sem['value'] }}">{{ $sem['label'] }}</option>
            @endforeach
          </select>
        </div>
        <div class="text-sm text-slate-700">
          IPS : <span id="ipSemesterValue" class="font-semibold">0.00</span>
        </div>
      </div>
    </div>
    <div id="ipTableWrap" class="overflow-x-auto">
    <table class="min-w-[900px] md:min-w-full border border-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border-b">No.</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Kode</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Mata Kuliah</th>
          <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border-b">SKS</th>
          <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border-b">Dosen Pengampu</th>
          <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border-b">Nilai IP</th>
          <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border-b">Nilai Huruf</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Keterangan</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @php $ipRows = $ipRows ?? collect(); @endphp
        @if ($ipRows->isEmpty())
          <tr>
            <td class="px-4 py-6 text-center text-sm text-slate-500" colspan="5">
              Belum ada data IP.
            </td>
          </tr>
        @endif
        @foreach ($ipRows as $i => $row)
          @php
            $ipValue = (float) ($row['nilai_ip'] ?? 0);
            $ipClass = 'text-slate-400';
            if ($ipValue < 2) {
              $ipClass = 'text-red-600';
            } elseif ($ipValue < 2.75) {
              $ipClass = 'text-amber-600';
            } elseif ($ipValue < 3.75) {
              $ipClass = 'text-blue-600';
            } else {
              $ipClass = 'text-emerald-600';
            }
          @endphp
          <tr class="hover:bg-gray-50 ip-row" data-semester="{{ $row['semester'] ?? '' }}">
            <td class="px-4 py-2 text-center text-sm text-gray-700 ip-no-cell">{{ $i + 1 }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ $row['kode_mata_kuliah'] ?? '-' }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ $row['mata_kuliah'] ?? '-' }}</td>
            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $row['sks'] ?? '-' }}</td>
            <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $row['dosen_pengampu'] ?? '-' }}</td>
            <td class="px-4 py-2 text-center text-sm font-semibold {{ $ipClass }}">{{ number_format((float) ($row['nilai_ip'] ?? 0), 2) }}</td>
            <td class="px-4 py-2 text-center text-sm font-semibold {{ $ipClass }}">{{ $row['nilai_huruf'] ?? '-' }}</td>
            <td class="px-4 py-2 text-sm {{ $ipClass }}">{{ $row['keterangan'] ?? '-' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    </div>
  </div>


<div class="bg-white rounded-lg shadow-md overflow-hidden w-full">
  <div class="px-4 py-3 border-b flex items-start justify-between gap-3">
    <div>
      <h3 class="text-sm font-semibold text-slate-800">Nilai</h3>
      <p class="text-xs text-slate-500">Rekap nilai tugas, kuis, dan ujian.</p>
    </div>
    <button
      type="button"
      id="btnToggleNilaiTable"
      class="inline-flex items-center gap-1 rounded-md border px-3 py-1.5 text-xs font-semibold transition bg-blue-100 text-blue-700 border-blue-200"
      title="Sembunyikan/Tampilkan tabel Nilai"
    >
      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
        <circle cx="12" cy="12" r="3"></circle>
      </svg>
    </button>
  </div>
  <div class="px-4 py-3 border-b">
    <div class="flex flex-wrap items-center gap-3">
      <div class="flex items-center gap-2">
        <label for="semester_filter" class="text-sm text-slate-800">Semester</label>
          <select
            name="semester_filter"
            id="semester_filter"
            class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800"
          >
            <option value="">Semua Semester</option>
            @foreach (($semesterOptions ?? []) as $sem)
              <option value="{{ $sem['value'] }}">{{ $sem['label'] }}</option>
            @endforeach
          </select>
      </div>
      <div class="flex items-center gap-2">
        <label for="mata_kuliah" class="text-sm text-slate-800">Mata Kuliah</label>
        <select
          name="mata_kuliah"
          id="mata_kuliah"
          class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800"
        >
          <option value="">Semua Mata Kuliah</option>
        </select>
      </div>
    </div>
  </div>
  <div id="nilaiTableWrap" class="overflow-x-auto">
  <table class="min-w-[900px] md:min-w-full border border-gray-200">
      <thead class="bg-gray-100">
          <tr>
              <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border-b">
                  No.
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Mata Kuliah
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Jenis Penilaian
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Judul
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Semester
              </th>
              <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border-b">
                  Nilai
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Keterangan
              </th>
          </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">
          @if ($nilaiRows->isEmpty())
            <tr>
              <td class="px-4 py-6 text-center text-sm text-slate-500" colspan="5">
                Belum ada nilai.
              </td>
            </tr>
          @endif
          @foreach ($nilaiRows as $row)
            @php
              $nilaiRaw = is_numeric($row['nilai'] ?? null) ? (float) $row['nilai'] : null;
              $nilaiLabel = is_null($nilaiRaw) ? ($row['nilai'] ?? '-') : (string) $row['nilai'];
              $nilaiClass = 'text-slate-400';
              if (!is_null($nilaiRaw)) {
                  if ($nilaiRaw < 60) {
                      $nilaiClass = 'text-red-600';
                  } elseif ($nilaiRaw < 70) {
                      $nilaiClass = 'text-amber-600';
                  } elseif ($nilaiRaw < 90) {
                      $nilaiClass = 'text-blue-600';
                  } else {
                      $nilaiClass = 'text-emerald-600';
                  }
              }
              $jenis = strtolower((string) ($row['jenis'] ?? ''));
              $badgeClass = $jenis === 'ujian'
                  ? 'bg-purple-100 text-purple-700'
                  : 'bg-blue-100 text-blue-700';
            @endphp
            @php
              $semesterValue = $row['semester'] ?? ($row['semester_ke'] ?? '-');
            @endphp
            <tr
                class="hover:bg-gray-50 nilai-row"
                data-semester="{{ $semesterValue !== '-' ? $semesterValue : '' }}"
                data-nilai="{{ is_null($nilaiRaw) ? '' : $nilaiRaw }}"
            >
                <td class="px-4 py-2 text-center text-sm text-gray-700 nilai-no-cell">
                    {{ $loop->iteration }}
                </td>
                <td class="px-4 py-2 text-sm text-gray-700">
                    {{ $row['mata_kuliah'] ?? '-' }}
                </td>
                <td class="px-4 py-2">
                    <span class="{{ $badgeClass }} text-xs px-2 py-1 rounded-full">
                        {{ $row['jenis'] ?? '-' }}
                    </span>
                </td>
                <td class="px-4 py-2 text-sm text-gray-700">
                    {{ $row['judul'] ?? '-' }}
                </td>
                <td class="px-4 py-2 text-sm text-gray-700">
                    {{ $semesterValue }}
                </td>
                <td class="px-4 py-2 text-center text-sm font-semibold {{ $nilaiClass }}">
                    {{ $nilaiLabel }}
                </td>
                <td class="px-4 py-2 text-sm text-gray-700">
                    {{ $row['keterangan'] ?? '-' }}
                </td>
            </tr>
          @endforeach
      </tbody>
  </table>
  </div>
</div>

<script>
  const filterSelect = document.getElementById('mata_kuliah');
  const semesterSelect = document.getElementById('semester_filter');
  const rows = Array.from(document.querySelectorAll('.nilai-row'));
  const ipSemesterSelect = document.getElementById('ip_semester_filter');
  const ipRows = Array.from(document.querySelectorAll('.ip-row'));
  const ipSemesterValue = document.getElementById('ipSemesterValue');
  const btnToggleIpTable = document.getElementById('btnToggleIpTable');
  const ipTableWrap = document.getElementById('ipTableWrap');
  const btnToggleNilaiTable = document.getElementById('btnToggleNilaiTable');
  const nilaiTableWrap = document.getElementById('nilaiTableWrap');

  const getMatkul = (row) => {
    const cell = row.querySelectorAll('td')[1];
    return (cell?.textContent || '').trim();
  };

  const getSemester = (row) => {
    const cell = row.querySelectorAll('td')[4];
    return (cell?.textContent || '').trim();
  };

  const buildOptions = () => {
    if (!filterSelect) return;
    const matkulSet = new Set();
    const semesterSet = new Set();
    rows.forEach((row) => {
      const matkul = getMatkul(row);
      if (matkul) matkulSet.add(matkul);
      const semester = getSemester(row);
      if (semester && semester !== '-') semesterSet.add(semester);
    });
    const options = Array.from(matkulSet).sort();
    options.forEach((matkul) => {
      const opt = document.createElement('option');
      opt.value = matkul;
      opt.textContent = matkul;
      filterSelect.appendChild(opt);
    });
    // semester options are provided from mahasiswa table
  };

  const applyFilter = () => {
    const selected = (filterSelect?.value || '').toLowerCase();
    const selectedSemester = (semesterSelect?.value || '').toLowerCase();
    let visibleNo = 1;
    rows.forEach((row) => {
      const matkul = getMatkul(row).toLowerCase();
      const semester = getSemester(row).toLowerCase();
      const matchMatkul = !selected || matkul === selected;
      const matchSemester = !selectedSemester || semester === selectedSemester;
      if (matchMatkul && matchSemester) {
        row.classList.remove('hidden');
        const noCell = row.querySelector('.nilai-no-cell');
        if (noCell) noCell.textContent = visibleNo++;
      } else {
        row.classList.add('hidden');
      }
    });
  };

  buildOptions();
  filterSelect?.addEventListener('change', applyFilter);
  semesterSelect?.addEventListener('change', applyFilter);
  applyFilter();

  const applyIpFilter = () => {
    const selected = (ipSemesterSelect?.value || '').toLowerCase();
    let visibleNo = 1;
    ipRows.forEach((row) => {
      const sem = (row.dataset.semester || '').toLowerCase();
      const match = !selected || sem === selected;
      row.style.display = match ? '' : 'none';
      if (match) {
        const noCell = row.querySelector('.ip-no-cell');
        if (noCell) noCell.textContent = visibleNo++;
      }
    });

    const selectedRows = ipRows.filter((row) => row.style.display !== 'none');
    let totalSks = 0;
    let totalIp = 0;
    selectedRows.forEach((row) => {
      const sksCell = row.querySelector('td:nth-child(4)');
      const ipCell = row.querySelector('td:nth-child(6)');
      const sks = Number((sksCell?.textContent || '').trim());
      const ip = Number((ipCell?.textContent || '').trim());
      if (!Number.isNaN(sks) && !Number.isNaN(ip)) {
        totalSks += sks;
        totalIp += ip * sks;
      }
    });
    const ips = totalSks > 0 ? (totalIp / totalSks) : 0;
    if (ipSemesterValue) {
      ipSemesterValue.textContent = ips.toFixed(2);
    }
  };

  ipSemesterSelect?.addEventListener('change', applyIpFilter);
  applyIpFilter();

  const setEyeButtonState = (button, isActive) => {
    if (!button) return;
    button.classList.remove('bg-blue-100', 'text-blue-700', 'border-blue-200', 'bg-red-100', 'text-red-700', 'border-red-200');
    if (isActive) {
      button.classList.add('bg-blue-100', 'text-blue-700', 'border-blue-200');
      button.innerHTML = `
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        </svg>
      `;
    } else {
      button.classList.add('bg-red-100', 'text-red-700', 'border-red-200');
      button.innerHTML = `
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17.94 17.94A10.6 10.6 0 0 1 12 20c-7 0-11-8-11-8a21.8 21.8 0 0 1 5.06-5.94"></path>
          <path d="M9.9 4.24A10.9 10.9 0 0 1 12 4c7 0 11 8 11 8a21.7 21.7 0 0 1-4.13 5.36"></path>
          <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
          <path d="M1 1l22 22"></path>
        </svg>
      `;
    }
  };

  const toggleTableVisibility = (button, wrapEl) => {
    if (!button || !wrapEl) return;
    const hidden = wrapEl.classList.toggle('hidden');
    setEyeButtonState(button, !hidden);
  };

  btnToggleIpTable?.addEventListener('click', () => toggleTableVisibility(btnToggleIpTable, ipTableWrap));
  btnToggleNilaiTable?.addEventListener('click', () => toggleTableVisibility(btnToggleNilaiTable, nilaiTableWrap));
  setEyeButtonState(btnToggleIpTable, true);
  setEyeButtonState(btnToggleNilaiTable, true);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const chartLabels = [];
  const chartValues = [];
  const nilaiRawList = [];
  const tugasValues = [];
  const ujianValues = [];

  @foreach ($nilaiRows as $row)
    @php
      $nilaiRaw = is_numeric($row['nilai'] ?? null) ? (float) $row['nilai'] : null;
      $nilaiVal = is_null($nilaiRaw) ? 0 : $nilaiRaw;
      $label = trim(($row['jenis'] ?? '') . ' - ' . ($row['judul'] ?? '-'));
    @endphp
    chartLabels.push(@json($label));
    chartValues.push({{ $nilaiVal }});
    nilaiRawList.push({{ $nilaiVal }});
    @if (strtolower((string) ($row['jenis'] ?? '')) === 'tugas')
      tugasValues.push({{ $nilaiVal }});
    @elseif (strtolower((string) ($row['jenis'] ?? '')) === 'ujian')
      ujianValues.push({{ $nilaiVal }});
    @endif
  @endforeach

  const buildIpsSeries = () => {
    const grouped = new Map();
    rows.forEach((row) => {
      const semester = (row.dataset.semester || '').trim();
      const nilaiStr = row.dataset.nilai || '';
      if (!semester || nilaiStr === '') return;
      const nilai = Number(nilaiStr);
      if (Number.isNaN(nilai)) return;
      const ipkVal = Math.max(0, Math.min(4, nilai / 25));
      const list = grouped.get(semester) || [];
      list.push(ipkVal);
      grouped.set(semester, list);
    });
    const labels = [];
    const ipsList = [];
    const semesters = Array.from(grouped.keys())
      .map((v) => Number(v))
      .filter((v) => !Number.isNaN(v))
      .sort((a, b) => a - b);

    semesters.forEach((semNum) => {
      const sem = String(semNum);
      const list = grouped.get(sem) || [];
      const sum = list.reduce((acc, v) => acc + v, 0);
      labels.push(`Semester ${sem}`);
      ipsList.push(list.length ? sum / list.length : 0);
    });

    return { labels, ips: ipsList };
  };

  const chartCtx = document.getElementById('nilaiChart');
  if (chartCtx) {
    const ipsSeries = buildIpsSeries();
    const labels = ipsSeries.labels.length ? ipsSeries.labels : chartLabels;
    const values = ipsSeries.ips.length ? ipsSeries.ips : chartValues;
    new Chart(chartCtx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          data: values,
          borderWidth: 2,
          tension: 0.35,
          fill: false,
          borderColor: '#2563eb',
          pointBackgroundColor: '#2563eb',
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            min: 0,
            max: 4,
            ticks: { stepSize: 0.5 }
          },
          x: {
            ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 8 }
          }
        }
      }
    });
  }

  const radarLabels = @json($radarLabels) || ['Kehadiran', 'Keaktifan', 'Tugas', 'Ujian', 'Kecepatan'];
  const radarData = @json($radarData) || [0, 0, 0, 0, 0];

  const radarCtx = document.getElementById('nilaiRadar');
  if (radarCtx) {
    const radarChart = new Chart(radarCtx, {
      type: 'radar',
      data: {
        labels: radarLabels,
        datasets: [{
          data: radarData,
          fill: true,
          backgroundColor: 'rgba(59,130,246,0.25)',
          borderColor: 'rgb(59,130,246)',
          pointBackgroundColor: 'rgb(59,130,246)',
          pointBorderColor: '#fff',
          pointRadius: 4,
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          r: {
            min: 0,
            max: 100,
            ticks: { display: false },
            grid: { color: '#e5e7eb' },
            angleLines: { color: '#d1d5db' },
            pointLabels: {
              font: { size: 12, weight: 'bold' },
              color: '#374151'
            }
          }
        }
      }
    });

    const radarSemesterSelect = document.getElementById('radar_semester_filter');

    const computeRadar = (semester) => {
      const values = {
        kehadiran: [],
        keaktifan: [],
        tugas: [],
        ujian: [],
        kecepatan: [],
      };
      rows.forEach((row) => {
        const sem = (row.dataset.semester || '').trim();
        if (semester && sem !== semester) return;
        const nilai = Number(row.dataset.nilai || 0);
        if (Number.isNaN(nilai)) return;
        const jenis = (row.querySelector('td:nth-child(4)')?.textContent || '').toLowerCase();
        if (jenis.includes('tugas')) values.tugas.push(nilai);
        if (jenis.includes('ujian')) values.ujian.push(nilai);
      });
      const avg = (arr) => arr.length ? arr.reduce((a, b) => a + b, 0) / arr.length : 0;
      const kehadiran = Number(@json($radarData[0] ?? 0));
      const keaktifan = Number(@json($radarData[1] ?? 0));
      const kecepatan = Number(@json($radarData[4] ?? 0));
      return [
        kehadiran,
        keaktifan,
        avg(values.tugas),
        avg(values.ujian),
        kecepatan,
      ];
    };

    const applyRadarFilter = () => {
      const sem = radarSemesterSelect?.value || '';
      const data = computeRadar(sem);
      radarChart.data.datasets[0].data = data.map((v) => Math.round(v * 100) / 100);
      radarChart.update();
    };

    radarSemesterSelect?.addEventListener('change', applyRadarFilter);
  }
</script>

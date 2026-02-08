<x-header> </x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

@php
  $nilaiRows = $nilaiRows ?? collect();
@endphp

<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Nilai</h2>
      <p class="text-sm text-slate-500">Rekap nilai tugas, kuis, dan ujian.</p>
    </div>
    <div class="flex items-center gap-2">
      <label for="mata_kuliah" class="text-sm text-slate-500">Filter</label>
      <select
        name="mata_kuliah"
        id="mata_kuliah"
        class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-700"
      >
        <option value="">Semua Mata Kuliah</option>
      </select>
    </div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl shadow p-4">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-slate-800">Grafik Nilai</h3>
        <span class="text-xs text-slate-500">Berdasarkan data tabel</span>
      </div>
      <div class="relative w-full h-56">
        <canvas id="nilaiChart"></canvas>
      </div>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-slate-800">Statistik Mahasiswa</h3>
        <span class="text-xs text-slate-500">Ringkasan nilai</span>
      </div>
      <div class="relative w-full h-56">
        <canvas id="nilaiRadar"></canvas>
      </div>
    </div>
  </div>
<div class="bg-white rounded-lg shadow-md overflow-hidden w-full">
  <table class="min-w-full border border-gray-200">
      <thead class="bg-gray-100">
          <tr>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Mata Kuliah
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Jenis Penilaian
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Judul
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
            <tr class="hover:bg-gray-50 nilai-row">
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

<script>
  const filterSelect = document.getElementById('mata_kuliah');
  const rows = Array.from(document.querySelectorAll('.nilai-row'));

  const getMatkul = (row) => {
    const cell = row.querySelector('td');
    return (cell?.textContent || '').trim();
  };

  const buildOptions = () => {
    if (!filterSelect) return;
    const matkulSet = new Set();
    rows.forEach((row) => {
      const matkul = getMatkul(row);
      if (matkul) matkulSet.add(matkul);
    });
    const options = Array.from(matkulSet).sort();
    options.forEach((matkul) => {
      const opt = document.createElement('option');
      opt.value = matkul;
      opt.textContent = matkul;
      filterSelect.appendChild(opt);
    });
  };

  const applyFilter = () => {
    const selected = (filterSelect?.value || '').toLowerCase();
    rows.forEach((row) => {
      const matkul = getMatkul(row).toLowerCase();
      if (!selected || matkul === selected) {
        row.classList.remove('hidden');
      } else {
        row.classList.add('hidden');
      }
    });
  };

  buildOptions();
  filterSelect?.addEventListener('change', applyFilter);
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

  const chartCtx = document.getElementById('nilaiChart');
  if (chartCtx) {
    new Chart(chartCtx, {
      type: 'line',
      data: {
        labels: chartLabels,
        datasets: [{
          data: chartValues,
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
            max: 100,
            ticks: { stepSize: 10 }
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
    new Chart(radarCtx, {
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
  }
</script>

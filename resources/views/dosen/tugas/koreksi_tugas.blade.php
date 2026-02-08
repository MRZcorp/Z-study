<x-header>Data Tugas</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-start gap-3">
      <a href="{{ url('/dosen/tugas_selesai') }}"
         class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white shadow hover:bg-blue-700">
        <span class="material-symbols-rounded text-base">chevron_left</span>
      </a>
      <div>
        <h2 class="text-xl font-semibold text-slate-800">Koreksi Tugas {{ $matkulName ?? '' }}</h2>
        @if(!empty($tugasNama))
          <p class="text-sm text-slate-700 font-medium">{{ $tugasNama }}</p>
          @php
            $durasiTugasLabel = '-';
            if (!empty($tugasMulai) && !empty($tugasDeadline)) {
                $startDur = \Carbon\Carbon::parse($tugasMulai);
                $endDur = \Carbon\Carbon::parse($tugasDeadline);
                $diffMinutesTotal = max(0, $startDur->diffInMinutes($endDur));
                if ($diffMinutesTotal >= 1440) {
                    $hari = intdiv($diffMinutesTotal, 1440);
                    $sisaMenit = $diffMinutesTotal % 1440;
                    $jam = intdiv($sisaMenit, 60);
                    $menit = $sisaMenit % 60;
                    $durasiTugasLabel = $menit > 0 ? "{$hari} hari {$jam} jam {$menit} menit" : "{$hari} hari {$jam} jam";
                } elseif ($diffMinutesTotal >= 60) {
                    $jam = intdiv($diffMinutesTotal, 60);
                    $menit = $diffMinutesTotal % 60;
                    $durasiTugasLabel = $menit > 0 ? "{$jam} jam {$menit} menit" : "{$jam} jam";
                } else {
                    $durasiTugasLabel = "{$diffMinutesTotal} menit";
                }
            }
          @endphp
          <p class="text-sm text-blue-600 font-semibold">
            Tugas ke: {{ $tugasKe ?? '-' }}
          </p>
        @else
          <p class="text-sm text-slate-500">{{ $kelasName ?? '' }}</p>
        @endif
        <p class="text-sm text-slate-500 mt-1">
          Waktu mengerjakan: {{ $durasiTugasLabel }}
        </p>
      </div>
    </div>
    <div></div>
  </div>

    <div class="rounded-xl border bg-white overflow-hidden">
    <div class="px-5 py-4 border-b flex items-center justify-between">
      <div class="flex items-center gap-3">
        <h3 class="font-semibold text-slate-800">Mahasiswa Mengumpulkan</h3>
      </div>
      <div class="text-right">
        <div class="flex items-center gap-2 justify-end">
          <span class="text-sm text-slate-600">{{ $kelasName ?? '' }}</span>
          <span class="text-sm font-semibold text-green-600">{{ $kumpulCount ?? 0 }} / {{ $kuotaKelas ?? 0 }}</span>
        </div>
        <div class="text-sm text-slate-500 mt-1">Waktu mengerjakan: {{ $durasiTugasLabel }}</div>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-center">No</th>
            <th class="px-4 py-3 text-left">Nama Mahasiswa</th>
            <th class="px-4 py-3 text-center">NIM</th>
            <th class="px-4 py-3 text-left">Program Studi</th>
            <th class="px-4 py-3 text-left">Waktu Pengumpulan</th>
            <th class="px-4 py-3 text-center">Koreksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse (($pengumpulan ?? []) as $i => $row)
            @php
              $nilaiKecepatan = 0;
              $nilaiInput = 0;
              $waktuMengumpulkan = 0;
              $durasiLabel = '-';
              if (!empty($tugasMulai) && $row->submitted_at) {
                  $start = \Carbon\Carbon::parse($tugasMulai);
                  $submit = \Carbon\Carbon::parse($row->submitted_at);
                  $diffSeconds = max(0, $start->diffInSeconds($submit, false));
                  $totalMinutes = $diffSeconds / 60;
                  if ($totalMinutes >= 60) {
                      $jam = (int) floor($totalMinutes / 60);
                      $menit = $totalMinutes - ($jam * 60);
                      $menitLabel = number_format($menit, 2, ',', '');
                      $durasiLabel = $menit > 0 ? "{$jam} jam {$menitLabel} menit" : "{$jam} jam";
                  } else {
                      $durasiLabel = number_format($totalMinutes, 2, ',', '') . ' menit';
                  }
              }

              // Nilai kecepatan
              if (!empty($tugasMulai) && !empty($tugasDeadline) && $row->submitted_at) {
                  $start = \Carbon\Carbon::parse($tugasMulai);
                  $deadline = \Carbon\Carbon::parse($tugasDeadline);
                  $nilaiInput = max(0, $start->diffInMinutes($deadline));

                  $submit = \Carbon\Carbon::parse($row->submitted_at);
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
            <tr class="border-t">
              <td class="px-4 py-3 text-center">{{ $i + 1 }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                 
                  <span>{{ $row->mahasiswa->user->name ?? '-' }}</span>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                {{ $row->mahasiswa->nim ?? '-' }}
              </td>
              <td class="px-4 py-3">
                {{ $row->mahasiswa->programStudi->nama_prodi ?? '-' }}
              </td>
              <td class="px-4 py-3">
                {{ $durasiLabel }}
              </td>
              <td class="px-4 py-3">
                @php
                  $nilai = $row->nilai;
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
                @endphp
                <div class="flex items-center gap-2 justify-end">
                  <button
                    type="button"
                    class="btn-preview-pengumpulan rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                    data-file="{{ $row->file_path ? asset('storage/' . $row->file_path) : '' }}"
                    data-name="{{ $row->file_name ?? 'File' }}"
                    data-deskripsi="{{ $row->deskripsi ?? '-' }}"
                    data-nilai="{{ $row->nilai ?? '' }}"
                    data-id="{{ $row->id }}"
                    data-mhs-id="{{ $row->mahasiswa_id ?? '' }}"
                    data-tugas-id="{{ $tugasId ?? request('tugas_id') ?? '' }}"
                    data-tugas="{{ $tugasNama ?? '-' }}"
                    data-tugas-ke="{{ $tugasKe ?? '-' }}"
                  >
                    <span class="material-symbols-rounded text-base">edit</span>
                  </button>
                  <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-lg font-bold {{ $nilaiClass }}">
                    {{ $nilaiLabel }}
                  </div>
                  <button
                    type="button"
                    class="btn-nilai-kecepatan w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-base font-semibold text-yellow-800 hover:bg-yellow-200"
                    data-nilai="{{ $nilaiKecepatan }}"
                    data-waktu="{{ $waktuMengumpulkan }}"
                    data-nilai-input="{{ $nilaiInput }}"
                    data-mhs-id="{{ $row->mahasiswa_id ?? '' }}"
                    data-tugas-id="{{ $tugasId ?? request('tugas_id') ?? '' }}"
                  >
                    {{ $nilaiKecepatan }}
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td class="px-4 py-4 text-slate-500" colspan="5">Belum ada data pengumpulan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-6 rounded-xl border bg-white overflow-hidden">
    <div class="px-5 py-4 border-b flex items-center justify-between">
      <div class="flex items-center gap-3">
        <h3 class="font-semibold text-slate-800">Mahasiswa Tidak Mengumpulkan</h3>
      </div>
      <div class="text-sm text-slate-500">
        {{ ($tidakMengumpulkan ?? collect())->count() }} mahasiswa
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-center">No</th>
            <th class="px-4 py-3 text-left">Nama Mahasiswa</th>
            <th class="px-4 py-3 text-left">NIM</th>
            <th class="px-4 py-3 text-left">Program Studi</th>
            <th class="px-4 py-3 text-left">Waktu Pengumpulan</th>
            <th class="px-4 py-3 text-center">Koreksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse (($tidakMengumpulkan ?? []) as $i => $mhs)
            @php
              $nilaiRow = ($nilaiMap[$mhs->id] ?? null);
              $nilaiVal = $nilaiRow ? $nilaiRow->nilai : null;
              $nilaiBoxClass = 'text-slate-400';
              if (!is_null($nilaiVal)) {
                  if ($nilaiVal < 60) {
                      $nilaiBoxClass = 'text-red-600';
                  } elseif ($nilaiVal < 70) {
                      $nilaiBoxClass = 'text-amber-600';
                  } elseif ($nilaiVal < 90) {
                      $nilaiBoxClass = 'text-blue-600';
                  } else {
                      $nilaiBoxClass = 'text-emerald-600';
                  }
              }
            @endphp
            <tr class="border-t">
              <td class="px-4 py-3">{{ $i + 1 }}</td>
              <td class="px-4 py-3">
                <span>{{ $mhs->user->name ?? '-' }}</span>
              </td>
              <td class="px-4 py-3">{{ $mhs->nim ?? '-' }}</td>
              <td class="px-4 py-3">{{ $mhs->programStudi->nama_prodi ?? '-' }}</td>
              <td class="px-4 py-3">-</td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    class="btn-preview-pengumpulan rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                    data-file=""
                  data-name="-"
                  data-deskripsi="-"
                  data-nilai="{{ $nilaiVal ?? '' }}"
                  data-id="{{ $nilaiRow->id ?? '' }}"
                  data-mhs-id="{{ $mhs->id }}"
                  data-tugas-id="{{ $tugasId ?? request('tugas_id') ?? '' }}"
                  data-tugas="{{ $tugasNama ?? '-' }}"
                  data-tugas-ke="{{ $tugasKe ?? '-' }}"
                  data-non-submit="1"
                >
                  <span class="material-symbols-rounded text-base">edit</span>
                </button>
                  <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-lg font-bold {{ $nilaiBoxClass }}">
                    {{ is_null($nilaiVal) ? 0 : $nilaiVal }}
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td class="px-4 py-4 text-slate-500" colspan="6">Semua mahasiswa sudah mengumpulkan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
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

<!-- MODAL PREVIEW PENGUMPULAN -->
<div id="previewPengumpulanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-[70vw] h-[70vh] bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div>
        <h3 id="previewPengumpulanTitle" class="text-lg font-semibold text-slate-800">Nama Tugas</h3>
        <p id="previewPengumpulanSub" class="text-sm text-blue-600 font-semibold">Tugas ke: -</p>
      </div>
      <div class="flex items-center gap-2">
        <a id="previewPengumpulanDownload" href="#" target="_blank" class="rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
          <span class="material-symbols-rounded text-base">download</span>
        </a>
        <button id="btnClosePengumpulan" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
      </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-5 h-[calc(70vh-64px)] min-h-0">
      <div class="lg:col-span-2 h-full min-h-0">
        <div id="previewPengumpulanContainer" class="w-full h-full rounded-xl border bg-slate-50 flex items-center justify-center text-sm text-slate-500">
          Tidak ada file.
        </div>
      </div>
      <div class="lg:col-span-1 flex flex-col gap-3 h-full min-h-0">
        <div>
          <p class="text-xs text-slate-500">Deskripsi Mahasiswa</p>
          <p id="previewMahasiswaDeskripsi" class="text-sm text-slate-700">-</p>
        </div>
        <div>
          <p class="text-xs text-slate-500">Nama File</p>
          <p id="previewMahasiswaFile" class="text-sm text-slate-700">-</p>
        </div>
        <div class="mt-auto space-y-3">
          <div>
            <label class="block text-xs text-slate-500 mb-1">Nilai</label>
            <input id="inputNilaiPengumpulan" type="number" min="0" max="100" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Masukkan nilai">
          </div>
          <button id="btnSaveNilai" type="button" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL SUKSES NILAI -->
<div id="nilaiSuccessModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center">
    <p class="text-base font-semibold text-slate-800">Nilai berhasil disimpan</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-emerald-600">check_circle</span>
    </div>
  </div>
</div>

<script>
  const previewPengumpulanModal = document.getElementById('previewPengumpulanModal');
  const btnClosePengumpulan = document.getElementById('btnClosePengumpulan');
  const previewPengumpulanContainer = document.getElementById('previewPengumpulanContainer');
  const previewPengumpulanDownload = document.getElementById('previewPengumpulanDownload');
  const previewPengumpulanTitle = document.getElementById('previewPengumpulanTitle');
  const previewPengumpulanSub = document.getElementById('previewPengumpulanSub');
  const previewMahasiswaDeskripsi = document.getElementById('previewMahasiswaDeskripsi');
  const previewMahasiswaFile = document.getElementById('previewMahasiswaFile');
  const inputNilaiPengumpulan = document.getElementById('inputNilaiPengumpulan');
  const btnSaveNilai = document.getElementById('btnSaveNilai');
  const nilaiSuccessModal = document.getElementById('nilaiSuccessModal');
  let currentPengumpulanId = null;
  let currentMahasiswaId = null;
  let currentTugasId = null;

  const renderPengumpulan = (url, ext) => {
    const lower = (ext || '').toLowerCase();
    if (!url) {
      previewPengumpulanContainer.innerHTML = 'Tidak ada file.';
      return;
    }
    if (['mp4', 'webm', 'ogg'].includes(lower)) {
      previewPengumpulanContainer.innerHTML = `<video src="${url}" controls class="w-full h-full rounded-xl bg-black"></video>`;
      return;
    }
    if (['pdf'].includes(lower)) {
      previewPengumpulanContainer.innerHTML = `<iframe src="${url}" class="w-full h-full rounded-xl"></iframe>`;
      return;
    }
    if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(lower)) {
      previewPengumpulanContainer.innerHTML = `<div class="text-center text-slate-500 text-sm">Preview tidak tersedia untuk file ini. Silakan download.</div>`;
      return;
    }
    previewPengumpulanContainer.innerHTML = `<iframe src="${url}" class="w-full h-full rounded-xl"></iframe>`;
  };

  document.querySelectorAll('.btn-preview-pengumpulan').forEach((btn) => {
    btn.addEventListener('click', () => {
      const fileUrl = btn.dataset.file || '';
      const fileName = btn.dataset.name || 'File';
      const tugasNama = btn.dataset.tugas || 'Tugas';
      const tugasKe = btn.dataset.tugasKe || '-';
      const deskripsi = btn.dataset.deskripsi || '-';
      const nilai = btn.dataset.nilai || '';
      currentPengumpulanId = btn.dataset.id || null;
      currentMahasiswaId = btn.dataset.mhsId || null;
      currentTugasId = btn.dataset.tugasId || null;
      previewPengumpulanTitle.textContent = tugasNama;
      if (previewPengumpulanSub) {
        previewPengumpulanSub.textContent = `Tugas ke: ${tugasKe}`;
      }
      previewPengumpulanDownload.href = fileUrl || '#';
      previewPengumpulanDownload.classList.toggle('pointer-events-none', !fileUrl);
      previewPengumpulanDownload.classList.toggle('opacity-50', !fileUrl);
      const ext = fileName.split('.').pop() || '';
      renderPengumpulan(fileUrl, ext);
      if (previewMahasiswaDeskripsi) previewMahasiswaDeskripsi.textContent = deskripsi;
      if (previewMahasiswaFile) previewMahasiswaFile.textContent = fileName;
      if (inputNilaiPengumpulan) inputNilaiPengumpulan.value = nilai;
      previewPengumpulanModal.classList.remove('hidden');
      previewPengumpulanModal.classList.add('flex');
    });
  });

  const closePengumpulan = () => {
    previewPengumpulanModal.classList.add('hidden');
    previewPengumpulanModal.classList.remove('flex');
    previewPengumpulanContainer.innerHTML = 'Tidak ada file.';
  };

  btnClosePengumpulan?.addEventListener('click', closePengumpulan);
  previewPengumpulanModal?.addEventListener('click', (e) => {
    if (e.target === previewPengumpulanModal) closePengumpulan();
  });

  btnSaveNilai?.addEventListener('click', async () => {
    const nilai = inputNilaiPengumpulan?.value ?? '';
    if (!currentPengumpulanId && (!currentMahasiswaId || !currentTugasId)) {
      alert('Data mahasiswa/tugas tidak lengkap.');
      return;
    }
    let nilaiKecepatan = null;
    if (currentMahasiswaId && currentTugasId) {
      const btnKecepatan = document.querySelector(
        `.btn-nilai-kecepatan[data-mhs-id="${currentMahasiswaId}"][data-tugas-id="${currentTugasId}"]`
      );
      if (btnKecepatan) {
        nilaiKecepatan = btnKecepatan.dataset.nilai || null;
      }
    }
    try {
      const res = await fetch("{{ route('dosen.tugas.nilai.save') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          id: currentPengumpulanId,
          mahasiswa_id: currentMahasiswaId,
          tugas_id: currentTugasId,
          nilai,
          nilai_kecepatan: nilaiKecepatan,
        }),
      });
      if (!res.ok) {
        throw new Error('Gagal menyimpan nilai');
      }
      if (btnSaveNilai) {
        btnSaveNilai.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        btnSaveNilai.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
      }
      if (nilaiSuccessModal) {
        nilaiSuccessModal.classList.remove('hidden');
        nilaiSuccessModal.classList.add('flex');
        setTimeout(() => {
          nilaiSuccessModal.classList.add('hidden');
          nilaiSuccessModal.classList.remove('flex');
        }, 1000);
      }
    } catch (err) {
      alert('Gagal menyimpan nilai.');
    }
  });

  const nilaiKecepatanModal = document.getElementById('nilaiKecepatanModal');
  const btnCloseNilaiKecepatan = document.getElementById('btnCloseNilaiKecepatan');
  const nilaiKecepatanInput = document.getElementById('nilaiKecepatanInput');
  const nilaiKecepatanWaktu = document.getElementById('nilaiKecepatanWaktu');
  const nilaiKecepatanLoop = document.getElementById('nilaiKecepatanLoop');

  document.querySelectorAll('.btn-nilai-kecepatan').forEach((btn) => {
    btn.addEventListener('click', () => {
      const nilai = Number(btn.dataset.nilai || 0);
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
          if (nilaiTercepatLoop <= 0 || nilaiInput <= 0) {
            continue;
          }
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
</script>

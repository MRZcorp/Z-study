<x-header>Koreksi Ujian</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

@php
  $pengumpulan = $pengumpulan ?? collect();
  $tidakMengumpulkan = $tidakMengumpulkan ?? collect();
  $jawabanMap = $jawabanMap ?? [];
@endphp

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-start gap-3">
      <a href="{{ url('/dosen/ujian_selesai') }}"
         class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white shadow hover:bg-blue-700">
        <span class="material-symbols-rounded text-base">chevron_left</span>
      </a>
      <div>
        <h2 class="text-xl font-semibold text-slate-800">Koreksi Ujian</h2>
        <p class="text-sm text-slate-500">Daftar peserta dan penilaian ujian.</p>
      </div>
    </div>
  </div>

  <div class="rounded-xl border bg-white overflow-hidden">
    <div class="px-5 py-4 border-b flex items-center justify-between">
      <div class="flex items-center gap-3">
        <h3 class="font-semibold text-slate-800">Mahasiswa Mengumpulkan</h3>
      </div>
      <div class="flex items-center gap-2">
        <span class="text-sm text-slate-600">Kelas {{ $kelasNama ?? '-' }}</span>
        <span class="text-sm font-semibold text-green-600">{{ $kumpulCount ?? 0 }} / {{ $kuotaKelas ?? 0 }}</span>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">No</th>
            <th class="px-4 py-3 text-left">Nama</th>
            <th class="px-4 py-3 text-left">NIM</th>
            <th class="px-4 py-3 text-left">Program Studi</th>
            <th class="px-4 py-3 text-left">Waktu Pengumpulan</th>
            <th class="px-4 py-3 text-left">Koreksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pengumpulan as $i => $row)
            @php
              $nilaiKecepatan = 0;
              $nilaiInput = 0;
              $waktuMengumpulkan = 0;
              if (!empty($ujianMulai) && !empty($ujianDeadline) && $row->submitted_at) {
                  $start = \Carbon\Carbon::parse($ujianMulai);
                  $deadline = \Carbon\Carbon::parse($ujianDeadline);
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
              <td class="px-4 py-3">{{ $i + 1 }}</td>
              <td class="px-4 py-3">
                <span>{{ $row->mahasiswa->user->name ?? '-' }}</span>
              </td>
              <td class="px-4 py-3">{{ $row->mahasiswa->nim ?? '-' }}</td>
              <td class="px-4 py-3">{{ $row->mahasiswa->programStudi->nama_prodi ?? '-' }}</td>
              <td class="px-4 py-3">{{ $row->submitted_at ? \Carbon\Carbon::parse($row->submitted_at)->format('d M Y H:i') : '-' }}</td>
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
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    class="btn-preview-jawaban rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                    data-nama="{{ $row->mahasiswa->user->name ?? '-' }}"
                    data-mahasiswa-id="{{ $row->mahasiswa_id }}"
                    data-ujian-id="{{ $row->ujian_id }}"
                    data-jawaban='@json($jawabanMap[$row->mahasiswa_id] ?? [])'
                  >
                    <span class="material-symbols-rounded text-base">edit</span>
                  </button>
                  <button
                    type="button"
                    class="btn-nilai-kecepatan w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-base font-semibold text-yellow-800 hover:bg-yellow-200"
                    data-nilai="{{ $nilaiKecepatan }}"
                    data-waktu="{{ $waktuMengumpulkan }}"
                    data-nilai-input="{{ $nilaiInput }}"
                    data-mhs-id="{{ $row->mahasiswa_id ?? '' }}"
                    data-ujian-id="{{ $row->ujian_id ?? '' }}"
                  >
                    {{ $nilaiKecepatan }}
                  </button>
                  <div id="nilaiBox-{{ $row->mahasiswa_id }}" class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-lg font-bold {{ $nilaiClass }}">
                    {{ $nilaiLabel }}
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td class="px-4 py-4 text-slate-500" colspan="6">Belum ada data pengumpulan.</td>
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
        {{ $tidakMengumpulkan->count() }} mahasiswa
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">No</th>
            <th class="px-4 py-3 text-left">Nama</th>
            <th class="px-4 py-3 text-left">NIM</th>
            <th class="px-4 py-3 text-left">Program Studi</th>
            <th class="px-4 py-3 text-left">Waktu Pengumpulan</th>
            <th class="px-4 py-3 text-left">Koreksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($tidakMengumpulkan as $i => $mhs)
            <tr class="border-t">
              <td class="px-4 py-3">{{ $i + 1 }}</td>
              <td class="px-4 py-3">{{ $mhs->user->name ?? '-' }}</td>
              <td class="px-4 py-3">{{ $mhs->nim ?? '-' }}</td>
              <td class="px-4 py-3">{{ $mhs->programStudi->nama_prodi ?? '-' }}</td>
              <td class="px-4 py-3">-</td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <button type="button" class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                    <span class="material-symbols-rounded text-base">edit</span>
                  </button>
                  <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-lg font-bold text-red-600">0</div>
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

<!-- MODAL PREVIEW KOREKSI UJIAN -->
<div id="previewPengumpulanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-[70vw] h-[70vh] bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div>
        <h3 id="previewPengumpulanTitle" class="text-lg font-semibold text-slate-800">Nama Ujian</h3>
        <p id="previewPengumpulanSub" class="text-sm text-blue-600 font-semibold">Ujian ke: -</p>
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
    <p class="text-base font-semibold text-slate-800">Nilai ujian berhasil disimpan</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-emerald-600">check_circle</span>
    </div>
  </div>
</div>

<!-- MODAL PREVIEW JAWABAN -->
<div id="previewJawabanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div>
        <h3 class="text-lg font-semibold text-slate-800">Preview Jawaban</h3>
        <p id="previewJawabanNama" class="text-sm text-slate-500">-</p>
      </div>
      <div class="ml-auto flex items-center gap-4 text-sm">
        <div class="grid grid-cols-3 gap-4 text-right text-slate-500 whitespace-nowrap -ml-2">
          <div class="space-y-1">
            <div>Total PG : <span id="totalPg" class="font-semibold text-emerald-600">0</span></div>
            <div>Total Essay : <span id="totalEssay" class="font-semibold text-blue-600">0</span></div>
          </div>
          <div class="space-y-1 border-l border-slate-200 pl-3">
            <div>Poin PG : <span id="poinPg" class="font-semibold text-emerald-600">0</span></div>
            <div>Poin Essay : <span id="poinEssay" class="font-semibold text-blue-600">0</span></div>
          </div>
          <div class="space-y-1 border-l border-slate-200 pl-3">
            <div>Total Poin : <span id="totalPoin" class="font-semibold text-slate-700">0</span></div>
            <div>Nilai Akhir : <span id="nilaiAkhir" class="font-semibold text-slate-700">0</span></div>
          </div>
        </div>
        <button id="btnSaveNilaiUjian" type="button" class="rounded-full bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Save</button>
      </div>
      <button id="btnClosePreviewJawaban" type="button" class="text-gray-400 hover:text-gray-600 text-2xl leading-none px-1">&times;</button>
    </div>
    <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
      <div id="previewJawabanList" class="space-y-3"></div>
    </div>
  </div>
</div>

<script>
  const previewJawabanModal = document.getElementById('previewJawabanModal');
  const previewJawabanNama = document.getElementById('previewJawabanNama');
  const previewJawabanList = document.getElementById('previewJawabanList');
  const poinPgEl = document.getElementById('poinPg');
  const poinEssayEl = document.getElementById('poinEssay');
  const totalPgEl = document.getElementById('totalPg');
  const totalEssayEl = document.getElementById('totalEssay');
  const totalPoinEl = document.getElementById('totalPoin');
  const nilaiAkhirEl = document.getElementById('nilaiAkhir');
  const btnSaveNilaiUjian = document.getElementById('btnSaveNilaiUjian');
  const btnClosePreviewJawaban = document.getElementById('btnClosePreviewJawaban');
  const nilaiSuccessModal = document.getElementById('nilaiSuccessModal');
  const csrfToken = '{{ csrf_token() }}';
  let activeMahasiswaId = null;
  let activeUjianId = null;
  let saveTimer = null;
  let updateEssayTotal = () => {};
  let currentPoinPg = 0;

  const closePreviewJawaban = () => {
    previewJawabanModal?.classList.add('hidden');
    previewJawabanModal?.classList.remove('flex');
    if (previewJawabanList) previewJawabanList.innerHTML = '';
    if (poinPgEl) poinPgEl.textContent = '0';
    if (poinEssayEl) poinEssayEl.textContent = '0';
    if (totalPoinEl) totalPoinEl.textContent = '0';
    if (nilaiAkhirEl) nilaiAkhirEl.textContent = '0';
  };

  document.querySelectorAll('.btn-preview-jawaban').forEach((btn) => {
    btn.addEventListener('click', () => {
      const nama = btn.dataset.nama || '-';
      activeMahasiswaId = btn.dataset.mahasiswaId || null;
      activeUjianId = btn.dataset.ujianId || null;
      const jawaban = JSON.parse(btn.dataset.jawaban || '[]');
      if (previewJawabanNama) previewJawabanNama.textContent = nama;
      let poinPg = 0;
      let totalBobot = 0;
      let totalPg = 0;
      let totalEssay = 0;
      if (previewJawabanList) {
        previewJawabanList.innerHTML = '';
        if (jawaban.length === 0) {
          previewJawabanList.innerHTML = '<div class="text-sm text-slate-500">Belum ada jawaban.</div>';
        } else {
          jawaban.forEach((row, idx) => {
            const item = document.createElement('div');
            item.className = 'rounded-xl border bg-white p-4 relative';
            const tipe = (row.tipe || 'essay').toLowerCase();
            const bobot = row.bobot ?? 0;
            totalBobot += Number(bobot) || 0;
            if (tipe === 'pg') {
              totalPg += Number(bobot) || 0;
            } else {
              totalEssay += Number(bobot) || 0;
            }
            const badgeClass = tipe === 'pg' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600';
            const badgeText = tipe === 'pg' ? 'PG' : 'Essay';
            const optionsHtml = (tipe === 'pg' && Array.isArray(row.options))
              ? `<div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-slate-600">
                  ${row.options.map((opt, i) => {
                    const letter = String.fromCharCode(65 + i);
                    const isAnswered = (row.jawaban_pg || '').toUpperCase() === letter;
                    const isCorrect = (row.pg_correct || '').toUpperCase() === letter;
                    const isAnsweredCorrect = isAnswered && isCorrect;
                    if (isAnsweredCorrect) {
                      poinPg += Number(bobot) || 0;
                    }
                    return `
                      <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold ${isAnswered ? 'bg-blue-600 text-white' : (isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600')}">
                          ${letter}
                        </span>
                        <span class="${isAnswered ? 'text-blue-600 font-semibold' : (isCorrect ? 'text-emerald-600 font-semibold' : '')}">${opt}</span>
                        ${isAnsweredCorrect ? '<span class=\"material-symbols-rounded text-emerald-600 text-base\">check_circle</span>' : ''}
                      </div>
                    `;
                  }).join('')}
                </div>`
              : '';
            const essayHtml = (tipe === 'essay')
              ? `<div class="mt-3 space-y-2">
                  <div class="text-sm text-slate-700 rounded-lg border bg-slate-50 px-3 py-2">${row.jawaban_text || '<span class=\"text-slate-400\">Belum dijawab.</span>'}</div>
                  <input type="number" min="0" class="essay-score w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Input nilai essay" value="${row.essay_score ?? ''}">
                </div>`
              : '';
            item.innerHTML = `
              <div class="absolute top-3 right-3 flex items-center gap-2 text-xs">
                <span class="font-semibold text-emerald-600">+${bobot}</span>
                <span class="px-2 py-0.5 rounded-md font-semibold uppercase ${badgeClass}">${badgeText}</span>
              </div>
              <div class="text-sm font-semibold text-slate-800">Soal ${idx + 1}</div>
              <div class="text-sm text-slate-600 mt-1">${row.soal || '-'}</div>
              ${optionsHtml}
              ${essayHtml}
            `;
            previewJawabanList.appendChild(item);
          });
        }
      }
      if (poinPgEl) poinPgEl.textContent = String(poinPg);
      currentPoinPg = poinPg;
      if (totalPgEl) totalPgEl.textContent = String(totalPg);
      if (totalEssayEl) totalEssayEl.textContent = String(totalEssay);

      const saveNilaiAkhir = (nilaiAkhir, showToast = false) => {
        if (!activeMahasiswaId || !activeUjianId) return;
        let nilaiKecepatan = null;
        const btnKecepatan = document.querySelector(
          `.btn-nilai-kecepatan[data-mhs-id="${activeMahasiswaId}"][data-ujian-id="${activeUjianId}"]`
        );
        if (btnKecepatan) {
          nilaiKecepatan = btnKecepatan.dataset.nilai || null;
        }
        fetch('{{ route('dosen.ujian.nilai.save') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            ujian_id: activeUjianId,
            mahasiswa_id: activeMahasiswaId,
            nilai: Math.round(nilaiAkhir),
            nilai_kecepatan: nilaiKecepatan,
          }),
        })
          .then((res) => res.json())
          .then((data) => {
            const nilai = Number(data?.nilai ?? nilaiAkhir);
            const nilaiBox = document.getElementById(`nilaiBox-${activeMahasiswaId}`);
            if (nilaiBox) {
              nilaiBox.textContent = isNaN(nilai) ? '?' : String(nilai);
              nilaiBox.classList.remove('text-slate-400', 'text-red-600', 'text-amber-600', 'text-blue-600', 'text-emerald-600');
              if (isNaN(nilai)) {
                nilaiBox.classList.add('text-slate-400');
              } else if (nilai < 60) {
                nilaiBox.classList.add('text-red-600');
              } else if (nilai < 70) {
                nilaiBox.classList.add('text-amber-600');
              } else if (nilai < 90) {
                nilaiBox.classList.add('text-blue-600');
              } else {
                nilaiBox.classList.add('text-emerald-600');
              }
            }
            if (showToast && nilaiSuccessModal) {
              nilaiSuccessModal.classList.remove('hidden');
              nilaiSuccessModal.classList.add('flex');
              setTimeout(() => {
                nilaiSuccessModal.classList.add('hidden');
                nilaiSuccessModal.classList.remove('flex');
              }, 1600);
            }
          })
          .catch(() => {});
      };

      updateEssayTotal = () => {
        if (!poinEssayEl) return;
        const inputs = previewJawabanList?.querySelectorAll('.essay-score') || [];
        let total = 0;
        inputs.forEach((input) => {
          const val = Number(input.value || 0);
          if (!Number.isNaN(val)) total += val;
        });
        poinEssayEl.textContent = String(total);
        const totalNilai = (Number(poinPg) || 0) + (Number(total) || 0);
        if (totalPoinEl) totalPoinEl.textContent = String(totalNilai);
        const nilaiAkhir = totalBobot > 0 ? ((totalNilai / totalBobot) * 100) : 0;
        if (nilaiAkhirEl) nilaiAkhirEl.textContent = String(Math.round(nilaiAkhir));
        if (saveTimer) clearTimeout(saveTimer);
        saveTimer = setTimeout(() => {
          saveNilaiAkhir(nilaiAkhir);
        }, 500);
      };

      const essayInputs = previewJawabanList?.querySelectorAll('.essay-score') || [];
      if (essayInputs.length === 0) {
        if (poinEssayEl) poinEssayEl.textContent = '0';
        if (totalEssayEl) totalEssayEl.textContent = String(totalEssay);
        const totalNilai = Number(poinPg) || 0;
        if (totalPoinEl) totalPoinEl.textContent = String(totalNilai);
        const nilaiAkhir = totalBobot > 0 ? ((totalNilai / totalBobot) * 100) : 0;
        if (nilaiAkhirEl) nilaiAkhirEl.textContent = String(Math.round(nilaiAkhir));
        saveNilaiAkhir(nilaiAkhir);
      } else {
        essayInputs.forEach((input, index) => {
          input.addEventListener('input', updateEssayTotal);
          input.addEventListener('input', () => {
            if (!activeMahasiswaId || !activeUjianId) return;
            const jawabanRow = jawaban[index];
            const soalId = jawabanRow?.soal_id || jawabanRow?.id;
            if (!soalId) return;
            fetch('{{ route('dosen.ujian.essay.save') }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
              },
              body: JSON.stringify({
                ujian_id: activeUjianId,
                mahasiswa_id: activeMahasiswaId,
                soal_id: soalId,
                essay_score: input.value || 0,
              }),
            }).catch(() => {});
          });
        });
        updateEssayTotal();
      }

      previewJawabanModal?.classList.remove('hidden');
      previewJawabanModal?.classList.add('flex');
    });
  });

  btnSaveNilaiUjian?.addEventListener('click', () => {
    updateEssayTotal();
    const nilaiAkhir = Number(nilaiAkhirEl?.textContent || 0);
    saveNilaiAkhir(nilaiAkhir, true);
  });

  btnClosePreviewJawaban?.addEventListener('click', closePreviewJawaban);
  previewJawabanModal?.addEventListener('click', (e) => {
    if (e.target === previewJawabanModal) closePreviewJawaban();
  });

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
</script>

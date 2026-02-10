<x-header>Dashboard</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>




            <!-- Main Content Grid -->
            <div class="content-grid">
                <div class="left-column">
                    <!-- Activity Chart -->
                    <div class="chart-container bg-white rounded-xl shadow p-4">
                        <div class="section-header">
                            <h3 class="section-title">Ringkasan Aktivitas</h3>
                            <select style="padding: 5px; border-radius: 5px; border: 1px solid #ddd;">
                                <option>7 Hari Terakhir</option>
                                <option>30 Hari Terakhir</option>
                                <option>90 Hari Terakhir</option>
                            </select>
                        </div>
                        <div class="relative w-full h-56 mt-3">
                            <canvas id="adminActivityChart"></canvas>
                        </div>
                    </div>

                    <div class="mt-4">
                      <!-- Recent Actions -->
                      <div class="recent-actions bg-white rounded-xl shadow p-4 ">
                          <div class="section-header">
                              <h3 class="section-title">Aktivitas Terbaru</h3>
                          </div>
                          <div class="actions-list max-h-[300px] overflow-auto pr-1">
                              @forelse (($recentActions ?? []) as $action)
                                <div class="action-item">
                                    <div class="action-icon">
                                        <i><span class="material-symbols-rounded">{{ $action['icon'] }}</span></i>
                                    </div>
                                    <div class="action-details">
                                        <div class="action-title">{{ $action['title'] }} {{ $action['detail'] }}</div>
                                        <div class="action-time">
                                          {{ \Carbon\Carbon::parse($action['time'])->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                              @empty
                                <div class="text-sm text-slate-500">Belum ada aktivitas terbaru.</div>
                              @endforelse
                          </div>
                      </div>
                    </div>
                </div>

                <div class="right-column">
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- TOTAL USERS -->
                    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
                      <div>
                        <div class="text-2xl font-bold text-slate-800">{{ $totalUsers ?? 0 }}</div>
                        <div class="text-sm text-slate-500">Total Pengguna</div>
                      </div>
                      <div class="p-3 rounded-lg bg-blue-100 text-blue-600">
                        <span class="material-symbols-rounded text-3xl">group</span>
                      </div>
                    </div>
                  
                    <!-- ACTIVE USERS -->
                    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
                      <div>
                        <div class="text-2xl font-bold text-slate-800">{{ $activeUsers ?? 0 }}</div>
                        <div class="text-sm text-slate-500">Pengguna Aktif</div>
                      </div>
                      <div class="p-3 rounded-lg bg-green-100 text-green-600">
                        <span class="material-symbols-rounded text-3xl">verified_user</span>
                      </div>
                    </div>
                  
                    
                  
                    <!-- DOSEN -->
                    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
                      <div>
                        <div class="text-2xl font-bold text-slate-800">{{ $totalDosen ?? 0 }}</div>
                        <div class="text-sm text-slate-500">Total Dosen</div>
                      </div>
                      <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600">
                        <span class="material-symbols-rounded text-3xl">school</span>
                      </div>
                    </div>
                  
                    <!-- DOSEN AKTIF -->
                    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
                      <div>
                        <div class="text-2xl font-bold text-slate-800">{{ $activeDosen ?? 0 }}</div>
                        <div class="text-sm text-slate-500">Dosen Aktif</div>
                      </div>
                      <div class="p-3 rounded-lg bg-green-100 text-green-600">
                        <span class="material-symbols-rounded text-3xl">co_present</span>
                      </div>
                    </div>
                  
                    <!-- MAHASISWA -->
                    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
                      <div>
                        <div class="text-2xl font-bold text-slate-800">{{ $totalMahasiswa ?? 0 }}</div>
                        <div class="text-sm text-slate-500">Total Mahasiswa</div>
                      </div>
                      <div class="p-3 rounded-lg bg-cyan-100 text-cyan-600">
                        <span class="material-symbols-rounded text-3xl">groups</span>
                      </div>
                    </div>
                  
                    <!-- KELAS -->
                    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
                      <div>
                        <div class="text-2xl font-bold text-slate-800">{{ $activeKelas ?? 0 }}</div>
                        <div class="text-sm text-slate-500">Kelas Aktif</div>
                      </div>
                      <div class="p-3 rounded-lg bg-purple-100 text-purple-600">
                        <span class="material-symbols-rounded text-3xl">calendar_month</span>
                      </div>
                    </div>
                  </div>

                  <!-- Kalender Akademik -->
                  <div class="bg-white rounded-xl shadow p-4 mt-4 min-h-[340px]">
                    <div class="section-header">
                      <div>
                        <h3 class="section-title">Kalender Akademik</h3>
                        <span class="text-xs text-slate-500">2025/2026</span>
                      </div>
                      <button id="btnKelolaKalender" class="text-sm text-blue-600 hover:text-blue-700">
                        Kelola
                      </button>
                    </div>
                    <div class="space-y-3 text-sm text-slate-700 max-h-[300px] overflow-auto pr-1">
                      @forelse (($kalenderAkademik ?? []) as $item)
                        <div class="flex items-start justify-between gap-3">
                          <div>
                            <div class="font-semibold text-slate-800">{{ $item->judul }}</div>
                            @if(!empty($item->keterangan))
                              <div class="text-xs text-slate-500">{{ $item->keterangan }}</div>
                            @endif
                          </div>
                          <div class="text-right text-slate-600 text-xs">
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                            @if(!empty($item->tanggal_selesai))
                              - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                            @endif
                          </div>
                        </div>
                      @empty
                        <div class="text-sm text-slate-500">Belum ada jadwal akademik.</div>
                      @endforelse
                    </div>
                  </div>
                  <div class="mt-3">
                    <button id="btnAktifkanKrs" class="w-full px-4 py-3 rounded-lg bg-white text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-50 flex items-center justify-center gap-2">
                      <span id="krsStatusDot" class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
                      Aktifkan KRS
                    </button>
                  </div>
                </div>
            </div>
        </div>
    




  
<!-- Modal Kelola Kalender Akademik -->
<div id="modalKalenderBackdrop" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl mx-4">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
      <h3 class="text-lg font-semibold text-slate-800">Kelola Kalender Akademik</h3>
      <button id="btnCloseKalenderModal" class="text-slate-500 hover:text-slate-700">✕</button>
    </div>

    <div class="p-5 grid grid-cols-1 lg:grid-cols-[1fr_1fr] gap-6">
      <div>
        <h4 class="text-sm font-semibold text-slate-700 mb-3">Tambah / Edit Jadwal</h4>
        <form id="kalenderForm" method="POST" action="{{ route('admin.kalender.store') }}" class="space-y-3">
          @csrf
          <input type="hidden" name="_method" id="kalenderFormMethod" value="POST">
          <input type="hidden" id="kalenderId" value="">

          <div>
            <label class="text-xs text-slate-600">Judul</label>
            <input id="kalenderJudul" name="judul" type="text" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" required>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-slate-600">Tanggal Mulai</label>
              <input id="kalenderMulai" name="tanggal_mulai" type="date" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" required>
            </div>
            <div>
              <label class="text-xs text-slate-600">Tanggal Selesai</label>
              <input id="kalenderSelesai" name="tanggal_selesai" type="date" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>
          </div>
          <div>
            <label class="text-xs text-slate-600">Keterangan</label>
            <textarea id="kalenderKeterangan" name="keterangan" rows="3" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"></textarea>
          </div>
          <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Simpan</button>
            <button type="button" id="btnResetKalender" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm">Batal</button>
          </div>
        </form>
      </div>

      <div>
        <h4 class="text-sm font-semibold text-slate-700 mb-3">Daftar Jadwal</h4>
        <div class="space-y-3 max-h-[320px] overflow-auto pr-1">
          @forelse (($kalenderAkademik ?? []) as $item)
            <div class="border border-slate-200 rounded-lg p-3">
              <div class="flex items-start justify-between gap-3">
                <div>
                  <div class="font-semibold text-slate-800 text-sm">{{ $item->judul }}</div>
                  @if(!empty($item->keterangan))
                    <div class="text-xs text-slate-500">{{ $item->keterangan }}</div>
                  @endif
                  <div class="text-xs text-slate-500 mt-1">
                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                    @if(!empty($item->tanggal_selesai))
                      - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                    @endif
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    class="text-blue-600 text-xs btn-edit-kalender"
                    data-id="{{ $item->id }}"
                    data-judul="{{ $item->judul }}"
                    data-mulai="{{ $item->tanggal_mulai }}"
                    data-selesai="{{ $item->tanggal_selesai }}"
                    data-keterangan="{{ $item->keterangan }}"
                  >Edit</button>
                  <form method="POST" action="{{ route('admin.kalender.destroy', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 text-xs">Hapus</button>
                  </form>
                </div>
              </div>
            </div>
          @empty
            <div class="text-sm text-slate-500">Belum ada jadwal.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Pengaktifan KRS -->
<div id="modalKrsBackdrop" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
      <h3 class="text-lg font-semibold text-slate-800">Pengaktifan KRS</h3>
      <button id="btnCloseKrsModal" class="text-slate-500 hover:text-slate-700">✕</button>
    </div>
    <div class="p-5 space-y-4">
      <form id="krsForm" method="POST" action="{{ route('admin.krs.upsert') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="status" id="krsStatus" value="aktif">
        <input type="hidden" name="password" id="krsPassword">
        <input type="hidden" name="mulai_tahun_ajar" id="krsMulaiHidden" value="{{ $selectedTahunMulai ?? '' }}">
        <input type="hidden" name="akhir_tahun_ajar" id="krsAkhirHidden" value="{{ $selectedTahunAkhir ?? '' }}">
        <input type="hidden" name="semester" id="krsSemesterHidden" value="{{ $selectedSemester ?? '' }}">
        <div>
          <label class="text-xs text-slate-600">Tahun Ajaran</label>
          <select name="mulai_tahun_ajar" id="krsTahunMulai" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
            @forelse (($tahunMulaiDropdown ?? []) as $tahunMulai)
              <option value="{{ $tahunMulai }}" @selected(($selectedTahunMulai ?? '') == $tahunMulai)>
                {{ $tahunMulai }}/{{ $tahunMulai + 1 }}
              </option>
            @empty
              <option value="2020" @selected(($selectedTahunMulai ?? '') == 2020)>2020/2021</option>
            @endforelse
          </select>
          <input type="hidden" name="akhir_tahun_ajar" id="krsTahunAkhir" value="{{ ($selectedTahunAkhir ?? 0) }}">
        </div>
        <div>
          <label class="text-xs text-slate-600">Semester</label>
          <select name="semester" id="krsSemester" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
            <option value="ganjil" @selected(($selectedSemester ?? '') === 'ganjil')>Ganjil</option>
            <option value="genap" @selected(($selectedSemester ?? '') === 'genap')>Genap</option>
          </select>
        </div>
        <div class="flex items-center gap-2 pt-2">
          <button type="button" id="btnKrsAktifkan" class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold">Aktifkan</button>
          <button type="button" id="btnKrsNonaktifkan" class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold">Nonaktifkan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi KRS -->
<div id="modalKrsConfirm" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-sm mx-4">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
      <h3 class="text-lg font-semibold text-slate-800">Konfirmasi KRS</h3>
      <button type="button" id="btnCloseKrsConfirm" class="text-slate-500 hover:text-slate-700">×</button>
    </div>
    <div class="p-5 space-y-4 text-sm text-slate-700">
      <p>Apakah Anda yakin ingin mengubah status KRS?</p>
      <div class="flex items-center gap-2">
        <button type="button" id="btnKrsConfirmYes" class="flex-1 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold">Ya</button>
        <button type="button" id="btnKrsConfirmCancel" class="flex-1 px-4 py-2 rounded-lg bg-slate-100 text-slate-700 text-sm font-semibold">Batal</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Password KRS -->
<div id="modalKrsPassword" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-sm mx-4">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
      <h3 class="text-lg font-semibold text-slate-800">Masukkan Password</h3>
      <button type="button" id="btnCloseKrsPassword" class="text-slate-500 hover:text-slate-700">×</button>
    </div>
    <div class="p-5 space-y-4">
      <div>
        <label class="text-xs text-slate-600">Password Admin</label>
        <input id="krsPasswordInput" type="password" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Masukkan password">
        <p id="krsPasswordError" class="text-xs text-red-600 mt-1 hidden">Password wajib diisi.</p>
      </div>
      <div class="flex items-center gap-2">
        <button type="button" id="btnKrsPasswordSubmit" class="flex-1 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold">Kirim</button>
        <button type="button" id="btnKrsPasswordCancel" class="flex-1 px-4 py-2 rounded-lg bg-slate-100 text-slate-700 text-sm font-semibold">Batal</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Status KRS -->
@if (session('error') || session('success'))
  <div id="modalKrsStatus" class="fixed inset-0 bg-slate-900/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm mx-4">
      <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
        <h3 class="text-lg font-semibold text-slate-800">
          {{ session('success') ? 'Berhasil' : 'Gagal' }}
        </h3>
        <button type="button" id="btnCloseKrsStatus" class="text-slate-500 hover:text-slate-700">×</button>
      </div>
      <div class="p-5 space-y-4 text-sm text-slate-700">
        <p>{{ session('success') ?? session('error') }}</p>
        <div class="flex justify-end">
          <button type="button" id="btnCloseKrsStatusFooter" class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold">Tutup</button>
        </div>
      </div>
    </div>
  </div>
@endif

<x-footer></x-footer>
 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const activityLabels = @json($chartLabels ?? []);
  const activityUsers = @json($chartUsers ?? []);
  const activityKelas = @json($chartKelas ?? []);

  const activityCtx = document.getElementById('adminActivityChart');
  if (activityCtx) {
    new Chart(activityCtx, {
      type: 'line',
      data: {
        labels: activityLabels,
        datasets: [
          {
            label: 'Pengguna Baru',
            data: activityUsers,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.15)',
            tension: 0.35,
            borderWidth: 2,
            fill: true,
            pointRadius: 3,
          },
          {
            label: 'Kelas Baru',
            data: activityKelas,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.15)',
            tension: 0.35,
            borderWidth: 2,
            fill: true,
            pointRadius: 3,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom' },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
          }
        }
      }
    });
  }
</script>

<script>
  const kalenderBackdrop = document.getElementById('modalKalenderBackdrop');
  const btnOpenKalender = document.getElementById('btnKelolaKalender');
  const btnCloseKalender = document.getElementById('btnCloseKalenderModal');
  const btnResetKalender = document.getElementById('btnResetKalender');
  const kalenderForm = document.getElementById('kalenderForm');
  const kalenderFormMethod = document.getElementById('kalenderFormMethod');
  const kalenderId = document.getElementById('kalenderId');
  const kalenderJudul = document.getElementById('kalenderJudul');
  const kalenderMulai = document.getElementById('kalenderMulai');
  const kalenderSelesai = document.getElementById('kalenderSelesai');
  const kalenderKeterangan = document.getElementById('kalenderKeterangan');

  const openKalenderModal = () => {
    kalenderBackdrop.classList.remove('hidden');
    kalenderBackdrop.classList.add('flex');
  };
  const closeKalenderModal = () => {
    kalenderBackdrop.classList.add('hidden');
    kalenderBackdrop.classList.remove('flex');
  };
  const resetKalenderForm = () => {
    kalenderForm.action = "{{ route('admin.kalender.store') }}";
    kalenderFormMethod.value = "POST";
    kalenderId.value = "";
    kalenderJudul.value = "";
    kalenderMulai.value = "";
    kalenderSelesai.value = "";
    kalenderKeterangan.value = "";
  };

  btnOpenKalender?.addEventListener('click', () => {
    resetKalenderForm();
    openKalenderModal();
  });
  btnCloseKalender?.addEventListener('click', closeKalenderModal);
  kalenderBackdrop?.addEventListener('click', (e) => {
    if (e.target === kalenderBackdrop) closeKalenderModal();
  });
  btnResetKalender?.addEventListener('click', resetKalenderForm);

  document.querySelectorAll('.btn-edit-kalender').forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      kalenderForm.action = "{{ url('/admin/kalender-akademik') }}/" + id;
      kalenderFormMethod.value = "PUT";
      kalenderId.value = id;
      kalenderJudul.value = btn.dataset.judul || '';
      kalenderMulai.value = btn.dataset.mulai || '';
      kalenderSelesai.value = btn.dataset.selesai || '';
      kalenderKeterangan.value = btn.dataset.keterangan || '';
      openKalenderModal();
    });
  });

  const krsBackdrop = document.getElementById('modalKrsBackdrop');
  const btnOpenKrs = document.getElementById('btnAktifkanKrs');
  const btnCloseKrs = document.getElementById('btnCloseKrsModal');

  const openKrsModal = () => {
    krsBackdrop.classList.remove('hidden');
    krsBackdrop.classList.add('flex');
  };
  const closeKrsModal = () => {
    krsBackdrop.classList.add('hidden');
    krsBackdrop.classList.remove('flex');
  };

  btnOpenKrs?.addEventListener('click', openKrsModal);
  btnCloseKrs?.addEventListener('click', closeKrsModal);
  krsBackdrop?.addEventListener('click', (e) => {
    if (e.target === krsBackdrop) closeKrsModal();
  });

  const krsForm = document.getElementById('krsForm');
  const krsStatus = document.getElementById('krsStatus');
  const krsPassword = document.getElementById('krsPassword');
  const krsPasswordInput = document.getElementById('krsPasswordInput');
  const krsPasswordError = document.getElementById('krsPasswordError');
  const krsTahunMulai = document.getElementById('krsTahunMulai');
  const krsTahunAkhir = document.getElementById('krsTahunAkhir');
  const krsSemester = document.getElementById('krsSemester');
  const krsMulaiHidden = document.getElementById('krsMulaiHidden');
  const krsAkhirHidden = document.getElementById('krsAkhirHidden');
  const krsSemesterHidden = document.getElementById('krsSemesterHidden');
  const krsStatusDot = document.getElementById('krsStatusDot');
  const btnKrsAktifkan = document.getElementById('btnKrsAktifkan');
  const btnKrsNonaktifkan = document.getElementById('btnKrsNonaktifkan');
  const krsStatusMap = @json($krsSettings ?? []);
  const krsConfirmModal = document.getElementById('modalKrsConfirm');
  const krsPasswordModal = document.getElementById('modalKrsPassword');
  const btnKrsConfirmYes = document.getElementById('btnKrsConfirmYes');
  const btnKrsConfirmCancel = document.getElementById('btnKrsConfirmCancel');
  const btnCloseKrsConfirm = document.getElementById('btnCloseKrsConfirm');
  const btnKrsPasswordSubmit = document.getElementById('btnKrsPasswordSubmit');
  const btnKrsPasswordCancel = document.getElementById('btnKrsPasswordCancel');
  const btnCloseKrsPassword = document.getElementById('btnCloseKrsPassword');
  let pendingKrsStatus = '';

  const submitKrs = (statusValue) => {
    if (!krsForm || !krsStatus || !krsTahunMulai || !krsTahunAkhir) return;
    krsStatus.value = statusValue;
    krsForm.submit();
  };

  const openConfirmModal = (statusValue) => {
    pendingKrsStatus = statusValue;
    if (!krsConfirmModal) return;
    krsConfirmModal.classList.remove('hidden');
    krsConfirmModal.classList.add('flex');
  };

  const closeConfirmModal = () => {
    if (!krsConfirmModal) return;
    krsConfirmModal.classList.add('hidden');
    krsConfirmModal.classList.remove('flex');
  };

  const openPasswordModal = () => {
    if (!krsPasswordModal) return;
    if (krsPasswordInput) krsPasswordInput.value = '';
    if (krsPasswordError) krsPasswordError.classList.add('hidden');
    krsPasswordModal.classList.remove('hidden');
    krsPasswordModal.classList.add('flex');
  };

  const closePasswordModal = () => {
    if (!krsPasswordModal) return;
    krsPasswordModal.classList.add('hidden');
    krsPasswordModal.classList.remove('flex');
  };

  btnKrsAktifkan?.addEventListener('click', () => openConfirmModal('aktif'));
  btnKrsNonaktifkan?.addEventListener('click', () => openConfirmModal('nonaktif'));

  btnKrsConfirmYes?.addEventListener('click', () => {
    closeConfirmModal();
    openPasswordModal();
  });
  btnKrsConfirmCancel?.addEventListener('click', closeConfirmModal);
  btnCloseKrsConfirm?.addEventListener('click', closeConfirmModal);
  krsConfirmModal?.addEventListener('click', (e) => {
    if (e.target === krsConfirmModal) closeConfirmModal();
  });

  btnKrsPasswordSubmit?.addEventListener('click', () => {
    const value = (krsPasswordInput?.value || '').trim();
    if (!value) {
      if (krsPasswordError) krsPasswordError.classList.remove('hidden');
      return;
    }
    if (krsPassword) krsPassword.value = value;
    closePasswordModal();
    submitKrs(pendingKrsStatus);
  });
  btnKrsPasswordCancel?.addEventListener('click', closePasswordModal);
  btnCloseKrsPassword?.addEventListener('click', closePasswordModal);
  krsPasswordModal?.addEventListener('click', (e) => {
    if (e.target === krsPasswordModal) closePasswordModal();
  });

  const setKrsButtonState = (statusValue) => {
    if (!btnKrsAktifkan || !btnKrsNonaktifkan) return;
    if (statusValue === 'aktif') {
      btnKrsAktifkan.className = 'flex-1 px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 text-sm font-semibold';
      btnKrsNonaktifkan.className = 'flex-1 px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold';
      btnKrsAktifkan.textContent = 'Aktif';
      btnKrsNonaktifkan.textContent = 'Nonaktifkan';
      btnKrsAktifkan.disabled = true;
      btnKrsNonaktifkan.disabled = false;
      btnKrsAktifkan.classList.add('opacity-60', 'cursor-not-allowed');
      btnKrsNonaktifkan.classList.remove('opacity-60', 'cursor-not-allowed');
      if (krsStatusDot) krsStatusDot.className = 'inline-block w-2.5 h-2.5 rounded-full bg-emerald-500';
    } else {
      btnKrsAktifkan.className = 'flex-1 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold';
      btnKrsNonaktifkan.className = 'flex-1 px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 text-sm font-semibold';
      btnKrsAktifkan.textContent = 'Aktifkan';
      btnKrsNonaktifkan.textContent = 'Nonaktif';
      btnKrsAktifkan.disabled = false;
      btnKrsNonaktifkan.disabled = true;
      btnKrsAktifkan.classList.remove('opacity-60', 'cursor-not-allowed');
      btnKrsNonaktifkan.classList.add('opacity-60', 'cursor-not-allowed');
      if (krsStatusDot) krsStatusDot.className = 'inline-block w-2.5 h-2.5 rounded-full bg-red-500';
    }
  };

  const refreshKrsState = () => {
    if (!krsTahunMulai || !krsTahunAkhir || !krsSemester) return;
    const targetAkhir = parseInt(krsTahunMulai.value || '0', 10) + 1;
    if (targetAkhir) krsTahunAkhir.value = String(targetAkhir);
    if (krsMulaiHidden) krsMulaiHidden.value = krsTahunMulai.value;
    if (krsAkhirHidden) krsAkhirHidden.value = krsTahunAkhir.value;
    if (krsSemesterHidden) krsSemesterHidden.value = krsSemester.value;
    const key = `${krsTahunMulai.value}-${krsTahunAkhir.value}-${krsSemester.value}`;
    const statusValue = krsStatusMap[key] || 'nonaktif';
    setKrsButtonState(statusValue);

    if (statusValue === 'aktif') {
      krsTahunMulai.disabled = true;
      krsSemester.disabled = true;
    } else {
      krsTahunMulai.disabled = false;
      krsSemester.disabled = false;
    }
  };

  krsTahunMulai?.addEventListener('change', refreshKrsState);
  krsSemester?.addEventListener('change', refreshKrsState);
  refreshKrsState();

  const krsStatusModal = document.getElementById('modalKrsStatus');
  const btnCloseKrsStatus = document.getElementById('btnCloseKrsStatus');
  const btnCloseKrsStatusFooter = document.getElementById('btnCloseKrsStatusFooter');
  const closeKrsStatusModal = () => {
    if (!krsStatusModal) return;
    krsStatusModal.classList.add('hidden');
    krsStatusModal.classList.remove('flex');
  };
  btnCloseKrsStatus?.addEventListener('click', closeKrsStatusModal);
  btnCloseKrsStatusFooter?.addEventListener('click', closeKrsStatusModal);
  krsStatusModal?.addEventListener('click', (e) => {
    if (e.target === krsStatusModal) closeKrsStatusModal();
  });
</script>

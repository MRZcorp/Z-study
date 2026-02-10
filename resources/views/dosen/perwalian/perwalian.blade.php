<x-header>Perwalian</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

<div class="max-w-7xl mx-auto space-y-6 p-6">
  <div>
    <h1 class="text-2xl font-bold text-slate-800">Mahasiswa Walian Saya</h1>
    <p class="text-sm text-slate-500">Daftar mahasiswa bimbingan perwalian beserta status KRS</p>
  </div>

  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-100 text-slate-600">
        <tr>
          <th class="px-4 py-3 text-left">No</th>
          <th class="px-4 py-3 text-left">Nama</th>
          <th class="px-4 py-3 text-left">NIM</th>
          <th class="px-4 py-3 text-center">Semester</th>
          <th class="px-4 py-3 text-center">IPS</th>
          <th class="px-4 py-3 text-center">IPK</th>
          <th class="px-4 py-3 text-center">Status KRS</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse(($perwalians ?? []) as $perwalian)
          @php
            $hasPending = $perwalian->kelas->contains(fn($k) => $k->pivot?->status === 'menunggu');
            $hasApproved = $perwalian->kelas->contains(fn($k) => $k->pivot?->status === 'disetujui');
            $hasRejected = $perwalian->kelas->contains(fn($k) => $k->pivot?->status === 'ditolak');
            $statusKrs = $hasPending ? 'menunggu' : ($hasApproved ? 'disetujui' : ($hasRejected ? 'ditolak' : '-'));
            $statusClass = $statusKrs === 'disetujui'
              ? 'bg-green-100 text-green-700'
              : ($statusKrs === 'menunggu' ? 'bg-yellow-100 text-yellow-700' : ($statusKrs === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600'));
          @endphp
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 font-medium">{{ $perwalian->user?->name ?? '-' }}</td>
            <td class="px-4 py-3">{{ $perwalian->nim ?? '-' }}</td>
            <td class="px-4 py-3 text-center">{{ $perwalian->semester_aktif ?? '-' }}</td>
            <td class="px-4 py-3 text-center">{{ number_format((float) ($perwalian->ips_terakhir ?? 0), 2) }}</td>
            <td class="px-4 py-3 text-center">{{ number_format((float) ($perwalian->ipk ?? 0), 2) }}</td>
            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                {{ $statusKrs }}
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex justify-center">
                <button
                  type="button"
                  class="btn-perwalian-detail p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                  data-modal-target="perwalianModal-{{ $perwalian->id }}"
                  title="Detail"
                >
                  <span class="material-symbols-rounded">edit</span>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="px-4 py-6 text-center text-slate-500" colspan="8">Belum ada data perwalian.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@foreach(($perwalians ?? []) as $perwalian)
  @php
    $kelasList = $perwalian->kelas ?? collect();
    $sksDiambil = $kelasList
      ->filter(fn($k) => $k->pivot?->status === 'disetujui')
      ->sum(fn($k) => (int) ($k->mataKuliah?->sks ?? 0));
    $sksDiambilSemester = $kelasList
      ->filter(fn($k) => $k->pivot?->status === 'disetujui')
      ->filter(fn($k) => !$semesterAktif || strtolower((string) ($k->semester ?? '')) === strtolower((string) $semesterAktif))
      ->sum(fn($k) => (int) ($k->mataKuliah?->sks ?? 0));
    $semesterOrder = ['ganjil' => 1, 'genap' => 2];
    $semesterItems = $kelasList
      ->filter(fn($k) => in_array($k->status ?? '', ['aktif', 'selesai'], true))
      ->sort(function ($a, $b) use ($semesterOrder) {
        $yearA = (int) preg_replace('/[^0-9]/', '', (string) ($a->tahun_ajar ?? '0'));
        $yearB = (int) preg_replace('/[^0-9]/', '', (string) ($b->tahun_ajar ?? '0'));
        if ($yearA !== $yearB) {
          return $yearA <=> $yearB;
        }
        $semA = $semesterOrder[strtolower((string) ($a->semester ?? ''))] ?? 99;
        $semB = $semesterOrder[strtolower((string) ($b->semester ?? ''))] ?? 99;
        return $semA <=> $semB;
      })
      ->map(fn($k) => (($k->tahun_ajar ?? '-') . '|' . ($k->semester ?? '-')))
      ->unique()
      ->values();
    $semesterMap = $semesterItems->mapWithKeys(fn($key, $idx) => [$key => $idx + 1]);
  @endphp
  <div id="perwalianModal-{{ $perwalian->id }}" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-3xl p-6 max-h-[80vh] overflow-y-auto">

      <div
        class="relative mb-4 rounded-xl shadow overflow-hidden"
        style="background-image: {{ $perwalian->bg ? "url('".asset('storage/' . $perwalian->bg)."')" : 'none' }}; background-color: #ffffff; background-size: cover; background-position: center;"
      >
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative p-4 text-white">
          <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
              <img
                src="{{ $perwalian->poto_profil ? asset('storage/' . $perwalian->poto_profil) : asset('img/default_profil.jpg') }}"
                class="w-24 h-24 rounded-full object-cover border-2 border-white"
                alt="Foto mahasiswa"
              >
              <div>
                <h2 class="text-lg font-semibold">{{ $perwalian->user?->name ?? '-' }}</h2>
                <p class="text-xs text-white/80">NIM: {{ $perwalian->nim ?? '-' }}</p>
                <p class="text-sm text-white">Semester: {{ $perwalian->semester_aktif ?? '-' }}</p>
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs text-gray-800">
              <div class="rounded-lg px-2 py-1.5 text-center bg-black/40 backdrop-blur-sm border border-white/20">
                <p class="text-white/70 text-xs">IPS</p>
                <p class="font-semibold text-white text-sm">{{ number_format((float) ($perwalian->ips_terakhir ?? 0), 2) }}</p>
              </div>
              <div class="rounded-lg px-2 py-1.5 text-center bg-black/40 backdrop-blur-sm border border-white/20">
                <p class="text-white/70 text-xs">IPK</p>
                <p class="font-semibold text-white text-sm">{{ number_format((float) ($perwalian->ipk ?? 0), 2) }}</p>
              </div>
              <div class="rounded-lg px-2 py-1.5 text-center bg-black/40 backdrop-blur-sm border border-white/20">
                <p class="text-white/70 text-xs">Max SKS</p>
                <p class="font-semibold text-white text-sm">{{ $sksDiambilSemester }} / {{ $perwalian->maks_sks ?? '-' }}</p>
              </div>
              <div class="rounded-lg px-2 py-1.5 text-center bg-black/40 backdrop-blur-sm border border-white/20 sm:col-span-3">
                <p class="text-white/70 text-xs">KRS Ditempuh</p>
                <p class="font-semibold text-white text-sm">{{ $sksDiambil }} / 144</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      @if ($kelasList->isNotEmpty())
        <div class="flex items-center justify-end mb-3">
          <form method="POST" action="{{ route('dosen.perwalian.kelas.approve_all', ['mahasiswa' => $perwalian->id]) }}">
            @csrf
            <button type="submit" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
              Setujui Semua
            </button>
          </form>
        </div>
      @endif

      <div class="flex items-center justify-between mb-3">
        <div class="text-sm text-slate-700 font-semibold">Filter Semester</div>
        <select
          class="perwalian-semester-filter h-9 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-700"
        >
          <option value="">Semua Semester</option>
          @foreach ($semesterItems as $key)
            <option value="{{ $semesterMap[$key] ?? '' }}">Semester {{ $semesterMap[$key] ?? '-' }}</option>
          @endforeach
        </select>
      </div>

      <div class="bg-slate-50 rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-100 text-slate-600">
            <tr>
              <th class="px-4 py-3 text-left">Kode</th>
              <th class="px-4 py-3 text-left">Mata Kuliah</th>
              <th class="px-4 py-3 text-center">SKS</th>
              <th class="px-4 py-3 text-left">Dosen Pengampu</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @forelse($kelasList as $kelas)
              @php
                $pivotStatus = $kelas->pivot?->status ?? '-';
                $isPending = $pivotStatus === 'menunggu';
                $canReset = $pivotStatus !== '-';
                $isApproved = $pivotStatus === 'disetujui';
                $isRejected = $pivotStatus === 'ditolak';
              @endphp
              @php
                $semesterKey = ($kelas->tahun_ajar ?? '-') . '|' . ($kelas->semester ?? '-');
                $semesterNum = $semesterMap[$semesterKey] ?? null;
              @endphp
              <tr class="hover:bg-white perwalian-row" data-semester="{{ $semesterNum ?? '' }}">
                <td class="px-4 py-3">{{ $kelas->mataKuliah?->kode_mata_kuliah ?? '-' }}</td>
                <td class="px-4 py-3">{{ $kelas->mataKuliah?->mata_kuliah ?? '-' }}</td>
                <td class="px-4 py-3 text-center">{{ $kelas->mataKuliah?->sks ?? '-' }}</td>
                <td class="px-4 py-3">{{ $kelas->dosens?->user?->name ?? '-' }}</td>
                <td class="px-4 py-3">
                  <div class="flex justify-center gap-2">
                    <form method="POST" action="{{ route('dosen.perwalian.kelas.reset', ['mahasiswa' => $perwalian->id, 'kelas' => $kelas->id]) }}">
                      @csrf
                      <button type="submit" class="p-2 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200" title="Reset" @disabled(!$canReset)>
                        <span class="material-symbols-rounded text-sm">restart_alt</span>
                      </button>
                    </form>
                    <form method="POST" action="{{ route('dosen.perwalian.kelas.reject', ['mahasiswa' => $perwalian->id, 'kelas' => $kelas->id]) }}">
                      @csrf
                      <button type="submit"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold
                          {{ $isApproved ? 'bg-slate-200 text-slate-500 cursor-not-allowed' : ($isRejected ? 'bg-red-600 text-white' : 'bg-red-600 text-white hover:bg-red-700') }}"
                        @disabled(!$isPending)>
                        Tolak
                      </button>
                    </form>
                    <form method="POST" action="{{ route('dosen.perwalian.kelas.approve', ['mahasiswa' => $perwalian->id, 'kelas' => $kelas->id]) }}">
                      @csrf
                      <button type="submit"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold
                          {{ $isRejected ? 'bg-white text-slate-700 border border-slate-200 cursor-not-allowed' : ($isApproved ? 'bg-blue-600 text-white' : 'bg-blue-600 text-white hover:bg-blue-700') }}"
                        @disabled(!$isPending)>
                        Setujui
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td class="px-4 py-6 text-center text-slate-500" colspan="5">Belum ada kelas yang diikuti.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endforeach

<script>
  document.querySelectorAll('.btn-perwalian-detail').forEach((btn) => {
    btn.addEventListener('click', () => {
      const modalId = btn.dataset.modalTarget;
      const modal = modalId ? document.getElementById(modalId) : null;
      if (!modal) return;
      modal.classList.remove('hidden');
    });
  });

  document.querySelectorAll('.modal-overlay').forEach((modal) => {
    modal.querySelectorAll('.btn-close').forEach((btn) => {
      btn.addEventListener('click', () => modal.classList.add('hidden'));
    });
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.classList.add('hidden');
    });
  });
</script>

<script>
  document.querySelectorAll('.perwalian-semester-filter').forEach((select) => {
    const modal = select.closest('.modal-overlay');
    const rows = Array.from(modal?.querySelectorAll('.perwalian-row') || []);
    const apply = () => {
      const selected = (select.value || '').toLowerCase();
      rows.forEach((row) => {
        const sem = (row.dataset.semester || '').toLowerCase();
        const match = !selected || sem === selected;
        row.style.display = match ? '' : 'none';
      });
    };
    const def = (select.dataset.default || '').toLowerCase();
    if (def) select.value = def;
    apply();
    select.addEventListener('change', apply);
  });
</script>

</body>
</html>

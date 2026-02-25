<x-header>Data Ujian</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

@php
  $now = \Carbon\Carbon::now();
  $ujian_kelas = $ujian_kelas ?? collect();
  $ujian_aktif = $ujian_kelas->filter(function ($ujian) use ($now) {
      $deadline = $ujian->deadline ?? null;
      return $deadline ? \Carbon\Carbon::parse($deadline)->isFuture() : true;
  });
@endphp

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Ujian</h2>
      <p class="text-sm text-slate-500">Kelola ujian aktif dan lihat ujian yang sudah selesai.</p>
    </div>
    <button id="btnOpenUjian" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 px-4 py-2 text-sm font-semibold text-white">
      <span class="material-symbols-rounded text-base">add</span>
      Buat Ujian
    </button>
  </div>

  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
      <span class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">Ujian Aktif</span>
      <a href="{{ url('/dosen/ujian_selesai') }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">Ujian Selesai</a>
    </div>
  </div>

  <div class="space-y-4">
    @if ($ujian_aktif->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada ujian aktif.
      </div>
    @endif

    @foreach ($ujian_aktif as $ujian)
      <div class="bg-white rounded-xl border p-5 flex flex-col gap-4 relative">
        <div class="flex items-start justify-between gap-3">
          <div class="inline-flex items-center gap-2 text-xs font-semibold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full w-fit">
            {{ $ujian->mataKuliah->mata_kuliah ?? '-' }}
          </div>
          <div class="flex items-center gap-2 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700">
              <span class="material-symbols-rounded text-sm">schedule</span>
              {{ $ujian->mulai_ujian ? \Carbon\Carbon::parse($ujian->mulai_ujian)->format('d M Y H:i') : '-' }}
            </span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-red-50 text-red-700">
              <span class="material-symbols-rounded text-sm">event</span>
              {{ $ujian->deadline ? \Carbon\Carbon::parse($ujian->deadline)->format('d M Y H:i') : '-' }}
            </span>
          </div>
        </div>

        <div>
          @php
            $tipeRaw = strtolower(trim((string) ($ujian->deskripsi ?? '')));
            $isUts = str_contains($tipeRaw, 'tengah');
            $isUas = str_contains($tipeRaw, 'akhir');
            $isQuiz = str_contains($tipeRaw, 'quiz');
            $keLabel = $isUts
              ? 'Ujian Tengah Semester'
              : ($isUas ? 'Ujian Akhir Semester' : (($isQuiz ? 'Quiz' : 'Ujian') . ' ke: ' . ($ujian->ujian_ke ?? 1)));
          @endphp
          <p class="text-xs font-semibold uppercase text-slate-500">{{ $ujian->deskripsi ?? '-' }}</p>
          <h3 class="font-semibold text-slate-800 mt-1">{{ $ujian->nama_ujian ?? 'Nama Ujian' }}</h3>
          <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-500">
            <span class="inline-flex items-center gap-2 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
              <span class="material-symbols-rounded text-sm">school</span>
              Kelas {{ $ujian->kelas->nama_kelas ?? '-' }}
              <span class="text-blue-600 font-semibold">{{ $keLabel }}</span>
            </span>
          </div>
        </div>

        @php
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
        @php
          $kelasRef = $ujian->kelas;
          $dosenRef = $kelasRef?->dosens;
          $chatUserMap = collect();
          if ($dosenRef && $dosenRef->user_id) {
              $chatUserMap[(string) $dosenRef->user_id] = [
                  'name' => $dosenRef->user->name ?? '-',
                  'foto' => $dosenRef->poto_profil ? asset('storage/' . $dosenRef->poto_profil) : asset('img/default_profil.jpg'),
                  'phone' => $dosenRef->no_hp ?? '-',
                  'role' => 'dosen',
                  'gelar' => $dosenRef->gelar ?? '',
                  'fakultas' => $dosenRef->fakultas->fakultas ?? '-',
                  'prodi' => $dosenRef->programStudi->nama_prodi ?? '-',
              ];
          }
          foreach (($kelasRef?->mahasiswas ?? collect()) as $mhsRef) {
              $chatUserMap[(string) ($mhsRef->user_id ?? '')] = [
                  'name' => $mhsRef->user->name ?? '-',
                  'foto' => $mhsRef->poto_profil ? asset('storage/' . $mhsRef->poto_profil) : asset('img/default_profil.jpg'),
                  'phone' => $mhsRef->no_hp ?? '-',
                  'role' => 'mahasiswa',
                  'nim' => $mhsRef->nim ?? '-',
                  'fakultas' => $mhsRef->fakultas->fakultas ?? '-',
                  'prodi' => $mhsRef->programStudi->nama_prodi ?? '-',
              ];
          }
        @endphp
        <div class="absolute bottom-4 right-4 flex items-center gap-2">
          <button
            type="button"
            onclick="openChatModal(this)"
            data-kelas-id="{{ $ujian->id }}"
            data-kelas-nama="{{ $ujian->nama_ujian ?? 'Ujian' }}"
            data-user-map='@json($chatUserMap)'
            class="rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
          >
            <span class="material-symbols-rounded text-base">chat</span>
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
          <a
            href="{{ route('dosen.ujian.soal', $ujian->id) }}"
            class="rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
          >
            <span class="material-symbols-rounded text-base">edit</span>
          </a>
          <div class="flex flex-col items-end gap-2">
            <button
              type="button"
              class="btn-edit-ujian rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
              data-id="{{ $ujian->id }}"
              data-nama="{{ $ujian->nama_ujian ?? '' }}"
              data-deskripsi="{{ $ujian->deskripsi ?? '' }}"
              data-mulai="{{ $ujian->mulai_ujian ? \Carbon\Carbon::parse($ujian->mulai_ujian)->format('Y-m-d\\TH:i') : '' }}"
              data-deadline="{{ $ujian->deadline ? \Carbon\Carbon::parse($ujian->deadline)->format('Y-m-d\\TH:i') : '' }}"
              data-kelas-id="{{ $ujian->nama_kelas_id ?? '' }}"
              data-matkul="{{ $ujian->mataKuliah->mata_kuliah ?? '' }}"
              data-matkul-id="{{ $ujian->mataKuliah->id ?? '' }}"
            >
              <span class="material-symbols-rounded text-base">settings</span>
            </button>
            <form action="{{ route('dosen.ujian.destroy', $ujian->id) }}" method="POST" class="form-delete-ujian" data-ujian="{{ $ujian->nama_ujian ?? 'Ujian' }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="rounded-full bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-700 hover:bg-red-200">
                <span class="material-symbols-rounded text-base">delete</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- MODAL BUAT UJIAN -->
<div id="ujianModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[80vh] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Buat Ujian</h3>
      <button id="btnCloseUjian" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <form action="{{ route('dosen.ujian.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 overflow-y-auto max-h-[calc(80vh-64px)]">
      @csrf
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
        <select id="kelasSelect" name="nama_kelas_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
          <option value="">Pilih Kelas</option>
          @foreach(($kelas_dosen ?? []) as $kelas)
            @if (($kelas->status ?? '') === 'selesai')
              @continue
            @endif
            @php $lastUjianKe = $ujianCountByKelas[$kelas->id] ?? 0; @endphp
            <option value="{{ $kelas->id }}" data-matkul="{{ $kelas->mataKuliah->mata_kuliah ?? '' }}" data-matkul-id="{{ $kelas->mataKuliah->id ?? '' }}" data-ujian-next="{{ $lastUjianKe + 1 }}">
              {{ $kelas->mataKuliah->mata_kuliah ?? '-' }} - Kelas {{ $kelas->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Mata Kuliah</label>
        <input type="text" id="matkulText" class="w-full rounded-lg border border-slate-300 px-4 py-2 bg-slate-50" readonly>
        <input type="hidden" id="matkulId" name="mata_kuliah_id">
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Tipe</label>
        <select id="tipeSelect" name="deskripsi" class="w-full rounded-lg border border-slate-300 px-4 py-2">
          <option value="">Pilih tipe</option>
          <option value="Quiz">Quiz</option>
          <option value="Ujian">Ujian</option>
          <option value="Ujian Tengah Semester">Ujian Tengah Semester</option>
          <option value="Ujian Akhir Semester">Ujian Akhir Semester</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Ujian</label>
        <input id="namaUjianInput" type="text" name="nama_ujian" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        <p id="ujianKeInfo" class="mt-1 text-xs text-blue-600 font-semibold">Ujian ke: -</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Mulai Ujian</label>
          <input type="datetime-local" name="mulai_ujian" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Deadline</label>
          <input type="datetime-local" name="deadline" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        </div>
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" id="btnCancelUjian" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:opacity-90">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL KONFIRMASI HAPUS UJIAN -->
<div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl p-6">
    <h4 class="text-lg font-semibold text-slate-800">Konfirmasi Hapus</h4>
    <p class="mt-3 text-sm text-slate-600">
      Data Ujian "<span id="deleteUjianName" class="font-semibold text-slate-800">-</span>" akan terhapus secara permanen.
      Apakah anda yakin ingin menghapus ujian ini?
    </p>
    <div class="mt-6 flex items-center justify-end gap-2">
      <button id="btnCancelDelete" type="button" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Tidak</button>
      <button id="btnConfirmDelete" type="button" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Ya</button>
    </div>
  </div>
</div>

<!-- MODAL SUKSES -->
<div id="actionSuccessModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center">
    <p id="actionSuccessText" class="text-base font-semibold text-slate-800">Aksi berhasil</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-emerald-600">check_circle</span>
    </div>
  </div>
</div>

<!-- MODAL ERROR -->
<div id="actionErrorModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center">
    <p id="actionErrorText" class="text-base font-semibold text-slate-800">Terjadi kesalahan</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-red-600">error</span>
    </div>
  </div>
</div>

<!-- MODAL PREVIEW UJIAN -->
<div id="previewUjianModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div>
        <h3 id="previewMatkul" class="text-lg font-semibold text-slate-800">Mata Kuliah</h3>
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

<!-- MODAL EDIT UJIAN -->
<div id="editUjianModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[80vh] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Edit Ujian</h3>
      <button id="btnCloseEditUjian" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <form id="editUjianForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 overflow-y-auto max-h-[calc(80vh-64px)]">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
        <select id="editKelasSelect" name="nama_kelas_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
          <option value="">Pilih Kelas</option>
          @foreach(($kelas_dosen ?? []) as $kelas)
            @if (($kelas->status ?? '') === 'selesai')
              @continue
            @endif
            <option value="{{ $kelas->id }}" data-matkul-id="{{ $kelas->mataKuliah->id ?? '' }}" data-matkul="{{ $kelas->mataKuliah->mata_kuliah ?? '' }}">
              {{ $kelas->mataKuliah->mata_kuliah ?? '-' }} - Kelas {{ $kelas->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Mata Kuliah</label>
        <input type="text" id="editMatkulText" class="w-full rounded-lg border border-slate-300 px-4 py-2 bg-slate-50" readonly>
        <input type="hidden" id="editMatkulId" name="mata_kuliah_id">
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Tipe</label>
        <select name="deskripsi" id="editDeskripsiUjian" class="w-full rounded-lg border border-slate-300 px-4 py-2">
          <option value="">Pilih tipe</option>
          <option value="Quiz">Quiz</option>
          <option value="Ujian">Ujian</option>
          <option value="Ujian Tengah Semester">Ujian Tengah Semester</option>
          <option value="Ujian Akhir Semester">Ujian Akhir Semester</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Ujian</label>
        <input type="text" name="nama_ujian" id="editNamaUjian" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Mulai Ujian</label>
          <input type="datetime-local" name="mulai_ujian" id="editMulaiUjian" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Deadline</label>
          <input type="datetime-local" name="deadline" id="editDeadlineUjian" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        </div>
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" id="btnCancelEditUjian" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:opacity-90">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  window.__chatContextType = 'ujian';
  window.__chatBaseUrlTemplate = @json(route('dosen.ujian.diskusi.index', ['ujian' => '__CTX_ID__']));
  window.__chatMessageUrlTemplate = @json(route('dosen.ujian.diskusi.update', ['ujian' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']));
</script>
@include('dosen.kelas.partials.chat_modal')

<script>
(() => {
  const ujianModal = document.getElementById('ujianModal');
  const btnOpenUjian = document.getElementById('btnOpenUjian');
  const btnCloseUjian = document.getElementById('btnCloseUjian');
  const btnCancelUjian = document.getElementById('btnCancelUjian');
  const kelasSelect = document.getElementById('kelasSelect');
  const matkulText = document.getElementById('matkulText');
  const matkulId = document.getElementById('matkulId');
  const ujianKeInfo = document.getElementById('ujianKeInfo');
  const tipeSelect = document.getElementById('tipeSelect');
  const namaUjianInput = document.getElementById('namaUjianInput');
  const previewModal = document.getElementById('previewUjianModal');
  const btnClosePreview = document.getElementById('btnClosePreview');
  const previewMatkul = document.getElementById('previewMatkul');
  const previewNama = document.getElementById('previewNama');
  const previewDeskripsi = document.getElementById('previewDeskripsi');
  const previewKelas = document.getElementById('previewKelas');
  const previewMulai = document.getElementById('previewMulai');
  const previewDeadline = document.getElementById('previewDeadline');
  const previewSoalList = document.getElementById('previewSoalList');
  const editUjianModal = document.getElementById('editUjianModal');
  const btnCloseEditUjian = document.getElementById('btnCloseEditUjian');
  const btnCancelEditUjian = document.getElementById('btnCancelEditUjian');
  const editUjianForm = document.getElementById('editUjianForm');
  const editKelasSelect = document.getElementById('editKelasSelect');
  const editMatkulText = document.getElementById('editMatkulText');
  const editMatkulId = document.getElementById('editMatkulId');
  const editNamaUjian = document.getElementById('editNamaUjian');
  const editDeskripsiUjian = document.getElementById('editDeskripsiUjian');
  const editMulaiUjian = document.getElementById('editMulaiUjian');
  const editDeadlineUjian = document.getElementById('editDeadlineUjian');
  const deleteConfirmModal = document.getElementById('deleteConfirmModal');
  const deleteUjianName = document.getElementById('deleteUjianName');
  const btnCancelDelete = document.getElementById('btnCancelDelete');
  const btnConfirmDelete = document.getElementById('btnConfirmDelete');
  const actionSuccessModal = document.getElementById('actionSuccessModal');
  const actionSuccessText = document.getElementById('actionSuccessText');
  const actionErrorModal = document.getElementById('actionErrorModal');
  const actionErrorText = document.getElementById('actionErrorText');
  let pendingDeleteForm = null;

  const closeUjianModal = () => {
    ujianModal.classList.add('hidden');
    ujianModal.classList.remove('flex');
  };

  const updateUjianKeLabel = (tipeValue, nextNumber) => {
    const raw = (tipeValue || '').toLowerCase();
    if (raw.includes('tengah')) return 'Ujian Tengah Semester';
    if (raw.includes('akhir')) return 'Ujian Akhir Semester';
    if (raw.includes('quiz')) return `Quiz ke: ${nextNumber}`;
    return `Ujian ke: ${nextNumber}`;
  };

  const applyTipeToNama = (tipeValue, inputEl) => {
    if (!inputEl) return;
    if (tipeValue === 'Ujian Tengah Semester' || tipeValue === 'Ujian Akhir Semester') {
      inputEl.value = tipeValue;
      return;
    }
    if (inputEl.value === 'Ujian Tengah Semester' || inputEl.value === 'Ujian Akhir Semester') {
      inputEl.value = '';
    }
  };

  btnOpenUjian?.addEventListener('click', () => {
    ujianModal.classList.remove('hidden');
    ujianModal.classList.add('flex');
    if (kelasSelect && kelasSelect.value) {
      const opt = kelasSelect.options[kelasSelect.selectedIndex];
      const next = Number(opt?.dataset?.ujianNext || 1);
      if (ujianKeInfo) {
        ujianKeInfo.textContent = updateUjianKeLabel(tipeSelect?.value, next);
      }
    }
  });
  btnCloseUjian?.addEventListener('click', closeUjianModal);
  btnCancelUjian?.addEventListener('click', closeUjianModal);
  ujianModal?.addEventListener('click', (e) => {
    if (e.target === ujianModal) closeUjianModal();
  });

  kelasSelect?.addEventListener('change', () => {
    const opt = kelasSelect.options[kelasSelect.selectedIndex];
    matkulText.value = opt?.dataset?.matkul || '';
    if (matkulId) {
      matkulId.value = opt?.dataset?.matkulId || '';
    }
    const next = Number(opt?.dataset?.ujianNext || 1);
    if (ujianKeInfo) {
      ujianKeInfo.textContent = updateUjianKeLabel(tipeSelect?.value, next);
    }
  });

  tipeSelect?.addEventListener('change', () => {
    if (!kelasSelect || !ujianKeInfo) return;
    const opt = kelasSelect.options[kelasSelect.selectedIndex];
    const next = Number(opt?.dataset?.ujianNext || 1);
    ujianKeInfo.textContent = updateUjianKeLabel(tipeSelect?.value, next);
    applyTipeToNama(tipeSelect?.value, namaUjianInput);
  });

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

  const closeEditUjianModal = () => {
    editUjianModal.classList.add('hidden');
    editUjianModal.classList.remove('flex');
  };

  btnCloseEditUjian?.addEventListener('click', closeEditUjianModal);
  btnCancelEditUjian?.addEventListener('click', closeEditUjianModal);
  editUjianModal?.addEventListener('click', (e) => {
    if (e.target === editUjianModal) closeEditUjianModal();
  });

  document.querySelectorAll('.btn-edit-ujian').forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      editUjianForm.action = `/dosen/ujian/${id}`;
      editNamaUjian.value = btn.dataset.nama || '';
      editDeskripsiUjian.value = btn.dataset.deskripsi || '';
      editMulaiUjian.value = btn.dataset.mulai || '';
      editDeadlineUjian.value = btn.dataset.deadline || '';
      editKelasSelect.value = btn.dataset.kelasId || '';
      editMatkulText.value = btn.dataset.matkul || '';
      editMatkulId.value = btn.dataset.matkulId || '';
      editUjianModal.classList.remove('hidden');
      editUjianModal.classList.add('flex');
    });
  });

  editDeskripsiUjian?.addEventListener('change', () => {
    applyTipeToNama(editDeskripsiUjian?.value, editNamaUjian);
  });

  editKelasSelect?.addEventListener('change', () => {
    const opt = editKelasSelect.options[editKelasSelect.selectedIndex];
    editMatkulText.value = opt?.dataset?.matkul || '';
    editMatkulId.value = opt?.dataset?.matkulId || '';
  });

  document.querySelectorAll('.form-delete-ujian').forEach((form) => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      pendingDeleteForm = form;
      if (deleteUjianName) {
        deleteUjianName.textContent = form.dataset.ujian || '-';
      }
      deleteConfirmModal?.classList.remove('hidden');
      deleteConfirmModal?.classList.add('flex');
    });
  });

  btnCancelDelete?.addEventListener('click', () => {
    deleteConfirmModal?.classList.add('hidden');
    deleteConfirmModal?.classList.remove('flex');
    pendingDeleteForm = null;
  });

  btnConfirmDelete?.addEventListener('click', () => {
    if (!pendingDeleteForm) return;
    const form = pendingDeleteForm;
    pendingDeleteForm = null;
    deleteConfirmModal?.classList.add('hidden');
    deleteConfirmModal?.classList.remove('flex');
    actionSuccessText.textContent = 'Ujian berhasil di hapus';
    actionSuccessModal?.classList.remove('hidden');
    actionSuccessModal?.classList.add('flex');
    setTimeout(() => {
      actionSuccessModal?.classList.add('hidden');
      actionSuccessModal?.classList.remove('flex');
      form.submit();
    }, 1000);
  });

  deleteConfirmModal?.addEventListener('click', (e) => {
    if (e.target === deleteConfirmModal) {
      deleteConfirmModal.classList.add('hidden');
      deleteConfirmModal.classList.remove('flex');
      pendingDeleteForm = null;
    }
  });

  @if (session('success'))
    actionSuccessText.textContent = @json(session('success'));
    actionSuccessModal?.classList.remove('hidden');
    actionSuccessModal?.classList.add('flex');
    setTimeout(() => {
      actionSuccessModal?.classList.add('hidden');
      actionSuccessModal?.classList.remove('flex');
    }, 1200);
  @endif

  @if (session('error'))
    actionErrorText.textContent = @json(session('error'));
    actionErrorModal?.classList.remove('hidden');
    actionErrorModal?.classList.add('flex');
    setTimeout(() => {
      actionErrorModal?.classList.add('hidden');
      actionErrorModal?.classList.remove('flex');
    }, 1500);
  @endif
  })();
</script>

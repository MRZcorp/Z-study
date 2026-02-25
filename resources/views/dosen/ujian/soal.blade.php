<x-header>Soal</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

@php
  $mataKuliahNama = $mataKuliahNama ?? ($ujian->mataKuliah->mata_kuliah ?? '-');
  $ujianKe = $ujianKe ?? ($ujian->ujian_ke ?? '-');
  $nilaiUjianKe = $nilaiUjianKe ?? ($ujian->nilai_ujian_ke ?? '-');
  $soalList = $soalList ?? collect();
  $providerSetting = strtolower(trim((string) config('services.llm.provider', 'auto')));
  if (!in_array($providerSetting, ['auto', 'llmapi', 'openrouter'], true)) {
    $providerSetting = 'auto';
  }

  $hasLlmapiKey = (bool) config('services.llmapi.api_key');
  $hasOpenrouterKey = (bool) config('services.openrouter.api_key');

  if ($providerSetting === 'llmapi') {
    $useLlmapi = true;
  } elseif ($providerSetting === 'openrouter') {
    $useLlmapi = false;
  } else {
    $useLlmapi = $hasLlmapiKey || !$hasOpenrouterKey;
  }

  $activeProvider = $useLlmapi
    ? config('services.llmapi.provider_name', 'LLMAPI')
    : config('services.openrouter.provider_name', 'OpenRouter');
  $activeModel = $useLlmapi
    ? (config('services.llmapi.model', '') ?: '-')
    : (config('services.openrouter.model', '') ?: '-');
@endphp

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6 rounded-2xl border bg-white shadow-sm">
    <div class="p-5 flex items-center justify-between gap-4">
      <div class="flex items-center gap-3 text-slate-800">
        <a href="{{ url('/dosen/ujian') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 shadow hover:bg-slate-200" title="Kembali">
          <span class="material-symbols-rounded text-base">chevron_left</span>
        </a>
        <div>
          <h2 class="text-xl font-semibold text-slate-800">{{ $ujian->nama_ujian ?? 'Ujian' }}</h2>
          <p class="text-sm text-slate-500">{{ $mataKuliahNama }}</p>
          <p class="text-sm font-semibold text-slate-600">Ujian ke: {{ $ujianKe }}</p>
        </div>
      </div>

      <div class="flex flex-col items-end gap-2">
        <div class="flex items-center gap-2 -mt-1">
          <button id="btnOpenMozart" type="button" class="inline-flex items-center gap-3 rounded-2xl bg-white border border-slate-200 px-4 py-2 shadow-sm hover:shadow">
            <img src="{{ asset('img/Logo_Zstudy.png') }}" alt="Mozart" class="w-8 h-8 object-contain">
            <span class="text-sm font-semibold text-slate-700">Mozart</span>
          </button>
          <button id="btnOpenSoal" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 px-4 py-2 text-sm font-semibold text-white">
            <span class="material-symbols-rounded text-base">add</span>
            Soal
          </button>
        </div>
        <div class="flex items-center gap-2">
          <button id="btnOpenImportSoal" type="button" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100" title="Import CSV">
            <span class="material-symbols-rounded text-base">upload</span>
          </button>
          <button id="btnOpenDeleteAllSoal" type="button" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-red-50 text-red-700 hover:bg-red-100" title="Hapus Semua Soal">
            <span class="material-symbols-rounded text-base">delete</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="space-y-4">
    @if ($soalList->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada soal.
      </div>
    @endif

    @foreach ($soalList as $soal)
      @php
        $mediaUrl = $soal->media_path ? asset('storage/' . $soal->media_path) : null;
        $mediaExt = $soal->media_path ? strtolower(pathinfo($soal->media_path, PATHINFO_EXTENSION)) : '';
        $isVideo = in_array($mediaExt, ['mp4', 'webm', 'ogg']);
        $isImage = in_array($mediaExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
      @endphp
      <div class="bg-white rounded-xl border p-5 relative">
        <div class="flex items-start gap-4">
          <div class="flex flex-col items-center gap-2">
            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold">
              {{ $loop->iteration }}
            </div>
            @if ($mediaUrl && ($isImage || $isVideo))
              <button
                type="button"
                class="btn-preview-media rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                data-media="{{ $mediaUrl }}"
                data-type="{{ $isVideo ? 'video' : 'image' }}"
              >
                <span class="material-symbols-rounded text-sm">visibility</span>
              </button>
            @endif
          </div>
          <div class="flex-1">
            <div class="flex flex-col gap-4 md:flex-row md:items-start">
              @if ($mediaUrl && ($isImage || $isVideo))
                <div class="shrink-0">
                  <div class="h-60 w-fit max-w-full rounded-xl border bg-slate-50 overflow-hidden flex items-center justify-center">
                    @if ($isImage)
                      <img src="{{ $mediaUrl }}" alt="Media Soal" class="h-60 w-auto object-contain">
                    @else
                      <video src="{{ $mediaUrl }}" controls class="h-60 w-auto object-contain"></video>
                    @endif
                  </div>
                </div>
              @endif

              <div class="flex-1 pb-12">
                <div class="flex items-start justify-between gap-3">
                  <h3 class="font-semibold text-slate-800">{{ $soal->pertanyaan ?? '-' }}</h3>
                  <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-emerald-600">
                      +{{ $soal->bobot ?? 0 }}
                    </span>
                    <span class="text-xs font-semibold uppercase px-2 py-0.5 rounded-md {{ ($soal->tipe ?? 'essay') === 'pg' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600' }}">
                      {{ ($soal->tipe ?? 'essay') === 'pg' ? 'PG' : 'Essay' }}
                    </span>
                  </div>
                </div>
                @if (($soal->tipe ?? 'essay') === 'pg' && !empty($soal->options))
                  <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-slate-600">
                    @foreach ($soal->options as $idx => $opt)
                      @php
                        $letter = chr(65 + $idx);
                        $isCorrect = strtoupper($soal->pg_correct ?? '') === $letter;
                      @endphp
                      <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold {{ $isCorrect ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                          {{ $letter }}
                        </span>
                        <span>{{ $opt }}</span>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>

          </div>
        </div>
        <div class="absolute bottom-4 right-4 flex items-center gap-2">
          <button
            type="button"
            class="btn-edit-soal rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200"
            data-id="{{ $soal->id }}"
            data-tipe="{{ $soal->tipe ?? 'essay' }}"
            data-pertanyaan="{{ $soal->pertanyaan ?? '' }}"
            data-bobot="{{ $soal->bobot ?? '' }}"
            data-options='@json($soal->options ?? [])'
            data-correct="{{ $soal->pg_correct ?? '' }}"
          >
            <span class="material-symbols-rounded text-sm">edit</span>
          </button>
          <form action="{{ route('dosen.ujian.soal.destroy', $soal->id) }}" method="POST" class="form-delete-soal" data-pertanyaan="{{ $soal->pertanyaan ?? 'Soal' }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 hover:bg-red-200">
              <span class="material-symbols-rounded text-sm">delete</span>
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- MODAL TAMBAH SOAL -->
<div id="soalModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[80vh] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Tambah Soal</h3>
      <button id="btnCloseSoal" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <form action="{{ route('dosen.ujian.soal.store', $ujian->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 overflow-y-auto max-h-[calc(80vh-64px)]">
      @csrf
      <input type="hidden" id="tipeInput" name="tipe" value="essay">
      <div class="flex items-center gap-2 rounded-xl bg-slate-100 p-1 w-fit">
        <button id="tabEssay" type="button" class="px-4 py-2 text-sm font-semibold rounded-lg bg-white text-slate-800 shadow">Essay</button>
        <button id="tabPg" type="button" class="px-4 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:bg-white">PG</button>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Foto / Video (opsional)</label>
        <input type="file" name="media" class="w-full rounded-lg border border-slate-300 px-4 py-2" accept="image/*,video/*">
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Pertanyaan</label>
        <textarea id="pertanyaanInput" name="pertanyaan" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Tulis pertanyaan..."></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Bobot Nilai</label>
        <input type="number" name="bobot" min="0" step="0.5" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Contoh: 10">
      </div>

      <div id="pgSection" class="hidden space-y-3">
        <div class="flex flex-wrap items-center gap-3">
          <button id="btnAddJawaban" type="button" class="w-fit rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            +PG
          </button>
          <select id="pgCorrect" name="pg_correct" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
            <option value="">Jawaban Benar</option>
          </select>
        </div>
        <div id="pgList" class="space-y-3"></div>
      </div>
      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" id="btnCancelSoal" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:opacity-90">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDIT SOAL -->
<div id="editSoalModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[80vh] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Edit Soal</h3>
      <button id="btnCloseEditSoal" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <form id="editSoalForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 overflow-y-auto max-h-[calc(80vh-64px)]">
      @csrf
      @method('PUT')
      <input type="hidden" id="editTipeInput" name="tipe" value="essay">

      <div class="flex items-center gap-2 rounded-xl bg-slate-100 p-1 w-fit">
        <button id="tabEssayEdit" type="button" class="px-4 py-2 text-sm font-semibold rounded-lg bg-white text-slate-800 shadow">Essay</button>
        <button id="tabPgEdit" type="button" class="px-4 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:bg-white">PG</button>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Foto / Video (opsional)</label>
        <input type="file" name="media" class="w-full rounded-lg border border-slate-300 px-4 py-2" accept="image/*,video/*">
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Pertanyaan</label>
        <textarea id="editPertanyaanInput" name="pertanyaan" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Tulis pertanyaan..."></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Bobot Nilai</label>
        <input type="number" id="editBobotInput" name="bobot" min="0" step="0.5" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Contoh: 10">
      </div>

      <div id="pgEditSection" class="hidden space-y-3">
        <div class="flex flex-wrap items-center gap-3">
          <button id="btnAddJawabanEdit" type="button" class="w-fit rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            +PG
          </button>
          <select id="pgCorrectEdit" name="pg_correct" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
            <option value="">Jawaban Benar</option>
          </select>
        </div>
        <div id="pgListEdit" class="space-y-3"></div>
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" id="btnCancelEditSoal" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:opacity-90">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL MOZART -->
<div id="mozartModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div class="flex items-center gap-3">
        <img src="{{ asset('img/Logo_Zstudy.png') }}" alt="Mozart" class="w-8 h-8 object-contain">
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Mozart.Ai</h3>
          <p class="text-xs text-slate-500">Asisten Pembuat Soal.</p>
          <p class="text-[11px] text-slate-400">Model: {{ $activeModel }}</p>
        </div>
      </div>
      <button id="btnCloseMozart" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <div class="p-6 space-y-4">
      <textarea id="mozartPrompt" rows="7" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm" placeholder="Buatkan saya 30 soal pg dan 5 essay tentang Algoritma dengan kesulitan menengah - tinggi"></textarea>
      <div id="mozartLoader" class="hidden">
        <div class="flex items-center gap-3 text-sm text-slate-600">
          <span class="inline-flex items-center justify-center w-6 h-6 rounded-full border-2 border-blue-600 border-t-transparent animate-spin"></span>
          <span>Harap tunggu.... sedang membuat soal!</span>
        </div>
        <div class="mt-3 h-2 w-full rounded-full bg-slate-100 overflow-hidden">
          <div class="h-full w-1/3 bg-gradient-to-r from-blue-500 to-indigo-500 animate-pulse"></div>
        </div>
      </div>
      <div class="flex justify-end">
        <button id="btnGenerateMozart" type="button" class="px-5 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">Start</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL IMPORT SOAL -->
<div id="importSoalModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Import Soal</h3>
      <button id="btnCloseImportSoal" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <form id="importSoalForm" action="{{ route('dosen.ujian.soal.import', $ujian->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
      @csrf
      <p class="text-sm text-slate-500">Import soal hanya mendukung format <span class="font-semibold text-slate-700">.csv</span>.</p>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Upload File CSV</label>
        <input id="importSoalFile" type="file" name="file" class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm" accept=".csv" required>
        <p id="importSoalFilename" class="mt-2 text-xs text-slate-500">Belum ada file.</p>
      </div>
      <div class="flex justify-end gap-3 pt-2">
        <button type="button" id="btnCancelImportSoal" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">Upload</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL KONFIRMASI DELETE -->
<div id="deleteSoalModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl p-6">
    <h4 class="text-lg font-semibold text-slate-800">Konfirmasi Hapus</h4>
    <p class="mt-3 text-sm text-slate-600">
      Soal "<span id="deleteSoalText" class="font-semibold text-slate-800">-</span>" akan terhapus secara permanen. Apakah anda yakin?
    </p>
    <div class="mt-6 flex items-center justify-end gap-2">
      <button id="btnCancelDeleteSoal" type="button" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Tidak</button>
      <button id="btnConfirmDeleteSoal" type="button" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Ya</button>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI DELETE ALL -->
<div id="deleteAllSoalModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl p-6">
    <h4 class="text-lg font-semibold text-slate-800">Konfirmasi Hapus Semua</h4>
    <p class="mt-3 text-sm text-slate-600">
      Apakah anda yakin menghapus semua soal?
    </p>
    <div class="mt-6 flex items-center justify-end gap-2">
      <button id="btnCancelDeleteAllSoal" type="button" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Tidak</button>
      <button id="btnConfirmDeleteAllSoal" type="button" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Ya</button>
    </div>
  </div>
  <form id="deleteAllSoalForm" action="{{ route('dosen.ujian.soal.destroyAll', $ujian->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
  </form>
</div>

<!-- MODAL SUKSES HAPUS -->
<div id="deleteSoalSuccess" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center">
    <p class="text-base font-semibold text-slate-800">Soal berhasil dihapus</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-emerald-600">check_circle</span>
    </div>
  </div>
</div>

<!-- MODAL SUKSES IMPORT -->
<div id="importSoalSuccessModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center">
    <p class="text-base font-semibold text-slate-800">Import soal berhasil</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-emerald-600">check_circle</span>
    </div>
  </div>
</div>

<!-- MODAL SUKSES MOZART -->
<div id="mozartSuccessModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center">
    <p class="text-base font-semibold text-slate-800">Soal berhasil dibuat oleh Mozart.Ai</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-emerald-600">check_circle</span>
    </div>
  </div>
</div>

<!-- MODAL SUKSES HAPUS SEMUA -->
<div id="deleteAllSoalSuccess" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center">
    <p class="text-base font-semibold text-slate-800">Semua soal berhasil dihapus</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-emerald-600">check_circle</span>
    </div>
  </div>
</div>

<!-- MODAL NOTIFIKASI -->
<div id="soalSuccessModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center">
    <p id="soalSuccessText" class="text-base font-semibold text-slate-800">Soal berhasil dibuat</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-emerald-600">check_circle</span>
    </div>
  </div>
</div>

<div id="soalErrorModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 text-center relative">
    <button id="btnCloseSoalError" type="button" class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">&times;</button>
    <p id="soalErrorText" class="text-base font-semibold text-slate-800">Input soal belum lengkap</p>
    <div class="mt-3 flex justify-center">
      <span class="material-symbols-rounded text-4xl text-red-600">error</span>
    </div>
  </div>
</div>

<!-- MODAL PREVIEW MEDIA -->
<div id="previewMediaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-slate-800">Preview</h3>
      <button id="btnClosePreviewMedia" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <div class="p-5">
      <div id="previewMediaContainer" class="w-full max-h-[60vh] flex items-center justify-center bg-slate-50 rounded-xl border overflow-hidden">
        Tidak ada media.
      </div>
    </div>
  </div>
</div>

<script>
  const soalModal = document.getElementById('soalModal');
  const btnOpenSoal = document.getElementById('btnOpenSoal');
  const btnCloseSoal = document.getElementById('btnCloseSoal');
  const btnCancelSoal = document.getElementById('btnCancelSoal');
  const tabEssay = document.getElementById('tabEssay');
  const tabPg = document.getElementById('tabPg');
  const tipeInput = document.getElementById('tipeInput');
  const pgSection = document.getElementById('pgSection');
  const btnAddJawaban = document.getElementById('btnAddJawaban');
  const pgList = document.getElementById('pgList');
  const pgCorrect = document.getElementById('pgCorrect');
  const editSoalModal = document.getElementById('editSoalModal');
  const btnCloseEditSoal = document.getElementById('btnCloseEditSoal');
  const btnCancelEditSoal = document.getElementById('btnCancelEditSoal');
  const editSoalForm = document.getElementById('editSoalForm');
  const tabEssayEdit = document.getElementById('tabEssayEdit');
  const tabPgEdit = document.getElementById('tabPgEdit');
  const editTipeInput = document.getElementById('editTipeInput');
  const editPertanyaanInput = document.getElementById('editPertanyaanInput');
  const editBobotInput = document.getElementById('editBobotInput');
  const pgEditSection = document.getElementById('pgEditSection');
  const btnAddJawabanEdit = document.getElementById('btnAddJawabanEdit');
  const pgListEdit = document.getElementById('pgListEdit');
  const pgCorrectEdit = document.getElementById('pgCorrectEdit');
  const deleteSoalModal = document.getElementById('deleteSoalModal');
  const deleteSoalText = document.getElementById('deleteSoalText');
  const btnCancelDeleteSoal = document.getElementById('btnCancelDeleteSoal');
  const btnConfirmDeleteSoal = document.getElementById('btnConfirmDeleteSoal');
  const deleteSoalSuccess = document.getElementById('deleteSoalSuccess');
  let pendingDeleteSoalForm = null;
  const soalSuccessModal = document.getElementById('soalSuccessModal');
  const soalErrorModal = document.getElementById('soalErrorModal');
  const soalErrorText = document.getElementById('soalErrorText');
  const btnCloseSoalError = document.getElementById('btnCloseSoalError');
  const soalSuccessText = document.getElementById('soalSuccessText');
  const btnOpenImportSoal = document.getElementById('btnOpenImportSoal');
  const btnOpenMozart = document.getElementById('btnOpenMozart');
  const mozartModal = document.getElementById('mozartModal');
  const btnCloseMozart = document.getElementById('btnCloseMozart');
  const btnGenerateMozart = document.getElementById('btnGenerateMozart');
  const mozartPrompt = document.getElementById('mozartPrompt');
  const mozartSuccessModal = document.getElementById('mozartSuccessModal');
  const mozartLoader = document.getElementById('mozartLoader');
  const csrfToken = '{{ csrf_token() }}';
  const btnOpenDeleteAllSoal = document.getElementById('btnOpenDeleteAllSoal');
  const deleteAllSoalModal = document.getElementById('deleteAllSoalModal');
  const btnCancelDeleteAllSoal = document.getElementById('btnCancelDeleteAllSoal');
  const btnConfirmDeleteAllSoal = document.getElementById('btnConfirmDeleteAllSoal');
  const deleteAllSoalForm = document.getElementById('deleteAllSoalForm');
  const deleteAllSoalSuccess = document.getElementById('deleteAllSoalSuccess');
  const importSoalModal = document.getElementById('importSoalModal');
  const btnCloseImportSoal = document.getElementById('btnCloseImportSoal');
  const btnCancelImportSoal = document.getElementById('btnCancelImportSoal');
  const importSoalFile = document.getElementById('importSoalFile');
  const importSoalForm = document.getElementById('importSoalForm');
  const importSoalFilename = document.getElementById('importSoalFilename');
  const importSoalSuccessModal = document.getElementById('importSoalSuccessModal');

  const closeSoalModal = () => {
    soalModal.classList.add('hidden');
    soalModal.classList.remove('flex');
  };

  btnOpenSoal?.addEventListener('click', () => {
    soalModal.classList.remove('hidden');
    soalModal.classList.add('flex');
    setTab('essay');
  });
  btnCloseSoal?.addEventListener('click', closeSoalModal);
  btnCancelSoal?.addEventListener('click', closeSoalModal);
  soalModal?.addEventListener('click', (e) => {
    if (e.target === soalModal) closeSoalModal();
  });

  const setTab = (mode) => {
    const isEssay = mode === 'essay';
    if (tipeInput) {
      tipeInput.value = mode;
    }
    tabEssay.classList.toggle('bg-white', isEssay);
    tabEssay.classList.toggle('text-slate-800', isEssay);
    tabEssay.classList.toggle('shadow', isEssay);
    tabPg.classList.toggle('bg-white', !isEssay);
    tabPg.classList.toggle('text-slate-800', !isEssay);
    tabPg.classList.toggle('shadow', !isEssay);
    tabPg.classList.toggle('text-slate-600', isEssay);
    tabEssay.classList.toggle('text-slate-600', !isEssay);
    pgSection.classList.toggle('hidden', isEssay);
    if (isEssay && pgList) {
      pgList.innerHTML = '';
      if (pgCorrect) {
        pgCorrect.innerHTML = '<option value="">Pilih jawaban benar</option>';
      }
    }
  };

  tabEssay?.addEventListener('click', () => setTab('essay'));
  tabPg?.addEventListener('click', () => setTab('pg'));

  const rebuildPgOptions = (listEl, selectEl) => {
    if (!listEl) return;
    const prevValue = selectEl?.value || '';
    if (selectEl) {
      selectEl.innerHTML = '<option value="">Jawaban Benar</option>';
    }
    Array.from(listEl.children).forEach((row, idx) => {
      const letter = String.fromCharCode(65 + idx);
      const labelEl = row.querySelector('.pg-letter');
      const inputEl = row.querySelector('input[name="options[]"]');
      if (labelEl) labelEl.textContent = `${letter}.`;
      if (inputEl) inputEl.placeholder = `Jawaban ${letter}`;
      if (selectEl) {
        const opt = document.createElement('option');
        opt.value = letter;
        opt.textContent = letter;
        selectEl.appendChild(opt);
      }
    });
    if (selectEl) {
      selectEl.value = prevValue && Array.from(selectEl.options).some((o) => o.value === prevValue) ? prevValue : '';
    }
  };

  const addPgOption = () => {
    if (!pgList) return;
    const wrap = document.createElement('div');
    wrap.className = 'flex items-center gap-3';
    wrap.innerHTML = `
      <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-semibold pg-letter">A.</div>
      <input type="text" name="options[]" class="flex-1 rounded-lg border border-slate-300 px-4 py-2" placeholder="Jawaban A">
      <button type="button" class="btn-remove-pg w-8 h-8 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-sm font-semibold hover:bg-red-200">X</button>
    `;
    pgList.appendChild(wrap);
    wrap.querySelector('.btn-remove-pg')?.addEventListener('click', () => {
      wrap.remove();
      rebuildPgOptions(pgList, pgCorrect);
    });
    rebuildPgOptions(pgList, pgCorrect);
  };

  btnAddJawaban?.addEventListener('click', addPgOption);

  const setEditTab = (mode) => {
    const isEssay = mode === 'essay';
    if (editTipeInput) {
      editTipeInput.value = mode;
    }
    tabEssayEdit?.classList.toggle('bg-white', isEssay);
    tabEssayEdit?.classList.toggle('text-slate-800', isEssay);
    tabEssayEdit?.classList.toggle('shadow', isEssay);
    tabPgEdit?.classList.toggle('bg-white', !isEssay);
    tabPgEdit?.classList.toggle('text-slate-800', !isEssay);
    tabPgEdit?.classList.toggle('shadow', !isEssay);
    tabPgEdit?.classList.toggle('text-slate-600', isEssay);
    tabEssayEdit?.classList.toggle('text-slate-600', !isEssay);
    pgEditSection?.classList.toggle('hidden', isEssay);
  };

  const addPgOptionEdit = (value = '') => {
    if (!pgListEdit) return;
    const wrap = document.createElement('div');
    wrap.className = 'flex items-center gap-3';
    wrap.innerHTML = `
      <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-semibold pg-letter">A.</div>
      <input type="text" name="options[]" class="flex-1 rounded-lg border border-slate-300 px-4 py-2" placeholder="Jawaban A" value="${value}">
      <button type="button" class="btn-remove-pg w-8 h-8 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-sm font-semibold hover:bg-red-200">X</button>
    `;
    pgListEdit.appendChild(wrap);
    wrap.querySelector('.btn-remove-pg')?.addEventListener('click', () => {
      wrap.remove();
      rebuildPgOptions(pgListEdit, pgCorrectEdit);
    });
    rebuildPgOptions(pgListEdit, pgCorrectEdit);
  };

  btnAddJawabanEdit?.addEventListener('click', () => addPgOptionEdit());

  document.querySelectorAll('.btn-edit-soal').forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      editSoalForm.action = `/dosen/ujian/soal/${id}`;
      editPertanyaanInput.value = btn.dataset.pertanyaan || '';
      editBobotInput.value = btn.dataset.bobot || '';
      const tipe = btn.dataset.tipe || 'essay';
      setEditTab(tipe);
      if (pgListEdit) pgListEdit.innerHTML = '';
      if (pgCorrectEdit) pgCorrectEdit.innerHTML = '<option value="">Jawaban Benar</option>';
      const options = JSON.parse(btn.dataset.options || '[]');
      if (tipe === 'pg') {
        options.forEach((opt) => addPgOptionEdit(opt));
        if (pgCorrectEdit && btn.dataset.correct) {
          pgCorrectEdit.value = btn.dataset.correct;
        }
      }
      editSoalModal.classList.remove('hidden');
      editSoalModal.classList.add('flex');
    });
  });

  tabEssayEdit?.addEventListener('click', () => setEditTab('essay'));
  tabPgEdit?.addEventListener('click', () => setEditTab('pg'));

  const closeEditSoal = () => {
    editSoalModal?.classList.add('hidden');
    editSoalModal?.classList.remove('flex');
  };
  btnCloseEditSoal?.addEventListener('click', closeEditSoal);
  btnCancelEditSoal?.addEventListener('click', closeEditSoal);
  editSoalModal?.addEventListener('click', (e) => {
    if (e.target === editSoalModal) closeEditSoal();
  });

  document.querySelectorAll('.form-delete-soal').forEach((form) => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      pendingDeleteSoalForm = form;
      if (deleteSoalText) deleteSoalText.textContent = form.dataset.pertanyaan || 'Soal';
      deleteSoalModal?.classList.remove('hidden');
      deleteSoalModal?.classList.add('flex');
    });
  });

  btnCancelDeleteSoal?.addEventListener('click', () => {
    deleteSoalModal?.classList.add('hidden');
    deleteSoalModal?.classList.remove('flex');
    pendingDeleteSoalForm = null;
  });

  btnConfirmDeleteSoal?.addEventListener('click', () => {
    if (!pendingDeleteSoalForm) return;
    const form = pendingDeleteSoalForm;
    pendingDeleteSoalForm = null;
    deleteSoalModal?.classList.add('hidden');
    deleteSoalModal?.classList.remove('flex');
    deleteSoalSuccess?.classList.remove('hidden');
    deleteSoalSuccess?.classList.add('flex');
    setTimeout(() => {
      deleteSoalSuccess?.classList.add('hidden');
      deleteSoalSuccess?.classList.remove('flex');
      form.submit();
    }, 1000);
  });

  const showSoalError = (message) => {
    if (soalErrorText) soalErrorText.textContent = message;
    soalErrorModal?.classList.remove('hidden');
    soalErrorModal?.classList.add('flex');
  };

  const closeSoalError = () => {
    soalErrorModal?.classList.add('hidden');
    soalErrorModal?.classList.remove('flex');
  };

  btnCloseSoalError?.addEventListener('click', closeSoalError);
  soalErrorModal?.addEventListener('click', (e) => {
    if (e.target === soalErrorModal) closeSoalError();
  });

  const soalForm = document.querySelector('#soalModal form');
  soalForm?.addEventListener('submit', (e) => {
    const tipe = tipeInput?.value || 'essay';
    const pertanyaan = document.getElementById('pertanyaanInput')?.value?.trim() || '';
    if (!pertanyaan) {
      e.preventDefault();
      showSoalError('Pertanyaan wajib diisi.');
      return;
    }
    if (tipe === 'pg') {
      const options = Array.from(document.querySelectorAll('#pgList input[name="options[]"]'))
        .map((el) => el.value.trim())
        .filter(Boolean);
      if (options.length === 0) {
        e.preventDefault();
        showSoalError('Jawaban PG wajib diisi.');
        return;
      }
      if (!pgCorrect?.value) {
        e.preventDefault();
        showSoalError('Pilih jawaban benar.');
        return;
      }
    }
  });

  @if (session('success'))
    if (soalSuccessText) {
      soalSuccessText.textContent = '{{ session('success') }}';
    }
    soalSuccessModal?.classList.remove('hidden');
    soalSuccessModal?.classList.add('flex');
    setTimeout(() => {
      soalSuccessModal?.classList.add('hidden');
      soalSuccessModal?.classList.remove('flex');
    }, 1200);
  @endif

  @if (session('import_success'))
    importSoalSuccessModal?.classList.remove('hidden');
    importSoalSuccessModal?.classList.add('flex');
    setTimeout(() => {
      importSoalSuccessModal?.classList.add('hidden');
      importSoalSuccessModal?.classList.remove('flex');
    }, 2200);
  @endif

  @if (session('delete_all_success'))
    deleteAllSoalSuccess?.classList.remove('hidden');
    deleteAllSoalSuccess?.classList.add('flex');
    setTimeout(() => {
      deleteAllSoalSuccess?.classList.add('hidden');
      deleteAllSoalSuccess?.classList.remove('flex');
    }, 2200);
  @endif

  @if ($errors->has('import'))
    showSoalError('{{ $errors->first('import') }}');
  @elseif ($errors->any())
    showSoalError('{{ $errors->first() }}');
  @endif

  const closeImportSoal = () => {
    importSoalModal?.classList.add('hidden');
    importSoalModal?.classList.remove('flex');
    if (importSoalForm) {
      importSoalForm.reset();
    }
    if (importSoalFilename) {
      importSoalFilename.textContent = 'Belum ada file.';
    }
  };

  btnOpenImportSoal?.addEventListener('click', () => {
    importSoalModal?.classList.remove('hidden');
    importSoalModal?.classList.add('flex');
  });
  btnCloseImportSoal?.addEventListener('click', closeImportSoal);
  btnCancelImportSoal?.addEventListener('click', closeImportSoal);
  importSoalModal?.addEventListener('click', (e) => {
    if (e.target === importSoalModal) closeImportSoal();
  });

  importSoalFile?.addEventListener('change', () => {
    const name = importSoalFile?.files?.[0]?.name || '';
    if (importSoalFilename) {
      importSoalFilename.textContent = name ? `File dipilih: ${name}` : 'Belum ada file.';
    }
  });

  const closeMozartModal = () => {
    mozartModal?.classList.add('hidden');
    mozartModal?.classList.remove('flex');
  };

  btnOpenMozart?.addEventListener('click', () => {
    mozartModal?.classList.remove('hidden');
    mozartModal?.classList.add('flex');
  });
  btnCloseMozart?.addEventListener('click', closeMozartModal);
  mozartModal?.addEventListener('click', (e) => {
    if (e.target === mozartModal) closeMozartModal();
  });
  btnGenerateMozart?.addEventListener('click', () => {
    const promptText = mozartPrompt?.value?.trim() || '';
    if (!promptText) {
      showSoalError('Prompt tidak boleh kosong.');
      return;
    }

    btnGenerateMozart.disabled = true;
    btnGenerateMozart.textContent = 'Generating...';
    mozartLoader?.classList.remove('hidden');

    fetch('{{ route('dosen.ujian.soal.generate', $ujian->id) }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ prompt: promptText }),
    })
      .then(async (res) => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
          const detail = data.detail ? ` Detail: ${typeof data.detail === 'string' ? data.detail : JSON.stringify(data.detail)}` : '';
          throw new Error((data.message || 'Gagal membuat soal.') + detail);
        }
        return data;
      })
      .then(() => {
        closeMozartModal();
        mozartLoader?.classList.add('hidden');
        mozartSuccessModal?.classList.remove('hidden');
        mozartSuccessModal?.classList.add('flex');
        setTimeout(() => {
          mozartSuccessModal?.classList.add('hidden');
          mozartSuccessModal?.classList.remove('flex');
          window.location.reload();
        }, 1600);
      })
      .catch((err) => {
        showSoalError(err.message || 'Gagal membuat soal.');
      })
      .finally(() => {
        btnGenerateMozart.disabled = false;
        btnGenerateMozart.textContent = 'Start';
        mozartLoader?.classList.add('hidden');
      });
  });

  mozartPrompt?.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && !event.shiftKey) {
      event.preventDefault();
      btnGenerateMozart?.click();
    }
  });

  const closeDeleteAllSoal = () => {
    deleteAllSoalModal?.classList.add('hidden');
    deleteAllSoalModal?.classList.remove('flex');
  };

  btnOpenDeleteAllSoal?.addEventListener('click', () => {
    deleteAllSoalModal?.classList.remove('hidden');
    deleteAllSoalModal?.classList.add('flex');
  });
  btnCancelDeleteAllSoal?.addEventListener('click', closeDeleteAllSoal);
  deleteAllSoalModal?.addEventListener('click', (e) => {
    if (e.target === deleteAllSoalModal) closeDeleteAllSoal();
  });
  btnConfirmDeleteAllSoal?.addEventListener('click', () => {
    closeDeleteAllSoal();
    deleteAllSoalSuccess?.classList.remove('hidden');
    deleteAllSoalSuccess?.classList.add('flex');
    setTimeout(() => {
      deleteAllSoalSuccess?.classList.add('hidden');
      deleteAllSoalSuccess?.classList.remove('flex');
      deleteAllSoalForm?.submit();
    }, 1200);
  });

  const previewMediaModal = document.getElementById('previewMediaModal');
  const btnClosePreviewMedia = document.getElementById('btnClosePreviewMedia');
  const previewMediaContainer = document.getElementById('previewMediaContainer');

  const closePreviewMedia = () => {
    previewMediaModal?.classList.add('hidden');
    previewMediaModal?.classList.remove('flex');
    if (previewMediaContainer) {
      previewMediaContainer.innerHTML = 'Tidak ada media.';
    }
  };

  document.querySelectorAll('.btn-preview-media').forEach((btn) => {
    btn.addEventListener('click', () => {
      const url = btn.dataset.media || '';
      const type = btn.dataset.type || 'image';
      if (!url) {
        previewMediaContainer.innerHTML = 'Tidak ada media.';
      } else if (type === 'video') {
        previewMediaContainer.innerHTML = `<video src="${url}" controls class="max-h-[60vh] w-auto"></video>`;
      } else {
        previewMediaContainer.innerHTML = `<img src="${url}" alt="Preview" class="max-h-[60vh] w-auto object-contain">`;
      }
      previewMediaModal?.classList.remove('hidden');
      previewMediaModal?.classList.add('flex');
    });
  });

  btnClosePreviewMedia?.addEventListener('click', closePreviewMedia);
  previewMediaModal?.addEventListener('click', (e) => {
    if (e.target === previewMediaModal) closePreviewMedia();
  });
</script>

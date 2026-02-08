<x-header>Materi Pembelajaran</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

@php
  $kelasNama = $kelas->mataKuliah->mata_kuliah ?? 'Materi Pembelajaran';
  $kelasKode = $kelas->nama_kelas ?? '-';
  $kelasHari = $kelas->hari_kelas ?? '-';
  $kelasJamMulai = isset($kelas) ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '';
  $kelasJamSelesai = isset($kelas) ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '';
  $dosenNama = $kelas->dosens->user->name ?? '-';
  $dosenGelar = $kelas->dosens->gelar ?? '';
  $dosenFoto = ($kelas->dosens && $kelas->dosens->poto_profil)
    ? asset('storage/' . $kelas->dosens->poto_profil)
    : asset('img/default_profil.jpg');
  $pertemuanHasMateri = $pertemuanHasMateri ?? $materi_kelas->pluck('pertemuan')->filter()->unique();
@endphp

<div class="bg-white rounded-xl border p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 relative">
  <a href="{{ route('dosen.materi.kelas') }}"
     class="absolute top-4 left-4 z-9 inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700">
    <span class="material-symbols-rounded text-base">chevron_left</span>
  </a>

  <!-- KIRI : INFO KELAS -->
  <div>
    <h2 class="text-xl font-bold text-slate-800 pl-12">
      {{$kelasNama}}
    </h2>

    <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-slate-600">

      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-base">school</span>
        Kelas {{$kelasKode}}
      </span>

      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-base">calendar_today</span>
        {{$kelasHari}}
      </span>

      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-base">schedule</span>
        {{$kelasJamMulai}} - {{$kelasJamSelesai}}
      </span>

    </div>
  </div>

  <!-- KANAN : DOSEN + STAT -->
  <div class="flex flex-wrap items-center gap-6 text-sm">

    <!-- DOSEN -->
    <div class="flex items-center gap-3">
      <img src="{{$dosenFoto}}"
           class="w-10 h-10 rounded-full object-cover"
           alt="Dosen">

      <div>
        <p class="font-semibold text-slate-800">
          {{$dosenNama}} {{$dosenGelar}}
        </p>
        <p class="text-xs text-slate-500">
          Dosen Pengampu
        </p>
      </div>
    </div>

    <!-- DIVIDER -->
    <div class="hidden lg:block w-px h-10 bg-slate-200"></div>

    <!-- STAT -->
    <div class="flex gap-4">

      <div class="text-center">
        <p class="text-lg font-bold text-slate-800">
          {{ $materi_total_pertemuan ?? $materi_kelas->pluck('pertemuan')->filter()->unique()->count() }}
        </p>
        <p class="text-xs text-slate-500">
          Pertemuan
        </p>
      </div>

      <div class="text-center">
        <p class="text-lg font-bold text-slate-800">
          {{ $materi_total_count ?? $materi_kelas->count() }}
        </p>
        <p class="text-xs text-slate-500">
          Materi
        </p>
      </div>

    </div>

  </div>

</div>


<div class="mt-4 grid grid-cols-1 lg:grid-cols-4 gap-6">

    <!-- SIDEBAR -->
    <aside class="lg:col-span-1 bg-gradient-to-r from-blue-200 to-purple-200 rounded-xl border p-4 h-fit sticky top-6">
      <!-- SIDEBAR CONTENT -->
      <aside class="bg-white rounded-xl border p-4 space-y-4 sticky top-6">

        <!-- MATA KULIAH -->
        <div>
          <h3 class="font-semibold text-slate-800">
            {{$kelasNama}}
          </h3>
          <p class="text-sm text-slate-500">
            Kelas {{$kelasKode}}
          </p>
        </div>
      
        <hr>
      
        <!-- FILTER PERTEMUAN -->
        <div class="space-y-1">
          <p class="text-xs font-semibold text-slate-400 uppercase mb-2">
            Pertemuan
          </p>
      
          <!-- SEMUA -->
          <a href="{{ request()->url() }}"
             class="block px-3 py-2 rounded-lg text-sm
                    {{ request('pertemuan') == null ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-slate-100' }}">
            Semua Pertemuan
          </a>
      
          @for ($i = 1; $i <= 14; $i++)
            <a href="{{ request()->url() }}?pertemuan={{ $i }}"
               class="block px-3 py-2 rounded-lg text-sm
                      {{ request('pertemuan') == $i ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-slate-100' }}">
              <span class="flex items-center justify-between">
                <span>Pertemuan {{ $i }}</span>
                @if (($pertemuanHasMateri ?? collect())->contains($i))
                  <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-green-500 text-white text-[10px]">
                    ✓
                  </span>
                @endif
              </span>
            </a>
          @endfor
        </div>
      
      </aside>
    </aside>
  
    <!-- MAIN CONTENT -->
    <section class="lg:col-span-3">

      <div class="mb-4 flex items-center justify-between rounded-xl border bg-white p-4">
        <div>
          <p class="text-sm text-slate-500">Materi untuk pertemuan yang dipilih</p>
          <p class="font-semibold text-slate-800">{{ request('pertemuan') ? 'Pertemuan ' . request('pertemuan') : 'Semua Pertemuan' }}</p>
        </div>
        @if(request('pertemuan'))
        <button id="btnOpenUpload" type="button" class="flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 px-4 py-2 text-sm font-semibold text-white">
          <span class="material-symbols-rounded text-base">upload_file</span>
          Upload Materi
        </button>
        @endif
      </div>

      @if ($materi_kelas->isEmpty())
        <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
          Belum ada materi.
        </div>
      @endif
     
@php
  $materiByPertemuan = $materi_kelas->groupBy('pertemuan')->sortKeys();
@endphp

<!-- CARD LIST -->
<div class="space-y-8">

  @if (!request('pertemuan'))
    @foreach ($materiByPertemuan as $pertemuan => $items)
      <div class="space-y-4">
        <div class="flex items-center gap-2">
          <span class="text-xs font-semibold text-blue-700 bg-blue-100 px-2.5 py-1 rounded-full">
            Pertemuan {{ $pertemuan ?? '-' }}
          </span>
          <span class="text-xs text-slate-500">({{ $items->count() }} materi)</span>
        </div>

        <div class="space-y-5">
          @foreach ($items as $materi)
            <!-- CARD FILE -->
            <div class="bg-white rounded-xl border p-5 flex flex-col gap-4 relative">
              <div class="flex gap-4 flex-1">
            @php
            $tipe_file = strtolower($materi['file_type']);
        
            $nameicon = match($tipe_file) {
                'pdf' => 'description',
                'zip' => 'folder_zip',
                'mp4', 'video' => 'videocam',
                'pptx' => 'Photo_Frame',
                'xlsx' => 'Data_Table',
                'docx' => 'Dictionary',
                default => 'insert_drive_file',
            };
        
            $bgicon = match($nameicon) {
                'description' => 'bg-red-100 text-red-600',
                'videocam' => 'bg-purple-100 text-purple-600',
                'folder_zip' => 'bg-gradient-to-r from-blue-200 to-purple-200 text-purple-600',
                'Photo_Frame' => 'bg-orange-100 text-orange-600',
                'Data_Table' => 'bg-green-100 text-green-600',
                'Dictionary' => 'bg-blue-100 text-blue-600',
                default => 'bg-slate-100 text-slate-700',
            };
        @endphp
        
            <div class="{{ $bgicon }} p-2 rounded-lg h-fit">
              <span class="material-symbols-rounded">{{ $nameicon }}</span>
            </div>

            <div>
              <h3 class="font-semibold text-slate-800">
                {{$materi['judul_materi']}}
              </h3>
              <p class="text-sm text-slate-500 mt-1 max-w-xl">
                {{$materi['deskripsi']}}
              </p>

              <div class="flex items-center gap-4 mt-3 text-sm text-slate-500">
                <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">Pertemuan {{ $materi->pertemuan ?? '-' }}</span>
                <span class="{{ $bgicon }} px-2 py-0.5 rounded-md text-xs font-medium">
                  {{$materi['file_type']}}
                </span>
                <span>{{$materi['created_at']}}</span>
              </div>
            </div>
          </div>

          <div class="absolute bottom-4 right-4 flex items-center gap-2">
            <a
              href="{{ asset('storage/' . $materi->file_path) }}"
              target="_blank"
              class="rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
              <span class="material-symbols-rounded text-base">Download</span>
            </a>
            <button
              type="button"
              class="btn-edit-materi rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
              data-id="{{ $materi->id }}"
              data-judul="{{ $materi->judul_materi }}"
              data-matkul="{{ $materi->matkul }}"
              data-deskripsi="{{ $materi->deskripsi }}"
              data-pertemuan="{{ $materi->pertemuan }}"
            >
              <span class="material-symbols-rounded text-base">edit</span>
            </button>
            <form action="{{ route('dosen.materi.destroy', $materi->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="rounded-full bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-700 hover:bg-red-200">
                <span class="material-symbols-rounded text-base">delete</span>
              </button>
            </form>
          </div>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  @else
    @foreach ($materi_kelas as $materi)
      <!-- CARD FILE -->
      <div class="bg-white rounded-xl border p-5 flex flex-col gap-4 relative">
          <div class="flex gap-4 flex-1">
            @php
            $tipe_file = strtolower($materi['file_type']);
        
            $nameicon = match($tipe_file) {
                'pdf' => 'description',
                'zip' => 'folder_zip',
                'mp4', 'video' => 'videocam',
                'pptx' => 'Photo_Frame',
                'xlsx' => 'Data_Table',
                'docx' => 'Dictionary',
                default => 'insert_drive_file',
            };
        
            $bgicon = match($nameicon) {
                'description' => 'bg-red-100 text-red-600',
                'videocam' => 'bg-purple-100 text-purple-600',
                'folder_zip' => 'bg-gradient-to-r from-blue-200 to-purple-200 text-purple-600',
                'Photo_Frame' => 'bg-orange-100 text-orange-600',
                'Data_Table' => 'bg-green-100 text-green-600',
                'Dictionary' => 'bg-blue-100 text-blue-600',
                default => 'bg-slate-100 text-slate-700',
            };
        @endphp
        
            <div class="{{ $bgicon }} p-2 rounded-lg h-fit">
              <span class="material-symbols-rounded">{{ $nameicon }}</span>
            </div>

            <div>
              <h3 class="font-semibold text-slate-800">
                {{$materi['judul_materi']}}
              </h3>
              <p class="text-sm text-slate-500 mt-1 max-w-xl">
                {{$materi['deskripsi']}}
              </p>

              <div class="flex items-center gap-4 mt-3 text-sm text-slate-500">
                <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">Pertemuan {{ $materi->pertemuan ?? '-' }}</span>
                <span class="{{ $bgicon }} px-2 py-0.5 rounded-md text-xs font-medium">
                  {{$materi['file_type']}}
                </span>
                <span>{{$materi['created_at']}}</span>
              </div>
          </div>

          <div class="absolute bottom-4 right-4 flex items-center gap-2">
            <a
                href="{{ asset('storage/' . $materi->file_path) }}"
                target="_blank"
                class="rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
              <span class="material-symbols-rounded text-base">Download</span>
            </a>
              <button
                type="button"
                class="btn-edit-materi rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
                data-id="{{ $materi->id }}"
                data-judul="{{ $materi->judul_materi }}"
                data-matkul="{{ $materi->matkul }}"
                data-deskripsi="{{ $materi->deskripsi }}"
                data-pertemuan="{{ $materi->pertemuan }}"
              >
                <span class="material-symbols-rounded text-base">edit</span>
              </button>
              <form action="{{ route('dosen.materi.destroy', $materi->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-full bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-700 hover:bg-red-200">
                  <span class="material-symbols-rounded text-base">delete</span>
                </button>
              </form>
          </div>
      </div>
    @endforeach
  @endif

</div>

    </section>
</div>

<!-- MODAL UPLOAD MATERI -->
<div id="uploadMateriModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl animate-scaleIn">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 id="uploadMateriTitle" class="text-lg font-semibold text-gray-800">Upload Materi</h3>
      <button id="btnCloseUploadMateri" type="button" class="text-gray-400 hover:text-gray-600">×</button>
    </div>
    <form id="uploadMateriForm" action="{{ url('/dosen/materi') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Judul Materi</label>
        <input type="text" name="judul_materi" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Mata Kuliah</label>
        <input type="text" name="matkul" id="matkulInput" value="{{ $kelasNama }}" class="w-full rounded-lg border border-slate-300 px-4 py-2 bg-slate-50" readonly>
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
        <input type="hidden" name="pertemuan" id="pertemuanInput" value="{{ request('pertemuan') }}">
        <p class="text-xs text-slate-500 mt-1">Pertemuan {{ request('pertemuan') }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Materi</label>
        <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">File Materi</label>
        <label class="flex items-center gap-3 w-full rounded-xl border border-dashed border-slate-300 px-4 py-6 cursor-pointer hover:bg-slate-50 transition">
          <span class="material-symbols-rounded text-blue-600 text-3xl">upload_file</span>
          <div>
            <p class="text-sm font-medium text-slate-700">Klik untuk upload file</p>
            <p class="text-xs text-slate-500">PDF, PPT, DOC, atau Video</p>
          </div>
          <input type="file" name="file_materi" class="hidden" required>
        </label>
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" id="btnCancelUpload" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:opacity-90">Upload Materi</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDIT MATERI -->
<div id="editMateriModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl animate-scaleIn">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">Edit Materi</h3>
      <button id="btnCloseEditMateri" type="button" class="text-gray-400 hover:text-gray-600">×</button>
    </div>
    <form id="editMateriForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Judul Materi</label>
        <input type="text" name="judul_materi" id="editJudul" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Mata Kuliah</label>
        <input type="text" name="matkul" id="editMatkul" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
      <input type="hidden" name="pertemuan" id="editPertemuan">
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Materi</label>
        <textarea name="deskripsi" id="editDeskripsi" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">File Materi (opsional)</label>
        <input type="file" name="file_materi" class="w-full rounded-lg border border-slate-300 px-4 py-2">
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" id="btnCancelEdit" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium hover:opacity-90">Simpan</button>
      </div>
    </form>
  </div>
</div>

<style>
  @keyframes scaleIn {
    from { transform: scale(.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }
  .animate-scaleIn {
    animation: scaleIn .2s ease-out;
  }
</style>

<script>
  const uploadMateriModal = document.getElementById('uploadMateriModal');
  const btnCloseUploadMateri = document.getElementById('btnCloseUploadMateri');
  const btnCancelUpload = document.getElementById('btnCancelUpload');
  const matkulInput = document.getElementById('matkulInput');
  const pertemuanInput = document.getElementById('pertemuanInput');
  const uploadMateriTitle = document.getElementById('uploadMateriTitle');
  const btnOpenUpload = document.getElementById('btnOpenUpload');

  const closeUploadModal = () => {
    uploadMateriModal.classList.add('hidden');
    uploadMateriModal.classList.remove('flex');
  };

  btnOpenUpload?.addEventListener('click', () => {
    matkulInput.value = '{{ $kelasNama }}';
    if (pertemuanInput) { pertemuanInput.value = '{{ request('pertemuan') }}'; }
    uploadMateriTitle.textContent = `Upload Materi - {{ $kelasNama }}`;
    uploadMateriModal.classList.remove('hidden');
    uploadMateriModal.classList.add('flex');
  });

  btnCloseUploadMateri?.addEventListener('click', closeUploadModal);
  btnCancelUpload?.addEventListener('click', closeUploadModal);
  uploadMateriModal?.addEventListener('click', (e) => {
    if (e.target === uploadMateriModal) {
      closeUploadModal();
    }
  });

  const editMateriModal = document.getElementById('editMateriModal');
  const btnCloseEditMateri = document.getElementById('btnCloseEditMateri');
  const btnCancelEdit = document.getElementById('btnCancelEdit');
  const editMateriForm = document.getElementById('editMateriForm');

  const closeEditModal = () => {
    editMateriModal.classList.add('hidden');
    editMateriModal.classList.remove('flex');
  };

  document.querySelectorAll('.btn-edit-materi').forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      editMateriForm.action = `/dosen/materi/item/${id}`;
      document.getElementById('editJudul').value = btn.dataset.judul || '';
      document.getElementById('editMatkul').value = btn.dataset.matkul || '';
      document.getElementById('editDeskripsi').value = btn.dataset.deskripsi || '';
      document.getElementById('editPertemuan').value = btn.dataset.pertemuan || '';

      editMateriModal.classList.remove('hidden');
      editMateriModal.classList.add('flex');
    });
  });

  btnCloseEditMateri?.addEventListener('click', closeEditModal);
  btnCancelEdit?.addEventListener('click', closeEditModal);
  editMateriModal?.addEventListener('click', (e) => {
    if (e.target === editMateriModal) {
      closeEditModal();
    }
  });
</script>

<x-header>Materi Pembelajaran</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6">
    <h2 class="text-xl font-semibold text-slate-800">Kelas yang Diampu</h2>
    <p class="text-sm text-slate-500">Daftar kelas yang kamu ajar.</p>
  </div>

  <div class="grid gap-6 justify-center [grid-template-columns:repeat(auto-fill,minmax(260px,260px))]">
    @if ($pilih_kelas->isEmpty())
      <div class="col-span-full text-center text-gray-500">
        Belum ada kelas yang kamu ajar.
      </div>
    @endif

    @foreach ($pilih_kelas as $kelas)
      @if (($kelas->status ?? '') === 'selesai')
        @continue
      @endif
      <!-- CARD -->
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden relative">

        <!-- HEADER -->
        <div
          class="relative h-28 bg-cover bg-center"
          style="background-image: url({{ $kelas->bg_image ? asset('storage/' . $kelas->bg_image) : asset('img/Logo_Zstudy.png') }});"
        >
          <div class="absolute inset-0 bg-black/30"></div>

          <!-- SKS TAG -->
          <div
            class="absolute top-1 left-0 z-9 flex items-center gap-1
                   bg-amber-50 text-gray-800 text-sm font-semibold
                   px-2 py-1 rounded-r-full shadow"
          >
            <span class="material-symbols-rounded text-base text-blue-600">
              attach_file
            </span>
            {{$kelas->mataKuliah->sks ?? '-'}}
          </div>

          <div class="absolute inset-0 bg-black/30"></div>

          <div class="absolute bottom-3 left-2 text-white z-10">
            <h3 class="text-sm font-semibold leading-snug max-w-[70%] line-clamp-2">
              {{ $kelas->mataKuliah->mata_kuliah ?? '-' }}
            </h3>
          </div>

          @php
            $stats = $materiStats[$kelas->id] ?? ['total' => 0, 'pertemuan' => 0];
          @endphp

          <!-- AVATAR -->
          <img
            src="{{ $kelas->dosens && $kelas->dosens->poto_profil
                  ? asset('storage/' . $kelas->dosens->poto_profil)
                  : asset('img/default_profil.jpg') }}"
            class="absolute -bottom-10 right-4 w-20 h-20 rounded-full border-4 border-white object-cover z-10"
            alt="Avatar"
          />
        </div>

        <!-- BODY -->
        <div class="pt-4 px-4 pb-4 space-y-1 text-sm text-gray-700">
          <p>Kelas {{$kelas->nama_kelas}}</p>
          <p>{{$kelas->jadwal_kelas}}</p>

          <div class="h-2"></div>

          <p>{{$kelas->hari_kelas}}</p>
          <p>{{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }}
            - {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}</p>

          <div class="h-2"></div>

          <p class="font-medium text-gray-900">{{$kelas->dosens->user->name ?? '-' }} {{$kelas->dosens->gelar ?? ''}} </p>
        </div>

        <!-- FOOTER -->
        <div class="flex items-center justify-between px-4 py-3 border-t">
          <div class="flex items-center gap-4 text-sm text-slate-600">
            <span class="flex items-center gap-1">
              <span class="material-symbols-rounded text-blue-600 text-lg">school</span>
              <span class="font-semibold">{{ $stats['pertemuan'] ?? 0 }}</span>
            </span>
            <span class="flex items-center gap-1">
              <span class="material-symbols-rounded text-blue-600 text-lg">menu_book</span>
              <span class="font-semibold">{{ $stats['total'] ?? 0 }}</span>
            </span>
          </div>
          <div class="flex items-center gap-2">
            <a
              href="{{ route('dosen.materi.kelas.detail', $kelas->slug) }}"
              class="flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-blue-600 hover:bg-slate-200"
            >
              <span class="material-symbols-rounded text-base">visibility</span>Lihat Materi
            </a>
</div>
        </div>
      </div>
      <!-- END CARD -->
    @endforeach
  </div>
</div>

<!-- MODAL UPLOAD MATERI -->
<div id="uploadMateriModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl animate-scaleIn">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 id="uploadMateriTitle" class="text-lg font-semibold text-gray-800">Upload Materi</h3>
      <button id="btnCloseUploadMateri" type="button" class="text-gray-400 hover:text-gray-600"></button>
    </div>
    <form id="uploadMateriForm" action="{{ url('/dosen/materi') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Judul Materi</label>
        <input type="text" name="judul_materi" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Mata Kuliah</label>
        <input type="text" name="matkul" id="matkulInput" class="w-full rounded-lg border border-slate-300 px-4 py-2 bg-slate-50" readonly>
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
  const uploadMateriTitle = document.getElementById('uploadMateriTitle');

  const closeUploadModal = () => {
    uploadMateriModal.classList.add('hidden');
    uploadMateriModal.classList.remove('flex');
  };

  document.querySelectorAll('.btn-upload-materi').forEach((btn) => {
    btn.addEventListener('click', () => {
      const matkul = btn.dataset.matkul || '';
      matkulInput.value = matkul;
      uploadMateriTitle.textContent = matkul ? `Upload Materi - ${matkul}` : 'Upload Materi';
      uploadMateriModal.classList.remove('hidden');
      uploadMateriModal.classList.add('flex');
    });
  });

  btnCloseUploadMateri?.addEventListener('click', closeUploadModal);
  btnCancelUpload?.addEventListener('click', closeUploadModal);
  uploadMateriModal?.addEventListener('click', (e) => {
    if (e.target === uploadMateriModal) {
      closeUploadModal();
    }
  });
</script>

<x-header>Data Kelas</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

<!-- SUB NAVBAR -->
<div class="mb-6">
  <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
    <a href="{{ route('dosen.kelas') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">
      Kelas Saya
    </a>
    <a href="{{ route('dosen.kelas_riwayat') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">
      Riwayat Kelas
    </a>
  </div>
</div>

<!-- HEADER -->
<div class="mb-6 flex items-center justify-between">
  <h2 class="text-lg font-semibold text-gray-800">Kelas Saya</h2>
  <button
    type="button"
    id="btnOpenBuatKelas"
    onclick="openBuatKelas()"
    class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow hover:bg-blue-900"
  >
    Buat Kelas
  </button>
</div>

  <div class="p-6 bg-gray-100 min-h-screen">
        <div class="grid gap-6 justify-center [grid-template-columns:repeat(auto-fill,minmax(260px,260px))]">
   

        @foreach ($pilih_kelas as $kelas)
            
        
      <!-- CARD -->
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
  
        <!-- HEADER (BACKGROUND UPLOADABLE) -->
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
          {{$kelas->mataKuliah->sks}}
        </div>
        <div class="absolute top-1 right-2 z-10 flex items-center gap-2">
          @php
            $kelasLabel = preg_replace('/^kelas\\s*/i', '', (string) ($kelas->nama_kelas ?? ''));
          @endphp
          <button
            type="button"
            class="btn-edit-kelas flex items-center gap-1 rounded-full bg-white/90 text-gray-700 text-xs font-semibold px-2 py-1 shadow hover:bg-white"
            data-kelas-id="{{ $kelas->id }}"
            data-mata-kuliah-id="{{ $kelas->mata_kuliah_id }}"
            data-nama-kelas="{{ $kelasLabel }}"
            data-jadwal-kelas="{{ $kelas->jadwal_kelas }}"
            data-hari-kelas="{{ $kelas->hari_kelas }}"
            data-jam-mulai="{{ $kelas->jam_mulai }}"
            data-jam-selesai="{{ $kelas->jam_selesai }}"
            data-kuota-maksimal="{{ $kelas->kuota_maksimal }}"
            data-status="{{ $kelas->status }}"
          >
            <span class="material-symbols-rounded text-sm">edit</span>
            Edit
          </button>
          <form action="{{ route('dosen.kelas.destroy', $kelas->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
            @csrf
            @method('DELETE')
            <button
              type="submit"
              class="flex items-center gap-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 shadow hover:bg-red-200"
            >
              <span class="material-symbols-rounded text-sm">delete</span>
              Hapus
            </button>
          </form>
        </div>
          <div class="absolute inset-0 bg-black/30"></div>
  
          <div class="absolute bottom-3 left-2 text-white z-10">
            
            <h3 
                  class="text-sm font-semibold leading-snug 
                        max-w-[70%] 
                        line-clamp-2"
                >
                  {{ $kelas->mataKuliah->mata_kuliah }}
                </h3>
          </div>
  
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
          <p>Kelas {{ $kelasLabel }}</p>
          <p>{{$kelas->jadwal_kelas}}</p>
  
          <!-- spacer tanpa titik -->
          <div class="h-2"></div>
  
          <p>{{$kelas->hari_kelas}}</p>
          <p>{{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }}
 - {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}</p>
  
          <div class="h-2"></div>
  
          <p class="font-medium text-gray-900">{{$kelas->dosens->user->name ?? '-' }} {{$kelas->dosens->gelar}} </p>
        </div>
  
        <!-- FOOTER -->
        <div class="flex items-center justify-between px-4 py-3 border-t">
  
          <!-- PEOPLE + KUOTA -->
          <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-600 text-lg">
              people
            </span>
  
            <!-- KUOTA (DINAMIS) -->
            <span class="text-sm font-semibold text-green-600">
              {{ $kelas->mahasiswas_count ?? 0 }} / {{ $kelas->kuota_maksimal }}
            </span>
          </div>


         


  
          <!-- BUTTON -->
          @php
            $chatUserMap = collect([
              (string) ($kelas->dosens->user_id ?? '') => [
                'name' => ($kelas->dosens->user->name ?? '-'),
                'foto' => ($kelas->dosens && $kelas->dosens->poto_profil ? asset('storage/' . $kelas->dosens->poto_profil) : asset('img/default_profil.jpg')),
                'phone' => ($kelas->dosens->no_hp ?? '-'),
                'role' => 'dosen',
                'gelar' => ($kelas->dosens->gelar ?? ''),
                'fakultas' => ($kelas->dosens->fakultas->fakultas ?? '-'),
                'prodi' => ($kelas->dosens->programStudi->nama_prodi ?? '-'),
              ],
            ])->merge(
              $kelas->mahasiswas->mapWithKeys(fn ($mhs) => [
                (string) ($mhs->user_id ?? '') => [
                  'name' => ($mhs->user->name ?? '-'),
                  'foto' => ($mhs->poto_profil ? asset('storage/' . $mhs->poto_profil) : asset('img/default_profil.jpg')),
                  'phone' => '-',
                  'role' => 'mahasiswa',
                  'nim' => ($mhs->nim ?? '-'),
                  'fakultas' => ($mhs->fakultas->fakultas ?? '-'),
                  'prodi' => ($mhs->programStudi->nama_prodi ?? '-'),
                ],
              ])
            );
          @endphp
          <div class="flex items-center gap-2">
            <button
              type="button"
              onclick="openChatModal(this)"
              data-kelas-id="{{ $kelas->id }}"
              data-kelas-nama="{{ $kelas->mataKuliah->mata_kuliah }} - {{ $kelasLabel }}"
              data-user-map='@json($chatUserMap)'
              class="flex items-center gap-1 rounded-full bg-slate-100 text-slate-700 px-3 py-1.5 text-sm font-semibold transition hover:bg-slate-200"
            >
              <span class="material-symbols-rounded text-base">chat</span>
              
            </button>

            <button
              type="button"
              onclick="openModal(this)"
              data-kelas-id="{{ $kelas->id }}"
              data-kelas-nama="{{ $kelas->mataKuliah->mata_kuliah }} - {{ $kelasLabel }}"
              data-dosen="{{ $kelas->dosens->user->name ?? '-' }}"
              data-participants='@json($kelas->mahasiswas->map(fn($mhs) => [
                "name" => $mhs->user->name ?? "-",
                "foto" => $mhs->poto_profil ? asset("storage/" . $mhs->poto_profil) : asset("img/default_profil.jpg"),
              ])->values())'
              class="flex items-center gap-1 rounded-full bg-gradient-to-r 
                     from-blue-500 to-purple-500 px-4 py-1.5 text-sm 
                     font-semibold text-white transition 
                     hover:-translate-y-2 hover:shadow-lg"
            >
              <span class="material-symbols-rounded text-base">visibility</span>
              Lihat
            </button>
          </div>
           
   

          
  
      </div>
      </div>
      <!-- END CARD -->
      @endforeach



{{-- ///////////////////////////////////////////////////////////////////////////////////////// --}}


      <!-- OVERLAY -->
<div id="pesertaModal"
class="fixed inset-0 z-50 hidden items-center justify-center
       bg-black/50 backdrop-blur-sm px-4">

<!-- MODAL BOX -->
<div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl
         animate-scaleIn">

<!-- HEADER -->
<div class="flex items-center justify-between px-5 py-4 border-b">
 <h3 id="pesertaModalTitle" class="text-lg font-semibold text-gray-800">
   Peserta Kelas
 </h3>

 <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
   ✕
 </button>
</div>

<!-- CONTENT -->
<div class="p-5 space-y-6 max-h-[70vh] overflow-y-auto">

 <!-- DOSEN -->
 <div>
   <p class="text-xs font-semibold text-gray-400 uppercase mb-3">
     Dosen Pengampu
   </p>

   <div class="flex items-center gap-4 p-3 rounded-xl
               bg-gray-50 border">

               <img 
        src="{{ $foto 
        ? asset('storage/' . $foto) 
        : asset('img/default_profil.jpg') }}"
          alt="Foto Profil"
          class="w-11 h-11 rounded-full object-cover">
     <div>
       <p class="font-semibold text-gray-800">
         {{$nama}}
       </p>
       <p class="text-xs text-gray-500">
         Host
       </p>
     </div>
   </div>
 </div>

 <!-- MAHASISWA -->
 <div>
   <p class="text-xs font-semibold text-gray-400 uppercase mb-3">
     Mahasiswa Peserta
   </p>

 <div id="pesertaList" class="space-y-2"></div>
 </div>

</div>
</div>
</div>

@include('dosen.kelas.partials.chat_modal')

<style>
  @keyframes scaleIn {
    from { transform: scale(.95); opacity: 0 }
    to   { transform: scale(1); opacity: 1 }
  }
  .animate-scaleIn {
    animation: scaleIn .2s ease-out;
  }
  </style>
  

  <script>
    function openModal(button) {
      const modal = document.getElementById('pesertaModal');
      const list = document.getElementById('pesertaList');
      const title = document.getElementById('pesertaModalTitle');

      const kelasNama = button?.dataset?.kelasNama;
      if (kelasNama) {
        title.textContent = `Peserta Kelas - ${kelasNama}`;
      } else {
        title.textContent = 'Peserta Kelas';
      }

      const participants = JSON.parse(button?.dataset?.participants || '[]');
      list.innerHTML = '';

      if (!participants.length) {
        const empty = document.createElement('div');
        empty.className = 'text-sm text-gray-500';
        empty.textContent = 'Belum ada peserta terdaftar.';
        list.appendChild(empty);
      } else {
        participants.forEach((item) => {
          const row = document.createElement('div');
          row.className = 'flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 transition';

          const img = document.createElement('img');
          img.src = item.foto;
          img.className = 'w-9 h-9 rounded-full object-cover';
          img.alt = item.name || 'Mahasiswa';

          const name = document.createElement('span');
          name.className = 'text-sm text-gray-800';
          name.textContent = item.name || '-';

          row.appendChild(img);
          row.appendChild(name);
          list.appendChild(row);
        });
      }

      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
    
    function closeModal() {
      document.getElementById('pesertaModal').classList.add('hidden');
      document.getElementById('pesertaModal').classList.remove('flex');
    }
    
    // klik di luar modal
    document.getElementById('pesertaModal').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });
    </script>

    <div id="buatKelasModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Buat Kelas Baru</h3>
        <button id="btnCloseBuatKelas" type="button" class="text-gray-400 hover:text-gray-600">×</button>
      </div>
      <form action="{{ url('/dosen/kelas') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        <input type="hidden" name="tahun_ajar" value="{{ $tahunAjarAktif ?? '' }}">
        <input type="hidden" name="semester" value="{{ $semesterAktif ?? '' }}">
        <input type="hidden" name="status" value="aktif">
        <div class="space-y-5">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Prodi</label>
              <select id="filterProdiModal" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Semua Prodi</option>
                @foreach(($prodis ?? []) as $prodiItem)
                  <option value="{{ $prodiItem->id }}">{{ $prodiItem->nama_prodi }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Mata Kuliah</label>
              <select name="mata_kuliah_id" id="mataKuliahSelectModal" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Pilih Mata Kuliah</option>
                @foreach(($mataKuliahs ?? []) as $mataKuliah)
                  <option value="{{ $mataKuliah->id }}" data-prodis="{{ $mataKuliah->programStudis->pluck('id')->implode(',') }}">
                    {{ $mataKuliah->kode_mata_kuliah }} - {{ $mataKuliah->mata_kuliah }} ({{ $mataKuliah->sks }} SKS)
                  </option>
                @endforeach
              </select>
            </div>
            <div>
              <label for="coverModal" class="block text-sm font-medium mb-1">Cover Kelas</label>
              <label for="coverModal" class="flex items-center gap-2 w-full rounded-lg border border-slate-300 px-4 py-2 cursor-pointer bg-white text-slate-500 hover:bg-slate-50 focus-within:ring-2 focus-within:ring-blue-500">
                <span class="material-symbols-rounded text-blue-600">upload</span>
                <span id="file-label-modal" class="text-sm">Pilih file</span>
                <input name="bg_image" id="coverModal" type="file" accept="image/*" class="hidden" onchange="updateFileNameModal(this)">
              </label>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Kuota Mahasiswa</label>
              <select name="kuota_maksimal" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option>10</option>
                <option>20</option>
                <option>30</option>
                <option>40</option>
                <option>50</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Kelas</label>
              <select name="nama_kelas" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option>A</option>
                <option>B</option>
                <option>C</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Sistem</label>
              <select name="jadwal_kelas" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option>Reguler Pagi</option>
                <option>Reguler Siang</option>
                <option>Reguler Malam</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Hari</label>
              <select name="hari_kelas" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option>Senin</option>
                <option>Selasa</option>
                <option>Rabu</option>
                <option>Kamis</option>
                <option>Jumat</option>
                <option>Sabtu</option>
                <option>Minggu</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Jam Mulai</label>
              <input name="jam_mulai" type="time" class="w-full rounded-lg border border-slate-300 px-4 py-2">
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Jam Selesai</label>
              <input name="jam_selesai" type="time" class="w-full rounded-lg border border-slate-300 px-4 py-2">
            </div>
          </div>

          <div class="flex justify-end gap-2">
            <button type="button" id="btnCloseBuatKelasFooter" class="text-sm px-3 py-2 rounded-md border border-slate-300 hover:bg-slate-100">Batal</button>
            <button type="submit" class="text-sm px-3 py-2 rounded-md bg-gradient-to-r from-blue-500 to-purple-500 text-white font-medium hover:opacity-90">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div id="editKelasModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Edit Kelas</h3>
        <button id="btnCloseEditKelas" type="button" class="text-gray-400 hover:text-gray-600">×</button>
      </div>
      <form id="editKelasForm" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')
        <div class="space-y-5">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Mata Kuliah</label>
              <select name="mata_kuliah_id" id="edit_mata_kuliah_id" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Pilih Mata Kuliah</option>
                @foreach(($mataKuliahs ?? []) as $mataKuliah)
                  <option value="{{ $mataKuliah->id }}">{{ $mataKuliah->kode_mata_kuliah }} - {{ $mataKuliah->mata_kuliah }} ({{ $mataKuliah->sks }} SKS)</option>
                @endforeach
              </select>
            </div>
            <div>
              <label for="coverEdit" class="block text-sm font-medium mb-1">Cover Kelas</label>
              <label for="coverEdit" class="flex items-center gap-2 w-full rounded-lg border border-slate-300 px-4 py-2 cursor-pointer bg-white text-slate-500 hover:bg-slate-50 focus-within:ring-2 focus-within:ring-blue-500">
                <span class="material-symbols-rounded text-blue-600">upload</span>
                <span id="file-label-edit" class="text-sm">Pilih file</span>
                <input name="bg_image" id="coverEdit" type="file" accept="image/*" class="hidden" onchange="updateFileNameEdit(this)">
              </label>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Kuota Mahasiswa</label>
              <select name="kuota_maksimal" id="edit_kuota_maksimal" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option>10</option>
                <option>20</option>
                <option>30</option>
                <option>40</option>
                <option>50</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Kelas</label>
              <select name="nama_kelas" id="edit_nama_kelas" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option>A</option>
                <option>B</option>
                <option>C</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Sistem</label>
              <select name="jadwal_kelas" id="edit_jadwal_kelas" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option>Reguler Pagi</option>
                <option>Reguler Siang</option>
                <option>Reguler Malam</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Hari</label>
              <select name="hari_kelas" id="edit_hari_kelas" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option>Senin</option>
                <option>Selasa</option>
                <option>Rabu</option>
                <option>Kamis</option>
                <option>Jumat</option>
                <option>Sabtu</option>
                <option>Minggu</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Jam Mulai</label>
              <input name="jam_mulai" id="edit_jam_mulai" type="time" class="w-full rounded-lg border border-slate-300 px-4 py-2">
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Jam Selesai</label>
              <input name="jam_selesai" id="edit_jam_selesai" type="time" class="w-full rounded-lg border border-slate-300 px-4 py-2">
            </div>
          </div>

          <div class="flex justify-end gap-2">
            <button type="button" id="btnCloseEditKelasFooter" class="text-sm px-3 py-2 rounded-md border border-slate-300 hover:bg-slate-100">Batal</button>
            <button type="submit" class="text-sm px-3 py-2 rounded-md bg-gradient-to-r from-blue-500 to-purple-500 text-white font-medium hover:opacity-90">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    const buatKelasModal = document.getElementById('buatKelasModal');
    const btnOpenBuatKelas = document.getElementById('btnOpenBuatKelas');
    const btnCloseBuatKelas = document.getElementById('btnCloseBuatKelas');
    const btnCloseBuatKelasFooter = document.getElementById('btnCloseBuatKelasFooter');

    function openBuatKelas() {
      if (!buatKelasModal) return;
      buatKelasModal.classList.remove('hidden');
      buatKelasModal.classList.add('flex');
    }

    function closeBuatKelas() {
      if (!buatKelasModal) return;
      buatKelasModal.classList.add('hidden');
      buatKelasModal.classList.remove('flex');
    }

    btnOpenBuatKelas?.addEventListener('click', openBuatKelas);
    btnCloseBuatKelas?.addEventListener('click', closeBuatKelas);
    btnCloseBuatKelasFooter?.addEventListener('click', closeBuatKelas);

    buatKelasModal?.addEventListener('click', (e) => {
      if (e.target === buatKelasModal) {
        closeBuatKelas();
      }
    });

  function updateFileNameModal(input) {
    const label = document.getElementById('file-label-modal');
    if (!label) return;
    label.textContent = input.files?.[0]?.name || 'Pilih file';
  }

  const filterProdiModal = document.getElementById('filterProdiModal');
  const mataKuliahSelectModal = document.getElementById('mataKuliahSelectModal');

  const applyProdiFilterModal = () => {
    if (!filterProdiModal || !mataKuliahSelectModal) return;
    const prodiId = filterProdiModal.value;
    const options = Array.from(mataKuliahSelectModal.options);
    options.forEach((opt) => {
      if (!opt.value) return;
      const prodis = (opt.dataset.prodis || '').split(',').filter(Boolean);
      opt.hidden = prodiId ? !prodis.includes(prodiId) : false;
    });
    if (mataKuliahSelectModal.selectedOptions.length && mataKuliahSelectModal.selectedOptions[0].hidden) {
      mataKuliahSelectModal.value = '';
    }
  };

  filterProdiModal?.addEventListener('change', applyProdiFilterModal);
  </script>

  <script>
    const editKelasModal = document.getElementById('editKelasModal');
    const editKelasForm = document.getElementById('editKelasForm');
    const btnCloseEditKelas = document.getElementById('btnCloseEditKelas');
    const btnCloseEditKelasFooter = document.getElementById('btnCloseEditKelasFooter');

    const closeEditKelas = () => {
      editKelasModal.classList.add('hidden');
      editKelasModal.classList.remove('flex');
    };

    const openEditKelas = () => {
      editKelasModal.classList.remove('hidden');
      editKelasModal.classList.add('flex');
    };

    document.querySelectorAll('.btn-edit-kelas').forEach((btn) => {
      btn.addEventListener('click', () => {
        const kelasId = btn.dataset.kelasId;
        if (!kelasId) return;

        editKelasForm.action = `/dosen/kelas/${kelasId}`;
        document.getElementById('edit_mata_kuliah_id').value = btn.dataset.mataKuliahId || '';
        const rawNamaKelas = btn.dataset.namaKelas || '';
        const cleanedNamaKelas = rawNamaKelas.replace(/^kelas\\s*/i, '').trim();
        document.getElementById('edit_nama_kelas').value = cleanedNamaKelas;
        document.getElementById('edit_jadwal_kelas').value = btn.dataset.jadwalKelas || '';
        document.getElementById('edit_hari_kelas').value = btn.dataset.hariKelas || '';
        document.getElementById('edit_jam_mulai').value = btn.dataset.jamMulai || '';
        document.getElementById('edit_jam_selesai').value = btn.dataset.jamSelesai || '';
        document.getElementById('edit_kuota_maksimal').value = btn.dataset.kuotaMaksimal || '';
        const editStatus = document.getElementById('edit_status');
        if (editStatus) editStatus.value = btn.dataset.status || 'draft';

        openEditKelas();
      });
    });

    btnCloseEditKelas?.addEventListener('click', closeEditKelas);
    btnCloseEditKelasFooter?.addEventListener('click', closeEditKelas);
    editKelasModal?.addEventListener('click', (e) => {
      if (e.target === editKelasModal) {
        closeEditKelas();
      }
    });

    function updateFileNameEdit(input) {
      const label = document.getElementById('file-label-edit');
      if (!label) return;
      label.textContent = input.files?.[0]?.name || 'Pilih file';
    }
  </script>
  



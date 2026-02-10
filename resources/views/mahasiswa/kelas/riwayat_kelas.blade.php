<x-header>Riwayat Kelas</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

<!-- SUB NAVBAR -->
<div class="mb-6">
  <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
    <a href="{{ route('mahasiswa.kelas_saya') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">
      Kelas Saya
    </a>
    <a href="{{ route('mahasiswa.kelas_tersedia') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">
      Kelas Tersedia
    </a>
    <a href="{{ route('mahasiswa.kelas_riwayat') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">
      Riwayat Kelas
    </a>
  </div>
</div>

<!-- HEADER -->
<div class="mb-6 flex items-center justify-between">
  <h2 class="text-lg font-semibold text-gray-800">Riwayat Kelas</h2>
</div>

<div class="mb-6 flex flex-wrap items-center gap-3">
  <div class="flex items-center gap-2">
    <label for="filter_tahun_ajar" class="text-sm text-slate-800">Tahun Ajar</label>
    <select
      id="filter_tahun_ajar"
      class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800"
    >
      <option value="">Semua Tahun</option>
    </select>
  </div>
  <div class="flex items-center gap-2">
    <label for="filter_semester" class="text-sm text-slate-800">Semester</label>
    <select
      id="filter_semester"
      class="h-10 rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-800"
    >
      <option value="">Semua Semester</option>
    </select>
  </div>
</div>

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="grid gap-6 justify-center [grid-template-columns:repeat(auto-fill,minmax(260px,260px))]">

        @if ($riwayat_kelas->isEmpty())
          <div class="col-span-full text-center text-gray-500">
            Belum ada riwayat kelas.
          </div>
        @endif

        @foreach ($riwayat_kelas as $kelas)
        <div
          class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden riwayat-card"
          data-tahun-ajar="{{ $kelas->tahun_ajar ?? ($kelas->tahun_ajaran ?? '') }}"
          data-semester="{{ $kelas->semester ?? '' }}"
        >
          <div
            class="relative h-28 bg-cover bg-center"
            style="background-image: url({{ $kelas->bg_image ? asset('storage/' . $kelas->bg_image) : asset('img/Logo_Zstudy.png') }});"
          >
          <div class="absolute inset-0 bg-black/30"></div>
          <div class="absolute top-1 left-0 z-9 flex items-center gap-1 bg-amber-50 text-gray-800 text-sm font-semibold px-2 py-1 rounded-r-full shadow">
            <span class="material-symbols-rounded text-base text-blue-600">
              attach_file
            </span>
            {{$kelas->mataKuliah->sks ?? '-'}}
          </div>
            <div class="absolute bottom-3 left-2 text-white z-10">
              <h3 class="text-sm font-semibold leading-snug max-w-[70%] line-clamp-2">
                {{ $kelas->mataKuliah->mata_kuliah ?? '-' }}
              </h3>
            </div>
            <button
              onclick="openModal(this)"
              data-kelas-id="{{ $kelas->id }}"
              data-kelas-nama="{{ $kelas->mataKuliah->mata_kuliah ?? '-' }} - {{ $kelas->nama_kelas }}"
              data-dosen="{{ $kelas->dosens->user->name ?? '-' }}"
              data-dosen-foto="{{ $kelas->dosens && $kelas->dosens->poto_profil ? asset('storage/' . $kelas->dosens->poto_profil) : asset('img/default_profil.jpg') }}"
              data-dosen-gelar="{{ $kelas->dosens->gelar ?? '' }}"
              data-participants='@json($kelas->mahasiswas->map(fn($mhs) => [
                 "name" => $mhs->user->name ?? "-",
                 "foto" => $mhs->poto_profil ? asset("storage/" . $mhs->poto_profil) : asset("img/default_profil.jpg"),
              ])->values())'
              class="absolute top-1 right-2 z-10 flex items-center justify-center rounded-full bg-white/90 text-blue-600 text-xs font-semibold w-7 h-7 shadow hover:bg-white"
              aria-label="Lihat peserta"
            >
              <span class="material-symbols-rounded text-base">visibility</span>
            </button>
            <img
              src="{{ $kelas->dosens && $kelas->dosens->poto_profil
                    ? asset('storage/' . $kelas->dosens->poto_profil)
                    : asset('img/default_profil.jpg') }}"
              class="absolute -bottom-10 right-4 w-20 h-20 rounded-full border-4 border-white object-cover z-10"
              alt="Avatar"
            />
          </div>
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
          <div class="flex items-center justify-between px-4 py-3 border-t">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-blue-600 text-lg">
                people
              </span>
              <span class="text-sm font-semibold text-green-600">
                {{ $kelas->mahasiswas_count ?? 0 }} / {{ $kelas->kuota_maksimal }}
              </span>
            </div>
            <a
              href="{{ route('mahasiswa.kelas_riwayat.detail', $kelas->id) }}"
              class="flex items-center gap-1 rounded-full bg-slate-100 px-4 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
            >
              <span class="material-symbols-rounded text-base">history</span>
              Riwayat
            </a>
          </div>
        </div>
        @endforeach
  </div>

<!-- OVERLAY -->
<div id="pesertaModal"
class="fixed inset-0 z-50 hidden items-center justify-center
       bg-black/50 backdrop-blur-sm px-4">

  <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl
           animate-scaleIn">

    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 id="pesertaModalTitle" class="text-lg font-semibold text-gray-800">
        Peserta Kelas
      </h3>

      <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
        Ã—
      </button>
    </div>

    <div class="p-5 space-y-6 max-h-[70vh] overflow-y-auto">
      <div>
        <p class="text-xs font-semibold text-gray-400 uppercase mb-3">
          Dosen Pengampu
        </p>

        <div class="flex items-center gap-4 p-3 rounded-xl
                    bg-gray-50 border">

          <img 
            id="pesertaDosenFoto"
            src="{{ asset('img/default_profil.jpg') }}"
            alt="Foto Profil"
            class="w-11 h-11 rounded-full object-cover">
          <div>
            <p id="pesertaDosenNama" class="font-semibold text-gray-800">Dosen</p>
            <p class="text-xs text-gray-500">
              Host
            </p>
          </div>
        </div>
      </div>

      <div>
        <p class="text-xs font-semibold text-gray-400 uppercase mb-3">
          Mahasiswa Peserta
        </p>

        <div id="pesertaList" class="space-y-2"></div>
      </div>
    </div>
  </div>
</div>

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
  const tahunSelect = document.getElementById('filter_tahun_ajar');
  const semesterSelect = document.getElementById('filter_semester');
  const cards = Array.from(document.querySelectorAll('.riwayat-card'));

  const buildFilterOptions = () => {
    const tahunSet = new Set();
    cards.forEach((card) => {
      const tahun = (card.dataset.tahunAjar || '').trim();
      if (tahun) tahunSet.add(tahun);
    });
    Array.from(tahunSet).sort().forEach((tahun) => {
      const opt = document.createElement('option');
      opt.value = tahun;
      opt.textContent = tahun;
      tahunSelect?.appendChild(opt);
    });
    const yearSet = new Set();
    cards.forEach((card) => {
      const tahun = (card.dataset.tahunAjar || '').toString();
      const match = tahun.match(/\\d{4}/);
      if (match) yearSet.add(Number(match[0]));
    });
    const yearList = Array.from(yearSet).sort((a, b) => a - b);
    const yearIndex = new Map(yearList.map((y, i) => [y, i]));
    const semesterMap = new Map();

    cards.forEach((card) => {
      const semesterRaw = (card.dataset.semester || '').trim().toLowerCase();
      const tahun = (card.dataset.tahunAjar || '').toString();
      const match = tahun.match(/\\d{4}/);
      if (!match || !semesterRaw) return;
      const year = Number(match[0]);
      const idx = yearIndex.get(year);
      if (idx === undefined) return;
      const base = (idx * 2) + 1;
      const semesterNum = semesterRaw === 'genap' ? base + 1 : base;
      card.dataset.semesterNum = String(semesterNum);
      semesterMap.set(semesterNum, semesterRaw);
    });

    Array.from(semesterMap.keys()).sort((a, b) => a - b).forEach((num) => {
      const label = `Semester ${num} ${semesterMap.get(num) === 'genap' ? 'Genap' : 'Ganjil'}`;
      const opt = document.createElement('option');
      opt.value = String(num);
      opt.textContent = label;
      semesterSelect?.appendChild(opt);
    });
  };

  const applyFilters = () => {
    const selectedTahun = (tahunSelect?.value || '').toLowerCase();
    const selectedSemester = (semesterSelect?.value || '').toLowerCase();
    cards.forEach((card) => {
      const tahun = (card.dataset.tahunAjar || '').toLowerCase();
      const semester = (card.dataset.semesterNum || '').toLowerCase();
      const matchTahun = !selectedTahun || tahun === selectedTahun;
      const matchSemester = !selectedSemester || semester === selectedSemester;
      if (matchTahun && matchSemester) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });
  };

  buildFilterOptions();
  tahunSelect?.addEventListener('change', applyFilters);
  semesterSelect?.addEventListener('change', applyFilters);

  function openModal(button) {
    const modal = document.getElementById('pesertaModal');
    const list = document.getElementById('pesertaList');
    const title = document.getElementById('pesertaModalTitle');
    const dosenNamaEl = document.getElementById('pesertaDosenNama');
    const dosenFotoEl = document.getElementById('pesertaDosenFoto');

    const kelasNama = button?.dataset?.kelasNama;
    if (kelasNama) {
      title.textContent = `Peserta Kelas - ${kelasNama}`;
    } else {
      title.textContent = 'Peserta Kelas';
    }

    const dosenNama = button?.dataset?.dosen || 'Dosen';
    const dosenGelar = button?.dataset?.dosenGelar || '';
    const dosenFoto = button?.dataset?.dosenFoto || '';
    if (dosenNamaEl) {
      dosenNamaEl.textContent = `${dosenNama} ${dosenGelar}`.trim();
    }
    if (dosenFotoEl && dosenFoto) {
      dosenFotoEl.src = dosenFoto;
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
  
  document.getElementById('pesertaModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });
</script>

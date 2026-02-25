<x-header>Data Mahasiswa</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>




<div class="max-w-7xl mx-auto space-y-6 p-6">

  <!-- HEADER -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

  <div>
    <h1 class="text-2xl font-bold text-slate-800">
      Kelola Data Mahasiswa
    </h1>
    <p class="text-sm text-slate-500">
      Manajemen data mahasiswa berdasarkan fakultas, program studi, dan angkatan
    </p>
  </div>

  <div class="flex items-center gap-2">
    <button id="btnOpenKrsSetting" type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200" title="Pengaturan KRS">
      <span class="material-symbols-rounded text-base">settings</span>
      KRS
    </button>
    <button id="btnOpenImportMahasiswa" type="button" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100" title="Import CSV">
      <span class="material-symbols-rounded text-base">upload</span>
    </button>

    <!-- BUTTON TAMBAH -->
    <button
    class="btn-add flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
    text-sm font-semibold text-white hover:bg-blue-700 whitespace-nowrap"
    data-modal-target="mahasiswaModal"
    data-title-add="Tambah Mahasiswa"
    data-store-url="{{ url('/admin/kelola_mahasiswa') }}">
  <span class="material-symbols-rounded text-base">person_add</span>
  Tambah Mahasiswa
  </button>
  </div>
</div>




  <!--  FILTER -->
  <form id="mahasiswaFilterForm" class="bg-white rounded-xl shadow p-2" method="GET" action="{{ url('/admin/kelola_mahasiswa') }}">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      

        <!-- FAKULTAS -->
        <select name="fakultas_id"
                data-prodi-target="filter_prodi_id"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Fakultas</option>
          @foreach(($fakultas ?? []) as $fakultasItem)
            <option value="{{ $fakultasItem->id }}" @selected(request('fakultas_id') == $fakultasItem->id)>{{ $fakultasItem->fakultas }}</option>
          @endforeach
        </select>

        <!-- PRODI -->
        <select id="filter_prodi_id"
                name="nama_prodi_id"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Prodi</option>
          @foreach(($prodis ?? []) as $prodi)
            <option value="{{ $prodi->id }}"
                    data-fakultas-id="{{ $prodi->fakultas_id }}"
                    @selected(request('nama_prodi_id') == $prodi->id)>
              {{ $prodi->nama_prodi }}
            </option>
          @endforeach
        </select>

        <!-- ANGKATAN -->
        <select name="angkatan_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Angkatan</option>
          @foreach(($angkatans ?? []) as $angkatan)
            <option value="{{ $angkatan->id }}" @selected(request('angkatan_id') == $angkatan->id)>{{ $angkatan->tahun }}</option>
          @endforeach
        </select>

        <!-- STATUS AKADEMIK -->
        <select name="status_akademik" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status Akademik</option>
          <option value="AKTIF" @selected(request('status_akademik') === 'AKTIF')>AKTIF</option>
          <option value="CUTI" @selected(request('status_akademik') === 'CUTI')>CUTI</option>
          <option value="DO" @selected(request('status_akademik') === 'DO')>DO</option>
          <option value="LULUS" @selected(request('status_akademik') === 'LULUS')>LULUS</option>
        </select>

        <!-- SEARCH -->
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Cari nama atau NIM..."
               class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                      focus:ring-2 focus:ring-blue-500">
      </div>
    </div>
  </form>
  

  <!-- TABLE -->
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-100 text-slate-600">
        <tr>
          <th class="px-4 py-3 text-left">No</th>
          <th class="px-4 py-3 text-left">Nama Mahasiswa</th>
          <th class="px-4 py-3 text-left">NIM</th>
          <th class="px-4 py-3 text-left">Fakultas</th>
          <th class="px-4 py-3 text-left">Prodi</th>
          <th class="px-4 py-3 text-center">Jenjang</th>
          <th class="px-4 py-3 text-center">Angkatan</th>
          <th class="px-4 py-3 text-center">Beasiswa</th>
          <th class="px-4 py-3 text-center">Status Akademik</th>
          <th class="px-4 py-3 text-center">Status KRS</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>

      <tbody class="divide-y pt-5">

        <!-- ROW 1 -->
        @foreach ($mhss as $mhs)
            
       
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3">{{ $loop->iteration }}</td>
          <td class="px-4 py-3 font-medium">
            {{$mhs->name}}
          </td>
          <td class="px-4 py-3">
            {{$mhs->mahasiswa->nim ?? '-' }}
          </td>
          <td class="px-4 py-3">
            {{$mhs->mahasiswa->fakultas->fakultas ?? '-' }}
          </td>
          <td class="px-4 py-3">
            {{$mhs->mahasiswa->programStudi->nama_prodi ?? '-' }}
          </td>
          <td class="px-4 py-3 text-center">
            {{ !empty($mhs->mahasiswa->jenjang) ? strtoupper($mhs->mahasiswa->jenjang) : '-' }}
          </td>
          <td class="px-4 py-3 text-center">
            {{$mhs->mahasiswa->angkatan?->tahun ?? '-' }}
          </td>
          <td class="px-4 py-3 text-center">
            {{ $mhs->mahasiswa->beasiswa?->nama ?? '-' }}
          </td>
          {{-- <td class="px-4 py-3">
            {{$mhs->email}}
          </td> --}}
          <td class="px-4 py-3 text-center">
            @php
              $statusAkademik = strtoupper($mhs->mahasiswa->status_akademik ?? 'AKTIF');
              $statusClass = $statusAkademik === 'AKTIF'
                ? 'bg-green-100 text-green-700'
                : ($statusAkademik === 'LULUS' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700');
              $statusKrs = strtolower((string) ($mhs->mahasiswa->status_krs ?? 'nonaktif'));
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
            {{ $statusAkademik }}
            </span>
          </td>
          <td class="px-4 py-3 text-center">
            @if ($statusKrs === 'aktif')
              <span class="inline-flex items-center justify-center text-green-600" title="Terbuka">
                <span class="material-symbols-rounded">lock_open</span>
              </span>
            @else
              <span class="inline-flex items-center justify-center text-red-600" title="Terkunci">
                <span class="material-symbols-rounded">lock</span>
              </span>
            @endif
          </td>
          <td class="px-4 py-3">
            <div class="flex justify-center gap-2">
              <button
                      class="btn-preview p-2 rounded-lg bg-slate-100 hover:bg-slate-200"
                      title="Lihat"
                      data-name="{{ $mhs->name }}"
                      data-nim="{{ $mhs->mahasiswa->nim ?? '' }}"
                      data-email="{{ $mhs->mahasiswa->email ?? $mhs->email }}"
                      data-role="{{ $mhs->role->nama_role ?? 'Mahasiswa' }}"
                      data-status="{{ $mhs->mahasiswa->status_akademik ?? 'AKTIF' }}"
                      data-created="{{ $mhs->created_at }}"
              >
                <span class="material-symbols-rounded">visibility</span>
              </button>
              <button
                      class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                      title="Edit"
                      @disabled(empty($mhs->mahasiswa?->id))
                      data-modal-target="mahasiswaModal"
                      data-title-edit="Edit Mahasiswa"
                      data-id="{{ $mhs->mahasiswa->id ?? '' }}"
                      data-name="{{ $mhs->name }}"
                      data-email="{{ $mhs->mahasiswa->email ?? $mhs->email }}"
                      data-nim="{{ $mhs->mahasiswa->nim ?? '' }}"
                      data-fakultas-id="{{ $mhs->mahasiswa->fakultas_id ?? '' }}"
                      data-nama-prodi-id="{{ $mhs->mahasiswa->nama_prodi_id ?? '' }}"
                      data-jenjang="{{ $mhs->mahasiswa->jenjang ?? '' }}"
                      data-beasiswa-id="{{ $mhs->mahasiswa->beasiswa_id ?? '' }}"
                      data-angkatan-id="{{ $mhs->mahasiswa->angkatan_id ?? '' }}"
                      data-status-akademik="{{ $mhs->mahasiswa->status_akademik ?? 'AKTIF' }}"
                      data-status-krs="{{ $mhs->mahasiswa->status_krs ?? 'nonaktif' }}"
                      data-update-url="{{ url('/admin/kelola_mahasiswa/' . ($mhs->mahasiswa->id ?? '')) }}">
                <span class="material-symbols-rounded">edit</span>
              </button>
              <button
                      class="btn-delete-mahasiswa p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                      title="Hapus"
                      @disabled(empty($mhs->mahasiswa?->id))
                      data-id="{{ $mhs->mahasiswa->id ?? '' }}"
                      data-delete-url="{{ url('/admin/kelola_mahasiswa/' . ($mhs->mahasiswa->id ?? '')) }}">
                <span class="material-symbols-rounded">delete</span>
              </button>
            </div>
          </td>
        </tr>
        @endforeach
        

      </tbody>
    </table>
  </div>

  <!-- MODAL -->
  <x-crud-modal id="mahasiswaModal" title="Tambah Mahasiswa">
    <input type="text" name="name" id="name" placeholder="Nama" class="w-full rounded-lg border p-2">

    <input type="email" name="email" id="email" placeholder="Email" class="w-full rounded-lg border p-2">

    <input type="text" name="nim" id="nim" placeholder="NIM" class="w-full rounded-lg border p-2">

    <select name="fakultas_id"
            id="fakultas_id"
            data-prodi-target="nama_prodi_id"
            class="w-full rounded-lg border p-2"
            >
      <option value="">Pilih Fakultas</option>
    @foreach(($fakultas ?? []) as $fakultasItem)
      <option value="{{ $fakultasItem->id }}">{{ $fakultasItem->fakultas }}</option>
    @endforeach
    </select>

    <select name="nama_prodi_id" id="nama_prodi_id" class="w-full rounded-lg border p-2">
      <option value="">Pilih Prodi</option>
    @foreach(($prodis ?? []) as $prodi)
      <option value="{{ $prodi->id }}" data-fakultas-id="{{ $prodi->fakultas_id }}">{{ $prodi->nama_prodi }}</option>
    @endforeach
    </select>

    <select name="jenjang" id="jenjang" class="w-full rounded-lg border p-2">
      <option value="">Pilih Jenjang</option>
      <option value="d3">D3</option>
      <option value="s1">S1</option>
      <option value="s2">S2</option>
    </select>

    <select name="angkatan_id" id="angkatan_id" class="w-full rounded-lg border p-2">
      <option value="">Pilih Angkatan</option>
      @foreach(($angkatans ?? []) as $angkatan)
        <option value="{{ $angkatan->id }}">{{ $angkatan->tahun }}</option>
      @endforeach
    </select>

    <select name="beasiswa_id" id="beasiswa_id" class="w-full rounded-lg border p-2">
      <option value="">Pilih Beasiswa</option>
      @foreach(($beasiswas ?? []) as $beasiswa)
        <option value="{{ $beasiswa->id }}">{{ $beasiswa->nama }}</option>
      @endforeach
    </select>

    <select name="status_akademik" id="status_akademik" class="w-full rounded-lg border p-2">
      <option value="">Pilih Status Akademik</option>
      <option value="AKTIF">AKTIF</option>
      <option value="CUTI">CUTI</option>
      <option value="DO">DO</option>
      <option value="LULUS">LULUS</option>
    </select>

    <select name="status_krs" id="status_krs" class="w-full rounded-lg border p-2">
      <option value="nonaktif">KRS Terkunci</option>
      <option value="aktif">KRS Terbuka</option>
    </select>
  </x-crud-modal>

  <!-- MODAL PENGATURAN KRS -->
  <div id="krsSettingModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Pengaturan Status KRS</h3>
        <button id="btnCloseKrsSetting" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
      </div>
      <div class="p-6 space-y-4">
        <div>
          <label for="krsScope" class="block text-sm font-medium text-slate-700 mb-2">Status KRS</label>
          <select id="krsScope" class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm">
            <option value="semua">Semua Mahasiswa</option>
            <option value="beasiswa_semua">Semua Mahasiswa Beasiswa</option>
            @foreach(($beasiswas ?? []) as $beasiswa)
              <option value="beasiswa:{{ $beasiswa->id }}">{{ $beasiswa->nama }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex justify-end gap-3 pt-2">
          <button type="button" id="btnKrsNonaktifkan" class="px-5 py-2 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700">Nonaktifkan</button>
          <button type="button" id="btnKrsAktifkan" class="px-5 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700">Aktifkan</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL IMPORT MAHASISWA -->
  <div id="importMahasiswaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Import Mahasiswa</h3>
        <button id="btnCloseImportMahasiswa" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
      </div>
      <form id="importMahasiswaForm" action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
        @csrf
        <p class="text-sm text-slate-500">Format file harus <span class="font-semibold text-slate-700">.csv</span>.</p>
        <p class="text-xs text-slate-500">Format kolom: No, Nama, NIM, Prodi, Jenjang, Fakultas, Angkatan. Kolom No akan diabaikan.</p>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Upload File CSV</label>
          <input id="importMahasiswaFile" type="file" name="file" class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm" accept=".csv" required>
          <p id="importMahasiswaFilename" class="mt-2 text-xs text-slate-500">Belum ada file.</p>
        </div>
        <div class="flex justify-end gap-3 pt-2">
          <button type="button" id="btnCancelImportMahasiswa" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Batal</button>
          <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">Upload</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL PREVIEW -->
  <div id="previewModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-800">Detail Mahasiswa</h3>
        <button type="button" class="btn-close text-slate-400 hover:text-slate-600">×</button>
      </div>
      <div class="space-y-2 text-sm text-slate-700">
        <p><span class="font-semibold">Nama:</span> <span id="previewName">-</span></p>
        <p><span class="font-semibold">NIDN/NIM:</span> <span id="previewId">-</span></p>
        <p><span class="font-semibold">Email:</span> <span id="previewEmail">-</span></p>
        <p><span class="font-semibold">Role:</span> <span id="previewRole">-</span></p>
        <p><span class="font-semibold">Status Akademik:</span> <span id="previewStatus">-</span></p>
        <p><span class="font-semibold">Tanggal Dibuat:</span> <span id="previewCreated">-</span></p>
      </div>
    </div>
  </div>

  <!-- MODAL KONFIRMASI HAPUS -->
  <div id="deleteModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6">
      <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Mahasiswa</h3>
      <div class="flex justify-end gap-2 mt-6">
        <button type="button" id="btnCancelDelete" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
        <button type="button" id="btnConfirmDelete" class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
      </div>
    </div>
  </div>

  <!-- MODAL SUKSES HAPUS -->
  <div id="deleteSuccessModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6 text-center">
      <div class="flex justify-center mb-3">
        <span class="material-symbols-rounded text-5xl text-green-600">check_circle</span>
      </div>
      <p id="deleteSuccessText" class="text-base font-semibold text-slate-800">Mahasiswa berhasil dihapus.</p>
    </div>
  </div>

  <!-- MODAL SUKSES IMPORT -->
  <div id="importSuccessModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6">
      <h3 class="text-lg font-semibold text-slate-800 mb-2">Import Berhasil</h3>
      <p id="importSuccessText" class="text-sm text-slate-600">Import mahasiswa berhasil.</p>
      <div class="flex justify-end mt-6">
        <button type="button" id="btnCloseImportSuccess" class="px-4 py-2 rounded-lg bg-blue-600 text-white">OK</button>
      </div>
    </div>
  </div>

  <script>
    const btnOpenImportMahasiswa = document.getElementById('btnOpenImportMahasiswa');
    const importMahasiswaModal = document.getElementById('importMahasiswaModal');
    const btnCloseImportMahasiswa = document.getElementById('btnCloseImportMahasiswa');
    const btnCancelImportMahasiswa = document.getElementById('btnCancelImportMahasiswa');
    const importMahasiswaForm = document.getElementById('importMahasiswaForm');
    const importMahasiswaFile = document.getElementById('importMahasiswaFile');
    const importMahasiswaFilename = document.getElementById('importMahasiswaFilename');
    const btnOpenKrsSetting = document.getElementById('btnOpenKrsSetting');
    const krsSettingModal = document.getElementById('krsSettingModal');
    const btnCloseKrsSetting = document.getElementById('btnCloseKrsSetting');
    const btnKrsAktifkan = document.getElementById('btnKrsAktifkan');
    const btnKrsNonaktifkan = document.getElementById('btnKrsNonaktifkan');
    const krsScope = document.getElementById('krsScope');
    const mahasiswaCrudForm = document.querySelector('#mahasiswaModal form.crud-form');
    const mahasiswaModal = document.getElementById('mahasiswaModal');
    const mahasiswaFilterForm = document.getElementById('mahasiswaFilterForm');
    const mahasiswaTableBody = document.querySelector('table tbody');
    const mahasiswaModalTitle = mahasiswaModal?.querySelector('.modal-title');
    const mahasiswaCrudMethod = mahasiswaModal?.querySelector('.crud-method');

    const fillEditMahasiswaForm = (btn) => {
      if (!mahasiswaCrudForm || !btn) return;

      const fakultasSelect = mahasiswaCrudForm.querySelector('#fakultas_id');
      const prodiSelect = mahasiswaCrudForm.querySelector('#nama_prodi_id');

      mahasiswaCrudForm.action = btn.dataset.updateUrl || '';
      if (mahasiswaCrudMethod) mahasiswaCrudMethod.value = 'PUT';
      if (mahasiswaModalTitle) mahasiswaModalTitle.textContent = btn.dataset.titleEdit || 'Edit Mahasiswa';

      const setValue = (selector, value) => {
        const el = mahasiswaCrudForm.querySelector(selector);
        if (el) el.value = value ?? '';
      };

      setValue('#name', btn.dataset.name);
      setValue('#email', btn.dataset.email);
      setValue('#nim', btn.dataset.nim);
      setValue('#fakultas_id', btn.dataset.fakultasId);

      if (fakultasSelect && prodiSelect) {
        const selectedFakultas = (btn.dataset.fakultasId || '').toString();
        Array.from(prodiSelect.options).forEach((opt) => {
          if (!opt.value) {
            opt.hidden = false;
            return;
          }
          const shouldShow = !selectedFakultas || (opt.dataset.fakultasId || '') === selectedFakultas;
          opt.hidden = !shouldShow;
        });
      }

      setValue('#nama_prodi_id', btn.dataset.namaProdiId);
      setValue('#jenjang', btn.dataset.jenjang);
      setValue('#beasiswa_id', btn.dataset.beasiswaId);
      setValue('#angkatan_id', btn.dataset.angkatanId);
      setValue('#status_akademik', btn.dataset.statusAkademik);
      setValue('#status_krs', btn.dataset.statusKrs || 'nonaktif');
    };

    const buildQuery = () => {
      if (!mahasiswaFilterForm) return '';
      const formData = new FormData(mahasiswaFilterForm);
      const params = new URLSearchParams();
      formData.forEach((value, key) => {
        if (value !== null && String(value).trim() !== '') {
          params.set(key, String(value).trim());
        }
      });
      return params.toString();
    };

    const fetchFilteredMahasiswa = async () => {
      const query = buildQuery();
      const url = `${window.location.pathname}${query ? `?${query}` : ''}`;
      try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const html = await res.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const newTbody = doc.querySelector('table tbody');
        if (newTbody && mahasiswaTableBody) {
          mahasiswaTableBody.innerHTML = newTbody.innerHTML;
          history.replaceState(null, '', url);
          bindMahasiswaActions();
        }
      } catch (err) {
        console.error('Gagal memuat data:', err);
      }
    };

    const debounce = (fn, delay = 300) => {
      let t;
      return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), delay);
      };
    };

    const bindFilterEvents = () => {
      if (!mahasiswaFilterForm) return;
      mahasiswaFilterForm.querySelectorAll('select').forEach((el) => {
        el.addEventListener('change', fetchFilteredMahasiswa);
      });
      const searchInput = mahasiswaFilterForm.querySelector('input[name="q"]');
      if (searchInput) {
        searchInput.addEventListener('input', debounce(fetchFilteredMahasiswa, 350));
      }
    };

    const bindMahasiswaActions = () => {
      document.querySelectorAll('.btn-preview').forEach((btn) => {
        btn.addEventListener('click', () => {
          const modal = document.getElementById('previewModal');
          if (!modal) return;
          document.getElementById('previewName').textContent = btn.dataset.name || '-';
          document.getElementById('previewId').textContent = btn.dataset.nim || '-';
          document.getElementById('previewEmail').textContent = btn.dataset.email || '-';
          document.getElementById('previewRole').textContent = btn.dataset.role || '-';
          document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
          document.getElementById('previewCreated').textContent = btn.dataset.created || '-';
          modal.classList.remove('hidden');
        });
      });

      document.querySelectorAll('.btn-delete-mahasiswa').forEach((btn) => {
        btn.addEventListener('click', () => {
          const id = (btn.dataset.id || '').trim();
          if (!id) return;
          const url = btn.dataset.deleteUrl || '';
          if (!url) return;
          const modal = document.getElementById('deleteModal');
          if (!modal) return;
          modal.dataset.deleteUrl = url;
          modal.classList.remove('hidden');
        });
      });

      document.querySelectorAll('.btn-edit').forEach((btn) => {
        btn.addEventListener('click', () => {
          fillEditMahasiswaForm(btn);
          mahasiswaModal?.classList.remove('hidden');
        });
      });
    };

    // Close modal when clicking outside
    document.querySelectorAll('.modal-overlay').forEach((modal) => {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.add('hidden');
        }
      });
    });

    // Delete confirm modal actions
    const deleteModal = document.getElementById('deleteModal');
    const deleteSuccessModal = document.getElementById('deleteSuccessModal');
    const deleteSuccessText = document.getElementById('deleteSuccessText');
    const importSuccessModal = document.getElementById('importSuccessModal');
    const importSuccessText = document.getElementById('importSuccessText');
    const btnCloseImportSuccess = document.getElementById('btnCloseImportSuccess');
    document.getElementById('btnCancelDelete')?.addEventListener('click', () => {
      deleteModal?.classList.add('hidden');
    });
    btnCloseImportSuccess?.addEventListener('click', () => {
      importSuccessModal?.classList.add('hidden');
    });
    document.getElementById('btnConfirmDelete')?.addEventListener('click', async () => {
      if (!deleteModal) return;
      const url = deleteModal.dataset.deleteUrl || '';
      if (!url) return;

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: new URLSearchParams({
            _token: '{{ csrf_token() }}',
            _method: 'DELETE',
          }),
        });

        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
          throw new Error(data.message || 'Gagal menghapus data.');
        }

        deleteModal.classList.add('hidden');
        await fetchFilteredMahasiswa();
        if (deleteSuccessText) {
          deleteSuccessText.textContent = data.message || 'Mahasiswa berhasil dihapus';
        }
        deleteSuccessModal?.classList.remove('hidden');
        setTimeout(() => {
          deleteSuccessModal?.classList.add('hidden');
        }, 1200);
      } catch (error) {
        alert(error.message || 'Gagal menghapus data.');
      }
    });

    mahasiswaCrudForm?.addEventListener('submit', async (e) => {
      e.preventDefault();

      const form = e.currentTarget;
      const action = form.getAttribute('action');
      if (!action) return;

      const formData = new FormData(form);

      try {
        const res = await fetch(action, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: formData,
        });

        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
          if (res.status === 422 && data.errors) {
            const firstKey = Object.keys(data.errors)[0];
            const firstMsg = firstKey ? data.errors[firstKey][0] : 'Validasi gagal.';
            throw new Error(firstMsg);
          }
          throw new Error(data.message || 'Gagal menyimpan data.');
        }

        mahasiswaModal?.classList.add('hidden');
        await fetchFilteredMahasiswa();
        if (deleteSuccessText) {
          deleteSuccessText.textContent = data.message || 'Data berhasil disimpan';
        }
        deleteSuccessModal?.classList.remove('hidden');
        setTimeout(() => {
          deleteSuccessModal?.classList.add('hidden');
        }, 1200);
      } catch (error) {
        alert(error.message || 'Gagal menyimpan data.');
      }
    });

    const closeImportMahasiswa = () => {
      importMahasiswaModal?.classList.add('hidden');
      importMahasiswaModal?.classList.remove('flex');
      importMahasiswaForm?.reset();
      if (importMahasiswaFilename) {
        importMahasiswaFilename.textContent = 'Belum ada file.';
      }
    };

    btnOpenImportMahasiswa?.addEventListener('click', () => {
      importMahasiswaModal?.classList.remove('hidden');
      importMahasiswaModal?.classList.add('flex');
    });
    btnCloseImportMahasiswa?.addEventListener('click', closeImportMahasiswa);
    btnCancelImportMahasiswa?.addEventListener('click', closeImportMahasiswa);
    importMahasiswaModal?.addEventListener('click', (e) => {
      if (e.target === importMahasiswaModal) closeImportMahasiswa();
    });
    importMahasiswaFile?.addEventListener('change', () => {
      const fileName = importMahasiswaFile?.files?.[0]?.name || '';
      if (importMahasiswaFilename) {
        importMahasiswaFilename.textContent = fileName ? `File dipilih: ${fileName}` : 'Belum ada file.';
      }
    });

    @if (session('import_success'))
      if (importSuccessText) {
        importSuccessText.textContent = @json(session('import_success'));
      }
      importSuccessModal?.classList.remove('hidden');
    @endif

    @if ($errors->has('import'))
      alert(@json($errors->first('import')));
    @endif

    const closeKrsSettingModal = () => {
      krsSettingModal?.classList.add('hidden');
      krsSettingModal?.classList.remove('flex');
    };

    const submitKrsSetting = async (statusKrs) => {
      const scope = krsScope?.value || 'semua';
      try {
        const res = await fetch(@json(route('admin.mahasiswa.status_krs.bulk')), {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: new URLSearchParams({
            _token: '{{ csrf_token() }}',
            scope,
            status_krs: statusKrs,
          }),
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
          throw new Error(data.message || 'Gagal memperbarui status KRS.');
        }
        closeKrsSettingModal();
        await fetchFilteredMahasiswa();
        if (deleteSuccessText) {
          deleteSuccessText.textContent = data.message || 'Status KRS berhasil diperbarui';
        }
        deleteSuccessModal?.classList.remove('hidden');
        setTimeout(() => {
          deleteSuccessModal?.classList.add('hidden');
        }, 1400);
      } catch (error) {
        alert(error.message || 'Gagal memperbarui status KRS.');
      }
    };

    btnOpenKrsSetting?.addEventListener('click', () => {
      krsSettingModal?.classList.remove('hidden');
      krsSettingModal?.classList.add('flex');
    });
    btnCloseKrsSetting?.addEventListener('click', closeKrsSettingModal);
    krsSettingModal?.addEventListener('click', (e) => {
      if (e.target === krsSettingModal) closeKrsSettingModal();
    });
    btnKrsAktifkan?.addEventListener('click', () => submitKrsSetting('aktif'));
    btnKrsNonaktifkan?.addEventListener('click', () => submitKrsSetting('nonaktif'));

    bindFilterEvents();
    bindMahasiswaActions();
  </script>

</div>

</body>
</html>

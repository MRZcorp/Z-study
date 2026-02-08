<x-header>Data Kelas</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>



<div class="max-w-7xl mx-auto space-y-6">

  <!-- HEADER -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">
        Kelola Data Kelas
      </h1>
      <p class="text-sm text-slate-500">
        Manajemen kelas perkuliahan berdasarkan mata kuliah, dosen, dan tahun ajaran
      </p>
    </div>
  

   <!-- BUTTON TAMBAH -->
  <button
   id="btnAddKelas"
   data-modal-target="kelasModal"
   data-title-add="Tambah Kelas"
   data-store-url="{{ route('admin.kelas.store') }}"
   class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
   text-sm font-semibold text-white hover:bg-blue-700">
<span class="material-symbols-rounded text-base">add</span>
Tambah Kelas
</button>
</div>


  <!-- NAVBAR FILTER -->
  <form id="kelasFilterForm" class="bg-white rounded-xl shadow p-4" method="GET">
    <div class="flex flex-col md:flex-row md:items-center gap-4">

      <div class="grid grid-cols-1 md:grid-cols-5 gap-4 flex-1">

        <!-- MATA KULIAH -->
        <select name="mata_kuliah_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Mata Kuliah</option>
          @foreach(($mataKuliahs ?? []) as $mk)
            <option value="{{ $mk->id }}" {{ (string) request('mata_kuliah_id') === (string) $mk->id ? 'selected' : '' }}>
              {{ $mk->mata_kuliah }}
            </option>
          @endforeach
        </select>

        <!-- SEMESTER -->
        <select name="semester" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Semester</option>
          <option value="ganjil" {{ request('semester') === 'ganjil' ? 'selected' : '' }}>Ganjil</option>
          <option value="genap" {{ request('semester') === 'genap' ? 'selected' : '' }}>Genap</option>
        </select>

        <!-- TAHUN AJAR -->
        <select name="tahun_ajar" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Tahun Ajar</option>
          @foreach(($tahunAjars ?? []) as $ta)
            <option value="{{ $ta }}" {{ request('tahun_ajar') === $ta ? 'selected' : '' }}>{{ $ta }}</option>
          @endforeach
        </select>

        <!-- STATUS -->
        <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
          <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
          <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>

        <!-- SEARCH -->
        <input type="text"
               name="q"
               placeholder="Cari kelas atau dosen..."
               value="{{ request('q') }}"
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
          <th class="px-4 py-3 text-left">Nama Mata Kuliah</th>
          <th class="px-4 py-3 text-left">Kelas</th>
          <th class="px-4 py-3 text-left">Dosen Pengampu</th>
          <th class="w-12 px-2 py-3 text-center">Jumlah Mahasiswa</th>
          <th class="px-4 py-3 text-center">Semester</th>
          <th class="px-4 py-3 text-center">Tahun Ajar</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>

      <tbody class="divide-y">

        {{-- <!-- ROW 1 --> {{ $loop->iteration }} --}}
        @foreach ($kelasList as $kelas)
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 font-medium">
              {{ $kelas->mataKuliah->mata_kuliah ?? '-' }}
            </td>
            <td class="px-4 py-3 font-semibold">
              {{ $kelas->nama_kelas ?? '-' }}
            </td>
            <td class="px-4 py-3">
              {{ $kelas->dosens?->user?->name ?? '-' }}
            </td>
            <td class="px-4 py-3 text-center">
              {{ $kelas->mahasiswas_count ?? 0 }}
            </td>
            <td class="px-4 py-3 text-center">
              {{ ucfirst($kelas->semester ?? '-') }}
            </td>
            <td class="px-4 py-3 text-center">
              {{ $kelas->tahun_ajar ?? '-' }}
            </td>
            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full {{ ($kelas->status ?? 'draft') === 'aktif' ? 'bg-green-100 text-green-700' : (($kelas->status ?? 'draft') === 'selesai' ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-600') }}
                         text-xs font-semibold">
                {{ $kelas->status ?? '-' }}
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <button
                  class="btn-preview p-2 rounded-lg bg-slate-100 hover:bg-slate-200"
                  title="Lihat"
                  data-matkul="{{ $kelas->mataKuliah->mata_kuliah ?? '-' }}"
                  data-kelas="{{ $kelas->nama_kelas ?? '-' }}"
                  data-dosen="{{ $kelas->dosens?->user?->name ?? '-' }}"
                  data-jumlah="{{ $kelas->mahasiswas_count ?? 0 }}"
                  data-semester="{{ $kelas->semester ?? '-' }}"
                  data-tahun="{{ $kelas->tahun_ajar ?? '-' }}"
                  data-status="{{ $kelas->status ?? '-' }}"
                  data-created="{{ $kelas->created_at }}"
                >
                  <span class="material-symbols-rounded">visibility</span>
                </button>

                <button
                  class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                  title="Edit"
                  data-modal-target="kelasModal"
                  data-title-edit="Edit Kelas"
                  data-id="{{ $kelas->id }}"
                  data-mata-kuliah-id="{{ $kelas->mata_kuliah_id }}"
                  data-dosen-id="{{ $kelas->dosen_id }}"
                  data-nama-kelas="{{ $kelas->nama_kelas }}"
                  data-jadwal="{{ $kelas->jadwal_kelas }}"
                  data-hari="{{ $kelas->hari_kelas }}"
                  data-jam-mulai="{{ $kelas->jam_mulai }}"
                  data-jam-selesai="{{ $kelas->jam_selesai }}"
                  data-kuota="{{ $kelas->kuota_maksimal }}"
                  data-status="{{ $kelas->status }}"
                  data-semester="{{ $kelas->semester }}"
                  data-tahun-ajar="{{ $kelas->tahun_ajar }}"
                  data-update-url="{{ url('/admin/data_kelas/' . $kelas->id) }}"
                >
                  <span class="material-symbols-rounded">edit</span>
                </button>

                <button
                  class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                  title="Hapus"
                  data-delete-url="{{ url('/admin/data_kelas/' . $kelas->id) }}"
                >
                  <span class="material-symbols-rounded">delete</span>
                </button>
              </div>
            </td>
          </tr>
        @endforeach

      </tbody>
    </table>
  </div>

</div>

<!-- MODAL -->
<x-crud-modal id="kelasModal" title="Tambah Kelas">
  <select name="mata_kuliah_id" id="mata_kuliah_id" class="w-full rounded-lg border p-2" required>
    <option value="">Pilih Mata Kuliah</option>
    @foreach(($mataKuliahs ?? []) as $mk)
      <option value="{{ $mk->id }}">{{ $mk->kode_mata_kuliah }} - {{ $mk->mata_kuliah }}</option>
    @endforeach
  </select>

  <select name="dosen_id" id="dosen_id" class="w-full rounded-lg border p-2" required>
    <option value="">Pilih Dosen</option>
    @foreach(($dosens ?? []) as $d)
      <option value="{{ $d->id }}">{{ $d->user->name ?? $d->dosen ?? '-' }}</option>
    @endforeach
  </select>

  <input type="text" name="nama_kelas" id="nama_kelas" placeholder="Nama Kelas" class="w-full rounded-lg border p-2" required>
  <input type="text" name="jadwal_kelas" id="jadwal_kelas" placeholder="Jadwal Kelas" class="w-full rounded-lg border p-2" required>

  <select name="semester" id="semester" class="w-full rounded-lg border p-2">
    <option value="">Pilih Semester</option>
    <option value="ganjil">Ganjil</option>
    <option value="genap">Genap</option>
  </select>

  <input type="text" name="tahun_ajar" id="tahun_ajar" placeholder="Tahun Ajar (contoh: 2025 / 2026)" class="w-full rounded-lg border p-2">

  <select name="hari_kelas" id="hari_kelas" class="w-full rounded-lg border p-2" required>
    <option value="">Pilih Hari</option>
    <option>Senin</option>
    <option>Selasa</option>
    <option>Rabu</option>
    <option>Kamis</option>
    <option>Jumat</option>
    <option>Sabtu</option>
  </select>

  <div class="grid grid-cols-2 gap-3">
    <input type="time" name="jam_mulai" id="jam_mulai" class="w-full rounded-lg border p-2" required>
    <input type="time" name="jam_selesai" id="jam_selesai" class="w-full rounded-lg border p-2" required>
  </div>

  <input type="number" name="kuota_maksimal" id="kuota_maksimal" placeholder="Kuota Maksimal" class="w-full rounded-lg border p-2" required>

  <select name="status" id="status" class="w-full rounded-lg border p-2">
    <option value="draft">Draft</option>
    <option value="aktif">Aktif</option>
    <option value="selesai">Selesai</option>
  </select>
</x-crud-modal>

<!-- MODAL PREVIEW -->
<div id="previewModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-slate-800">Detail Kelas</h3>
      <button type="button" class="btn-close text-slate-400 hover:text-slate-600">×</button>
    </div>
    <div class="space-y-2 text-sm text-slate-700">
      <p><span class="font-semibold">Mata Kuliah:</span> <span id="previewMatkul">-</span></p>
      <p><span class="font-semibold">Kelas:</span> <span id="previewKelas">-</span></p>
      <p><span class="font-semibold">Dosen:</span> <span id="previewDosen">-</span></p>
      <p><span class="font-semibold">Jumlah Mahasiswa:</span> <span id="previewJumlah">-</span></p>
      <p><span class="font-semibold">Semester:</span> <span id="previewSemester">-</span></p>
      <p><span class="font-semibold">Tahun Ajar:</span> <span id="previewTahun">-</span></p>
      <p><span class="font-semibold">Status:</span> <span id="previewStatus">-</span></p>
      <p><span class="font-semibold">Tanggal Dibuat:</span> <span id="previewCreated">-</span></p>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div id="deleteModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-sm p-6">
    <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Kelas</h3>
    <div class="flex justify-end gap-2 mt-6">
      <button type="button" id="btnCancelDelete" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
      <button type="button" id="btnConfirmDelete" class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
    </div>
  </div>
</div>

<script>
  const kelasFilterForm = document.getElementById('kelasFilterForm');
  const kelasTableBody = document.querySelector('table tbody');

  const buildQuery = () => {
    if (!kelasFilterForm) return '';
    const formData = new FormData(kelasFilterForm);
    const params = new URLSearchParams();
    formData.forEach((value, key) => {
      if (value !== null && String(value).trim() !== '') {
        params.set(key, String(value).trim());
      }
    });
    return params.toString();
  };

  const fetchFilteredKelas = async () => {
    const query = buildQuery();
    const url = `${window.location.pathname}${query ? `?${query}` : ''}`;
    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const html = await res.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');
      const newTbody = doc.querySelector('table tbody');
      if (newTbody && kelasTableBody) {
        kelasTableBody.innerHTML = newTbody.innerHTML;
        history.replaceState(null, '', url);
        bindKelasActions();
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
    if (!kelasFilterForm) return;
    kelasFilterForm.querySelectorAll('select').forEach((el) => {
      el.addEventListener('change', fetchFilteredKelas);
    });
    const searchInput = kelasFilterForm.querySelector('input[name="q"]');
    if (searchInput) {
      searchInput.addEventListener('input', debounce(fetchFilteredKelas, 350));
    }
  };

  const kelasModal = document.getElementById('kelasModal');
  const modalTitle = kelasModal?.querySelector('.modal-title');
  const crudForm = kelasModal?.querySelector('.crud-form');
  const crudMethod = kelasModal?.querySelector('.crud-method');

  const openModal = () => kelasModal?.classList.remove('hidden');
  const closeModal = () => kelasModal?.classList.add('hidden');

  document.getElementById('btnAddKelas')?.addEventListener('click', () => {
    if (!kelasModal || !crudForm || !crudMethod || !modalTitle) return;
    modalTitle.textContent = document.getElementById('btnAddKelas')?.dataset?.titleAdd || 'Tambah Kelas';
    crudForm.action = document.getElementById('btnAddKelas')?.dataset?.storeUrl || '';
    crudMethod.value = 'POST';
    crudForm.reset();
    openModal();
  });

  const bindKelasActions = () => {
    document.querySelectorAll('.btn-preview').forEach((btn) => {
      btn.addEventListener('click', () => {
        const modal = document.getElementById('previewModal');
        if (!modal) return;
        document.getElementById('previewMatkul').textContent = btn.dataset.matkul || '-';
        document.getElementById('previewKelas').textContent = btn.dataset.kelas || '-';
        document.getElementById('previewDosen').textContent = btn.dataset.dosen || '-';
        document.getElementById('previewJumlah').textContent = btn.dataset.jumlah || '-';
        document.getElementById('previewSemester').textContent = btn.dataset.semester || '-';
        document.getElementById('previewTahun').textContent = btn.dataset.tahun || '-';
        document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
        document.getElementById('previewCreated').textContent = btn.dataset.created || '-';
        modal.classList.remove('hidden');
      });
    });

    document.querySelectorAll('.btn-edit').forEach((btn) => {
      btn.addEventListener('click', () => {
        if (!kelasModal || !crudForm || !crudMethod || !modalTitle) return;
        modalTitle.textContent = btn.dataset.titleEdit || 'Edit Kelas';
        crudForm.action = btn.dataset.updateUrl || '';
        crudMethod.value = 'PUT';

        const setVal = (name, value) => {
          const input = kelasModal.querySelector(`[name="${name}"]`);
          if (input) input.value = value ?? '';
        };
        setVal('mata_kuliah_id', btn.dataset.mataKuliahId);
        setVal('dosen_id', btn.dataset.dosenId);
        setVal('nama_kelas', btn.dataset.namaKelas);
        setVal('jadwal_kelas', btn.dataset.jadwal);
        setVal('hari_kelas', btn.dataset.hari);
        setVal('jam_mulai', btn.dataset.jamMulai);
        setVal('jam_selesai', btn.dataset.jamSelesai);
        setVal('kuota_maksimal', btn.dataset.kuota);
        setVal('status', btn.dataset.status);
        setVal('semester', btn.dataset.semester);
        setVal('tahun_ajar', btn.dataset.tahunAjar);

        openModal();
      });
    });

    document.querySelectorAll('.btn-delete').forEach((btn) => {
      btn.addEventListener('click', () => {
        const url = btn.dataset.deleteUrl || '';
        if (!url) return;
        const modal = document.getElementById('deleteModal');
        if (!modal) return;
        modal.dataset.deleteUrl = url;
        modal.classList.remove('hidden');
      });
    });
  };

  kelasModal?.querySelectorAll('.btn-close').forEach((btn) => {
    btn.addEventListener('click', closeModal);
  });

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
  document.getElementById('btnCancelDelete')?.addEventListener('click', () => {
    deleteModal?.classList.add('hidden');
  });
  document.getElementById('btnConfirmDelete')?.addEventListener('click', () => {
    if (!deleteModal) return;
    const url = deleteModal.dataset.deleteUrl || '';
    if (!url) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.innerHTML = `
      @csrf
      <input type="hidden" name="_method" value="DELETE">
    `;
    document.body.appendChild(form);
    form.submit();
  });

  bindFilterEvents();
  bindKelasActions();
</script>

</div>


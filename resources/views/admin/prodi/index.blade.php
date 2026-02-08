<x-header>Program Studi</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>

<div class="max-w-7xl mx-auto space-y-6">
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Kelola Program Studi</h1>
      <p class="text-sm text-slate-500">Kelola prodi, fakultas, status, dan jumlah mahasiswa</p>
    </div>

    <button
      id="btnAddProdi"
      data-modal-target="prodiModal"
      data-title-add="Tambah Program Studi"
      data-store-url="{{ route('admin.prodi.store') }}"
      class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
    >
      <span class="material-symbols-rounded text-base">add</span>
      Tambah Program Studi
    </button>
  </div>

  <form id="prodiFilterForm" class="bg-white rounded-xl shadow p-4" method="GET">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <select name="fakultas_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Fakultas</option>
        @foreach(($fakultasList ?? []) as $fakultas)
          <option value="{{ $fakultas->id }}" {{ (string) request('fakultas_id') === (string) $fakultas->id ? 'selected' : '' }}>
            {{ $fakultas->fakultas }}
          </option>
        @endforeach
      </select>

      <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status</option>
        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
      </select>

      <input type="text" name="q" placeholder="Cari kode atau nama prodi..." value="{{ request('q') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
    </div>
  </form>

  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-100 text-slate-600">
        <tr>
          <th class="px-4 py-3 text-left">No</th>
          <th class="px-4 py-3 text-left">Kode</th>
          <th class="px-4 py-3 text-left">Nama Prodi</th>
          <th class="px-4 py-3 text-left">Fakultas</th>
          <th class="px-4 py-3 text-center">Jenjang S1</th>
          <th class="px-4 py-3 text-center">Jenjang D3</th>
          <th class="px-4 py-3 text-center">Mahasiswa</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody id="prodiTbody" class="divide-y">
        @foreach ($prodis as $prodi)
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 font-mono text-slate-700">{{ $prodi->kode }}</td>
            <td class="px-4 py-3 font-medium">{{ $prodi->nama_prodi }}</td>
            <td class="px-4 py-3 text-slate-600">{{ $prodi->fakultas?->fakultas ?? '-' }}</td>
            <td class="px-4 py-3 text-center">{{ $prodi->s1 ?? '-' }} SKS</td>
            <td class="px-4 py-3 text-center">{{ $prodi->d3 ?? '-' }} SKS</td>
            <td class="px-4 py-3 text-center">{{ $prodi->mahasiswas_count ?? 0 }}</td>
            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                {{ $prodi->status }}
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <button
                  class="btn-preview p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700"
                  title="Detail"
                  data-kode="{{ $prodi->kode }}"
                  data-nama="{{ $prodi->nama_prodi }}"
                  data-fakultas="{{ $prodi->fakultas?->fakultas ?? '-' }}"
                  data-s1="{{ $prodi->s1 ?? '-' }}"
                  data-d3="{{ $prodi->d3 ?? '-' }}"
                  data-mhs-count="{{ $prodi->mahasiswas_count ?? 0 }}"
                  data-status="{{ $prodi->status }}"
                  data-created="{{ $prodi->created_at }}"
                >
                  <span class="material-symbols-rounded">visibility</span>
                </button>

                <button
                  class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                  title="Edit"
                  data-modal-target="prodiModal"
                  data-title-edit="Edit Program Studi"
                  data-id="{{ $prodi->id }}"
                  data-kode="{{ $prodi->kode }}"
                  data-nama="{{ $prodi->nama_prodi }}"
                  data-fakultas-id="{{ $prodi->fakultas_id }}"
                  data-s1="{{ $prodi->s1 ?? '' }}"
                  data-d3="{{ $prodi->d3 ?? '' }}"
                  data-status="{{ $prodi->status }}"
                  data-update-url="{{ url('/admin/prodi/' . $prodi->id) }}"
                >
                  <span class="material-symbols-rounded">edit</span>
                </button>

                <button
                  class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                  title="Hapus"
                  data-delete-url="{{ url('/admin/prodi/' . $prodi->id) }}"
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

<x-crud-modal id="prodiModal" title="Tambah Program Studi">
  <input type="text" name="kode" id="kode" placeholder="Kode Prodi" class="w-full rounded-lg border p-2" required>
  <input type="text" name="nama_prodi" id="nama_prodi" placeholder="Nama Program Studi" class="w-full rounded-lg border p-2" required>
  <select name="fakultas_id" id="fakultas_id" class="w-full rounded-lg border p-2" required>
    <option value="">Pilih Fakultas</option>
    @foreach(($fakultasList ?? []) as $fakultas)
      <option value="{{ $fakultas->id }}">{{ $fakultas->fakultas }}</option>
    @endforeach
  </select>
  <input type="number" name="s1" id="s1" placeholder="Jenjang S1 (144-160)" class="w-full rounded-lg border p-2">
  <input type="number" name="d3" id="d3" placeholder="Jenjang D3 (108-120)" class="w-full rounded-lg border p-2">
  <select name="status" id="status" class="w-full rounded-lg border p-2">
    <option value="aktif">Aktif</option>
    <option value="nonaktif">Nonaktif</option>
  </select>
</x-crud-modal>

<div id="previewModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-slate-800">Detail Program Studi</h3>
      <button type="button" class="btn-close text-slate-400 hover:text-slate-600">X</button>
    </div>
    <div class="space-y-2 text-sm text-slate-700">
      <p><span class="font-semibold">Kode:</span> <span id="previewKode">-</span></p>
      <p><span class="font-semibold">Nama:</span> <span id="previewNama">-</span></p>
      <p><span class="font-semibold">Fakultas:</span> <span id="previewFakultas">-</span></p>
      <p><span class="font-semibold">Jenjang S1:</span> <span id="previewS1">-</span> SKS</p>
      <p><span class="font-semibold">Jenjang D3:</span> <span id="previewD3">-</span> SKS</p>
      <p><span class="font-semibold">Mahasiswa:</span> <span id="previewMahasiswa">-</span></p>
      <p><span class="font-semibold">Status:</span> <span id="previewStatus">-</span></p>
      <p><span class="font-semibold">Tanggal Dibuat:</span> <span id="previewCreated">-</span></p>
    </div>
  </div>
</div>

<div id="deleteModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-sm p-6">
    <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Program Studi</h3>
    <div class="flex justify-end gap-2 mt-6">
      <button type="button" id="btnCancelDelete" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
      <button type="button" id="btnConfirmDelete" class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
    </div>
  </div>
</div>

<script>
  const prodiFilterForm = document.getElementById('prodiFilterForm');
  const prodiTableBody = document.getElementById('prodiTbody');
  const prodiModal = document.getElementById('prodiModal');
  const modalTitle = prodiModal?.querySelector('.modal-title');
  const crudForm = prodiModal?.querySelector('.crud-form');
  const crudMethod = prodiModal?.querySelector('.crud-method');

  const buildQuery = () => {
    if (!prodiFilterForm) return '';
    const formData = new FormData(prodiFilterForm);
    const params = new URLSearchParams();
    formData.forEach((value, key) => {
      if (value !== null && String(value).trim() !== '') {
        params.set(key, String(value).trim());
      }
    });
    return params.toString();
  };

  const fetchFilteredProdi = async () => {
    const query = buildQuery();
    const url = `${window.location.pathname}${query ? `?${query}` : ''}`;
    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const html = await res.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');
      const newTbody = doc.getElementById('prodiTbody') || doc.querySelector('table tbody');
      if (newTbody && prodiTableBody) {
        prodiTableBody.innerHTML = newTbody.innerHTML;
        history.replaceState(null, '', url);
        bindProdiActions();
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
    if (!prodiFilterForm) return;
    prodiFilterForm.querySelectorAll('select').forEach((el) => {
      el.addEventListener('change', fetchFilteredProdi);
    });
    const searchInput = prodiFilterForm.querySelector('input[name="q"]');
    if (searchInput) {
      searchInput.addEventListener('input', debounce(fetchFilteredProdi, 350));
    }
    prodiFilterForm.addEventListener('submit', (e) => {
      e.preventDefault();
      fetchFilteredProdi();
    });
  };

  const openModal = () => prodiModal?.classList.remove('hidden');
  const closeModal = () => prodiModal?.classList.add('hidden');

  document.getElementById('btnAddProdi')?.addEventListener('click', () => {
    if (!prodiModal || !crudForm || !crudMethod || !modalTitle) return;
    modalTitle.textContent = document.getElementById('btnAddProdi')?.dataset?.titleAdd || 'Tambah Program Studi';
    crudForm.action = document.getElementById('btnAddProdi')?.dataset?.storeUrl || '';
    crudMethod.value = 'POST';
    crudForm.reset();
    openModal();
  });

  const bindProdiActions = () => {
    document.querySelectorAll('.btn-preview').forEach((btn) => {
      btn.addEventListener('click', () => {
        const modal = document.getElementById('previewModal');
        if (!modal) return;
        document.getElementById('previewKode').textContent = btn.dataset.kode || '-';
        document.getElementById('previewNama').textContent = btn.dataset.nama || '-';
        document.getElementById('previewFakultas').textContent = btn.dataset.fakultas || '-';
        document.getElementById('previewS1').textContent = btn.dataset.s1 || '-';
        document.getElementById('previewD3').textContent = btn.dataset.d3 || '-';
        document.getElementById('previewMahasiswa').textContent = btn.dataset.mhsCount || '-';
        document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
        document.getElementById('previewCreated').textContent = btn.dataset.created || '-';
        modal.classList.remove('hidden');
      });
    });

    document.querySelectorAll('.btn-edit').forEach((btn) => {
      btn.addEventListener('click', () => {
        if (!prodiModal || !crudForm || !crudMethod || !modalTitle) return;
        modalTitle.textContent = btn.dataset.titleEdit || 'Edit Program Studi';
        crudForm.action = btn.dataset.updateUrl || '';
        crudMethod.value = 'PUT';

        const setVal = (name, value) => {
          const input = prodiModal.querySelector(`[name="${name}"]`);
          if (input) input.value = value ?? '';
        };
        setVal('kode', btn.dataset.kode);
        setVal('nama_prodi', btn.dataset.nama);
        setVal('fakultas_id', btn.dataset.fakultasId);
        setVal('s1', btn.dataset.s1);
        setVal('d3', btn.dataset.d3);
        setVal('status', btn.dataset.status);

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

  prodiModal?.querySelectorAll('.btn-close').forEach((btn) => {
    btn.addEventListener('click', closeModal);
  });

  document.querySelectorAll('.modal-overlay').forEach((modal) => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
      }
    });
  });

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
  bindProdiActions();
</script>

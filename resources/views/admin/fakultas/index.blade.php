<x-header>Data Fakultas</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>

<div class="max-w-7xl mx-auto space-y-6">
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Kelola Data Fakultas</h1>
      <p class="text-sm text-slate-500">Kelola fakultas, kode, status, dan jumlah prodi</p>
    </div>

    <button
      id="btnAddFakultas"
      data-modal-target="fakultasModal"
      data-title-add="Tambah Fakultas"
      data-store-url="{{ route('admin.fakultas.store') }}"
      class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
    >
      <span class="material-symbols-rounded text-base">add</span>
      Tambah Fakultas
    </button>
  </div>

  <form id="fakultasFilterForm" class="bg-white rounded-xl shadow p-4" method="GET">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status</option>
        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
      </select>

      <input type="text" name="q" placeholder="Cari kode atau nama fakultas..." value="{{ request('q') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
    </div>
  </form>

  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-100 text-slate-600">
        <tr>
          <th class="px-4 py-3 text-left">No</th>
          <th class="px-4 py-3 text-left">Kode</th>
          <th class="px-4 py-3 text-left">Nama Fakultas</th>
          <th class="px-4 py-3 text-center">Jumlah Prodi</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody id="fakultasTbody" class="divide-y">
        @foreach ($fakultasList as $fakultas)
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 font-mono text-slate-700">{{ $fakultas->kode }}</td>
            <td class="px-4 py-3 font-medium">{{ $fakultas->fakultas }}</td>
            <td class="px-4 py-3 text-center">{{ $fakultas->program_studis_count ?? 0 }}</td>
            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                {{ $fakultas->status }}
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <button
                  class="btn-preview p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700"
                  title="Detail"
                  data-kode="{{ $fakultas->kode }}"
                  data-nama="{{ $fakultas->fakultas }}"
                  data-status="{{ $fakultas->status }}"
                  data-prodi-count="{{ $fakultas->program_studis_count ?? 0 }}"
                  data-created="{{ $fakultas->created_at }}"
                >
                  <span class="material-symbols-rounded">visibility</span>
                </button>

                <button
                  class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                  title="Edit"
                  data-modal-target="fakultasModal"
                  data-title-edit="Edit Fakultas"
                  data-id="{{ $fakultas->id }}"
                  data-kode="{{ $fakultas->kode }}"
                  data-nama="{{ $fakultas->fakultas }}"
                  data-status="{{ $fakultas->status }}"
                  data-update-url="{{ url('/admin/fakultas/' . $fakultas->id) }}"
                >
                  <span class="material-symbols-rounded">edit</span>
                </button>

                <button
                  class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                  title="Hapus"
                  data-delete-url="{{ url('/admin/fakultas/' . $fakultas->id) }}"
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

<x-crud-modal id="fakultasModal" title="Tambah Fakultas">
  <input type="text" name="kode" id="kode" placeholder="Kode Fakultas" class="w-full rounded-lg border p-2" required>
  <input type="text" name="fakultas" id="fakultas" placeholder="Nama Fakultas" class="w-full rounded-lg border p-2" required>
  <select name="status" id="status" class="w-full rounded-lg border p-2">
    <option value="aktif">Aktif</option>
    <option value="nonaktif">Nonaktif</option>
  </select>
</x-crud-modal>

<div id="previewModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-slate-800">Detail Fakultas</h3>
      <button type="button" class="btn-close text-slate-400 hover:text-slate-600">X</button>
    </div>
    <div class="space-y-2 text-sm text-slate-700">
      <p><span class="font-semibold">Kode:</span> <span id="previewKode">-</span></p>
      <p><span class="font-semibold">Nama:</span> <span id="previewNama">-</span></p>
      <p><span class="font-semibold">Jumlah Prodi:</span> <span id="previewProdiCount">-</span></p>
      <p><span class="font-semibold">Status:</span> <span id="previewStatus">-</span></p>
      <p><span class="font-semibold">Tanggal Dibuat:</span> <span id="previewCreated">-</span></p>
    </div>
  </div>
</div>

<div id="deleteModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-sm p-6">
    <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Fakultas</h3>
    <div class="flex justify-end gap-2 mt-6">
      <button type="button" id="btnCancelDelete" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
      <button type="button" id="btnConfirmDelete" class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
    </div>
  </div>
</div>

<script>
  const fakultasFilterForm = document.getElementById('fakultasFilterForm');
  const fakultasTableBody = document.getElementById('fakultasTbody');
  const fakultasModal = document.getElementById('fakultasModal');
  const modalTitle = fakultasModal?.querySelector('.modal-title');
  const crudForm = fakultasModal?.querySelector('.crud-form');
  const crudMethod = fakultasModal?.querySelector('.crud-method');

  const buildQuery = () => {
    if (!fakultasFilterForm) return '';
    const formData = new FormData(fakultasFilterForm);
    const params = new URLSearchParams();
    formData.forEach((value, key) => {
      if (value !== null && String(value).trim() !== '') {
        params.set(key, String(value).trim());
      }
    });
    return params.toString();
  };

  const fetchFilteredFakultas = async () => {
    const query = buildQuery();
    const url = `${window.location.pathname}${query ? `?${query}` : ''}`;
    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const html = await res.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');
      const newTbody = doc.getElementById('fakultasTbody') || doc.querySelector('table tbody');
      if (newTbody && fakultasTableBody) {
        fakultasTableBody.innerHTML = newTbody.innerHTML;
        history.replaceState(null, '', url);
        bindFakultasActions();
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
    if (!fakultasFilterForm) return;
    fakultasFilterForm.querySelectorAll('select').forEach((el) => {
      el.addEventListener('change', fetchFilteredFakultas);
    });
    const searchInput = fakultasFilterForm.querySelector('input[name="q"]');
    if (searchInput) {
      searchInput.addEventListener('input', debounce(fetchFilteredFakultas, 350));
    }
    fakultasFilterForm.addEventListener('submit', (e) => {
      e.preventDefault();
      fetchFilteredFakultas();
    });
  };

  const openModal = () => fakultasModal?.classList.remove('hidden');
  const closeModal = () => fakultasModal?.classList.add('hidden');

  document.getElementById('btnAddFakultas')?.addEventListener('click', () => {
    if (!fakultasModal || !crudForm || !crudMethod || !modalTitle) return;
    modalTitle.textContent = document.getElementById('btnAddFakultas')?.dataset?.titleAdd || 'Tambah Fakultas';
    crudForm.action = document.getElementById('btnAddFakultas')?.dataset?.storeUrl || '';
    crudMethod.value = 'POST';
    crudForm.reset();
    openModal();
  });

  const bindFakultasActions = () => {
    document.querySelectorAll('.btn-preview').forEach((btn) => {
      btn.addEventListener('click', () => {
        const modal = document.getElementById('previewModal');
        if (!modal) return;
        document.getElementById('previewKode').textContent = btn.dataset.kode || '-';
        document.getElementById('previewNama').textContent = btn.dataset.nama || '-';
        document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
        document.getElementById('previewProdiCount').textContent = btn.dataset.prodiCount || '-';
        document.getElementById('previewCreated').textContent = btn.dataset.created || '-';
        modal.classList.remove('hidden');
      });
    });

    document.querySelectorAll('.btn-edit').forEach((btn) => {
      btn.addEventListener('click', () => {
        if (!fakultasModal || !crudForm || !crudMethod || !modalTitle) return;
        modalTitle.textContent = btn.dataset.titleEdit || 'Edit Fakultas';
        crudForm.action = btn.dataset.updateUrl || '';
        crudMethod.value = 'PUT';

        const setVal = (name, value) => {
          const input = fakultasModal.querySelector(`[name="${name}"]`);
          if (input) input.value = value ?? '';
        };
        setVal('kode', btn.dataset.kode);
        setVal('fakultas', btn.dataset.nama);
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

  fakultasModal?.querySelectorAll('.btn-close').forEach((btn) => {
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
  bindFakultasActions();
</script>

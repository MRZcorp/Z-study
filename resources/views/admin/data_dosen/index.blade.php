<x-header>Data Dosen</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>



<div class="max-w-7xl mx-auto space-y-6 p-6">

  <!-- HEADER -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>

    <h1 class="text-2xl font-bold text-slate-800">
      Kelola Data Dosen
    </h1>
    <p class="text-sm text-slate-500">
      Manajemen data dosen berdasarkan fakultas, program studi, dan status kepegawaian
    </p>    
  </div>
  
   <!-- BUTTON TAMBAH -->
   <button class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
   text-sm font-semibold text-white hover:bg-blue-700 whitespace-nowrap">
<span class="material-symbols-rounded text-base">person_add</span>
Tambah Dosen
</button>
</div>

  <!-- NAVBAR FILTER -->
  <form id="dosenFilterForm" class="bg-white rounded-xl shadow p-4" method="GET">
    <div class="flex flex-col md:flex-row md:items-center gap-4">

      <div class="grid grid-cols-1 md:grid-cols-5 gap-4 flex-1">

        <!-- FAKULTAS -->
        <select name="fakultas_id"
                data-prodi-target="filter_prodi_id_dosen"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Fakultas</option>
          @foreach(($fakultas ?? []) as $fakultasItem)
            <option value="{{ $fakultasItem->id }}" {{ (string) request('fakultas_id') === (string) $fakultasItem->id ? 'selected' : '' }}>
              {{ $fakultasItem->fakultas }}
            </option>
          @endforeach
        </select>

        <!-- PRODI -->
        <select id="filter_prodi_id_dosen"
                name="nama_prodi_id"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Prodi</option>
          @foreach(($prodis ?? []) as $prodi)
            <option value="{{ $prodi->id }}"
                    data-fakultas-id="{{ $prodi->fakultas_id }}"
                    {{ (string) request('nama_prodi_id') === (string) $prodi->id ? 'selected' : '' }}>
              {{ $prodi->nama_prodi }}
            </option>
          @endforeach
        </select>

        <!-- JABATAN -->
        <select name="jabatan" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Jabatan</option>
          <option value="Dosen Tetap" {{ request('jabatan') === 'Dosen Tetap' ? 'selected' : '' }}>Dosen Tetap</option>
          <option value="Dosen Tidak Tetap" {{ request('jabatan') === 'Dosen Tidak Tetap' ? 'selected' : '' }}>Dosen Tidak Tetap</option>
        </select>

        <!-- STATUS -->
        <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
          <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <!-- SEARCH -->
        <input type="text"
               name="q"
               placeholder="Cari nama atau NIDN..."
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
          <th class="px-4 py-3 text-left">Nama Dosen</th>
          <th class="px-4 py-3 text-left">NIDN</th>
          <th class="px-4 py-3 text-left">Fakultas</th>
          <th class="px-4 py-3 text-left">jabatan</th>
          <th class="px-4 py-3 text-left">Email</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>

      <tbody class="divide-y">

        <!-- ROW 1 -->
        @foreach ($dosens as $dosen)
            
        
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3">{{ $loop->iteration }}</td>
          <td class="px-4 py-3 font-medium">
            {{$dosen->name}}
          </td>
          <td class="px-4 py-3">
            {{$dosen->nidn ?? '-' }}
          </td>
          <td class="px-4 py-3">
            {{ $dosen->dosens?->fakultas?->fakultas ?? '-' }}
          </td>
          <td class="px-4 py-3">
            {{ $dosen->dosens?->status ?? 'Dosen Tetap' }}
          </td>
          <td class="px-4 py-3">
            {{$dosen->email}}
          </td>
          <td class="px-4 py-3 text-center">
            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700
                         text-xs font-semibold">
                         {{$dosen->status}}
            </span>
          </td>
          <td class="px-4 py-3">
            <div class="flex justify-center gap-2">
              <button
                class="btn-preview p-2 rounded-lg bg-slate-100 hover:bg-slate-200"
                title="Lihat"
                data-name="{{ $dosen->name }}"
                data-nidn="{{ $dosen->nidn ?? '' }}"
                data-email="{{ $dosen->email }}"
                data-role="{{ $dosen->role->nama_role ?? 'Dosen' }}"
                data-status="{{ $dosen->status ?? '-' }}"
                data-created="{{ $dosen->created_at }}"
              >
                <span class="material-symbols-rounded">visibility</span>
              </button>
              <button class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                      title="Edit">
                <span class="material-symbols-rounded">edit</span>
              </button>
              <button
                class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                title="Hapus"
                data-delete-url="{{ url('/admin/kelola_dosen/' . $dosen->id) }}"
              >
                <span class="material-symbols-rounded">delete</span>
              </button>
            </div>
          </td>
        </tr>
        @endforeach

        {{-- <!-- ROW 2 -->
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3">2</td>
          <td class="px-4 py-3 font-medium">
            Sri Lestari, M.Kom
          </td>
          <td class="px-4 py-3">
            0024089203
          </td>
          <td class="px-4 py-3">
            Fakultas Teknik
          </td>
          <td class="px-4 py-3">
            Dosen Tidak Tetap
        </td>
          <td class="px-4 py-3">
            sri@kampus.ac.id
          </td>
          <td class="px-4 py-3 text-center">
            <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-600
                         text-xs font-semibold">
              Nonaktif
            </span>
          </td>
          <td class="px-4 py-3">
            <div class="flex justify-center gap-2">
              <button class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200">
                <span class="material-symbols-rounded">visibility</span>
              </button>
              <button class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700">
                <span class="material-symbols-rounded">edit</span>
              </button>
              <button class="p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700">
                <span class="material-symbols-rounded">delete</span>
              </button>
            </div>
          </td>
        </tr> --}}

      </tbody>
    </table>
  </div>

</div>

<!-- MODAL PREVIEW -->
<div id="previewModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-slate-800">Detail Dosen</h3>
      <button type="button" class="btn-close text-slate-400 hover:text-slate-600">×</button>
    </div>
    <div class="space-y-2 text-sm text-slate-700">
      <p><span class="font-semibold">Nama:</span> <span id="previewName">-</span></p>
      <p><span class="font-semibold">NIDN/NIM:</span> <span id="previewId">-</span></p>
      <p><span class="font-semibold">Email:</span> <span id="previewEmail">-</span></p>
      <p><span class="font-semibold">Role:</span> <span id="previewRole">-</span></p>
      <p><span class="font-semibold">Status:</span> <span id="previewStatus">-</span></p>
      <p><span class="font-semibold">Tanggal Dibuat:</span> <span id="previewCreated">-</span></p>
    </div>
  </div>
</div>

<!-- MODAL SUCCESS -->
<div id="successModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-sm p-6">
    <h3 class="text-lg font-semibold text-slate-800 mb-2">Berhasil</h3>
    <p id="successMessage" class="text-sm text-slate-600"></p>
    <div class="flex justify-end mt-6">
      <button type="button" class="btn-close px-4 py-2 rounded-lg bg-blue-600 text-white">OK</button>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div id="deleteModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-sm p-6">
    <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Dosen</h3>
    <p class="text-sm text-slate-600">Yakin hapus dosen ini?</p>
    <div class="flex justify-end gap-2 mt-6">
      <button type="button" id="btnCancelDelete" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
      <button type="button" id="btnConfirmDelete" class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
    </div>
  </div>
</div>

<script>
  const dosenFilterForm = document.getElementById('dosenFilterForm');
  const dosenTableBody = document.querySelector('table tbody');

  const buildQuery = () => {
    if (!dosenFilterForm) return '';
    const formData = new FormData(dosenFilterForm);
    const params = new URLSearchParams();
    formData.forEach((value, key) => {
      if (value !== null && String(value).trim() !== '') {
        params.set(key, String(value).trim());
      }
    });
    return params.toString();
  };

  const fetchFilteredDosen = async () => {
    const query = buildQuery();
    const url = `${window.location.pathname}${query ? `?${query}` : ''}`;
    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const html = await res.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');
      const newTbody = doc.querySelector('table tbody');
      if (newTbody && dosenTableBody) {
        dosenTableBody.innerHTML = newTbody.innerHTML;
        history.replaceState(null, '', url);
        bindDosenActions();
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
    if (!dosenFilterForm) return;
    dosenFilterForm.querySelectorAll('select').forEach((el) => {
      el.addEventListener('change', fetchFilteredDosen);
    });
    const searchInput = dosenFilterForm.querySelector('input[name="q"]');
    if (searchInput) {
      searchInput.addEventListener('input', debounce(fetchFilteredDosen, 350));
    }
  };

  const bindDosenActions = () => {
    document.querySelectorAll('.btn-preview').forEach((btn) => {
      btn.addEventListener('click', () => {
        const modal = document.getElementById('previewModal');
        if (!modal) return;
        const nidn = btn.dataset.nidn || '';
        document.getElementById('previewName').textContent = btn.dataset.name || '-';
        document.getElementById('previewId').textContent = nidn || '-';
        document.getElementById('previewEmail').textContent = btn.dataset.email || '-';
        document.getElementById('previewRole').textContent = btn.dataset.role || '-';
        document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
        document.getElementById('previewCreated').textContent = btn.dataset.created || '-';
        modal.classList.remove('hidden');
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

  // Close modal when clicking outside
  document.querySelectorAll('.modal-overlay').forEach((modal) => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
      }
    });
  });

  // Success modal from session
  @if (session('success'))
    const successModal = document.getElementById('successModal');
    const successMessage = document.getElementById('successMessage');
    if (successModal && successMessage) {
      successMessage.textContent = @json(session('success'));
      successModal.classList.remove('hidden');
    }
  @endif

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
  bindDosenActions();
</script>

</body>
</html>

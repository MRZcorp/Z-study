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

        <!-- STATUS -->
        <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
          <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
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
          <th class="px-4 py-3 text-center">Angkatan</th>
         
          <th class="px-4 py-3 text-center">Status</th>
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
            {{$mhs->mahasiswa->angkatan?->tahun ?? '-' }}
          </td>
          {{-- <td class="px-4 py-3">
            {{$mhs->email}}
          </td> --}}
          <td class="px-4 py-3 text-center">
            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700
                         text-xs font-semibold">
            {{$mhs->mahasiswa->status ?? '-' }}
            </span>
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
                      data-status="{{ $mhs->mahasiswa->status ?? '-' }}"
                      data-created="{{ $mhs->created_at }}"
              >
                <span class="material-symbols-rounded">visibility</span>
              </button>
              <button
                      class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                      title="Edit"
                      data-modal-target="mahasiswaModal"
                      data-title-edit="Edit Mahasiswa"
                      data-id="{{ $mhs->mahasiswa->id ?? '' }}"
                      data-name="{{ $mhs->name }}"
                      data-email="{{ $mhs->mahasiswa->email ?? $mhs->email }}"
                      data-nim="{{ $mhs->mahasiswa->nim ?? '' }}"
                      data-fakultas-id="{{ $mhs->mahasiswa->fakultas_id ?? '' }}"
                      data-nama-prodi-id="{{ $mhs->mahasiswa->nama_prodi_id ?? '' }}"
                      data-angkatan-id="{{ $mhs->mahasiswa->angkatan_id ?? '' }}"
                      data-status="{{ $mhs->mahasiswa->status ?? '' }}"
                      data-update-url="{{ url('/admin/kelola_mahasiswa/' . ($mhs->mahasiswa->id ?? '')) }}">
                <span class="material-symbols-rounded">edit</span>
              </button>
              <button
                      class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                      title="Hapus"
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
    <input type="text" name="name" id="name" placeholder="Nama" class="w-full rounded-lg border p-2" required>

    <input type="email" name="email" id="email" placeholder="Email" class="w-full rounded-lg border p-2" required>

    <input type="text" name="nim" id="nim" placeholder="NIM" class="w-full rounded-lg border p-2" required>

    <select name="fakultas_id"
            id="fakultas_id"
            data-prodi-target="nama_prodi_id"
            class="w-full rounded-lg border p-2"
            required>
      <option value="">Pilih Fakultas</option>
    @foreach(($fakultas ?? []) as $fakultasItem)
      <option value="{{ $fakultasItem->id }}">{{ $fakultasItem->fakultas }}</option>
    @endforeach
    </select>

    <select name="nama_prodi_id" id="nama_prodi_id" class="w-full rounded-lg border p-2" required>
      <option value="">Pilih Prodi</option>
    @foreach(($prodis ?? []) as $prodi)
      <option value="{{ $prodi->id }}" data-fakultas-id="{{ $prodi->fakultas_id }}">{{ $prodi->nama_prodi }}</option>
    @endforeach
    </select>

    <select name="angkatan_id" id="angkatan_id" class="w-full rounded-lg border p-2" required>
      <option value="">Pilih Angkatan</option>
      @foreach(($angkatans ?? []) as $angkatan)
        <option value="{{ $angkatan->id }}">{{ $angkatan->tahun }}</option>
      @endforeach
    </select>

    <select name="status" id="status" class="w-full rounded-lg border p-2">
      <option value="aktif">Aktif</option>
      <option value="nonaktif">Nonaktif</option>
    </select>
  </x-crud-modal>

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
        <p><span class="font-semibold">Status:</span> <span id="previewStatus">-</span></p>
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

  <script>
    const mahasiswaFilterForm = document.getElementById('mahasiswaFilterForm');
    const mahasiswaTableBody = document.querySelector('table tbody');

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
    bindMahasiswaActions();
  </script>

</div>

</body>
</html>

<x-header>Data Jadwal Kelas</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>





  <div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">
          Kelola Jadwal Kuliah
        </h1>
        <p class="text-sm text-slate-500">
          Atur jadwal perkuliahan berdasarkan mata kuliah, kelas, dan waktu
        </p>
      </div>

      <!-- BUTTON TAMBAH -->
      <button
        id="btnAddJadwal"
        data-modal-target="jadwalModal"
        data-title-add="Tambah Jadwal"
        data-store-url="{{ route('admin.jadwal.store') }}"
        class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
                     text-sm font-semibold text-white hover:bg-blue-700">
        <span class="material-symbols-rounded text-base">add</span>
        Tambah Jadwal
      </button>
    </div>

    <!-- FILTER -->
    <form id="jadwalFilterForm" class="bg-white rounded-xl shadow p-4" method="GET">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

        <!-- HARI -->
        <select name="hari_kelas" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Hari</option>
          <option value="Senin" {{ request('hari_kelas') === 'Senin' ? 'selected' : '' }}>Senin</option>
          <option value="Selasa" {{ request('hari_kelas') === 'Selasa' ? 'selected' : '' }}>Selasa</option>
          <option value="Rabu" {{ request('hari_kelas') === 'Rabu' ? 'selected' : '' }}>Rabu</option>
          <option value="Kamis" {{ request('hari_kelas') === 'Kamis' ? 'selected' : '' }}>Kamis</option>
          <option value="Jumat" {{ request('hari_kelas') === 'Jumat' ? 'selected' : '' }}>Jumat</option>
          <option value="Sabtu" {{ request('hari_kelas') === 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
        </select>

        <!-- FAKULTAS -->
        <select id="fakultas_id" name="fakultas_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Fakultas</option>
          @foreach(($fakultasList ?? []) as $fakultas)
            <option value="{{ $fakultas->id }}" {{ (string) request('fakultas_id') === (string) $fakultas->id ? 'selected' : '' }}>
              {{ $fakultas->fakultas }}
            </option>
          @endforeach
        </select>

        <!-- PRODI -->
        <select id="prodi_id" name="prodi_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Prodi</option>
          @foreach(($prodis ?? []) as $prodi)
            <option value="{{ $prodi->id }}"
                    data-fakultas="{{ $prodi->fakultas_id }}"
                    {{ (string) request('prodi_id') === (string) $prodi->id ? 'selected' : '' }}>
              {{ $prodi->nama_prodi }}
            </option>
          @endforeach
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
               placeholder="Cari kelas atau ruangan..."
               value="{{ request('q') }}"
               class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                      focus:ring-2 focus:ring-blue-500">
      </div>
    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">No</th>
            <th class="px-4 py-3 text-left">Mata Kuliah</th>
            <th class="px-4 py-3 text-left">Kelas</th>
            <th class="px-4 py-3 text-left">Hari</th>
            <th class="px-4 py-3 text-center">Jam Mulai</th>
            <th class="px-4 py-3 text-center">Jam Selesai</th>
            <th class="px-4 py-3 text-left">Ruangan / Mode</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y">

          {{-- <!-- ROW 1 -->{{ $loop->iteration }} --}}
          @foreach ($jadwals as $kelas)
            <tr class="hover:bg-slate-50">
              <td class="px-4 py-3">{{ $loop->iteration }}</td>

              <td class="px-4 py-3 font-medium">
                {{ $kelas->mataKuliah->mata_kuliah ?? '-' }}
              </td>

              <td class="px-4 py-3 font-semibold">
                {{ $kelas->nama_kelas ?? '-' }}
              </td>

              <td class="px-4 py-3">
                {{ $kelas->hari_kelas ?? '-' }}
              </td>

              <td class="px-4 py-3 text-center">
                {{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }}
              </td>

              <td class="px-4 py-3 text-center">
                {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}
              </td>

              <td class="px-4 py-3">
                {{ $kelas->jadwal_kelas ?? '-' }}
              </td>

              <td class="px-4 py-3 text-center">
                <span class="px-3 py-1 rounded-full {{ ($kelas->status ?? 'aktif') === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}
                             text-xs font-semibold">
                  {{ $kelas->status ?? '-' }}
                </span>
              </td>

              <td class="px-4 py-3">
                <div class="flex justify-center gap-2">
                  <button
                    class="btn-preview p-2 rounded-lg bg-slate-100 hover:bg-slate-200"
                    title="Detail"
                    data-matkul="{{ $kelas->mataKuliah->mata_kuliah ?? '-' }}"
                    data-kelas="{{ $kelas->nama_kelas ?? '-' }}"
                    data-hari="{{ $kelas->hari_kelas ?? '-' }}"
                    data-jam-mulai="{{ $kelas->jam_mulai ?? '-' }}"
                    data-jam-selesai="{{ $kelas->jam_selesai ?? '-' }}"
                    data-ruang="{{ $kelas->jadwal_kelas ?? '-' }}"
                    data-status="{{ $kelas->status ?? '-' }}"
                    data-created="{{ $kelas->created_at }}"
                  >
                    <span class="material-symbols-rounded">visibility</span>
                  </button>

                  <button
                    class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                    title="Edit"
                    data-modal-target="jadwalModal"
                    data-title-edit="Edit Jadwal"
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
                    data-update-url="{{ url('/admin/kelola_jadwal/' . $kelas->id) }}"
                  >
                    <span class="material-symbols-rounded">edit</span>
                  </button>

                  <button
                    class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                    title="Hapus"
                    data-delete-url="{{ url('/admin/kelola_jadwal/' . $kelas->id) }}"
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
  <x-crud-modal id="jadwalModal" title="Tambah Jadwal">
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
    <input type="text" name="jadwal_kelas" id="jadwal_kelas" placeholder="Ruangan / Mode" class="w-full rounded-lg border p-2" required>

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
      <option value="aktif">Aktif</option>
      <option value="nonaktif">Nonaktif</option>
    </select>
  </x-crud-modal>

  <!-- MODAL PREVIEW -->
  <div id="previewModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-800">Detail Jadwal</h3>
        <button type="button" class="btn-close text-slate-400 hover:text-slate-600">×</button>
      </div>
      <div class="space-y-2 text-sm text-slate-700">
        <p><span class="font-semibold">Mata Kuliah:</span> <span id="previewMatkul">-</span></p>
        <p><span class="font-semibold">Kelas:</span> <span id="previewKelas">-</span></p>
        <p><span class="font-semibold">Hari:</span> <span id="previewHari">-</span></p>
        <p><span class="font-semibold">Jam Mulai:</span> <span id="previewJamMulai">-</span></p>
        <p><span class="font-semibold">Jam Selesai:</span> <span id="previewJamSelesai">-</span></p>
        <p><span class="font-semibold">Ruangan/Mode:</span> <span id="previewRuang">-</span></p>
        <p><span class="font-semibold">Status:</span> <span id="previewStatus">-</span></p>
        <p><span class="font-semibold">Tanggal Dibuat:</span> <span id="previewCreated">-</span></p>
      </div>
    </div>
  </div>

  <!-- MODAL KONFIRMASI HAPUS -->
  <div id="deleteModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6">
      <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Jadwal</h3>
      <div class="flex justify-end gap-2 mt-6">
        <button type="button" id="btnCancelDelete" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
        <button type="button" id="btnConfirmDelete" class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
      </div>
    </div>
  </div>

  <script>
    const jadwalFilterForm = document.getElementById('jadwalFilterForm');
    const jadwalTableBody = document.querySelector('table tbody');
    const fakultasSelect = document.getElementById('fakultas_id');
    const prodiSelect = document.getElementById('prodi_id');

    const buildQuery = () => {
      if (!jadwalFilterForm) return '';
      const formData = new FormData(jadwalFilterForm);
      const params = new URLSearchParams();
      formData.forEach((value, key) => {
        if (value !== null && String(value).trim() !== '') {
          params.set(key, String(value).trim());
        }
      });
      return params.toString();
    };

    const fetchFilteredJadwal = async () => {
      const query = buildQuery();
      const url = `${window.location.pathname}${query ? `?${query}` : ''}`;
      try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const html = await res.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const newTbody = doc.querySelector('table tbody');
        if (newTbody && jadwalTableBody) {
          jadwalTableBody.innerHTML = newTbody.innerHTML;
          history.replaceState(null, '', url);
          bindJadwalActions();
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
      if (!jadwalFilterForm) return;
      jadwalFilterForm.querySelectorAll('select').forEach((el) => {
        el.addEventListener('change', fetchFilteredJadwal);
      });
      const searchInput = jadwalFilterForm.querySelector('input[name="q"]');
      if (searchInput) {
        searchInput.addEventListener('input', debounce(fetchFilteredJadwal, 350));
      }
    };

    const filterProdiByFakultas = () => {
      if (!fakultasSelect || !prodiSelect) return;
      const selectedFakultas = fakultasSelect.value;
      const options = Array.from(prodiSelect.options);
      let hasSelected = false;
      options.forEach((opt) => {
        if (!opt.value) {
          opt.hidden = false;
          return;
        }
        const fakultasId = opt.dataset.fakultas || '';
        const show = !selectedFakultas || String(fakultasId) === String(selectedFakultas);
        opt.hidden = !show;
        if (opt.selected && show) hasSelected = true;
      });
      if (!hasSelected) {
        prodiSelect.value = '';
      }
    };


    const jadwalModal = document.getElementById('jadwalModal');
    const modalTitle = jadwalModal?.querySelector('.modal-title');
    const crudForm = jadwalModal?.querySelector('.crud-form');
    const crudMethod = jadwalModal?.querySelector('.crud-method');

    const openModal = () => jadwalModal?.classList.remove('hidden');
    const closeModal = () => jadwalModal?.classList.add('hidden');

    document.getElementById('btnAddJadwal')?.addEventListener('click', () => {
      if (!jadwalModal || !crudForm || !crudMethod || !modalTitle) return;
      modalTitle.textContent = document.getElementById('btnAddJadwal')?.dataset?.titleAdd || 'Tambah Jadwal';
      crudForm.action = document.getElementById('btnAddJadwal')?.dataset?.storeUrl || '';
      crudMethod.value = 'POST';
      crudForm.reset();
      openModal();
    });

    const bindJadwalActions = () => {
      document.querySelectorAll('.btn-preview').forEach((btn) => {
        btn.addEventListener('click', () => {
          const modal = document.getElementById('previewModal');
          if (!modal) return;
          document.getElementById('previewMatkul').textContent = btn.dataset.matkul || '-';
          document.getElementById('previewKelas').textContent = btn.dataset.kelas || '-';
          document.getElementById('previewHari').textContent = btn.dataset.hari || '-';
          document.getElementById('previewJamMulai').textContent = btn.dataset.jamMulai || '-';
          document.getElementById('previewJamSelesai').textContent = btn.dataset.jamSelesai || '-';
          document.getElementById('previewRuang').textContent = btn.dataset.ruang || '-';
          document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
          document.getElementById('previewCreated').textContent = btn.dataset.created || '-';
          modal.classList.remove('hidden');
        });
      });

      document.querySelectorAll('.btn-edit').forEach((btn) => {
        btn.addEventListener('click', () => {
          if (!jadwalModal || !crudForm || !crudMethod || !modalTitle) return;
          modalTitle.textContent = btn.dataset.titleEdit || 'Edit Jadwal';
          crudForm.action = btn.dataset.updateUrl || '';
          crudMethod.value = 'PUT';

          const setVal = (name, value) => {
            const input = jadwalModal.querySelector(`[name="${name}"]`);
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

    jadwalModal?.querySelectorAll('.btn-close').forEach((btn) => {
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

    fakultasSelect?.addEventListener('change', filterProdiByFakultas);
    filterProdiByFakultas();
    bindFilterEvents();
    bindJadwalActions();
  </script>

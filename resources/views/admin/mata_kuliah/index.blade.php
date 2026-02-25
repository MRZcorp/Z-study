<x-header>Data Mata Kuliah</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>

  <div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">
          Kelola Data Mata Kuliah
        </h1>
        <p class="text-sm text-slate-500">
          Kelola mata kuliah, jurusan, SKS, dan status aktif
        </p>
      </div>

      <!-- BUTTON TAMBAH -->
      <button
        id="btnAddMatkul"
        data-modal-target="matkulModal"
        data-title-add="Tambah Mata Kuliah"
        data-store-url="{{ route('admin.mata_kuliah.store') }}"
        class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
                     text-sm font-semibold text-white hover:bg-blue-700">
        <span class="material-symbols-rounded text-base">add</span>
        Tambah Mata Kuliah
      </button>
    </div>

    <!-- FILTER -->
    <form id="matkulFilterForm" class="bg-white rounded-xl shadow p-4" method="GET">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <!-- JURUSAN / PRODI -->
        <select name="prodi_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Jurusan / Prodi</option>
          @foreach(($prodis ?? []) as $prodi)
            <option value="{{ $prodi->id }}" {{ (string) request('prodi_id') === (string) $prodi->id ? 'selected' : '' }}>
              {{ $prodi->nama_prodi }}
            </option>
          @endforeach
        </select>

        <!-- STATUS -->
        <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
          <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <!-- SEMESTER -->
        <select name="semester" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Semester</option>
          <option value="ganjil" {{ request('semester') === 'ganjil' ? 'selected' : '' }}>Ganjil</option>
          <option value="genap" {{ request('semester') === 'genap' ? 'selected' : '' }}>Genap</option>
        </select>

        <!-- SEARCH -->
        <input type="text"
               name="q"
               placeholder="Cari kode atau nama mata kuliah..."
               value="{{ request('q') }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                      focus:ring-2 focus:ring-blue-500">
      </div>
    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">No</th>
            <th class="px-4 py-3 text-left">Kode</th>
            <th class="px-4 py-3 text-left">Nama Mata Kuliah</th>
            <th class="px-4 py-3 text-center">SKS</th>
            <th class="px-4 py-3 text-center">Semester</th>
            <th class="px-4 py-3 text-left">Fakultas</th>
            <th class="px-4 py-3 text-left">Jurusan / Prodi</th>
            <th class="px-4 py-3 text-center">Jumlah Kelas</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody id="matkulTbody" class="divide-y">

          <!-- ROW -->
          @foreach ($matkuls as $matkul)
              
          
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>

            <td class="px-4 py-3 font-mono text-slate-700">
              {{$matkul->kode_mata_kuliah}}
            </td>

            <td class="px-4 py-3 font-medium truncate max-w-xs">
              {{$matkul->mata_kuliah}}
            </td>

            <td class="px-4 py-3 text-center">
              {{$matkul->sks}}
            </td>
            <td class="px-4 py-3 text-center">
              {{ ucfirst($matkul->semester ?? '-') }}
            </td>

            @php
              $fakultasList = $matkul->programStudis
                ->map(fn($p) => $p->fakultas?->fakultas)
                ->filter()
                ->unique()
                ->values();
              $prodiList = $matkul->programStudis
                ->pluck('nama_prodi')
                ->filter()
                ->unique()
                ->values();
              $fakultasText = $fakultasList->count() > 2 ? 'Semua Fakultas' : ($fakultasList->join(', ') ?: '-');
              $prodiText = $prodiList->count() > 2 ? 'Semua Prodi' : ($prodiList->join(', ') ?: '-');
              $fakultasFullText = $fakultasList->join(', ') ?: '-';
              $prodiFullText = $prodiList->join(', ') ?: '-';
            @endphp
            <td class="px-4 py-3 text-slate-600">
              {{ $fakultasText }}
            </td>
            <td class="px-4 py-3 text-slate-600">
              {{ $prodiText }}
            </td>

            <td class="px-4 py-3 text-center">
              {{ $matkul->kelas_count ?? 0 }}
            </td>

            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-green-100 text-green-700
                           text-xs font-semibold">
                           {{$matkul->status}}
              </span>
            </td>

            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">

                <button
                  class="btn-preview p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700"
                  title="Detail"
                  data-kode="{{ $matkul->kode_mata_kuliah }}"
                  data-nama="{{ $matkul->mata_kuliah }}"
                  data-sks="{{ $matkul->sks }}"
                  data-semester="{{ $matkul->semester }}"
                  data-fakultas="{{ $fakultasFullText }}"
                  data-prodi="{{ $prodiFullText }}"
                  data-status="{{ $matkul->status }}"
                  data-created="{{ $matkul->created_at }}"
                >
                  <span class="material-symbols-rounded">visibility</span>
                </button>

                <button
                  class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                  title="Edit"
                  data-modal-target="matkulModal"
                  data-title-edit="Edit Mata Kuliah"
                  data-id="{{ $matkul->id }}"
                  data-kode="{{ $matkul->kode_mata_kuliah }}"
                  data-nama="{{ $matkul->mata_kuliah }}"
                  data-sks="{{ $matkul->sks }}"
                  data-semester="{{ $matkul->semester }}"
                  data-status="{{ $matkul->status }}"
                  data-prodis='@json($matkul->programStudis->pluck("id"))'
                  data-update-url="{{ url('/admin/kelola_mata_kuliah/' . $matkul->id) }}"
                >
                  <span class="material-symbols-rounded">edit</span>
                </button>

                <button
                  class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                  title="Hapus"
                  data-delete-url="{{ url('/admin/kelola_mata_kuliah/' . $matkul->id) }}"
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
  <x-crud-modal id="matkulModal" title="Tambah Mata Kuliah">
    <input type="text" name="kode_mata_kuliah" id="kode_mata_kuliah" placeholder="Kode Mata Kuliah" class="w-full rounded-lg border p-2" required>
    <input type="text" name="mata_kuliah" id="mata_kuliah" placeholder="Nama Mata Kuliah" class="w-full rounded-lg border p-2" required>
    <select name="semester" id="semester" class="w-full rounded-lg border p-2" required>
      <option value="">Pilih Semester</option>
      <option value="ganjil">Ganjil</option>
      <option value="genap">Genap</option>
    </select>
    <input type="number" name="sks" id="sks" placeholder="SKS" class="w-full rounded-lg border p-2" required>

    <div class="rounded-lg border p-3 space-y-3">
      <p class="text-sm font-semibold text-slate-700">Program Studi</p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <select id="modal_fakultas_filter" class="w-full rounded-lg border p-2 text-sm">
          <option value="">Semua Fakultas</option>
          @foreach(($fakultas ?? []) as $f)
            <option value="{{ $f->id }}">{{ $f->fakultas }}</option>
          @endforeach
        </select>
        <select id="modal_prodi_picker" class="w-full rounded-lg border p-2 text-sm md:col-span-2">
          <option value="">Pilih Prodi</option>
          <option value="__ALL__">Semua Prodi</option>
          @foreach(($prodis ?? []) as $prodi)
            <option value="{{ $prodi->id }}" data-fakultas-id="{{ $prodi->fakultas_id }}">{{ $prodi->nama_prodi }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex items-center justify-between gap-2">
        <p class="text-xs text-slate-500">Klik tambah prodi untuk memasukkan ke daftar terpilih.</p>
        <button type="button" id="btnAddProdi" class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
          <span class="material-symbols-rounded text-sm">add</span>
          Tambah Prodi
        </button>
      </div>
      <div id="selectedProdiList" class="flex flex-wrap gap-2"></div>
      <select name="prodi_ids[]" id="prodi_ids" class="hidden" multiple>
        @foreach(($prodis ?? []) as $prodi)
          <option value="{{ $prodi->id }}" data-fakultas-id="{{ $prodi->fakultas_id }}">{{ $prodi->nama_prodi }}</option>
        @endforeach
      </select>
    </div>

    <select name="status" id="status" class="w-full rounded-lg border p-2">
      <option value="aktif">Aktif</option>
      <option value="nonaktif">Nonaktif</option>
    </select>
  </x-crud-modal>

  <!-- MODAL PREVIEW -->
  <div id="previewModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-800">Detail Mata Kuliah</h3>
        <button type="button" class="btn-close text-slate-400 hover:text-slate-600">×</button>
      </div>
      <div class="space-y-2 text-sm text-slate-700">
        <p><span class="font-semibold">Kode:</span> <span id="previewKode">-</span></p>
        <p><span class="font-semibold">Nama:</span> <span id="previewNama">-</span></p>
        <p><span class="font-semibold">SKS:</span> <span id="previewSks">-</span></p>
        <p><span class="font-semibold">Semester:</span> <span id="previewSemester">-</span></p>
        <p><span class="font-semibold">Fakultas:</span> <span id="previewFakultas">-</span></p>
        <p><span class="font-semibold">Prodi:</span> <span id="previewProdi">-</span></p>
        <p><span class="font-semibold">Status:</span> <span id="previewStatus">-</span></p>
        <p><span class="font-semibold">Tanggal Dibuat:</span> <span id="previewCreated">-</span></p>
      </div>
    </div>
  </div>

  <!-- MODAL KONFIRMASI HAPUS -->
  <div id="deleteModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6">
      <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Mata Kuliah</h3>
      <div class="flex justify-end gap-2 mt-6">
        <button type="button" id="btnCancelDelete" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
        <button type="button" id="btnConfirmDelete" class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
      </div>
    </div>
  </div>

  <script>
    const matkulFilterForm = document.getElementById('matkulFilterForm');
    const matkulTableBody = document.getElementById('matkulTbody');

    const buildQuery = () => {
      if (!matkulFilterForm) return '';
      const formData = new FormData(matkulFilterForm);
      const params = new URLSearchParams();
      formData.forEach((value, key) => {
        if (value !== null && String(value).trim() !== '') {
          params.set(key, String(value).trim());
        }
      });
      return params.toString();
    };

    const fetchFilteredMatkul = async () => {
      const query = buildQuery();
      const url = `${window.location.pathname}${query ? `?${query}` : ''}`;
      try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const html = await res.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const newTbody = doc.getElementById('matkulTbody') || doc.querySelector('table tbody');
        if (newTbody && matkulTableBody) {
          matkulTableBody.innerHTML = newTbody.innerHTML;
          history.replaceState(null, '', url);
          bindMatkulActions();
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
      if (!matkulFilterForm) return;
      matkulFilterForm.querySelectorAll('select').forEach((el) => {
        el.addEventListener('change', fetchFilteredMatkul);
      });
      const searchInput = matkulFilterForm.querySelector('input[name="q"]');
      if (searchInput) {
        searchInput.addEventListener('input', debounce(fetchFilteredMatkul, 350));
      }
      matkulFilterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchFilteredMatkul();
      });
    };

    const matkulModal = document.getElementById('matkulModal');
    const modalTitle = matkulModal?.querySelector('.modal-title');
    const crudForm = matkulModal?.querySelector('.crud-form');
    const crudMethod = matkulModal?.querySelector('.crud-method');
    const prodiSelect = matkulModal?.querySelector('#prodi_ids');
    const modalFakultasFilter = matkulModal?.querySelector('#modal_fakultas_filter');
    const modalProdiPicker = matkulModal?.querySelector('#modal_prodi_picker');
    const selectedProdiList = matkulModal?.querySelector('#selectedProdiList');
    const btnAddProdi = matkulModal?.querySelector('#btnAddProdi');

    const openModal = () => matkulModal?.classList.remove('hidden');
    const closeModal = () => matkulModal?.classList.add('hidden');

    document.getElementById('btnAddMatkul')?.addEventListener('click', () => {
      if (!matkulModal || !crudForm || !crudMethod || !modalTitle) return;
      modalTitle.textContent = document.getElementById('btnAddMatkul')?.dataset?.titleAdd || 'Tambah Mata Kuliah';
      crudForm.action = document.getElementById('btnAddMatkul')?.dataset?.storeUrl || '';
      crudMethod.value = 'POST';
      crudForm.reset();
      if (modalFakultasFilter) modalFakultasFilter.value = '';
      if (modalProdiPicker) modalProdiPicker.value = '';
      setSelectedProdiIds([]);
      syncProdiPickerVisibility();
      openModal();
    });

    const setMultiSelect = (selectEl, values) => {
      if (!selectEl) return;
      const set = new Set(values.map(String));
      Array.from(selectEl.options).forEach((opt) => {
        opt.selected = set.has(String(opt.value));
      });
      renderSelectedProdiTags();
    };

    const getSelectedProdiIds = () => {
      if (!prodiSelect) return [];
      return Array.from(prodiSelect.options)
        .filter((opt) => opt.selected)
        .map((opt) => String(opt.value));
    };

    const setSelectedProdiIds = (ids) => {
      if (!prodiSelect) return;
      const selectedSet = new Set((ids || []).map(String));
      Array.from(prodiSelect.options).forEach((opt) => {
        opt.selected = selectedSet.has(String(opt.value));
      });
      renderSelectedProdiTags();
    };

    const renderSelectedProdiTags = () => {
      if (!selectedProdiList || !prodiSelect) return;
      selectedProdiList.innerHTML = '';
      const selectedOptions = Array.from(prodiSelect.options).filter((opt) => opt.selected);
      if (!selectedOptions.length) {
        const empty = document.createElement('span');
        empty.className = 'text-xs text-slate-400';
        empty.textContent = 'Belum ada prodi dipilih.';
        selectedProdiList.appendChild(empty);
        return;
      }
      selectedOptions.forEach((opt) => {
        const badge = document.createElement('span');
        badge.className = 'inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-700';
        badge.textContent = opt.textContent || '-';

        const btnRemove = document.createElement('button');
        btnRemove.type = 'button';
        btnRemove.className = 'text-slate-500 hover:text-red-600';
        btnRemove.textContent = 'x';
        btnRemove.addEventListener('click', () => {
          opt.selected = false;
          renderSelectedProdiTags();
        });

        badge.appendChild(btnRemove);
        selectedProdiList.appendChild(badge);
      });
    };

    const syncProdiPickerVisibility = () => {
      if (!modalProdiPicker) return;
      const selectedFakultas = String(modalFakultasFilter?.value || '');
      Array.from(modalProdiPicker.options).forEach((opt) => {
        if (!opt.value || opt.value === '__ALL__') {
          opt.hidden = false;
          return;
        }
        const fid = String(opt.dataset.fakultasId || '');
        opt.hidden = selectedFakultas !== '' && fid !== selectedFakultas;
      });
      if (modalProdiPicker.selectedOptions[0]?.hidden) {
        modalProdiPicker.value = '';
      }
    };

    const bindMatkulActions = () => {
      document.querySelectorAll('.btn-preview').forEach((btn) => {
        btn.addEventListener('click', () => {
          const modal = document.getElementById('previewModal');
          if (!modal) return;
          document.getElementById('previewKode').textContent = btn.dataset.kode || '-';
          document.getElementById('previewNama').textContent = btn.dataset.nama || '-';
          document.getElementById('previewSks').textContent = btn.dataset.sks || '-';
          document.getElementById('previewSemester').textContent = btn.dataset.semester || '-';
          document.getElementById('previewFakultas').textContent = btn.dataset.fakultas || '-';
          document.getElementById('previewProdi').textContent = btn.dataset.prodi || '-';
          document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
          document.getElementById('previewCreated').textContent = btn.dataset.created || '-';
          modal.classList.remove('hidden');
        });
      });

      document.querySelectorAll('.btn-edit').forEach((btn) => {
        btn.addEventListener('click', () => {
          if (!matkulModal || !crudForm || !crudMethod || !modalTitle) return;
          modalTitle.textContent = btn.dataset.titleEdit || 'Edit Mata Kuliah';
          crudForm.action = btn.dataset.updateUrl || '';
          crudMethod.value = 'PUT';

          const setVal = (name, value) => {
            const input = matkulModal.querySelector(`[name="${name}"]`);
            if (input) input.value = value ?? '';
          };
          setVal('kode_mata_kuliah', btn.dataset.kode);
          setVal('mata_kuliah', btn.dataset.nama);
          setVal('semester', btn.dataset.semester);
          setVal('sks', btn.dataset.sks);
          setVal('status', btn.dataset.status);
          const prodis = JSON.parse(btn.dataset.prodis || '[]');
          setMultiSelect(prodiSelect, prodis);
          if (modalFakultasFilter) modalFakultasFilter.value = '';
          if (modalProdiPicker) modalProdiPicker.value = '';
          syncProdiPickerVisibility();

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

    matkulModal?.querySelectorAll('.btn-close').forEach((btn) => {
      btn.addEventListener('click', closeModal);
    });

    modalFakultasFilter?.addEventListener('change', syncProdiPickerVisibility);
    btnAddProdi?.addEventListener('click', () => {
      if (!modalProdiPicker || !prodiSelect) return;
      const selected = modalProdiPicker.value || '';
      if (!selected) return;

      const currentIds = getSelectedProdiIds();
      const merged = new Set(currentIds);

      if (selected === '__ALL__') {
        Array.from(modalProdiPicker.options).forEach((opt) => {
          if (!opt.value || opt.value === '__ALL__' || opt.hidden) return;
          merged.add(String(opt.value));
        });
      } else {
        const targetOpt = Array.from(modalProdiPicker.options).find((opt) => String(opt.value) === String(selected) && !opt.hidden);
        if (targetOpt) merged.add(String(targetOpt.value));
      }

      setSelectedProdiIds(Array.from(merged));
      modalProdiPicker.value = '';
    });

    syncProdiPickerVisibility();
    renderSelectedProdiTags();

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
    const previewModal = document.getElementById('previewModal');
    document.getElementById('btnCancelDelete')?.addEventListener('click', () => {
      deleteModal?.classList.add('hidden');
    });
    previewModal?.querySelectorAll('.btn-close').forEach((btn) => {
      btn.addEventListener('click', () => {
        previewModal.classList.add('hidden');
      });
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
    bindMatkulActions();
  </script>

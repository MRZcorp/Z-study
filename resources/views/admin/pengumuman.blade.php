<x-header>Pengumuman</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>



  <div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">
          Kelola Pengumuman
        </h1>
        <p class="text-sm text-slate-500">
          Kelola, terbitkan, dan arsipkan pengumuman untuk pengguna
        </p>
      </div>

      <!-- BUTTON TAMBAH -->
      <button
        id="btnAddPengumuman"
        data-modal-target="pengumumanModal"
        data-title-add="Tambah Pengumuman"
        data-store-url="{{ route('admin.pengumuman.store') }}"
        class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
                     text-sm font-semibold text-white hover:bg-blue-700">
        <span class="material-symbols-rounded text-base">add</span>
        Tambah Pengumuman
      </button>
    </div>

    <!-- FILTER BAR -->
    <form id="pengumumanFilterForm" class="bg-white rounded-xl shadow p-4" method="GET">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

        <!-- STATUS -->
        <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
          <option value="publish" {{ request('status') === 'publish' ? 'selected' : '' }}>Publish</option>
        </select>

        <!-- TIPE -->
        <select name="tipe" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Tipe</option>
          <option value="info" {{ request('tipe') === 'info' ? 'selected' : '' }}>Info</option>
          <option value="event" {{ request('tipe') === 'event' ? 'selected' : '' }}>Event</option>
          <option value="peringatan" {{ request('tipe') === 'peringatan' ? 'selected' : '' }}>Peringatan</option>
        </select>

        <!-- BULAN -->
        <input type="month"
               name="bulan"
               value="{{ request('bulan') }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

        <!-- TAHUN -->
        <input type="number" name="tahun" placeholder="Tahun"
               value="{{ request('tahun') }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

        <!-- SEARCH -->
        <input type="text" name="q"
               placeholder="Cari judul pengumuman..."
               value="{{ request('q') }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
      </div>
    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">No</th>
            <th class="px-4 py-3 text-left">Judul Pengumuman</th>
            <th class="px-4 py-3 text-center">Tipe</th>
            <th class="px-4 py-3 text-left">Tanggal Publish</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y">
          {{-- {{ $loop->iteration }} --}}
          <!-- ROW -->
          @foreach ($pengumumans as $p)
            @php
              $tipe = strtolower($p->tipe ?? '');
              $tipeClass = match($tipe) {
                'info' => 'bg-blue-100 text-blue-700',
                'event' => 'bg-green-100 text-green-700',
                'peringatan' => 'bg-yellow-100 text-yellow-700',
                default => 'bg-slate-100 text-slate-700',
              };
              $statusLabel = $p->is_active ? 'Publish' : 'Draft';
              $statusClass = $p->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600';
            @endphp
            <tr class="hover:bg-slate-50">
              <td class="px-4 py-3">{{ $loop->iteration }}</td>

              <td class="px-4 py-3 font-medium truncate max-w-xs">
                {{ $p->judul }}
              </td>

              <td class="px-4 py-3 text-center">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $tipeClass }}">
                  {{ ucfirst($tipe) }}
                </span>
              </td>

              <td class="px-4 py-3 text-slate-500">
                @if ($p->tanggal_publish)
                  {{ \Illuminate\Support\Str::lower(\Carbon\Carbon::parse($p->tanggal_publish)->locale('id')->translatedFormat('d F Y')) }}
                @else
                  -
                @endif
              </td>

              <td class="px-4 py-3 text-center">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                  {{ $statusLabel }}
                </span>
              </td>

              <td class="px-4 py-3">
                <div class="flex justify-center gap-2">

                  <button
                    class="btn-preview p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700"
                    title="Detail"
                    data-judul="{{ $p->judul }}"
                    data-isi="{{ $p->isi }}"
                    data-tipe="{{ $p->tipe }}"
                    data-status="{{ $statusLabel }}"
                    data-berkas="{{ $p->file_name ?? '-' }}"
                    data-berkas-url="{{ $p->file_path ? asset('storage/' . $p->file_path) : '' }}"
                    data-tanggal-display="{{ $p->tanggal_publish ? \Illuminate\Support\Str::lower(\Carbon\Carbon::parse($p->tanggal_publish)->locale('id')->translatedFormat('d F Y')) : '-' }}"
                    data-tanggal-raw="{{ $p->tanggal_publish ?? '' }}"
                    data-created="{{ $p->created_at }}"
                  >
                    <span class="material-symbols-rounded text-base">visibility</span>
                  </button>

                  <button
                    class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                    title="Edit"
                    data-modal-target="pengumumanModal"
                    data-title-edit="Edit Pengumuman"
                    data-id="{{ $p->id }}"
                    data-judul="{{ $p->judul }}"
                    data-isi="{{ $p->isi }}"
                    data-tipe="{{ $p->tipe }}"
                    data-status="{{ $statusLabel === 'Publish' ? 'publish' : 'draft' }}"
                    data-tanggal="{{ $p->tanggal_publish }}"
                    data-berkas="{{ $p->file_name ?? '' }}"
                    data-update-url="{{ url('/admin/pengumuman/' . $p->id) }}"
                  >
                    <span class="material-symbols-rounded text-base">edit</span>
                  </button>

                  <button
                    class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                    title="Hapus"
                    data-delete-url="{{ url('/admin/pengumuman/' . $p->id) }}"
                  >
                    <span class="material-symbols-rounded text-base">delete</span>
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
  <x-crud-modal id="pengumumanModal" title="Tambah Pengumuman">
    <input type="text" name="judul" id="judul" placeholder="Judul" class="w-full rounded-lg border p-2" required>
    <textarea name="isi" id="isi" rows="4" placeholder="Isi pengumuman" class="w-full rounded-lg border p-2" required></textarea>

    <div class="space-y-2">
      <input type="file" name="berkas" id="berkas" class="w-full rounded-lg border p-2">
      <p id="berkasInfo" class="text-xs text-slate-500 hidden"></p>
    </div>

    <select name="tipe" id="tipe" class="w-full rounded-lg border p-2" required>
      <option value="info">Info</option>
      <option value="event">Event</option>
      <option value="peringatan">Peringatan</option>
    </select>

    <select name="status" id="status" class="w-full rounded-lg border p-2" required>
      <option value="publish">Publish</option>
      <option value="draft">Draft</option>
    </select>

    <input type="date" name="tanggal_publish" id="tanggal_publish" class="w-full rounded-lg border p-2">
  </x-crud-modal>

      <!-- MODAL PREVIEW -->
  <div id="previewModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden" style="width:70vw; max-width:1100px; height:75vh;">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <div>
          <h3 class="text-lg font-semibold text-slate-800">Detail Pengumuman</h3>
          <p id="previewSubTitle" class="text-sm text-slate-500">-</p>
        </div>
        <div class="flex items-center gap-2">
          <a id="previewDownload" href="#" target="_blank" class="rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
            <span class="material-symbols-rounded text-base">download</span>
          </a>
          <button type="button" class="btn-close text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
        </div>
      </div>

    <div class="grid grid-cols-20 gap-4 p-5" style="height:calc(75vh - 64px);">
      <div class="col-span-15 h-full">
        <div id="previewContainer" class="w-full h-full rounded-xl border bg-slate-50 flex items-center justify-center text-sm text-slate-500">
          Tidak ada file.
        </div>
      </div>
      <div class="col-span-5 flex flex-col gap-3 h-full text-sm text-slate-700 break-words">
          <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700">
              <span class="material-symbols-rounded text-sm">event</span>
              <span id="previewTanggal">-</span>
            </span>
          </div>
          <div>
            <p class="text-xs text-slate-500">Deskripsi</p>
            <p id="previewIsi">-</p>
          </div>
          <div class="mt-auto">
            <p class="text-xs text-slate-500">File</p>
            <div id="previewFileList" class="mt-1 flex flex-col gap-1 text-sm text-blue-700 max-h-40 overflow-y-auto pr-1"></div>
          </div>
          <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
            <span id="previewTipeBadge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
              <span class="material-symbols-rounded text-sm">flag</span>
              <span id="previewTipe">-</span>
            </span>
            <span id="previewStatusBadge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
              <span class="material-symbols-rounded text-sm">task_alt</span>
              <span id="previewStatus">-</span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL KONFIRMASI HAPUS -->
  <div id="deleteModal" class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6">
      <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Pengumuman</h3>
      <div class="flex justify-end gap-2 mt-6">
        <button type="button" id="btnCancelDelete" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
        <button type="button" id="btnConfirmDelete" class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
      </div>
    </div>
  </div>

  <script>
    const pengumumanFilterForm = document.getElementById('pengumumanFilterForm');
    const pengumumanTableBody = document.querySelector('table tbody');

    const buildQuery = () => {
      if (!pengumumanFilterForm) return '';
      const formData = new FormData(pengumumanFilterForm);
      const params = new URLSearchParams();
      formData.forEach((value, key) => {
        if (value !== null && String(value).trim() !== '') {
          params.set(key, String(value).trim());
        }
      });
      return params.toString();
    };

    const fetchFilteredPengumuman = async () => {
      const query = buildQuery();
      const url = `${window.location.pathname}${query ? `?${query}` : ''}`;
      try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const html = await res.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const newTbody = doc.querySelector('table tbody');
        if (newTbody && pengumumanTableBody) {
          pengumumanTableBody.innerHTML = newTbody.innerHTML;
          history.replaceState(null, '', url);
          bindPengumumanActions();
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
      if (!pengumumanFilterForm) return;
      pengumumanFilterForm.querySelectorAll('select, input[type="month"], input[type="number"]').forEach((el) => {
        el.addEventListener('change', fetchFilteredPengumuman);
      });
      const searchInput = pengumumanFilterForm.querySelector('input[name="q"]');
      if (searchInput) {
        searchInput.addEventListener('input', debounce(fetchFilteredPengumuman, 350));
      }
    };

    const pengumumanModal = document.getElementById('pengumumanModal');
    const modalTitle = pengumumanModal?.querySelector('.modal-title');
    const crudForm = pengumumanModal?.querySelector('.crud-form');
    const crudMethod = pengumumanModal?.querySelector('.crud-method');
    const berkasInput = pengumumanModal?.querySelector('#berkas');
    const berkasInfo = pengumumanModal?.querySelector('#berkasInfo');

    const openModal = () => pengumumanModal?.classList.remove('hidden');
    const closeModal = () => pengumumanModal?.classList.add('hidden');

    document.getElementById('btnAddPengumuman')?.addEventListener('click', () => {
      if (!pengumumanModal || !crudForm || !crudMethod || !modalTitle) return;
      modalTitle.textContent = document.getElementById('btnAddPengumuman')?.dataset?.titleAdd || 'Tambah Pengumuman';
      crudForm.action = document.getElementById('btnAddPengumuman')?.dataset?.storeUrl || '';
      crudMethod.value = 'POST';
      crudForm.reset();
      if (berkasInfo) {
        berkasInfo.textContent = '';
        berkasInfo.classList.add('hidden');
      }
      openModal();
    });

    const bindPengumumanActions = () => {
      const previewModal = document.getElementById('previewModal');
      const previewContainer = document.getElementById('previewContainer');
      const previewDownload = document.getElementById('previewDownload');
      const previewFileList = document.getElementById('previewFileList');
      const previewSubTitle = document.getElementById('previewSubTitle');

      const renderPreview = (url, ext) => {
        const lower = (ext || '').toLowerCase();
        if (!previewContainer) return;
        if (!url) {
          previewContainer.innerHTML = 'Tidak ada file.';
          return;
        }

        if (['mp4', 'webm', 'ogg'].includes(lower)) {
          previewContainer.innerHTML = `<video src="${url}" controls class="w-full h-full rounded-xl bg-black"></video>`;
          return;
        }

        if (['pdf'].includes(lower)) {
          previewContainer.innerHTML = `<iframe src="${url}" class="w-full h-full rounded-xl"></iframe>`;
          return;
        }

        if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(lower)) {
          previewContainer.innerHTML = `<div class="text-center text-slate-500 text-sm">Preview tidak tersedia untuk file ini. Silakan download.</div>`;
          return;
        }

        previewContainer.innerHTML = `<iframe src="${url}" class="w-full h-full rounded-xl"></iframe>`;
      };

      const setActiveFile = (file) => {
        if (!file) {
          renderPreview('', '');
          if (previewDownload) previewDownload.href = '#';
          return;
        }
        renderPreview(file.url, file.ext);
        if (previewDownload) previewDownload.href = file.url || '#';
      };

      document.querySelectorAll('.btn-preview').forEach((btn) => {
        btn.addEventListener('click', () => {
          if (!previewModal) return;
          const berkasName = btn.dataset.berkas || '';
          const berkasUrl = btn.dataset.berkasUrl || '';
          const cleanUrl = berkasUrl ? berkasUrl.split('?')[0].toLowerCase() : '';
          const ext = cleanUrl ? cleanUrl.split('.').pop() : '';
          const files = berkasUrl
            ? [{ name: berkasName || 'Berkas', url: berkasUrl, ext }]
            : [];

          const tipeValue = (btn.dataset.tipe || '-').toLowerCase();
          document.getElementById('previewTipe').textContent = btn.dataset.tipe || '-';
          const statusValue = (btn.dataset.status || '-').toLowerCase();
          document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
          document.getElementById('previewTanggal').textContent = btn.dataset.tanggalDisplay || '-';
          document.getElementById('previewIsi').textContent = btn.dataset.isi || '-';
          if (previewSubTitle) {
            previewSubTitle.textContent = btn.dataset.judul || '-';
          }

          const tipeBadge = document.getElementById('previewTipeBadge');
          if (tipeBadge) {
            tipeBadge.classList.remove('bg-blue-100', 'text-blue-700', 'bg-green-100', 'text-green-700', 'bg-yellow-100', 'text-yellow-700', 'bg-slate-100', 'text-slate-700', 'text-slate-600');
            if (tipeValue === 'info') {
              tipeBadge.classList.add('bg-blue-100', 'text-blue-700');
            } else if (tipeValue === 'event') {
              tipeBadge.classList.add('bg-green-100', 'text-green-700');
            } else if (tipeValue === 'peringatan') {
              tipeBadge.classList.add('bg-yellow-100', 'text-yellow-700');
            } else {
              tipeBadge.classList.add('bg-slate-100', 'text-slate-700');
            }
          }

          const statusBadge = document.getElementById('previewStatusBadge');
          if (statusBadge) {
            statusBadge.classList.remove('bg-green-100', 'text-green-700', 'bg-slate-200', 'text-slate-600', 'bg-slate-100');
            if (statusValue === 'publish') {
              statusBadge.classList.add('bg-green-100', 'text-green-700');
            } else if (statusValue === 'draft') {
              statusBadge.classList.add('bg-slate-200', 'text-slate-600');
            } else {
              statusBadge.classList.add('bg-slate-100', 'text-slate-600');
            }
          }

          // previewCreated removed

          if (previewFileList) {
            previewFileList.innerHTML = '';
            if (files.length === 0) {
              previewFileList.innerHTML = '<span class="text-slate-400 text-sm">Tidak ada file.</span>';
              setActiveFile(null);
            } else {
              files.forEach((file, idx) => {
                const btnFile = document.createElement('button');
                btnFile.type = 'button';
                btnFile.className = 'text-left hover:underline';
                btnFile.textContent = file.name || `File ${idx + 1}`;
                btnFile.addEventListener('click', () => setActiveFile(file));
                previewFileList.appendChild(btnFile);
              });
              setActiveFile(files[0]);
            }
          }

          previewModal.classList.remove('hidden');
          previewModal.classList.add('flex');
        });
      });

      document.querySelectorAll('.btn-edit').forEach((btn) => {
        btn.addEventListener('click', () => {
          if (!pengumumanModal || !crudForm || !crudMethod || !modalTitle) return;
          modalTitle.textContent = btn.dataset.titleEdit || 'Edit Pengumuman';
          crudForm.action = btn.dataset.updateUrl || '';
          crudMethod.value = 'PUT';

          const setVal = (name, value) => {
            const input = pengumumanModal.querySelector(`[name="${name}"]`);
            if (input) input.value = value ?? '';
          };
          setVal('judul', btn.dataset.judul);
          setVal('isi', btn.dataset.isi);
          setVal('tipe', btn.dataset.tipe);
          setVal('status', btn.dataset.status);
          setVal('tanggal_publish', btn.dataset.tanggalRaw);
          if (berkasInput) berkasInput.value = '';
          if (berkasInfo) {
            const name = btn.dataset.berkas || '';
            if (name) {
              berkasInfo.textContent = `Berkas saat ini: ${name}`;
              berkasInfo.classList.remove('hidden');
            } else {
              berkasInfo.textContent = '';
              berkasInfo.classList.add('hidden');
            }
          }

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

    pengumumanModal?.querySelectorAll('.btn-close').forEach((btn) => {
      btn.addEventListener('click', closeModal);
    });

    const previewModalEl = document.getElementById('previewModal');
    const closePreview = () => {
      previewModalEl?.classList.add('hidden');
      previewModalEl?.classList.remove('flex');
      const container = document.getElementById('previewContainer');
      const fileList = document.getElementById('previewFileList');
      const downloadBtn = document.getElementById('previewDownload');
      if (container) container.innerHTML = 'Tidak ada file.';
      if (fileList) fileList.innerHTML = '';
      if (downloadBtn) downloadBtn.href = '#';
    };

    previewModalEl?.querySelectorAll('.btn-close').forEach((btn) => {
      btn.addEventListener('click', closePreview);
    });

    previewModalEl?.addEventListener('click', (e) => {
      if (e.target === previewModalEl) closePreview();
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
    bindPengumumanActions();
  </script>

</div>

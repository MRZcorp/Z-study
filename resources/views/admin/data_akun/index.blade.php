<x-header>Kelola Pengguna</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>

  <!-- CONTAINER -->
  <div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">
          Kelola Akun Pengguna
        </h1>
        <p class="text-sm text-slate-500">
          Kelola data pengguna, role, dan status akun
        </p>
      </div>

   <!-- BUTTON TAMBAH -->
          <button
          id="btnAddUser"
          data-modal-target="userModal"
          data-title-add="Tambah User"
          data-store-url="{{ route('user_setting.store') }}"
          class="btn-add flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
          text-sm font-semibold text-white hover:bg-blue-700"
          >
          <span class="material-symbols-rounded text-base">add</span>
          Tambah Akun
          </button>



</div>
    <!-- FILTER -->
    <form id="userFilterForm" class="bg-white rounded-xl shadow p-4" method="GET">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- ROLE -->
        <select name="role" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Role</option>
          @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ (string) request('role') === (string) $role->id ? 'selected' : '' }}>
              {{ $role->nama_role }}
            </option>
          @endforeach
        </select>

        <!-- STATUS -->
        <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
          <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <!-- SEARCH -->
        <input
          name="q"
          type="text"
          placeholder="Cari nama atau email..."
          value="{{ request('q') }}"
          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
        >
      </div>
    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">No</th>
            <th class="px-4 py-3 text-left">Nama</th>
            <th class="px-4 py-3 text-left">NIDN / NIM</th>
            <th class="px-4 py-3 text-left">Email</th>
            <th class="px-4 py-3 text-center">Role</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y">

          <!-- ROW -->
          @foreach ($users as $user)
          
          <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 font-medium">
                <div class="max-w-[200px] truncate">
                {{ $user->name }}
                </div>
                </td>
                <td class="px-4 py-3 text-slate-600">
                  <div class="max-w-[200px] truncate">
                    {{ $user->nidn ?? $user->nim ?? '-' }}
                  </div>
                </td>
                <td class="px-4 py-3 text-slate-600">
                    <div class="max-w-[200px] truncate">
                    {{ $user->email}}
                    </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      @php
                        $role = strtolower($user->role->nama_role ?? '');
                        
                        $roleClass = match($role) {
                          'admin' => 'bg-green-100 text-green-700',
                          'dosen' => 'bg-blue-100 text-blue-700',
                          'mahasiswa' => 'bg-purple-100 text-purple-700',
                          default => 'bg-slate-100 text-slate-700',
                        };
                      @endphp
                    
                      <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $roleClass }}">
                        {{ $user->role->nama_role ?? '-' }}
                      </span>
                    </td>
                    

            <!-- STATUS -->
            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                {{$user->status ?? '-' }}
              </span>
            </td>

            <!-- ACTION -->
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <button
                  class="btn-preview p-2 rounded-lg bg-slate-100 hover:bg-slate-200"
                  data-name="{{ $user->name }}"
                  data-nidn="{{ $user->nidn ?? '' }}"
                  data-nim="{{ $user->nim ?? '' }}"
                  data-email="{{ $user->email }}"
                  data-role="{{ $user->role->nama_role ?? '-' }}"
                  data-status="{{ $user->status ?? '-' }}"
                  data-created="{{ $user->created_at }}"
                >
                  <span class="material-symbols-rounded">visibility</span>
                </button>

                <!-- EDIT -->

           

                <button 
                class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                data-modal-target="userModal"
                data-title-edit="Edit User"
                data-id="{{ $user->id }}"
                data-name="{{ $user->name }}"
                data-email="{{ $user->email }}"
                data-role-id="{{ $user->role_id }}"
                data-status="{{ $user->status }}"
                data-update-url="{{ url('/admin/user_setting/' . $user->id) }}"
                >
                <span class="material-symbols-rounded">edit</span>
                </button>

                


                <!-- DELETE -->
<button 
  class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
  data-id="{{ $user->id }}"
  data-delete-url="{{ url('/admin/user_setting/' . $user->id) }}"
>
  <span class="material-symbols-rounded">delete</span>
</button>
                
              </div>
            </td>
          </tr>
          @endforeach

         
          <!-- MODAL -->
<x-crud-modal id="userModal" title="Tambah User">
  <input type="text" name="name" id="name" placeholder="Nama" class="w-full rounded-lg border p-2" required>

  <input type="email" name="email" id="email" placeholder="Email" class="w-full rounded-lg border p-2" required>

  <div class="relative">
    <input type="password" name="password" id="password" placeholder="Password" value="123" class="w-full rounded-lg border p-2 pr-10">
    <button type="button" class="btn-toggle-password absolute right-2 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700" aria-label="Lihat password" data-target="password">
      <span class="material-symbols-rounded text-lg">visibility</span>
    </button>
  </div>

  <select name="role_id" id="role_id" class="w-full rounded-lg border p-2" required>
    <option value="">Pilih Role</option>
    @foreach($roles as $role)
      <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
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
      <h3 class="text-lg font-semibold text-slate-800">Detail User</h3>
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
    <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus User</h3>
    <p class="text-sm text-slate-600">Yakin hapus user ini?</p>
    <div class="flex justify-end gap-2 mt-6">
      <button type="button" id="btnCancelDelete" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
      <button type="button" id="btnConfirmDelete" class="px-4 py-2 rounded-lg bg-red-600 text-white">Hapus</button>
    </div>
  </div>
</div>

          
          

  
          

        </tbody>
      </table>
    </div>
  </div>

<script>
  const userFilterForm = document.getElementById('userFilterForm');
  const usersTableBody = document.querySelector('table tbody');

  const buildQuery = () => {
    if (!userFilterForm) return '';
    const formData = new FormData(userFilterForm);
    const params = new URLSearchParams();
    formData.forEach((value, key) => {
      if (value !== null && String(value).trim() !== '') {
        params.set(key, String(value).trim());
      }
    });
    return params.toString();
  };

  const fetchFilteredUsers = async () => {
    const query = buildQuery();
    const url = `${window.location.pathname}${query ? `?${query}` : ''}`;
    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const html = await res.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');
      const newTbody = doc.querySelector('table tbody');
      if (newTbody && usersTableBody) {
        usersTableBody.innerHTML = newTbody.innerHTML;
        history.replaceState(null, '', url);
        bindUserActions();
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
    if (!userFilterForm) return;
    userFilterForm.querySelectorAll('select').forEach((el) => {
      el.addEventListener('change', fetchFilteredUsers);
    });
    const searchInput = userFilterForm.querySelector('input[name="q"]');
    if (searchInput) {
      searchInput.addEventListener('input', debounce(fetchFilteredUsers, 350));
    }
  };

  const bindUserActions = () => {
    document.querySelectorAll('.btn-preview').forEach((btn) => {
      btn.addEventListener('click', () => {
        const modal = document.getElementById('previewModal');
        if (!modal) return;
        const nidn = btn.dataset.nidn || '';
        const nim = btn.dataset.nim || '';
        document.getElementById('previewName').textContent = btn.dataset.name || '-';
        document.getElementById('previewId').textContent = nidn || nim || '-';
        document.getElementById('previewEmail').textContent = btn.dataset.email || '-';
        document.getElementById('previewRole').textContent = btn.dataset.role || '-';
        document.getElementById('previewStatus').textContent = btn.dataset.status || '-';
        document.getElementById('previewCreated').textContent = btn.dataset.created || '-';
        modal.classList.remove('hidden');
      });
    });

    document.querySelectorAll('.btn-edit').forEach((btn) => {
      btn.addEventListener('click', () => {
        if (!userModal || !crudForm || !crudMethod || !modalTitle) return;
        modalTitle.textContent = btn.dataset.titleEdit || 'Edit User';
        crudForm.action = btn.dataset.updateUrl || '';
        crudMethod.value = 'PUT';

        const setVal = (name, value) => {
          const input = userModal.querySelector(`[name="${name}"]`);
          if (input) input.value = value ?? '';
        };
        setVal('name', btn.dataset.name);
        setVal('email', btn.dataset.email);
      setVal('password', '123');
      setVal('role_id', btn.dataset.roleId);
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

  const userModal = document.getElementById('userModal');
  const modalTitle = userModal?.querySelector('.modal-title');
  const crudForm = userModal?.querySelector('.crud-form');
  const crudMethod = userModal?.querySelector('.crud-method');

  const openModal = () => {
    userModal?.classList.remove('hidden');
  };
  const closeModal = () => {
    userModal?.classList.add('hidden');
  };

  document.getElementById('btnAddUser')?.addEventListener('click', () => {
    if (!userModal || !crudForm || !crudMethod || !modalTitle) return;
    modalTitle.textContent = document.getElementById('btnAddUser')?.dataset?.titleAdd || 'Tambah User';
    crudForm.action = document.getElementById('btnAddUser')?.dataset?.storeUrl || '';
    crudMethod.value = 'POST';
    crudForm.reset();
    openModal();
  });

  userModal?.querySelectorAll('.btn-close').forEach((btn) => {
    btn.addEventListener('click', closeModal);
  });

  bindFilterEvents();
  bindUserActions();

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

  document.querySelectorAll('.btn-toggle-password').forEach((btn) => {
    btn.addEventListener('click', () => {
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      if (!input) return;
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.innerHTML = `<span class="material-symbols-rounded text-lg">${isHidden ? 'visibility_off' : 'visibility'}</span>`;
    });
  });
</script>

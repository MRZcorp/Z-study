<x-header>Kelola Pengguna</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>
<meta name="csrf-token" content="{{ csrf_token() }}">

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
class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
text-sm font-semibold text-white hover:bg-blue-700"
>
<span class="material-symbols-rounded text-base">add</span>
Tambah Akun
</button>



</div>
    <!-- FILTER -->
    <div class="bg-white rounded-xl shadow p-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- ROLE -->
        <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Role</option>
          <option>Admin</option>
          <option>Dosen</option>
          <option>Mahasiswa</option>
        </select>

        <!-- STATUS -->
        <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option>Aktif</option>
          <option>Nonaktif</option>
        </select>

        <!-- SEARCH -->
        <input
          type="text"
          placeholder="Cari nama atau email..."
          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
        >
      </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">No</th>
            <th class="px-4 py-3 text-left">Nama</th>
            <th class="px-4 py-3 text-left">Email</th>
            <th class="px-4 py-3 text-center">Role</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-left">Tanggal Dibuat</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y">

          <!-- ROW -->
          @foreach ($users as $user)
          
          <tr class="hover:bg-slate-50">
          <td class="px-4 py-3">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 font-medium">
                <div class="max-w-[200px] truncate">
                {{ $user->name }}
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

            <td class="px-4 py-3 text-slate-500">
              {{$user->created_at}}
            </td>

            <!-- ACTION -->
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <button class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200">
                  <span class="material-symbols-rounded">visibility</span>
                </button>

                <!-- EDIT -->

                
<button 
class="btn-edit p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
data-id="{{ $user->id }}"
data-name="{{ $user->name }}"
data-email="{{ $user->email }}"
data-role="{{ $user->role_id }}"
data-status="{{ $user->status }}"
>
<span class="material-symbols-rounded">edit</span>
</button>

                
<!-- DELETE -->
<button 
  class="btn-delete p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
  data-id="{{ $user->id }}"
>
  <span class="material-symbols-rounded">delete</span>
</button>
                
              </div>
            </td>
          </tr>
          @endforeach

         
          
          


       
          @include('admin.data_akun.edit')
       
          <script>
            const modal = document.getElementById('userModal');
            const btnAdd = document.getElementById('btnAddUser');
            const btnClose = document.getElementById('btnClose');
            const form = document.getElementById('userForm');
            const method = document.getElementById('method');
            
            
            btnAdd.addEventListener('click', () => {
            modal.classList.remove('hidden');
            form.action = "{{ route('user_setting.store') }}";
            method.value = "POST";
            document.getElementById('modalTitle').innerText = "Tambah User";
            form.reset();
            });
            
            
            btnClose.addEventListener('click', () => {
            modal.classList.add('hidden');
            });
            
            
            // EDIT
            
            
            document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            
            
            const id = btn.dataset.id;
            
            
            document.getElementById('name').value = btn.dataset.name;
            document.getElementById('email').value = btn.dataset.email;
            document.getElementById('role').value = btn.dataset.role;
            document.getElementById('status').value = btn.dataset.status;
            //edit data disini  urlnya
            form.action = `/admin/user_setting/${id}`;
            method.value = "PUT";
            
            
            
            document.getElementById('modalTitle').innerText = "Edit User";
            });
            });
            
            
            // DELETE
            
            
            document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', () => {
            if(confirm('Yakin hapus user ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/user_setting/${btn.dataset.id}`;
            //ini url delete
           
            
            
            form.innerHTML = `
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            `;
            
            
            document.body.appendChild(form);
            form.submit();
            }
            });
            });
            </script>
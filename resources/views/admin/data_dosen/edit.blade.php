<!-- MODAL -->
<div id="userModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-md p-6">
  
  
  <h2 id="modalTitle" class="text-lg font-bold mb-4">Tambah User</h2>
  
  
  <form id="userForm" method="POST">
  @csrf
  <input type="hidden" name="_method" id="method">
  
  
  <div class="space-y-4">
  <input type="text" name="name" id="name" placeholder="Nama" class="w-full rounded-lg border p-2" required>
  
  
  <input type="email" name="email" id="email" placeholder="Email" class="w-full rounded-lg border p-2" required>
  
  
  <select name="role_id" id="role" class="w-full rounded-lg border p-2" required>
  <option value="">Pilih Role</option>
  @foreach($roles as $role)
  <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
  @endforeach
  </select>
  
  
  <select name="status" id="status" class="w-full rounded-lg border p-2">
  <option value="aktif">Aktif</option>
  <option value="nonaktif">Nonaktif</option>
  </select>
  </div>
  
  
  <div class="flex justify-end gap-2 mt-6">
  <button type="button" id="btnClose" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
  <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Simpan</button>
  </div>
  </form>
  
  
  </div>
  </div>
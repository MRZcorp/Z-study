<div id="userModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
      <h2 id="modalTitle" class="text-lg font-semibold mb-4">Tambah User</h2>
  
      <form id="userForm">
        <input type="hidden" id="user_id">
  
        <div class="mb-3">
          <label class="text-sm font-medium">Nama</label>
          <input type="text" id="name" class="w-full mt-1 border rounded-lg px-3 py-2">
        </div>
  
        <div class="mb-3">
          <label class="text-sm font-medium">Email</label>
          <input type="email" id="email" class="w-full mt-1 border rounded-lg px-3 py-2">
        </div>
  
        <div class="mb-4">
          <label class="text-sm font-medium">Role</label>
          <select id="role" class="w-full mt-1 border rounded-lg px-3 py-2">
            <option value="admin">Admin</option>
            <option value="dosen">Dosen</option>
            <option value="mahasiswa">Mahasiswa</option>
          </select>
        </div>
  
        <div class="flex justify-end gap-2">
          <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-lg">
            Batal
          </button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

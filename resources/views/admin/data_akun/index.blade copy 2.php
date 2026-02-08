
          <!-- MODAL -->
<div id="{{ $id }}" 
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-md p-6">
  
  
  <h2 id="modalTitle" class="text-lg font-bold mb-4">{{ $title }}</h2>
  
  
  <form id="userForm" method="POST">
  @csrf
  
  <input type="hidden" name="_method" id="method">
  
  
  <div class="space-y-4">
  {{ $slot }}
  </div>
  
  
  <div class="flex justify-end gap-2 mt-6">
  <button type="button" id="btnClose" class="px-4 py-2 rounded-lg bg-slate-200">Batal</button>
  <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">Simpan</button>
  </div>
  </form>
  </div>
  </div>


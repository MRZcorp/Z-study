{{-- modal delete --}}

<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-sm">
      <h2 class="text-lg font-semibold mb-3">Hapus User?</h2>
      <p class="text-sm text-gray-600 mb-4">Data tidak bisa dikembalikan.</p>
  
      <div class="flex justify-end gap-2">
        

        <button type="button"
id="btnCloseEdit"
class="px-4 py-2 bg-gray-200 rounded-lg">
Batal
</button>

        <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-lg">
          Hapus
        </button>
      </div>
    </div>
  </div>
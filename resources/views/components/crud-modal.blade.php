<div id="{{ $id }}"
  class="modal-overlay hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

  <div class="bg-white rounded-xl w-full max-w-md p-6">

    <h2 class="modal-title text-lg font-bold mb-4">
      {{ $title }}
    </h2>

    <form class="crud-form" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" class="crud-method" value="POST">

      <div class="space-y-4">
        {{ $slot }}
      </div>

      <div class="flex justify-end gap-2 mt-6">
        <button type="button" class="btn-close px-4 py-2 rounded-lg bg-slate-200">
          Batal
        </button>
        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white">
          Simpan
        </button>
      </div>
    </form>

  </div>
</div>

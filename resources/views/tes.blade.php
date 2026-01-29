<x-header></x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

<!-- OVERLAY -->
<div id="pesertaModal"
     class="fixed inset-0 z-50 hidden items-center justify-center
            bg-black/50 backdrop-blur-sm px-4">

  <!-- MODAL BOX -->
  <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl
              animate-scaleIn">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <h3 class="text-lg font-semibold text-gray-800">
        Peserta Kelas
      </h3>

      <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
        ✕
      </button>
    </div>

    <!-- CONTENT -->
    <div class="p-5 space-y-6 max-h-[70vh] overflow-y-auto">

      <!-- DOSEN -->
      <div>
        <p class="text-xs font-semibold text-gray-400 uppercase mb-3">
          Dosen Pengampu
        </p>

        <div class="flex items-center gap-4 p-3 rounded-xl
                    bg-gray-50 border">
          <img src="/img/dosen.jpg"
               class="w-11 h-11 rounded-full object-cover">
          <div>
            <p class="font-semibold text-gray-800">
              Dr. Andi Wijaya, M.Kom
            </p>
            <p class="text-xs text-gray-500">
              Host
            </p>
          </div>
        </div>
      </div>

      <!-- MAHASISWA -->
      <div>
        <p class="text-xs font-semibold text-gray-400 uppercase mb-3">
          Mahasiswa Peserta
        </p>

        <div class="space-y-2">

          <div class="flex items-center gap-3 p-3 rounded-xl
                      hover:bg-gray-100 transition">
            <img src="/img/user1.jpg"
                 class="w-9 h-9 rounded-full object-cover">
            <span class="text-sm text-gray-800">
              M. Zaky Nugraha A R
            </span>
          </div>

          <div class="flex items-center gap-3 p-3 rounded-xl
                      hover:bg-gray-100 transition">
            <img src="/img/user2.jpg"
                 class="w-9 h-9 rounded-full object-cover">
            <span class="text-sm text-gray-800">
              Aulia Rahman
            </span>
          </div>

          <div class="flex items-center gap-3 p-3 rounded-xl
                      hover:bg-gray-100 transition">
            <img src="/img/user3.jpg"
                 class="w-9 h-9 rounded-full object-cover">
            <span class="text-sm text-gray-800">
              Siti Nurhaliza
            </span>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

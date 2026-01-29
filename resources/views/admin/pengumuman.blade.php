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
      <button class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
                     text-sm font-semibold text-white hover:bg-blue-700">
        <span class="material-symbols-rounded text-base">add</span>
        Tambah Pengumuman
      </button>
    </div>

    <!-- FILTER BAR -->
    <div class="bg-white rounded-xl shadow p-4">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

        <!-- STATUS -->
        <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="draft">Draft</option>
          <option value="publish">Publish</option>
        </select>

        <!-- TIPE -->
        <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Tipe</option>
          <option value="info">Info</option>
          <option value="event">Event</option>
          <option value="peringatan">Peringatan</option>
        </select>

        <!-- BULAN -->
        <input type="month"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

        <!-- TAHUN -->
        <input type="number" placeholder="Tahun"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

        <!-- SEARCH -->
        <input type="text"
               placeholder="Cari judul pengumuman..."
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
      </div>
    </div>

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

          <!-- ROW -->
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>

            <td class="px-4 py-3 font-medium truncate max-w-xs">
              Libur Nasional Hari Raya Idul Fitri
            </td>

            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                Info
              </span>
            </td>

            <td class="px-4 py-3 text-slate-500">
              20 Maret 2026
            </td>

            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                Publish
              </span>
            </td>

            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">

                <button class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700"
                        title="Detail">
                  <span class="material-symbols-rounded text-base">visibility</span>
                </button>

                <button class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                        title="Edit">
                  <span class="material-symbols-rounded text-base">edit</span>
                </button>

                <button class="p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                        title="Hapus">
                  <span class="material-symbols-rounded text-base">delete</span>
                </button>

              </div>
            </td>
          </tr>

          <!-- ROW -->
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">2</td>

            <td class="px-4 py-3 font-medium truncate max-w-xs">
              Pengumpulan Laporan Akhir Semester
            </td>

            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                Peringatan
              </span>
            </td>

            <td class="px-4 py-3 text-slate-500">
              -
            </td>

            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-600 text-xs font-semibold">
                Draft
              </span>
            </td>

            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">

                <button class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700">
                  <span class="material-symbols-rounded">visibility</span>
                </button>

                <button class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700">
                  <span class="material-symbols-rounded">edit</span>
                </button>

                <button class="p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700">
                  <span class="material-symbols-rounded">delete</span>
                </button>

              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

  </div>

</body>
</html>
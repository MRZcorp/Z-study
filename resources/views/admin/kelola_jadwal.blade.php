<x-header>Data Jadwal Kelas</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>





  <div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">
          Kelola Jadwal Kuliah
        </h1>
        <p class="text-sm text-slate-500">
          Atur jadwal perkuliahan berdasarkan mata kuliah, kelas, dan waktu
        </p>
      </div>

      <!-- BUTTON TAMBAH -->
      <button class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
                     text-sm font-semibold text-white hover:bg-blue-700">
        <span class="material-symbols-rounded text-base">add</span>
        Tambah Jadwal
      </button>
    </div>

    <!-- FILTER -->
    <div class="bg-white rounded-xl shadow p-4">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

        <!-- HARI -->
        <select class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Hari</option>
          <option>Senin</option>
          <option>Selasa</option>
          <option>Rabu</option>
          <option>Kamis</option>
          <option>Jumat</option>
          <option>Sabtu</option>
        </select>

        <!-- MATA KULIAH -->
        <select class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Mata Kuliah</option>
          <option>Pemrograman Dasar</option>
          <option>Basis Data</option>
          <option>Rekayasa Perangkat Lunak</option>
        </select>

        <!-- DOSEN -->
        <select class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Dosen</option>
          <option>Dr. Andi Wijaya</option>
          <option>Sri Lestari, M.Kom</option>
        </select>

        <!-- STATUS -->
        <select class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="aktif">Aktif</option>
          <option value="nonaktif">Nonaktif</option>
        </select>

        <!-- SEARCH -->
        <input type="text"
               placeholder="Cari kelas atau ruangan..."
               class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                      focus:ring-2 focus:ring-blue-500">
      </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">No</th>
            <th class="px-4 py-3 text-left">Mata Kuliah</th>
            <th class="px-4 py-3 text-left">Kelas</th>
            <th class="px-4 py-3 text-left">Hari</th>
            <th class="px-4 py-3 text-center">Jam Mulai</th>
            <th class="px-4 py-3 text-center">Jam Selesai</th>
            <th class="px-4 py-3 text-left">Ruangan / Mode</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y">

          <!-- ROW 1 -->
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>

            <td class="px-4 py-3 font-medium">
              Pemrograman Dasar
            </td>

            <td class="px-4 py-3 font-semibold">
              Kelas A
            </td>

            <td class="px-4 py-3">
              Senin
            </td>

            <td class="px-4 py-3 text-center">
              08:00
            </td>

            <td class="px-4 py-3 text-center">
              09:40
            </td>

            <td class="px-4 py-3">
              Lab Komputer 1
            </td>

            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-green-100 text-green-700
                           text-xs font-semibold">
                Aktif
              </span>
            </td>

            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <button class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200"
                        title="Detail">
                  <span class="material-symbols-rounded">visibility</span>
                </button>

                <button class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                        title="Edit">
                  <span class="material-symbols-rounded">edit</span>
                </button>

                <button class="p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                        title="Hapus">
                  <span class="material-symbols-rounded">delete</span>
                </button>
              </div>
            </td>
          </tr>

          <!-- ROW 2 -->
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">2</td>

            <td class="px-4 py-3 font-medium">
              Basis Data
            </td>

            <td class="px-4 py-3 font-semibold">
              Kelas B
            </td>

            <td class="px-4 py-3">
              Rabu
            </td>

            <td class="px-4 py-3 text-center">
              13:00
            </td>

            <td class="px-4 py-3 text-center">
              14:40
            </td>

            <td class="px-4 py-3">
              Online (Zoom)
            </td>

            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-600
                           text-xs font-semibold">
                Nonaktif
              </span>
            </td>

            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <button class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200">
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
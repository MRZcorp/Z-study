<x-header>Data Dosen</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>



<div class="max-w-7xl mx-auto space-y-6 p-6">

  <!-- HEADER -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>

    <h1 class="text-2xl font-bold text-slate-800">
      Kelola Data Dosen
    </h1>
    <p class="text-sm text-slate-500">
      Manajemen data dosen berdasarkan fakultas, program studi, dan status kepegawaian
    </p>    
  </div>
  
   <!-- BUTTON TAMBAH -->
   <button class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
   text-sm font-semibold text-white hover:bg-blue-700 whitespace-nowrap">
<span class="material-symbols-rounded text-base">person_add</span>
Tambah Dosen
</button>
</div>

  <!-- NAVBAR FILTER -->
  <div class="bg-white rounded-xl shadow p-4">
    <div class="flex flex-col md:flex-row md:items-center gap-4">

      <div class="grid grid-cols-1 md:grid-cols-5 gap-4 flex-1">

        <!-- FAKULTAS -->
        <select class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Fakultas</option>
          <option>Fakultas Teknik</option>
          <option>Fakultas Ilmu Komputer</option>
          <option>Fakultas Ekonomi</option>
        </select>

        <!-- PRODI -->
        <select class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Prodi</option>
          <option>Informatika</option>
          <option>Sistem Informasi</option>
          <option>Manajemen</option>
        </select>

        <!-- JABATAN -->
        <select class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Jabatan</option>
          <option>Dosen Tetap</option>
          <option>Dosen Tidak Tetap</option>
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
               placeholder="Cari nama atau NIDN..."
               class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                      focus:ring-2 focus:ring-blue-500">
      </div>

     

    </div>
  </div>

  <!-- TABLE -->
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-100 text-slate-600">
        <tr>
          <th class="px-4 py-3 text-left">No</th>
          <th class="px-4 py-3 text-left">Nama Dosen</th>
          <th class="px-4 py-3 text-left">NIDN</th>
          <th class="px-4 py-3 text-left">Fakultas</th>
          <th class="px-4 py-3 text-left">jabatan</th>
          <th class="px-4 py-3 text-left">Email</th>
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>

      <tbody class="divide-y">

        <!-- ROW 1 -->
        @foreach ($dosens as $dosen)
            
        
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3">{{ $loop->iteration }}</td>
          <td class="px-4 py-3 font-medium">
            {{$dosen->name}}
          </td>
          <td class="px-4 py-3">
            {{$dosen->nidn ?? '-' }}
          </td>
          <td class="px-4 py-3">
            Fakultas Ilmu Komputer
          </td>
          <td class="px-4 py-3">
            Dosen Tetap
          </td>
          <td class="px-4 py-3">
            {{$dosen->email}}
          </td>
          <td class="px-4 py-3 text-center">
            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700
                         text-xs font-semibold">
                         {{$dosen->status}}
            </span>
          </td>
          <td class="px-4 py-3">
            <div class="flex justify-center gap-2">
              <button class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200"
                      title="Lihat">
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
        @endforeach

        {{-- <!-- ROW 2 -->
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3">2</td>
          <td class="px-4 py-3 font-medium">
            Sri Lestari, M.Kom
          </td>
          <td class="px-4 py-3">
            0024089203
          </td>
          <td class="px-4 py-3">
            Fakultas Teknik
          </td>
          <td class="px-4 py-3">
            Dosen Tidak Tetap
        </td>
          <td class="px-4 py-3">
            sri@kampus.ac.id
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
        </tr> --}}

      </tbody>
    </table>
  </div>

</div>

</body>
</html>

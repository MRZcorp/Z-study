<x-header>Data Mahasiswa</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>




<div class="max-w-7xl mx-auto space-y-6 p-6">

  <!-- HEADER -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

  <div>
    <h1 class="text-2xl font-bold text-slate-800">
      Kelola Data Mahasiswa
    </h1>
    <p class="text-sm text-slate-500">
      Manajemen data mahasiswa berdasarkan fakultas, program studi, dan angkatan
    </p>
  </div>

   <!-- BUTTON TAMBAH -->
   <button class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
   text-sm font-semibold text-white hover:bg-blue-700 whitespace-nowrap">
<span class="material-symbols-rounded text-base">person_add</span>
Tambah Mahasiswa
</button>
</div>




  <!--  FILTER -->
  <div class="bg-white rounded-xl shadow p-2">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      

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

        <!-- ANGKATAN -->
        <select class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Angkatan</option>
          <option>2021</option>
          <option>2022</option>
          <option>2023</option>
          <option>2024</option>
        </select>

        <!-- STATUS -->
        <select class="rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="aktif">Aktif</option>
          <option value="nonaktif">Nonaktif</option>
          <option value="lulus">Lulus</option>
        </select>

        <!-- SEARCH -->
        <input type="text"
               placeholder="Cari nama atau NIM..."
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
          <th class="px-4 py-3 text-left">Nama Mahasiswa</th>
          <th class="px-4 py-3 text-left">NIM</th>
          <th class="px-4 py-3 text-left">Fakultas</th>
          <th class="px-4 py-3 text-left">Prodi</th>
          <th class="px-4 py-3 text-center">Angkatan</th>
          {{-- <th class="px-4 py-3 text-left">Email</th> --}}
          <th class="px-4 py-3 text-center">Status</th>
          <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
      </thead>

      <tbody class="divide-y pt-5">

        <!-- ROW 1 -->
        @foreach ($mhss as $mhs)
            
       
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3">{{ $loop->iteration }}</td>
          <td class="px-4 py-3 font-medium">
            {{$mhs->name}}
          </td>
          <td class="px-4 py-3">
            {{$mhs->mahasiswa->nim ?? '-' }}
          </td>
          <td class="px-4 py-3">
            {{$mhs->mahasiswa->fakultas ?? '-' }}
          </td>
          <td class="px-4 py-3">
            {{$mhs->mahasiswa->prodi ?? '-' }}
          </td>
          <td class="px-4 py-3 text-center">
            {{$mhs->mahasiswa->angkatan ?? '-' }}
          </td>
          {{-- <td class="px-4 py-3">
            {{$mhs->email}}
          </td> --}}
          <td class="px-4 py-3 text-center">
            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700
                         text-xs font-semibold">
            {{$mhs->mahasiswa->status ?? '-' }}
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
            Siti Aisyah
          </td>
          <td class="px-4 py-3">
            2021102033
          </td>
          <td class="px-4 py-3">
            Fakultas Ekonomi
          </td>
          <td class="px-4 py-3">
            Manajemen
          </td>
          <td class="px-4 py-3 text-center">
            2021
          </td>
          <td class="px-4 py-3">
            aisyah@mail.ac.id
          </td>
          <td class="px-4 py-3 text-center">
            <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-600
                         text-xs font-semibold">
              Lulus
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

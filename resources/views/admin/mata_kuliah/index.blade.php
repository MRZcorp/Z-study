<x-header>Data Mata Kuliah</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Data Mata Kuliah</title>

  
  <div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">
          Kelola Data Mata Kuliah
        </h1>
        <p class="text-sm text-slate-500">
          Kelola mata kuliah, jurusan, SKS, dan status aktif
        </p>
      </div>

      <!-- BUTTON TAMBAH -->
      <button class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2
                     text-sm font-semibold text-white hover:bg-blue-700">
        <span class="material-symbols-rounded text-base">add</span>
        Tambah Mata Kuliah
      </button>
    </div>

    <!-- FILTER -->
    <div class="bg-white rounded-xl shadow p-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- JURUSAN / PRODI -->
        <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Jurusan / Prodi</option>
          <option>Teknik Informatika</option>
          <option>Sistem Informasi</option>
          <option>Manajemen</option>
        </select>

        <!-- STATUS -->
        <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Status</option>
          <option value="aktif">Aktif</option>
          <option value="nonaktif">Nonaktif</option>
        </select>

        <!-- SEARCH -->
        <input type="text"
               placeholder="Cari kode atau nama mata kuliah..."
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                      focus:ring-2 focus:ring-blue-500">
      </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left">No</th>
            <th class="px-4 py-3 text-left">Kode</th>
            <th class="px-4 py-3 text-left">Nama Mata Kuliah</th>
            <th class="px-4 py-3 text-center">SKS</th>
            <th class="px-4 py-3 text-left">Jurusan / Prodi</th>
            <th class="px-4 py-3 text-center">Jumlah Kelas</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y">

          <!-- ROW -->
          @foreach ($matkuls as $matkul)
              
          
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>

            <td class="px-4 py-3 font-mono text-slate-700">
              {{$matkul->kode_mata_kuliah}}
            </td>

            <td class="px-4 py-3 font-medium truncate max-w-xs">
              {{$matkul->mata_kuliah}}
            </td>

            <td class="px-4 py-3 text-center">
              {{$matkul->sks}}
            </td>

            <td class="px-4 py-3 text-slate-600">
              {{$matkul->programStudi->nama_prodi}}
            </td>

            <td class="px-4 py-3 text-center">
              0
            </td>

            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 rounded-full bg-green-100 text-green-700
                           text-xs font-semibold">
                           {{$matkul->status}}
              </span>
            </td>

            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">

                <button class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200
                               text-slate-700" title="Detail">
                  <span class="material-symbols-rounded">visibility</span>
                </button>

                <button class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200
                               text-blue-700" title="Edit">
                  <span class="material-symbols-rounded">edit</span>
                </button>

                <button class="p-2 rounded-lg bg-red-100 hover:bg-red-200
                               text-red-700" title="Hapus">
                  <span class="material-symbols-rounded">delete</span>
                </button>

              </div>
            </td>
          </tr>
          @endforeach


       

        </tbody>
      </table>
    </div>

  </div>


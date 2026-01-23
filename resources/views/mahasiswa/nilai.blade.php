<x-header> </x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

<div class="space-y-6">
<form method="GET" action="#" class="bg-white p-4 rounded-lg shadow-md w-full">
  <div class="mb-4">
      <label for="mata_kuliah" class="block text-sm font-medium text-gray-700 mb-2">
          Mata Kuliah / Kelas
      </label>
      <select 
          name="mata_kuliah" 
          id="mata_kuliah"
          class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
      >
          <option value="">Semua Mata Kuliah</option>
          <option value="pemrograman_web">Pemrograman Web</option>
          <option value="basis_data">Basis Data</option>
          <option value="sistem_informasi">Sistem Informasi</option>
      </select>
  </div>
</form>
<div class="bg-white rounded-lg shadow-md overflow-hidden w-full">
  <table class="min-w-full border border-gray-200">
      <thead class="bg-gray-100">
          <tr>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Mata Kuliah
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Jenis Penilaian
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Judul
              </th>
              <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 border-b">
                  Nilai
              </th>
              <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">
                  Keterangan
              </th>
          </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">
          <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 text-sm text-gray-700">
                  Pemrograman Web
              </td>
              <td class="px-4 py-2">
                  <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">
                      Tugas
                  </span>
              </td>
              <td class="px-4 py-2 text-sm text-gray-700">
                  Tugas 1 - HTML &amp; CSS Dasar
              </td>
              <td class="px-4 py-2 text-center text-sm font-semibold text-green-600">
                  85
              </td>
              <td class="px-4 py-2 text-sm text-gray-700">
                  Baik
              </td>
          </tr>

          <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 text-sm text-gray-700">
                  Pemrograman Web
              </td>
              <td class="px-4 py-2">
                  <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                      Kuis
                  </span>
              </td>
              <td class="px-4 py-2 text-sm text-gray-700">
                  Kuis 1 - JavaScript Fundamentals
              </td>
              <td class="px-4 py-2 text-center text-sm font-semibold text-green-600">
                  90
              </td>
              <td class="px-4 py-2 text-sm text-gray-700">
                  Sangat Baik
              </td>
          </tr>

          <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 text-sm text-gray-700">
                  Basis Data
              </td>
              <td class="px-4 py-2">
                  <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full">
                      Ujian
                  </span>
              </td>
              <td class="px-4 py-2 text-sm text-gray-700">
                  UTS - Basis Data
              </td>
              <td class="px-4 py-2 text-center text-sm font-semibold text-red-500">
                  -
              </td>
              <td class="px-4 py-2 text-sm text-gray-700">
                  Nilainya Tidak Ada
              </td>
          </tr>
      </tbody>
  </table>
</div>

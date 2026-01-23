<x-header></x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>


<style>
    .badge{padding:.25rem .5rem;border-radius:.375rem;font-size:.75rem;font-weight:500}
    .badge-success{background:#dcfce7;color:#166534}
    .badge-info{background:#e0f2fe;color:#075985}
    .badge-default{background:#f1f5f9;color:#475569}
    .badge-muted{background:#e5e7eb;color:#374151}
    .btn-primary{padding:.4rem .75rem;border-radius:.375rem;background:#0f172a;color:white;font-size:.75rem}
    </style>
    
    <div class="space-y-10">
<div class="bg-white rounded-xl border border-slate-200 shadow-sm">
    <div class="p-5 border-b border-slate-200">
      <h2 class="font-semibold text-slate-800">Kelas yang Tersedia</h2>
      <p class="text-sm text-slate-500">
        Pilih kelas yang ingin Anda ikuti dan ajukan permohonan bergabung
      </p>
    </div>
  
    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr class="text-left text-sm text-slate-600">
            <th class="px-4 py-3 border-r border-slate-200">Mata Kuliah</th>
            <th class="px-4 py-3 border-r border-slate-200">Nama Kelas</th>
            <th class="px-4 py-3 border-r border-slate-200">Dosen Pengampu</th>
            <th class="px-4 py-3 border-r border-slate-200">Semester</th>
            <th class="px-4 py-3 border-r border-slate-200">Kuota</th>
            <th class="px-4 py-3 border-r border-slate-200">Status Pendaftaran</th>
            <th class="px-4 py-3 border-r border-slate-200">Status Enrollment</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>
  
        <tbody class="text-sm text-slate-700 divide-y divide-slate-200">
          <!-- ROW -->
          <tr class="hover:bg-slate-50 transition">
            <td class="px-4 py-3 font-medium">{{ $mapel }}</td>
            <td class="px-4 py-3">Kelas A</td>
            <td class="px-4 py-3">Dr. Budi Santoso, M.Kom</td>
            <td class="px-4 py-3">Genap 2025/2026</td>
            <td class="px-4 py-3">35/40</td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 text-xs rounded-md bg-emerald-100 text-emerald-700">
                Terbuka
              </span>
            </td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 text-xs rounded-md bg-emerald-100 text-emerald-700">
                Disetujui
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 text-xs rounded-md bg-slate-200 text-slate-600">
                Sudah Terdaftar
              </span>
            </td>
          </tr>
  
          <!-- ROW -->
          <tr class="hover:bg-slate-50 transition">
            <td class="px-4 py-3 font-medium">Basis Data</td>
            <td class="px-4 py-3">Kelas B</td>
            <td class="px-4 py-3">Prof. Siti Rahma, Ph.D</td>
            <td class="px-4 py-3">Genap 2025/2026</td>
            <td class="px-4 py-3">28/35</td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 text-xs rounded-md bg-emerald-100 text-emerald-700">
                Terbuka
              </span>
            </td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 text-xs rounded-md bg-amber-100 text-amber-700">
                Menunggu Persetujuan
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <span class="px-3 py-1 text-xs rounded-md bg-slate-300 text-slate-700">
                Menunggu
              </span>
            </td>
          </tr>
  
        </tbody>
      </table>
    </div>
  </div>
  


  <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
    <div class="p-5 border-b border-slate-200">
      <h2 class="font-semibold text-slate-800">Kelas yang Diikuti</h2>
      <p class="text-sm text-slate-500">
        Daftar kelas yang telah disetujui dan sedang Anda ikuti
      </p>
    </div>
  
    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr class="text-left text-sm text-slate-600">
            <th class="px-4 py-3 border-r border-slate-200">Mata Kuliah</th>
            <th class="px-4 py-3 border-r border-slate-200">Kelas</th>
            <th class="px-4 py-3 border-r border-slate-200">Dosen</th>
            <th class="px-4 py-3 border-r border-slate-200">Semester</th>
            <th class="px-4 py-3 text-center">Status</th>
          </tr>
        </thead>
  
        <tbody class="divide-y divide-slate-200 text-sm">
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3 font-medium">Pemrograman Web</td>
            <td class="px-4 py-3">A</td>
            <td class="px-4 py-3">Dr. Budi Santoso, M.Kom</td>
            <td class="px-4 py-3">Genap 2025/2026</td>
            <td class="px-4 py-3 text-center">
              <span class="badge badge-info">Disetujui</span>
            </td>
          </tr>
  
          <tr class="hover:bg-slate-50">
            <td class="px-4 py-3 font-medium">Basis Data</td>
            <td class="px-4 py-3">B</td>
            <td class="px-4 py-3">Prof. Siti Rahma, Ph.D</td>
            <td class="px-4 py-3">Genap 2025/2026</td>
            <td class="px-4 py-3 text-center">
              <span class="badge badge-info">Aktif</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  
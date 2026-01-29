<x-header>Dashboard</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>



    <div class="p-6">
  
  
        <!-- STATISTIK ATAS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
          {{-- <div class="stats-container"> --}}
          <!-- Jumlah Kelas -->
          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-blue-200 text-blue-600">
              <span class="material-symbols-rounded text-3xl">Co_Present</span>
            </div>
            <div class="ml-4">
              <h4 class="text-3xl font-semibold text-gray-700">6</h4>
              <p class="text-gray-500">Jumlah Kelas</p>
            </div>
          </div>
      
          <!-- Tugas Aktif -->
          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-orange-200 text-orange-600 ">
              <span class="material-symbols-rounded text-3xl">assignment</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">12</h4>
              <p class="text-gray-500">Total Materi</p>
            </div>
          </div>
      
          <!-- Kuis / Ujian -->
          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-pink-200 text-pink-600">
              <span class="material-symbols-rounded text-3xl">Contract_Edit</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">3</h4>
              <p class="text-gray-500">Total Tugas</p>
            </div>
          </div>
      
          <!-- Rata-rata Nilai -->
          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-green-200 text-green-600">
              <span class="material-symbols-rounded text-3xl">quiz</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">8</h4>
              <p class="text-gray-500">Total Kuis & Ujian</p>
            </div>
          </div>
      
        </div>
      
        <!-- KONTEN BAWAH -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-6 mt-6">
      
          <!-- PENGUMUMAN -->
<div class="bg-white rounded-lg shadow-sm p-6">
  <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
      <span class="material-symbols-rounded text-blue-600">campaign</span>
      Pengumuman
  </h3>




  <!-- SCROLL AREA -->
<ul class="space-y-4 max-h-100 overflow-y-auto pr-2">
  @foreach ($pengumuman as $info)
  @php
      $tipe = strtolower($info->tipe);
      $icon = match ($tipe) {
          'info' => 'info',
          'peringatan' => 'warning',
          'event' => 'event',
          default => 'info',
      };
  
      $bgicon = match ($icon) {
          'info' => 'bg-blue-100 text-blue-600',
          'warning' => 'bg-yellow-100 text-yellow-600',
          'event' => 'bg-green-100 text-green-600',
          default => 'bg-slate-100 text-slate-700',
      };
  @endphp
  
  <li class="flex items-start p-4 bg-slate-50 rounded-lg border border-slate-200">
      <div class="p-2 rounded-lg {{ $bgicon }}">
          <span class="material-symbols-rounded">{{ $icon }}</span>
      </div>
  
      <div class="ml-4">
          <p
              class="font-semibold text-gray-800 cursor-pointer hover:underline"
              onclick="openAnnouncement(
                  `{{ $info->judul }}`,
                  `{{ $info->tanggal_publish }}`,
                  `{!! nl2br(e($info->isi)) !!}`
              )"
          >
              {{ $info->judul }}
          </p>
  
          <p class="text-sm text-gray-500 line-clamp-2">
              {{ $info->isi }}
          </p>
  
          <p class="text-xs text-gray-400 mt-1">
              {{ $info->tanggal_publish }}
          </p>
      </div>
  </li>
  @endforeach
  </div>
      
          <!-- TUGAS TERDEKAT -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
              Jadwal Kelas
            </h3>
      
            <ul class="space-y-4">
  
                <li class="flex items-start p-4 bg-slate-50 rounded-lg border border-slate-200">
                  <div class="p-2 rounded-lg bg-orange-100 text-orange-600 mt-1">
                    <span class="material-symbols-rounded">assignment</span>
                  </div>
              
                  <div class="ml-4">
                    <p class="font-semibold text-gray-800">
                      Tugas 3: Implementasi REST API
                    </p>
                    <p class="text-sm text-gray-500">
                      Pemrograman Web Lanjut
                    </p>
                    <p class="text-sm text-red-500 flex items-center mt-1">
                      <span class="material-symbols-rounded text-base mr-1">event</span>
                      25 Januari 2026
                    </p>
                  </div>
                </li>
              
                <li class="flex items-start p-4 bg-slate-50 rounded-lg border border-slate-200">
                  <div class="p-2 rounded-lg bg-orange-100 text-orange-600 mt-1">
                    <span class="material-symbols-rounded">assignment</span>
                  </div>
              
                  <div class="ml-4">
                    <p class="font-semibold text-gray-800">
                      Tugas 2: Normalisasi Database
                    </p>
                    <p class="text-sm text-gray-500">
                      Basis Data
                    </p>
                    <p class="text-sm text-red-500 flex items-center mt-1">
                      <span class="material-symbols-rounded text-base mr-1">event</span>
                      28 Januari 2026
                    </p>
                  </div>
                </li>
              
              </ul>
              
          </div>
      
        </div>
      
      </div>
    </main>




  
<x-footer></x-footer>
 
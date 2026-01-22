<x-header></x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>


<main class="main-content">
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
              <p class="text-gray-500">Tugas Aktif</p>
            </div>
          </div>
      
          <!-- Kuis / Ujian -->
          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-pink-200 text-pink-600">
              <span class="material-symbols-rounded text-3xl">Contract_Edit</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">3</h4>
              <p class="text-gray-500">Kuis / Ujian Aktif</p>
            </div>
          </div>
      
          <!-- Rata-rata Nilai -->
          <div class="flex items-center p-6 bg-white rounded-lg shadow-sm">
            <div class="p-3 rounded-lg bg-green-200 text-green-600">
              <span class="material-symbols-rounded text-3xl">grade</span>
            </div>
            <div class="ml-4">
              <h4 class="text-2xl font-semibold text-gray-700">82.5</h4>
              <p class="text-gray-500">Rata-rata Nilai</p>
            </div>
          </div>
      
        </div>
      
        <!-- KONTEN BAWAH -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-6 mt-6">
      
          <!-- KELAS AKTIF -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
              Kelas Aktif
            </h3>
      
            <ul class="space-y-4">
  
                <li class="flex items-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                  <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                    <span class="material-symbols-rounded">menu_book</span>
                  </div>
              
                  <div class="ml-4">
                    <p class="font-semibold text-gray-800">
                      Pemrograman Web Lanjut
                    </p>
                    <p class="text-sm text-gray-500">
                      Semester 5
                    </p>
                  </div>
                </li>
              
                <li class="flex items-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                  <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                    <span class="material-symbols-rounded">menu_book</span>
                  </div>
              
                  <div class="ml-4">
                    <p class="font-semibold text-gray-800">
                      Basis Data
                    </p>
                    <p class="text-sm text-gray-500">
                      Semester 5
                    </p>
                  </div>
                </li>
              
              </ul>
              
          </div>
      
          <!-- TUGAS TERDEKAT -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
              Tugas Terdekat
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
 
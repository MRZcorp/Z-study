<x-header></x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

<!-- PROFIL MAHASISWA -->
<div class="mb-6 bg-white rounded-xl shadow p-5">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
  
      <!-- KIRI -->
      <div class="flex items-center gap-4">
        <img src="/img/zaky.jpeg"
             class="w-16 h-16 rounded-full object-cover border-2 border-blue-500"
             alt="Foto Mahasiswa">
  
        <div>
          <h2 class="text-lg font-semibold text-gray-800">
            M. Zaky Nugraha A R
          </h2>
          <p class="text-sm text-gray-500">NIM: 202201234</p>
          <p class="text-sm text-gray-500">
            Fakultas Teknik · Informatika
          </p>
        </div>
      </div>
  
      <!-- KANAN -->
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
  
        
  
        <div class="bg-gray-100 rounded-lg px-3 py-2">
            <p class="text-gray-500">Tahun Ajar</p>
            <p class="font-semibold text-gray-800">2024 / 2025</p>
          </div>

        <div class="bg-gray-100 rounded-lg px-3 py-2">
          <p class="text-gray-500">Semester</p>
          <p class="font-semibold text-gray-800">Genap : 6</p>
        </div>
  
        
  
        <div class="bg-gray-100 rounded-lg px-3 py-2 col-span-2 sm:col-span-1">
          <p class="text-gray-500">Dosen Wali</p>
          <p class="font-semibold text-gray-800">Dr. Budi Yono</p>
        </div>

        <div class="bg-gray-100 rounded-lg px-3 py-2">
            <p class="text-gray-500">IPK</p>
            <p class="font-semibold text-gray-800">3.75</p>
          </div>
    
          <div class="bg-gray-100 rounded-lg px-3 py-2">
            <p class="text-gray-500">SKS Ditempuh</p>
            <p class="font-semibold text-gray-800"> 64 / 144</p>
          </div>

          <div class="bg-gray-100 rounded-lg px-3 py-2">
            <p class="text-gray-500">Max SKS</p>
            <p class="font-semibold text-gray-800"> 0 / 24</p>
          </div>
  
      </div>
    </div>
  </div>
  
<!-- SUB NAVBAR -->
<div class="mb-6">
    <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
  
      <!-- TAB 1 -->
      <a href="mahasiswa/kelas"
         class="px-4 py-2 text-sm font-semibold rounded-lg
                bg-blue-800 text-white shadow">
        Kelas Saya 
      </a>
  
      <!-- TAB 2 -->
      <a href="mahasiswa/kelas_tersedia"
         class="px-4 py-2 text-sm font-semibold rounded-lg
                text-gray-600 hover:bg-gray-100">
        Kelas Tersedia
      </a>
  
    </div>
  </div>
  
<div class="p-6 bg-gray-100 min-h-screen">
    {{-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"> --}}
        <div class="grid gap-6"
        style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
   

        @foreach ($pilih_kelas as $kelas)
            
        
      <!-- CARD -->
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
  
        <!-- HEADER (BACKGROUND UPLOADABLE) -->
        <div
          class="relative h-28 bg-cover bg-center"
          style="background-image: url({{asset('storage/' . $kelas->bg_image)}});"
        > <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30"></div>
      
        <!-- SKS TAG -->
        <div
          class="absolute top-1 left-0 z-9 flex items-center gap-1 
                 bg-amber-50 text-gray-800 text-sm font-semibold
                 px-2 py-1 rounded-r-full shadow"
        >
          <span class="material-symbols-rounded text-base text-blue-600">
            attach_file
          </span>
          {{$kelas->sks}}
        </div>
          <div class="absolute inset-0 bg-black/30"></div>
  
          <div class="absolute bottom-3 left-2 text-white z-10">
            
            <h3 class="text-sm font-semibold">{{ $kelas->mata_kuliah }}</h3>
          </div>
  
          <!-- AVATAR -->
          <img
            src="{{asset('storage/' . $kelas->kelas_image)}}"
            class="absolute -bottom-10 right-4 w-20 h-20 rounded-full border-4 border-white object-cover z-10"
            alt="Avatar"
          />
        </div>
  
        <!-- BODY -->
        <div class="pt-4 px-4 pb-4 space-y-1 text-sm text-gray-700">
          <p>Kelas {{$kelas->nama_kelas}}</p>
          <p>{{$kelas->jadwal_kelas}}</p>
  
          <!-- spacer tanpa titik -->
          <div class="h-2"></div>
  
          <p>{{$kelas->hari_kelas}}</p>
          <p>{{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }}
 - {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}</p>
  
          <div class="h-2"></div>
  
          <p class="font-medium text-gray-900">{{$kelas->dosens->dosen ?? '-' }}</p>
        </div>
  
        <!-- FOOTER -->
        <div class="flex items-center justify-between px-4 py-3 border-t">
  
          <!-- PEOPLE + KUOTA -->
          <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-600 text-lg">
              people
            </span>
  
            <!-- KUOTA (DINAMIS) -->
            <span class="text-sm font-semibold text-green-600">
              {{ $kelas->kuota_terdaftar }} / {{ $kelas->kuota_maksimal }}
            </span>
          </div>


         


  
          <!-- BUTTON -->
          <form action="{{ route('mahasiswa.kelas.ikuti', $kelas->id) }}" method="POST">
            @csrf
            @if ($kelas->kuota_terdaftar >= $kelas->kuota_maksimal)
            <button disabled class="px-4 py-1.5 rounded-full bg-gray-400 text-white">
                Penuh
            </button>
        @else
         <!-- tombol ikuti -->
            <button
              type="submit"
              class="flex items-center gap-1 rounded-full bg-gradient-to-r 
                     from-blue-500 to-purple-500 px-4 py-1.5 text-sm 
                     font-semibold text-white transition 
                     hover:-translate-y-2 hover:shadow-lg"
            >
              <span class="material-symbols-rounded text-base">add_circle</span>
              Ikuti
            </button>
           
   
@endif
          </form>
  
      </div>
      </div>
      <!-- END CARD -->
      @endforeach




    </div>
  </div>



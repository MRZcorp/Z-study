<x-header>Profil Saya</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>



<div class="p-6 bg-gray-100 min-h-screen">

    <!-- HEADER -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Profil Dosen</h1>
      <p class="text-sm text-gray-500">Data lengkap dan status akademik</p>
    </div>
    
  
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  
      <!-- LEFT PROFILE -->
      <script src="//unpkg.com/alpinejs" defer></script>

<div x-data="{ photoPreview: null }"
     class="bg-white rounded-2xl shadow p-6">

  <div class="flex flex-col items-center text-center">

    <!-- FOTO -->
    <label class="relative cursor-pointer group">
      <img
        :src="photoPreview ? photoPreview : '{{ $foto 
                                        ? asset('storage/' . $foto) 
                                        : asset('img/default_profil.jpg') }}'"
        class="w-32 h-32 rounded-full object-cover border-4 border-blue-600
               group-hover:opacity-80 transition"
      >

      <!-- OVERLAY EDIT -->
      <div
        class="absolute inset-0 bg-black/40 rounded-full
               flex items-center justify-center
               text-white text-sm font-semibold
               opacity-0 group-hover:opacity-100 transition">
        Edit Foto
      </div>

      <input type="file" class="hidden"
             @change="
               const file = $event.target.files[0];
               if (file) photoPreview = URL.createObjectURL(file);
             ">
    </label>

    <h3 class="mt-4 text-lg font-semibold">
      {{$nama}}
    </h3>
    <p class="text-sm text-gray-500">{{$role}}</p>
  </div>


  
  <div x-data="{ edit: false }"
  class="bg-white rounded-2xl shadow p-6">

<div class="flex justify-between items-center mb-4">
 <h2 class="text-lg font-semibold">Informasi Pribadi</h2>
 
</div>



 <!-- NAMA -->
 <div>
   <label class="text-sm font-medium">Nama Lengkap</label>
   <template x-if="!edit">
     <p class="text-gray-700 mt-1">{{$nama}}</p>
   </template>
   <template x-if="edit">
     <input type="text"
            class="w-full rounded-lg border-gray-300
                   focus:ring-blue-500 focus:border-blue-500"
            value="{{$nama}}">
   </template>
 </div>

 <!-- EMAIL -->
 <div>
   <label class="text-sm font-medium">Email</label>
   <template x-if="!edit">
     <p class="text-gray-700 mt-1">{{$email}}</p>
   </template>
   <template x-if="edit">
     <input type="email"
            class="w-full rounded-lg border-gray-300
                   focus:ring-blue-500 focus:border-blue-500"
            value="zaky@kampus.ac.id">
   </template>
 </div>

 <!-- NO HP -->
 <div>
   <label class="text-sm font-medium">No HP</label>
   <template x-if="!edit">
     <p class="text-gray-700 mt-1">null</p>
   </template>
   <template x-if="edit">
     <input type="text"
            class="w-full rounded-lg border-gray-300
                   focus:ring-blue-500 focus:border-blue-500"
            value="081234567890">
   </template>
 </div>
 <button
   @click="edit = !edit"
   class="text-sm font-semibold text-blue-600 hover:underline">
   <span x-text="edit ? 'Batal' : 'Edit Data'"></span>
 </button>

 <!-- ACTION -->
 <template x-if="edit">
   <div class="flex justify-end gap-3 pt-4 border-t">
     <button type="button"
             @click="edit = false"
             class="px-4 py-2 text-sm rounded-lg
                    text-gray-600 hover:bg-gray-100">
       Batal
     </button>

     <button type="submit"
             class="px-4 py-2 text-sm rounded-lg
                    bg-blue-600 text-white hover:bg-blue-700">
       Simpan
     </button>
   </div>
 </template>


</div>

      </div>
  
      <!-- RIGHT DETAILS -->
      
      <div class="lg:col-span-2 space-y-6">
        <!-- SUB NAVBAR -->
<div class="mb-6">
  <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">

    <!-- TAB 1 -->
    <a href="#"
       class="px-4 py-2 text-sm font-semibold rounded-lg
              bg-blue-800 text-white shadow">
      Akademik 
    </a>

    <!-- TAB 2 -->
    <a href="{#"
       class="px-4 py-2 text-sm font-semibold rounded-lg
              text-gray-600 hover:bg-gray-100">
      Biodata
    </a>

  </div>
</div>
  
        <!-- AKADEMIK -->
        <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-lg font-semibold mb-4">Informasi Akademik</h2>
  
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <p><span class="font-medium">Fakultas:</span> {{$fakultas}}</p>
            <p><span class="font-medium">Program Studi:</span> {{$prodi}} </p>
            <p><span class="font-medium">Jenjang:</span> null</p>
            <p><span class="font-medium">Angkatan:</span> {{{$angkatan}}}</p>
            <p><span class="font-medium">IPK:</span> 3.75</p>
            <p><span class="font-medium">SKS Maksimal:</span> 24</p>
          </div>
        </div>
  
        <!-- STATUS STUDI -->
        <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-lg font-semibold mb-4">Status Studi</h2>
  
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <p><span class="font-medium">Semester:</span> Genap 2025 / 2026</p>
            <p><span class="font-medium">Status Akademik:</span>
              <span class="text-green-600 font-semibold">Aktif</span>
            </p>
            <p><span class="font-medium">Jumlah SKS Diambil:</span> 21</p>
            <p><span class="font-medium">Batas Studi:</span> 8 Semester</p>
          </div>
        </div>
  
        <!-- DOSEN WALI -->
        <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-lg font-semibold mb-4">Dosen Wali</h2>
  
          <div class="flex items-center gap-4">
            <img
              src="/img/zaky.jpeg"
              class="w-16 h-16 rounded-full object-cover border"
            >
            <div class="text-sm">
              <p class="font-semibold">Dr. Andi Pratama, M.Kom</p>
              <p class="text-gray-500">NIDN: 0213128901</p>
              <p class="text-gray-500">Email: andi@kampus.ac.id</p>
            </div>
          </div>
        </div>
  
        <!-- SISTEM -->
        <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-lg font-semibold mb-4">Informasi Sistem</h2>
  
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <p><span class="font-medium">Role:</span> {{$role}}</p>
            <p><span class="font-medium">Terakhir Login:</span> 24 Jan 2026, 18:20</p>
            <p><span class="font-medium">Akun Dibuat:</span> 01 Sep 2021</p>
            <p><span class="font-medium">Status Akun:</span>
              <span class="text-green-600 font-semibold">Aktif</span>
            </p>
          </div>
        </div>
  
      </div>
    </div>
  </div>
  

<x-header>Data Kelas</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

<!-- PROFIL Dosen -->
<!-- CARD PROFILE WITH BACKGROUND -->
<div 
  id="profileCard"
  class="relative mb-6 rounded-xl shadow overflow-hidden cursor-pointer group"
  style="background-image: url('/img/zaky.jpeg'); background-size: cover; background-position: center;"
>

  <!-- OVERLAY -->
  <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition"></div>

  <!-- UPLOAD INPUT (HIDDEN) -->
  <input 
    type="file" 
    id="bgUpload"
    accept="image/*"
    class="hidden"
  >

  <!-- CONTENT -->
  <div class="relative p-5 text-white">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

      <!-- KIRI -->
      <div class="flex items-center gap-4">
        <img 
        src="{{ $foto 
        ? asset('storage/' . $foto) 
        : asset('img/Logo_Zstudy.png') }}"
          class="w-26 h-26 rounded-full object-cover border-2 border-white"
          alt="Foto Profil"
        >

        <div>
          <h2 class="text-lg font-semibold">
            {{$nama}}
          </h2>
          <p class="text-sm text-white">NIDN: {{$id_user}}</p>
          <p class="text-sm text-white">Dosen Tetap</p>
        </div>
      </div>

      <!-- KANAN -->
      <div class="grid grid-cols-2 sm:grid-cols-2 gap-3 text-sm text-gray-800">

        
        <div class="rounded-lg px-3 py-2 text-center
            bg-black/40 backdrop-blur-sm
            border border-white/20">
  <p class="text-white/70 text-sm">Tahun Ajar</p>
  <p class="font-semibold text-white text-lg">2024 / 2025</p>
</div>

        
        <div class="rounded-lg px-3 py-2 text-center
        bg-black/40 backdrop-blur-sm
        border border-white/20">
<p class="text-white/70 text-sm">Semester</p>
<p class="font-semibold text-white text-lg">Genap : 6</p>
</div>
        
        <div class="rounded-lg px-3 py-2 text-center
        bg-black/40 backdrop-blur-sm
        border border-white/20">
<p class="text-white/70 text-sm">Jumlah Kelas</p>
<p class="font-semibold text-white text-lg">4</p>
</div>

        <div class="rounded-lg px-3 py-2 text-center
            bg-black/40 backdrop-blur-sm
            border border-white/20">
  <p class="text-white/70 text-sm">Jumlah SKS</p>
  <p class="font-semibold text-white text-lg">8</p>
</div>

      </div>

    </div>

   

  </div>
</div>

  
<!-- SUB NAVBAR -->
<div class="mb-6">
    <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
  
      <!-- TAB 1 -->
      <a href="{{ route('dosen.kelas') }}"
         class="px-4 py-2 text-sm font-semibold rounded-lg
                bg-blue-800 text-white shadow">
        Kelas Saya 
      </a>
  
      <!-- TAB 2 -->
      <a href="{{ route('dosen.buat_kelas') }}"
         class="px-4 py-2 text-sm font-semibold rounded-lg
                text-gray-600 hover:bg-gray-100">
        Buat Kelas
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
        {{-- <img
          src="{{ $kelas->dosens && $kelas->dosens->poto_profil
                ? asset('storage/' . $kelas->dosens->poto_profil)
                : asset('img/default_profil.jpg') }}"
          class="absolute -bottom-10 right-4 w-20 h-20 rounded-full border-4 border-white object-cover z-10"
          alt="Avatar"
        /> --}}
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
          {{$kelas->mataKuliah->sks}}
        </div>
          <div class="absolute inset-0 bg-black/30"></div>
  
          <div class="absolute bottom-3 left-2 text-white z-10">
            
            <h3 
                  class="text-sm font-semibold leading-snug 
                        max-w-[70%] 
                        line-clamp-2"
                >
                  {{ $kelas->mataKuliah->mata_kuliah }}
                </h3>
          </div>
  
          <!-- AVATAR -->
          <img
          src="{{ $kelas->dosens && $kelas->dosens->poto_profil
                ? asset('storage/' . $kelas->dosens->poto_profil)
                : asset('img/default_profil.jpg') }}"
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
  
          <p class="font-medium text-gray-900">{{$kelas->dosens->user->name ?? '-' }} {{$kelas->dosens->gelar}} </p>
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
          
         <!-- tombol lihat -->
  

            <button
            onclick="openModal()"
             data-kelas-id="{{ $kelas->id }}"
            data-dosen="{{ $kelas->dosen->user->name ?? '-' }}"
              class="flex items-center gap-1 rounded-full bg-gradient-to-r 
                     from-blue-500 to-purple-500 px-4 py-1.5 text-sm 
                     font-semibold text-white transition 
                     hover:-translate-y-2 hover:shadow-lg"
            >
              <span class="material-symbols-rounded text-base">Visibility</span>
              Lihat
            </button>
           
   

          
  
      </div>
      </div>
      <!-- END CARD -->
      @endforeach



{{-- ///////////////////////////////////////////////////////////////////////////////////////// --}}


      <!-- OVERLAY -->
<div id="pesertaModal"
class="fixed inset-0 z-50 hidden items-center justify-center
       bg-black/50 backdrop-blur-sm px-4">

<!-- MODAL BOX -->
<div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl
         animate-scaleIn">

<!-- HEADER -->
<div class="flex items-center justify-between px-5 py-4 border-b">
 <h3 class="text-lg font-semibold text-gray-800">
   Peserta Kelas
 </h3>

 <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
   ✕
 </button>
</div>

<!-- CONTENT -->
<div class="p-5 space-y-6 max-h-[70vh] overflow-y-auto">

 <!-- DOSEN -->
 <div>
   <p class="text-xs font-semibold text-gray-400 uppercase mb-3">
     Dosen Pengampu
   </p>

   <div class="flex items-center gap-4 p-3 rounded-xl
               bg-gray-50 border">

               <img 
        src="{{ $foto 
        ? asset('storage/' . $foto) 
        : asset('img/Logo_Zstudy.png') }}"
          alt="Foto Profil"
          class="w-11 h-11 rounded-full object-cover">
     <div>
       <p class="font-semibold text-gray-800">
         {{$nama}}
       </p>
       <p class="text-xs text-gray-500">
         Host
       </p>
     </div>
   </div>
 </div>

 <!-- MAHASISWA -->
 <div>
   <p class="text-xs font-semibold text-gray-400 uppercase mb-3">
     Mahasiswa Peserta
   </p>

   <div class="space-y-2">

     <div class="flex items-center gap-3 p-3 rounded-xl
                 hover:bg-gray-100 transition">
       <img src="/img/user1.jpg"
            class="w-9 h-9 rounded-full object-cover">
       <span class="text-sm text-gray-800">
         M. Zaky Nugraha A R
       </span>
     </div>

     <div class="flex items-center gap-3 p-3 rounded-xl
                 hover:bg-gray-100 transition">
       <img src="/img/user2.jpg"
            class="w-9 h-9 rounded-full object-cover">
       <span class="text-sm text-gray-800">
         Aulia Rahman
       </span>
     </div>

     <div class="flex items-center gap-3 p-3 rounded-xl
                 hover:bg-gray-100 transition">
       <img src="/img/user3.jpg"
            class="w-9 h-9 rounded-full object-cover">
       <span class="text-sm text-gray-800">
         Siti Nurhaliza
       </span>
     </div>

   </div>
 </div>

</div>
</div>
</div>

<style>
  @keyframes scaleIn {
    from { transform: scale(.95); opacity: 0 }
    to   { transform: scale(1); opacity: 1 }
  }
  .animate-scaleIn {
    animation: scaleIn .2s ease-out;
  }
  </style>
  

  <script>
    function openModal() {
      document.getElementById('pesertaModal').classList.remove('hidden');
      document.getElementById('pesertaModal').classList.add('flex');
    }
    
    function closeModal() {
      document.getElementById('pesertaModal').classList.add('hidden');
      document.getElementById('pesertaModal').classList.remove('flex');
    }
    
    // klik di luar modal
    document.getElementById('pesertaModal').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });
    </script>




















































  <script>
    const card = document.getElementById('profileCard');
    const upload = document.getElementById('bgUpload');
  
    card.addEventListener('click', () => {
      upload.click();
    });
  
    upload.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (!file) return;
  
      const reader = new FileReader();
      reader.onload = () => {
        card.style.backgroundImage = `url('${reader.result}')`;
      };
      reader.readAsDataURL(file);
    });
  </script>
  
<x-header></x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

<!-- PROFIL MAHASISWA -->
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
          src="/img/zaky.jpeg"
          class="w-26 h-26 rounded-full object-cover border-2 border-white"
          alt="Foto Mahasiswa"
        >

        <div>
          <h2 class="text-lg font-semibold">
            M. Zaky Nugraha A R
          </h2>
          <p class="text-sm text-white">NIDN: 202201234</p>
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
      text-gray-600 hover:bg-gray-100">
        Kelas Saya 
      </a>
  
      <!-- TAB 2 -->
      <a href="{{ route('dosen.buat_kelas') }}"
      class="px-4 py-2 text-sm font-semibold rounded-lg
      bg-blue-800 text-white shadow">
         
        Buat Kelas
      </a>
  
    </div>
  </div>

<form action="{{ url('/dosen/kelas') }}"
method="POST"
enctype="multipart/form-data">
@csrf


<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-2xl shadow p-6">

        <h2 class="text-xl font-semibold mb-6">Buat Kelas Baru</h2>

        <div class="space-y-5">

            <!-- Mata Kuliah -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Mata Kuliah</label>
                <input name="mata_kuliah"
                         type="text"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Contoh: Pemrograman Web">
            </div>
            

                <!-- COVER KELAS -->
                <div>
                    <label for="cover" class="block text-sm font-medium mb-1">
                        Cover Kelas
                    </label>
            
                    <label
                        for="cover"
                        class="flex items-center gap-2 w-full rounded-lg border border-slate-300 
                               px-4 py-2 cursor-pointer bg-white text-slate-500
                               hover:bg-slate-50
                               focus-within:ring-2 focus-within:ring-blue-500">
            
                        <span class="material-symbols-rounded text-blue-600">
                            upload
                        </span>
            
                        <span id="file-label" class="text-sm">
                            Pilih file
                        </span>
            
                        <input
                            name="bg_image"
                            id="cover"
                            type="file"
                            class="hidden"
                            onchange="updateFileName(this)"
                        >
                    </label>
                </div>
            
               
            

             <!-- INPUT KUOTA -->
    <div>
        <label class="block text-sm font-medium mb-1">Kuota Mahasiswa</label>
        <input name="kuota_maksimal" type="number"
               class="w-full rounded-lg border border-slate-300 px-4 py-2
                      focus:ring-2 focus:ring-blue-500 focus:outline-none"
               placeholder="Contoh: 30">
    </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">SKS</label>
                <select name="sks" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
            </div>
            </div>

            <!-- Kelas & Sistem -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kelas</label>
                    <select name="nama_kelas" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        <option>Kelas A</option>
                        <option>Kelas B</option>
                        <option>Kelas C</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Sistem</label>
                    <select name="jadwal_kelas" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        <option>Reguler Pagi</option>
                        <option>Reguler Siang</option>
                        <option>Reguler Malam</option>
                    </select>
                </div>
            </div>

            <!-- Hari & Jam -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Hari</label>
                    <select name="hari_kelas" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        <option>Senin</option>
                        <option>Selasa</option>
                        <option>Rabu</option>
                        <option>Kamis</option>
                        <option>Jumat</option>
                        <option>Sabtu</option>
                        <option>Minggu</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jam Mulai</label>
                    <input name="jam_mulai" type="time"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jam Selesai</label>
                    <input name="jam_selesai" type="time"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2">
                </div>
            </div>

           <!-- Kuota -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

   

    <!-- SPACER (BIAR SEIMBANG) -->
    <div class="hidden md:block"></div>
    <div class="hidden md:block"></div>

    <!-- BUTTON GROUP -->
    <div class="flex justify-end gap-2">
        <button type="reset"
                class="text-sm px-3 py-2 rounded-md border border-slate-300
                       hover:bg-slate-100">
            Reset
        </button>

        <button type="submit"
                class="text-sm px-3 py-2 rounded-md
                       bg-gradient-to-r from-blue-500 to-purple-500
                       text-white font-medium hover:opacity-90">
            Simpan
        </button>
    </div>

</div>
</form>

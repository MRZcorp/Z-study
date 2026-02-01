<x-header>Tugas Mahasiswa</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>




<!-- SUB NAVBAR -->
<div class="mb-6">
  <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">

    <!-- TAB 1 -->
    <a href="#"
       class="px-4 py-2 text-sm font-semibold rounded-lg
              bg-blue-800 text-white shadow">
      Ditugaskan 
    </a>

    <!-- TAB 2 -->
    <a href="{#"
       class="px-4 py-2 text-sm font-semibold rounded-lg
              text-gray-600 hover:bg-gray-100">
      Selesai
    </a>

  </div>
</div>

<div class="space-y-6">
<div class="bg-white border rounded-xl p-6 space-y-4 shadow-sm">


  @foreach ($tugas_kelas as $tugas)
      
  
    <!-- HEADER -->
    <div class="flex items-start justify-between">
      <h3 class="font-semibold text-lg text-slate-800">
        {{$tugas->nama_tugas}}
      </h3>
  
      <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
        Belum Dikumpulkan
      </span>
    </div>
  
    <!-- DESKRIPSI -->
    <p class="text-sm text-slate-600">
      {{$tugas->detail_tugas}}
    </p>
  
    <!-- INFO -->
    <div class="flex flex-wrap gap-6 text-sm text-slate-500">
      <div class="flex items-center gap-2">
        📘 {{$tugas->mataKuliah->mata_kuliah}} - Kelas {{$tugas->kelas->nama_kelas}}
      </div>
      <div class="flex items-center gap-2">
        ⏰ {{$tugas->deadline}}
      </div>
    </div>
  
    <!-- ACTION -->
    <div class="flex gap-3 pt-2">
      <button class="px-4 py-2 text-sm border rounded-lg hover:bg-slate-100">
        Lihat Detail
      </button>
  
      <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Upload Tugas
      </button>
    </div>
  
  </div>
  
  <div class="bg-white border rounded-xl p-6 space-y-4 shadow-sm">

    @endforeach

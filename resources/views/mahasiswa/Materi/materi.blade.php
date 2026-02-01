<x-header>Materi Pembelajaran</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>
<div class="bg-white rounded-xl border p-5 flex flex-col lg:flex-row
            lg:items-center lg:justify-between gap-6
            style="background-image: {{ asset('img/Logo_Zstudy.png') }}>

  <!-- KIRI : INFO KELAS -->
  <div>
    <h2 class="text-xl font-bold text-slate-800">
      Pemrograman Dasar
    </h2>

    <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-slate-600">

      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-base">school</span>
        Kelas A
      </span>

      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-base">calendar_today</span>
        Senin
      </span>

      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-base">schedule</span>
        08:00 – 09:40
      </span>

    </div>
  </div>

  <!-- KANAN : DOSEN + STAT -->
  <div class="flex flex-wrap items-center gap-6 text-sm">

    <!-- DOSEN -->
    <div class="flex items-center gap-3">
      <img src="/img/dosen1.jpg"
           class="w-10 h-10 rounded-full object-cover"
           alt="Dosen">

      <div>
        <p class="font-semibold text-slate-800">
          Dr. Andi Wijaya, M.Kom
        </p>
        <p class="text-xs text-slate-500">
          Dosen Pengampu
        </p>
      </div>
    </div>

    <!-- DIVIDER -->
    <div class="hidden lg:block w-px h-10 bg-slate-200"></div>

    <!-- STAT -->
    <div class="flex gap-4">

      <div class="text-center">
        <p class="text-lg font-bold text-slate-800">
          14
        </p>
        <p class="text-xs text-slate-500">
          Pertemuan
        </p>
      </div>

      <div class="text-center">
        <p class="text-lg font-bold text-slate-800">
          {{ $materi_kelas->count() }}
        </p>
        <p class="text-xs text-slate-500">
          Materi
        </p>
      </div>

    </div>

  </div>

</div>


<div class="mt-4 grid grid-cols-1 lg:grid-cols-4 gap-6">

    <!-- SIDEBAR -->
    <aside class="lg:col-span-1 bg-gradient-to-r from-blue-200 to-purple-200 rounded-xl border p-4 h-fit sticky top-6">
      <!-- SIDEBAR CONTENT -->
      <aside class="bg-white rounded-xl border p-4 space-y-4 sticky top-6">

        <!-- MATA KULIAH -->
        <div>
          <h3 class="font-semibold text-slate-800">
            Pemrograman Dasar
          </h3>
          <p class="text-sm text-slate-500">
            Kelas A
          </p>
        </div>
      
        <hr>
      
        <!-- FILTER PERTEMUAN -->
        <div class="space-y-1">
          <p class="text-xs font-semibold text-slate-400 uppercase mb-2">
            Pertemuan
          </p>
      
          <!-- SEMUA -->
          <a href="{{ request()->url() }}"
             class="block px-3 py-2 rounded-lg text-sm
                    {{ request('pertemuan') == null ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-slate-100' }}">
            Semua Pertemuan
          </a>
      
          @for ($i = 1; $i <= 14; $i++)
            <a href="{{ request()->url() }}?pertemuan={{ $i }}"
               class="block px-3 py-2 rounded-lg text-sm
                      {{ request('pertemuan') == $i ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-slate-100' }}">
              Pertemuan {{ $i }}
            </a>
          @endfor
        </div>
      
      </aside>
      


    </aside>
  
    <!-- MAIN CONTENT -->
    <section class="lg:col-span-3">
     
<!-- CARD LIST -->
<div class="space-y-5">



    @foreach ($materi_kelas as $materi)
        
   
      <!-- CARD PDF -->
      <div class="bg-white rounded-xl border p-5 flex justify-between items-start gap-4">
          <div class="flex gap-4">
              
            

            
            @php
            $tipe_file = strtolower($materi['file_type']);
        
            // 1️⃣ FILE TYPE → ICON
            $nameicon = match($tipe_file) {
                'pdf' => 'description',
                'zip' => 'folder_zip',
                'mp4', 'video' => 'videocam',
                'pptx', => 'Photo_Frame',
                'xlsx', => 'Data_Table',
                'docx', => 'Dictionary',
                default => 'insert_drive_file',
            };
        
            // 2️⃣ ICON → WARNA
            $bgicon = match($nameicon) {
                'description' => 'bg-red-100 text-red-600',
                'videocam' => 'bg-purple-100 text-purple-600',
                'folder_zip' => 'bg-gradient-to-r from-blue-200 to-purple-200 text-purple-600',
                'Photo_Frame' => 'bg-orange-100 text-orange-600',
                'Data_Table' => 'bg-green-100 text-green-600',
                'Dictionary' => 'bg-blue-100 text-blue-600',
                default => 'bg-slate-100 text-slate-700',
            };
        @endphp
        
                    <div class="{{ $bgicon }} p-2 rounded-lg h-fit">
                  <span class="material-symbols-rounded">{{ $nameicon }}</span>
              </div>

              <div>
                  <h3 class="font-semibold text-slate-800">
                      {{$materi ['judul_materi']}}
                  </h3>
                  <p class="text-sm text-slate-500 mt-1 max-w-xl">
                      {{$materi ['deskripsi']}}
                  </p>

                  <div class="flex items-center gap-4 mt-3 text-sm text-slate-500">
                      <span class="{{ $bgicon }} px-2 py-0.5 rounded-md text-xs font-medium">
                          {{$materi ['file_type']}}
                      </span>
                      <span>{{$materi ['created_at']}}</span>
                  </div>
              </div>
          </div>

          <button
          
              {{-- class="flex items-center gap-2 border px-4 py-2 rounded-lg text-sm hover:bg-slate-100 transition"> --}}
              class="mb-6  transform-gpu rounded-full bg-gradient-to-r from-blue-500 to-purple-500 px-8 py-4 font-bold text-white transition-transform hover:-translate-y-1 hover:shadow-lg">
              <a href="{{ asset('storage/' . $materi->file_path) }}" target="_blank">
            
           
            <span class="material-symbols-rounded text-base">Download</span>
            <span class="nav-label">Download</span>
              
            </a>
         
     
    </button> 
</div>
      @endforeach

  </div>



    </section>
  
  </div>
  

 

  







 {{-- @php
      $fileUrl = asset('storage/' . $materi->file_path);
      $type = strtolower($materi->file_type);
  @endphp
  
  
      <!-- 👁️ LIHAT / PREVIEW -->
      <a 
          href="{{ $fileUrl }}" 
          target="_blank"
          class="inline-flex items-center gap-2 px-3 py-1.5 
                 bg-blue-100 text-blue-700 rounded-md 
                 hover:bg-blue-200 transition text-sm"
          title="Lihat Materi"
      >
          <span class="material-symbols-rounded text-base">
              visibility
          </span>
          Lihat
      </a>--}}
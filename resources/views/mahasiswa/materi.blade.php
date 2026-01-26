<x-header> </x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

    

  <!-- FILTER BAR -->
  <div class="flex items-center gap-3 mb-6">
      <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg border shadow-sm">
          <span class="material-symbols-rounded text-slate-500">filter_list</span>
          <select class="bg-transparent outline-none text-sm text-slate-700">
              <option>Semua Mata Kuliah</option>
              <option>Algoritma</option>
              <option>Struktur Data</option>
          </select>
      </div>
  </div>

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
                default => 'insert_drive_file',
            };
        
            // 2️⃣ ICON → WARNA
            $bgicon = match($nameicon) {
                'description' => 'bg-red-100 text-red-600',
                'videocam' => 'bg-purple-100 text-purple-600',
                'folder_zip' => 'bg-green-100 text-green-600',
                default => 'bg-slate-100 text-slate-700',
            };
        @endphp
        
                    <div class="{{ $bgicon }} p-2 rounded-lg h-fit">
                  <span class="material-symbols-rounded">{{ $nameicon }}</span>
              </div>

              <div>
                  <h3 class="font-semibold text-slate-800">
                      {{$materi ['judul_materi']}}
                      <p class="text-sm text-slate-500 mt-1 max-w-xl">
                        {{$materi ['matkul']}}
                    </p>
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
    </button> 
</div>
      @endforeach









      



  </div>
</main>

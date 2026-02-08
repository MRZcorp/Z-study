<x-header>Materi Pembelajaran</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

@php
  $kelasNama = $kelas->mataKuliah->mata_kuliah ?? 'Materi Pembelajaran';
  $kelasKode = $kelas->nama_kelas ?? '-';
  $kelasHari = $kelas->hari_kelas ?? '-';
  $kelasJamMulai = isset($kelas) ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '';
  $kelasJamSelesai = isset($kelas) ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '';
  $dosenNama = $kelas->dosens->user->name ?? '-';
  $dosenGelar = $kelas->dosens->gelar ?? '';
  $dosenFoto = ($kelas->dosens && $kelas->dosens->poto_profil)
    ? asset('storage/' . $kelas->dosens->poto_profil)
    : asset('img/default_profil.jpg');
@endphp

<div class="bg-white rounded-xl border p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 relative">
  <a href="{{ route('mahasiswa.materi.kelas') }}"
     class="absolute top-4 left-4 z-9 inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700">
    <span class="material-symbols-rounded text-base">chevron_left</span>
  </a>


  <!-- KIRI : INFO KELAS -->
  <div>
    <h2 class="text-xl font-bold text-slate-800 pl-12">
      {{$kelasNama}}
    </h2>

    <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-slate-600">

      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-base">school</span>
        Kelas {{$kelasKode}}
      </span>

      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-base">calendar_today</span>
        {{$kelasHari}}
      </span>

      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-base">schedule</span>
        {{$kelasJamMulai}} - {{$kelasJamSelesai}}
      </span>

    </div>
  </div>

  <!-- KANAN : DOSEN + STAT -->
  <div class="flex flex-wrap items-center gap-6 text-sm">

    <!-- DOSEN -->
    <div class="flex items-center gap-3">
      <img src="{{$dosenFoto}}"
           class="w-10 h-10 rounded-full object-cover"
           alt="Dosen">

      <div>
        <p class="font-semibold text-slate-800">
          {{$dosenNama}} {{$dosenGelar}}
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
          {{ $materi_total_pertemuan ?? $materi_kelas->pluck('pertemuan')->filter()->unique()->count() }}
        </p>
        <p class="text-xs text-slate-500">
          Pertemuan
        </p>
      </div>

      <div class="text-center">
        <p class="text-lg font-bold text-slate-800">
          {{ $materi_total_count ?? $materi_kelas->count() }}
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
            {{$kelasNama}}
          </h3>
          <p class="text-sm text-slate-500">
            Kelas {{$kelasKode}}
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
              <span class="flex items-center justify-between">
                <span>Pertemuan {{ $i }}</span>
                @php $badge = ($pertemuanBadge ?? collect())->get($i); @endphp
                @if ($badge === 'new')
                  <span class="inline-flex items-center justify-center h-4 px-2 rounded-full bg-red-500 text-white text-[10px] font-semibold">
                    New
                  </span>
                @elseif ($badge === 'done')
                  <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-green-500 text-white text-[10px]">
                    ✓
                  </span>
                @endif
              </span>
            </a>
          @endfor
        </div>
      
      </aside>
    </aside>
  
    <!-- MAIN CONTENT -->
    <section class="lg:col-span-3">
     
@php
  $materiByPertemuan = $materi_kelas->groupBy('pertemuan')->sortKeys();
@endphp

<!-- CARD LIST -->
<div class="space-y-8">

  @if (!request('pertemuan'))
    @foreach ($materiByPertemuan as $pertemuan => $items)
      <div class="space-y-4">
        <div class="flex items-center gap-2">
          <span class="text-xs font-semibold text-blue-700 bg-blue-100 px-2.5 py-1 rounded-full">
            Pertemuan {{ $pertemuan ?? '-' }}
          </span>
          <span class="text-xs text-slate-500">({{ $items->count() }} materi)</span>
        </div>

        <div class="space-y-5">
          @foreach ($items as $materi)
            <!-- CARD FILE -->
            <div class="bg-white rounded-xl border p-5 flex gap-4 relative">
              <div class="flex gap-4">
            @php
            $tipe_file = strtolower($materi['file_type']);
        
            $nameicon = match($tipe_file) {
                'pdf' => 'description',
                'zip' => 'folder_zip',
                'mp4', 'video' => 'videocam',
                'pptx' => 'Photo_Frame',
                'xlsx' => 'Data_Table',
                'docx' => 'Dictionary',
                default => 'insert_drive_file',
            };
        
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
                {{$materi['judul_materi']}}
              </h3>
              <p class="text-sm text-slate-500 mt-1 max-w-xl">
                {{$materi['deskripsi']}}
              </p>

              <div class="flex items-center gap-4 mt-3 text-sm text-slate-500">
                <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">Pertemuan {{ $materi->pertemuan ?? '-' }}</span>
                <span class="{{ $bgicon }} px-2 py-0.5 rounded-md text-xs font-medium">
                  {{$materi['file_type']}}
                </span>
                <span>{{$materi['created_at']}}</span>
              </div>
            </div>
          </div>

              <a
                  href="{{ route('mahasiswa.materi.download', $materi->id) }}"
                  class="absolute bottom-4 right-4 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 px-3 py-1.5 text-sm font-semibold text-white hover:opacity-95"
              >
                <span class="material-symbols-rounded text-base">Download</span>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  @else
    @foreach ($materi_kelas as $materi)
      <!-- CARD FILE -->
      <div class="bg-white rounded-xl border p-5 flex gap-4 relative">
          <div class="flex gap-4">
            @php
            $tipe_file = strtolower($materi['file_type']);
        
            $nameicon = match($tipe_file) {
                'pdf' => 'description',
                'zip' => 'folder_zip',
                'mp4', 'video' => 'videocam',
                'pptx' => 'Photo_Frame',
                'xlsx' => 'Data_Table',
                'docx' => 'Dictionary',
                default => 'insert_drive_file',
            };
        
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
                {{$materi['judul_materi']}}
              </h3>
              <p class="text-sm text-slate-500 mt-1 max-w-xl">
                {{$materi['deskripsi']}}
              </p>

              <div class="flex items-center gap-4 mt-3 text-sm text-slate-500">
                <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">Pertemuan {{ $materi->pertemuan ?? '-' }}</span>
                <span class="{{ $bgicon }} px-2 py-0.5 rounded-md text-xs font-medium">
                  {{$materi['file_type']}}
                </span>
                <span>{{$materi['created_at']}}</span>
              </div>
            </div>
          </div>

          <a
              href="{{ route('mahasiswa.materi.download', $materi->id) }}"
              class="absolute bottom-4 right-4 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 px-3 py-1.5 text-sm font-semibold text-white hover:opacity-95"
          >
            <span class="material-symbols-rounded text-base">Download</span>
          </a>
      </div>
    @endforeach
  @endif

</div>

    </section>
</div>

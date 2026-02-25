<x-header>Materi Pembelajaran</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

<div class="p-6 bg-gray-100 min-h-screen">
  <div class="mb-6">
    <h2 class="text-xl font-semibold text-slate-800">Kelas yang Diikuti</h2>
    <p class="text-sm text-slate-500">Daftar kelas yang sudah kamu ikuti.</p>
  </div>

  <div class="materi-list-grid grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-3">
    @if ($pilih_kelas->isEmpty())
      <div class="col-span-full text-center text-gray-500">
        Belum ada kelas yang kamu ikuti.
      </div>
    @endif

    @foreach ($pilih_kelas as $kelas)
      <!-- CARD -->
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden relative">

        <!-- HEADER -->
        <div
          class="relative h-28 bg-cover bg-center"
          style="background-image: url({{ $kelas->bg_image ? asset('storage/' . $kelas->bg_image) : asset('img/Logo_Zstudy.png') }});"
        >
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
            {{$kelas->mataKuliah->sks ?? '-'}}
          </div>

          <div class="absolute inset-0 bg-black/30"></div>

          <div class="absolute bottom-3 left-2 text-white z-10">
            <h3 class="text-sm font-semibold leading-snug max-w-[70%] line-clamp-2">
              {{ $kelas->mataKuliah->mata_kuliah ?? '-' }}
            </h3>
          </div>

          @php
            $stats = $materiStats[$kelas->id] ?? ['total' => 0, 'pertemuan' => 0];
          @endphp
          @php
            $badge = $materiBadgeByKelas[$kelas->id] ?? null;
          @endphp
          @if($badge === 'new')
            <span
              class="materi-badge absolute top-2 right-2 z-20 inline-flex items-center gap-1 rounded-full bg-red-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow"
              data-kelas="{{ $kelas->slug }}"
              data-total="{{ $stats['total'] ?? 0 }}"
              title="Materi baru"
            >
              New
            </span>
          @endif

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

          <div class="h-2"></div>

          <p>{{$kelas->hari_kelas}}</p>
          <p>{{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }}
            - {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}</p>

          <div class="h-2"></div>

          <p class="font-medium text-gray-900">{{$kelas->dosens->user->name ?? '-' }} {{$kelas->dosens->gelar ?? ''}} </p>
        </div>

        <!-- FOOTER -->
        <div class="flex items-center justify-between px-4 py-3 border-t">
          <div class="flex items-center gap-4 text-sm text-slate-600">
            <span class="flex items-center gap-1">
              <span class="material-symbols-rounded text-blue-600 text-lg">school</span>
              <span class="font-semibold">{{ $stats['pertemuan'] ?? 0 }}</span>
            </span>
            <span class="flex items-center gap-1">
              <span class="material-symbols-rounded text-blue-600 text-lg">menu_book</span>
              <span class="font-semibold">{{ $stats['total'] ?? 0 }}</span>
            </span>
          </div>
          <a
            href="{{ route('mahasiswa.materi.kelas.detail', $kelas->slug) }}"
            class="btn-materi-view flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-blue-600 hover:bg-slate-200"
            data-kelas="{{ $kelas->slug }}"
            data-total="{{ $stats['total'] ?? 0 }}"
          >
            <span class="material-symbols-rounded text-base">visibility</span>Materi
          </a>
        </div>
      </div>
      <!-- END CARD -->
    @endforeach
  </div>
</div>

<style>
  @media (min-width: 1024px) {
    .materi-list-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    }

    .sidebar.collapsed ~ .main-content .materi-list-grid {
      grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }
  }
</style>

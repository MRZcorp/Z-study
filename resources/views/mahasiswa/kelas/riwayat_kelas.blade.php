<x-header>Riwayat Kelas</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

<!-- SUB NAVBAR -->
<div class="mb-6">
  <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
    <a href="{{ route('mahasiswa.kelas_saya') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">
      Kelas Saya
    </a>
    <a href="{{ route('mahasiswa.kelas_tersedia') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">
      Kelas Tersedia
    </a>
    <a href="{{ route('mahasiswa.kelas_riwayat') }}"
       class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">
      Riwayat Kelas
    </a>
  </div>
</div>



  <div class="p-6 bg-gray-100 min-h-screen">
        <div class="grid gap-6 justify-center [grid-template-columns:repeat(auto-fill,minmax(260px,260px))]">

        @if ($riwayat_kelas->isEmpty())
          <div class="col-span-full text-center text-gray-500">
            Belum ada riwayat kelas.
          </div>
        @endif

        @foreach ($riwayat_kelas as $kelas)
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
          <div
            class="relative h-28 bg-cover bg-center"
            style="background-image: url({{ $kelas->bg_image ? asset('storage/' . $kelas->bg_image) : asset('img/Logo_Zstudy.png') }});"
          >
          <div class="absolute inset-0 bg-black/30"></div>
          <div class="absolute top-1 left-0 z-9 flex items-center gap-1 bg-amber-50 text-gray-800 text-sm font-semibold px-2 py-1 rounded-r-full shadow">
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
            <img
              src="{{ $kelas->dosens && $kelas->dosens->poto_profil
                    ? asset('storage/' . $kelas->dosens->poto_profil)
                    : asset('img/default_profil.jpg') }}"
              class="absolute -bottom-10 right-4 w-20 h-20 rounded-full border-4 border-white object-cover z-10"
              alt="Avatar"
            />
          </div>
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
          <div class="flex items-center justify-between px-4 py-3 border-t">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-blue-600 text-lg">
                people
              </span>
              <span class="text-sm font-semibold text-green-600">
                {{ $kelas->mahasiswas_count ?? 0 }} / {{ $kelas->kuota_maksimal }}
              </span>
            </div>
          </div>
        </div>
        @endforeach
  </div>


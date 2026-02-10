<x-header>Riwayat Kelas</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

<!-- HEADER -->
<div class="mb-6 flex items-center justify-between">
  <div class="flex items-center gap-3">
    <a href="{{ route('mahasiswa.kelas_riwayat') }}"
       class="flex items-center justify-center w-9 h-9 rounded-lg border bg-blue-600 text-white">
      <span class="material-symbols-rounded text-base">chevron_left</span>
    </a>
    <div>
      <h2 class="text-lg font-semibold text-gray-800">Riwayat Kelas</h2>
      <p class="text-sm text-gray-500">
        {{ $kelas->mataKuliah->mata_kuliah ?? '-' }} - {{ $kelas->nama_kelas ?? '-' }}
      </p>
    </div>
  </div>
</div>

@php
  $tab = $tab ?? request('tab', 'materi');
@endphp

<!-- SUB NAVBAR DETAIL -->
<div class="mb-6">
  <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
    <a href="{{ route('mahasiswa.kelas_riwayat.detail', $kelas->id) }}?tab=materi"
       class="px-4 py-2 text-sm font-semibold rounded-lg {{ $tab === 'materi' ? 'bg-blue-800 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
      Materi
    </a>
    <a href="{{ route('mahasiswa.kelas_riwayat.detail', $kelas->id) }}?tab=tugas"
       class="px-4 py-2 text-sm font-semibold rounded-lg {{ $tab === 'tugas' ? 'bg-blue-800 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
      Tugas
    </a>
    <a href="{{ route('mahasiswa.kelas_riwayat.detail', $kelas->id) }}?tab=ujian"
       class="px-4 py-2 text-sm font-semibold rounded-lg {{ $tab === 'ujian' ? 'bg-blue-800 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
      Ujian
    </a>
  </div>
</div>

@php
  $tab = request('tab', 'materi');
@endphp

@if ($tab === 'tugas')
  @include('mahasiswa.kelas._riwayat_tugas_content', [
    'tugas_selesai' => $tugas_selesai ?? collect()
  ])
@elseif ($tab === 'ujian')
  @include('mahasiswa.kelas._riwayat_ujian_content', [
    'ujian_selesai' => $ujian_selesai ?? collect(),
    'jawabanMap' => $jawabanMap ?? [],
    'nilaiMap' => $nilaiMap ?? []
  ])
@else
  @include('mahasiswa.kelas._riwayat_materi_content', [
    'kelas' => $kelas,
    'materi_kelas' => $materi_kelas,
    'materi_total_count' => $materi_total_count ?? null,
    'materi_total_pertemuan' => $materi_total_pertemuan ?? null,
    'pertemuanHasMateri' => $pertemuanHasMateri ?? null,
  ])
@endif

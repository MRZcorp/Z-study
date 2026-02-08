<x-header>Rekap Nilai</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

@include('dosen.rekap._riwayat_rekap_content', [
  'kelas' => $kelas ?? null,
  'tugasList' => $tugasList ?? collect(),
  'ujianList' => $ujianList ?? collect(),
  'pengumpulanMap' => $pengumpulanMap ?? collect(),
  'hasilUjianMap' => $hasilUjianMap ?? collect(),
  'rekapMap' => $rekapMap ?? collect(),
  'showBack' => true,
])

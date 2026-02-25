<x-header>Ujian Selesai</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

@include('dosen.ujian._riwayat_ujian_content')

<script>
  window.__chatContextType = 'ujian';
  window.__chatBaseUrlTemplate = @json(route('dosen.ujian.diskusi.index', ['ujian' => '__CTX_ID__']));
  window.__chatMessageUrlTemplate = @json(route('dosen.ujian.diskusi.update', ['ujian' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']));
</script>
@include('dosen.kelas.partials.chat_modal')

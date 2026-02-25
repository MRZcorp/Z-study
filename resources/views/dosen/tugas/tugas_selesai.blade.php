<x-header>Data Tugas</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

@include('dosen.tugas._riwayat_tugas_content')

<script>
  window.__chatContextType = 'tugas';
  window.__chatBaseUrlTemplate = @json(route('dosen.tugas.diskusi.index', ['tugas' => '__CTX_ID__']));
  window.__chatMessageUrlTemplate = @json(route('dosen.tugas.diskusi.update', ['tugas' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']));
</script>
@include('dosen.kelas.partials.chat_modal')

@php
    use App\Models\Dosen;
    use App\Models\Mahasiswa;

    $foto = null;

    if (session('nama_role') === 'dosen') {
        $dosen = Dosen::where('user_id', session('user_id'))->first();
        $foto = $dosen?->poto_profil;
        $nama =  $dosen?->user->name;
        $role = $dosen?->user->role->nama_role;
    }

    if (session('nama_role') === 'mahasiswa') {
        $mahasiswa = Mahasiswa::where('user_id', session('user_id'))->first();
        $foto = $mahasiswa?->poto_profil;
        $nama =  $mahasiswa?->user->name;
        $role = $mahasiswa?->user->role->nama_role;
    }
@endphp
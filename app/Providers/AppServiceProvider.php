<?php

namespace App\Providers;

use App\Models\Dosen;
use App\Models\Diskusi;
use App\Models\DiskusiRead;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\PengumumanRead;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {

            $foto = null;
            $nama = null;
            $email = null;
            $role = null;
            $id_user = null;
            $fakultas = null;
            $prodi = null;
            $profil = null;
            $angkatan = null;
            $setting = null;
            $gelar = null;
            $no_hp = null;
            $bg = null;
            $jenjang = null;
            $navbarKelasIds = collect();
            $navbarDiskusi = collect();
            $navbarDiskusiCount = 0;
            $navbarDiskusiHasNew = false;
            $navbarDiskusiMarkReadRoute = null;
    
 // Kalau belum login / session kosong → kirim default dan STOP
 if (!session()->has('user_id') || !session()->has('nama_role')) {
    $view->with(compact('foto', 'nama', 'role', 'id_user'));
    return;
}





            $userId = session('user_id');
            $roleName = session('nama_role');
            $roleKey = strtolower($roleName ?? '');
    
            if ($roleKey === 'dosen') {
                $dosen = Dosen::with(['user.role', 'fakultas', 'programStudi'])
                    ->where('user_id', $userId)
                    ->first();
    
                if ($dosen) {
                    $foto = $dosen->poto_profil;
                    $bg = $dosen->bg;
                    $nama = $dosen->user->name ?? null;
                    $role = $dosen->user->role->nama_role ?? null;
                    $id_user = $dosen->nidn ?? null;
                    $email = $dosen->email ?? null;
                    $gelar = $dosen->gelar ?? null;
                    $no_hp = $dosen->no_hp ?? null;
                    $fakultas = $dosen->fakultas->fakultas ?? null;
                    $prodi = $dosen->programStudi->nama_prodi ?? null;
                    $profil = '/dosen/profil';
                    $setting = '/dosen/pengaturan';
                    $navbarKelasIds = Kelas::where('dosen_id', $dosen->id)->pluck('id');
                    $navbarDiskusiMarkReadRoute = route('dosen.diskusi.mark_read');
                }
            }
    
            if ($roleKey === 'mahasiswa') {
                $mahasiswa = Mahasiswa::with(['user.role', 'fakultas', 'programStudi', 'angkatan'])
                    ->where('user_id', $userId)
                    ->first();
    
                if ($mahasiswa) {
                    $foto = $mahasiswa->poto_profil;
                    $bg = $mahasiswa->bg;
                    $nama = $mahasiswa->user->name ?? null;
                    $id_user = $mahasiswa->nim ?? null;
                    $role = $mahasiswa->user->role->nama_role ?? null;
                    $fakultas = $mahasiswa->fakultas->fakultas ?? null;
                    $prodi = $mahasiswa->programStudi->nama_prodi ?? null;
                    $email = $mahasiswa->email ?? null;
                    $no_hp = $mahasiswa->no_hp ?? null;
                    $angkatan = $mahasiswa->angkatan?->tahun ?? null;
                    $jenjang = $mahasiswa->jenjang ?? null;
                    $profil = '/mahasiswa/profil';
                    $setting = '/mahasiswa/pengaturan';
                    $navbarKelasIds = Kelas::whereHas('mahasiswas', function ($q) use ($mahasiswa) {
                        $q->where('mahasiswa_id', $mahasiswa->id)
                            ->where(function ($sq) {
                                $sq->whereNull('kelas_mahasiswa.status')
                                    ->orWhere('kelas_mahasiswa.status', 'disetujui');
                            });
                    })->pluck('id');
                    $navbarDiskusiMarkReadRoute = route('mahasiswa.diskusi.mark_read');
                }
            }

            if ($roleKey === 'admin') {
                $user = User::with('role')->where('id', $userId)->first();
                $dosen = Dosen::with('user.role')->where('user_id', $userId)->first();
                if ($user) {
                    $foto = $dosen?->poto_profil ?? null;
                    $nama = $user->name ?? null;
                    $role = $user->role->nama_role ?? null;
                    $id_user = $dosen?->nidn ?? ($user->username ?? $user->email ?? null);
                    $email = $dosen?->email ?? $user->email ?? null;
                    $profil = '/admin/user_profile';
                    $setting = '/admin/pengaturan';
                }
            }
    
            $shared = compact(
                'foto',
                'nama',
                'role',
                'id_user',
                'fakultas',
                'bg',
                'prodi',
                'email',
                'profil',
                'angkatan',
                'setting',
                'gelar',
                'no_hp',
                'jenjang'
            );

            $navbarPengumuman = Pengumuman::where('is_active', true)
                ->orderByRaw('COALESCE(tanggal_publish, created_at) DESC')
                ->limit(5)
                ->get();
            $navbarPengumumanCount = $navbarPengumuman->count();
            $navbarHasNew = false;
            if ($userId) {
                $navbarHasNew = Pengumuman::where('is_active', true)
                    ->whereNotExists(function ($q) use ($userId) {
                        $q->selectRaw(1)
                          ->from('pengumuman_reads')
                          ->whereColumn('pengumuman_reads.pengumuman_id', 'pengumumen.id')
                          ->where('pengumuman_reads.user_id', $userId);
                    })
                    ->exists();
            }

            if ($userId && in_array($roleKey, ['dosen', 'mahasiswa'], true) && $navbarKelasIds->isNotEmpty()) {
                $kelasRows = Kelas::with('mataKuliah:id,mata_kuliah')
                    ->whereIn('id', $navbarKelasIds)
                    ->get()
                    ->keyBy('id');

                $ujianRows = Ujian::select('id', 'nama_ujian', 'nama_kelas_id')
                    ->whereIn('nama_kelas_id', $navbarKelasIds)
                    ->get()
                    ->keyBy('id');
                $ujianIds = $ujianRows->keys();

                $tugasRows = Tugas::select('id', 'nama_tugas', 'nama_kelas_id')
                    ->whereIn('nama_kelas_id', $navbarKelasIds)
                    ->get()
                    ->keyBy('id');
                $tugasIds = $tugasRows->keys();

                $kelasLatest = Diskusi::selectRaw('MAX(id) as latest_id, kelas_id as context_id')
                    ->whereIn('kelas_id', $navbarKelasIds)
                    ->whereNull('ujian_id')
                    ->whereNull('tugas_id')
                    ->groupBy('kelas_id')
                    ->get();

                $ujianLatest = $ujianIds->isNotEmpty()
                    ? Diskusi::selectRaw('MAX(id) as latest_id, ujian_id as context_id')
                        ->whereIn('ujian_id', $ujianIds)
                        ->groupBy('ujian_id')
                        ->get()
                    : collect();

                $tugasLatest = $tugasIds->isNotEmpty()
                    ? Diskusi::selectRaw('MAX(id) as latest_id, tugas_id as context_id')
                        ->whereIn('tugas_id', $tugasIds)
                        ->groupBy('tugas_id')
                        ->get()
                    : collect();

                $latestIds = $kelasLatest->pluck('latest_id')
                    ->merge($ujianLatest->pluck('latest_id'))
                    ->merge($tugasLatest->pluck('latest_id'))
                    ->filter()
                    ->unique()
                    ->values();

                $latestMessages = $latestIds->isNotEmpty()
                    ? Diskusi::with('user:id,name')->whereIn('id', $latestIds)->get()->keyBy('id')
                    : collect();

                $kelasReads = DiskusiRead::where('user_id', $userId)
                    ->where('context_type', 'kelas')
                    ->whereIn('context_id', $kelasLatest->pluck('context_id')->filter())
                    ->get()
                    ->keyBy('context_id');
                $ujianReads = DiskusiRead::where('user_id', $userId)
                    ->where('context_type', 'ujian')
                    ->whereIn('context_id', $ujianLatest->pluck('context_id')->filter())
                    ->get()
                    ->keyBy('context_id');
                $tugasReads = DiskusiRead::where('user_id', $userId)
                    ->where('context_type', 'tugas')
                    ->whereIn('context_id', $tugasLatest->pluck('context_id')->filter())
                    ->get()
                    ->keyBy('context_id');

                $defaultKelasThumb = asset('img/grup.png');
                $kelasThumbUrl = function ($rawPath) use ($defaultKelasThumb) {
                    $path = trim((string) ($rawPath ?? ''));
                    if ($path === '') {
                        return $defaultKelasThumb;
                    }
                    if (Str::startsWith($path, ['http://', 'https://', '/'])) {
                        return $path;
                    }
                    return asset('storage/' . ltrim($path, '/'));
                };

                $makeItem = function (string $type, int $contextId, ?int $latestId) use (
                    $latestMessages,
                    $kelasRows,
                    $ujianRows,
                    $tugasRows,
                    $kelasReads,
                    $ujianReads,
                    $tugasReads,
                    $roleKey,
                    $userId,
                    $kelasThumbUrl
                ) {
                    if (!$latestId || !$latestMessages->has($latestId)) {
                        return null;
                    }
                    $msg = $latestMessages->get($latestId);
                    $title = '-';
                    $url = '#';
                    $typeLabel = ucfirst($type);
                    $readAt = null;
                    $thumb = asset('img/grup.png');

                    if ($type === 'kelas') {
                        $kelas = $kelasRows->get($contextId);
                        $matkul = $kelas?->mataKuliah?->mata_kuliah ?? '-';
                        $kelasNama = $kelas?->nama_kelas ?? '-';
                        $title = "Kelas {$kelasNama} - {$matkul}";
                        $url = $roleKey === 'dosen' ? url('/dosen/kelas') : url('/mahasiswa/kelas');
                        $typeLabel = 'Diskusi Kelas';
                        $readAt = optional($kelasReads->get($contextId))->last_read_at;
                        $thumb = $kelasThumbUrl($kelas?->bg_image);
                    } elseif ($type === 'ujian') {
                        $ujian = $ujianRows->get($contextId);
                        $title = $ujian?->nama_ujian ?? "Ujian #{$contextId}";
                        $url = $roleKey === 'dosen' ? url('/dosen/ujian') : url('/mahasiswa/ujian');
                        $typeLabel = 'Diskusi Ujian';
                        $readAt = optional($ujianReads->get($contextId))->last_read_at;
                        $thumb = $kelasThumbUrl($kelasRows->get((int) ($ujian?->nama_kelas_id ?? 0))?->bg_image);
                    } else {
                        $tugas = $tugasRows->get($contextId);
                        $title = $tugas?->nama_tugas ?? "Tugas #{$contextId}";
                        $url = $roleKey === 'dosen' ? url('/dosen/tugas') : url('/mahasiswa/tugas');
                        $typeLabel = 'Diskusi Tugas';
                        $readAt = optional($tugasReads->get($contextId))->last_read_at;
                        $thumb = $kelasThumbUrl($kelasRows->get((int) ($tugas?->nama_kelas_id ?? 0))?->bg_image);
                    }

                    $isUnread = ((int) ($msg->user_id ?? 0) !== (int) $userId)
                        && (!$readAt || optional($msg->created_at)->gt($readAt));

                    return [
                        'type' => $type,
                        'context_id' => $contextId,
                        'type_label' => $typeLabel,
                        'title' => $title,
                        'url' => $url,
                        'sender' => $msg->user?->name ?? '-',
                        'pesan' => (string) ($msg->pesan ?? '-'),
                        'thumb' => $thumb,
                        'time' => $msg->created_at
                            ? $msg->created_at->locale('id')->diffForHumans()
                            : '-',
                        'latest_at' => optional($msg->created_at),
                        'unread' => $isUnread,
                    ];
                };

                $items = collect();
                foreach ($kelasLatest as $row) {
                    $item = $makeItem('kelas', (int) $row->context_id, (int) $row->latest_id);
                    if ($item) $items->push($item);
                }
                foreach ($ujianLatest as $row) {
                    $item = $makeItem('ujian', (int) $row->context_id, (int) $row->latest_id);
                    if ($item) $items->push($item);
                }
                foreach ($tugasLatest as $row) {
                    $item = $makeItem('tugas', (int) $row->context_id, (int) $row->latest_id);
                    if ($item) $items->push($item);
                }

                $navbarDiskusi = $items
                    ->sortByDesc(fn ($i) => optional($i['latest_at'])->timestamp ?? 0)
                    ->values()
                    ->take(8);

                $navbarDiskusiCount = $navbarDiskusi->count();
                $navbarDiskusiHasNew = $navbarDiskusi->contains(fn ($i) => (bool) ($i['unread'] ?? false));
            }

            $existing = $view->getData();
            foreach ($shared as $key => $value) {
                if (array_key_exists($key, $existing)) {
                    $current = $existing[$key];
                    if ($current !== null && $current !== '') {
                        continue;
                    }
                }

                $view->with($key, $value);
            }

            $view->with('navbarPengumuman', $navbarPengumuman);
            $view->with('navbarPengumumanCount', $navbarPengumumanCount);
            $view->with('navbarHasNew', $navbarHasNew);
            $view->with('navbarDiskusi', $navbarDiskusi);
            $view->with('navbarDiskusiCount', $navbarDiskusiCount);
            $view->with('navbarDiskusiHasNew', $navbarDiskusiHasNew);
            $view->with('navbarDiskusiMarkReadRoute', $navbarDiskusiMarkReadRoute);
        });

    }
}

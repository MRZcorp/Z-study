<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\KalenderAkademik;
use App\Models\KrsSetting;
use App\Models\AdminActivity;
use Illuminate\Support\Carbon;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Echo_;

class AdminController extends Controller
{
    //
    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'aktif')->count();
        $inactiveUsers = User::where('status', 'nonaktif')->count();

        $totalDosen = Dosen::count();
        $activeDosen = Dosen::where('status', 'aktif')->count();

        $totalMahasiswa = Mahasiswa::count();
        $activeKelas = Kelas::where('status', 'aktif')->count();

        $systemHealth = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100) : 100;

        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startDate->copy()->addDays($i);
        }

        $userCounts = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date');

        $kelasCounts = Kelas::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartUsers = [];
        $chartKelas = [];
        foreach ($dates as $date) {
            $key = $date->toDateString();
            $chartLabels[] = $date->format('d M');
            $chartUsers[] = (int) ($userCounts[$key] ?? 0);
            $chartKelas[] = (int) ($kelasCounts[$key] ?? 0);
        }

        $recentActions = collect();
        $recentUsers = User::latest()->take(5)->get();
        foreach ($recentUsers as $user) {
            $recentActions->push([
                'icon' => 'Person_Add',
                'title' => 'Pengguna baru',
                'detail' => $user->name ?? '-',
                'time' => $user->created_at,
            ]);
        }
        $recentKelas = Kelas::latest()->take(5)->get();
        foreach ($recentKelas as $kelas) {
            $recentActions->push([
                'icon' => 'calendar_month',
                'title' => 'Kelas baru',
                'detail' => $kelas->nama_kelas ?? '-',
                'time' => $kelas->created_at,
            ]);
        }
        $recentAdminActivities = AdminActivity::latest()->take(5)->get();
        foreach ($recentAdminActivities as $activity) {
            $recentActions->push([
                'icon' => $activity->icon ?? 'flag',
                'title' => $activity->title ?? 'Aktivitas Admin',
                'detail' => $activity->detail ?? '-',
                'time' => $activity->created_at,
            ]);
        }
        $recentActions = $recentActions
            ->sortByDesc('time')
            ->take(6)
            ->values();

        $kalenderAkademik = KalenderAkademik::orderBy('tanggal_mulai')
            ->orderBy('tanggal_selesai')
            ->get();

        $tahunAjaranOptions = $kalenderAkademik
            ->map(function ($item) {
                $mulai = Carbon::parse($item->tanggal_mulai)->year;
                $akhir = $mulai + 1;
                return [
                    'mulai' => $mulai,
                    'akhir' => $akhir,
                    'label' => $mulai . '/' . $akhir,
                ];
            })
            ->unique('label')
            ->values();

        if ($tahunAjaranOptions->isEmpty()) {
            $currentYear = Carbon::now()->year;
            $tahunAjaranOptions = collect([
                [
                    'mulai' => $currentYear,
                    'akhir' => $currentYear + 1,
                    'label' => $currentYear . '/' . ($currentYear + 1),
                ],
            ]);
        }

        $now = Carbon::now();
        $activeSetting = KrsSetting::where('status', 'aktif')->latest()->first();

        if ($activeSetting) {
            $selectedTahunMulai = $activeSetting->mulai_tahun_ajar;
            $selectedTahunAkhir = $activeSetting->akhir_tahun_ajar;
            $selectedSemester = $activeSetting->semester;
        } else {
            $selectedTahunAjaran = $tahunAjaranOptions
                ->firstWhere('mulai', $now->year) ?? $tahunAjaranOptions->last() ?? $tahunAjaranOptions->first();
            $selectedTahunMulai = $selectedTahunAjaran['mulai'];
            $selectedTahunAkhir = $selectedTahunAjaran['akhir'];
            $selectedSemester = $now->month >= 7 ? 'ganjil' : 'genap';
        }

        $tahunMulaiBase = $selectedTahunMulai;
        $tahunMulaiDropdown = collect(range($tahunMulaiBase - 2, $tahunMulaiBase + 2))
            ->unique()
            ->values();

        $krsSettings = KrsSetting::all()->mapWithKeys(function ($item) {
            $key = $item->mulai_tahun_ajar . '-' . $item->akhir_tahun_ajar . '-' . $item->semester;
            return [$key => $item->status];
        });

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'systemHealth',
            'totalDosen',
            'activeDosen',
            'totalMahasiswa',
            'activeKelas',
            'chartLabels',
            'chartUsers',
            'chartKelas',
            'recentActions',
            'kalenderAkademik',
            'tahunAjaranOptions',
            'selectedTahunMulai',
            'selectedTahunAkhir',
            'tahunMulaiDropdown',
            'selectedSemester',
            'krsSettings'
        ));
        
    }

    public function storeKalender(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['nullable', 'string'],
        ]);

        KalenderAkademik::create($data);

        return back();
    }

    public function updateKalender(Request $request, KalenderAkademik $kalender)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $kalender->update($data);

        return back();
    }

    public function destroyKalender(KalenderAkademik $kalender)
    {
        $kalender->delete();

        return back();
    }

    public function upsertKrs(Request $request)
    {
        $data = $request->validate([
            'mulai_tahun_ajar' => ['required', 'integer'],
            'akhir_tahun_ajar' => ['required', 'integer', 'gte:mulai_tahun_ajar'],
            'semester' => ['required', 'in:ganjil,genap'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $password = (string) $request->input('password', '');
        if (trim($password) === '') {
            return back()->with('error', 'Password wajib diisi.');
        }
        $userId = session('user_id');
        $user = $userId ? User::find($userId) : null;
        if (!$user || !Hash::check($password, $user->password)) {
            return back()->with('error', 'Password salah. Perubahan KRS dibatalkan.');
        }

        $previousActive = KrsSetting::where('status', 'aktif')->latest()->first();
        $existingSetting = KrsSetting::where('mulai_tahun_ajar', $data['mulai_tahun_ajar'])
            ->where('akhir_tahun_ajar', $data['akhir_tahun_ajar'])
            ->where('semester', $data['semester'])
            ->first();
        $previousStatus = $existingSetting?->status;

        KrsSetting::updateOrCreate(
            [
                'mulai_tahun_ajar' => $data['mulai_tahun_ajar'],
                'akhir_tahun_ajar' => $data['akhir_tahun_ajar'],
                'semester' => $data['semester'],
            ],
            [
                'status' => $data['status'],
            ]
        );

        if ($data['status'] === 'aktif') {
            KrsSetting::where('mulai_tahun_ajar', $data['mulai_tahun_ajar'])
                ->where('akhir_tahun_ajar', $data['akhir_tahun_ajar'])
                ->where('semester', '!=', $data['semester'])
                ->update(['status' => 'nonaktif']);

            if (!$previousActive) {
                if ($data['semester'] === 'ganjil') {
                    Mahasiswa::query()->update(['semester_aktif' => 1]);
                }
            } elseif ($previousActive->semester !== $data['semester'] || $previousActive->mulai_tahun_ajar !== $data['mulai_tahun_ajar'] || $previousActive->akhir_tahun_ajar !== $data['akhir_tahun_ajar']) {
                Mahasiswa::query()->increment('semester_aktif');
            }
        }

        if ($previousStatus !== $data['status']) {
            $labelSemester = ucfirst($data['semester']);
            $labelTahun = $data['mulai_tahun_ajar'] . '/' . $data['akhir_tahun_ajar'];
            AdminActivity::create([
                'user_id' => $userId,
                'icon' => $data['status'] === 'aktif' ? 'verified' : 'block',
                'title' => $data['status'] === 'aktif' ? 'KRS Diaktifkan' : 'KRS Dinonaktifkan',
                'detail' => $labelSemester . ' ' . $labelTahun,
            ]);
        }

        $statusLabel = $data['status'] === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'KRS berhasil ' . $statusLabel . '.');
    }
}

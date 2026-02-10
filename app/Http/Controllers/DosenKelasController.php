<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\KrsSetting;
use App\Models\MateriKelas;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\PengumpulanTugas;
use App\Models\HasilUjian;
use App\Models\RekapNilai;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DosenKelasController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $dosen = Dosen::where('user_id', $userId)->first();
        $dosenId = $dosen?->id;

        $krsAktif = KrsSetting::where('status', 'aktif')->latest()->first();
        $semesterAktif = $krsAktif?->semester;
        $tahunAjarAktif = $krsAktif ? ($krsAktif->mulai_tahun_ajar . ' / ' . $krsAktif->akhir_tahun_ajar) : null;

        $pilih_kelas = Kelas::with(['dosens', 'mataKuliah', 'mahasiswas.user'])
            ->withCount('mahasiswas')
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->where('status', 'aktif')
            ->when($tahunAjarAktif, fn($q) => $q->where('tahun_ajar', $tahunAjarAktif))
            ->latest()
            ->get();

        $prodis = ProgramStudi::when($dosen?->fakultas_id, fn($q) => $q->where('fakultas_id', $dosen->fakultas_id))
            ->orderBy('nama_prodi')
            ->get();

        $mataKuliahs = MataKuliah::with('programStudis')
            ->where('status', 'aktif')
            ->when($dosen?->fakultas_id, function ($q) use ($dosen) {
                $q->whereHas('programStudis', fn($mq) => $mq->where('fakultas_id', $dosen->fakultas_id));
            })
            ->when($semesterAktif, fn($q) => $q->where('semester', $semesterAktif))
            ->orderBy('kode_mata_kuliah')
            ->get();

        return view('dosen.kelas.kelas', compact(
            'pilih_kelas',
            'mataKuliahs',
            'prodis',
            'semesterAktif',
            'tahunAjarAktif'
        ));
    }

    public function riwayat()
    {
        $userId = session('user_id');
        $dosen = Dosen::where('user_id', $userId)->first();
        $dosenId = $dosen?->id;

        $riwayat_kelas = Kelas::with(['dosens', 'mataKuliah', 'mahasiswas.user'])
            ->withCount('mahasiswas')
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->where('status', 'selesai')
            ->latest()
            ->get();

        return view('dosen.kelas.list_riwayat_kelas', compact('riwayat_kelas'));
    }

    public function riwayatDetail(Kelas $kelas)
    {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        if (!$dosenId || $kelas->dosen_id !== $dosenId) {
            abort(403, 'Akses kelas tidak diizinkan.');
        }

        $materiQuery = MateriKelas::query()
            ->where('kelas_id', $kelas->id)
            ->latest();

        if (request('pertemuan')) {
            $materiQuery->where('pertemuan', request('pertemuan'));
        }

        $materi_kelas = $materiQuery->get();

        $pertemuanHasMateri = MateriKelas::where('kelas_id', $kelas->id)
            ->pluck('pertemuan')
            ->filter()
            ->unique();

        $materi_total_count = MateriKelas::where('kelas_id', $kelas->id)->count();
        $materi_total_pertemuan = MateriKelas::where('kelas_id', $kelas->id)
            ->pluck('pertemuan')
            ->filter()
            ->unique()
            ->count();

        $tugas_selesai = Tugas::with([
                'mataKuliah',
                'kelas' => fn($q) => $q->withCount('mahasiswas'),
                'files',
                'pengumpulan.mahasiswa.user',
            ])
            ->where('nama_kelas_id', $kelas->id)
            ->whereNotNull('deadline')
            ->where('deadline', '<', Carbon::now())
            ->latest()
            ->get();

        $ujian_selesai = Ujian::with([
                'mataKuliah',
                'kelas' => fn($q) => $q->withCount('mahasiswas'),
                'soals',
                'hasilUjian.mahasiswa.user',
            ])
            ->where('nama_kelas_id', $kelas->id)
            ->whereNotNull('deadline')
            ->where('deadline', '<', Carbon::now())
            ->latest()
            ->get();

        $kelas->load(['mataKuliah', 'dosens', 'mahasiswas.user', 'mahasiswas.programStudi']);
        $tugasList = Tugas::where('nama_kelas_id', $kelas->id)
            ->orderBy('created_at')
            ->get();
        $ujianList = Ujian::where('nama_kelas_id', $kelas->id)
            ->orderBy('created_at')
            ->get();

        $mahasiswaIds = $kelas->mahasiswas->pluck('id');
        $tugasIds = $tugasList->pluck('id');
        $ujianIds = $ujianList->pluck('id');

        $pengumpulanMap = PengumpulanTugas::whereIn('tugas_id', $tugasIds)
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->get()
            ->groupBy(['mahasiswa_id', 'tugas_id']);

        $hasilUjianMap = HasilUjian::whereIn('ujian_id', $ujianIds)
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->get()
            ->groupBy(['mahasiswa_id', 'ujian_id']);

        $rekapMap = RekapNilai::where('kelas_id', $kelas->id)
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->get()
            ->keyBy('mahasiswa_id');

        return view('dosen.kelas.riwayat_kelas', compact(
            'kelas',
            'materi_kelas',
            'materi_total_count',
            'materi_total_pertemuan',
            'pertemuanHasMateri',
            'tugas_selesai',
            'ujian_selesai',
            'tugasList',
            'ujianList',
            'pengumpulanMap',
            'hasilUjianMap',
            'rekapMap'
        ));
    }

    public function create()
    {
        $mataKuliahs = MataKuliah::with('programStudis')
            ->where('status', 'aktif')
            ->orderBy('kode_mata_kuliah')
            ->get();

        $prodis = ProgramStudi::orderBy('nama_prodi')->get();

        return view('dosen.kelas.buat_kelas', compact('mataKuliahs', 'prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_kelas' => 'required|string|max:10',
            'jadwal_kelas' => 'required|string|max:50',
            'hari_kelas' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'kuota_maksimal' => 'required|integer|min:1',
            'bg_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tahun_ajar' => 'nullable|string|max:20',
            'semester' => 'nullable|in:ganjil,genap',
            'status' => 'nullable|in:draft,aktif,selesai',
        ]);

        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        $bg_image = null;
        if ($request->hasFile('bg_image')) {
            $bg_image = $request->file('bg_image')->store('img', 'public');
        }

        $namaKelas = preg_replace('/^kelas\\s*/i', '', (string) ($request->nama_kelas ?? ''));
        $namaKelas = strtoupper(trim($namaKelas));

        $kelas = Kelas::create([
            'dosen_id' => $dosenId,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'tahun_ajar' => $request->tahun_ajar,
            'semester' => $request->semester,
            'nama_kelas' => $namaKelas,
            'jadwal_kelas' => $request->jadwal_kelas,
            'hari_kelas' => $request->hari_kelas,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota_maksimal' => $request->kuota_maksimal,
            'bg_image' => $bg_image,
            'status' => $request->status ?? 'draft',
        ]);

        $matkulName = MataKuliah::where('id', $request->mata_kuliah_id)->value('mata_kuliah');
        $baseSlug = Str::slug(trim(($matkulName ?? '') . ' ' . ($namaKelas ?? '')), '_');
        $slug = $baseSlug ?: ('kelas_' . $kelas->id);
        if (Kelas::where('slug', $slug)->where('id', '!=', $kelas->id)->exists()) {
            $slug = $slug . '_' . $kelas->id;
        }
        $kelas->update(['slug' => $slug]);

        return redirect()
            ->route('dosen.kelas')
            ->with('success', 'Kelas berhasil dibuat');
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_kelas' => 'required|string|max:10',
            'jadwal_kelas' => 'required|string|max:50',
            'hari_kelas' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'kuota_maksimal' => 'required|integer|min:1',
            'bg_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tahun_ajar' => 'nullable|string|max:20',
            'semester' => 'nullable|in:ganjil,genap',
            'status' => 'nullable|in:draft,aktif,selesai',
        ]);

        $bg_image = $kelas->bg_image;
        if ($request->hasFile('bg_image')) {
            $bg_image = $request->file('bg_image')->store('img', 'public');
            if ($kelas->bg_image && Storage::disk('public')->exists($kelas->bg_image)) {
                Storage::disk('public')->delete($kelas->bg_image);
            }
        }

        $namaKelas = preg_replace('/^kelas\\s*/i', '', (string) ($request->nama_kelas ?? ''));
        $namaKelas = strtoupper(trim($namaKelas));

        $kelas->update([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'tahun_ajar' => $request->tahun_ajar,
            'semester' => $request->semester,
            'nama_kelas' => $namaKelas,
            'jadwal_kelas' => $request->jadwal_kelas,
            'hari_kelas' => $request->hari_kelas,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota_maksimal' => $request->kuota_maksimal,
            'bg_image' => $bg_image,
            'status' => $request->status ?? $kelas->status,
        ]);

        $matkulName = MataKuliah::where('id', $request->mata_kuliah_id)->value('mata_kuliah');
        $baseSlug = Str::slug(trim(($matkulName ?? '') . ' ' . ($namaKelas ?? '')), '_');
        $slug = $baseSlug ?: ('kelas_' . $kelas->id);
        if (Kelas::where('slug', $slug)->where('id', '!=', $kelas->id)->exists()) {
            $slug = $slug . '_' . $kelas->id;
        }
        $kelas->update(['slug' => $slug]);

        return redirect()
            ->route('dosen.kelas')
            ->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        if (!$dosenId || $kelas->dosen_id !== $dosenId) {
            abort(403, 'Akses kelas tidak diizinkan.');
        }

        if ($kelas->bg_image && Storage::disk('public')->exists($kelas->bg_image)) {
            Storage::disk('public')->delete($kelas->bg_image);
        }

        $kelas->delete();

        return redirect()
            ->route('dosen.kelas')
            ->with('success', 'Kelas berhasil dihapus');
    }
}

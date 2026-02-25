<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Models\Beasiswa;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DataMahasiswaController extends Controller
{
    public function index()
    {
        $query = User::with(['mahasiswa.fakultas', 'mahasiswa.programStudi', 'mahasiswa.beasiswa', 'mahasiswa.angkatan'])
            ->whereHas('role', fn($q) => $q->where('nama_role', 'mahasiswa'))
            ->whereHas('mahasiswa');

        $search = request()->input('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhereHas('mahasiswa', function ($mq) use ($search) {
                        $mq->where('nim', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $fakultasId = request()->input('fakultas_id');
        if ($fakultasId) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('fakultas_id', $fakultasId));
        }

        $prodiId = request()->input('nama_prodi_id');
        if ($prodiId) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('nama_prodi_id', $prodiId));
        }

        $angkatanId = request()->input('angkatan_id');
        if ($angkatanId) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('angkatan_id', $angkatanId));
        }

        $statusAkademik = request()->input('status_akademik');
        if ($statusAkademik) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('status_akademik', $statusAkademik));
        }

        $mhss = $query->get();

        $fakultas = Fakultas::orderBy('fakultas')->get();
        $prodis = ProgramStudi::orderBy('nama_prodi')->get();
        $angkatans = Angkatan::orderBy('tahun', 'desc')->get();
        $beasiswas = Beasiswa::orderBy('nama')->get();

        return view('admin.data_mahasiswa.index', compact('mhss', 'fakultas', 'prodis', 'angkatans', 'beasiswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email', 'unique:mahasiswas,email'],
            'nim' => ['nullable', 'string', 'max:50', 'unique:users,nim', 'unique:mahasiswas,nim'],
            'fakultas_id' => ['nullable', 'exists:fakultas,id'],
            'nama_prodi_id' => ['nullable', 'exists:program_studis,id'],
            'jenjang' => ['nullable', Rule::in(['d3', 's1', 's2'])],
            'beasiswa_id' => ['nullable', 'exists:beasiswas,id'],
            'angkatan_id' => ['nullable', 'exists:angkatans,id'],
            'status_akademik' => ['nullable', Rule::in(['AKTIF', 'CUTI', 'DO', 'LULUS'])],
            'status_krs' => ['nullable', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $role = Role::where('nama_role', 'mahasiswa')->first();
        if (!$role) {
            return redirect()->back()->with('error', 'Role mahasiswa tidak ditemukan');
        }

        $nullIfEmpty = static fn($value) => $value === '' ? null : $value;
        $validated = array_map($nullIfEmpty, $validated);

        DB::transaction(function () use ($validated, $role) {
            $user = User::create([
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'] ?? null,
                'nim' => $validated['nim'] ?? null,
                'password' => bcrypt('123'),
                'role_id' => $role->id,
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $validated['nim'] ?? null,
                'fakultas_id' => $validated['fakultas_id'] ?? null,
                'nama_prodi_id' => $validated['nama_prodi_id'] ?? null,
                'jenjang' => $validated['jenjang'] ?? null,
                'beasiswa_id' => $validated['beasiswa_id'] ?? null,
                'angkatan_id' => $validated['angkatan_id'] ?? null,
                'email' => $validated['email'] ?? null,
                'status_akademik' => $validated['status_akademik'] ?? null,
                'status_krs' => $validated['status_krs'] ?? 'nonaktif',
            ]);
        });

        if ($request->ajax()) {
            return response()->json(['message' => 'Mahasiswa berhasil ditambahkan']);
        }

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        $user = $mahasiswa->user;

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
                Rule::unique('mahasiswas', 'email')->ignore($mahasiswa->id),
            ],
            'nim' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'nim')->ignore($user?->id),
                Rule::unique('mahasiswas', 'nim')->ignore($mahasiswa->id),
            ],
            'fakultas_id' => ['nullable', 'exists:fakultas,id'],
            'nama_prodi_id' => ['nullable', 'exists:program_studis,id'],
            'jenjang' => ['nullable', Rule::in(['d3', 's1', 's2'])],
            'beasiswa_id' => ['nullable', 'exists:beasiswas,id'],
            'angkatan_id' => ['nullable', 'exists:angkatans,id'],
            'status_akademik' => ['nullable', Rule::in(['AKTIF', 'CUTI', 'DO', 'LULUS'])],
            'status_krs' => ['nullable', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $nullIfEmpty = static fn($value) => $value === '' ? null : $value;
        $validated = array_map($nullIfEmpty, $validated);

        DB::transaction(function () use ($validated, $mahasiswa, $user) {
            if ($user) {
                $user->update([
                    'name' => $validated['name'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'nim' => $validated['nim'] ?? null,
                ]);
            }

            $mahasiswa->update([
                'nim' => $validated['nim'] ?? null,
                'fakultas_id' => $validated['fakultas_id'] ?? null,
                'nama_prodi_id' => $validated['nama_prodi_id'] ?? null,
                'jenjang' => $validated['jenjang'] ?? null,
                'beasiswa_id' => $validated['beasiswa_id'] ?? null,
                'angkatan_id' => $validated['angkatan_id'] ?? null,
                'email' => $validated['email'] ?? null,
                'status_akademik' => $validated['status_akademik'] ?? null,
                'status_krs' => $validated['status_krs'] ?? $mahasiswa->status_krs ?? 'nonaktif',
            ]);
        });

        if ($request->ajax()) {
            return response()->json(['message' => 'Mahasiswa berhasil diupdate']);
        }

        return redirect()->back()->with('success', 'Mahasiswa berhasil diupdate');
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        DB::transaction(function () use ($mahasiswa) {
            if ($mahasiswa->user) {
                $mahasiswa->user->delete();
                return;
            }

            $mahasiswa->delete();
        });

        if (request()->ajax()) {
            return response()->json(['message' => 'Mahasiswa berhasil dihapus']);
        }

        return redirect()->back()->with('success', 'Mahasiswa berhasil dihapus');
    }

    public function bulkUpdateStatusKrs(Request $request)
    {
        $validated = $request->validate([
            'scope' => ['required', 'string', 'max:50'],
            'status_krs' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $query = Mahasiswa::query();
        $scope = $validated['scope'];
        $targetLabel = 'semua mahasiswa';

        if ($scope === 'beasiswa' || $scope === 'beasiswa_semua') {
            $query->whereNotNull('beasiswa_id');
            $targetLabel = 'mahasiswa beasiswa';
        } elseif (str_starts_with($scope, 'beasiswa:')) {
            $beasiswaId = (int) substr($scope, strlen('beasiswa:'));
            $beasiswa = Beasiswa::find($beasiswaId);
            if (!$beasiswa) {
                return response()->json(['message' => 'Beasiswa tidak ditemukan.'], 422);
            }
            $query->where('beasiswa_id', $beasiswaId);
            $targetLabel = "mahasiswa {$beasiswa->nama}";
        } elseif ($scope !== 'semua') {
            return response()->json(['message' => 'Target pengaturan KRS tidak valid.'], 422);
        }

        $updatedCount = $query->update([
            'status_krs' => $validated['status_krs'],
        ]);

        $statusLabel = $validated['status_krs'] === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        $message = "Status KRS {$targetLabel} berhasil {$statusLabel}: {$updatedCount} data.";

        if ($request->ajax()) {
            return response()->json([
                'message' => $message,
                'updated_count' => $updatedCount,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200|mimes:csv,txt|mimetypes:text/plain,text/csv,application/vnd.ms-excel',
        ], [
            'file.mimes' => 'Import mahasiswa hanya mendukung format .csv.',
            'file.mimetypes' => 'Import mahasiswa hanya mendukung format .csv.',
        ]);

        $roleMahasiswa = Role::where('nama_role', 'mahasiswa')->first();
        if (!$roleMahasiswa) {
            return redirect()->back()->withErrors(['import' => 'Role mahasiswa tidak ditemukan.']);
        }

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext !== 'csv') {
            return redirect()->back()->withErrors(['import' => 'Import mahasiswa hanya mendukung format .csv.']);
        }

        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return redirect()->back()->withErrors(['import' => 'Gagal membaca file CSV.']);
        }

        $rows = [];
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (count($row) === 1) {
                $semicolonRow = str_getcsv($row[0], ';');
                if (count($semicolonRow) > 1) {
                    $row = $semicolonRow;
                }
            }
            $rows[] = array_map(fn($v) => trim((string) $v), $row);
        }
        fclose($handle);

        if (count($rows) === 0) {
            return redirect()->back()->withErrors(['import' => 'File CSV kosong.']);
        }

        $firstRow = $rows[0] ?? [];
        if (count(array_filter($firstRow, fn($v) => $v !== '')) === 0) {
            return redirect()->back()->withErrors(['import' => 'File CSV kosong atau baris pertama tidak valid.']);
        }

        $normalize = function ($value) {
            $value = preg_replace('/^\xEF\xBB\xBF/', '', trim((string) $value));
            $value = strtolower($value);
            return preg_replace('/\s+/', '_', $value);
        };

        $headerMap = [];
        foreach ($firstRow as $idx => $label) {
            $key = $normalize($label);
            if ($key !== '') {
                $headerMap[$key] = $idx;
            }
        }

        $headerAliases = [
            'nama' => ['nama', 'name'],
            'nim' => ['nim'],
            'prodi' => ['prodi', 'program_studi', 'nama_prodi'],
            'jenjang' => ['jenjang', 'degree', 'strata'],
            'fakultas' => ['fakultas'],
            'angkatan' => ['angkatan', 'tahun', 'tahun_angkatan'],
        ];

        $resolveHeaderIndex = function (array $aliases) use ($headerMap) {
            foreach ($aliases as $alias) {
                if (array_key_exists($alias, $headerMap)) {
                    return $headerMap[$alias];
                }
            }
            return null;
        };

        $mappedIndexes = [
            'nama' => $resolveHeaderIndex($headerAliases['nama']),
            'nim' => $resolveHeaderIndex($headerAliases['nim']),
            'prodi' => $resolveHeaderIndex($headerAliases['prodi']),
            'jenjang' => $resolveHeaderIndex($headerAliases['jenjang']),
            'fakultas' => $resolveHeaderIndex($headerAliases['fakultas']),
            'angkatan' => $resolveHeaderIndex($headerAliases['angkatan']),
        ];

        $hasHeader = !in_array(null, $mappedIndexes, true);
        $startRow = $hasHeader ? 1 : 0;

        if (!$hasHeader) {
            $mappedIndexes = [
                'nama' => 1,
                'nim' => 2,
                'prodi' => 3,
                'jenjang' => 4,
                'fakultas' => 5,
                'angkatan' => 6,
            ];
        }

        $errors = [];
        $toCreate = [];
        $seenNims = [];
        $rowNumber = $hasHeader ? 1 : 0;

        for ($i = $startRow; $i < count($rows); $i++) {
            $rowNumber++;
            $row = $rows[$i];

            $getIdx = function (int $idx) use ($row) {
                return trim((string) ($row[$idx] ?? ''));
            };

            $name = $getIdx($mappedIndexes['nama']);
            $nim = $getIdx($mappedIndexes['nim']);
            $prodiName = $getIdx($mappedIndexes['prodi']);
            $jenjang = strtolower($getIdx($mappedIndexes['jenjang']));
            $fakultasName = $getIdx($mappedIndexes['fakultas']);
            $angkatanYear = $getIdx($mappedIndexes['angkatan']);

            if ($name === '' && $nim === '' && $prodiName === '' && $jenjang === '' && $fakultasName === '' && $angkatanYear === '') {
                continue;
            }

            if ($name === '' || $nim === '' || $prodiName === '' || $jenjang === '' || $fakultasName === '' || $angkatanYear === '') {
                $errors[] = "Baris {$rowNumber}: format wajib No, Nama, NIM, Prodi, Jenjang, Fakultas, Angkatan.";
                continue;
            }

            if (!in_array($jenjang, ['d3', 's1', 's2'], true)) {
                $errors[] = "Baris {$rowNumber}: jenjang harus salah satu dari D3, S1, atau S2.";
                continue;
            }

            if (!preg_match('/^\d{4}$/', $angkatanYear)) {
                $errors[] = "Baris {$rowNumber}: angkatan harus format tahun (contoh: 2021).";
                continue;
            }

            if (isset($seenNims[$nim])) {
                $errors[] = "Baris {$rowNumber}: NIM duplikat di file CSV.";
                continue;
            }

            if (User::where('nim', $nim)->exists() || Mahasiswa::where('nim', $nim)->exists()) {
                $errors[] = "Baris {$rowNumber}: NIM sudah terdaftar.";
                continue;
            }

            $fakultas = Fakultas::whereRaw('LOWER(fakultas) = ?', [strtolower($fakultasName)])->first();
            if (!$fakultas) {
                $errors[] = "Baris {$rowNumber}: fakultas '{$fakultasName}' tidak ditemukan.";
                continue;
            }

            $prodi = ProgramStudi::whereRaw('LOWER(nama_prodi) = ?', [strtolower($prodiName)])
                ->where('fakultas_id', $fakultas->id)
                ->first();
            if (!$prodi) {
                $errors[] = "Baris {$rowNumber}: prodi '{$prodiName}' pada fakultas '{$fakultasName}' tidak ditemukan.";
                continue;
            }

            $angkatan = Angkatan::where('tahun', (int) $angkatanYear)->first();
            if (!$angkatan) {
                $errors[] = "Baris {$rowNumber}: angkatan '{$angkatanYear}' tidak ditemukan.";
                continue;
            }

            $seenNims[$nim] = true;

            $toCreate[] = [
                'name' => $name,
                'nim' => $nim,
                'fakultas_id' => (int) $fakultas->id,
                'nama_prodi_id' => (int) $prodi->id,
                'jenjang' => $jenjang,
                'angkatan_id' => (int) $angkatan->id,
                'role_id' => $roleMahasiswa->id,
            ];
        }

        if ($errors) {
            $preview = array_slice($errors, 0, 5);
            $suffix = count($errors) > 5 ? ' (dan lainnya)' : '';
            return redirect()->back()->withErrors([
                'import' => implode(' ', $preview) . $suffix,
            ]);
        }

        if (count($toCreate) === 0) {
            return redirect()->back()->withErrors(['import' => 'Tidak ada data mahasiswa yang bisa diimpor.']);
        }

        DB::transaction(function () use ($toCreate) {
            foreach ($toCreate as $item) {
                $user = User::create([
                    'name' => $item['name'],
                    'email' => null,
                    'nim' => $item['nim'],
                    'password' => bcrypt((string) $item['nim']),
                    'role_id' => $item['role_id'],
                ]);

                Mahasiswa::create([
                    'user_id' => $user->id,
                    'nim' => $item['nim'],
                    'fakultas_id' => $item['fakultas_id'],
                    'nama_prodi_id' => $item['nama_prodi_id'],
                    'jenjang' => $item['jenjang'],
                    'beasiswa_id' => null,
                    'angkatan_id' => $item['angkatan_id'],
                    'email' => null,
                    'status_akademik' => 'AKTIF',
                    'status_krs' => 'nonaktif',
                ]);
            }
        });

        return redirect()->back()->with('import_success', 'Import mahasiswa berhasil: ' . count($toCreate) . ' baris.');
    }
}

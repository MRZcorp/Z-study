<?php

namespace App\Http\Controllers;

use App\Models\Diskusi;
use App\Models\DiskusiRead;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DiskusiController extends Controller
{
    public function dosen()
    {
        return view('dosen.diskusi');
    }

    public function index()
    {
        return view('mahasiswa.diskusi');
    }

    public function mahasiswa()
    {
        return view('mahasiswa.diskusi');
    }

    public function kelasMessages(Kelas $kelas)
    {
        $this->authorizeKelasAccess($kelas);
        $kelas->loadMissing([
            'mataKuliah:id,mata_kuliah',
            'dosens.user:id,name',
            'dosens.fakultas:id,fakultas',
            'dosens.programStudi:id,nama_prodi',
            'mahasiswas.user:id,name',
            'mahasiswas.fakultas:id,fakultas',
            'mahasiswas.programStudi:id,nama_prodi',
        ]);
        $contacts = $this->buildContextContacts($kelas);

        $messages = Diskusi::with('user:id,name')
            ->where('kelas_id', $kelas->id)
            ->whereNull('ujian_id')
            ->whereNull('tugas_id')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages' => $this->formatMessages($messages, $contacts['user_map'] ?? []),
            'user_map' => $contacts['user_map'] ?? [],
            'dosen_contact' => $contacts['dosen_contact'] ?? null,
        ]);
    }

    public function storeKelasMessage(Request $request, Kelas $kelas)
    {
        $this->authorizeKelasAccess($kelas);
        $validated = $this->validateStorePayload($request);
        $attachment = $this->storeAttachment($request);

        $message = Diskusi::create([
            'kelas_id' => $kelas->id,
            'user_id' => (int) session('user_id'),
            'pesan' => $validated['pesan'] ?? null,
            'lampiran_path' => $attachment['path'] ?? null,
            'lampiran_name' => $attachment['name'] ?? null,
            'lampiran_mime' => $attachment['mime'] ?? null,
            'lampiran_size' => $attachment['size'] ?? null,
        ]);

        return response()->json([
            'message' => 'Pesan berhasil dikirim.',
            'data' => $this->formatOneMessage($message, session('name') ?? '-'),
        ]);
    }

    public function updateKelasMessage(Request $request, Kelas $kelas, Diskusi $diskusi)
    {
        $this->authorizeKelasAccess($kelas);
        $this->authorizeMessageOwnerForKelas($kelas, $diskusi);
        $validated = $request->validate(['pesan' => 'required|string|max:2000']);

        $diskusi->update(['pesan' => $validated['pesan']]);

        return response()->json([
            'message' => 'Pesan berhasil diperbarui.',
            'data' => $this->formatOneMessage($diskusi, $diskusi->user?->name ?? (session('name') ?? '-'), true),
        ]);
    }

    public function destroyKelasMessage(Kelas $kelas, Diskusi $diskusi)
    {
        $this->authorizeKelasAccess($kelas);
        $this->authorizeMessageOwnerForKelas($kelas, $diskusi);
        $this->deleteAttachment($diskusi);
        $diskusi->delete();

        return response()->json(['message' => 'Pesan berhasil dihapus.']);
    }

    public function ujianMessages(Ujian $ujian)
    {
        $this->authorizeUjianAccess($ujian);
        $kelas = Kelas::with([
            'mataKuliah:id,mata_kuliah',
            'dosens.user:id,name',
            'dosens.fakultas:id,fakultas',
            'dosens.programStudi:id,nama_prodi',
            'mahasiswas.user:id,name',
            'mahasiswas.fakultas:id,fakultas',
            'mahasiswas.programStudi:id,nama_prodi',
        ])->find($ujian->nama_kelas_id);
        $contacts = $kelas ? $this->buildContextContacts($kelas) : ['user_map' => [], 'dosen_contact' => null];

        $messages = Diskusi::with('user:id,name')
            ->where('ujian_id', $ujian->id)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages' => $this->formatMessages($messages, $contacts['user_map'] ?? []),
            'user_map' => $contacts['user_map'] ?? [],
            'dosen_contact' => $contacts['dosen_contact'] ?? null,
        ]);
    }

    public function storeUjianMessage(Request $request, Ujian $ujian)
    {
        $this->authorizeUjianAccess($ujian);
        $validated = $this->validateStorePayload($request);
        $attachment = $this->storeAttachment($request);

        $message = Diskusi::create([
            'kelas_id' => $ujian->nama_kelas_id,
            'ujian_id' => $ujian->id,
            'user_id' => (int) session('user_id'),
            'pesan' => $validated['pesan'] ?? null,
            'lampiran_path' => $attachment['path'] ?? null,
            'lampiran_name' => $attachment['name'] ?? null,
            'lampiran_mime' => $attachment['mime'] ?? null,
            'lampiran_size' => $attachment['size'] ?? null,
        ]);

        return response()->json([
            'message' => 'Pesan berhasil dikirim.',
            'data' => $this->formatOneMessage($message, session('name') ?? '-'),
        ]);
    }

    public function updateUjianMessage(Request $request, Ujian $ujian, Diskusi $diskusi)
    {
        $this->authorizeUjianAccess($ujian);
        $this->authorizeMessageOwnerForUjian($ujian, $diskusi);
        $validated = $request->validate(['pesan' => 'required|string|max:2000']);

        $diskusi->update(['pesan' => $validated['pesan']]);

        return response()->json([
            'message' => 'Pesan berhasil diperbarui.',
            'data' => $this->formatOneMessage($diskusi, $diskusi->user?->name ?? (session('name') ?? '-'), true),
        ]);
    }

    public function destroyUjianMessage(Ujian $ujian, Diskusi $diskusi)
    {
        $this->authorizeUjianAccess($ujian);
        $this->authorizeMessageOwnerForUjian($ujian, $diskusi);
        $this->deleteAttachment($diskusi);
        $diskusi->delete();

        return response()->json(['message' => 'Pesan berhasil dihapus.']);
    }

    public function tugasMessages(Tugas $tugas)
    {
        $this->authorizeTugasAccess($tugas);
        $kelas = Kelas::with([
            'mataKuliah:id,mata_kuliah',
            'dosens.user:id,name',
            'dosens.fakultas:id,fakultas',
            'dosens.programStudi:id,nama_prodi',
            'mahasiswas.user:id,name',
            'mahasiswas.fakultas:id,fakultas',
            'mahasiswas.programStudi:id,nama_prodi',
        ])->find($tugas->nama_kelas_id);
        $contacts = $kelas ? $this->buildContextContacts($kelas) : ['user_map' => [], 'dosen_contact' => null];

        $messages = Diskusi::with('user:id,name')
            ->where('tugas_id', $tugas->id)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages' => $this->formatMessages($messages, $contacts['user_map'] ?? []),
            'user_map' => $contacts['user_map'] ?? [],
            'dosen_contact' => $contacts['dosen_contact'] ?? null,
        ]);
    }

    public function storeTugasMessage(Request $request, Tugas $tugas)
    {
        $this->authorizeTugasAccess($tugas);
        $validated = $this->validateStorePayload($request);
        $attachment = $this->storeAttachment($request);

        $message = Diskusi::create([
            'kelas_id' => $tugas->nama_kelas_id,
            'tugas_id' => $tugas->id,
            'user_id' => (int) session('user_id'),
            'pesan' => $validated['pesan'] ?? null,
            'lampiran_path' => $attachment['path'] ?? null,
            'lampiran_name' => $attachment['name'] ?? null,
            'lampiran_mime' => $attachment['mime'] ?? null,
            'lampiran_size' => $attachment['size'] ?? null,
        ]);

        return response()->json([
            'message' => 'Pesan berhasil dikirim.',
            'data' => $this->formatOneMessage($message, session('name') ?? '-'),
        ]);
    }

    public function updateTugasMessage(Request $request, Tugas $tugas, Diskusi $diskusi)
    {
        $this->authorizeTugasAccess($tugas);
        $this->authorizeMessageOwnerForTugas($tugas, $diskusi);
        $validated = $request->validate(['pesan' => 'required|string|max:2000']);

        $diskusi->update(['pesan' => $validated['pesan']]);

        return response()->json([
            'message' => 'Pesan berhasil diperbarui.',
            'data' => $this->formatOneMessage($diskusi, $diskusi->user?->name ?? (session('name') ?? '-'), true),
        ]);
    }

    public function destroyTugasMessage(Tugas $tugas, Diskusi $diskusi)
    {
        $this->authorizeTugasAccess($tugas);
        $this->authorizeMessageOwnerForTugas($tugas, $diskusi);
        $this->deleteAttachment($diskusi);
        $diskusi->delete();

        return response()->json(['message' => 'Pesan berhasil dihapus.']);
    }

    public function unreadStatus(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:kelas,ujian,tugas',
            'ids' => 'nullable|string',
        ]);

        $type = $validated['type'];
        $ids = collect(explode(',', (string) ($validated['ids'] ?? '')))
            ->map(fn ($v) => (int) trim($v))
            ->filter(fn ($v) => $v > 0)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json(['data' => []]);
        }

        $this->authorizeContextIdsAccess($type, $ids->all());

        $column = $this->contextColumn($type);
        $userId = (int) session('user_id');

        $latestRows = Diskusi::selectRaw("{$column} as context_id, MAX(created_at) as latest_at")
            ->whereIn($column, $ids)
            ->where('user_id', '!=', $userId)
            ->groupBy($column)
            ->get()
            ->keyBy('context_id');

        $readRows = DiskusiRead::where('user_id', $userId)
            ->where('context_type', $type)
            ->whereIn('context_id', $ids)
            ->get()
            ->keyBy('context_id');

        $data = [];
        foreach ($ids as $id) {
            $latestAt = optional($latestRows->get($id))->latest_at;
            $lastReadAt = optional($readRows->get($id))->last_read_at;
            $data[(string) $id] = $latestAt
                ? (!$lastReadAt || Carbon::parse($latestAt)->gt(Carbon::parse($lastReadAt)))
                : false;
        }

        return response()->json(['data' => $data]);
    }

    public function markRead(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:kelas,ujian,tugas',
            'id' => 'required|integer|min:1',
        ]);

        $type = $validated['type'];
        $id = (int) $validated['id'];

        $this->authorizeContextIdsAccess($type, [$id]);

        DiskusiRead::updateOrCreate(
            [
                'user_id' => (int) session('user_id'),
                'context_type' => $type,
                'context_id' => $id,
            ],
            [
                'last_read_at' => now(),
            ]
        );

        return response()->json(['message' => 'Diskusi ditandai sudah dibaca.']);
    }

    private function formatMessages($rows, array $userMap = []): array
    {
        return $rows->map(function ($item) use ($userMap) {
            $uid = (string) ($item->user_id ?? '');
            $contact = $userMap[$uid] ?? [];
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'user_name' => $item->user?->name ?? '-',
                'user_foto' => $contact['foto'] ?? null,
                'pesan' => $item->pesan,
                'lampiran_url' => $this->attachmentUrl($item->lampiran_path),
                'lampiran_name' => $item->lampiran_name,
                'lampiran_mime' => $item->lampiran_mime,
                'lampiran_size' => $item->lampiran_size,
                'tanggal' => optional($item->created_at)->format('d M Y'),
                'jam' => optional($item->created_at)->format('H:i'),
                'waktu' => optional($item->created_at)->format('d M Y H:i'),
            ];
        })->values()->all();
    }

    private function formatOneMessage(Diskusi $item, string $fallbackName, bool $useUpdatedAt = false): array
    {
        $time = $useUpdatedAt ? $item->updated_at : $item->created_at;

        return [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'user_name' => $item->user?->name ?? $fallbackName,
            'pesan' => $item->pesan,
            'lampiran_url' => $this->attachmentUrl($item->lampiran_path),
            'lampiran_name' => $item->lampiran_name,
            'lampiran_mime' => $item->lampiran_mime,
            'lampiran_size' => $item->lampiran_size,
            'tanggal' => optional($time)->format('d M Y'),
            'jam' => optional($time)->format('H:i'),
            'waktu' => optional($time)->format('d M Y H:i'),
        ];
    }

    private function authorizeKelasAccess(Kelas $kelas): void
    {
        $userId = (int) session('user_id');
        $role = strtolower((string) session('nama_role'));

        if ($role === 'dosen') {
            $dosenId = Dosen::where('user_id', $userId)->value('id');
            if (!$dosenId || (int) $kelas->dosen_id !== (int) $dosenId) {
                abort(403, 'Akses diskusi kelas tidak diizinkan.');
            }
            return;
        }

        if ($role === 'mahasiswa') {
            $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');
            $isParticipant = $mahasiswaId
                ? $kelas->mahasiswas()->where('mahasiswas.id', $mahasiswaId)->exists()
                : false;
            if (!$isParticipant) {
                abort(403, 'Akses diskusi kelas tidak diizinkan.');
            }
            return;
        }

        abort(403, 'Akses diskusi kelas tidak diizinkan.');
    }

    private function authorizeUjianAccess(Ujian $ujian): void
    {
        $kelas = Kelas::find($ujian->nama_kelas_id);
        if (!$kelas) {
            abort(404, 'Kelas ujian tidak ditemukan.');
        }

        $this->authorizeKelasAccess($kelas);
    }

    private function authorizeTugasAccess(Tugas $tugas): void
    {
        $kelas = Kelas::find($tugas->nama_kelas_id);
        if (!$kelas) {
            abort(404, 'Kelas tugas tidak ditemukan.');
        }

        $this->authorizeKelasAccess($kelas);
    }

    private function authorizeMessageOwnerForKelas(Kelas $kelas, Diskusi $diskusi): void
    {
        if ((int) $diskusi->kelas_id !== (int) $kelas->id || $diskusi->ujian_id || $diskusi->tugas_id) {
            abort(404, 'Pesan tidak ditemukan pada kelas ini.');
        }

        if ((int) $diskusi->user_id !== (int) session('user_id')) {
            abort(403, 'Anda hanya dapat mengubah pesan milik sendiri.');
        }
    }

    private function authorizeMessageOwnerForUjian(Ujian $ujian, Diskusi $diskusi): void
    {
        if ((int) $diskusi->ujian_id !== (int) $ujian->id) {
            abort(404, 'Pesan tidak ditemukan pada ujian ini.');
        }

        if ((int) $diskusi->user_id !== (int) session('user_id')) {
            abort(403, 'Anda hanya dapat mengubah pesan milik sendiri.');
        }
    }

    private function authorizeMessageOwnerForTugas(Tugas $tugas, Diskusi $diskusi): void
    {
        if ((int) $diskusi->tugas_id !== (int) $tugas->id) {
            abort(404, 'Pesan tidak ditemukan pada tugas ini.');
        }

        if ((int) $diskusi->user_id !== (int) session('user_id')) {
            abort(403, 'Anda hanya dapat mengubah pesan milik sendiri.');
        }
    }

    private function contextColumn(string $type): string
    {
        return match ($type) {
            'kelas' => 'kelas_id',
            'ujian' => 'ujian_id',
            'tugas' => 'tugas_id',
            default => 'kelas_id',
        };
    }

    private function authorizeContextIdsAccess(string $type, array $ids): void
    {
        foreach ($ids as $id) {
            if ($type === 'kelas') {
                $kelas = Kelas::find($id);
                if (!$kelas) {
                    abort(404, 'Kelas tidak ditemukan.');
                }
                $this->authorizeKelasAccess($kelas);
                continue;
            }

            if ($type === 'ujian') {
                $ujian = Ujian::find($id);
                if (!$ujian) {
                    abort(404, 'Ujian tidak ditemukan.');
                }
                $this->authorizeUjianAccess($ujian);
                continue;
            }

            $tugas = Tugas::find($id);
            if (!$tugas) {
                abort(404, 'Tugas tidak ditemukan.');
            }
            $this->authorizeTugasAccess($tugas);
        }
    }

    private function buildContextContacts(Kelas $kelas): array
    {
        $defaultAvatar = asset('img/default_profil.jpg');
        $userMap = [];

        $dosen = $kelas->dosens;
        $dosenName = $dosen?->user?->name ?? '-';
        $dosenMatkul = $kelas->mataKuliah?->mata_kuliah ?? '-';
        $dosenContact = [
            'user_id' => $dosen?->user_id,
            'name' => $dosenName,
            'foto' => $this->photoUrl($dosen?->poto_profil, $defaultAvatar),
            'phone' => $dosen?->no_hp ?? '-',
            'email' => $dosen?->email ?? ($dosen?->user?->email ?? '-'),
            'nidn' => $dosen?->nidn ?? '-',
            'nim' => null,
            'role' => 'dosen',
            'gelar' => $dosen?->gelar ?? '',
            'homebase' => $dosen?->fakultas?->fakultas ?? '-',
            'fakultas' => $dosen?->fakultas?->fakultas ?? '-',
            'prodi' => $dosen?->programStudi?->nama_prodi ?? '-',
            'mata_kuliah' => $dosenMatkul,
        ];
        if ($dosen?->user_id) {
            $userMap[(string) $dosen->user_id] = $dosenContact;
        }

        foreach ($kelas->mahasiswas as $mhs) {
            $uid = (string) ($mhs->user_id ?? '');
            if ($uid === '') {
                continue;
            }
            $userMap[$uid] = [
                'name' => $mhs->user?->name ?? '-',
                'foto' => $this->photoUrl($mhs->poto_profil, $defaultAvatar),
                'phone' => $mhs->no_hp ?? '-',
                'email' => $mhs->email ?? ($mhs->user?->email ?? '-'),
                'nidn' => null,
                'nim' => $mhs->nim ?? '-',
                'role' => 'mahasiswa',
                'gelar' => '',
                'homebase' => '-',
                'fakultas' => $mhs->fakultas?->fakultas ?? '-',
                'prodi' => $mhs->programStudi?->nama_prodi ?? '-',
                'mata_kuliah' => '-',
            ];
        }

        return [
            'user_map' => $userMap,
            'dosen_contact' => $dosenContact,
        ];
    }

    private function photoUrl(?string $path, string $fallback): string
    {
        $raw = trim((string) $path);
        if ($raw === '') {
            return $fallback;
        }
        if (Str::startsWith($raw, ['http://', 'https://', '/'])) {
            return $raw;
        }
        return asset('storage/' . ltrim($raw, '/'));
    }

    private function validateStorePayload(Request $request): array
    {
        $validated = $request->validate([
            'pesan' => 'nullable|string|max:2000',
            'lampiran' => 'nullable|file|max:20480',
        ]);

        $hasText = trim((string) ($validated['pesan'] ?? '')) !== '';
        $hasFile = $request->hasFile('lampiran');
        if (!$hasText && !$hasFile) {
            throw ValidationException::withMessages([
                'pesan' => ['Pesan atau lampiran wajib diisi.'],
            ]);
        }

        return $validated;
    }

    private function storeAttachment(Request $request): array
    {
        if (!$request->hasFile('lampiran')) {
            return [];
        }
        $file = $request->file('lampiran');
        $path = $file->store('diskusi', 'public');
        return [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ];
    }

    private function deleteAttachment(Diskusi $diskusi): void
    {
        $path = trim((string) ($diskusi->lampiran_path ?? ''));
        if ($path !== '') {
            Storage::disk('public')->delete($path);
        }
    }

    private function attachmentUrl(?string $path): ?string
    {
        $raw = trim((string) $path);
        if ($raw === '') {
            return null;
        }
        return asset('storage/' . ltrim($raw, '/'));
    }
}

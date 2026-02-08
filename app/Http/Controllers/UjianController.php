<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\JawabanMahasiswa;
use App\Models\HasilUjian;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class UjianController extends Controller
{
    //

    public function dosen()
     {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        $kelas_dosen = Kelas::with('mataKuliah')
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->orderBy('nama_kelas')
            ->get();

        $kelasIds = $kelas_dosen->pluck('id');
        $ujian_kelas = Ujian::with([
                'mataKuliah',
                'kelas' => fn($q) => $q->withCount('mahasiswas'),
                'soals',
                'hasilUjian.mahasiswa.user',
            ])
            ->when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
            ->latest()
            ->get();

        $ujianCountByKelas = Ujian::select('nama_kelas_id', \DB::raw('count(*) as total'))
            ->when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
            ->groupBy('nama_kelas_id')
            ->pluck('total', 'nama_kelas_id');

        return view('dosen.ujian.ujian', compact('kelas_dosen', 'ujian_kelas', 'ujianCountByKelas'));
    }

    public function dosenSelesai()
    {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        $kelas_dosen = Kelas::with('mataKuliah')
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->orderBy('nama_kelas')
            ->get();

        $kelasIds = $kelas_dosen->pluck('id');
        $ujian_kelas = Ujian::with(['mataKuliah', 'kelas' => fn($q) => $q->withCount('mahasiswas'), 'soals'])
            ->when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
            ->latest()
            ->get();

        $ujianCountByKelas = Ujian::select('nama_kelas_id', \DB::raw('count(*) as total'))
            ->when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
            ->groupBy('nama_kelas_id')
            ->pluck('total', 'nama_kelas_id');

        return view('dosen.ujian.ujian_selesai', compact('kelas_dosen', 'ujian_kelas', 'ujianCountByKelas'));
    }

    public function koreksi(?Ujian $ujian = null)
    {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        $kelasIds = Kelas::query()
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->pluck('id');

        $ujianTarget = $ujian;
        if ($ujianTarget) {
            $ujianTarget->load(['kelas' => fn($q) => $q->withCount('mahasiswas')]);
            if (!$kelasIds->contains($ujianTarget->nama_kelas_id)) {
                abort(403, 'Akses ujian tidak diizinkan.');
            }
        } else {
            $ujianTarget = Ujian::with(['kelas' => fn($q) => $q->withCount('mahasiswas')])
                ->when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
                ->whereNotNull('deadline')
                ->where('deadline', '<', now())
                ->latest()
                ->first();
        }

        if (!$ujianTarget) {
            return view('dosen.ujian.koreksi_ujian', [
                'pengumpulan' => collect(),
                'tidakMengumpulkan' => collect(),
                'kelasNama' => '-',
                'kumpulCount' => 0,
                'kuotaKelas' => 0,
            ]);
        }

        $pengumpulan = HasilUjian::with(['mahasiswa.user', 'mahasiswa.programStudi'])
            ->where('ujian_id', $ujianTarget->id)
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at')
            ->get();

        $kuotaKelas = $ujianTarget->kelas->mahasiswas_count ?? ($ujianTarget->kelas->mahasiswas()->count() ?? 0);
        $kumpulCount = $pengumpulan->count();
        $ujianMulai = $ujianTarget->mulai_ujian ?? null;
        $ujianDeadline = $ujianTarget->deadline ?? null;

        $soalMap = Soal::where('ujian_id', $ujianTarget->id)
            ->orderBy('created_at')
            ->get()
            ->keyBy('id');

        $jawabanMap = JawabanMahasiswa::where('ujian_id', $ujianTarget->id)
            ->whereIn('mahasiswa_id', $pengumpulan->pluck('mahasiswa_id'))
            ->get()
            ->groupBy('mahasiswa_id')
            ->map(function ($rows) use ($soalMap) {
                return $rows->map(function ($row) use ($soalMap) {
                    $soal = $soalMap[$row->soal_id] ?? null;
                    return [
                        'soal_id' => $row->soal_id,
                        'soal' => $soal?->pertanyaan ?? '-',
                        'tipe' => $row->tipe,
                        'bobot' => $soal?->bobot ?? 0,
                        'jawaban_pg' => $row->jawaban_pg,
                        'jawaban_text' => $row->jawaban_text,
                        'options' => $soal?->options ?? [],
                        'pg_correct' => $soal?->pg_correct ?? null,
                        'essay_score' => $row->essay_score,
                    ];
                })->values();
            })
            ->toArray();

        $tidakMengumpulkan = $ujianTarget->kelas
            ? $ujianTarget->kelas->mahasiswas()
                ->with(['user', 'programStudi'])
                ->whereNotIn('mahasiswa_id', $pengumpulan->pluck('mahasiswa_id'))
                ->get()
            : collect();

        return view('dosen.ujian.koreksi_ujian', [
            'pengumpulan' => $pengumpulan,
            'tidakMengumpulkan' => $tidakMengumpulkan,
            'kelasNama' => $ujianTarget->kelas->nama_kelas ?? '-',
            'kumpulCount' => $kumpulCount,
            'kuotaKelas' => $kuotaKelas,
            'jawabanMap' => $jawabanMap,
            'ujianMulai' => $ujianMulai,
            'ujianDeadline' => $ujianDeadline,
        ]);
    }

    public function soalDosen(Ujian $ujian)
    {
        $ujian->load(['mataKuliah', 'kelas']);
        $mataKuliahNama = $ujian->mataKuliah->mata_kuliah ?? '-';
        $ujianKe = $ujian->ujian_ke ?? '-';
        $nilaiUjianKe = $ujian->nilai_ujian_ke ?? '-';
        $soalList = Soal::where('ujian_id', $ujian->id)
            ->orderBy('created_at')
            ->get();

        return view('dosen.ujian.soal', compact('ujian', 'mataKuliahNama', 'ujianKe', 'nilaiUjianKe', 'soalList'));
    }

    public function storeSoal(Request $request, Ujian $ujian)
    {
        $request->validate([
            'tipe' => 'required|in:essay,pg',
            'pertanyaan' => 'required|string',
            'media' => 'nullable|file|max:51200',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'pg_correct' => 'required_if:tipe,pg|string',
            'bobot' => 'nullable|numeric|min:0',
        ]);

        if ($request->tipe === 'pg') {
            $options = array_values(array_filter($request->options ?? []));
            if (count($options) === 0) {
                return back()->withErrors(['options' => 'Jawaban pilihan ganda wajib diisi.'])->withInput();
            }
            $correct = $request->pg_correct;
            $maxIndex = count($options) - 1;
            $maxLetter = chr(65 + $maxIndex);
            if (!preg_match('/^[A-Z]$/', $correct) || $correct < 'A' || $correct > $maxLetter) {
                return back()->withErrors(['pg_correct' => 'Jawaban benar tidak valid.'])->withInput();
            }
        }

        $data = [
            'ujian_id' => $ujian->id,
            'tipe' => $request->tipe,
            'pertanyaan' => $request->pertanyaan,
            'bobot' => $request->bobot,
            'options' => $request->tipe === 'pg' ? array_values(array_filter($request->options ?? [])) : null,
            'pg_correct' => $request->tipe === 'pg' ? $request->pg_correct : null,
        ];

        $soal = Soal::create($data);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('soal', 'public');
            $soal->media_path = $path;
            $soal->save();
        }

        return redirect()->back()->with('success', 'Soal berhasil dibuat');
    }

    public function importSoal(Request $request, Ujian $ujian)
    {
        $request->validate([
            'file' => 'required|file|max:51200|mimes:csv,txt|mimetypes:text/plain,text/csv,application/vnd.ms-excel',
        ], [
            'file.mimes' => 'Import soal hanya mendukung format .csv.',
            'file.mimetypes' => 'Import soal hanya mendukung format .csv.',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext !== 'csv') {
            return redirect()->back()->withErrors([
                'import' => 'Import soal hanya mendukung format .csv.',
            ]);
        }

        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return redirect()->back()->withErrors(['import' => 'Gagal membaca file.']);
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return redirect()->back()->withErrors(['import' => 'File CSV kosong atau header tidak ditemukan.']);
        }
        $delimiter = substr_count($firstLine, ';') >= substr_count($firstLine, ',') ? ';' : ',';
        $header = str_getcsv($firstLine, $delimiter);
        if (!$header) {
            fclose($handle);
            return redirect()->back()->withErrors(['import' => 'File CSV kosong atau header tidak ditemukan.']);
        }

        $normalizeHeader = function ($value) {
            $value = trim((string) $value);
            $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
            $value = strtolower($value);
            $value = preg_replace('/\s+/', '_', $value);
            return $value;
        };

        $headerMap = [];
        foreach ($header as $idx => $label) {
            $key = $normalizeHeader($label);
            if ($key !== '') {
                $headerMap[$key] = $idx;
            }
        }

        $required = [
            'tipe',
            'pertanyaan',
            'bobot',
            'opsi_a',
            'opsi_b',
            'opsi_c',
            'opsi_d',
            'opsi_e',
            'jawaban_benar',
        ];

        $missing = array_values(array_filter($required, fn($key) => !array_key_exists($key, $headerMap)));
        if ($missing) {
            fclose($handle);
            return redirect()->back()->withErrors([
                'import' => 'Header tidak lengkap. Wajib: ' . implode(', ', $missing),
            ]);
        }

        $rowsToInsert = [];
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNumber++;
            $get = function ($key) use ($row, $headerMap) {
                $idx = $headerMap[$key] ?? null;
                return $idx === null ? '' : trim((string) ($row[$idx] ?? ''));
            };

            $tipe = strtolower($get('tipe'));
            $pertanyaan = $get('pertanyaan');
            $bobotRaw = $get('bobot');
            $bobot = $bobotRaw === '' ? null : (float) str_replace(',', '.', $bobotRaw);

            if ($tipe === '' && $pertanyaan === '') {
                continue;
            }

            if (!in_array($tipe, ['essay', 'pg'], true)) {
                $errors[] = "Baris {$rowNumber}: tipe harus 'essay' atau 'pg'.";
                continue;
            }

            if ($pertanyaan === '') {
                $errors[] = "Baris {$rowNumber}: pertanyaan wajib diisi.";
                continue;
            }

            $options = null;
            $pgCorrect = null;

            if ($tipe === 'pg') {
                $letters = ['a', 'b', 'c', 'd', 'e'];
                $optionsByLetter = [];
                $gapFound = false;
                foreach ($letters as $letter) {
                    $val = $get("opsi_{$letter}");
                    if ($val === '') {
                        $gapFound = true;
                        continue;
                    }
                    if ($gapFound) {
                        $errors[] = "Baris {$rowNumber}: opsi harus berurutan tanpa kosong di tengah (A, B, C...).";
                        continue 2;
                    }
                    $optionsByLetter[strtoupper($letter)] = $val;
                }

                if (count($optionsByLetter) === 0) {
                    $errors[] = "Baris {$rowNumber}: opsi PG wajib diisi.";
                    continue;
                }

                $jawaban = strtoupper($get('jawaban_benar'));
                if ($jawaban === '') {
                    $errors[] = "Baris {$rowNumber}: jawaban_benar wajib diisi.";
                    continue;
                }

                if (array_key_exists($jawaban, $optionsByLetter)) {
                    $pgCorrect = $jawaban;
                } else {
                    $matched = null;
                    foreach ($optionsByLetter as $letter => $text) {
                        if (strcasecmp($text, $get('jawaban_benar')) === 0) {
                            $matched = $letter;
                            break;
                        }
                    }
                    if ($matched) {
                        $pgCorrect = $matched;
                    } else {
                        $errors[] = "Baris {$rowNumber}: jawaban_benar tidak cocok dengan opsi.";
                        continue;
                    }
                }

                $options = array_values($optionsByLetter);
            }

            $rowsToInsert[] = [
                'ujian_id' => $ujian->id,
                'tipe' => $tipe,
                'pertanyaan' => $pertanyaan,
                'bobot' => $bobot,
                'options' => $options,
                'pg_correct' => $pgCorrect,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        fclose($handle);

        if ($errors) {
            $preview = array_slice($errors, 0, 5);
            $suffix = count($errors) > 5 ? ' (dan lainnya)' : '';
            return redirect()->back()->withErrors([
                'import' => implode(' ', $preview) . $suffix,
            ]);
        }

        if (count($rowsToInsert) === 0) {
            return redirect()->back()->withErrors(['import' => 'Tidak ada data soal yang bisa diimpor.']);
        }

        DB::transaction(function () use ($rowsToInsert) {
            foreach ($rowsToInsert as $row) {
                Soal::create($row);
            }
        });

        return redirect()->back()->with('import_success', 'Soal berhasil diimpor: ' . count($rowsToInsert) . ' baris.');
    }

    public function updateSoal(Request $request, Soal $soal)
    {
        $request->validate([
            'tipe' => 'required|in:essay,pg',
            'pertanyaan' => 'required|string',
            'media' => 'nullable|file|max:51200',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'pg_correct' => 'required_if:tipe,pg|string',
            'bobot' => 'nullable|numeric|min:0',
        ]);

        if ($request->tipe === 'pg') {
            $options = array_values(array_filter($request->options ?? []));
            if (count($options) === 0) {
                return back()->withErrors(['options' => 'Jawaban pilihan ganda wajib diisi.'])->withInput();
            }
            $correct = $request->pg_correct;
            $maxIndex = count($options) - 1;
            $maxLetter = chr(65 + $maxIndex);
            if (!preg_match('/^[A-Z]$/', $correct) || $correct < 'A' || $correct > $maxLetter) {
                return back()->withErrors(['pg_correct' => 'Jawaban benar tidak valid.'])->withInput();
            }
        }

        $soal->tipe = $request->tipe;
        $soal->pertanyaan = $request->pertanyaan;
        $soal->bobot = $request->bobot;
        $soal->options = $request->tipe === 'pg' ? array_values(array_filter($request->options ?? [])) : null;
        $soal->pg_correct = $request->tipe === 'pg' ? $request->pg_correct : null;

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('soal', 'public');
            $soal->media_path = $path;
        }

        $soal->save();

        return redirect()->back()->with('success', 'Soal berhasil diperbarui');
    }

    public function destroySoal(Soal $soal)
    {
        $soal->delete();
        return redirect()->back()->with('success', 'Soal berhasil dihapus');
    }

    public function destroyAllSoal(Ujian $ujian)
    {
        Soal::where('ujian_id', $ujian->id)->delete();
        return redirect()->back()->with('delete_all_success', 'Semua soal berhasil dihapus');
    }

    public function generateSoalAI(Request $request, Ujian $ujian)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
        ]);

        $apiKey = env('OPENROUTER_API_KEY');
        $model = env('OPENROUTER_MODEL', 'mistralai/mistral-small-3.1-24b-instruct:free');

        if (!$apiKey) {
            return response()->json(['message' => 'OPENROUTER_API_KEY belum diset.'], 422);
        }

        $system = "You are an exam question generator. Return ONLY valid JSON with this schema:\n"
            . "{ \"questions\": [ { \"tipe\": \"pg|essay\", \"pertanyaan\": \"string\", \"difficulty\": \"easy|medium|hard|very hard\", \"options\": [\"A\", \"B\"...], \"pg_correct\": \"A|B|C|D|E\" } ] }\n"
            . "Rules: PG must have 3-5 options; Essay must have options=null and pg_correct=null. "
            . "Questions must be in Indonesian. No markdown, no code fences.";

        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $request->prompt],
            ],
            'temperature' => 0.7,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'HTTP-Referer' => $request->getSchemeAndHttpHost(),
                'X-Title' => 'ZStudy Mozart.Ai',
            ])->timeout(60)->retry(1, 1000)->post('https://openrouter.ai/api/v1/chat/completions', $payload);
        } catch (\Throwable $e) {
            \Log::error('OpenRouter request failed', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Gagal menghubungi OpenRouter.',
                'detail' => $e->getMessage(),
            ], 502);
        }

        if (!$response->successful()) {
            \Log::error('OpenRouter response error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return response()->json([
                'message' => 'Gagal menghubungi OpenRouter.',
                'detail' => $response->json(),
                'status' => $response->status(),
            ], 502);
        }

        $content = data_get($response->json(), 'choices.0.message.content', '');
        $content = trim((string) $content);

        $stripCodeFences = function ($text) {
            if (preg_match('/```(?:json)?\s*(.*?)\s*```/is', $text, $m)) {
                return trim($m[1]);
            }
            return $text;
        };

        $normalizeJson = function ($text) {
            $text = trim($text);
            $text = preg_replace('/^\xEF\xBB\xBF/', '', $text);
            // remove trailing commas before } or ]
            $text = preg_replace('/,\s*([}\]])/', '$1', $text);
            return $text;
        };

        $repairJson = function ($text) {
            $inString = false;
            $escape = false;
            $brace = 0;
            $bracket = 0;
            $len = strlen($text);
            for ($i = 0; $i < $len; $i++) {
                $ch = $text[$i];
                if ($escape) {
                    $escape = false;
                    continue;
                }
                if ($ch === '\\') {
                    $escape = true;
                    continue;
                }
                if ($ch === '"') {
                    $inString = !$inString;
                    continue;
                }
                if ($inString) {
                    continue;
                }
                if ($ch === '{') {
                    $brace++;
                } elseif ($ch === '}') {
                    $brace = max(0, $brace - 1);
                } elseif ($ch === '[') {
                    $bracket++;
                } elseif ($ch === ']') {
                    $bracket = max(0, $bracket - 1);
                }
            }
            if ($bracket > 0) {
                $text .= str_repeat(']', $bracket);
            }
            if ($brace > 0) {
                $text .= str_repeat('}', $brace);
            }
            return $text;
        };

        $raw = $normalizeJson($stripCodeFences($content));
        $jsonStart = strpos($raw, '{');
        $jsonEnd = strrpos($raw, '}');
        $jsonText = $raw;

        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonText = substr($raw, $jsonStart, $jsonEnd - $jsonStart + 1);
        }

        $decoded = json_decode($jsonText, true);
        if (!is_array($decoded)) {
            $repaired = $normalizeJson($repairJson($jsonText));
            $decoded = json_decode($repaired, true);
        }
        if (!is_array($decoded)) {
            // if AI returned a JSON array, wrap it
            $arrayDecoded = json_decode($raw, true);
            if (is_array($arrayDecoded) && isset($arrayDecoded[0])) {
                $decoded = ['questions' => $arrayDecoded];
            }
        }

        if (!is_array($decoded)) {
            $snippet = mb_substr($content, 0, 800);
            \Log::warning('OpenRouter JSON decode failed', [
                'content_snippet' => $snippet,
                'json_error' => json_last_error_msg(),
            ]);
            return response()->json([
                'message' => 'Format jawaban AI tidak valid.',
                'detail' => 'JSON tidak bisa diparse. Cuplikan: ' . $snippet,
            ], 422);
        }

        $items = $decoded['questions'] ?? null;
        if (!is_array($items) || count($items) === 0) {
            \Log::warning('OpenRouter empty questions', [
                'decoded' => $decoded,
            ]);
            return response()->json(['message' => 'Tidak ada soal yang dihasilkan.'], 422);
        }

        $difficultyMap = [
            'easy' => 5,
            'mudah' => 5,
            'medium' => 10,
            'menengah' => 10,
            'hard' => 15,
            'tinggi' => 15,
            'very hard' => 20,
            'very_hard' => 20,
            'sangat tinggi' => 20,
        ];

        $created = 0;
        $errors = [];

        foreach ($items as $idx => $item) {
            $tipe = strtolower(trim((string) ($item['tipe'] ?? '')));
            $pertanyaan = trim((string) ($item['pertanyaan'] ?? ''));
            $difficultyRaw = strtolower(trim((string) ($item['difficulty'] ?? 'medium')));
            $difficultyKey = preg_replace('/\s+/', ' ', $difficultyRaw);
            $bobot = $difficultyMap[$difficultyKey] ?? 10;

            if (!in_array($tipe, ['pg', 'essay'], true)) {
                $errors[] = "Item " . ($idx + 1) . ": tipe tidak valid.";
                continue;
            }
            if ($pertanyaan === '') {
                $errors[] = "Item " . ($idx + 1) . ": pertanyaan kosong.";
                continue;
            }

            $options = null;
            $pgCorrect = null;

            if ($tipe === 'pg') {
                $rawOptions = $item['options'] ?? [];
                if (!is_array($rawOptions)) {
                    $errors[] = "Item " . ($idx + 1) . ": options harus array.";
                    continue;
                }
                $stripPrefix = function ($value) {
                    $value = trim((string) $value);
                    return preg_replace('/^\s*[A-E]\s*[\.\)]\s*/i', '', $value);
                };
                $options = array_values(array_filter(array_map(fn($v) => trim($stripPrefix($v)), $rawOptions)));
                if (count($options) < 3 || count($options) > 5) {
                    $errors[] = "Item " . ($idx + 1) . ": jumlah options harus 3-5.";
                    continue;
                }

                $pgCorrect = strtoupper(trim((string) ($item['pg_correct'] ?? '')));
                $letters = array_slice(['A', 'B', 'C', 'D', 'E'], 0, count($options));
                if (!in_array($pgCorrect, $letters, true)) {
                    $matched = null;
                    foreach ($options as $i => $opt) {
                        $rawCorrect = $stripPrefix($item['pg_correct'] ?? '');
                        if (strcasecmp($opt, (string) $rawCorrect) === 0) {
                            $matched = $letters[$i];
                            break;
                        }
                    }
                    if ($matched) {
                        $pgCorrect = $matched;
                    } else {
                        $errors[] = "Item " . ($idx + 1) . ": pg_correct tidak valid.";
                        continue;
                    }
                }

                if ($bobot > 10) {
                    $bobot = 10;
                }
            } else {
                if ($bobot < 10) {
                    $bobot = 10;
                }
                if ($bobot > 20) {
                    $bobot = 20;
                }
            }

            Soal::create([
                'ujian_id' => $ujian->id,
                'tipe' => $tipe,
                'pertanyaan' => $pertanyaan,
                'bobot' => $bobot,
                'options' => $options,
                'pg_correct' => $pgCorrect,
            ]);
            $created++;
        }

        if ($created === 0) {
            return response()->json([
                'message' => 'Tidak ada soal valid yang bisa disimpan.',
                'errors' => $errors,
            ], 422);
        }

        return response()->json([
            'message' => 'Soal berhasil dibuat.',
            'created' => $created,
            'errors' => $errors,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas_id' => 'required|exists:kelas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_ujian' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mulai_ujian' => 'required|date',
            'deadline' => 'required|date|after:mulai_ujian',
            'file_ujian' => 'nullable|file|max:51200',
        ]);

        $data = [
            'nama_kelas_id' => $request->nama_kelas_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_ujian' => $request->nama_ujian,
            'deskripsi' => $request->deskripsi,
            'mulai_ujian' => $request->mulai_ujian,
            'deadline' => $request->deadline,
        ];

        if (Schema::hasColumn('ujians', 'ujian_ke')) {
            $lastNumber = Ujian::where('nama_kelas_id', $request->nama_kelas_id)->max('ujian_ke') ?? 0;
            $data['ujian_ke'] = $lastNumber + 1;
        }

        $ujian = Ujian::create($data);

        if ($request->hasFile('file_ujian')) {
            $file = $request->file('file_ujian');
            $path = $file->store('ujian', 'public');
            $ujian->file_path = $path;
            $ujian->file_name = $file->getClientOriginalName();
            $ujian->save();
        }

        return redirect()->back()->with('success', 'Ujian berhasil dibuat');
    }

    public function update(Request $request, Ujian $ujian)
    {
        $request->validate([
            'nama_kelas_id' => 'required|exists:kelas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_ujian' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mulai_ujian' => 'required|date',
            'deadline' => 'required|date|after:mulai_ujian',
            'file_ujian' => 'nullable|file|max:51200',
        ]);

        if ($ujian->nama_kelas_id != $request->nama_kelas_id && Schema::hasColumn('ujians', 'ujian_ke')) {
            $lastNumber = Ujian::where('nama_kelas_id', $request->nama_kelas_id)->max('ujian_ke') ?? 0;
            $ujian->ujian_ke = $lastNumber + 1;
        }

        $updateData = [
            'nama_kelas_id' => $request->nama_kelas_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_ujian' => $request->nama_ujian,
            'deskripsi' => $request->deskripsi,
            'mulai_ujian' => $request->mulai_ujian,
            'deadline' => $request->deadline,
        ];

        if ($ujian->nama_kelas_id != $request->nama_kelas_id && Schema::hasColumn('ujians', 'ujian_ke')) {
            $lastNumber = Ujian::where('nama_kelas_id', $request->nama_kelas_id)->max('ujian_ke') ?? 0;
            $ujian->ujian_ke = $lastNumber + 1;
        }

        $ujian->update($updateData);

        if ($request->hasFile('file_ujian')) {
            if ($ujian->file_path && Storage::disk('public')->exists($ujian->file_path)) {
                Storage::disk('public')->delete($ujian->file_path);
            }
            $file = $request->file('file_ujian');
            $path = $file->store('ujian', 'public');
            $ujian->file_path = $path;
            $ujian->file_name = $file->getClientOriginalName();
            $ujian->save();
        }

        return redirect()->back()->with('success', 'Ujian berhasil diperbarui');
    }

    public function destroy(Ujian $ujian)
    {
        if ($ujian->file_path && Storage::disk('public')->exists($ujian->file_path)) {
            Storage::disk('public')->delete($ujian->file_path);
        }
        $kelasId = $ujian->nama_kelas_id;
        $ujian->delete();

        if ($kelasId && Schema::hasColumn('ujians', 'ujian_ke')) {
            $rows = Ujian::where('nama_kelas_id', $kelasId)
                ->orderBy('created_at')
                ->get();
            $counter = 1;
            foreach ($rows as $row) {
                $row->ujian_ke = $counter++;
                $row->save();
            }
        }

        return redirect()->back()->with('success', 'Ujian berhasil dihapus');
    }

    private function getMahasiswaKelasIds(?int $mahasiswaId)
    {
        if (!$mahasiswaId) {
            return collect();
        }

        return Kelas::query()
            ->whereHas('mahasiswas', function ($mq) use ($mahasiswaId) {
                $mq->where('mahasiswa_id', $mahasiswaId)
                    ->where(function ($sq) {
                        $sq->whereNull('kelas_mahasiswa.status')
                            ->orWhere('kelas_mahasiswa.status', 'disetujui');
                    });
            })
            ->pluck('id');
    }


    public function mahasiswa()
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $kelasIds = $this->getMahasiswaKelasIds($mahasiswaId);

        if ($kelasIds->isEmpty()) {
            return view('mahasiswa.ujian.ujian', ['ujian_kelas' => collect()]);
        }

        $ujian_kelas = Ujian::with(['mataKuliah', 'kelas'])
            ->whereIn('nama_kelas_id', $kelasIds)
            ->where(function ($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now());
            })
            ->whereDoesntHave('hasilUjian', function ($q) use ($mahasiswaId) {
                if ($mahasiswaId) {
                    $q->where('mahasiswa_id', $mahasiswaId)->whereNotNull('submitted_at');
                }
            })
            ->orderByRaw('CASE WHEN mulai_ujian IS NULL THEN 1 ELSE 0 END, mulai_ujian ASC')
            ->get();

        return view('mahasiswa.ujian.ujian', compact('ujian_kelas'));
    }

    public function mahasiswaSelesai()
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $kelasIds = $this->getMahasiswaKelasIds($mahasiswaId);

        if ($kelasIds->isEmpty()) {
            return view('mahasiswa.ujian.ujian_selesai', ['ujian_kelas' => collect()]);
        }

        $ujian_kelas = Ujian::with(['mataKuliah', 'kelas', 'soals', 'hasilUjian'])
            ->whereIn('nama_kelas_id', $kelasIds)
            ->whereNotNull('deadline')
            ->where(function ($q) use ($mahasiswaId) {
                $q->where('deadline', '<', now())
                  ->orWhereHas('hasilUjian', function ($hq) use ($mahasiswaId) {
                      if ($mahasiswaId) {
                          $hq->where('mahasiswa_id', $mahasiswaId)->whereNotNull('submitted_at');
                      }
                  });
            })
            ->latest()
            ->get();

        $jawabanMap = [];
        if ($mahasiswaId) {
            $jawabanMap = JawabanMahasiswa::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('ujian_id', $ujian_kelas->pluck('id'))
                ->get()
                ->groupBy('ujian_id')
                ->map(function ($rows) {
                    return $rows->keyBy('soal_id')->map(function ($row) {
                        return [
                            'tipe' => $row->tipe,
                            'jawaban_pg' => $row->jawaban_pg,
                            'jawaban_text' => $row->jawaban_text,
                        ];
                    });
                })
                ->toArray();
        }

        $nilaiMap = [];
        if ($mahasiswaId) {
            $nilaiMap = HasilUjian::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('ujian_id', $ujian_kelas->pluck('id'))
                ->pluck('nilai', 'ujian_id')
                ->toArray();
        }

        return view('mahasiswa.ujian.ujian_selesai', compact('ujian_kelas', 'jawabanMap', 'nilaiMap'));
    }

    public function mahasiswaSoal(Ujian $ujian)
    {
        $ujian->load(['mataKuliah', 'kelas']);
        $soalList = Soal::where('ujian_id', $ujian->id)->orderBy('created_at')->get();

        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');
        $jawabanMap = [];
        if ($mahasiswaId) {
            $jawabanMap = JawabanMahasiswa::where('mahasiswa_id', $mahasiswaId)
                ->where('ujian_id', $ujian->id)
                ->get()
                ->keyBy('soal_id')
                ->map(function ($row) {
                    return [
                        'tipe' => $row->tipe,
                        'jawaban_pg' => $row->jawaban_pg,
                        'jawaban_text' => $row->jawaban_text,
                    ];
                })
                ->toArray();
        }

        return view('mahasiswa.ujian.soal', compact('ujian', 'soalList', 'jawabanMap'));
    }

    public function saveJawaban(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'soal_id' => 'required|exists:soals,id',
            'tipe' => 'required|in:pg,essay',
            'jawaban' => 'nullable|string',
        ]);

        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');
        if (!$mahasiswaId) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan.'], 404);
        }

        $ujian = Ujian::find($request->ujian_id);
        if (!$ujian) {
            return response()->json(['message' => 'Ujian tidak ditemukan.'], 404);
        }

        $isMember = Kelas::where('id', $ujian->nama_kelas_id)
            ->whereHas('mahasiswas', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId)
                    ->where(function ($sq) {
                        $sq->whereNull('kelas_mahasiswa.status')
                            ->orWhere('kelas_mahasiswa.status', 'disetujui');
                    });
            })
            ->exists();

        if (!$isMember) {
            return response()->json(['message' => 'Tidak terdaftar di kelas ujian ini.'], 403);
        }

        $jawaban = trim((string) ($request->jawaban ?? ''));

        JawabanMahasiswa::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'soal_id' => $request->soal_id,
            ],
            [
                'ujian_id' => $request->ujian_id,
                'tipe' => $request->tipe,
                'jawaban_pg' => $request->tipe === 'pg' ? $jawaban : null,
                'jawaban_text' => $request->tipe === 'essay' ? $jawaban : null,
            ]
        );

        return response()->json(['message' => 'Jawaban tersimpan.']);
    }

    public function submitUjian(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
        ]);

        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');
        if (!$mahasiswaId) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan.'], 404);
        }

        $ujian = Ujian::find($request->ujian_id);
        if (!$ujian) {
            return response()->json(['message' => 'Ujian tidak ditemukan.'], 404);
        }

        $isMember = Kelas::where('id', $ujian->nama_kelas_id)
            ->whereHas('mahasiswas', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId)
                    ->where(function ($sq) {
                        $sq->whereNull('kelas_mahasiswa.status')
                            ->orWhere('kelas_mahasiswa.status', 'disetujui');
                    });
            })
            ->exists();

        if (!$isMember) {
            return response()->json(['message' => 'Tidak terdaftar di kelas ujian ini.'], 403);
        }

        HasilUjian::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'ujian_id' => $ujian->id,
            ],
            [
                'submitted_at' => now(),
            ]
        );

        return response()->json(['message' => 'Ujian berhasil diselesaikan.']);
    }

    public function saveNilaiUjian(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'nilai' => 'nullable|numeric|min:0',
            'nilai_kecepatan' => 'nullable|numeric|min:0',
        ]);

        $ujian = Ujian::find($request->ujian_id);
        if (!$ujian) {
            return response()->json(['message' => 'Ujian tidak ditemukan.'], 404);
        }

        $isMember = Kelas::where('id', $ujian->nama_kelas_id)
            ->whereHas('mahasiswas', function ($q) use ($request) {
                $q->where('mahasiswa_id', $request->mahasiswa_id)
                    ->where(function ($sq) {
                        $sq->whereNull('kelas_mahasiswa.status')
                            ->orWhere('kelas_mahasiswa.status', 'disetujui');
                    });
            })
            ->exists();

        if (!$isMember) {
            return response()->json(['message' => 'Mahasiswa tidak terdaftar di kelas ujian ini.'], 403);
        }

        $soals = Soal::where('ujian_id', $ujian->id)->get()->keyBy('id');
        $jawabans = JawabanMahasiswa::where('ujian_id', $ujian->id)
            ->where('mahasiswa_id', $request->mahasiswa_id)
            ->get()
            ->keyBy('soal_id');

        $totalBobot = $soals->sum('bobot');
        $poinPg = 0;
        $poinEssay = 0;

        foreach ($soals as $soal) {
            $jawaban = $jawabans->get($soal->id);
            if ($soal->tipe === 'pg') {
                $jawabPg = strtoupper((string) ($jawaban?->jawaban_pg ?? ''));
                $benar = strtoupper((string) ($soal->pg_correct ?? ''));
                if ($jawabPg !== '' && $benar !== '' && $jawabPg === $benar) {
                    $poinPg += (float) ($soal->bobot ?? 0);
                }
            } else {
                $poinEssay += (float) ($jawaban?->essay_score ?? 0);
            }
        }

        $totalPoin = $poinPg + $poinEssay;
        $nilaiAkhir = $totalBobot > 0 ? round(($totalPoin / $totalBobot) * 100) : 0;

        HasilUjian::updateOrCreate(
            [
                'mahasiswa_id' => $request->mahasiswa_id,
                'ujian_id' => $ujian->id,
            ],
            [
                'nilai' => $nilaiAkhir,
                'nilai_kecepatan' => $request->nilai_kecepatan ?? 0,
            ]
        );

        return response()->json([
            'message' => 'Nilai tersimpan.',
            'nilai' => $nilaiAkhir,
        ]);
    }

    public function saveEssayScore(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'soal_id' => 'required|exists:soals,id',
            'essay_score' => 'nullable|numeric|min:0',
        ]);

        $ujian = Ujian::find($request->ujian_id);
        if (!$ujian) {
            return response()->json(['message' => 'Ujian tidak ditemukan.'], 404);
        }

        $isMember = Kelas::where('id', $ujian->nama_kelas_id)
            ->whereHas('mahasiswas', function ($q) use ($request) {
                $q->where('mahasiswa_id', $request->mahasiswa_id)
                    ->where(function ($sq) {
                        $sq->whereNull('kelas_mahasiswa.status')
                            ->orWhere('kelas_mahasiswa.status', 'disetujui');
                    });
            })
            ->exists();

        if (!$isMember) {
            return response()->json(['message' => 'Mahasiswa tidak terdaftar di kelas ujian ini.'], 403);
        }

        JawabanMahasiswa::updateOrCreate(
            [
                'mahasiswa_id' => $request->mahasiswa_id,
                'soal_id' => $request->soal_id,
            ],
            [
                'ujian_id' => $request->ujian_id,
                'essay_score' => $request->essay_score ?? 0,
            ]
        );

        return response()->json(['message' => 'Nilai essay tersimpan.']);
    }
}

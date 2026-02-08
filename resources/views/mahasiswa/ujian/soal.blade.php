<x-header>Ujian</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

@php
  $soalList = $soalList ?? collect();
  $jawabanMap = $jawabanMap ?? [];
  $mataKuliahNama = $ujian->mataKuliah->mata_kuliah ?? '-';
  $kelasNama = $ujian->kelas->nama_kelas ?? '-';
@endphp

<style>
  [data-exam-lock="true"] ~ .main-content nav,
  [data-exam-lock="true"] ~ .main-content .sidebar,
  [data-exam-lock="true"] ~ .main-content .sidebar-menu-button,
  [data-exam-lock="true"] ~ .main-content .sidebar-toggler,
  nav,
  .sidebar,
  .sidebar-menu-button,
  .sidebar-toggler {
    pointer-events: none;
  }
  nav *, .sidebar * {
    pointer-events: none;
  }
</style>

<div class="p-6 bg-gray-100 min-h-screen" id="ujianSoalPage" data-exam-lock="true">
  <div class="mb-6">
<div class="rounded-2xl border bg-white p-5 shadow-sm">
      <h2 class="text-xl font-semibold text-slate-800">{{ $ujian->nama_ujian ?? 'Ujian' }}</h2>
      <p class="text-sm text-slate-500 mt-1">{{ $ujian->deskripsi ?? '-' }}</p>
      <p class="text-sm text-slate-500 mt-1">{{ $mataKuliahNama }} ? Kelas {{ $kelasNama }}</p>
    </div>
  </div>

  <div class="space-y-4" id="soalWrapper" data-ujian-id="{{ $ujian->id }}">
    @if ($soalList->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada soal.
      </div>
    @endif

    @foreach ($soalList as $soal)
      @php
        $jawaban = $jawabanMap[$soal->id] ?? null;
        $selectedPg = $jawaban['jawaban_pg'] ?? '';
        $essayText = $jawaban['jawaban_text'] ?? '';
      @endphp
      <div
        class="bg-white rounded-xl border p-5 relative"
        data-soal-id="{{ $soal->id }}"
        data-tipe="{{ $soal->tipe ?? 'essay' }}"
        data-selected="{{ $selectedPg }}"
      >
        <div class="absolute top-4 right-4 flex items-center gap-2 text-xs">
          <span class="font-semibold text-emerald-600">+{{ $soal->bobot ?? 0 }}</span>
          <span class="px-2 py-0.5 rounded-md font-semibold uppercase {{ ($soal->tipe ?? 'essay') === 'pg' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600' }}">
            {{ ($soal->tipe ?? 'essay') === 'pg' ? 'PG' : 'Essay' }}
          </span>
        </div>
        <div class="flex items-start gap-4">
          <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold">
            {{ $loop->iteration }}
          </div>
          <div class="flex-1">
            <div class="flex items-start justify-between gap-3">
              <h3 class="font-semibold text-slate-800">{{ $soal->pertanyaan ?? '-' }}</h3>
            </div>

            @if (($soal->tipe ?? 'essay') === 'pg' && !empty($soal->options))
              <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-slate-600">
                @foreach ($soal->options as $idx => $opt)
                  <button type="button" class="pg-option flex items-center gap-2 px-1 py-2 text-left hover:bg-transparent" data-option="{{ chr(65 + $idx) }}">
                    <span class="pg-letter w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-semibold">
                      {{ chr(65 + $idx) }}
                    </span>
                    <span class="pg-text">{{ $opt }}</span>
                  </button>
                @endforeach
              </div>
            @else
              <div class="mt-3">
                <textarea
                  class="essay-input w-full rounded-lg border border-slate-300 px-4 py-2 text-sm"
                  rows="4"
                  placeholder="Tulis jawaban..."
                >{{ $essayText }}</textarea>
              </div>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-6 flex justify-end">
    <button id="btnFinishUjian" type="button" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
      <span class="material-symbols-rounded text-base">task_alt</span>
      Selesai
    </button>
  </div>
</div>

<div id="countdownOverlay" class="fixed top-20 right-6 z-40">
  <div id="countdownBadge" class="rounded-full text-white text-xs font-semibold px-3 py-1.5 shadow bg-emerald-600">
    <span class="mr-1">Sisa Waktu</span>
    <span class="deadline-countdown" data-deadline="{{ $ujian->deadline ? \Carbon\Carbon::parse($ujian->deadline)->toIso8601String() : '' }}">-</span>
  </div>
</div>

<script>
  const countdownEls = document.querySelectorAll('.deadline-countdown');
  const countdownBadge = document.getElementById('countdownBadge');
  const formatTime = (ms) => {
    const totalSeconds = Math.max(0, Math.floor(ms / 1000));
    const h = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
    const m = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
    const s = String(totalSeconds % 60).padStart(2, '0');
    return `${h}:${m}:${s}`;
  };

  const updateCountdowns = () => {
    const now = Date.now();
    countdownEls.forEach((el) => {
      const deadlineStr = el.dataset.deadline || '';
      if (!deadlineStr) {
        el.textContent = '-';
        return;
      }
      const deadline = Date.parse(deadlineStr);
      if (Number.isNaN(deadline)) {
        el.textContent = '-';
        return;
      }
      const diff = deadline - now;
      if (diff <= 0) {
        el.textContent = 'Selesai';
        countdownBadge?.classList.remove('bg-emerald-600', 'bg-amber-500', 'bg-red-600');
        countdownBadge?.classList.add('bg-red-600');
        if (window.submitUjian) {
          window.submitUjian();
        }
        return;
      }

      el.textContent = formatTime(diff);
      if (countdownBadge) {
        countdownBadge.classList.remove('bg-emerald-600', 'bg-amber-500', 'bg-red-600');
        if (diff < 10 * 60 * 1000) {
          countdownBadge.classList.add('bg-red-600');
        } else if (diff < 30 * 60 * 1000) {
          countdownBadge.classList.add('bg-amber-500');
        } else {
          countdownBadge.classList.add('bg-emerald-600');
        }
      }
    });
  };

  updateCountdowns();
  setInterval(updateCountdowns, 1000);
</script>

<script>
  const soalWrapper = document.getElementById('soalWrapper');
  const ujianId = soalWrapper?.dataset?.ujianId || '';
  const csrfToken = '{{ csrf_token() }}';

  const saveJawaban = async ({ soalId, tipe, jawaban }) => {
    const res = await fetch('{{ route('mahasiswa.ujian.jawaban.save') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        ujian_id: ujianId,
        soal_id: soalId,
        tipe,
        jawaban,
      }),
    });
    if (!res.ok) {
      const data = await res.json().catch(() => ({}));
      throw new Error(data.message || 'Gagal menyimpan jawaban.');
    }
  };

  document.querySelectorAll('[data-soal-id]').forEach((card) => {
    const soalId = card.dataset.soalId;
    const tipe = (card.dataset.tipe || 'essay').toLowerCase();
    const selectedPg = (card.dataset.selected || '').toUpperCase();

    if (tipe === 'pg') {
      const options = card.querySelectorAll('.pg-option');
      if (selectedPg) {
        const target = Array.from(options).find((b) => (b.dataset.option || '').toUpperCase() === selectedPg);
        if (target) {
          target.querySelector('.pg-letter')?.classList.remove('bg-slate-100', 'text-slate-600');
          target.querySelector('.pg-letter')?.classList.add('bg-blue-600', 'text-white');
          target.querySelector('.pg-text')?.classList.add('text-blue-600', 'font-semibold');
        }
      }
      options.forEach((btn) => {
        btn.addEventListener('click', async () => {
          options.forEach((b) => {
            b.classList.remove('text-blue-600');
            b.querySelector('.pg-letter')?.classList.remove('bg-blue-600', 'text-white');
            b.querySelector('.pg-letter')?.classList.add('bg-slate-100', 'text-slate-600');
            b.querySelector('.pg-text')?.classList.remove('text-blue-600', 'font-semibold');
          });
          btn.querySelector('.pg-letter')?.classList.remove('bg-slate-100', 'text-slate-600');
          btn.querySelector('.pg-letter')?.classList.add('bg-blue-600', 'text-white');
          btn.querySelector('.pg-text')?.classList.add('text-blue-600', 'font-semibold');
          try {
            await saveJawaban({ soalId, tipe: 'pg', jawaban: btn.dataset.option || '' });
          } catch (e) {
            console.error(e);
          }
        });
      });
    } else {
      const input = card.querySelector('.essay-input');
      let timer = null;
      input?.addEventListener('input', () => {
        if (timer) clearTimeout(timer);
        timer = setTimeout(async () => {
          try {
            await saveJawaban({ soalId, tipe: 'essay', jawaban: input.value || '' });
          } catch (e) {
            console.error(e);
          }
        }, 500);
      });
    }
  });
</script>

<script>
  const sidebar = document.querySelector('.sidebar');
  sidebar?.classList.add('collapsed');

  document.querySelectorAll('.sidebar a, .sidebar button, .sidebar .dropdown-toggle, .sidebar input, .sidebar select, .sidebar textarea').forEach((el) => {
    el.classList.add('pointer-events-none', 'opacity-60');
    el.setAttribute('tabindex', '-1');
    el.setAttribute('aria-disabled', 'true');
    if (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA') {
      el.setAttribute('disabled', 'disabled');
    }
  });

  document.querySelectorAll('.sidebar-menu-button, .sidebar-toggler').forEach((el) => {
    el.classList.add('pointer-events-none', 'opacity-60');
    el.setAttribute('tabindex', '-1');
    el.setAttribute('aria-disabled', 'true');
  });

  document.querySelectorAll('nav button, nav a, nav input, nav select, nav textarea').forEach((el) => {
    el.classList.add('pointer-events-none', 'opacity-60');
    el.setAttribute('tabindex', '-1');
    el.setAttribute('aria-disabled', 'true');
    if (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA') {
      el.setAttribute('disabled', 'disabled');
    }
  });

  const notifBtn = document.getElementById('notifButton');
  const notifDropdown = document.getElementById('notifDropdown');
  notifBtn?.classList.add('pointer-events-none', 'opacity-60');
  notifBtn?.setAttribute('tabindex', '-1');
  if (notifDropdown) {
    notifDropdown.classList.add('hidden');
    notifDropdown.setAttribute('aria-hidden', 'true');
  }
</script>

<!-- MODAL SELESAI UJIAN -->
<div id="finishUjianModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm px-4">
  <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl p-6">
    <h4 class="text-lg font-semibold text-slate-800">Selesai Ujian</h4>
    <p class="mt-3 text-sm text-slate-600">Apakah anda yakin ingin mengakhiri ujian?</p>
    <p id="unfinishedInfo" class="mt-3 text-sm text-amber-600"></p>
    <div class="mt-6 flex items-center justify-end gap-2">
      <button id="btnCancelFinish" type="button" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Tidak</button>
      <button id="btnConfirmFinish" type="button" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Ya</button>
    </div>
  </div>
</div>

<script>
  const btnFinishUjian = document.getElementById('btnFinishUjian');
  const finishUjianModal = document.getElementById('finishUjianModal');
  const btnCancelFinish = document.getElementById('btnCancelFinish');
  const btnConfirmFinish = document.getElementById('btnConfirmFinish');
  const unfinishedInfo = document.getElementById('unfinishedInfo');
  let isSubmitting = false;

  const getUnanswered = () => {
    const cards = document.querySelectorAll('[data-soal-id]');
    const missing = [];
    cards.forEach((card, idx) => {
      const tipe = (card.dataset.tipe || 'essay').toLowerCase();
      if (tipe === 'pg') {
        const selected = card.querySelector('.pg-letter.bg-blue-600');
        if (!selected) missing.push(idx + 1);
      } else {
        const input = card.querySelector('.essay-input');
        if (!input || !input.value.trim()) missing.push(idx + 1);
      }
    });
    return missing;
  };

  const openFinishModal = () => {
    const missing = getUnanswered();
    if (unfinishedInfo) {
      unfinishedInfo.textContent = missing.length
        ? `Soal no. ${missing.join(', ')} belum di jawab.`
        : 'Semua soal sudah terjawab.';
    }
    finishUjianModal?.classList.remove('hidden');
    finishUjianModal?.classList.add('flex');
  };

  const closeFinishModal = () => {
    finishUjianModal?.classList.add('hidden');
    finishUjianModal?.classList.remove('flex');
  };

  btnFinishUjian?.addEventListener('click', openFinishModal);
  btnCancelFinish?.addEventListener('click', closeFinishModal);
  finishUjianModal?.addEventListener('click', (e) => {
    if (e.target === finishUjianModal) closeFinishModal();
  });

  window.submitUjian = async () => {
    if (isSubmitting) return;
    isSubmitting = true;
    try {
      const res = await fetch('{{ route('mahasiswa.ujian.submit') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ ujian_id: ujianId }),
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || 'Gagal mengakhiri ujian.');
      }
      window.location.href = '{{ route('mahasiswa.ujian.selesai') }}';
    } catch (e) {
      console.error(e);
      isSubmitting = false;
    }
  };

  btnConfirmFinish?.addEventListener('click', () => {
    closeFinishModal();
    submitUjian();
  });
</script>

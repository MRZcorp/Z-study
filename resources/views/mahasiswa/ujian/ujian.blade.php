<x-header>Ujian</x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

@php
  $now = \Carbon\Carbon::now();
  $ujian_kelas = $ujian_kelas ?? collect();
@endphp

<div id="ujianPage" class="p-6 bg-gray-100 min-h-screen" data-server-now="{{ \Carbon\Carbon::now()->timestamp * 1000 }}">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h2 class="text-xl font-semibold text-slate-800">Ujian</h2>
      <p class="text-sm text-slate-500">Daftar ujian dari kelas yang kamu ikuti.</p>
    </div>
  </div>

  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow w-fit">
      <span class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-800 text-white shadow">Ujian Aktif</span>
      <a href="{{ url('/mahasiswa/ujian_selesai') }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-600 hover:bg-gray-100">Ujian Selesai</a>
    </div>
  </div>

  <div class="space-y-4">
    @if ($ujian_kelas->isEmpty())
      <div class="rounded-xl border bg-white p-6 text-sm text-slate-500">
        Belum ada ujian.
      </div>
    @endif

    @foreach ($ujian_kelas as $ujian)
      @php
        $mulaiUjian = $ujian->mulai_ujian ? \Carbon\Carbon::parse($ujian->mulai_ujian) : null;
        $deadlineUjian = $ujian->deadline ? \Carbon\Carbon::parse($ujian->deadline) : null;
        $startIso = $mulaiUjian ? $mulaiUjian->toIso8601String() : '';
        $deadlineIso = $deadlineUjian ? $deadlineUjian->toIso8601String() : '';
        $startMs = $mulaiUjian ? ($mulaiUjian->timestamp * 1000) : '';
        $deadlineMs = $deadlineUjian ? ($deadlineUjian->timestamp * 1000) : '';
      @endphp
      <div class="ujian-card bg-white rounded-xl border p-5 flex flex-col gap-4 relative" data-start="{{ $startIso }}" data-deadline="{{ $deadlineIso }}" data-start-ms="{{ $startMs }}" data-deadline-ms="{{ $deadlineMs }}">
        <div class="absolute top-4 right-4 flex items-center gap-2 text-xs text-slate-500">
          <span class="start-badge inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700">
            <span class="material-symbols-rounded text-sm">schedule</span>
            <span class="start-time">{{ $ujian->mulai_ujian ? \Carbon\Carbon::parse($ujian->mulai_ujian)->format('d M Y H:i') : '-' }}</span>
          </span>
          <span class="end-badge inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-red-50 text-red-700">
            <span class="material-symbols-rounded text-sm">event</span>
            <span class="end-time">{{ $ujian->deadline ? \Carbon\Carbon::parse($ujian->deadline)->format('d M Y H:i') : '-' }}</span>
          </span>
        </div>
        <div class="flex items-start justify-between gap-3">
          <div class="inline-flex items-center gap-2 text-xs font-semibold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full w-fit">
            {{ $ujian->mataKuliah->mata_kuliah ?? '-' }}
          </div>
        </div>

        <div>
          <h3 class="font-semibold text-slate-800 pr-40">{{ $ujian->nama_ujian ?? 'Nama Ujian' }}</h3>
          <p class="text-sm text-slate-500 mt-1">{{ $ujian->deskripsi ?? '-' }}</p>
          <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-500">
            <span class="inline-flex items-center gap-2 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
              <span class="material-symbols-rounded text-sm">school</span>
              <span>Kelas {{ $ujian->kelas->nama_kelas ?? '-' }}</span>
              <span class="text-blue-600 font-semibold">Ujian ke: {{ $ujian->ujian_ke ?? 1 }}</span>
            </span>
          </div>
        </div>
        <div class="absolute bottom-4 right-4 flex items-center gap-2">
          <span class="countdown-start hidden text-xs font-semibold px-2 py-1 rounded-md bg-slate-100 text-slate-600"></span>
          <button
            type="button"
            class="btn-start-ujian hidden inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            data-start-url="{{ route('mahasiswa.ujian.soal', $ujian->id) }}"
          >
            <span class="material-symbols-rounded text-base">play_arrow</span>
            Mulai Ujian
          </button>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- MODAL KONFIRMASI MULAI UJIAN -->
<div id="confirmStartModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="w-full max-w-sm rounded-2xl bg-white shadow-xl p-6">
    <h3 class="text-lg font-semibold text-slate-800">Mulai Ujian</h3>
    <p class="text-sm text-slate-600 mt-2">Apakah Anda akan memulai ujian sekarang?</p>
    <div class="mt-6 flex items-center justify-end gap-2">
      <button type="button" id="btnCancelStart" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Batal</button>
      <a id="btnConfirmStart" href="#" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Mulai</a>
    </div>
  </div>
</div>

<script>
  const confirmStartModal = document.getElementById('confirmStartModal');
  const btnCancelStart = document.getElementById('btnCancelStart');
  const btnConfirmStart = document.getElementById('btnConfirmStart');

  document.querySelectorAll('.btn-start-ujian').forEach((btn) => {
    btn.addEventListener('click', () => {
      if (btn.hasAttribute('disabled')) return;
      const url = btn.dataset.startUrl || '#';
      if (btnConfirmStart) btnConfirmStart.href = url;
      confirmStartModal?.classList.remove('hidden');
      confirmStartModal?.classList.add('flex');
    });
  });

  btnCancelStart?.addEventListener('click', () => {
    confirmStartModal?.classList.add('hidden');
    confirmStartModal?.classList.remove('flex');
  });

  confirmStartModal?.addEventListener('click', (e) => {
    if (e.target === confirmStartModal) {
      confirmStartModal.classList.add('hidden');
      confirmStartModal.classList.remove('flex');
    }
  });
</script>

<script>
  const cards = document.querySelectorAll('.ujian-card');
  const ujianPage = document.getElementById('ujianPage');
  const serverNowMs = Number(ujianPage?.dataset?.serverNow || 0);
  const clientNowAtLoad = Date.now();
  const timeOffset = serverNowMs ? (clientNowAtLoad - serverNowMs) : 0;
  const formatCountdown = (ms) => {
    const totalSeconds = Math.max(0, Math.floor(ms / 1000));
    const h = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
    const m = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
    const s = String(totalSeconds % 60).padStart(2, '0');
    return `${h}:${m}:${s}`;
  };

  const updateStartCountdown = () => {
    const now = Date.now() - timeOffset;
    cards.forEach((card) => {
      const startMsRaw = card.dataset.startMs || '';
      const deadlineMsRaw = card.dataset.deadlineMs || '';
      const startMs = Number(startMsRaw);
      const deadlineMs = Number(deadlineMsRaw);
      const countdownEl = card.querySelector('.countdown-start');
      const btn = card.querySelector('.btn-start-ujian');
      const startTimeEl = card.querySelector('.start-time');
      const endTimeEl = card.querySelector('.end-time');
      const startBadge = card.querySelector('.start-badge');
      const endBadge = card.querySelector('.end-badge');
      if (!btn) return;
      btn.classList.add('hidden');
      btn.style.display = 'none';

      if (!startMs || Number.isNaN(startMs)) {
        if (countdownEl) countdownEl.classList.add('hidden');
        return;
      }
      const startAt = startMs;
      const deadlineAt = deadlineMs && !Number.isNaN(deadlineMs) ? deadlineMs : null;

      const diff = startAt - now;
      const windowMs = 30 * 60 * 1000;
      const isExpired = deadlineAt && !Number.isNaN(deadlineAt) && now > deadlineAt;

      if (isExpired) {
        if (countdownEl) countdownEl.classList.add('hidden');
        if (startTimeEl) {
          startTimeEl.textContent = startTimeEl.dataset.original || startTimeEl.textContent;
        }
        if (endTimeEl) {
          endTimeEl.textContent = 'Selesai';
        }
        if (startBadge) {
          startBadge.classList.remove('bg-emerald-100', 'text-emerald-800', 'bg-amber-100', 'text-amber-800');
          startBadge.classList.add('bg-amber-50', 'text-amber-700');
        }
        if (endBadge) {
          endBadge.classList.remove('bg-emerald-100', 'text-emerald-800', 'bg-red-100', 'text-red-800');
          endBadge.classList.add('bg-red-50', 'text-red-700');
        }
        return;
      }

      if (diff > windowMs) {
        btn?.classList.add('hidden');
        if (countdownEl) countdownEl.classList.add('hidden');
        if (startTimeEl) startTimeEl.textContent = startTimeEl.dataset.original || startTimeEl.textContent;
        if (endTimeEl) endTimeEl.textContent = endTimeEl.dataset.original || endTimeEl.textContent;
        if (startBadge) {
          startBadge.classList.remove('bg-amber-100', 'text-amber-800', 'bg-emerald-100', 'text-emerald-800');
          startBadge.classList.add('bg-amber-50', 'text-amber-700');
        }
        if (endBadge) {
          endBadge.classList.remove('bg-red-100', 'text-red-800', 'bg-emerald-100', 'text-emerald-800');
          endBadge.classList.add('bg-red-50', 'text-red-700');
        }
        return;
      }

      if (diff > 0) {
        btn?.classList.add('hidden');
        btn.style.display = 'none';
        if (countdownEl) countdownEl.classList.add('hidden');
        if (startTimeEl) {
          startTimeEl.dataset.original = startTimeEl.dataset.original || startTimeEl.textContent;
          startTimeEl.textContent = `Mulai dalam ${formatCountdown(diff)}`;
        }
        if (endTimeEl) {
          endTimeEl.dataset.original = endTimeEl.dataset.original || endTimeEl.textContent;
          endTimeEl.textContent = endTimeEl.dataset.original;
        }
        if (startBadge) {
          startBadge.classList.remove('bg-amber-50', 'text-amber-700');
          startBadge.classList.add('bg-amber-100', 'text-amber-800');
        }
        if (endBadge) {
          endBadge.classList.remove('bg-red-100', 'text-red-800');
          endBadge.classList.add('bg-red-50', 'text-red-700');
        }
        return;
      }

      btn?.classList.remove('hidden');
      btn.style.display = '';
      if (countdownEl) {
        countdownEl.classList.add('hidden');
      }
      if (startTimeEl) {
        startTimeEl.textContent = 'Sedang berlangsung';
      }
      if (endTimeEl && deadlineAt) {
        const diffEnd = Math.max(0, deadlineAt - now);
        endTimeEl.textContent = `Sisa ${formatCountdown(diffEnd)}`;
      }
      if (startBadge) {
        startBadge.classList.remove('bg-amber-50', 'text-amber-700', 'bg-amber-100', 'text-amber-800');
        startBadge.classList.add('bg-emerald-100', 'text-emerald-800');
      }
      if (endBadge) {
        endBadge.classList.remove('bg-red-100', 'text-red-800', 'bg-emerald-100', 'text-emerald-800');
        endBadge.classList.add('bg-red-50', 'text-red-700');
      }
    });
  };

  updateStartCountdown();
  setInterval(updateStartCountdown, 1000);
</script>

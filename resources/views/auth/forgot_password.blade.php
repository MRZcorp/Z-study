<x-header>Forgot Password</x-header>

<div class="min-h-screen flex items-center justify-center p-6" style="background-color: rgba(119, 206, 243, 0.2);">
  <div class="relative w-[92%] md:w-full max-w-md rounded-3xl bg-white/55 backdrop-blur-xl border border-white/60 shadow-2xl p-8">
    <div class="flex flex-col items-center text-center mb-4">
      <div class="h-14 w-14 mb-3 rounded-2xl bg-gradient-to-br from-[#2563EB] to-[#7C3AED] flex items-center justify-center text-white">
        <span class="material-symbols-rounded">mail</span>
      </div>
      <h1 class="text-xl font-semibold text-slate-900">Lupa Password</h1>
      <p class="text-sm text-slate-500">Masukkan username / NIM / NIDN / email untuk menerima tautan reset.</p>
    </div>

    @if (session('status'))
      <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 text-sm text-emerald-700">
        <div>{{ session('status') }}</div>
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-700">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
      @csrf
      <input
        type="text"
        name="identity"
        value="{{ old('identity') }}"
        placeholder="Username / NIM / NIDN / Email"
        class="w-full border-b border-slate-300 bg-transparent
               px-2 py-3 text-slate-900 placeholder-slate-500
               focus:border-[#2563EB] focus:border-b-2 focus:outline-none"
      />

      <button
        type="submit"
        class="mt-2 w-full rounded-full
               bg-gradient-to-r from-[#2563EB] to-[#7C3AED]
               px-6 py-3 text-sm font-semibold text-white
               transition hover:-translate-y-1 hover:shadow-xl"
      >
        Kirim Tautan
      </button>
    </form>

    <div class="mt-6 border-t pt-5">
      <h3 class="text-sm font-semibold text-slate-700 mb-3">Masukkan Kode Reset</h3>
      <form action="{{ route('password.verify') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', session('reset_email')) }}" />
        <input
          type="text"
          name="token"
          value="{{ old('token') }}"
          placeholder="Kode Reset (6 digit)"
          class="w-full border-b border-slate-300 bg-transparent
                 px-2 py-3 text-slate-900 placeholder-slate-500
                 focus:border-[#2563EB] focus:border-b-2 focus:outline-none"
        />
        <button
          type="submit"
          class="w-full rounded-full
                 bg-slate-900 px-6 py-3 text-sm font-semibold text-white
                 transition hover:-translate-y-1 hover:shadow-xl"
        >
          Verifikasi Token
        </button>
      </form>
    </div>
  </div>
</div>

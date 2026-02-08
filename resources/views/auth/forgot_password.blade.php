<x-header>Forgot Password</x-header>

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
  <div class="w-full max-w-lg rounded-3xl bg-white/80 backdrop-blur-md border border-white/50 shadow-2xl p-8">
    <div class="flex items-center gap-3 mb-6">
      <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white">
        <span class="material-symbols-rounded">mail</span>
      </div>
      <div>
        <h1 class="text-2xl font-semibold text-slate-800">Lupa Password</h1>
        <p class="text-sm text-slate-500">Masukkan username / NIM / NIDN / email untuk menerima tautan reset.</p>
      </div>
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
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-2">Username / NIM / NIDN / Email</label>
        <input
          type="text"
          name="identity"
          value="{{ old('identity') }}"
          placeholder="zstudy@kampus.ac.id"
          class="w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
        />
      </div>

      <div class="flex items-center justify-between">
        <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-slate-700">Kembali ke Login</a>
        <button
          type="submit"
          class="rounded-full bg-gradient-to-r from-indigo-700 to-purple-700 px-6 py-2.5 text-sm font-semibold text-white shadow hover:-translate-y-0.5 hover:shadow-lg"
        >
          Kirim Tautan
        </button>
      </div>
    </form>

    <div class="mt-6 border-t pt-5">
      <h3 class="text-sm font-semibold text-slate-700 mb-3">Masukkan Kode Reset</h3>
      <form action="{{ route('password.verify') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', session('reset_email')) }}" />
        <div>
          <label class="block text-sm font-medium text-slate-600 mb-2">Kode Reset (6 digit)</label>
          <input
            type="text"
            name="token"
            value="{{ old('token') }}"
            placeholder="123456"
            class="w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
          />
        </div>
        <div class="flex justify-end">
          <button
            type="submit"
            class="rounded-full bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white shadow hover:-translate-y-0.5 hover:shadow-lg"
          >
            Verifikasi Token
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

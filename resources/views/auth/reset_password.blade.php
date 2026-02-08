<x-header>Reset Password</x-header>

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
  <div class="w-full max-w-lg rounded-3xl bg-white/80 backdrop-blur-md border border-white/50 shadow-2xl p-8">
    <div class="flex items-center gap-3 mb-6">
      <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white">
        <span class="material-symbols-rounded">lock_reset</span>
      </div>
      <div>
        <h1 class="text-2xl font-semibold text-slate-800">Reset Password</h1>
        <p class="text-sm text-slate-500">Masukkan email dan password baru.</p>
      </div>
    </div>

    @if ($errors->any())
      <div class="mb-4 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-700">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
      @csrf
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-2">Email</label>
        <input
          type="email"
          name="email"
          value="{{ old('email', $email ?? '') }}"
          readonly
          placeholder="contoh: zstudy@kampus.ac.id"
          class="w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
        />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-600 mb-2">Password Baru</label>
          <div class="relative">
            <input
              id="passwordInput"
              type="password"
              name="password"
              placeholder="Password baru"
              class="w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 pr-12 text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
            />
            <button
              type="button"
              id="togglePassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700"
              aria-label="Tampilkan password"
            >
              <span class="material-symbols-rounded">visibility</span>
            </button>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-600 mb-2">Konfirmasi Password</label>
          <div class="relative">
            <input
              id="passwordConfirmInput"
              type="password"
              name="password_confirmation"
              placeholder="Ulangi password"
              class="w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 pr-12 text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
            />
            <button
              type="button"
              id="togglePasswordConfirm"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700"
              aria-label="Tampilkan konfirmasi password"
            >
              <span class="material-symbols-rounded">visibility</span>
            </button>
          </div>
        </div>
      </div>

      <div class="flex items-center justify-between">
        <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-slate-700">Kembali ke Login</a>
        <button
          type="submit"
          class="rounded-full bg-gradient-to-r from-indigo-700 to-purple-700 px-6 py-2.5 text-sm font-semibold text-white shadow hover:-translate-y-0.5 hover:shadow-lg"
        >
          Reset Password
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  const passwordInput = document.getElementById('passwordInput');
  const togglePassword = document.getElementById('togglePassword');
  const passwordConfirmInput = document.getElementById('passwordConfirmInput');
  const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');

  const toggleVisibility = (input, button) => {
    if (!input || !button) return;
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    const icon = button.querySelector('.material-symbols-rounded');
    if (icon) {
      icon.textContent = isHidden ? 'visibility_off' : 'visibility';
    }
  };

  togglePassword?.addEventListener('click', () => toggleVisibility(passwordInput, togglePassword));
  togglePasswordConfirm?.addEventListener('click', () => toggleVisibility(passwordConfirmInput, togglePasswordConfirm));
</script>

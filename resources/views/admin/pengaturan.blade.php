<x-header></x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>


<div class="p-6 bg-gray-100 min-h-screen">

    <!-- TITLE -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Pengaturan Akun</h1>
      <p class="text-sm text-gray-500">Kelola informasi akun dan keamanan</p>
    </div>
  
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  
      <!-- LEFT: PROFILE CARD -->
      <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex flex-col items-center text-center">
          <img
            id="adminFotoPreview"
            src="{{ $foto ? asset('storage/' . $foto) : asset('img/default_profil.jpg') }}"
            class="w-28 h-28 rounded-full object-cover border-4 border-blue-600 cursor-pointer hover:opacity-90 transition"
            alt="Profile"
            title="Klik untuk ganti foto"
          >
          <h3 class="mt-4 text-lg font-semibold">{{ $nama ?? ($user->name ?? 'Admin') }}</h3>
          <p class="text-sm text-gray-500">{{ $role ?? ($user->role->nama_role ?? 'Admin') }}</p>
  
          <form
            action="{{ route('admin.pengaturan.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="mt-4"
          >
            @csrf
            @method('PUT')
            <input
              id="adminFotoInput"
              type="file"
              name="foto"
              accept=".jpg,.jpeg,.png"
              class="hidden"
            >
          </form>
        </div>
  
        <div class="mt-6 border-t pt-4 text-sm text-gray-600 space-y-1">
          <p><span class="font-medium">ID:</span> {{ $id_user ?? '-' }}</p>
          <p><span class="font-medium">Email:</span> {{ $email ?? '-' }}</p>
          <p><span class="font-medium">Role:</span> {{ $role ?? '-' }}</p>
          <p><span class="font-medium">Status:</span> {{ $dosen->status ?? ($user->status ?? '-') }}</p>
        </div>
      </div>
  
      <!-- RIGHT: FORM -->
      <div class="lg:col-span-2 space-y-6">
  
        <!-- DATA AKUN -->
        <div class="bg-white rounded-2xl shadow p-6">
          <div>
            <h2 class="text-lg font-semibold text-gray-800">
              Informasi Akun
            </h2>
            <p class="text-sm text-gray-500">
              Data utama akun admin.
            </p>
          </div>
  
          <form id="infoForm" action="{{ route('admin.pengaturan.update.info') }}" method="POST" class="mt-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input type="text"
                       id="infoNama"
                       name="name"
                       class="w-full px-3 py-2 rounded-lg border border-transparent
                              focus:ring-blue-500 focus:border-blue-500 bg-gray-50 pointer-events-none"
                       value="{{ $nama ?? '' }}"
                       readonly>
              </div>
  
              <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email"
                       id="infoEmail"
                       name="email"
                       class="w-full px-3 py-2 rounded-lg border border-transparent
                              focus:ring-blue-500 focus:border-blue-500 bg-gray-50 pointer-events-none"
                       value="{{ $email ?? '' }}"
                       readonly>
              </div>
  
              <div>
                <label class="block text-sm font-medium mb-1">ID</label>
                <input type="text"
                       id="infoId"
                       class="w-full px-3 py-2 rounded-lg border border-transparent
                              focus:ring-blue-500 focus:border-blue-500 bg-gray-50 pointer-events-none"
                       value="{{ $dosen->nidn ?? '-' }}"
                       readonly>
              </div>
  
              <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text"
                       id="infoUsername"
                       name="username"
                       class="w-full px-3 py-2 rounded-lg border border-transparent
                              focus:ring-blue-500 focus:border-blue-500 bg-gray-50 pointer-events-none"
                       value="{{ $user->username ?? '-' }}"
                       readonly>
              </div>
  
              <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <input type="text"
                       class="w-full px-3 py-2 rounded-lg border border-transparent
                              focus:ring-blue-500 focus:border-blue-500 bg-gray-50 pointer-events-none"
                       value="{{ $role ?? '' }}"
                       readonly>
              </div>
            </div>
  
            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
              <button
                id="toggleInfoCard"
                class="px-5 py-2 text-sm font-semibold rounded-lg
                       bg-blue-600 text-white hover:bg-blue-700">
                Edit
              </button>
  
              <button
                id="infoCancel"
                class="px-5 py-2 text-sm font-semibold rounded-lg
                       text-gray-600 hover:bg-gray-100 hidden">
                Batal
              </button>
  
              <button
                id="infoSave"
                class="px-5 py-2 text-sm font-semibold rounded-lg
                       bg-blue-600 text-white hover:bg-blue-700 hidden">
                Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
  
        <!-- KEAMANAN -->
        <div class="bg-white rounded-2xl shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-gray-800">
                Keamanan
              </h2>
              <p class="text-sm text-gray-500">
                Perbarui password akun.
              </p>
            </div>
            <button
              id="toggleSecurityCard"
              class="px-4 py-2 text-sm font-semibold rounded-full
                     bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
              Ubah Password
            </button>
          </div>
  
          <form id="securityForm" action="{{ route('admin.pengaturan.update.password') }}" method="POST" class="hidden mt-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Password Baru</label>
                <input type="password"
                       name="password"
                       class="w-full px-3 py-2 rounded-lg border border-transparent
                              focus:ring-blue-500 focus:border-blue-500 bg-gray-50 pointer-events-none"
                       readonly>
              </div>
  
              <div>
                <label class="block text-sm font-medium mb-1">
                  Konfirmasi Password
                </label>
                <input type="password"
                       name="password_confirmation"
                       class="w-full px-3 py-2 rounded-lg border border-transparent
                              focus:ring-blue-500 focus:border-blue-500 bg-gray-50 pointer-events-none"
                       readonly>
              </div>
            </div>
  
            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
              <button
                id="securityCancel"
                class="px-5 py-2 text-sm font-semibold rounded-lg
                       text-gray-600 hover:bg-gray-100">
                Batal
              </button>
  
              <button
                id="securitySave"
                class="px-5 py-2 text-sm font-semibold rounded-lg
                       bg-blue-600 text-white hover:bg-blue-700">
                Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
  
      </div>
    </div>
  </div>

  <script>
    const adminFotoPreview = document.getElementById('adminFotoPreview');
    const adminFotoInput = document.getElementById('adminFotoInput');

    if (adminFotoPreview && adminFotoInput) {
      adminFotoPreview.addEventListener('click', () => adminFotoInput.click());
      adminFotoInput.addEventListener('change', () => {
        if (adminFotoInput.files && adminFotoInput.files.length > 0) {
          adminFotoInput.closest('form').submit();
        }
      });
    }

    const toggleInfoCard = document.getElementById('toggleInfoCard');
    const infoCancel = document.getElementById('infoCancel');
    const infoSave = document.getElementById('infoSave');
    const infoFields = [
      document.getElementById('infoNama'),
      document.getElementById('infoEmail'),
      document.getElementById('infoId'),
      document.getElementById('infoUsername'),
    ].filter(Boolean);
    const toggleSecurityCard = document.getElementById('toggleSecurityCard');
    const securityForm = document.getElementById('securityForm');
    const securityCancel = document.getElementById('securityCancel');
    const securitySave = document.getElementById('securitySave');
    const securityFields = securityForm
      ? Array.from(securityForm.querySelectorAll('input[type="password"]'))
      : [];

    if (toggleInfoCard && infoCancel && infoSave && infoFields.length) {
      const setEditMode = (isEdit) => {
        infoFields.forEach((input) => {
          input.readOnly = !isEdit;
          input.classList.toggle('bg-gray-50', !isEdit);
          input.classList.toggle('bg-white', isEdit);
          input.classList.toggle('border-gray-300', isEdit);
          input.classList.toggle('border-transparent', !isEdit);
          input.classList.toggle('pointer-events-none', !isEdit);
        });
        toggleInfoCard.classList.toggle('hidden', isEdit);
        infoCancel.classList.toggle('hidden', !isEdit);
        infoSave.classList.toggle('hidden', !isEdit);
      };

      setEditMode(false);

      toggleInfoCard.addEventListener('click', (event) => {
        event.preventDefault();
        setEditMode(true);
      });
      infoCancel.addEventListener('click', (event) => {
        event.preventDefault();
        setEditMode(false);
      });
    }

    if (toggleSecurityCard && securityForm && securityCancel && securitySave) {
      const setSecurityMode = (isEdit) => {
        securityForm.classList.toggle('hidden', !isEdit);
        securityFields.forEach((input) => {
          input.readOnly = !isEdit;
          input.classList.toggle('bg-gray-50', !isEdit);
          input.classList.toggle('bg-white', isEdit);
          input.classList.toggle('border-gray-300', isEdit);
          input.classList.toggle('border-transparent', !isEdit);
          input.classList.toggle('pointer-events-none', !isEdit);
        });
        toggleSecurityCard.textContent = isEdit ? 'Tutup' : 'Ubah Password';
      };

      setSecurityMode(false);

      toggleSecurityCard.addEventListener('click', (event) => {
        event.preventDefault();
        setSecurityMode(securityForm.classList.contains('hidden'));
      });

      securityCancel.addEventListener('click', (event) => {
        event.preventDefault();
        setSecurityMode(false);
      });
    }
  </script>

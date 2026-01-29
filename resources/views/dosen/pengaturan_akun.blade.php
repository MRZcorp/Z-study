<x-header></x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>


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
            src="/img/zaky.jpeg"
            class="w-28 h-28 rounded-full object-cover border-4 border-blue-600"
            alt="Profile"
          >
          <h3 class="mt-4 text-lg font-semibold">M. Zaky Nugraha A R</h3>
          <p class="text-sm text-gray-500">Mahasiswa</p>
  
          <button
            class="mt-4 px-4 py-2 text-sm font-semibold rounded-full
                   bg-blue-600 text-white hover:bg-blue-700 transition">
            Ganti Foto
          </button>
        </div>
  
        <div class="mt-6 border-t pt-4 text-sm text-gray-600 space-y-1">
          <p><span class="font-medium">NIM:</span> 210123456</p>
          <p><span class="font-medium">Fakultas:</span> Teknik</p>
          <p><span class="font-medium">Jurusan:</span> Informatika</p>
          <p><span class="font-medium">IPK:</span> 3.75</p>
        </div>
      </div>
  
      <!-- RIGHT: FORM -->
      <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6 space-y-6">
  
        <!-- DATA AKUN -->
        <div>
          <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Informasi Akun
          </h2>
  
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
              <input type="text"
                     class="w-full rounded-lg border-gray-300
                            focus:ring-blue-500 focus:border-blue-500"
                     value="M. Zaky Nugraha A R">
            </div>
  
            <div>
              <label class="block text-sm font-medium mb-1">Email</label>
              <input type="email"
                     class="w-full rounded-lg border-gray-300
                            focus:ring-blue-500 focus:border-blue-500"
                     value="zaky@kampus.ac.id">
            </div>
  
            <div>
              <label class="block text-sm font-medium mb-1">No HP</label>
              <input type="text"
                     class="w-full rounded-lg border-gray-300
                            focus:ring-blue-500 focus:border-blue-500"
                     placeholder="08xxxxxxxxxx">
            </div>
  
            <div>
              <label class="block text-sm font-medium mb-1">Username</label>
              <input type="text"
                     class="w-full rounded-lg border-gray-300
                            focus:ring-blue-500 focus:border-blue-500"
                     value="zaky">
            </div>
          </div>
        </div>
  
        <!-- KEAMANAN -->
        <div>
          <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Keamanan
          </h2>
  
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Password Baru</label>
              <input type="password"
                     class="w-full rounded-lg border-gray-300
                            focus:ring-blue-500 focus:border-blue-500">
            </div>
  
            <div>
              <label class="block text-sm font-medium mb-1">
                Konfirmasi Password
              </label>
              <input type="password"
                     class="w-full rounded-lg border-gray-300
                            focus:ring-blue-500 focus:border-blue-500">
            </div>
          </div>
        </div>
  
        <!-- ACTION -->
        <div class="flex justify-end gap-3 pt-4 border-t">
          <button
            class="px-5 py-2 text-sm font-semibold rounded-lg
                   text-gray-600 hover:bg-gray-100">
            Batal
          </button>
  
          <button
            class="px-5 py-2 text-sm font-semibold rounded-lg
                   bg-blue-600 text-white hover:bg-blue-700">
            Simpan Perubahan
          </button>
        </div>
  
      </div>
    </div>
  </div>
  
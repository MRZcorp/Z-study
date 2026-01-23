<x-header></x-header>



<body class="bg-black">
  <div class="container mx-auto px-4 py-12 max-w-3xl">
    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-10">
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Buat Akun</h2>
        <p class="text-gray-500 mt-2">Silahkan isi Data berikut untuk membuat akun</p>
      </div>

      <form>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- First Name -->
          <div>
            <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Nama Depan</label>
            <input type="text" id="firstName" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out" placeholder="Rajesh">
          </div>

          <!-- Last Name -->
          <div>
            <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Nama Belakang</label>
            <input type="text" id="lastName" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out" placeholder="Maheshwari">
          </div>

          <!-- Email -->
          <div class="md:col-span-2">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
            <input type="email" id="email" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out" placeholder="nama@email.com">
          </div>

          <!-- Phone Number -->
          <div class="md:col-span-2">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomer HP</label>
            <input type="tel" id="phone" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out" placeholder="+62 81234567890">
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" id="password" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out" placeholder="••••••••">
          </div>

          <!-- Confirm Password -->
          <div>
            <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Komfirmasi Password</label>
            <input type="password" id="confirmPassword" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out" placeholder="••••••••">
          </div>

          <!-- Date of Birth -->
          <div>
            <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Tanggal lahir</label>
            <input type="date" id="dob" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out">
          </div>

          <!-- Country -->
          <div>
            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
            <select id="country" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out">
              <option value="" selected disabled>Select your country</option>
              <option value="us">United States</option>
              <option value="ca">Canada</option>
              <option value="uk">United Kingdom</option>
              <option value="au">Australia</option>
            </select>
          </div>

 <!-- Country -->
 <div>
  <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
  <select id="country" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out">
    <option value="" selected disabled>Select your country</option>
    <option value="us">United States</option>
    <option value="ca">Canada</option>
    <option value="uk">United Kingdom</option>
    <option value="au">Australia</option>
  </select>
</div>

<div>
  <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
  <select id="country" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out">
    <option value="" selected disabled>Select your country</option>
    <option value="us">United States</option>
    <option value="ca">Canada</option>
    <option value="uk">United Kingdom</option>
    <option value="au">Australia</option>
  </select>
</div>

<div>
  <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
  <select id="country" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out">
    <option value="" selected disabled>Pilih Provinsi</option>
    <option value="us">United States</option>
    <option value="ca">Canada</option>
    <option value="uk">United Kingdom</option>
    <option value="au">Australia</option>
  </select>
</div>

<div>
  <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Kabupaten</label>
  <select id="country" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out">
    <option value="" selected disabled>pilih Kabupaten</option>
    <option value="us">United States</option>
    <option value="ca">Canada</option>
    <option value="uk">United Kingdom</option>
    <option value="au">Australia</option>
  </select>
</div>


<div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-md">
  <h2 class="text-xl font-bold mb-4 text-gray-800">
    Form Wilayah
  </h2>

  <!-- Provinsi -->
  <div class="mb-4">
    <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">
      Provinsi
    </label>
    <select
      id="provinsi"
      class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    >
      <option value="">-- Pilih Provinsi --</option>
    </select>
  </div>

  <!-- Kabupaten -->
  <div class="mb-4">
    <label for="kabupaten" class="block text-sm font-medium text-gray-700 mb-1">
      Kabupaten / Kota
    </label>
    <select
      id="kabupaten"
      disabled
      class="w-full rounded-lg border border-gray-300 p-2 bg-gray-100 text-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    >
      <option value="">-- Pilih Kabupaten / Kota --</option>
    </select>
  </div>

  <button
    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
    onclick="submitForm()"
  >
    Simpan
  </button>
</div>

<script>
  const provinsiSelect = document.getElementById("provinsi");
  const kabupatenSelect = document.getElementById("kabupaten");

  // Load Provinsi
  fetch("https://api.datawilayah.com/api/provinsi.json")
    .then(res => res.json())
    .then(data => {
      data.forEach(prov => {
        const option = document.createElement("option");
        option.value = prov.id;
        option.textContent = prov.nama;
        provinsiSelect.appendChild(option);
      });
    })
    .catch(() => {
      alert("Gagal memuat data provinsi");
    });

  // Load Kabupaten berdasarkan Provinsi
  provinsiSelect.addEventListener("change", function () {
    const provinsiId = this.value;

    kabupatenSelect.innerHTML =
      "<option value=''>-- Pilih Kabupaten / Kota --</option>";
    kabupatenSelect.disabled = true;
    kabupatenSelect.classList.add("bg-gray-100", "text-gray-500");

    if (!provinsiId) return;

    fetch(`https://api.datawilayah.com/api/kabupaten_kota/${provinsiId}.json`)
      .then(res => res.json())
      .then(data => {
        data.forEach(kab => {
          const option = document.createElement("option");
          option.value = kab.id;
          option.textContent = kab.nama;
          kabupatenSelect.appendChild(option);
        });

        kabupatenSelect.disabled = false;
        kabupatenSelect.classList.remove("bg-gray-100", "text-gray-500");
      })
      .catch(() => {
        alert("Gagal memuat data kabupaten");
      });
  });

  function submitForm() {
    const prov = provinsiSelect.options[provinsiSelect.selectedIndex].text;
    const kab = kabupatenSelect.options[kabupatenSelect.selectedIndex].text;

    if (!provinsiSelect.value || !kabupatenSelect.value) {
      alert("Silakan pilih provinsi dan kabupaten terlebih dahulu!");
      return;
    }

    alert(`Data tersimpan:\nProvinsi: ${prov}\nKabupaten: ${kab}`);
  }
</script>







          <!-- Address -->
          <div class="md:col-span-2">
            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <textarea id="address" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300  transition duration-150 ease-in-out" placeholder="Enter your full address"></textarea>
          </div>

          

          <!-- Terms and Conditions -->
          <div class="md:col-span-2 mt-2">
            <div class="flex items-start">
              <div class="flex items-center h-5">
                <input id="terms" type="checkbox" class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
              </div>
              <div class="ml-3 text-sm">
                <label for="terms" class="font-medium text-gray-700">Menyetujui <a href="#" class="text-indigo-600 hover:text-indigo-500">Syarat dan Ketentuan</a> serta <a href="#" class="text-indigo-600 hover:text-indigo-500">Kebijakan Privasi</a></label>
              </div>
            </div>
          </div>
        </div>
         
        <!-- Submit Button -->
        <div class="mt-8">
          <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-150 ease-in-out shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Buat Akun
          </button>
        </div>

        <!-- Sign In Link -->
        <div class="text-center mt-6">
          <p class="text-sm text-gray-600">
            Sudah Punya Akun? <a href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">Masuk</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</body>

</html>
<x-header>Materi Pembelajaran</x-header>
<x-navbar></x-navbar>
<x-sidebar>dosen</x-sidebar>

<form 
    action="{{ url('/dosen/materi') }}" 
    method="POST" 
    enctype="multipart/form-data"
    class="max-w-4xl mx-auto p-6"
>
    @csrf

    <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">

        <!-- Header -->
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">
                Upload Materi Perkuliahan
            </h2>
            <p class="text-sm text-slate-500">
                Tambahkan materi untuk mahasiswa
            </p>
        </div>

        <!-- Judul & Mata Kuliah -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Judul Materi
                </label>
                <input 
                    type="text" 
                    name="judul_materi"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2
                           focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Contoh: Pengenalan Laravel"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Mata Kuliah
                </label>
                <input 
                    type="text" 
                    name="matkul"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2
                           focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Contoh: Pemrograman Web"
                    required
                >
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Deskripsi Materi
            </label>
            <textarea 
                name="deskripsi"
                rows="4"
                class="w-full rounded-lg border border-slate-300 px-4 py-2
                       focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Tuliskan ringkasan materi..."
                required
            ></textarea>
        </div>

        <!-- Upload File -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                File Materi
            </label>

            <label class="flex items-center gap-3 w-full rounded-xl border border-dashed 
                          border-slate-300 px-4 py-6 cursor-pointer
                          hover:bg-slate-50 transition">

                <span class="material-symbols-rounded text-blue-600 text-3xl">
                    upload_file
                </span>

                <div>
                    <p class="text-sm font-medium text-slate-700">
                        Klik untuk upload file
                    </p>
                    <p class="text-xs text-slate-500">
                        PDF, PPT, DOC, atau Video
                    </p>
                </div>

                <input 
                    type="file" 
                    name="file_materi" 
                    class="hidden"
                    required
                >
            </label>
        </div>

        <!-- Button -->
        <div class="flex justify-end gap-3 pt-4 border-t">
            <button 
                type="reset"
                class="px-4 py-2 rounded-lg border border-slate-300
                       text-slate-600 hover:bg-slate-100"
            >
                Reset
            </button>

            <button 
                type="submit"
                class="px-5 py-2 rounded-lg bg-gradient-to-r 
                       from-blue-500 to-indigo-600 text-white
                       font-medium hover:opacity-90"
            >
                Upload Materi
            </button>
        </div>

    </div>
</form>

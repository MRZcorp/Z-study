<x-header> </x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

    

  <!-- FILTER BAR -->
  <div class="flex items-center gap-3 mb-6">
      <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg border shadow-sm">
          <span class="material-symbols-rounded text-slate-500">filter_list</span>
          <select class="bg-transparent outline-none text-sm text-slate-700">
              <option>Semua Mata Kuliah</option>
              <option>Algoritma</option>
              <option>Struktur Data</option>
          </select>
      </div>
  </div>

  <!-- CARD LIST -->
  <div class="space-y-5">

      <!-- CARD PDF -->
      <div class="bg-white rounded-xl border p-5 flex justify-between items-start gap-4">
          <div class="flex gap-4">
              <div class="bg-red-100 text-red-600 p-2 rounded-lg h-fit">
                  <span class="material-symbols-rounded">description</span>
              </div>

              <div>
                  <h3 class="font-semibold text-slate-800">
                      Pengantar Algoritma dan Struktur Data
                  </h3>
                  <p class="text-sm text-slate-500 mt-1 max-w-xl">
                      Materi pengantar tentang konsep dasar algoritma,
                      kompleksitas waktu, dan struktur data fundamental.
                  </p>

                  <div class="flex items-center gap-4 mt-3 text-sm text-slate-500">
                      <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-md text-xs font-medium">
                          PDF
                      </span>
                      <span>15 Jan 2026</span>
                  </div>
              </div>
          </div>

          <button
              class="flex items-center gap-2 border px-4 py-2 rounded-lg text-sm hover:bg-slate-100 transition">
              <span class="material-symbols-rounded text-base">download</span>
              Download
          </button>
      </div>

      <!-- CARD VIDEO -->
      <div class="bg-white rounded-xl border p-5 flex justify-between items-start gap-4">
          <div class="flex gap-4">
              <div class="bg-purple-100 text-purple-600 p-2 rounded-lg h-fit">
                  <span class="material-symbols-rounded">videocam</span>
              </div>

              <div>
                  <h3 class="font-semibold text-slate-800">
                      Tutorial Big O Notation
                  </h3>
                  <p class="text-sm text-slate-500 mt-1 max-w-xl">
                      Video tutorial menjelaskan konsep Big O Notation
                      untuk analisis kompleksitas algoritma.
                  </p>

                  <div class="flex items-center gap-4 mt-3 text-sm text-slate-500">
                      <span class="bg-purple-50 text-purple-600 px-2 py-0.5 rounded-md text-xs font-medium">
                          Video
                      </span>
                      <span>14 Jan 2026</span>
                  </div>
              </div>
          </div>

          <button
              class="flex items-center gap-2 border px-4 py-2 rounded-lg text-sm hover:bg-slate-100 transition">
              <span class="material-symbols-rounded text-base">download</span>
              Download
          </button>
      </div>

  </div>
</main>

<x-header></x-header>

<style>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
<!-- TOP RIGHT TOGGLE -->
<div class="flex justify-between items-center ">

  <!-- LEFT BRAND -->
  <div class="flex items-center">
    <div class="h-20 w-20 flex items-center justify-center overflow-hidden mr-3">
      <img src="{{ asset('img/Logo_Zstudy.png') }}" class="h-15 w-15 object-contain" />
    </div>
    <div>
      <p class="font-medium text-gray-900">Z-Study</p>
      <p class="text-sm text-gray-500">Online Learning System</p>
    </div>
  </div>

  <!-- RIGHT BUTTON -->
  <button
    onclick="toggleLogin()"
    class="rounded-full bg-gradient-to-r from-indigo-500 to-purple-500
           px-6 py-3 mr-3 text-sm font-semibold text-white
           transition hover:-translate-y-1 hover:shadow-xl"
  >
    Login
  </button>

</div>



<!-- Main Conten -->
<div class="w-full bg-gray-100 flex justify-center pt-6 pb-7">

  <!-- FRAME -->
  <div class="relative w-full max-w-6xl h-[550px] overflow-hidden rounded-2xl shadow-xl bg-black">

    <!-- PROGRESS BAR -->
    <div class="absolute top-3 left-1/2 -translate-x-1/2 w-[80%] h-1 bg-white/30 rounded-full z-30">
      <div
        id="progressBar"
        class="h-full w-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-300"
      ></div>
    </div>

    <!-- TEXT OVERLAY -->
    <div
      id="overlay"
      class="absolute z-20 max-w-md
            bg-white/30 backdrop-blur-md
             rounded-xl p-6
             transition-all duration-700 ease-out
         transform-gpu"
    >
      <h1
        id="overlayTitle"
        class="text-3xl font-bold text-yellow-500 mb-3"
      >
         Z-Study
      </h1>
      <p
        id="overlayText"
        class="text-gray-800 leading-relaxed"
      >
      Z-Study menggabungkan inovasi teknologi dan pembelajaran
        fleksibel untuk mendorong pertumbuhan intelektual mahasiswa.
      </p>
    </div>

    <!-- SLIDER -->
    <div
      id="slider"
      class="flex h-full overflow-x-scroll scroll-smooth snap-x snap-mandatory no-scrollbar"
    >

      <!-- SLIDE 1 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="Z-Study"
        data-text="Z-Study menggabungkan inovasi teknologi dan pembelajaran fleksibel untuk 
        mendorong pertumbuhan intelektual mahasiswa."
        data-x="left-10"
        data-y="top-[60%]"
      
        >
        <img src="/img/bg1.jpg" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 2 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="FASILITAS MODERN"
        data-text="Lingkungan belajar modern dengan fasilitas lengkap untuk mendukung 
        kreativitas dan kolaborasi mahasiswa."
        data-x="left-10"
        data-y="top-10"
      >
        <img src="/img/bg2.jpg" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 3 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="KAMPUS HIJAU"
        data-text="Ruang terbuka hijau yang nyaman dan asri untuk menciptakan suasana 
        belajar yang seimbang dan produktif."
        data-x="left-[58%]"
        data-y="top-[60%]"
      >
        <img src="/img/bg3.jpg" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 4 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="KAMPUS HIJAU"
        data-text="Ruang terbuka hijau yang nyaman dan asri untuk menciptakan suasana 
        belajar yang seimbang dan produktif."
        
        data-x="left-[58%]"
        data-y="top-10"
      >
        <img src="/img/bg3.jpg" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 5 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="KAMPUS HIJAU"
        data-text="Ruang terbuka hijau yang nyaman dan asri untuk menciptakan suasana 
        belajar yang seimbang dan produktif."
        
        data-x="left-10"
        data-y="top-10"
      >
        <img src="/img/bg3.jpg" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 6 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="KAMPUS HIJAU"
        data-text="Ruang terbuka hijau yang nyaman dan asri untuk menciptakan
         suasana belajar yang seimbang dan produktif."

         
        data-x="left-[58%]"
        data-y="top-[60%]"
      >
        <img src="/img/bg3.jpg" class="w-full h-full object-cover" />
      </div>

    </div>
  </div>
</div>


 <!-- LOGIN -->
<!-- OVERLAY + BLUR -->
<div
id="loginOverlay"
class="fixed inset-0 z-30 hidden
       items-center justify-center
       bg-black/40 backdrop-blur-sm">

<!-- LOGIN CARD -->
<div
  class="relative w-full max-w-md rounded-3xl
         bg-white/20 backdrop-blur-xl
         p-8
         shadow-[0_25px_50px_-15px_rgba(0,0,0,0.4)]
         border border-white/30">

  <!-- CLOSE BUTTON -->
  <button
    onclick="toggleLogin()"
    class="absolute right-4 top-4
           text-white/70 hover:text-white text-xl">
    ✕
  </button>

 
  <!-- TITLE -->
<div class="flex flex-col items-center text-center mb-2">

<!-- LOGO -->
<div class="h-20 w-20 mb-4 flex items-center justify-center overflow-hidden">
<img src="{{ asset('img/Logo_Zstudy.png') }}" class="h-20 w-20 object-contain" />
</div>


<!-- WELCOME TEXT -->
<h2 class="mb-1 text-2xl font-semibold text-white">
Hai, Selamat datang di
</h2>
<h2 class="mb-4 text-2xl font-bold text-white">
Z-Study! 👋
</h2>
</div>
<!--  TEXT -->
{{-- <p class="text-sm text-gray-900 mb-4">Sistem terintegrasi untuk pengelolaan data dan layanan dosen 
  serta mahasiswa. </p>
  <p>Hanya dapat diakses oleh pengguna terdaftar.</p> --}}



  <!-- FORM -->
  <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
    @csrf

    @if(session('error'))
      <div class="rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
      </div>
    @endif

    <!-- USERNAME -->
    <input
      type="text"
      name="username"
      placeholder="Username / ID"
      class="w-full border-b border-white/40 bg-transparent
             px-2 py-3 text-white placeholder-white/70
             focus:border-indigo-400 focus:outline-none"
    />

    <!-- PASSWORD -->
    <input
      type="password"
      name="password"
      placeholder="Password"
      class="w-full border-b border-white/40 bg-transparent
             px-2 py-3 text-white placeholder-white/70
             focus:border-indigo-400 focus:outline-none"
    />

    <!-- OPTIONS -->
    <div class="flex items-center justify-between text-sm text-white/80">
      <label class="flex items-center gap-2">
        <input type="checkbox" name="remember" class="rounded text-indigo-600">
        Ingat Saya
      </label>

      <a href="#" class="hover:text-white">
        Lupa Password?
      </a>
    </div>

    <!-- BUTTON -->
    <button
      type="submit"
      class="mt-4 w-full rounded-full
             bg-gradient-to-r from-indigo-700 to-purple-700
             px-6 py-3 text-sm font-semibold text-white
             transition hover:-translate-y-1 hover:shadow-xl">
      Masuk
    </button>
  </form>
</div>
</div>
</div>



<!-- SCRIPT -->
<script>
const slider = document.getElementById("slider")
const progressBar = document.getElementById("progressBar")
const overlayTitle = document.getElementById("overlayTitle")
const overlayText = document.getElementById("overlayText")
const slides = slider.children

let currentIndex = 0
let isScrolling = false
let loginOpened = false

function updateProgress() {
  const percent = ((currentIndex + 1) / slides.length) * 100
  progressBar.style.width = percent + "%"
}
function toggleLogin() {
    const overlay = document.getElementById('loginOverlay');
    overlay.classList.toggle('hidden');
    overlay.classList.toggle('flex');
    if (overlay.classList.contains('hidden')) {
    loginOpened = false
  }

  }

function updateOverlay() {
  const activeSlide = slides[currentIndex]
  const title = activeSlide.dataset.title
  const text = activeSlide.dataset.text

  // Animate OUT
  overlay.classList.add("opacity-0", "scale-95", "translate-y-4")

  setTimeout(() => {
    overlayTitle.textContent = title
    overlayText.textContent = text

    // Animate IN
    overlay.classList.remove("opacity-0", "scale-95", "translate-y-4")
  }, 300)
}

function goToSlide(index) {
  if (index < 0) index = 0
  if (index >= slides.length) index = slides.length - 1

  currentIndex = index

  const slideWidth = slider.clientWidth
  slider.scrollTo({
    left: slideWidth * currentIndex,
    behavior: "smooth"
  })

  updateProgress()
  updateOverlay()
  updateOverlayPosition()

  // 🔥 AUTO OPEN LOGIN JIKA SLIDE TERAKHIR
  if (currentIndex === slides.length - 1 && !loginOpened) {
    loginOpened = true
    setTimeout(() => {
      toggleLogin()
    }, 2000) // tunggu animasi slide selesai
  }
}
function updateOverlayPosition() {
  const activeSlide = slides[currentIndex]

  const x = activeSlide.dataset.x || "left-10"
  const y = activeSlide.dataset.y || "top-1/2"

  // Hanya posisi berbasis TOP (biar bisa dianimasikan)
  const positions = [
    "left-[58%]", "right-10",
    "top-10", "top-1/2",
    "top-3/4", "top-[60%]",
    "-translate-y-1/2"
  ]

  // Bersihkan semua dulu
  overlay.classList.remove(...positions)

  // Pasang posisi baru
  overlay.classList.add(x, y)

  // Auto center kalau pakai top-1/2
  if (y === "top-1/2") {
    overlay.classList.add("-translate-y-1/2")
  }
}

slider.addEventListener("wheel", (e) => {
  e.preventDefault()
  if (isScrolling) return

  isScrolling = true

  if (e.deltaY > 0) {
    goToSlide(currentIndex + 1)
  } else {
    goToSlide(currentIndex - 1)
  }

  setTimeout(() => {
    isScrolling = false
  }, 600)
}, { passive: false })

// INIT
updateProgress()
updateOverlay()
updateOverlayPosition()
</script>
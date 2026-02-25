<x-header></x-header>

<style>
:root {
  --primary: #2563EB;
  --secondary: #7C3AED;
  --accent: #38BDF8;
  --bg: #F8FAFC;
  --text-main: #0F172A;
}
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
@media (max-width: 768px) {
  #overlay {
    left: 1rem !important;
    right: 1rem !important;
    top: auto !important;
    bottom: 1.25rem !important;
    transform: translateY(0) !important;
  }
}
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
  -webkit-text-fill-color: #0F172A;
  caret-color: #0F172A;
  transition: background-color 5000s ease-in-out 0s;
  -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0) inset !important;
  box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0) inset !important;
  background-color: transparent !important;
  filter: none !important;
}
input:autofill {
  -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0) inset !important;
  box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0) inset !important;
  -webkit-text-fill-color: #0F172A;
  caret-color: #0F172A;
  background-color: transparent !important;
  filter: none !important;
}
input:-moz-autofill {
  box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0) inset !important;
  -webkit-text-fill-color: #0F172A;
  caret-color: #0F172A;
  background-color: transparent !important;
  filter: none !important;
}
</style>
<!-- TOP RIGHT TOGGLE -->
<div class="flex items-center justify-between gap-3">

  <!-- LEFT BRAND -->
  <div class="flex items-center">
    <div class="h-20 w-20 flex items-center justify-center overflow-hidden mr-3">
      <img src="{{ asset('img/Logo_Zstudy.png') }}" class="h-15 w-15 object-contain" />
    </div>
    <div>
      <p class="font-semibold text-slate-900">Z-Study</p>
      <p class="text-sm text-slate-500">Online Learning System</p>
    </div>
  </div>

  <!-- RIGHT BUTTON -->
  <button
    onclick="toggleLogin()"
    class="ml-auto shrink-0 rounded-full bg-gradient-to-r from-[#2563EB] to-[#7C3AED]
           px-6 py-3 md:mr-3 text-sm font-semibold text-white
           transition hover:-translate-y-1 hover:shadow-xl"
  >
    Login
  </button>

</div>



<!-- Main Conten -->
<div class="w-full min-h-screen flex justify-center pt-4 pb-6 md:pt-6 md:pb-7" style="background-color: rgba(119, 206, 243, 0.2);">

  <!-- FRAME -->
  <div class="relative w-full max-w-6xl h-[420px] md:h-[550px] overflow-hidden rounded-2xl shadow-xl bg-black">

    <!-- PROGRESS BAR -->
    <div class="absolute top-3 left-1/2 -translate-x-1/2 w-[90%] md:w-[80%] h-1 bg-white/40 rounded-full z-30">
      <div
        id="progressBar"
        class="h-full w-0 rounded-full transition-all duration-300"
        style="background-color: var(--accent);"
      ></div>
    </div>

    <!-- CHEVRON BUTTONS -->
    <button
      type="button"
      id="prevSlide"
      class="absolute left-3 md:left-5 top-1/2 -translate-y-1/2 z-30
             h-11 w-11 rounded-full bg-white/10 backdrop-blur
             flex items-center justify-center text-slate-700
             shadow hover:bg-white/90 transition"
      aria-label="Slide sebelumnya"
    >
      <span class="material-symbols-rounded text-2xl">chevron_left</span>
    </button>
    <button
      type="button"
      id="nextSlide"
      class="absolute right-3 md:right-5 top-1/2 -translate-y-1/2 z-30
             h-11 w-11 rounded-full bg-white/10 backdrop-blur
             flex items-center justify-center text-slate-700
             shadow hover:bg-white/90 transition"
      aria-label="Slide berikutnya"
    >
      <span class="material-symbols-rounded text-2xl">chevron_right</span>
    </button>

    <!-- TEXT OVERLAY -->
    <div
      id="overlay"
      class="absolute z-20 max-w-[90%] md:max-w-md
            bg-white/80 backdrop-blur-md
             rounded-xl p-4 md:p-6 border border-white/60
             transition-all duration-700 ease-out
         transform-gpu pointer-events-none"
    >
      <h1
        id="overlayTitle"
        class="text-2xl md:text-3xl font-bold mb-2 md:mb-3"
        style="color: var(--primary);"
      >
         Z-Study
      </h1>
      <p
        id="overlayText"
        class="text-xs md:text-sm font-semibold leading-relaxed text-slate-700"
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
        data-text="Z-Study merupakan platform online learning system yang mengintegrasikan proses pembelajaran, evaluasi, dan manajemen akademik dalam satu sistem terpusat sehingga memudahkan mahasiswa dan dosen."
        data-x="left-10"
        data-y="top-[60%]"
      
        >
        <img src="/img/sld1.png" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 2 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="Akses Fleksibel Kapan Saja"
        data-text="Z-Study dapat diakses kapan saja dan di mana saja melalui perangkat berbasis web, sehingga mendukung pembelajaran mandiri tanpa terikat ruang dan waktu."
        data-x="left-10"
        data-y="top-10"
      >
        <img src="/img/sld11.png" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 3 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="Mendukung Proses Akademik Kampus"
        data-text="Sistem ini dirancang sesuai kebutuhan akademik dengan dukungan login menggunakan NIM, NIDN, atau Email, sehingga aman dan relevan untuk lingkungan perguruan tinggi."
        data-x="left-[58%]"
        data-y="top-[60%]"
      >
        <img src="/img/sld2.png" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 4 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="Efisiensi Pengelolaan Pembelajaran"
        data-text="Dengan Z-Study, proses pengelolaan materi, tugas, dan evaluasi pembelajaran menjadi lebih efisien karena seluruh data tersimpan dan dikelola secara digital."
        
        data-x="left-10"
        data-y="top-[60%]"
      >
        <img src="/img/sld22.png" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 5 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="Meningkatkan Kualitas Pembelajaran"
        data-text="Z-Study mendorong peningkatan kualitas pembelajaran melalui pemanfaatan teknologi informasi yang mendukung interaksi, kemandirian belajar, dan perkembangan intelektual mahasiswa."
        
        data-x="left-10"
        data-y="top-10"
      >
        <img src="/img/sld3.png" class="w-full h-full object-cover" />
      </div>

      <!-- SLIDE 6 -->
      <div
        class="min-w-full h-full snap-center"
        data-title="Antarmuka Modern dan Mudah Digunakan"
        data-text="Z-Study memiliki tampilan antarmuka yang modern, responsif, dan ramah pengguna (user friendly), sehingga memudahkan pengguna dalam memahami dan menggunakan fitur sistem."

         
        data-x="left-[58%]"
        data-y="top-[60%]"
      >
        <img src="/img/sld33.png" class="w-full h-full object-cover" />
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
  class="relative w-[92%] md:w-full max-w-md rounded-3xl
         bg-white/55 backdrop-blur-xl
         p-8
         shadow-[0_25px_50px_-15px_rgba(0,0,0,0.4)]
         border border-white/60">

  <!-- CLOSE BUTTON -->
  <button
    onclick="toggleLogin()"
    class="absolute right-4 top-4 h-10 w-10 rounded-full
           flex items-center justify-center
           text-slate-600 hover:text-slate-800 hover:bg-slate-100/70 text-xl">
    ✕
  </button>

 
  <!-- TITLE -->
<div class="flex flex-col items-center text-center mb-2">

<!-- LOGO -->
<div class="h-20 w-20 mb-4 flex items-center justify-center overflow-hidden">
<img src="{{ asset('img/Logo_Zstudy.png') }}" class="h-20 w-20 object-contain" />
</div>


<!-- WELCOME TEXT -->
<h2 class="mb-1 text-2xl font-semibold text-slate-900">
Hai, Selamat datang di
</h2>
<h2 class="mb-4 font-bold" style="color: var(--secondary); font-size: 2.2rem;">
Z-Study!
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
      placeholder="Username / NIM / NIDN / Email"
      class="w-full border-b border-slate-300 bg-transparent
             px-2 py-3 text-slate-900 placeholder-slate-500
             focus:border-[#2563EB] focus:border-b-2 focus:outline-none"
    />

    <!-- PASSWORD -->
    <div class="relative">
      <input
        id="loginPasswordInput"
        type="password"
        name="password"
        placeholder="Password"
        class="w-full border-b border-slate-300 bg-transparent
               px-2 py-3 pr-10 text-slate-900 placeholder-slate-500
               focus:border-[#2563EB] focus:border-b-2 focus:outline-none"
      />
      <button
        type="button"
        id="toggleLoginPassword"
        class="absolute right-1 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700"
        aria-label="Tampilkan password"
      >
        <span class="material-symbols-rounded text-base">visibility</span>
      </button>
    </div>

    <!-- OPTIONS -->
    <div class="flex items-center justify-between text-sm text-slate-600">
      <label class="flex items-center gap-2">
        <input type="checkbox" name="remember" class="rounded text-[#2563EB]">
        Ingat Saya
      </label>

      <a href="{{ route('password.request') }}" data-open-forgot class="hover:text-slate-900">
        Lupa Password?
      </a>
    </div>

    <!-- BUTTON -->
    <button
      type="submit"
      class="mt-4 w-full rounded-full
             bg-gradient-to-r from-[#2563EB] to-[#7C3AED]
             px-6 py-3 text-sm font-semibold text-white
             transition hover:-translate-y-1 hover:shadow-xl">
      Masuk
    </button>
  </form>
</div>
</div>
</div>

<!-- FORGOT PASSWORD -->
<div
  id="forgotOverlay"
  class="fixed inset-0 z-40 hidden
         items-center justify-center
         bg-black/40 backdrop-blur-sm">

  <div
    class="relative w-[92%] md:w-full max-w-md rounded-3xl
           bg-white/55 backdrop-blur-xl
           p-8
           shadow-[0_25px_50px_-15px_rgba(0,0,0,0.4)]
           border border-white/60">

    <button
      type="button"
      id="forgotClose"
      class="absolute right-4 top-4 h-10 w-10 rounded-full
             flex items-center justify-center
             text-slate-600 hover:text-slate-800 hover:bg-slate-100/70 text-xl">
      ✕
    </button>

    <div class="flex flex-col items-center text-center mb-4">
      <div class="h-14 w-14 mb-3 rounded-2xl bg-gradient-to-br from-[#2563EB] to-[#7C3AED] flex items-center justify-center text-white">
        <span class="material-symbols-rounded">mail</span>
      </div>
      <h2 class="mb-1 text-xl font-semibold text-slate-900">Lupa Password</h2>
      <p class="text-sm text-slate-500">Masukkan username / NIM / NIDN / email untuk menerima tautan reset.</p>
    </div>

    @if (session('status'))
      <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
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
               transition hover:-translate-y-1 hover:shadow-xl">
        Kirim Tautan
      </button>
    </form>

    <div class="mt-6 border-t border-white/60 pt-5">
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
                 transition hover:-translate-y-1 hover:shadow-xl">
          Verifikasi Token
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
const prevBtn = document.getElementById("prevSlide")
const nextBtn = document.getElementById("nextSlide")

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

const loginOverlay = document.getElementById('loginOverlay');
loginOverlay?.addEventListener('click', (e) => {
  if (e.target === loginOverlay) {
    toggleLogin();
  }
});

const forgotOverlay = document.getElementById('forgotOverlay');
const openForgotLinks = document.querySelectorAll('[data-open-forgot]');
const closeForgotBtn = document.getElementById('forgotClose');

const openForgot = () => {
  if (!forgotOverlay) return;
  forgotOverlay.classList.remove('hidden');
  forgotOverlay.classList.add('flex');
  if (loginOverlay) {
    loginOverlay.classList.add('hidden');
    loginOverlay.classList.remove('flex');
  }
};

const closeForgot = () => {
  if (!forgotOverlay) return;
  forgotOverlay.classList.add('hidden');
  forgotOverlay.classList.remove('flex');
};

openForgotLinks.forEach((link) => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    openForgot();
  });
});

closeForgotBtn?.addEventListener('click', closeForgot);
forgotOverlay?.addEventListener('click', (e) => {
  if (e.target === forgotOverlay) closeForgot();
});

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

  // 🔥 Login dibuka saat scroll lagi di slide terakhir (lihat wheel handler)
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
    if (currentIndex === slides.length - 1) {
      if (!loginOpened) {
        loginOpened = true
        toggleLogin()
      }
    } else {
      goToSlide(currentIndex + 1)
    }
  } else {
    goToSlide(currentIndex - 1)
  }

  setTimeout(() => {
    isScrolling = false
  }, 600)
}, { passive: false })

prevBtn?.addEventListener("click", () => {
  goToSlide(currentIndex - 1)
})

nextBtn?.addEventListener("click", () => {
  goToSlide(currentIndex + 1)
})

// INIT
updateProgress()
updateOverlay()
updateOverlayPosition()
</script>

<script>
  const loginPasswordInput = document.getElementById('loginPasswordInput');
  const toggleLoginPassword = document.getElementById('toggleLoginPassword');

  toggleLoginPassword?.addEventListener('click', () => {
    if (!loginPasswordInput) return;
    const isHidden = loginPasswordInput.type === 'password';
    loginPasswordInput.type = isHidden ? 'text' : 'password';
    const icon = toggleLoginPassword.querySelector('.material-symbols-rounded');
    if (icon) icon.textContent = isHidden ? 'visibility_off' : 'visibility';
  });
</script>

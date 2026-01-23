<x-header></x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>

<div class="max-w-4xl mx-auto p-6 space-y-6">
  
  <div class="sticky top-18 float-right bg-white border rounded-xl px-4 py-3 shadow-sm text-center w-48">
    <p class="text-xs text-slate-500">Sisa Waktu</p>
    <p class="font-mono text-lg font-semibold text-red-600">01:27:45</p>
    </div>


  <div class="space-y-2">
    <h1 class="text-2xl font-bold">Kuis Mid Semester</h1>
    <p class="text-sm text-slate-500">
      Struktur Data dan Algoritma · Kelas A
    </p>
 <!-- Progress -->
 <div class="w-full h-2 bg-slate-200 rounded-full">
  <div id="progressBar" class="h-2 bg-blue-600 rounded-full" style="width: 20%"></div>
</div>
<p class="text-xs text-slate-500">Sesi 1 dari 5 soal</p>
</div>

   
  

  <div class="space-y-6" id="questionGroup">

    <!-- SOAL -->
    <div class="border rounded-xl p-5 space-y-4 question">
      <p class="font-medium">
        1. Apa yang dimaksud dengan algoritma?
      </p>
  
      <div class="space-y-2">
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q1" class="answer">
          <span>Bahasa pemrograman tingkat tinggi</span>
        </label>
  
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q1" class="answer">
          <span>Langkah sistematis menyelesaikan masalah</span>
        </label>
      </div>
    </div>
  
    <!-- DUPLIKASI sampai 5 soal -->

 <!-- SOAL -->
 <div class="border rounded-xl p-5 space-y-4 question">
  <p class="font-medium">
    1. Apa yang dimaksud dengan algoritma?
  </p>

  <div class="space-y-2">
    <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
      <input type="radio" name="q2" class="answer">
      <span>Bahasa pemrograman tingkat tinggi</span>
    </label>

    <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
      <input type="radio" name="q2" class="answer">
      <span>Langkah sistematis menyelesaikan masalah</span>
    </label>
  </div>
</div>

<!-- DUPLIKASI sampai 5 soal --> <!-- SOAL -->
    <div class="border rounded-xl p-5 space-y-4 question">
      <p class="font-medium">
        1. Apa yang dimaksud dengan algoritma?
      </p>
  
      <div class="space-y-2">
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q3" class="answer">
          <span>Bahasa pemrograman tingkat tinggi</span>
        </label>
  
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q3" class="answer">
          <span>Langkah sistematis menyelesaikan masalah</span>
        </label>
      </div>
    </div>
  
    <!-- DUPLIKASI sampai 5 soal --> <!-- SOAL -->
    <div class="border rounded-xl p-5 space-y-4 question">
      <p class="font-medium">
        1. Apa yang dimaksud dengan algoritma?
      </p>
  
      <div class="space-y-2">
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q4" class="answer">
          <span>Bahasa pemrograman tingkat tinggi</span>
        </label>
  
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q4" class="answer">
          <span>Langkah sistematis menyelesaikan masalah</span>
        </label>
      </div>
    </div>
  
    <!-- DUPLIKASI sampai 5 soal --> <!-- SOAL -->
    <div class="border rounded-xl p-5 space-y-4 question">
      <p class="font-medium">
        1. Apa yang dimaksud dengan algoritma?
      </p>
  
      <div class="space-y-2">
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q5" class="answer">
          <span>Bahasa pemrograman tingkat tinggi</span>
        </label>
  
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q5" class="answer">
          <span>Langkah sistematis menyelesaikan masalah</span>
        </label>
      </div>
    </div>
  
    <!-- DUPLIKASI sampai 5 soal --> <!-- SOAL -->
    <div class="border rounded-xl p-5 space-y-4 question">
      <p class="font-medium">
        1. Apa yang dimaksud dengan algoritma?
      </p>
  
      <div class="space-y-2">
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q6" class="answer">
          <span>Bahasa pemrograman tingkat tinggi</span>
        </label>
  
        <label class="flex gap-3 items-center p-3 border rounded-lg cursor-pointer">
          <input type="radio" name="q6" class="answer">
          <span>Langkah sistematis menyelesaikan masalah</span>
        </label>
      </div>
    </div>
  
    <!-- DUPLIKASI sampai 5 soal -->









  </div>

  
  <div class="pt-6 text-right">
    <button id="nextBtn"
      disabled
      class="px-6 py-2 rounded-lg bg-slate-300 text-white cursor-not-allowed">
      Lanjut Sesi
    </button>
  </div>
  
  </div> <!-- end container -->

  
  <script>
    const answers = document.querySelectorAll(".answer");
    const nextBtn = document.getElementById("nextBtn");
  
    function checkAnswers() {
      const questions = document.querySelectorAll(".question");
      let answered = 0;
  
      questions.forEach(q => {
        if (q.querySelector("input:checked")) {
          answered++;
        }
      });
  
      if (answered === 5) {
        nextBtn.disabled = false;
        nextBtn.classList.remove("bg-slate-300", "cursor-not-allowed");
        nextBtn.classList.add("bg-blue-600", "hover:bg-blue-700");
      }
    }
  
    answers.forEach(a => {
      a.addEventListener("change", checkAnswers);
    });
  </script>
  
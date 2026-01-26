<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Progres Nilai Semester</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    

  <div style="max-width: 700px; margin: 40px auto; background: white; padding: 20px; border-radius: 12px;">
    <h2 style="font-weight: bold; margin-bottom: 16px;">
      📊 nilai
    </h2>

    <canvas id="chartNilai"></canvas>
  </div>
  

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('chartNilai').getContext('2d');

const semester = [
  'Semester 1',
  'Semester 2',
  'Semester 3',
  'Semester 4',
  'Semester 5',
  'Semester 6',
  'Semester 7',
  'Semester 8'
];

const nilai = [5, 82, 78, 88, 82, 77, 88, 85];

new Chart(ctx, {
  type: 'line',
  data: {
    labels: semester,
    datasets: [{
      data: nilai,
      borderWidth: 3,
      pointRadius: 6,
      pointHoverRadius: 8,
      tension: 0.4,
      fill: false,

      // 🎯 INI KUNCINYA
      segment: {
        borderColor: ctx => {
          return ctx.p1.parsed.y > ctx.p0.parsed.y
            ? '#16a34a'   // naik → hijau
            : '#dc2626';  // turun → merah
        }
      },

      // WARNA TITIK (BIAR KONSISTEN)
      pointBackgroundColor: nilai.map((val, i) =>
        i === 0 || val >= nilai[i - 1] ? '#16a34a' : '#dc2626'
      )
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        min: 0,
        max: 100,
        ticks: {
          stepSize: 10
        }
      }
    }
  }
});
</script>



  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 p-10">

  <!-- CARD -->
  <div class="max-w-sm mx-auto bg-white rounded-xl shadow p-4">
    <h2 class="text-center font-bold mb-3">
      Statistik Mahasiswa
    </h2>

    <!-- CONTAINER CHART (HEIGHT NYATA) -->
    <div class="relative w-full h-54">
      <canvas id="statChart"></canvas>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const ctx = document.getElementById('statChart');

      new Chart(ctx, {
        type: 'radar',
        data: {
          labels: ['Sikap', 'Ujian', 'Tugas', 'Kecepatan', 'Kehadiran' ],
          datasets: [{
            data: [80, 70, 85, 60, 90],
            fill: true,
            backgroundColor: 'rgba(59,130,246,0.25)',
            borderColor: 'rgb(59,130,246)',
            pointBackgroundColor: 'rgb(59,130,246)',
            pointBorderColor: '#fff',
            pointRadius: 5,
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            r: {
              min: 0,
              max: 100,
              ticks: {
                display: false
              },
              grid: {
                color: '#e5e7eb'
              },
              angleLines: {
                color: '#d1d5db'
              },
              pointLabels: {
                font: {
                  size: 13,
                  weight: 'bold'
                },
                color: '#374151'
              }
            }
          }
        }
      });
    });
  </script>

    
</body>
</html>


<x-header></x-header>
<x-navbar></x-navbar>
<x-sidebar>mahasiswa</x-sidebar>


<div style="width: 600px; height: 350px;">
    <canvas id="ipkChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="chart-ipk.js"></script>


<script>
const ctx = document.getElementById('ipkChart').getContext('2d');

const semesterLabels = [
    'Semester 1',
    'Semester 2',
    'Semester 3',
    'Semester 4',
    'Semester 5'
];

const ipkData = [3.12, 3.25, 3.18, 3.40, 3.55];

// warna otomatis naik hijau, turun merah
const segmentColor = ctx => {
    return ctx.p0.parsed.y < ctx.p1.parsed.y
        ? 'green'
        : 'red';
};

new Chart(ctx, {
    type: 'line',
    data: {
        labels: semesterLabels,
        datasets: [{
            label: 'IPK Mahasiswa',
            data: ipkData,
            borderWidth: 3,
            fill: false,
            tension: 0.4,
            segment: {
                borderColor: segmentColor
            },
            pointBackgroundColor: '#1d4ed8',
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                min: 0,
                max: 4,
                ticks: {
                    stepSize: 0.25, // cocok untuk IPK
                    callback: value => value.toFixed(2) // tampil 2 angka koma
                },
                // title: {
                //     display: true,
                //     text: 'IPK'
                // }
            },
            x: {
                // title: {
                //     display: true,
                //     text: 'Semester'
                // }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: ctx => `IPK: ${ctx.parsed.y.toFixed(2)}`
                }
            }
        }
    }
});
</script>
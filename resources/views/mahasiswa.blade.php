<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<script> 
    setTimeout(() => {
      location.reload();
    }, 5000); // 2000 ms = 2 detik
  </script>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Z-Study</h2>
        <span>Online Learning System</span>
    </div>

    <ul class="sidebar-menu">
        <li class="active">
            <a href="#">
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span>Kelas</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span>Materi Pembejaran</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span>Tugas</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span>Kuis / Ujian</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span>Nilai</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span>Diskusi</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span>Pengumuman</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="#" class="logout">Logout</a>
    </div>
</div>

</body>
</html>

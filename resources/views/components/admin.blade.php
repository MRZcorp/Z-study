

  <nav class="sidebar-nav">
    <!-- Primary Top Nav -->
    <ul class="nav-list primary-nav">

      <x-side-link 
      link="admin" 
      icon="dashboard" 
      label="Dashboard"
      
      ></x-side-link>
      <x-side-link 
      link="admin" 
      icon="User_Attributes" 
      label="Akun Pengguna"
      icon2="keyboard_arrow_down"
      drop
      drop1="Admin"
      link1="kelola_admin"
      drop2="Dosen"
      link2="kelola_dosen"
      drop3="Mahasiswa"
      link3="kelola_mahasiswa"
      ></x-side-link>
      
      <x-side-link 
      link="admin" 
      icon="local_library" 
      label="Akademik"
      icon2="keyboard_arrow_down"
      drop
      drop1="Kelas"
      link1="kelola_kelas"
      drop2="Mata Kuliah"
      link2="kelola_matakuliah"
      drop3="Jadwal"
      link3="kelola_jadwal"
      ></x-side-link>
      
      <x-side-link 
      link="admin/pengumuman" 
      icon="notifications" 
      label="Pengumuman"
      ></x-side-link>

      <x-side-link 
      link="admin/pengaturan" 
      icon="settings" 
      label="Pengaturan"
      ></x-side-link>
      </ul>

    <!-- Secondary Bottom Nav -->
    
    <ul class="nav-list secondary-nav">

      <x-side-link 
      link="admin/bantuan" 
      icon="help" 
      label="Bantuan"
      ></x-side-link>
  
      <x-side-link 
      link="logout" 
      icon="logout" 
      label="Sign Out"
      ></x-side-link>
  
      
     
  
    </ul>
  {{-- <!-- Dropdown -->
<li class="nav-item dropdown-container">
  <a href="#" class="nav-link dropdown-toggle">
  <span class="material-symbols-rounded">local_library</span>
  <span class="nav-label">Akademik</span>
  <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
  </a>
         <!-- Dropdown Menu -->
         <ul class="dropdown-menu">
          <li class="nav-item"><a class="nav-link dropdown-title">Akademik</a></li>
          <li class="nav-item"><a href="#" class="nav-link dropdown-link">Kelas</a></li>
          <li class="nav-item"><a href="#" class="nav-link dropdown-link">Mata Kuliah</a></li>
          <li class="nav-item"><a href="#" class="nav-link dropdown-link">Jadwal</a></li>
        </ul>
      </li> --}}
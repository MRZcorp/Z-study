

  <nav class="sidebar-nav">
    <!-- Primary Top Nav -->
    <ul class="nav-list primary-nav">

      <x-side-link 
      link="admin" 
      icon="dashboard" 
      label="Dashboard"
      
      ></x-side-link>
      <li class="nav-item menu-item dropdown-container admin-tooltip {{ request()->is('admin/user_setting') || request()->is('admin/kelola_dosen') || request()->is('admin/kelola_dosen/wali') || request()->is('admin/kelola_mahasiswa') ? 'active' : '' }}" data-dropdown-id="Akun Pengguna">
        <a href="" class="nav-link dropdown-toggle">
          <span class="material-symbols-rounded">User_Attributes</span>
          <span class="nav-label">Akun Pengguna</span>
          <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
          <span class="admin-tooltip-label">Akun Pengguna</span>
        </a>
        <ul class="dropdown-menu">
          <li class="nav-item"><a class="nav-link dropdown-title">Akun Pengguna</a></li>
          <li class="nav-item"><a href="/admin/user_setting" class="nav-link dropdown-link {{ request()->is('admin/user_setting') ? 'active' : '' }}">Setting Pengguna</a></li>
          <li class="nav-item"><a href="/admin/kelola_dosen" class="nav-link dropdown-link {{ request()->is('admin/kelola_dosen') ? 'active' : '' }}">Data Dosen</a></li>
          <li class="nav-item"><a href="/admin/kelola_dosen/wali" class="nav-link dropdown-link {{ request()->is('admin/kelola_dosen/wali') ? 'active' : '' }}">Data Dosen Wali</a></li>
          <li class="nav-item"><a href="/admin/kelola_mahasiswa" class="nav-link dropdown-link {{ request()->is('admin/kelola_mahasiswa') ? 'active' : '' }}">Data Mahasiswa</a></li>
        </ul>
      </li>
      
      {{-- <x-side-link 
      link="" 
      icon="local_library" 
      label="Akademik"
      icon2="keyboard_arrow_down"
      drop
      drop1="Kelas"
      link1="/admin/kelola_kelas"
      drop2="Mata Kuliah"
      link2="/admin/kelola_matakuliah"
      drop3="Jadwal"
      link3="/admin/kelola_jadwal"
      ></x-side-link> --}}

  <!-- Dropdown -->
  <li class="nav-item menu-item dropdown-container admin-tooltip {{ request()->is('admin/kelola_mata_kuliah') || request()->is('admin/data_kelas') || request()->is('admin/kelola_jadwal') ? 'active' : '' }}" data-dropdown-id="Akademik">
    <a href="" class="nav-link dropdown-toggle">
    <span class="material-symbols-rounded">local_library</span>
    <span class="nav-label">Akademik</span>
    <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
    <span class="admin-tooltip-label">Akademik</span>
    </a>
           <!-- Dropdown Menu -->
           <ul class="dropdown-menu">
            <li class="nav-item"><a class="nav-link dropdown-title">Akademik</a></li>
            <li class="nav-item"><a href="/admin/kelola_mata_kuliah" class="nav-link dropdown-link {{ request()->is('admin/kelola_mata_kuliah') ? 'active' : '' }}">Mata Kuliah</a></li>
            <li class="nav-item"><a href="/admin/data_kelas" class="nav-link dropdown-link {{ request()->is('admin/data_kelas') ? 'active' : '' }}">Kelas</a></li>
            <li class="nav-item"><a href="/admin/kelola_jadwal" class="nav-link dropdown-link {{ request()->is('admin/kelola_jadwal') ? 'active' : '' }}">Jadwal</a></li>
          </ul>
        </li>




      
      <x-side-link 
      link="admin/prodi" 
      icon="school" 
      label="Program Studi"
      ></x-side-link>

      <x-side-link 
      link="admin/fakultas" 
      icon="apartment" 
      label="Fakultas"
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
  
  
    </ul>

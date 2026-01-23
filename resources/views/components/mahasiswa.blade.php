

  <nav class="sidebar-nav">
    <!-- Primary Top Nav -->
    <ul class="nav-list primary-nav">

      <x-side-link link="mahasiswa" icon="dashboard" label="Dashboard"></x-side-link>
      <x-side-link link="mahasiswa/kelas" icon="Add_Home" label="Kelas"></x-side-link>
      <x-side-link link="mahasiswa/materi" icon="Menu_Book" label="Materi"></x-side-link>
      <x-side-link link="mahasiswa/tugas" icon="Assignment_Add" label="Tugas"></x-side-link>
      <x-side-link link="mahasiswa/ujian" icon="Contract_Edit" label="Kuis / Ujian"></x-side-link>
      <x-side-link link="mahasiswa/nilai" icon="School" label="Nilai"></x-side-link>
      <x-side-link link="mahasiswa/pengaturan" icon="settings" label="Pengaturan"></x-side-link>
      <x-side-link link="mahasiswa/nilai" icon="School" label="Nilai"></x-side-link>
    

    </ul>
    <!-- Secondary Bottom Nav -->
    
    <ul class="nav-list secondary-nav">

      <x-side-link link="mahasiswa/Bantuan" icon="help" label="Bantuan"></x-side-link>
      <x-side-link link="logout" icon="logout" label="Sign Out"></x-side-link>


      
    </ul>
  </nav>
   {{-- <li class="nav-item menu-item {{ request()->is('mahasiswa/pengaturan') ? 'active' : '' }}">
        <a href="{{ url('mahasiswa/pengaturan') }}" class="nav-link">
          <span class="material-symbols-rounded">settings</span>
          <span class="nav-label">Pengaturan</span>
        </a>
        <ul class="dropdown-menu">
          <li class="nav-item"><a class="nav-link dropdown-title">Pengaturan</a></li>
        </ul>
      </li>
    </ul> --}}
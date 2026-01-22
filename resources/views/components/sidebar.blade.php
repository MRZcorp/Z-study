




<!-- Mobile Sidebar Menu Button -->


<button class="sidebar-menu-button">
    <span class="material-symbols-rounded">menu</span>
  </button>

  
  @php
  $role = trim($slot);
@endphp


<aside class="sidebar">
  <!-- Sidebar Header -->
<header class="sidebar-header">
 {{-- <!-- BRAND SIDEBAR -->
 <div class="flex items-center gap-3 p-4">
  <img src="{{ asset('img/Logo_Zstudy.png') }}" class="h-10 w-10" />

  <div class="sidebar-text">
    <h1 class="font-bold text-lg">Z-Study</h1>
    <p class="text-xs text-gray-500">Online Learning System</p>
  </div>
</div> --}}
  
       <a href="#" class="nav-label header-logo">
        <img src="{{ asset('img/Logo_Zstudy.png') }}" alt="Z" class="logo-img">
      </a>
      

  <button class="sidebar-toggler">
    <span class="material-symbols-rounded">chevron_left</span>
  </button>
</header>
    @includeIf('components.' . $role)


</aside>

<script src="{{ asset('js/script.js') }}"></script>
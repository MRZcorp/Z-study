




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
<script>
  const examLock = document.querySelector('[data-exam-lock="true"]');
  if (examLock) {
    document.querySelectorAll('.sidebar a, .sidebar button, .sidebar .dropdown-toggle, .sidebar input, .sidebar select, .sidebar textarea').forEach((el) => {
      el.classList.add('pointer-events-none', 'opacity-60');
      el.setAttribute('tabindex', '-1');
      el.setAttribute('aria-disabled', 'true');
      if (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA') {
        el.setAttribute('disabled', 'disabled');
      }
    });
    document.querySelectorAll('.sidebar-menu-button, .sidebar-toggler').forEach((el) => {
      el.classList.add('pointer-events-none', 'opacity-60');
      el.setAttribute('tabindex', '-1');
      el.setAttribute('aria-disabled', 'true');
    });
  }
</script>
<div class="main-content">

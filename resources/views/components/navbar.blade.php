
<!-- Navigation Bar -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-end h-16 items-center">
            <!-- Left Section - Logo/Brand -->
 <!-- NAVBAR LEFT BRAND -->

    <div class="flex-2 flex justify-between items-center">
        <div class="flex items-center">
            <div class="h-10 w-10   flex items-center justify-center text-blue-600 overflow-hidden mr-3">
                <img src="{{ asset('img/Logo_Zstudy.png') }}" class="h-10 w-10" />
            </div>
            <div>
                <p class="font-medium text-gray-900">Z-Study</p>
                <p class="text-sm text-gray-500">Online Learning System</p>
            </div>
            </div>

            
            <!-- Right Section - Actions -->
            <div class="flex items-center gap-4">


        <!-- Search Bar -->
        <!-- Search Expand -->
        <!-- Google Style Expand Search -->
        <div class="relative flex items-center">
        <form action="{{ route('search') }}" method="GET"
          id="searchForm"
          class="relative flex items-center overflow-hidden search-expand"
          style="width:40px; transition: width 300ms ease;">

        <input
            type="text"
            name="q"
            placeholder="Cari..."
            class="h-10 w-full pl-4 pr-10 rounded-full border border-gray-300
                   text-sm opacity-0 transition-opacity duration-200
                   focus:outline-none focus:ring-2 focus:ring-blue-500"
        >

        <!--  Search Icon -->
        <button type="submit"
                id="searchBtn"
                class="absolute right-2 text-gray-500 hover:text-blue-600">
            <span class="material-symbols-rounded text-[22px]">
                search
            </span>
            </button>
            </form>
            <div id="searchDropdown"
                 class="absolute top-12 left-0 w-80 bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-100 hidden">
              <div id="searchResults" class="max-h-80 overflow-y-auto"></div>
            </div>
            </div>



  
                <div class="dropdown relative">
                    <button id="notifButton" class="p-2 text-gray-600 hover:text-blue-600 rounded-full 
                    hover:bg-gray-100 transition-colors duration-200 relative">
                        <span class="material-symbols-rounded">Notifications</span>
                        @if(($navbarHasNew ?? false))
                        <span class="notification-dot absolute top-0 right-0 h-2.5 w-2.5 rounded-full bg-red-500 
                        border-2 border-white"></span>
                        @endif
                    </button>
                    <div class="dropdown-menu absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl py-2 z-50 opacity-0 invisible transition-all duration-300 transform -translate-y-2 border border-gray-100" id="notifDropdown">
                        <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700">Pengumuman</span>
                            <span class="text-xs text-gray-500">{{ $navbarPengumumanCount ?? 0 }}</span>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse(($navbarPengumuman ?? collect()) as $item)
                                <div class="px-4 py-2 hover:bg-blue-50 transition-colors">
                                    <button
                                        type="button"
                                        class="text-left text-sm font-semibold text-gray-800 hover:underline btn-preview-announcement"
                                        data-judul="{{ $item->judul }}"
                                        data-isi="{{ $item->isi }}"
                                        data-tipe="{{ $item->tipe ?? 'Info' }}"
                                        data-status="{{ $item->is_active ? 'Publish' : 'Draft' }}"
                                        data-berkas="{{ $item->file_name ?? '' }}"
                                        data-berkas-url="{{ $item->file_path ? asset('storage/' . $item->file_path) : '' }}"
                                        data-tanggal-display="{{ $item->tanggal_publish ? \Illuminate\Support\Str::lower(\Carbon\Carbon::parse($item->tanggal_publish)->locale('id')->translatedFormat('d F Y')) : '-' }}"
                                        data-created="{{ $item->created_at ?? '' }}"
                                    >
                                        {{ $item->judul }}
                                    </button>
                                    <p class="text-xs text-gray-500 line-clamp-2">{{ $item->isi }}</p>
                                    <p class="text-[11px] text-gray-400 mt-1">
                                        {{ $item->tanggal_publish ? \Illuminate\Support\Str::lower(\Carbon\Carbon::parse($item->tanggal_publish)->locale('id')->translatedFormat('d F Y')) : '-' }}
                                    </p>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-gray-500 text-center">Tidak ada pengumuman.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="dropdown relative" id="messageWrapper">
                    <button id="messageButton" class="p-2 text-gray-600 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors duration-200 relative">
                        <span class="material-symbols-rounded">chat</span>
                        @if(($navbarDiskusiHasNew ?? false))
                        <span class="message-dot absolute top-0 right-0 h-2.5 w-2.5 rounded-full bg-red-500 border-2 border-white"></span>
                        @endif
                    </button>
                    <div class="dropdown-menu absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl py-2 z-50 opacity-0 invisible transition-all duration-300 transform -translate-y-2 border border-gray-100" id="messageDropdown">
                        <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700">Pesan Z-Study</span>
                            <span class="text-xs text-gray-500">{{ $navbarDiskusiCount ?? 0 }}</span>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse(($navbarDiskusi ?? collect()) as $item)
                                <button
                                    type="button"
                                    class="w-full text-left px-4 py-2 hover:bg-blue-50 transition-colors btn-open-diskusi {{ ($item['unread'] ?? false) ? 'bg-red-50/40' : '' }}"
                                    data-url="{{ $item['url'] ?? '#' }}"
                                    data-type="{{ $item['type'] ?? 'kelas' }}"
                                    data-id="{{ $item['context_id'] ?? '' }}"
                                    data-title="{{ $item['title'] ?? '-' }}"
                                    data-sender="{{ $item['sender'] ?? '-' }}"
                                    data-pesan="{{ $item['pesan'] ?? '-' }}"
                                    data-time="{{ $item['time'] ?? '-' }}"
                                >
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-start gap-3 min-w-0">
                                            <img
                                              src="{{ $item['thumb'] ?? asset('img/grup.png') }}"
                                              alt="Kelas"
                                              class="h-10 w-10 rounded-full object-cover border border-slate-200 shadow-sm"
                                            >
                                            <div class="min-w-0">
                                            <p class="text-[11px] uppercase tracking-wide text-blue-600 font-semibold">{{ $item['type_label'] ?? '-' }}</p>
                                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $item['title'] ?? '-' }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $item['sender'] ?? '-' }}: {{ \Illuminate\Support\Str::limit($item['pesan'] ?? '-', 55) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 shrink-0">
                                            @if(($item['unread'] ?? false))
                                                <span class="inline-block h-2 w-2 rounded-full bg-red-500"></span>
                                            @endif
                                            <span class="text-[11px] text-gray-400">{{ $item['time'] ?? '-' }}</span>
                                        </div>
                                    </div>
                                </button>
                            @empty
                                <div class="px-4 py-6 text-sm text-gray-500 text-center">Belum ada pesan diskusi.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
  {{-- mode --}}

{{--   
    <button onclick="(() => document.body.classList.toggle('dark'))()"
          class="h-12 w-12 rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="fill-violet-700 block dark:hidden" fill="currentColor" viewBox="0 0 20 20">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
          </svg>
          <svg class="fill-yellow-500 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
              <path
                  d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                  fill-rule="evenodd" clip-rule="evenodd"></path>
          </svg>
      </button> --}}


  {{-- mode --}}


                <div class="hidden md:block h-6 w-px bg-gray-200"></div>
  
                <div class="dropdown relative">
                    <button class="flex items-center space-x-2 focus:outline-none group">
                        <div class="relative">
                            <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center 
                            justify-center text-blue-600 overflow-hidden avatar-ring">
                               
                               
                               
                                <img
                                src="{{ $foto 
                                    ? asset('storage/' . $foto) 
                                    : asset('img/default_profil.jpg') }}"
                                class="h-10 w-10 rounded-full object-cover"
                                alt="Foto Profil"
                            />
                            
                            
                            </div>
                            <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full 
                            bg-green-500 border-2 border-white"></span>
                        </div>
                        <div class="hidden lg:flex flex-col items-start">
                            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600 
                            transition-colors duration-200">{{$nama}}</span>
                            <span class="text-xs text-gray-500">{{ $role}}</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-500 hidden lg:inline transition-transform duration-200 group-hover:text-blue-600"></i>
                    </button>
                    <div class="dropdown-menu absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl py-1 z-50 opacity-0 invisible transition-all duration-300 transform -translate-y-2 border border-gray-100">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 overflow-hidden mr-3">
                                  
                                  
                                    
                                    <img
                                    src="{{ $foto 
                                        ? asset('storage/' . $foto) 
                                        : asset('img/default_profil.jpg') }}"
                                    class="h-10 w-10 rounded-full object-cover"
                                    alt="Foto Profil"
                                />




                                
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900"> {{$nama}} </p>
                                    <p class="text-sm text-gray-500"> {{$id_user}} </p>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{$profil}}" class="hidden px-4 py-2.5 text-gray-700 hover:bg-blue-50 
                        hover:text-blue-600 flex items-center transition-colors duration-200">
                            <span class="material-symbols-rounded">Account_Circle</span>
                             <i class="fas fa-user-circle text-gray-400 mr-3 w-5 text-center"></i>
                             My Profile
                        </a>
                        <a href="{{$setting}}" class="block px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center transition-colors duration-200">
                            <span class="material-symbols-rounded">Settings</span> 
                            <i class="fas fa-cog text-gray-400 mr-3 w-5 text-center"></i>
                            Account Settings
                        </a>
                        {{-- <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full block px-4 py-2.5 text-gray-700
                                       hover:bg-blue-50 hover:text-blue-600
                                       flex items-center transition-colors duration-200"
                                onclick="return confirm('Yakin ingin logout?')">
                        
                                <span class="material-symbols-rounded mr-3">logout</span>
                                Sign out
                            </button>
                        </form> --}}

                        
                        <div class="border-t border-gray-100 my-1"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center transition-colors duration-200">
                            
                            <span class="material-symbols-rounded mr-3">logout</span>
                            <i class="fas fa-sign-out-alt text-gray-400 mr-3 w-5 text-center"></i>
                            Sign out
                        </button>
                    </form>
  
                <button id="mobile-menu-button" class="md:hidden p-2 text-gray-600 hover:text-blue-600 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-bars text-xl"></i>
                    <span class="sr-only">Menu</span>
                </button>
            </div>
        </div>
    </div>
  
    
  </nav>

<!-- MODAL PESAN DISKUSI -->
<div id="diskusiModal" class="fixed inset-0 z-[70] hidden items-center justify-center md:justify-start bg-black/45 backdrop-blur-sm px-4 md:px-6">
  <div class="w-full md:w-[340px] md:min-w-[340px] md:max-w-[340px] h-[720px] max-h-[90vh] rounded-2xl bg-white shadow-xl overflow-hidden flex flex-col">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="text-lg font-semibold text-slate-800">Pesan Z-Study</h3>
        <button id="btnCloseDiskusiModal" type="button" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
      </div>
      <div class="p-4 border-b bg-slate-50/70">
        <div class="relative flex items-center">
          <span class="material-symbols-rounded absolute left-3 text-gray-400 text-[20px]">search</span>
          <input
            id="diskusiSearchInput"
            type="text"
            placeholder="Cari pesan diskusi..."
            class="h-10 w-full rounded-full border border-gray-300 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
        <div class="mt-3 flex items-center gap-2">
          <button type="button" id="diskusiFilterAll" class="rounded-full bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white">Semua</button>
          <button type="button" id="diskusiFilterUnread" class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200">Belum Dibaca</button>
        </div>
      </div>
      <div id="diskusiModalList" class="flex-1 overflow-y-auto">
        @forelse(($navbarDiskusi ?? collect()) as $item)
          <div
            class="modal-diskusi-item w-full text-left px-4 py-3 border-b border-slate-100 hover:bg-blue-50 transition-colors cursor-pointer {{ ($item['unread'] ?? false) ? 'bg-red-50/40' : '' }}"
            data-url="{{ $item['url'] ?? '#' }}"
            data-type="{{ $item['type'] ?? 'kelas' }}"
            data-id="{{ $item['context_id'] ?? '' }}"
            data-title="{{ $item['title'] ?? '-' }}"
            data-sender="{{ $item['sender'] ?? '-' }}"
            data-pesan="{{ $item['pesan'] ?? '-' }}"
            data-time="{{ $item['time'] ?? '-' }}"
            data-unread="{{ ($item['unread'] ?? false) ? '1' : '0' }}"
            data-search="{{ strtolower(($item['type_label'] ?? '') . ' ' . ($item['title'] ?? '') . ' ' . ($item['sender'] ?? '') . ' ' . ($item['pesan'] ?? '')) }}"
          >
            <div class="flex items-start justify-between gap-3">
              <div class="flex items-start gap-3 min-w-0">
                <img
                  src="{{ $item['thumb'] ?? asset('img/grup.png') }}"
                  alt="Kelas"
                  class="h-11 w-11 rounded-full object-cover border border-slate-200 shadow-sm"
                >
                <div class="min-w-0">
                  <p class="text-[11px] uppercase tracking-wide text-blue-600 font-semibold">{{ $item['type_label'] ?? '-' }}</p>
                  <p class="diskusi-open-title text-sm font-semibold text-gray-800 truncate hover:underline">{{ $item['title'] ?? '-' }}</p>
                  <p class="text-xs text-gray-500 truncate">{{ $item['sender'] ?? '-' }}: {{ \Illuminate\Support\Str::limit($item['pesan'] ?? '-', 70) }}</p>
                </div>
              </div>
              <div class="flex items-center gap-1 shrink-0">
                @if(($item['unread'] ?? false))
                  <span class="inline-block h-2 w-2 rounded-full bg-red-500"></span>
                @endif
                <span class="text-[11px] text-gray-400">{{ $item['time'] ?? '-' }}</span>
              </div>
            </div>
          </div>
        @empty
          <div class="px-4 py-10 text-sm text-gray-500 text-center">Belum ada pesan diskusi.</div>
        @endforelse
        <div id="diskusiModalEmpty" class="hidden px-4 py-10 text-sm text-gray-500 text-center">Tidak ada hasil.</div>
      </div>
  </div>
</div>

@php
  $roleKeyNavbar = strtolower((string) ($role ?? ''));
  $navbarChatTemplates = [];
  if ($roleKeyNavbar === 'dosen') {
      $navbarChatTemplates = [
          'kelas' => [
              'base' => route('dosen.kelas.diskusi.index', ['kelas' => '__CTX_ID__']),
              'message' => route('dosen.kelas.diskusi.update', ['kelas' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']),
          ],
          'ujian' => [
              'base' => route('dosen.ujian.diskusi.index', ['ujian' => '__CTX_ID__']),
              'message' => route('dosen.ujian.diskusi.update', ['ujian' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']),
          ],
          'tugas' => [
              'base' => route('dosen.tugas.diskusi.index', ['tugas' => '__CTX_ID__']),
              'message' => route('dosen.tugas.diskusi.update', ['tugas' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']),
          ],
      ];
  } elseif ($roleKeyNavbar === 'mahasiswa') {
      $navbarChatTemplates = [
          'kelas' => [
              'base' => route('mahasiswa.kelas.diskusi.index', ['kelas' => '__CTX_ID__']),
              'message' => route('mahasiswa.kelas.diskusi.update', ['kelas' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']),
          ],
          'ujian' => [
              'base' => route('mahasiswa.ujian.diskusi.index', ['ujian' => '__CTX_ID__']),
              'message' => route('mahasiswa.ujian.diskusi.update', ['ujian' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']),
          ],
          'tugas' => [
              'base' => route('mahasiswa.tugas.diskusi.index', ['tugas' => '__CTX_ID__']),
              'message' => route('mahasiswa.tugas.diskusi.update', ['tugas' => '__CTX_ID__', 'diskusi' => '__DISKUSI_ID__']),
          ],
      ];
  }
@endphp
<script>
  window.__navbarChatTemplates = @json($navbarChatTemplates);
</script>

<script>
  (() => {
    const examLock = document.querySelector('[data-exam-lock="true"]');
    if (examLock) {
      document.querySelectorAll('nav button, nav a, nav input, nav select, nav textarea').forEach((el) => {
        el.classList.add('pointer-events-none', 'opacity-60');
        el.setAttribute('tabindex', '-1');
        el.setAttribute('aria-disabled', 'true');
        if (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA') {
          el.setAttribute('disabled', 'disabled');
        }
      });
    }

    const searchBtn = document.getElementById('searchBtn');
    const searchForm = document.getElementById('searchForm');
    const searchInput = searchForm?.querySelector('input');
    const searchDropdown = document.getElementById('searchDropdown');
    const searchResults = document.getElementById('searchResults');

    const expandSearch = () => {
      if (!searchForm) return;
      searchForm.style.width = '260px';
      if (searchInput) {
        searchInput.classList.remove('opacity-0');
        searchInput.classList.add('opacity-100');
      }
    };

    const collapseSearch = () => {
      if (!searchForm) return;
      if (searchInput && searchInput.value.trim() !== '') return;
      searchForm.style.width = '40px';
      if (searchInput) {
        searchInput.classList.add('opacity-0');
        searchInput.classList.remove('opacity-100');
      }
      if (searchDropdown) searchDropdown.classList.add('hidden');
    };

    const renderSearchResults = (items) => {
      if (!searchResults || !searchDropdown) return;
      if (!items.length) {
        searchResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">Tidak ada hasil.</div>';
        searchDropdown.classList.remove('hidden');
        return;
      }
      const grouped = {};
      items.forEach((item) => {
        grouped[item.type] = grouped[item.type] || [];
        grouped[item.type].push(item);
      });
      searchResults.innerHTML = Object.entries(grouped).map(([type, list]) => {
        const rows = list.map((it) => `
                <a href="${it.url}" class="block px-4 py-2 hover:bg-blue-50">
                  <div class="text-xs uppercase text-gray-400">${type}</div>
                  <div class="text-sm font-semibold text-gray-800">${it.label}</div>
                </a>
            `).join('');
        return `<div class="border-b border-gray-100 py-1">${rows}</div>`;
      }).join('');
      searchDropdown.classList.remove('hidden');
    };

    let searchTimer = null;
    const fetchSearch = () => {
      const q = searchInput?.value.trim() || '';
      if (!q) {
        if (searchDropdown) searchDropdown.classList.add('hidden');
        return;
      }
      fetch(`{{ route('search') }}?q=${encodeURIComponent(q)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      })
        .then((res) => res.json())
        .then((data) => renderSearchResults(data.items || []))
        .catch(() => {
          if (searchDropdown) searchDropdown.classList.add('hidden');
        });
    };

    searchInput?.addEventListener('input', () => {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(fetchSearch, 250);
    });

    searchInput?.addEventListener('focus', () => {
      expandSearch();
      fetchSearch();
    });

    searchBtn?.addEventListener('click', (e) => {
      e.preventDefault();
      expandSearch();
      searchInput?.focus();
      fetchSearch();
    });

    document.addEventListener('click', (e) => {
      if (!searchDropdown || !searchForm) return;
      if (!searchForm.contains(e.target)) {
        searchDropdown.classList.add('hidden');
        collapseSearch();
      }
    });

    searchForm?.addEventListener('submit', (e) => {
      e.preventDefault();
      fetchSearch();
    });

    const notifDropdown = document.getElementById('notifDropdown');
    const notifButton = document.getElementById('notifButton');
    const messageDropdown = document.getElementById('messageDropdown');
    const messageWrapper = document.getElementById('messageWrapper');
    const messageButton = document.getElementById('messageButton');
    const messageMarkReadUrl = @json($navbarDiskusiMarkReadRoute ?? null);
    const diskusiModal = document.getElementById('diskusiModal');
    const btnCloseDiskusiModal = document.getElementById('btnCloseDiskusiModal');
    const diskusiSearchInput = document.getElementById('diskusiSearchInput');
    const diskusiFilterAll = document.getElementById('diskusiFilterAll');
    const diskusiFilterUnread = document.getElementById('diskusiFilterUnread');
    const diskusiModalEmpty = document.getElementById('diskusiModalEmpty');
    const navbarChatTemplates = window.__navbarChatTemplates || {};
    let diskusiFilterMode = 'all';
    const isMobileViewport = () => window.matchMedia('(max-width: 767px)').matches;
    let reopenDiskusiModalAfterChatClose = false;
    const toggleMobileSidebarForDiskusi = (hide) => {
      if (!isMobileViewport()) return;
      const sidebar = document.querySelector('.sidebar');
      const sidebarBtn = document.querySelector('.sidebar-menu-button');
      if (hide) {
        sidebar?.classList.add('hidden');
        sidebarBtn?.classList.add('hidden');
        return;
      }
      sidebar?.classList.remove('hidden');
      sidebarBtn?.classList.remove('hidden');
    };

    const openDiskusiModalPanel = () => {
      if (!diskusiModal) return;
      hideDropdown(messageDropdown);
      toggleMobileSidebarForDiskusi(true);
      if (diskusiSearchInput) diskusiSearchInput.value = '';
      diskusiFilterMode = 'all';
      diskusiFilterAll?.classList.remove('bg-slate-100', 'text-slate-700');
      diskusiFilterAll?.classList.add('bg-blue-600', 'text-white');
      diskusiFilterUnread?.classList.remove('bg-blue-600', 'text-white');
      diskusiFilterUnread?.classList.add('bg-slate-100', 'text-slate-700');
      applyDiskusiModalFilter();
      if (!isMobileViewport()) {
        diskusiModal.classList.add('split-with-chat');
      } else {
        diskusiModal.classList.remove('split-with-chat');
      }
      diskusiModal.classList.remove('hidden');
      diskusiModal.classList.add('flex');
      setTimeout(() => diskusiSearchInput?.focus(), 50);
    };

    const showDropdown = (dropdown) => {
      if (!dropdown) return;
      dropdown.classList.remove('opacity-0', 'invisible', '-translate-y-2');
      dropdown.classList.add('opacity-100', 'visible', 'translate-y-0');
    };

    const hideDropdown = (dropdown) => {
      if (!dropdown) return;
      dropdown.classList.add('opacity-0', 'invisible', '-translate-y-2');
      dropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
    };

    const toggleDropdown = (dropdown) => {
      if (!dropdown) return;
      const isOpen = dropdown.classList.contains('visible');
      hideDropdown(notifDropdown);
      hideDropdown(messageDropdown);
      if (!isOpen) showDropdown(dropdown);
    };

    notifButton?.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleDropdown(notifDropdown);
    });

    messageButton?.addEventListener('click', (e) => {
      e.stopPropagation();
      if (isMobileViewport()) {
        openDiskusiModalPanel();
        return;
      }
      openDiskusiModalPanel();
    });

    messageWrapper?.addEventListener('mouseenter', () => {
      showDropdown(messageDropdown);
    });

    messageWrapper?.addEventListener('mouseleave', () => {
      hideDropdown(messageDropdown);
    });

    document.addEventListener('click', (e) => {
      if (notifDropdown && !notifDropdown.contains(e.target) && !notifButton?.contains(e.target)) {
        hideDropdown(notifDropdown);
      }
      if (messageDropdown && !messageDropdown.contains(e.target) && !messageButton?.contains(e.target)) {
        hideDropdown(messageDropdown);
      }
    });

    const markDiskusiRead = (type, id) => {
      if (!messageMarkReadUrl || !id) return Promise.resolve();
      return fetch(messageMarkReadUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        credentials: 'same-origin',
        body: JSON.stringify({ type, id }),
      }).catch(() => {});
    };

    const openDiskusiChatModal = (btn) => {
      if (!btn) return;
      const fromDiskusiModal = btn.classList.contains('modal-diskusi-item');
      const type = btn.dataset.type || 'kelas';
      const id = String(btn.dataset.id || '');
      const title = btn.dataset.title || 'Diskusi';
      const templates = navbarChatTemplates[type] || null;

      if (!templates || typeof window.openChatModal !== 'function') {
        return;
      }

      window.__chatContextType = type;
      window.__chatBaseUrlTemplate = templates.base;
      window.__chatMessageUrlTemplate = templates.message;

      const tempBtn = document.createElement('button');
      tempBtn.dataset.kelasId = id;
      tempBtn.dataset.kelasNama = title;
      tempBtn.dataset.userMap = '{}';

      const openSelectedChat = () => {
        if (isMobileViewport()) {
          reopenDiskusiModalAfterChatClose = fromDiskusiModal;
          if (diskusiModal) {
            diskusiModal.classList.add('hidden');
            diskusiModal.classList.remove('flex', 'split-with-chat');
          }
          // Keep split mode on mobile too so fullscreen/mobile-specific chat styles apply.
          window.__chatSplitWithNavbar = true;
        } else {
          reopenDiskusiModalAfterChatClose = false;
          if (diskusiModal) {
            diskusiModal.classList.remove('hidden');
            diskusiModal.classList.add('flex');
            diskusiModal.classList.add('split-with-chat');
          }
          window.__chatSplitWithNavbar = true;
        }
        const chatModal = document.getElementById('chatModal');
        const isChatOpen = !!chatModal && !chatModal.classList.contains('hidden');
        if (isChatOpen && typeof window.switchChatContextFromNavbar === 'function') {
          window.switchChatContextFromNavbar(tempBtn);
          return;
        }
        window.openChatModal(tempBtn);
      };

      markDiskusiRead(type, Number(id)).finally(() => {
        openSelectedChat();
      });
    };

    const applyDiskusiModalFilter = () => {
      const q = (diskusiSearchInput?.value || '').trim().toLowerCase();
      const items = Array.from(document.querySelectorAll('.modal-diskusi-item'));
      let shown = 0;
      items.forEach((item) => {
        const hay = item.dataset.search || '';
        const unread = item.dataset.unread === '1';
        const passText = !q || hay.includes(q);
        const passUnread = diskusiFilterMode === 'all' ? true : unread;
        const ok = passText && passUnread;
        item.classList.toggle('hidden', !ok);
        if (ok) shown += 1;
      });
      if (diskusiModalEmpty) {
        diskusiModalEmpty.classList.toggle('hidden', shown > 0);
      }
    };

    diskusiSearchInput?.addEventListener('input', applyDiskusiModalFilter);
    diskusiFilterAll?.addEventListener('click', () => {
      diskusiFilterMode = 'all';
      diskusiFilterAll.classList.remove('bg-slate-100', 'text-slate-700');
      diskusiFilterAll.classList.add('bg-blue-600', 'text-white');
      diskusiFilterUnread.classList.remove('bg-blue-600', 'text-white');
      diskusiFilterUnread.classList.add('bg-slate-100', 'text-slate-700');
      applyDiskusiModalFilter();
    });
    diskusiFilterUnread?.addEventListener('click', () => {
      diskusiFilterMode = 'unread';
      diskusiFilterUnread.classList.remove('bg-slate-100', 'text-slate-700');
      diskusiFilterUnread.classList.add('bg-blue-600', 'text-white');
      diskusiFilterAll.classList.remove('bg-blue-600', 'text-white');
      diskusiFilterAll.classList.add('bg-slate-100', 'text-slate-700');
      applyDiskusiModalFilter();
    });

    document.getElementById('diskusiModalList')?.addEventListener('click', (e) => {
      const btn = e.target.closest('.modal-diskusi-item');
      if (!btn) return;
      e.preventDefault();
      openDiskusiChatModal(btn);
    });

    const closeDiskusiModal = () => {
      if (!diskusiModal) return;
      reopenDiskusiModalAfterChatClose = false;
      const chatModal = document.getElementById('chatModal');
      const isSplitChatOpen = !!chatModal
        && !chatModal.classList.contains('hidden')
        && chatModal.classList.contains('navbar-split-chat');
      if (isSplitChatOpen) {
        if (typeof window.closeChatModal === 'function') {
          window.closeChatModal();
        } else {
          chatModal.classList.add('hidden');
          chatModal.classList.remove('flex', 'navbar-split-chat');
        }
      }
      diskusiModal.classList.remove('split-with-chat');
      diskusiModal.classList.add('hidden');
      diskusiModal.classList.remove('flex');
      toggleMobileSidebarForDiskusi(false);
    };

    window.addEventListener('navbar-chat-closed', () => {
      if (!diskusiModal) return;
      if (isMobileViewport() && reopenDiskusiModalAfterChatClose) {
        reopenDiskusiModalAfterChatClose = false;
        openDiskusiModalPanel();
        return;
      }
      const isDiskusiOpen = !diskusiModal.classList.contains('hidden');
      if (isDiskusiOpen && !isMobileViewport()) {
        diskusiModal.classList.add('split-with-chat');
        return;
      }
      diskusiModal.classList.remove('split-with-chat');
    });

    btnCloseDiskusiModal?.addEventListener('click', closeDiskusiModal);
    diskusiModal?.addEventListener('click', (e) => {
      if (e.target === diskusiModal) closeDiskusiModal();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeDiskusiModal();
    });

    messageDropdown?.addEventListener('click', (e) => {
      const btn = e.target.closest('.btn-open-diskusi');
      if (!btn) return;
      e.preventDefault();
      hideDropdown(messageDropdown);
      openDiskusiChatModal(btn);
    });

    if (notifDropdown) {
      notifDropdown.addEventListener('mouseenter', () => {
        fetch('{{ route('pengumuman.read_all') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
          },
        }).then(() => {
          const dot = document.querySelector('.notification-dot');
          if (dot) dot.classList.add('hidden');
        }).catch(() => {});
      });
    }
  })();
</script>
<style>
  #diskusiModal.split-with-chat {
    justify-content: center !important;
  }
  @media (min-width: 768px) {
    #diskusiModal.split-with-chat > div {
      transform: translateX(-280px);
    }
  }
  @media (max-width: 767px) {
    #diskusiModal {
      inset: 64px 0 0 0 !important;
      height: calc(100dvh - 64px) !important;
      align-items: stretch !important;
      justify-content: stretch !important;
      padding: 0 !important;
      z-index: 300 !important;
    }
    #diskusiModal > div {
      width: 100vw !important;
      min-width: 0 !important;
      max-width: none !important;
      height: calc(100dvh - 64px) !important;
      max-height: none !important;
      border-radius: 0 !important;
      transform: none !important;
    }
  }
</style>

<!-- MODAL PREVIEW PENGUMUMAN (NAVBAR) -->
<div id="navbarPreviewModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden" style="width:70vw; max-width:1100px; height:75vh;">
    <div class="flex items-center justify-between px-5 py-4 border-b">
      <div>
        <h3 class="text-lg font-semibold text-slate-800">Detail Pengumuman</h3>
        <p id="navbarPreviewSubTitle" class="text-sm text-slate-500">-</p>
      </div>
      <div class="flex items-center gap-2">
        <a id="navbarPreviewDownload" href="#" target="_blank" class="rounded-full bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
          <span class="material-symbols-rounded text-base">download</span>
        </a>
        <button type="button" class="btn-close text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
      </div>
    </div>

    <div class="flex gap-4 p-5" style="height:calc(75vh - 64px);">
      <div class="w-[75%] h-full">
        <div id="navbarPreviewContainer" class="w-full h-full rounded-xl border bg-slate-50 flex items-center justify-center text-sm text-slate-500">
          Tidak ada file.
        </div>
      </div>
      <div class="w-[25%] flex flex-col gap-3 h-full text-sm text-slate-700 break-words">
        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700">
            <span class="material-symbols-rounded text-sm">event</span>
            <span id="navbarPreviewTanggal">-</span>
          </span>
        </div>
        <div>
          <p class="text-xs text-slate-500">Deskripsi</p>
          <p id="navbarPreviewIsi">-</p>
        </div>
        <div class="mt-auto">
          <p class="text-xs text-slate-500">File</p>
          <div id="navbarPreviewFileList" class="mt-1 flex flex-col gap-1 text-sm text-blue-700 max-h-40 overflow-y-auto pr-1"></div>
        </div>
        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
          <span id="navbarPreviewTipeBadge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
            <span class="material-symbols-rounded text-sm">flag</span>
            <span id="navbarPreviewTipe">-</span>
          </span>
          <span id="navbarPreviewStatusBadge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
            <span class="material-symbols-rounded text-sm">task_alt</span>
            <span id="navbarPreviewStatus">-</span>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (() => {
  const previewModal = document.getElementById('navbarPreviewModal');
  const previewContainer = document.getElementById('navbarPreviewContainer');
  const previewDownload = document.getElementById('navbarPreviewDownload');
  const previewFileList = document.getElementById('navbarPreviewFileList');
  const previewSubTitle = document.getElementById('navbarPreviewSubTitle');

  const renderPreview = (url, ext) => {
    const lower = (ext || '').toLowerCase();
    if (!previewContainer) return;
    if (!url) {
      previewContainer.innerHTML = 'Tidak ada file.';
      return;
    }

    if (['mp4', 'webm', 'ogg'].includes(lower)) {
      previewContainer.innerHTML = `<video src="${url}" controls class="w-full h-full rounded-xl bg-black"></video>`;
      return;
    }

    if (['pdf'].includes(lower)) {
      previewContainer.innerHTML = `<iframe src="${url}" class="w-full h-full rounded-xl"></iframe>`;
      return;
    }

    if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(lower)) {
      previewContainer.innerHTML = `<div class="text-center text-slate-500 text-sm">Preview tidak tersedia untuk file ini. Silakan download.</div>`;
      return;
    }

    previewContainer.innerHTML = `<iframe src="${url}" class="w-full h-full rounded-xl"></iframe>`;
  };

  const setActiveFile = (file) => {
    if (!file) {
      renderPreview('', '');
      if (previewDownload) previewDownload.href = '#';
      return;
    }
    renderPreview(file.url, file.ext);
    if (previewDownload) previewDownload.href = file.url || '#';
  };

  const closePreview = () => {
    previewModal?.classList.add('hidden');
    previewModal?.classList.remove('flex');
    if (previewContainer) previewContainer.innerHTML = 'Tidak ada file.';
    if (previewFileList) previewFileList.innerHTML = '';
    if (previewDownload) previewDownload.href = '#';
  };

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-preview-announcement');
    if (!btn) return;
    if (!previewModal) return;
    const berkasName = btn.dataset.berkas || '';
    const berkasUrl = btn.dataset.berkasUrl || '';
    const cleanUrl = berkasUrl ? berkasUrl.split('?')[0].toLowerCase() : '';
    const ext = cleanUrl ? cleanUrl.split('.').pop() : '';
    const files = berkasUrl
      ? [{ name: berkasName || 'Berkas', url: berkasUrl, ext }]
      : [];

    const tipeValue = (btn.dataset.tipe || '-').toLowerCase();
    const statusValue = (btn.dataset.status || '-').toLowerCase();
    document.getElementById('navbarPreviewTipe').textContent = btn.dataset.tipe || '-';
    document.getElementById('navbarPreviewStatus').textContent = btn.dataset.status || '-';
    document.getElementById('navbarPreviewTanggal').textContent = btn.dataset.tanggalDisplay || '-';
    document.getElementById('navbarPreviewIsi').textContent = btn.dataset.isi || '-';
    if (previewSubTitle) {
      previewSubTitle.textContent = btn.dataset.judul || '-';
    }

    const tipeBadge = document.getElementById('navbarPreviewTipeBadge');
    if (tipeBadge) {
      tipeBadge.classList.remove('bg-blue-100', 'text-blue-700', 'bg-green-100', 'text-green-700', 'bg-yellow-100', 'text-yellow-700', 'bg-slate-100', 'text-slate-700', 'text-slate-600');
      if (tipeValue === 'info') {
        tipeBadge.classList.add('bg-blue-100', 'text-blue-700');
      } else if (tipeValue === 'event') {
        tipeBadge.classList.add('bg-green-100', 'text-green-700');
      } else if (tipeValue === 'peringatan') {
        tipeBadge.classList.add('bg-yellow-100', 'text-yellow-700');
      } else {
        tipeBadge.classList.add('bg-slate-100', 'text-slate-700');
      }
    }

    const statusBadge = document.getElementById('navbarPreviewStatusBadge');
    if (statusBadge) {
      statusBadge.classList.remove('bg-green-100', 'text-green-700', 'bg-slate-200', 'text-slate-600', 'bg-slate-100');
      if (statusValue === 'publish') {
        statusBadge.classList.add('bg-green-100', 'text-green-700');
      } else if (statusValue === 'draft') {
        statusBadge.classList.add('bg-slate-200', 'text-slate-600');
      } else {
        statusBadge.classList.add('bg-slate-100', 'text-slate-600');
      }
    }

    if (previewFileList) {
      previewFileList.innerHTML = '';
      if (files.length === 0) {
        previewFileList.innerHTML = '<span class="text-slate-400 text-sm">Tidak ada file.</span>';
        setActiveFile(null);
      } else {
        files.forEach((file, idx) => {
          const btnFile = document.createElement('button');
          btnFile.type = 'button';
          btnFile.className = 'text-left hover:underline';
          btnFile.textContent = file.name || `File ${idx + 1}`;
          btnFile.addEventListener('click', () => setActiveFile(file));
          previewFileList.appendChild(btnFile);
        });
        setActiveFile(files[0]);
      }
    }

    previewModal.classList.remove('hidden');
    previewModal.classList.add('flex');
  });

  previewModal?.querySelectorAll('.btn-close').forEach((btn) => {
    btn.addEventListener('click', closePreview);
  });

  previewModal?.addEventListener('click', (e) => {
    if (e.target === previewModal) closePreview();
  });
  })();
</script>

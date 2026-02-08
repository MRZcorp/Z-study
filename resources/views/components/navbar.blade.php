
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
                                    <p class="text-sm font-semibold text-gray-800">{{ $item->judul }}</p>
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
                                    : asset('img/Logo_Zstudy.png') }}"
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
                                        : asset('img/Logo_Zstudy.png') }}"
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
                        
                        <a href="{{$profil}}" class="block px-4 py-2.5 text-gray-700 hover:bg-blue-50 
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

<script>
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
    const searchInput = searchForm.querySelector('input');
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
        const q = searchInput.value.trim();
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

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchSearch, 250);
    });

    searchInput.addEventListener('focus', () => {
        expandSearch();
        fetchSearch();
    });

    searchBtn.addEventListener('click', (e) => {
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

    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchSearch();
    });

    const notifDropdown = document.getElementById('notifDropdown');
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
</script>

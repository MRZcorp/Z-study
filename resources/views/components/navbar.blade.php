<!-- Premium Professional Navigation Bar -->
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
    <form action="/search" method="GET"
          id="searchForm"
          class="relative flex items-center overflow-hidden
                 w-10 focus-within:w-104
                 transition-all duration-300 ease-in-out">

        <input
            type="text"
            name="q"
            placeholder="Cari..."
            class="h-10 w-full pl-4 pr-10 rounded-full border border-gray-300
                   text-sm opacity-0 focus:opacity-100
                   transition-opacity duration-200
                   focus:outline-none focus:ring-2 focus:ring-blue-500"
        >

        <!-- Google Search Icon -->
        <button type="submit"
                id="searchBtn"
                class="absolute right-2 text-gray-500 hover:text-blue-600">
            <span class="material-symbols-rounded text-[22px]">
                search
            </span>
        </button>
    </form>
</div>

<script>
    const searchBtn = document.getElementById('searchBtn');
    const searchForm = document.getElementById('searchForm');
    const searchInput = searchForm.querySelector('input');

    searchBtn.addEventListener('click', function (e) {
        if (!searchInput.value) {
            e.preventDefault(); // cegah submit saat masih kosong
            searchInput.focus();
        }
    });
</script>

  
                <button class="p-2 text-gray-600 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-colors duration-200 relative">
                    <i class="fas fa-bell"></i>
                    
                    <span class="material-symbols-rounded">Notifications</span>
                    <span class="absolute top-0 right-0 h-2.5 w-2.5 rounded-full bg-red-500 border-2 border-white"></span>
                </button>
  
                <div class="hidden md:block h-6 w-px bg-gray-200"></div>
  
                <div class="dropdown relative">
                    <button class="flex items-center space-x-2 focus:outline-none group">
                        <div class="relative">
                            <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 overflow-hidden avatar-ring">
                                <img src="{{ asset('img/zaky.jpeg') }}" alt="User" class="h-full w-full object-cover">
                            </div>
                            <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 border-2 border-white"></span>
                        </div>
                        <div class="hidden lg:flex flex-col items-start">
                            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors duration-200">M. Zaky Nugraha A R</span>
                            <span class="text-xs text-gray-500">mahaiswa</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-500 hidden lg:inline transition-transform duration-200 group-hover:text-blue-600"></i>
                    </button>
                    <div class="dropdown-menu absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl py-1 z-50 opacity-0 invisible transition-all duration-300 transform -translate-y-2 border border-gray-100">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 overflow-hidden mr-3">
                                    <img src="{{ asset('img/zaky.jpeg') }}" alt="User" class="h-full w-full object-cover">
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">M. Zaky Nugraha A R</p>
                                    <p class="text-sm text-gray-500">2143013</p>
                                </div>
                            </div>
                        </div>
                        <a href="admin" class="block px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center transition-colors duration-200">
                            <span class="material-symbols-rounded">Account_Circle</span>
                             <i class="fas fa-user-circle text-gray-400 mr-3 w-5 text-center"></i>
                             My Profile
                        </a>
                        <a href="dosen" class="block px-4 py-2.5 text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center transition-colors duration-200">
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
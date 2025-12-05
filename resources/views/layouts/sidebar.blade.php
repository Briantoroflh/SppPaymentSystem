<aside id="sidebar" class="bg-base-100 border-base-300 flex flex-col transition-all duration-300 fixed md:fixed top-0 left-0 h-screen z-40 w-20 md:w-72 overflow-hidden shadow-lg" style="transform: translateX(-100%);" data-visible="false">

    <!-- User Card Section -->
    <div class="hidden md:block p-4 border-b-2 border-primary bg-gradient-to-r from-primary/10 to-secondary/10">
        <div class="flex items-center gap-3">
            <div class="avatar">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center flex-shrink-0 ring-2 ring-primary">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Michał" alt="avatar" class="w-full h-full rounded-full" />
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-sm text-primary">{{ Auth::user()->name }}</h3>
                <p class="text-xs text-base-content/60">
                    @if(Auth::user()->hasRole('School Admin'))
                    @php
                    $school = \App\Models\School::where('user_id', Auth::user()->id)->first();
                    @endphp
                    @if($school)
                    <i class="ri-building-line mr-1"></i>{{ $school->name }}
                    @else
                    <span>School Admin</span>
                    @endif
                    @else
                    @php
                    $roles = Auth::user()->getRoleNames();
                    @endphp
                    <i class="ri-user-3-line me-1"></i>{{ $roles->first() ?? 'User' }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Menu Content -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden scrollbar-thin scrollbar-track-base-200 scrollbar-thumb-accent">
        @php
        use App\Helper\MenuAccessHelper;
        $menus = MenuAccessHelper::getGroupedAccessibleMenus();
        $currentPath = request()->path();
        @endphp

        @forelse($menus as $section => $items)
        <div class="px-0 py-4 {{ !$loop->first ? 'border-t border-base-300' : '' }}">
            <h2 class="text-xs font-bold uppercase tracking-wider text-primary px-4 mb-3 hidden md:block">{{ $section }}</h2>
            <ul class="menu menu-compact space-y-2 px-0 w-full">
                @foreach($items as $menu)
                @php
                $isActive = $currentPath === trim($menu->url, '/') || request()->url() === url($menu->url);
                @endphp
                <li>
                    <a href="{{ $menu->url }}" class="flex items-center gap-3 px-4 py-3 rounded-none transition-colors cursor-pointer group mx-2 rounded-lg {{ $isActive ? 'bg-primary/20 border-l-4 border-primary text-primary font-semibold' : 'text-base-content hover:bg-secondar-content/20 hover:text-primary' }}">
                        <i class="{{ $menu->icon }} w-5 h-5 {{ $isActive ? 'text-primary' : 'text-base-content/60 group-hover:text-primary' }} flex-shrink-0"></i>
                        <span class="hidden md:inline text-sm font-medium">{{ $menu->title }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @empty
        <div class="p-4 text-center text-base-content/50">
            <p class="text-sm">No menus available</p>
        </div>
        @endforelse
    </nav>

    <!-- Footer Section (Fixed) -->
    <div class="hidden md:flex border-t-2 border-base-300 bg-gradient-to-t from-base-100 to-transparent p-4 gap-2 flex-shrink-0 h-20">
        <!-- Theme Toggle Dropdown -->
        <div class="dropdown dropdown-top dropdown-end flex-1">
            <button tabindex="0" class="btn btn-sm btn-ghost w-full gap-2 justify-start" aria-label="Toggle theme">
                <svg id="themeIcon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3" stroke="currentColor" stroke-width="2"></line>
                    <line x1="12" y1="21" x2="12" y2="23" stroke="currentColor" stroke-width="2"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" stroke="currentColor" stroke-width="2"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" stroke="currentColor" stroke-width="2"></line>
                    <line x1="1" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="2"></line>
                    <line x1="21" y1="12" x2="23" y2="12" stroke="currentColor" stroke-width="2"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" stroke="currentColor" stroke-width="2"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" stroke="currentColor" stroke-width="2"></line>
                </svg>
                <span class="text-xs font-medium">Theme</span>
            </button>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52 border border-base-300">
                <li><a onclick="setTheme('light')" class="flex gap-2"><i class="ri-sun-line"></i> Light</a></li>
                <li><a onclick="setTheme('dark')" class="flex gap-2"><i class="ri-moon-line"></i> Dark</a></li>
                <li><a onclick="setTheme('system')" class="flex gap-2"><i class="ri-computer-line"></i> System</a></li>
            </ul>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="btn btn-sm btn-error w-full gap-2 justify-start" aria-label="Logout">
                <i class="ri-logout-box-line"></i>
                <span class="text-xs font-medium">Logout</span>
            </button>
        </form>
    </div>

    <!-- Mobile Toggle Button -->
    <button id="sidebarToggleMobile" class="md:hidden fixed bottom-6 right-6 z-30 btn btn-circle btn-primary shadow-xl hover:shadow-2xl transition-all" aria-label="Toggle Sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
    </button>
</aside>
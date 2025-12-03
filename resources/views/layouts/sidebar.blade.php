<aside id="sidebar" class="bg-slate-900 border-slate-700 flex flex-col transition-all duration-300 fixed md:static top-0 left-0 h-screen z-40 w-20 md:w-72 overflow-hidden" style="transform: translateX(-100%);" data-visible="false">

    <!-- User Card Section -->
    <div class="hidden md:block p-4 border-b-2 border-blue-600 bg-gradient-to-r from-blue-600/20 to-indigo-600/20">
        <div class="flex items-center gap-3">
            <div class="avatar">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 ring-2 ring-blue-400">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Michał" alt="avatar" class="w-full h-full rounded-full" />
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-sm text-blue-300">Michał Kowalski</h3>
                <p class="text-xs text-blue-200/70">Session ends in 9m 5s</p>
            </div>
            <button class="btn btn-sm btn-success gap-2 hover:shadow-md transition-all" aria-label="Logout">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 3l7 7m0 0l-7 7m7-7H9" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu Content -->
    <nav class="flex-1 overflow-y-auto scrollbar-hide">
        <!-- BANKING Section -->
        <div class="px-0 py-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-blue-400 px-4 mb-3 hidden md:block">Banking</h2>
            <ul class="menu menu-compact space-y-2 px-0 w-full">
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-none hover:bg-blue-600/30 transition-colors cursor-pointer group mx-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-400 group-hover:text-blue-300 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 13h2v8H3zm4-8h2v16H7zm4-2h2v18h-2zm4 4h2v14h-2zm4-1h2v15h-2z" />
                        </svg>
                        <span class="hidden md:inline text-sm text-slate-200 group-hover:text-blue-300 font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-none hover:bg-purple-600/30 transition-colors cursor-pointer group mx-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-400 group-hover:text-purple-300 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z" />
                        </svg>
                        <span class="hidden md:inline text-sm text-slate-200 group-hover:text-purple-300 font-medium">History</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-none hover:bg-cyan-600/30 transition-colors cursor-pointer group mx-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400 group-hover:text-cyan-300 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2V17zm4 0h-2V7h2V17zm4 0h-2v-4h2V17z" />
                        </svg>
                        <span class="hidden md:inline text-sm text-slate-200 group-hover:text-cyan-300 font-medium">Analysis</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-none bg-green-600/40 border-l-4 border-green-500 transition-colors cursor-pointer group mx-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-400 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z" />
                        </svg>
                        <span class="hidden md:inline text-sm font-semibold text-green-300">Finances</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- SERVICES Section -->
        <div class="px-0 py-4 border-t border-slate-700">
            <h2 class="text-xs font-bold uppercase tracking-wider text-blue-400 px-4 mb-3 hidden md:block">Services</h2>
            <ul class="menu menu-compact space-y-2 px-0 w-full">
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-none hover:bg-orange-600/30 transition-colors cursor-pointer group mx-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-400 group-hover:text-orange-300 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12V12zm0-3H6V7h12v2z" />
                        </svg>
                        <span class="hidden md:inline text-sm text-slate-200 group-hover:text-orange-300 font-medium flex-1">Messages</span>
                        <span class="badge badge-error badge-sm hidden md:inline flex-shrink-0">9</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-none hover:bg-sky-600/30 transition-colors cursor-pointer group mx-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sky-400 group-hover:text-sky-300 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-8-6z" />
                        </svg>
                        <span class="hidden md:inline text-sm text-slate-200 group-hover:text-sky-300 font-medium">Documents</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-none hover:bg-pink-600/30 transition-colors cursor-pointer group mx-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-pink-400 group-hover:text-pink-300 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                        <span class="hidden md:inline text-sm text-slate-200 group-hover:text-pink-300 font-medium flex-1">Products</span>
                        <span class="badge badge-success badge-sm hidden md:inline flex-shrink-0">New</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- OTHER Section -->
        <div class="px-0 py-4 border-t border-slate-700">
            <h2 class="text-xs font-bold uppercase tracking-wider text-blue-400 px-4 mb-3 hidden md:block">Other</h2>
            <ul class="menu menu-compact space-y-2 px-0 w-full">
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-none hover:bg-red-600/30 transition-colors cursor-pointer group mx-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400 group-hover:text-red-300 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                        </svg>
                        <span class="hidden md:inline text-sm text-slate-200 group-hover:text-red-300 font-medium">Help</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-none hover:bg-violet-600/30 transition-colors cursor-pointer group mx-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-400 group-hover:text-violet-300 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l1.72-1.34c.15-.12.19-.34.1-.51l-1.63-2.83c-.12-.22-.38-.29-.59-.22l-2.03.81c-.42-.32-.9-.6-1.44-.79l-.31-2.15c-.05-.24-.24-.41-.48-.41h-3.27c-.24 0-.43.17-.49.41l-.31 2.15c-.54.19-1.02.47-1.44.79l-2.03-.81c-.21-.09-.47-.02-.59.22L2.74 8.87c-.09.17-.05.39.1.51l1.72 1.34c-.05.3-.07.62-.07.94s.02.64.07.94l-1.72 1.34c-.15.12-.19.34-.1.51l1.63 2.83c.12.22.38.29.59.22l2.03-.81c.42.32.9.6 1.44.79l.31 2.15c.05.24.24.41.48.41h3.27c.24 0 .43-.17.49-.41l.31-2.15c.54-.19 1.02-.47 1.44-.79l2.03.81c.21.09.47.02.59-.22l1.63-2.83c.09-.17.05-.39-.1-.51l-1.72-1.34zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" />
                        </svg>
                        <span class="hidden md:inline text-sm text-slate-200 group-hover:text-violet-300 font-medium">Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<!-- Mobile Toggle Button -->
<button id="sidebarToggleMobile" class="md:hidden fixed bottom-6 right-6 z-30 btn btn-circle btn-blue shadow-xl hover:shadow-2xl transition-all" aria-label="Toggle Sidebar">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="6" x2="21" y2="6" />
        <line x1="3" y1="12" x2="21" y2="12" />
        <line x1="3" y1="18" x2="21" y2="18" />
    </svg>
</button>
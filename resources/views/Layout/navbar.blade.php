<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden transition-opacity duration-300"
    onclick="toggleSidebar()"></div>

<nav
    class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 md:px-6 dark:bg-[#1C2B33] dark:border-[#2C3E48]">

    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-[#2C3E48]">
            <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex justify-center items-center">
            <img src="{{ asset('image/motodo.png') }}" alt="Motodo Logo" class="h-12 rounded-lg ">
        </a>
    </div>

    <div class="flex items-center gap-3">

        <div class="flex items-center gap-2 px-3 py-2 bg-gray-100 rounded-lg dark:bg-[#2C3E48]">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=0082FB&color=fff"
                alt="User" class="w-8 h-8 rounded-full object-cover">
            <span class="hidden md:block font-medium text-gray-700 dark:text-gray-300">
                {{ auth()->user()->name ?? 'User' }}
            </span>
        </div>
    </div>
</nav>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.add('opacity-100'), 10);
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.remove('opacity-100');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }

    window.addEventListener('resize', () => {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
        }
    });
</script>

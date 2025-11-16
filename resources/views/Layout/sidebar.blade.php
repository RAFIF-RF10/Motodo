<div id="overlay"
    class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden transition-opacity duration-300 md:hidden"
    onclick="toggleSidebar()"></div>

<aside id="sidebar"
    class="fixed top-0 left-0 z-40 h-screen pt-20 bg-white transition-all duration-300 -translate-x-full md:translate-x-0 md:w-64 dark:bg-[#1C2B33]">
    <div class="h-full px-4 pb-4 overflow-y-auto">

        <div class="space-y-1 py-4">
            {{-- 🔹 Menu Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 transition-all duration-200 group
                {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-500/30' : '' }}
                dark:text-gray-300 dark:hover:bg-[#2C3E48]">
                <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                    {{ svg('ri-dashboard-line', 'w-5 h-5') }}
                </div>
                <span class="font-medium sidebar-text whitespace-nowrap">Dashboard</span>
            </a>

            @if (auth()->user()->role->name === 'Admin')
                <a href="{{ route('todolist.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 transition-all duration-200 group
                    {{ request()->routeIs('todolist.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-500/30' : '' }}
                    dark:text-gray-300 dark:hover:bg-[#2C3E48]">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        {{ svg('ri-todo-line', 'w-5 h-5') }}
                    </div>
                    <span class="font-medium sidebar-text whitespace-nowrap">Todo Lists</span>
                </a>

                <a href="{{ route('teacher.submission.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 transition-all duration-200 group
                    {{ request()->routeIs('students.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-500/30' : '' }}
                    dark:text-gray-300 dark:hover:bg-[#2C3E48]">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        {{ svg('hugeicons-students', 'w-5 h-5') }}
                    </div>
                    <span class="font-medium sidebar-text whitespace-nowrap">Kelola Tugas Siswa</span>
                </a>

                {{-- <a href="{{ route('reports.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 transition-all duration-200 group
                    {{ request()->routeIs('reports.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-500/30' : '' }}
                    dark:text-gray-300 dark:hover:bg-[#2C3E48]">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        {{ svg('ri-file-list-line', 'w-5 h-5') }}
                    </div>
                    <span class="font-medium sidebar-text whitespace-nowrap">Laporan Tugas</span>
                </a> --}}
            @endif

            {{-- 🔹 Menu untuk SISWA --}}
            @if (auth()->user()->role->name === 'User')
                <a href="{{ route('student.todo.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 transition-all duration-200 group
                    {{ request()->routeIs('student.tasks.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-500/30' : '' }}
                    dark:text-gray-300 dark:hover:bg-[#2C3E48]">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        {{ svg('ri-todo-line', 'w-5 h-5') }}
                    </div>
                    <span class="font-medium sidebar-text whitespace-nowrap">Tugas Saya</span>
                    @if (isset($pendingTasks) && $pendingTasks > 0)
                        <span
                            class="sidebar-badge bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $pendingTasks }}</span>
                    @endif
                </a>

                <a href="{{ route('student.submissions.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 transition-all duration-200 group
                    {{ request()->routeIs('student.submissions.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-500/30' : '' }}
                    dark:text-gray-300 dark:hover:bg-[#2C3E48]">
                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                        {{ svg('ri-file-list-line', 'w-5 h-5') }}
                    </div>
                    <span class="font-medium sidebar-text whitespace-nowrap">Pengumpulan Saya</span>
                </a>
            @endif

            {{-- 🔹 Tombol Logout --}}
            <div class="pt-4 mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-4 px-4 py-3 text-red-600 rounded-xl hover:bg-red-50 transition-all duration-200 dark:text-red-400 dark:hover:bg-[#2C3E48]">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            {{ svg('ri-logout-box-line', 'w-5 h-5') }}
                        </div>
                        <span class="font-medium sidebar-text whitespace-nowrap">Logout</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</aside>

<script>
    let isCollapsed = false;

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const isMobile = window.innerWidth < 768;

        if (isMobile) {
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        } else {
            isCollapsed = !isCollapsed;
            const texts = document.querySelectorAll('.sidebar-text');
            const badges = document.querySelectorAll('.sidebar-badge');

            if (isCollapsed) {
                sidebar.classList.remove('md:w-64');
                sidebar.classList.add('md:w-20');
                texts.forEach(t => t.classList.add('md:hidden'));
                badges.forEach(b => b.classList.add('md:hidden'));
            } else {
                sidebar.classList.remove('md:w-20');
                sidebar.classList.add('md:w-64');
                texts.forEach(t => t.classList.remove('md:hidden'));
                badges.forEach(b => b.classList.remove('md:hidden'));
            }
        }
    }

    window.addEventListener('resize', () => {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const texts = document.querySelectorAll('.sidebar-text');
        const badges = document.querySelectorAll('.sidebar-badge');

        if (window.innerWidth >= 768) {
            overlay.classList.add('hidden');
            sidebar.classList.remove('-translate-x-full');
            if (isCollapsed) {
                sidebar.classList.add('md:w-20');
                texts.forEach(t => t.classList.add('md:hidden'));
                badges.forEach(b => b.classList.add('md:hidden'));
            } else {
                sidebar.classList.add('md:w-64');
                texts.forEach(t => t.classList.remove('md:hidden'));
                badges.forEach(b => b.classList.remove('md:hidden'));
            }
        } else {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('md:w-20', 'md:w-64');
            texts.forEach(t => t.classList.remove('md:hidden'));
            badges.forEach(b => b.classList.remove('md:hidden'));
        }
    });
</script>

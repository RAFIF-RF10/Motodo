@extends('layout.app')

@section('content')
    <div class="p-6 mt-16 space-y-8 max-w-7xl mx-auto">

        <div
            class="relative rounded-3xl overflow-hidden shadow-xl border border-white/20 bg-gradient-to-r from-[#0064E0] to-[#0082FB] text-white p-10">
            <div class="relative z-10">
                <h1 class="text-4xl font-black mb-2 tracking-tight">Daftar Tugas Saya</h1>
                <p class="text-white dark:text-gray-400 text-md">
                    Kelola dan kerjakan semua tugas yang diberikan oleh guru.
                </p>
            </div>
            <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none select-none">
                {{ svg('heroicon-s-academic-cap', 'w-64 h-64') }}
            </div>
        </div>


        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="flex flex-col items-start gap-1 sm:w-40">
                <label for="sort" class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Urutan</label>
                <select id="sort"
                    class="border-b border-gray-300 dark:border-gray-700 bg-transparent px-0 py-2 text-base focus:ring-0 focus:border-[#0082FB] dark:text-gray-200 w-full outline-none">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>

            <div class="flex flex-col gap-1 flex-1">
                <label for="searchInput" class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Cari
                    Tugas</label>
                <input id="searchInput" type="text" placeholder="Cari tugas berdasarkan judul..."
                    class="w-full px-0 py-2 border-b border-gray-300 bg-transparent text-base focus:ring-0 focus:border-[#0082FB] dark:text-gray-200 outline-none">
            </div>
        </div>

        <div id="taskContainer" class="flex flex-col gap-6">
            @forelse($lists as $list)
                @php
                    $timestamp = $list->created_at->timestamp;
                    $progress =
                        isset($list->submissions_completed) &&
                        isset($list->submissions_total) &&
                        $list->submissions_total > 0
                            ? ($list->submissions_completed / $list->submissions_total) * 100
                            : 0;
                @endphp

                <a href="{{ route('student.todo.show', $list->id) }}"
                    class="task-card group flex flex-col sm:flex-row items-stretch bg-white dark:bg-[#2D3748] rounded-xl shadow-md hover:shadow-lg transition-all border border-gray-100 dark:border-gray-700/50 overflow-hidden"
                    data-title="{{ $list->title }}" data-timestamp="{{ $timestamp }}"
                    data-progress="{{ $progress }}">
                    <div class="flex items-center justify-center sm:w-36  bg-[#0082FB]/10 dark:bg-[#0082FB]/20">
                        @if ($list->image)
                            <img src="{{ asset('storage/' . $list->image) }}" alt="Ilustrasi Tugas"
                                class="w-full h-full opacity-80 object-cover">
                        @else
                            <img src="{{ asset('images/default.png') }}" alt="Default"
                                class="w-full h-full opacity-60 rounded-md">
                        @endif

                    </div>

                    <div class="flex-1 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h3
                                    class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-[#0082FB] transition-colors line-clamp-1">
                                    {{ $list->title }}
                                </h3>
                                <span
                                    class="inline-flex items-center flex-shrink-0 gap-1 px-3 py-1 bg-[#0082FB]/10 text-[#0064E0] dark:bg-[#0064E0]/30 dark:text-blue-100 text-xs font-semibold rounded-full ml-3">
                                    {{ $list->tasks_count ?? $list->tasks->count() }} Tugas
                                </span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                {{ $list->description ?? 'Tanpa deskripsi' }}
                            </p>
                        </div>

                        @if (isset($list->submissions_completed) && isset($list->submissions_total))
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    <span>Progress</span>
                                    <span>{{ $list->submissions_completed }} / {{ $list->submissions_total }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-gradient-to-r from-[#0082FB] to-[#0064E0] h-1.5 rounded-full transition-all duration-500"
                                        style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div
                            class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-700/50">
                            <span class="flex items-center justify-center gap-1">
                                {{ svg('heroicon-c-calendar-date-range', 'w-6 h-6') }}
                                {{ $list->created_at->format('d M Y') }}
                            </span>
                            <span class="text-[#0064E0] font-semibold flex items-center gap-1">
                                Lihat
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div id="noTasksFound"
                    class="bg-white dark:bg-[#1E293B] rounded-2xl p-10 text-center border border-gray-200 dark:border-gray-700/50">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada tugas</h3>
                    <p class="text-gray-600 dark:text-gray-400">Guru Anda akan memberikan tugas segera. Silakan cek kembali
                        nanti.</p>
                </div>
            @endforelse
        </div>

        <div id="filterEmptyState"
            class="hidden bg-white flex justify-center  text-centerflex-col align-items-center dark:bg-[#1E293B] rounded-2xl p-10 text-center border border-gray-200 dark:border-gray-700/50">
            <div class="text-4xl text-center justify-center items-center mb-4">{{ svg('heroicon-s-magnifying-glass', 'w-10 h-10 text-blue-500') }}</div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak ada tugas ditemukan</h3>
            <p class="text-gray-600 dark:text-gray-400">Coba kata kunci pencarian atau urutan lain.</p>
        </div>
    </div>

   <script>
   document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sort');
    const taskContainer = document.getElementById('taskContainer');
    const cards = Array.from(taskContainer.querySelectorAll('.task-card'));
    const noTasksDefault = document.getElementById('noTasksFound');
    const filterEmptyState = document.getElementById('filterEmptyState');

    function filterAndSortTasks() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const sortOrder = sortSelect.value;

        let visibleCards = [];

        cards.forEach(card => {
            const title = card.dataset.title.toLowerCase();
            if (title.includes(searchTerm)) {
                card.style.display = ''; // pakai default display
                visibleCards.push(card);
            } else {
                card.style.display = 'none';
            }
        });

        // Sorting
        visibleCards.sort((a, b) => {
            const timeA = parseInt(a.dataset.timestamp);
            const timeB = parseInt(b.dataset.timestamp);
            return sortOrder === 'desc' ? timeB - timeA : timeA - timeB;
        });

        // Re-append sorted cards
        visibleCards.forEach(card => taskContainer.appendChild(card));

        // Atur empty state
        if (visibleCards.length === 0) {
            if (noTasksDefault) noTasksDefault.classList.add('hidden');
            filterEmptyState.classList.remove('hidden');
        } else {
            filterEmptyState.classList.add('hidden');
            if (noTasksDefault) noTasksDefault.classList.add('hidden');
        }
    }

    let timeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(filterAndSortTasks, 200);
    });

    sortSelect.addEventListener('change', filterAndSortTasks);

    filterAndSortTasks();
});

</script>

@endsection

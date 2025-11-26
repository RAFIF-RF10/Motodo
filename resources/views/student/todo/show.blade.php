@extends('layout.app')

@section('content')
    <div class="p-6 mt-2 space-y-6">
        <div
        class="relative rounded-3xl overflow-hidden shadow-xl border border-white/20 bg-gradient-to-r from-[#0064E0] to-[#0082FB] text-white p-10">
        <a href="{{ route('student.todo.index') }}"
            class="inline-flex items-center gap-2 text-white hover:text-slate-200 dark:text-blue-400 dark:hover:text-blue-300 mb-4 group transition-all">
            {{ svg('heroicon-o-arrow-small-left','w-8 h-8 group-hover:-translate-x-1 transition-transform') }}
            <span class="font-medium">Kembali</span>
        </a>
        <div class="relative z-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-white mb-3">
                {{ $list->title }}
            </h1>
            <p class="text-gray-200 dark:text-gray-300 text-lg">
                {{ $list->description ?? 'Tanpa deskripsi' }}
            </p>
        </div>
        <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none select-none">
            {{ svg('heroicon-s-academic-cap', 'w-64 h-64') }}
        </div>
    </div>

    <!-- Search + Sort -->
    <div class="mt-4 flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Cari Tugas</label>
            <input id="taskSearch" type="text" placeholder="Cari tugas berdasarkan judul..."
                class="w-full px-0 py-2 border-b border-gray-300 bg-transparent text-base focus:ring-0 focus:border-[#0082FB] dark:text-gray-200 outline-none">
        </div>

        <div class="w-full md:w-48">
            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Urutkan</label>
            <select id="taskSort"
                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:ring-0 focus:border-[#0082FB]">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
            </select>
        </div>
    </div>

    <!-- Tasks List -->
    <div id="taskContainer" class="flex flex-col gap-4">
        @forelse($tasks as $task)
            @php
                $submission = $task->submissions->first();
                $statusName = strtolower($submission->status->name ?? '');
                $priorityColor = match ($task->priority?->name) {
                    'High', 'Tinggi' => 'border-red-500',
                    'Medium', 'Sedang' => 'border-yellow-500',
                    default => 'border-green-500',
                };
            @endphp

            <a href="{{ route('student.task.show', $task->id) }}"
                class="task-card flex flex-col md:flex-row items-start md:items-center justify-between bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-lg transition-all border-l-4 {{ $priorityColor }} overflow-hidden group"
                data-title="{{ strtolower($task->title) }}"
                data-deadline="{{ $task->deadline ?? '9999-12-31' }}">

                <div class="flex-1 p-5 space-y-2">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                        <span
                            class="px-2 py-0.5 text-xs rounded-full font-semibold
                            @if ($submission) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif">
                            {{ $submission ? 'Dikumpulkan' : 'Belum' }}
                        </span>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">
                        {{ $task->description ?? '-' }}
                    </p>

                    <div class="flex flex-wrap gap-2">
                        <span
                            class="px-2 py-0.5 rounded-full text-xs font-semibold
                            @if ($task->priority?->name === 'High' || $task->priority?->name === 'Tinggi') bg-red-100 text-red-700
                            @elseif($task->priority?->name === 'Medium' || $task->priority?->name === 'Sedang') bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ $task->priority?->name ?? 'Normal' }}
                        </span>

                        @if (!$submission)
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Belum Dikerjakan
                            </span>
                        @else
                            @switch($statusName)
                                @case('pending')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">Sudah Dikumpulkan</span>
                                @break

                                @case('in progress')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">Sedang Dinilai</span>
                                @break

                                @case('completed')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">Selesai</span>
                                @break

                                @default
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">Tidak Diketahui</span>
                            @endswitch
                        @endif
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Deadline: <strong class="text-gray-800 dark:text-gray-200">
                            {{ $task->deadline ? date('d M Y', strtotime($task->deadline)) : 'Tidak ada' }}
                        </strong>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900 w-full md:w-32 flex items-center justify-center md:flex-col md:justify-between p-4">
                    <span class="text-xs text-gray-500 dark:text-gray-400 md:mb-2">Lihat Detail</span>
                    <span class="text-blue-600 dark:text-blue-400 text-2xl font-semibold group-hover:translate-x-1 transition-transform">→</span>
                </div>
            </a>

        @empty
            <div id="defaultEmpty" class="bg-gray-50 dark:bg-gray-800 rounded-xl p-10 text-center">
                <div class="text-6xl flex align-items-center justify-center mb-4">
                    <img src="{{ asset('image/default/noTask.png') }}" width="500px" alt="">
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada tugas</h3>
                <p class="text-gray-600 dark:text-gray-400">Guru akan menambahkan tugas untuk kategori ini segera.</p>
            </div>
        @endforelse
    </div>

    <div id="searchEmpty" class="hidden bg-gray-50 dark:bg-gray-800 rounded-xl p-10 text-center">
        <div class="text-5xl mb-3">🔍</div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Tidak ada tugas ditemukan</h3>
        <p class="text-gray-600 dark:text-gray-400">Coba kata kunci lain ya.</p>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('taskSearch');
    const sort = document.getElementById('taskSort');
    const container = document.getElementById('taskContainer');
    const cards = Array.from(container.querySelectorAll('.task-card'));
    const searchEmpty = document.getElementById('searchEmpty');
    const defaultEmpty = document.getElementById('defaultEmpty');

    function runFilter() {
        const keyword = input.value.trim().toLowerCase();
        let visibleCount = 0;

        cards.forEach(card => {
            const title = card.dataset.title.toLowerCase();
            const match = title.includes(keyword);
            card.style.display = match ? '' : 'none'; // pakai default display
            if (match) visibleCount++;
        });

        if (defaultEmpty) defaultEmpty.style.display = cards.length === 0 ? 'block' : 'none';
        searchEmpty.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    function runSort() {
        const mode = sort.value;
        const sorted = cards.sort((a, b) => {
            const da = new Date(a.dataset.deadline);
            const db = new Date(b.dataset.deadline);
            return mode === 'newest' ? db - da : da - db;
        });
        sorted.forEach(card => container.appendChild(card));
    }

    input.addEventListener('input', runFilter);
    sort.addEventListener('change', () => {
        runSort();
        runFilter();
    });

    runSort();
    runFilter();
});

</script>

@endsection

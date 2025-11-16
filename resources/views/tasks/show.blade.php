@extends('Layout.app')

@section('content')
    <div class="p-6 md:p-10 mt-16">

        {{-- HEADER --}}
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-bold text-[#1C2B33] dark:text-white mb-2">{{ $task->title }}</h1>
                <p class="text-gray-600 dark:text-gray-300 text-lg">{{ $task->description }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('tasks.edit', ['todolist' => $task->todo_list_id, 'task' => $task->id]) }}"
                    class="px-5 py-2.5 rounded-xl bg-[#FBBF24] hover:bg-[#F59E0B] text-white font-medium shadow transition flex items-center gap-1">
                    <i class="ri-edit-line"></i> Edit
                </a>

                <form action="{{ route('tasks.destroy', ['todolist' => $task->todo_list_id, 'task' => $task->id]) }}"
                    method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                    @csrf
                    @method('DELETE')
                    <button
                        class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium shadow transition flex items-center gap-1">
                        <i class="ri-delete-bin-6-line"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- STATUS ALERT --}}
        <div class="mb-6">
            @php $statusName = $task->status?->name ?? 'Belum'; @endphp

            @if ($statusName === 'Completed')
                <div
                    class="bg-green-100 text-green-800 border border-green-300 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="ri-checkbox-circle-line text-xl"></i> Tugas ini sudah <b>selesai</b>.
                </div>
            @elseif($statusName === 'Ditolak')
                <div class="bg-red-100 text-red-800 border border-red-300 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="ri-close-circle-line text-xl"></i> Tugas ini <b>ditolak</b>.
                </div>
            @else
                <div
                    class="bg-yellow-100 text-yellow-800 border border-yellow-300 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="ri-time-line text-xl"></i> Tugas ini masih <b>menunggu pengumpulan</b>.
                </div>
            @endif
        </div>

        {{-- DETAIL GRID --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="bg-white dark:bg-[#1C2B33] p-5 rounded-xl shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm">Deadline</p>
                <p class="text-lg font-semibold">
                    {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : 'Tidak ada' }}
                </p>
            </div>

            <div class="bg-white dark:bg-[#1C2B33] p-5 rounded-xl shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm">Prioritas</p>
                <p class="text-lg font-semibold">{{ $task->priority?->name ?? 'Tidak ada' }}</p>
            </div>

            <div class="bg-white dark:bg-[#1C2B33] p-5 rounded-xl shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm">Status</p>
                <p class="text-lg font-semibold">{{ $statusName }}</p>
            </div>

            <div class="bg-white dark:bg-[#1C2B33] p-5 rounded-xl shadow-sm border border-gray-200">
                <p class="text-gray-500 text-sm">Jumlah Siswa</p>
                <p class="text-lg font-semibold">{{ $task->detail?->assigned_user_count ?? 0 }} siswa</p>
            </div>
        </div>

        {{-- DESKRIPSI LENGKAP --}}
        @if ($task->detail?->long_description)
            <div class="bg-[#E8F1FF] dark:bg-blue-900/30 p-6 rounded-xl shadow-sm mb-10 border border-blue-200">
                <h3 class="font-semibold text-lg text-[#0064E0] dark:text-blue-300 mb-2">Deskripsi Lengkap</h3>
                <p class="text-gray-700 dark:text-gray-200 whitespace-pre-wrap leading-relaxed">
                    {{ $task->detail->long_description }}
                </p>
            </div>
        @endif

        {{-- PENGUMPULAN --}}
        <div class="bg-white dark:bg-[#1C2B33] rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">

            {{-- HEADER LIST --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Daftar Pengumpulan</h2>

                <div class="flex items-center gap-3">

                    {{-- Search --}}
                    <input type="text" id="searchInput" placeholder="Cari siswa..."
                        class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#0082FB] outline-none transition">

                    {{-- Filter --}}
                    <select id="statusFilter"
                        class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#0082FB] outline-none transition">
                        <option value="">Semua Status</option>
                        <option value="Belum">Belum Mengumpul</option>
                        <option value="On Progress">On Progress</option>
                        <option value="Completed">Completed</option>
                    </select>

                    {{-- EXPORT EXCEL --}}
                    <a
                        href="{{ route('tasks.export', [
                            'todoListId' => $task->todo_list_id,
                            'taskId' => $task->id,
                        ]) }}">
                        Export
                    </a>

                </div>
            </div>

            {{-- LEGEND --}}
            <div class="flex gap-5 flex-wrap mb-6 text-sm">
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-red-500"></span> Belum</div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-yellow-400"></span> On Progress
                </div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-green-500"></span> Completed</div>
            </div>

            {{-- TABLE --}}
            @if ($task->submissions->count())
                <div class="overflow-x-auto border rounded-xl">
                    <table class="w-full text-sm">
                        <thead class="bg-[#E8F1FF] dark:bg-blue-900/40">
                            <tr>
                                <th class="px-4 py-3 text-left">Siswa</th>
                                <th class="px-4 py-3 text-left">File</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                            </tr>
                        </thead>

                        <tbody id="studentTableBody">
                            @foreach ($task->submissions as $sub)
                                <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                                    <td class="px-4 py-3 font-medium student-name">{{ $sub->user->name }}</td>

                                    <td class="px-4 py-3">
                                        @if ($sub->file_path)
                                            <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank"
                                                class="text-[#0064E0] hover:underline">Download</a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        @php
                                            $status = $sub->status?->name ?? 'Belum';
                                            $color =
                                                [
                                                    'Completed' => 'bg-green-200 text-green-800',
                                                    'On Progress' => 'bg-yellow-200 text-yellow-800',
                                                    'Belum' => 'bg-red-200 text-red-800',
                                                ][$status] ?? 'bg-red-200 text-red-800';
                                        @endphp

                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium student-status {{ $color }}">
                                            {{ $status }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">{{ $sub->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
                    Belum ada pengumpulan.
                </p>
            @endif
        </div>

        {{-- Back --}}
        <div class="mt-6">
            <a href="{{ route('todolist.show', $task->todo_list_id) }}"
                class="inline-flex items-center text-[#0064E0] hover:underline">
                <i class="ri-arrow-left-line mr-1"></i> Kembali ke TodoList
            </a>
        </div>
    </div>


    {{-- SEARCH & FILTER --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const rows = document.querySelectorAll('#studentTableBody tr');

            function runFilter() {
                const search = searchInput.value.toLowerCase();
                const filter = statusFilter.value;

                rows.forEach(row => {
                    const name = row.querySelector('.student-name').textContent.toLowerCase();
                    const status = row.querySelector('.student-status').textContent.trim();

                    const matchName = name.includes(search);
                    const matchStatus = !filter || filter === status;

                    row.style.display = (matchName && matchStatus) ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', runFilter);
            statusFilter.addEventListener('change', runFilter);
        });
    </script>

@endsection

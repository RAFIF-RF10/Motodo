@extends('Layout.app')

@section('content')

<head>
    <meta name="turbo-cache-control" content="no-cache">
</head>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 md:p-6 lg:p-10 mt-16">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Back Button -->
        <div>
            <a href="{{ route('todolist.show', $task->todo_list_id) }}"
                class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 font-medium transition-colors group">
                {{ svg('heroicon-m-arrow-small-left', 'h-5 w-5 group-hover:-translate-x-1 transition-transform') }}
                <span>Kembali</span>
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            <!-- Header Section -->
            <div class="p-6 md:p-8 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-3">
                            {{ $task->title }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 text-base md:text-lg leading-relaxed">
                            {{ $task->description }}
                        </p>
                    </div>

                    <div class="flex gap-2 flex-shrink-0">
                        <a href="{{ route('tasks.edit', ['todolist' => $task->todo_list_id, 'task' => $task->id]) }}"
                            class="px-4 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-medium transition-colors flex items-center gap-2 shadow-sm">
                            <i class="ri-edit-line text-lg"></i>
                            <span>Edit</span>
                        </a>

                        <form action="{{ route('tasks.destroy', ['todolist' => $task->todo_list_id, 'task' => $task->id]) }}"
                            method="POST" onsubmit="return confirmDelete(event)" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2.5 rounded-lg bg-red-500 hover:bg-red-600 text-white font-medium transition-colors flex items-center gap-2 shadow-sm">
                                <i class="ri-delete-bin-6-line text-lg"></i>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Status Alert -->
            <div class="p-6 md:p-8 border-b border-gray-200 dark:border-gray-700">
                @php $statusName = $task->status?->name ?? 'Belum'; @endphp

                @if ($statusName === 'Completed')
                    <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 px-5 py-4 rounded-r-lg">
                        <div class="flex items-center gap-3">
                            <i class="ri-checkbox-circle-line text-2xl text-green-600 dark:text-green-400"></i>
                            <span class="text-green-800 dark:text-green-200 font-medium">
                                Tugas ini sudah <strong>selesai</strong>
                            </span>
                        </div>
                    </div>
                @elseif($statusName === 'Ditolak')
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 px-5 py-4 rounded-r-lg">
                        <div class="flex items-center gap-3">
                            <i class="ri-close-circle-line text-2xl text-red-600 dark:text-red-400"></i>
                            <span class="text-red-800 dark:text-red-200 font-medium">
                                Tugas ini <strong>ditolak</strong>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 px-5 py-4 rounded-r-lg">
                        <div class="flex items-center gap-3">
                            <i class="ri-time-line text-2xl text-amber-600 dark:text-amber-400"></i>
                            <span class="text-amber-800 dark:text-amber-200 font-medium">
                                Tugas ini masih <strong>menunggu pengumpulan</strong>
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Info Cards -->
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <!-- Deadline Card -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-11 h-11 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                                {{ svg('heroicon-o-clock', 'w-6 h-6 text-blue-600 dark:text-blue-400') }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Deadline
                                </p>
                                <p class="font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : 'Tidak ada' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Priority Card -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-600 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-11 h-11 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center flex-shrink-0">
                                {{ svg('heroicon-s-flag', 'w-6 h-6 text-purple-600 dark:text-purple-400') }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Prioritas
                                </p>
                                <p class="font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $task->priority?->name ?? 'Tidak ada' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-green-300 dark:hover:border-green-600 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-11 h-11 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center flex-shrink-0">
                                {{ svg('heroicon-o-information-circle', 'w-6 h-6 text-green-600 dark:text-green-400') }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Status
                                </p>
                                <p class="font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $statusName }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Student Count Card -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-cyan-300 dark:hover:border-cyan-600 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-11 h-11 rounded-lg bg-cyan-100 dark:bg-cyan-900/40 flex items-center justify-center flex-shrink-0">
                                {{ svg('heroicon-o-users', 'w-6 h-6 text-cyan-600 dark:text-cyan-400') }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Jumlah Siswa
                                </p>
                                <p class="font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $task->detail?->assigned_user_count ?? 0 }} siswa
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Long Description -->
        @if ($task->detail?->long_description)
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="ri-file-text-line text-xl text-blue-600 dark:text-blue-400"></i>
                    Deskripsi Lengkap
                </h3>
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                    {{ $task->detail->long_description }}
                </div>
            </div>
        @endif

        <!-- Detail Soal -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="ri-attachment-line text-xl text-blue-600 dark:text-blue-400"></i>
                Detail Soal
            </h3>
            @if ($task->image)
                <a href="{{ asset('storage/' . $task->image) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg font-medium transition-colors">
                    <x-heroicon-o-eye class="w-5 h-5" />
                    <span>Lihat File</span>
                </a>
            @else
                <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada file</p>
            @endif
        </div>

        <!-- Submissions Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

            <!-- Table Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Pengumpulan</h2>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <input type="text" id="searchInput" placeholder="Cari siswa..."
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">

                        <select id="statusFilter"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                            <option value="">Semua Status</option>
                            <option value="Belum">Belum Mengumpul</option>
                            <option value="On Progress">On Progress</option>
                            <option value="Completed">Completed</option>
                        </select>

                        <a href="{{ route('tasks.export', ['todoListId' => $task->todo_list_id, 'taskId' => $task->id]) }}"
                            class="px-4 py-2 rounded-lg bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white font-medium transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <i class="ri-download-line"></i>
                            <span>Export</span>
                        </a>
                    </div>
                </div>

                <!-- Status Legend -->
                <div class="flex flex-wrap gap-4 mt-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-gray-700 dark:text-gray-300">Belum</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="text-gray-700 dark:text-gray-300">On Progress</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="text-gray-700 dark:text-gray-300">Completed</span>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            @if ($task->submissions->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700 dark:text-gray-300">Nama</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700 dark:text-gray-300">File</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700 dark:text-gray-300">Status</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700 dark:text-gray-300">Tanggal</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-700 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="studentTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($task->submissions as $sub)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white student-name">
                                        {{ $sub->user->name }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($sub->file_path)
                                            <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition">
                                                <i class="ri-download-2-line"></i>
                                                Download
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @php
                                            $status = $sub->status?->name ?? 'Belum';
                                            $color = [
                                                'Completed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                'On Progress' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                                'Belum' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                            ][$status] ?? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                                        @endphp

                                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-medium student-status {{ $color }}">
                                            {{ $status }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $sub->created_at->format('d M Y H:i') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($sub->status->name !== 'Completed')
                                            <div class="flex justify-center gap-2">
                                                <button
                                                    onclick="confirmAction('{{ route('teacher.submission.setStatusTask', $sub->id) }}', 'Completed')"
                                                    class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 transition-colors"
                                                    title="Tandai Selesai">
                                                    {{ svg('heroicon-s-check-circle', 'w-7 h-7') }}
                                                </button>

                                                <button
                                                    onclick="confirmAction('{{ route('teacher.submission.setStatusTask', $sub->id) }}', 'Pending')"
                                                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                    title="Kembalikan ke Pending">
                                                    {{ svg('heroicon-s-x-circle', 'w-7 h-7') }}
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex justify-center">
                                                <span class="inline-flex items-center gap-1 text-gray-400 dark:text-gray-600 text-xs font-medium">
                                                    <i class="ri-checkbox-circle-fill text-green-500"></i>
                                                    Selesai
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16">
                    <i class="ri-inbox-line text-5xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada pengumpulan</p>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection

@push('scripts')
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

    window.confirmDelete = function(e) {
        e.preventDefault();
        const form = e.target.closest('form');

        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data ini tidak bisa dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed && form) {
                form.submit();
            }
        });
    }

    function confirmAction(url, status) {
        Swal.fire({
            title: 'Ubah Status?',
            text: "Yakin ingin mengubah status menjadi " + status + "?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0064E0',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrf = document.createElement('input');
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const input = document.createElement('input');
                input.name = 'status';
                input.value = status;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

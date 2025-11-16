@extends('layout.app')

@section('content')
    <section class="p-6 mt-20">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Detail Todo : {{ $list->title }} </h1>
            <a href="{{ route('todolist.index') }}"
                class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition flex items-center gap-1">
                {{ svg('letsicon-back')->class('w-5 h-5') }} Kembali
            </a>
        </div>

        <div class="mb-4">
            <p class="text-gray-600 dark:text-gray-400">
                <strong>Tanggal dibuat:</strong> {{ $list->created_at->format('d M Y') }}
            </p>
        </div>

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Daftar Task</h2>
            <a href="{{ route('tasks.create', ['todolist' => $list->id]) }}"
                class="btn hover:bg-[#0064e0] font-bold  text-xl p-2.5 text-white rounded-lg bg-[#0082fb] transition">+
                Tambah Task</a>
        </div>

        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 mb-6">

            <div class="flex-grow">
                <input id="searchInput" type="text" placeholder="Cari task berdasarkan judul..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:text-gray-200">
            </div>

            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex flex-col items-start gap-2">
                    <label for="sort" class="text-gray-600 dark:text-gray-300 font-medium text-sm">URUTAN</label>
                    <select id="sort" name="sort"
                        class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0082fb] dark:bg-gray-800 dark:text-gray-200 w-full">
                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                        <option value="asc" {{ request('sort', 'desc') == 'asc' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>

                <div class="flex items-start flex-col gap-2">
                    <label for="date" class="text-gray-600 dark:text-gray-300 font-medium text-sm">TANGGAL</label>
                    <input type="date" id="date" name="date" value="{{ request('date') }}"
                        class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0082fb] dark:bg-gray-800 dark:text-gray-200 w-full">
                </div>
            </div>

        </div>

        <div
            class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 relative">

            <div id="loadingOverlay"
                class="hidden absolute inset-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm z-10 flex items-center justify-center">
                <div class="flex flex-col items-center gap-3">
                    <div role="status">
                        <svg aria-hidden="true"
                            class="w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 font-medium">Memuat data...</p>
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="taskTable">
                <thead class="bg-[#0064e0] dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">#</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">Judul</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">Deskripsi</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-white">Prioritas</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-white">Deadline</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-white">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($tasks as $index => $task)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $task->title }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $task->description ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($task->priority?->name === 'Easy' || $task->priority?->name === 'Rendah') bg-green-100 text-green-700
                                    @elseif($task->priority?->name === 'Medium' || $task->priority?->name === 'Sedang') bg-yellow-100 text-yellow-700
                                    @elseif($task->priority?->name === 'High' || $task->priority?->name === 'Tinggi') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $task->priority?->name ?? 'Tidak ada' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">
                                {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                @if ($task->status?->name === 'Completed') bg-green-100 text-green-700
                                @elseif($task->status?->name === 'In Progress') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700 @endif">
                                    {{ $task->status?->name ?? 'Pending' }}
                                </span>

                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('tasks.show', ['todolist' => $list->id, 'task' => $task->id]) }}"
                                        class="p-2 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition"
                                        title="Lihat">
                                        <x-ri-eye-line class="w-5 h-5" />
                                    </a>
                                    <a href="{{ route('tasks.edit', ['todolist' => $list->id, 'task' => $task->id]) }}"
                                        class="p-2 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition"
                                        title="Edit">
                                        <x-ri-edit-line class="w-5 h-5" />
                                    </a>
                                    <form
                                        action="{{ route('tasks.destroy', ['todolist' => $list->id, 'task' => $task->id]) }}"
                                        method="POST" style="display:inline;"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition"
                                            title="Hapus">
                                            <x-ri-delete-bin-5-line class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Belum ada task yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Guard terhadap multiple flash calls
        let _flashShown = false;

        function showFlashMessage() {
            if (_flashShown) return;
            _flashShown = true;

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1800
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 1800
                });
            @endif
        }

        document.addEventListener('DOMContentLoaded', () => {
            showFlashMessage();

            const searchInput = document.getElementById('searchInput');
            const sortSelect = document.getElementById('sort');
            const dateInput = document.getElementById('date');
            const tableBody = document.querySelector('#taskTable tbody');
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Fungsi untuk mendapatkan semua baris task
            function getAllRows() {
                return Array.from(tableBody.querySelectorAll('tr')).filter(row => {
                    return !row.querySelector('td[colspan]'); // Exclude empty state row
                });
            }

            // Fungsi untuk menampilkan/menyembunyikan loading
            function showLoading(show) {
                if (loadingOverlay) {
                    loadingOverlay.classList.toggle('hidden', !show);
                }
            }

            // Fungsi untuk menampilkan pesan "tidak ada data"
            function showEmptyState(message = 'Belum ada task yang ditemukan.') {
                const emptyRow = tableBody.querySelector('td[colspan]');
                if (emptyRow) {
                    emptyRow.textContent = message;
                } else {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            ${message}
                        </td>
                    `;
                    tableBody.appendChild(tr);
                }
            }

            // Fungsi untuk menyembunyikan empty state
            function hideEmptyState() {
                const emptyRow = tableBody.querySelector('td[colspan]');
                if (emptyRow) {
                    emptyRow.closest('tr').remove();
                }
            }

            // Fungsi untuk filter dan sort
            function filterAndSort() {
                showLoading(true);

                // Simulasi delay untuk UX yang lebih baik
                setTimeout(() => {
                    const searchTerm = searchInput.value.toLowerCase().trim();
                    const sortOrder = sortSelect.value;
                    const filterDate = dateInput.value;

                    let rows = getAllRows();
                    let visibleCount = 0;

                    // Filter berdasarkan search
                    rows.forEach(row => {
                        const title = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const matchSearch = title.includes(searchTerm);

                        // Filter berdasarkan tanggal
                        let matchDate = true;
                        if (filterDate) {
                            const deadlineCell = row.querySelector('td:nth-child(5)').textContent.trim();
                            if (deadlineCell !== '-') {
                                // Parse tanggal dari format "dd MMM yyyy"
                                const deadlineText = deadlineCell;
                                const taskDate = parseDateFromText(deadlineText);
                                const selectedDate = new Date(filterDate);

                                matchDate = taskDate &&
                                           taskDate.getDate() === selectedDate.getDate() &&
                                           taskDate.getMonth() === selectedDate.getMonth() &&
                                           taskDate.getFullYear() === selectedDate.getFullYear();
                            } else {
                                matchDate = false;
                            }
                        }

                        if (matchSearch && matchDate) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Sort rows yang visible
                    const visibleRows = rows.filter(row => row.style.display !== 'none');

                    if (visibleRows.length > 0) {
                        hideEmptyState();

                        // Sort berdasarkan nomor urut atau bisa juga berdasarkan data lain
                        visibleRows.sort((a, b) => {
                            const indexA = parseInt(a.querySelector('td:first-child').textContent);
                            const indexB = parseInt(b.querySelector('td:first-child').textContent);

                            return sortOrder === 'asc' ? indexA - indexB : indexB - indexA;
                        });

                        // Re-append rows dalam urutan yang benar
                        visibleRows.forEach((row, index) => {
                            row.querySelector('td:first-child').textContent = index + 1;
                            tableBody.appendChild(row);
                        });
                    } else {
                        let message = 'Belum ada task yang ditemukan.';
                        if (searchTerm) {
                            message = `Tidak ada task dengan judul "${searchTerm}"`;
                        }
                        if (filterDate) {
                            message = `Tidak ada task pada tanggal ${formatDate(filterDate)}`;
                        }
                        if (searchTerm && filterDate) {
                            message = `Tidak ada task dengan judul "${searchTerm}" pada tanggal ${formatDate(filterDate)}`;
                        }
                        showEmptyState(message);
                    }

                    showLoading(false);
                }, 300);
            }

            // Fungsi helper untuk parse tanggal dari text
            function parseDateFromText(dateText) {
                const months = {
                    'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'Mei': 4, 'Jun': 5,
                    'Jul': 6, 'Agu': 7, 'Sep': 8, 'Okt': 9, 'Nov': 10, 'Des': 11,
                    'May': 4, 'Aug': 7, 'Oct': 9, 'Dec': 11
                };

                const parts = dateText.trim().split(' ');
                if (parts.length === 3) {
                    const day = parseInt(parts[0]);
                    const month = months[parts[1]];
                    const year = parseInt(parts[2]);

                    if (!isNaN(day) && month !== undefined && !isNaN(year)) {
                        return new Date(year, month, day);
                    }
                }
                return null;
            }

            // Fungsi helper untuk format tanggal
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }

            // Event listeners
            if (searchInput) {
                searchInput.addEventListener('input', filterAndSort);
            }

            if (sortSelect) {
                sortSelect.addEventListener('change', filterAndSort);
            }

            if (dateInput) {
                dateInput.addEventListener('change', filterAndSort);
            }
        });

        // Handle Turbo navigation
        if (typeof Turbo !== 'undefined') {
            document.addEventListener('turbo:load', () => {
                _flashShown = false;
            });
        }
    </script>
@endpush

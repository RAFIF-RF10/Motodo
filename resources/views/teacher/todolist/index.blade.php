@extends('layout.app')

@section('content')
    <section class="p-6 mt-2">
       <div class="relative mb-16 rounded-3xl overflow-hidden shadow-xl border border-white/20 bg-gradient-to-r from-[#0064E0] to-[#0082FB] text-white p-10">
            <div class="relative z-10">
                <h1 class="text-4xl font-black mb-2 tracking-tight">Daftar Tugas Saya</h1>
                <p class="text-white dark:text-gray-400 text-md">
                    Kelola dan kerjakan semua tugas yang diberikan oleh guru.
                </p>
            </div>
            <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none select-none">
                {{ svg('hugeicons-book-02', 'w-64 h-64') }}
            </div>
        </div>


        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 mb-6">
            <div class="flex-grow">
                <input id="searchInput" placeholder="Cari List..." type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-400 dark:bg-gray-800 dark:text-gray-200">
            </div>

            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex flex-col items-start gap-2 ">
                    <label for="sort" class="text-gray-500 font-medium text-sm">URUTAN</label>
                    <select name="sort" id="sort"
                        class="border border-gray-400 rounded-lg px-3 py-2 text-sm focus:ring-[#0082fb]">
                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                        <option value="asc" {{ request('sort', 'desc') == 'asc' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>

            </div>
            <div class="flex items-start flex-col gap-2">
            <label for="date" class="text-gray-600 dark:text-gray-300 font-medium text-sm">TANGGAL</label>
            <input type="date" id="date" name="date" value="{{ request('date') }}"
                class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0082fb] dark:bg-gray-800 dark:text-gray-200 w-full">
        </div>
         <button id="openModalBtn"
        class="bg-blue-600 cursor-pointer hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition">
        + Tambah Todo
    </button>
        </div>

        <div
            class="overflow-hidden bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
            <table class="min-w-full" id="todoTable">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-gray-700 dark:to-gray-800">
                        <th
                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white dark:text-gray-200">
                            #
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white dark:text-gray-200">
                            Judul
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white dark:text-gray-200">
                            Tanggal Dibuat
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-white dark:text-gray-200">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($lists as $index => $todo)
                        <tr
                            class="group hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent dark:hover:from-gray-700/50 dark:hover:to-transparent transition-all duration-200">
                            <td class="px-6 py-5 text-sm font-semibold text-gray-600 dark:text-gray-400">
                                <div
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400 group-hover:bg-blue-200 dark:group-hover:bg-gray-600 transition-colors">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center">
                                    <span
                                        class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $todo->title }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                                        {{ $todo->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('todolist.show', $todo->id) }}"
                                        class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-all duration-200 hover:scale-110 transform"
                                        title="Lihat Detail">
                                        <x-ri-eye-line class="w-5 h-5" />
                                    </a>

                                    <button
                                        onclick="openEditModal({{ $todo->id }}, '{{ $todo->title }}', '{{ $todo->description ?? '' }}')"
                                        class="p-2 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-all duration-200 hover:scale-110 transform cursor-pointer"
                                        title="Edit">
                                        <x-ri-edit-line class="w-5 h-5" />
                                    </button>

                                    @if (auth()->user()->role->name === 'Admin')
                                        <form action="{{ route('todolist.destroy', $todo->id) }}" method="POST"
                                            class="inline" onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition-all duration-200 hover:scale-110 transform cursor-pointer"
                                                title="Hapus">
                                                <x-ri-delete-bin-5-line class="w-5 h-5" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada todo list</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Klik tombol "Tambah Todo" untuk
                                        membuat yang pertama</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
   <div id="editModal" class="hidden fixed inset-0 bg-black/50 flex justify-center items-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 p-6 relative">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Edit Todo</h2>

        <form id="editTodoForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="edit_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Judul
                </label>
                <input type="text" id="edit_title" name="title" required
                    class="w-full mt-2 px-3 py-2 border rounded-lg dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label for="edit_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Gambar (Opsional)
                </label>
                <input type="file" id="edit_image" name="image" accept="image/*"
                    class="w-full mt-2 px-3 py-2 border rounded-lg cursor-pointer dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-400">
                <img id="edit_image_preview" src="" alt="Preview" class="mt-3 rounded-lg hidden w-full object-cover max-h-40">
            </div>

            <div class="mb-4">
                <label for="edit_description"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Deskripsi
                </label>
                <textarea id="edit_description" name="description" rows="3"
                    class="w-full mt-2 px-3 py-2 border rounded-lg dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-400"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 cursor-pointer rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>


  <div id="addModal" class="hidden fixed inset-0 bg-black/50 flex justify-center items-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 p-6 relative">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Tambah Todo Baru</h2>

        <form  data-turbo="false" action="{{ route('todolist.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Judul
                </label>
                <input type="text" id="title" name="title" required
                    class="w-full mt-2 px-3 py-2 border rounded-lg dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Gambar (Opsional)
                </label>
                <input type="file" id="image" name="image" accept="image/*"
                    class="w-full mt-2 px-3 py-2 border rounded-lg cursor-pointer dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Deskripsi (Opsional)
                </label>
                <textarea id="description" name="description" rows="3"
                    class="w-full mt-2 px-3 py-2 border rounded-lg dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-400"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="closeModalBtn"
                    class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 cursor-pointer rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {
            const flashSuccess = @json(session('success'));
            const flashError = @json(session('error'));

            function showFlashOnce() {
                if (window._flashShown) return;
                if (flashSuccess) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: flashSuccess,
                        showConfirmButton: false,
                        timer: 1800
                    });
                    window._flashShown = true;
                    return;
                }

                if (flashError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: flashError,
                        showConfirmButton: false,
                        timer: 1800
                    });
                    window._flashShown = true;
                }
            }

            if (window.Turbo) {
                document.addEventListener('turbo:load', showFlashOnce);
            } else {
                document.addEventListener('DOMContentLoaded', showFlashOnce);
            }
        })();

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
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        }

        document.addEventListener('turbo:load', () => {
            const modal = document.getElementById('addModal');
            const openBtn = document.getElementById('openModalBtn');
            const closeBtn = document.getElementById('closeModalBtn');

            if (openBtn && modal && closeBtn) {
                openBtn.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });

                closeBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });

                window.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            }
        });

      

        function openEditModal(id, title, desc) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editTodoForm');

            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = desc;

            form.action = `/todolist/${id}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }


document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sort');
    const dateInput = document.getElementById('date');
    const tableBody = document.querySelector('#todoTable tbody');

    // Simpan semua rows asli
    let allRows = [];

    function initializeRows() {
        allRows = Array.from(tableBody.querySelectorAll('tr')).filter(row => {
            return !row.querySelector('td[colspan]');
        });

        // Simpan index asli untuk sorting
        allRows.forEach((row, index) => {
            row.setAttribute('data-original-index', index + 1);
        });
    }

    initializeRows();

    function showEmptyState(message = 'Belum ada todo list') {
        tableBody.innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center space-y-3">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">${message}</p>
                    </div>
                </td>
            </tr>
        `;
    }

    function parseDateFromText(text) {
        const bulan = {
            'Jan': 0, 'Januari': 0,
            'Feb': 1, 'Februari': 1,
            'Mar': 2, 'Maret': 2,
            'Apr': 3, 'April': 3,
            'Mei': 4, 'May': 4,
            'Jun': 5, 'Juni': 5,
            'Jul': 6, 'Juli': 6,
            'Agu': 7, 'Agustus': 7, 'Aug': 7,
            'Sep': 8, 'September': 8,
            'Okt': 9, 'Oktober': 9, 'Oct': 9,
            'Nov': 10, 'November': 10,
            'Des': 11, 'Desember': 11, 'Dec': 11
        };

        const parts = text.trim().split(' ');
        if (parts.length === 3) {
            const day = parseInt(parts[0]);
            const month = bulan[parts[1]];
            const year = parseInt(parts[2]);

            if (!isNaN(day) && month !== undefined && !isNaN(year)) {
                return new Date(year, month, day);
            }
        }
        return null;
    }

    function formatDate(value) {
        const d = new Date(value);
        const day = d.getDate();
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const month = monthNames[d.getMonth()];
        const year = d.getFullYear();
        return `${day} ${month} ${year}`;
    }

    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const sortOrder = sortSelect.value;
        const filterDate = dateInput.value;

        // Clone array
        let filteredRows = [...allRows];

        // Filter berdasarkan search term (judul)
        if (searchTerm) {
            filteredRows = filteredRows.filter(row => {
                const titleCell = row.querySelector('td:nth-child(2) span');
                if (titleCell) {
                    const title = titleCell.textContent.toLowerCase();
                    return title.includes(searchTerm);
                }
                return false;
            });
        }

        // Filter berdasarkan tanggal dibuat
        if (filterDate) {
            filteredRows = filteredRows.filter(row => {
                const dateCell = row.querySelector('td:nth-child(3) span');
                if (dateCell) {
                    const dateText = dateCell.textContent.trim();
                    const todoDate = parseDateFromText(dateText);
                    const selectedDate = new Date(filterDate);

                    return todoDate &&
                        todoDate.getDate() === selectedDate.getDate() &&
                        todoDate.getMonth() === selectedDate.getMonth() &&
                        todoDate.getFullYear() === selectedDate.getFullYear();
                }
                return false;
            });
        }

        // Sorting berdasarkan index asli (urutan dibuat)
        filteredRows.sort((a, b) => {
            const indexA = parseInt(a.getAttribute('data-original-index'));
            const indexB = parseInt(b.getAttribute('data-original-index'));
            return sortOrder === 'asc' ? indexA - indexB : indexB - indexA;
        });

        // Clear table body
        tableBody.innerHTML = '';

        // Tampilkan hasil
        if (filteredRows.length > 0) {
            filteredRows.forEach((row, index) => {
                // Update nomor urut di kolom pertama
                const numberCell = row.querySelector('td:first-child div');
                if (numberCell) {
                    numberCell.textContent = index + 1;
                }
                tableBody.appendChild(row);
            });
        } else {
            let message = 'Belum ada todo list';

            if (searchTerm && !filterDate) {
                message = `Tidak ada todo dengan judul "${searchTerm}"`;
            } else if (filterDate && !searchTerm) {
                message = `Tidak ada todo pada tanggal ${formatDate(filterDate)}`;
            } else if (searchTerm && filterDate) {
                message = `Tidak ada todo berjudul "${searchTerm}" pada tanggal ${formatDate(filterDate)}`;
            }

            showEmptyState(message);
        }
    }

    // Event listeners dengan debounce untuk search
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterAndSort, 300);
    });

    sortSelect.addEventListener('change', filterAndSort);
    dateInput.addEventListener('change', filterAndSort);
});
    </script>
@endpush

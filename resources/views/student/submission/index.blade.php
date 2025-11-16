@extends('layout.app')

@section('content')
    <div class="p-6 mt-20">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Pengumpulan Saya</h1>
            <p class="text-gray-600 dark:text-gray-400">Pantau status pengumpulan tugas Anda</p>
        </div>

        <!-- Status Summary -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <!-- Total Tugas -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Tugas</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalTasks }}</p>
        </div>
        <div class="text-blue-500 dark:text-blue-400">
            {{ svg('heroicon-s-document-text','w-10 h-10') }}
        </div>
    </div>

    <!-- Selesai -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $completedCount }}</p>
        </div>
        <div class="text-green-500 dark:text-green-400">
            {{ svg('heroicon-o-check-circle','w-10 h-10') }}
        </div>
    </div>

    <!-- Sedang Diproses -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Sedang Diproses</p>
            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $inProgressCount }}</p>
        </div>
        <div class="text-yellow-500 dark:text-yellow-400">
            {{ svg('heroicon-s-clock','w-10 h-10') }}
        </div>
    </div>

    <!-- Belum Dikumpulkan -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Belum Dikumpulkan</p>
            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $notSubmittedCount }}</p>
        </div>
        <div class="text-red-500 dark:text-red-400">
            {{ svg('heroicon-s-x-circle','w-10 h-10') }}
        </div>
    </div>
</div>


        <!-- Tabs -->
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('all')" id="tab-all"
                        class="tab-button border-b-2 cursor-pointer border-blue-500 text-blue-600 dark:text-blue-400 py-4 px-1 text-sm font-medium">
                    Semua ({{ $totalTasks }})
                </button>
                <button onclick="showTab('submitted')" id="tab-submitted"
                        class="tab-button border-b-2 cursor-pointer border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 py-4 px-1 text-sm font-medium">
                    Sudah Dikumpulkan ({{ $submissions->count() }})
                </button>
                <button onclick="showTab('not-submitted')" id="tab-not-submitted"
                        class="tab-button border-b-2 cursor-pointer border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 py-4 px-1 text-sm font-medium">
                    Belum Dikumpulkan ({{ $notSubmittedCount }})
                </button>
            </nav>
        </div>

        <!-- All Tasks Table -->
        <div id="content-all" class="tab-content">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Tugas</th>
                                <th class="px-6 py-3 text-left">Deadline</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($allTasks as $index => $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('student.task.show', $task->id) }}"
                                            class="text-black hover:text-blue-600 font-semibold">
                                            {{ $task->title }}
                                        </a>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $task->todoList->title ?? '' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($task->deadline)
                                            <div class="text-sm">
                                                <p>{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($task->deadline)->format('H:i') }}
                                                </p>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $submission = $submissions->firstWhere('task_id', $task->id);
                                        @endphp
                                        @if($submission)
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                                @if($submission->status->name === 'Completed') bg-green-100 text-green-700
                                                @elseif($submission->status->name === 'Pending') bg-red-100 text-red-700
                                                @else bg-yellow-100 text-yellow-700 @endif">
                                                {{ $submission->status->name }}
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                                Belum Dikumpulkan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($submission)
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                                    Lihat File
                                                </a>
                                                @if($submission->status->name !== 'Completed')
                                                    <button onclick="openEditModal({{ $submission->id }}, '{{ addslashes($submission->notes) }}')"
                                                            class="text-yellow-600 hover:text-yellow-800 text-sm font-semibold cursor-pointer">
                                                        Edit
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <a href="{{ route('student.task.show', $task->id) }}"
                                                class="text-green-600 hover:text-green-800 text-sm font-semibold">
                                                Kumpulkan
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center">
                                        <div class="text-6xl mb-4">📝</div>
                                        <p class="text-gray-600 dark:text-gray-400">Belum ada tugas tersedia</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submitted Tasks Table -->
        <div id="content-submitted" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Tugas</th>
                                <th class="px-6 py-3 text-left">Tanggal Kumpul</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($submissions as $index => $submission)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('student.task.show', $submission->task_id) }}"
                                            class="text-blue-600 hover:text-blue-600 font-semibold">
                                            {{ $submission->task->title ?? 'Tugas' }}
                                        </a>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $submission->task->todoList->title ?? '' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <p>{{ $submission->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $submission->created_at->format('H:i') }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if($submission->status->name === 'Completed') bg-green-100 text-green-700
                                            @elseif($submission->status->name === 'Pending') bg-red-100 text-red-700
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                            {{ $submission->status?->name ?? 'In Progress' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            @if($submission->file_path)
                                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                                    Lihat File
                                                </a>
                                            @endif
                                            @if($submission->status->name !== 'Completed')
                                                <button onclick="openEditModal({{ $submission->id }}, '{{ addslashes($submission->notes) }}')"
                                                        class="text-yellow-600 hover:text-yellow-800 text-sm font-semibold cursor-pointer">
                                                    Edit
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center">
                                        <div class="text-6xl mb-4">📝</div>
                                        <p class="text-gray-600 dark:text-gray-400">Anda belum mengumpulkan tugas apapun</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Not Submitted Tasks Table -->
        <div id="content-not-submitted" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Tugas</th>
                                <th class="px-6 py-3 text-left">Deadline</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($notSubmittedTasks as $index => $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('student.task.show', $task->id) }}"
                                            class=" text-black hover:text-blue-600 font-semibold">
                                            {{ $task->title }}
                                        </a>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $task->todoList->title ?? '' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($task->deadline)
                                            <div class="text-sm">
                                                <p class="@if(\Carbon\Carbon::parse($task->deadline)->isPast()) text-red-600 font-semibold @endif">
                                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y, H:i') }}
                                                </p>
                                                @if(\Carbon\Carbon::parse($task->deadline)->isPast())
                                                    <p class="text-xs text-red-500">Terlambat!</p>
                                                @else
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('student.task.show', $task->id) }}"
                                            class="inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                                            Kumpulkan Sekarang
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center">
                                        <div class="text-6xl mb-4 flex align-items-center justify-center text-green-500">{{ svg('heroicon-c-check-circle', 'w-10 h-10 ') }}</div>
                                        <p class="text-gray-600 dark:text-gray-400">Semua tugas sudah dikumpulkan!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Edit Pengumpulan</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        File Baru (Opsional)
                    </label>
                    <input type="file" name="file"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah file</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Catatan
                    </label>
                    <textarea name="notes" id="editNotes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition cursor-pointer">
                        Simpan Perubahan
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

// Tab Navigation
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(b => {
        b.classList.remove('border-blue-500','text-blue-600','dark:text-blue-400');
        b.classList.add('border-transparent','text-gray-500','dark:text-gray-400');
    });

    document.getElementById('content-' + tab).classList.remove('hidden');
    const activeButton = document.getElementById('tab-' + tab);
    activeButton.classList.add('border-blue-500','text-blue-600','dark:text-blue-400');
    activeButton.classList.remove('border-transparent','text-gray-500','dark:text-gray-400');
}

// Modal Edit Submission
function openEditModal(submissionId, notes) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');

    if (!modal || !form) return;

    form.action = `/submission/${submissionId}/update`;
    document.getElementById('editNotes').value = notes || '';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    if (!modal || !form) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
    form.reset();
}

// Close modal on outside click
document.getElementById('editModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Confirm Delete Example
window.confirmDelete = function(e) {
    e.preventDefault();
    const form = e.target.closest('form');
    if (!form) return;

    Swal.fire({
        title: 'Yakin hapus?',
        text: "Data ini tidak bisa dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus'
    }).then(result => {
        if (result.isConfirmed) form.submit();
    });
}

// Optional: Modal Add Example (mirip todolist)
document.addEventListener('turbo:load', () => {
    const modal = document.getElementById('addModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');

    if (modal && openBtn && closeBtn) {
        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        window.addEventListener('click', e => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    }
});
</script>

@endpush

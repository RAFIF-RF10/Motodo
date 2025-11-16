@extends('layout.app')

@section('content')
<section class="p-6 mt-20">
    <div class="bg-[#F1F5F8] dark:bg-[#1C2B33] rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-[#0064E0] dark:text-[#0082FB] mb-6">
            Daftar Pengumpulan Tugas
        </h2>

        @forelse($groupedSubmissions as $todoListTitle => $tasks)
            <div class="mb-4">
                {{-- Header TODO LIST - Level 1 Accordion --}}
                <div class="bg-white dark:bg-[#24323C] rounded-lg shadow-md border-2 border-[#0082FB] dark:border-[#0064E0] overflow-hidden">
                    <button type="button"
                            onclick="toggleAccordion('todolist-{{ $loop->index }}')"
                            class="w-full px-6 py-5 flex items-center justify-between hover:bg-blue-50 dark:hover:bg-[#2a3a45] transition">
                        <div class="flex items-center gap-4 flex-1">
                            <span class="text-3xl text-blue-500 ">{{ svg('fas-book-reader','w-6 h-6') }}</span>
                            <div class="text-left">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ $todoListTitle }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $tasks->count() }} task
                                    <span class="mx-2">•</span>
                                    <span class="text-blue-600 dark:text-blue-400">
                                        {{ $tasks->sum(fn($t) => $t->count()) }} total pengumpulan
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="icon-todolist-{{ $loop->index }}" class="transform transition-transform duration-300">
                                {{ svg('heroicon-s-chevron-down', 'w-7 h-7 text-[#0082FB] dark:text-[#0064E0]') }}
                            </span>
                        </div>
                    </button>

                    {{-- Content TODO LIST - Hidden by default --}}
                    <div id="todolist-{{ $loop->index }}" class="hidden border-t-2 border-[#0082FB] dark:border-[#0064E0] bg-gray-50 dark:bg-[#1C2B33] p-4">

                        @foreach($tasks as $taskTitle => $submissions)
                            <div class="mb-3">
                                {{-- Header TASK - Level 2 Accordion --}}
                                <div class="bg-white dark:bg-[#24323C] rounded-lg shadow-sm border border-gray-300 dark:border-gray-600 overflow-hidden">
                                    <button type="button"
                                            onclick="toggleAccordion('task-{{ $loop->parent->index }}-{{ $loop->index }}')"
                                            class="w-full px-5 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-[#2a3a45] transition">
                                        <div class="flex items-center gap-3 flex-1">
                                            <span class="text-2xl text-blue-500">{{ svg('bxs-book','w-6 h-6') }}</span>
                                            <div class="text-left">
                                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                    {{ $taskTitle }}
                                                </h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $submissions->count() }} pengumpulan
                                                    <span class="mx-2">•</span>
                                                    <span class="text-green-600 dark:text-green-400">
                                                        {{ $submissions->where('status.name', 'Completed')->count() }} selesai
                                                    </span>
                                                    <span class="mx-2">•</span>
                                                    <span class="text-yellow-600 dark:text-yellow-400">
                                                        {{ $submissions->where('status.name', 'In Progress')->count() }} proses
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span id="icon-task-{{ $loop->parent->index }}-{{ $loop->index }}" class="transform transition-transform duration-300">
                                                {{ svg('heroicon-s-chevron-right', 'w-5 h-5 text-gray-600 dark:text-gray-400') }}
                                            </span>
                                        </div>
                                    </button>

                                    {{-- Content TASK - Hidden by default --}}
                                    <div id="task-{{ $loop->parent->index }}-{{ $loop->index }}" class="hidden border-t border-gray-200 dark:border-gray-700">
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm text-left">
                                                <thead class="bg-[#0082FB] text-white uppercase text-xs">
                                                    <tr>
                                                        <th class="px-4 py-3">No</th>
                                                        <th class="px-4 py-3">Nama Siswa</th>
                                                        <th class="px-4 py-3">Catatan</th>
                                                        <th class="px-4 py-3">Status</th>
                                                        <th class="px-4 py-3 text-center">File</th>
                                                        <th class="px-4 py-3 text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-[#1C2B33]">
                                                    @foreach($submissions as $index => $submission)
                                                        <tr class="border-t dark:border-gray-700 hover:bg-[#EAF1FB] dark:hover:bg-[#24323C] transition">
                                                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                                                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $submission->user->name ?? '-' }}
                                                            </td>
                                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                                                {{ $submission->notes ?? '-' }}
                                                            </td>

                                                            <td class="px-4 py-3">
                                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                                    @if($submission->status->name === 'Completed') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300
                                                                    @elseif($submission->status->name === 'In Progress') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300
                                                                    @elseif($submission->status->name === 'Pending') bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                                                    @endif">
                                                                    {{ $submission->status->name }}
                                                                </span>
                                                            </td>

                                                            <td class="px-4 py-3 text-center">
                                                                <a href="{{ asset('storage/' . $submission->file_path) }}"
                                                                   target="_blank"
                                                                   class="inline-flex items-center gap-1 text-[#0064E0] dark:text-[#0082FB] font-medium hover:underline">
                                                                    {{ svg('heroicon-o-document-text', 'w-4 h-4') }}
                                                                    Lihat
                                                                </a>
                                                            </td>

                                                            <td class="px-4 py-3 text-center">
                                                                @if($submission->status->name !== 'Completed')
                                                                    <div class="flex justify-center gap-2">
                                                                        <button onclick="confirmAction('{{ route('teacher.submission.updateStatus', $submission->id) }}', 'Completed')"
                                                                                class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 cursor-pointer transition"
                                                                                title="Tandai Selesai">
                                                                            {{ svg('heroicon-s-check-circle','w-8 h-8') }}
                                                                        </button>
                                                                        <button onclick="confirmAction('{{ route('teacher.submission.updateStatus', $submission->id) }}', 'Pending')"
                                                                                class="text-red-600 cursor-pointer hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition"
                                                                                title="Kembalikan ke Pending">
                                                                            {{ svg('heroicon-s-x-circle','w-8 h-8') }}
                                                                        </button>
                                                                    </div>
                                                                @else
                                                                    <span class="text-gray-400 dark:text-gray-600 text-xs">
                                                                        Selesai ✓
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-10 text-center">
                <div class="text-6xl mb-4">✅</div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada pengumpulan tugas</h3>
                <p class="text-gray-600 dark:text-gray-400">Belum ada siswa yang mengumpulkan tugas.</p>
            </div>
        @endforelse
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-90');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-90');
        }
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
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                form.appendChild(method);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'status';
                input.value = status;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    @if (session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#0064E0',
    });
    @endif
</script>
@endsection

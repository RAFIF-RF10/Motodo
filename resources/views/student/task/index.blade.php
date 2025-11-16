@extends('layout.app')

@section('content')
<section class="p-6 mt-20">
    <div class="bg-white dark:bg-[#1E2A32] rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Daftar Tugas</h2>
        </div>

        <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-[#24323C] text-gray-800 dark:text-gray-100 uppercase">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Judul</th>
                    <th class="px-4 py-2">Deskripsi</th>
                    <th class="px-4 py-2">Deadline</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $index => $task)
                    <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $task->title }}</td>
                        <td class="px-4 py-3">{{ Str::limit($task->description, 50) }}</td>
                        <td class="px-4 py-3">{{ $task->deadline ? $task->deadline->format('d M Y') : '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('student.tasks.show', $task->id) }}"
                               class="text-blue-600 hover:text-blue-800">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada tugas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">{{ $tasks->links() }}</div>
    </div>
</section>
@endsection

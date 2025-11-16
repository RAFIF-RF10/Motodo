@extends('layout.app')

@section('content')
<div class="p-6 mt-16 space-y-6">
    <!-- Header -->
      <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20 dark:border-gray-700/20">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
                <div class="flex-1">
                    <a href="{{ route('student.todo.index') }}"
                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 mb-4 group transition-all">
                        {{ svg('heroicon-o-arrow-small-left','w-8 h-8 group-hover:-translate-x-1 transition-transform') }}
                        <span class="font-medium">Kembali</span>
                    </a>

                    <h1 class="text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-blue-600  mb-3">
                        {{ $list->title }}
                    </h1>

                    <p class="text-gray-600 dark:text-gray-300 text-lg">
                        {{ $list->description ?? 'Tanpa deskripsi' }}
                    </p>
                </div>

                <div class="flex items-center gap-3 bg-blue-500 text-white px-6 py-3 rounded-2xl shadow-lg">
                    {{ svg('fwb-o-calendar-month', 'w-6 h-6') }}
                    <div class="text-sm">
                        <div class="font-semibold">Dibuat</div>
                        <div class="font-bold">{{ date('d M Y', strtotime($list->created_at)) }}</div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Tasks List -->
    <div class="flex flex-col gap-4">
        @forelse($tasks as $task)
            @php
                $submission = $task->submissions->first();
                $statusName = strtolower($submission->status->name ?? '');
                $priorityColor = match($task->priority?->name) {
                    'High', 'Tinggi' => 'border-red-500',
                    'Medium', 'Sedang' => 'border-yellow-500',
                    default => 'border-green-500',
                };
            @endphp

            <a href="{{ route('student.task.show', $task->id) }}"
                class="flex flex-col md:flex-row items-start md:items-center justify-between bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-lg transition-all border-l-4 {{ $priorityColor }} overflow-hidden group">

                <!-- Left Content -->
                <div class="flex-1 p-5 space-y-2">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                        <span class="px-2 py-0.5 text-xs rounded-full font-semibold
                            @if ($submission) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif">
                            {{ $submission ? 'Dikumpulkan' : 'Belum' }}
                        </span>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">
                        {{ $task->description ?? '-' }}
                    </p>

                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold
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
                        Deadline: <strong class="text-gray-800 dark:text-gray-200">{{ $task->deadline ? date('d M Y', strtotime($task->deadline)) : 'Tidak ada' }}</strong>
                    </div>
                </div>

                <!-- Right Arrow / Detail -->
                <div class="bg-gray-50 dark:bg-gray-900 w-full md:w-32 flex items-center justify-center md:flex-col md:justify-between p-4">
                    <span class="text-xs text-gray-500 dark:text-gray-400 md:mb-2">Lihat Detail</span>
                    <span class="text-blue-600 dark:text-blue-400 text-2xl font-semibold group-hover:translate-x-1 transition-transform">→</span>
                </div>
            </a>
        @empty
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-10 text-center">
        <div class="text-6xl flex align-items-center justify-center mb-4">
                    <img src="{{asset('image/default/noTask.png')}}" width="500px" alt="">
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada tugas</h3>
                <p class="text-gray-600 dark:text-gray-400">Guru akan menambahkan tugas untuk kategori ini segera.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

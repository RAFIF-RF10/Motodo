@extends('layout.app')

@section('content')
    <div class="p-6 mt-20 max-w-5xl mx-auto space-y-10">

        {{-- HEADER --}}
        <div
            class="relative rounded-3xl overflow-hidden shadow-xl border border-white/20 bg-gradient-to-r from-[#0064E0] to-[#0082FB] text-white p-10">
            <div class="relative z-10">
                <h1 class="text-4xl font-black mb-2 tracking-tight">{{ $task->title }}</h1>
                <p class="text-lg opacity-90">
                    {{ $task->description ?? 'Tanpa deskripsi' }}
                </p>
            </div>
            <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none select-none">
                {{ svg('heroicon-s-academic-cap', 'w-64 h-64') }}
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="flex items-center gap-4 bg-[#F1F5F8] dark:bg-[#1C2B33] border border-white/30 dark:border-gray-700 rounded-2xl p-5 shadow-lg backdrop-blur-sm">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl">
                    {{ svg('heroicon-s-document-text', 'w-8 h-8 text-[#0082FB]') }}
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Detail Soal</p>
                    @if ($task->image)
                        <a href="{{ asset('storage/' . $task->image) }}" target="_blank"
                            class="text-blue-600 hover:underline font-semibold flex items-center gap-1">
                            <x-heroicon-o-eye class="w-5 h-5 text-blue-500" /> Lihat
                        </a>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada file</p>
                    @endif

                </div>
            </div>

            {{-- Deadline --}}
            <div
                class="flex items-center gap-4 bg-[#F1F5F8] dark:bg-[#1C2B33] border border-white/30 dark:border-gray-700 rounded-2xl p-5 shadow-lg backdrop-blur-sm">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl">
                    {{ svg('heroicon-s-clock', 'w-8 h-8 text-yellow-500') }}
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Batas Waktu</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $task->deadline ? $task->deadline->format('d M Y') : '-' }}
                    </p>
                </div>
            </div>

            {{-- Status --}}
            <div
                class="flex items-center gap-4 bg-[#F1F5F8] dark:bg-[#1C2B33] border border-white/30 dark:border-gray-700 rounded-2xl p-5 shadow-lg backdrop-blur-sm">
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                    {{ svg('heroicon-o-check-circle', 'w-8 h-8 text-green-500') }}
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $task->submissions->count() ? 'Sudah Dikumpulkan' : 'Belum' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- FORM PENGUMPULAN --}}
        <div class="bg-[#F1F5F8] dark:bg-[#1C2B33] border border-white/30 dark:border-gray-700 rounded-3xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg">
                    {{ svg('heroicon-s-cloud-arrow-up', 'w-6 h-6 text-[#0082FB]') }}
                </div>
                Kumpulkan Tugas
            </h2>

            @php
                $sudahDikumpulkan = $task->submissions->count() > 0;
                $latest = $task->submissions->first();
            @endphp

            <form action="{{ route('student.tasks.submit', $task->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6 {{ $sudahDikumpulkan ? 'opacity-60 pointer-events-none select-none' : '' }}">
                @csrf

                {{-- File Upload --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">File Tugas</label>
                    <input type="file" name="file" {{ $sudahDikumpulkan ? 'disabled' : '' }}
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl p-3 focus:ring-2 focus:ring-[#0082FB] outline-none transition">
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Catatan
                        (Opsional)</label>
                    <textarea name="notes" rows="3" {{ $sudahDikumpulkan ? 'disabled' : '' }}
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl p-3 focus:ring-2 focus:ring-[#0082FB] outline-none transition"></textarea>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full py-3 rounded-xl text-white font-semibold flex items-center justify-center gap-2 transition-all shadow-lg
                {{ $sudahDikumpulkan
                    ? 'bg-gray-400 cursor-not-allowed'
                    : 'bg-gradient-to-r from-[#0064E0] to-[#0082FB] hover:from-[#0082FB] hover:to-[#0064E0]' }}">
                    @if ($sudahDikumpulkan)
                        {{ svg('heroicon-s-lock-closed', 'w-5 h-5') }}
                        Sudah Dikumpulkan
                    @else
                        {{ svg('heroicon-s-paper-airplane', 'w-5 h-5 rotate-45') }}
                        Kumpulkan Sekarang
                    @endif
                </button>
            </form>

            {{-- Info jika sudah dikumpulkan --}}
            @if ($sudahDikumpulkan)
                <div
                    class="mt-6 bg-white/70 dark:bg-gray-900/70 border border-gray-300 dark:border-gray-700 rounded-xl p-6 text-center backdrop-blur-md">
                    <h3
                        class="text-lg font-semibold text-green-600 dark:text-green-400 mb-2 flex items-center justify-center gap-2">
                        {{ svg('heroicon-c-check-circle', 'w-6 h-6') }}
                        Tugas sudah dikumpulkan
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Dikirim pada {{ $latest->created_at->format('d M Y H:i') }}
                    </p>
                    @if ($latest->file_path)
                        <a href="{{ asset('storage/' . $latest->file_path) }}" target="_blank"
                            class="inline-flex items-center gap-2 bg-[#0082FB]/10 hover:bg-[#0082FB]/20 text-[#0064E0] dark:text-blue-300 px-4 py-2 rounded-lg text-sm font-medium transition">
                            {{ svg('heroicon-s-document-text', 'w-4 h-4') }} Lihat File
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

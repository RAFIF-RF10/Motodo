@extends('Layout.app')

@section('content')
@push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <div
        class="max-w-3xl mx-auto mt-20 px-6 py-10 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-md transition-all">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">
            Buat Tugas untuk: <span class="text-blue-600">{{ $todoList->title }}</span>
        </h1>

        <form  data-turbo="false" action="{{ route('tasks.store', ['todolist' => $todoList->id]) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            <input type="hidden" name="todo_list_id" value="{{ $todoList->id }}">

            <div>
                <label class="flex items-center gap-2 font-semibold text-gray-700 dark:text-gray-200 mb-2">
                    @svg('heroicon-o-pencil-square', 'w-5 h-5 text-blue-600')
                    Judul <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title"
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 w-full text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('title') border-red-500 @enderror"
                    placeholder="Masukkan judul tugas..." required value="{{ old('title') }}">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 font-semibold text-gray-700 dark:text-gray-200 mb-2">
                    @svg('heroicon-o-chat-bubble-bottom-center-text', 'w-5 h-5 text-blue-600')
                    Deskripsi Singkat
                </label>
                <textarea name="description"
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 w-full text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('description') border-red-500 @enderror"
                    rows="3" placeholder="Tulis deskripsi singkat...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 font-semibold text-gray-700 dark:text-gray-200 mb-2">
                    @svg('heroicon-o-document-text', 'w-5 h-5 text-blue-600')
                    Deskripsi Lengkap
                </label>
                <textarea name="long_description"
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 w-full text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('long_description') border-red-500 @enderror"
                    rows="4" placeholder="Tulis detail tugas...">{{ old('long_description') }}</textarea>
                @error('long_description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 font-semibold text-gray-700 dark:text-gray-200 mb-2">
                    @svg('heroicon-o-calendar-days', 'w-5 h-5 text-blue-600')
                    Deadline <span class="text-red-500">*</span>
                </label>
                <input type="date" name="deadline"
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 w-full text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('deadline') border-red-500 @enderror"
                    required value="{{ old('deadline') }}">
                @error('deadline')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 font-semibold text-gray-700 dark:text-gray-200 mb-2">
                    @svg('heroicon-o-signal', 'w-5 h-5 text-blue-600')
                    Level Prioritas
                </label>
                <select name="priority_id"
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 w-full text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('priority_id') border-red-500 @enderror">
                    <option value="">-- Pilih Level --</option>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority->id }}" {{ old('priority_id') == $priority->id ? 'selected' : '' }}>
                            {{ $priority->name }}
                        </option>
                    @endforeach
                </select>
                @error('priority_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 font-semibold text-gray-700 dark:text-gray-200 mb-2">
                    @svg('heroicon-o-photo', 'w-5 h-5 text-blue-600')
                    Upload File / Gambar
                </label>
                <div class="relative group border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 transition"
                    onclick="document.getElementById('fileInput').click()">
                    <input id="fileInput" type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.svg,.zip"
                        class="hidden" onchange="previewFile(event)">
                    <div class="flex flex-col items-center justify-center space-y-2">
                        @svg('heroicon-o-cloud-arrow-up', 'w-10 h-10 text-gray-400 group-hover:text-blue-500 transition')
                        <p class="text-gray-500 dark:text-gray-400">Klik atau drag file ke sini untuk upload</p>
                    </div>

                    <div id="previewContainer" class="mt-4 hidden">
                        <img id="previewImage" src="" alt="Preview"
                            class="mx-auto max-h-48 rounded-lg shadow-md hidden">
                        <p id="fileName" class="text-sm text-gray-600 dark:text-gray-300"></p>
                    </div>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 font-semibold text-gray-700 dark:text-gray-200 mb-2">
                    @svg('heroicon-o-user-group', 'w-5 h-5 text-blue-600')
                    Jumlah Siswa yang Ditugaskan
                </label>
                <input type="number" name="assigned_user_count"
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 w-full text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('assigned_user_count') border-red-500 @enderror"
                    min="0" value="{{ old('assigned_user_count', 0) }}">
                @error('assigned_user_count')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-6">
                <a href="{{ route('todolist.show', $todoList->id) }}"
                    class="px-5 py-2.5 rounded-lg bg-gray-500 hover:bg-gray-600 text-white font-medium transition flex items-center gap-1">
                    @svg('heroicon-o-x-mark', 'w-5 h-5')
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition flex items-center gap-1">
                    @svg('heroicon-o-check-circle', 'w-5 h-5')
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        function previewFile(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            const fileName = document.getElementById('fileName');

            if (!file) {
                previewContainer.classList.add('hidden');
                return;
            }

            fileName.textContent = `📁 ${file.name}`;
            previewContainer.classList.remove('hidden');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.classList.add('hidden');
            }
        }
    </script>
@endpush

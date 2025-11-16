@extends('layout.app')

@section('content')
<section class="p-6 mt-20">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Tambah Task Baru</h1>
                <p class="text-gray-600 mt-1">Untuk list: <span class="font-semibold">{{ $todolist->title }}</span></p>
            </div>
            <a href="{{ route('todolists.show', $todolist->id) }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-lg transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Alert untuk error -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('tasks.store', $todolist->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Judul Task -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Judul Task <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('title') border-red-500 @enderror"
                           placeholder="Masukkan judul task"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="description"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-500 @enderror"
                              placeholder="Tambahkan deskripsi task (opsional)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row untuk Priority dan Deadline -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Prioritas -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Prioritas <span class="text-red-500">*</span>
                        </label>
                        <select name="priority"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('priority') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Prioritas</option>
                            <option class="bg-green-500" value="easy" {{ old('priority') == 'easy' ? 'selected' : '' }}>
                                Mudah
                            </option>
                            <option class="bg-orange-400" value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>
                                Sedang
                            </option>
                            <option class="bg-red-500" value="hard" {{ old('priority') == 'hard' ? 'selected' : '' }}>
                             Sulit
                            </option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Deadline
                        </label>
                        <input type="date"
                               name="deadline"
                               value="{{ old('deadline') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('deadline') border-red-500 @enderror">
                        @error('deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Attachment -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Lampiran
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs text-gray-500">JPG, PNG, PDF, DOCX, ZIP, RAR (Max. 2MB)</p>
                            </div>
                            <input type="file"
                                   name="attachment"
                                   class="hidden"
                                   accept=".jpg,.jpeg,.png,.pdf,.docx,.zip,.rar"
                                   onchange="displayFileName(this)">
                        </label>
                    </div>
                    <p id="file-name" class="mt-2 text-sm text-gray-600"></p>
                    @error('attachment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        💾 Simpan Task
                    </button>
                    <a href="{{ route('todolists.show', $todolist->id) }}"
                       class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-3 rounded-lg transition duration-200 text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function displayFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameDisplay = document.getElementById('file-name');
    if (fileName) {
        fileNameDisplay.textContent = `📎 File dipilih: ${fileName}`;
        fileNameDisplay.classList.add('text-blue-600', 'font-medium');
    } else {
        fileNameDisplay.textContent = '';
    }
}
   function showFlashMessage() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1800
                });
            @endif
        }

        document.addEventListener('turbo:load', showFlashMessage);
        document.addEventListener('DOMContentLoaded', showFlashMessage);

</script>
@endsection

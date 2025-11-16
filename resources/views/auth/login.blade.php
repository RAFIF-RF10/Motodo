<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/motodo.png') }}" class="rounded-2xl" type="image/png">
    <title>Motodo Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#F1F5F8] to-[#E5E7EB] flex justify-center items-center p-4">

    <div class="max-w-5xl w-full bg-white shadow-2xl rounded-3xl overflow-hidden flex flex-col lg:flex-row">

        <div class="lg:w-1/2 p-8 sm:p-12 flex flex-col justify-center">

            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('image/motodo.png') }}"
                         class="h-18 w-18 rounded-xl shadow-md object-cover"
                         alt="Motodo Logo">
                    <div>
                        <h1 class="text-2xl font-bold text-[#1C2B33]">Motodo</h1>
                        <p class="text-xs text-gray-500">Task Management</p>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-[#1C2B33] mb-2">
                    Selamat Datang
                </h2>
                <p class="text-gray-600">Masuk untuk mengelola dan memantau tugas Anda</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-600">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form class="space-y-5" method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl font-medium bg-[#F1F5F8] border-2 border-transparent text-gray-800 placeholder-gray-400 text-sm focus:outline-none focus:border-[#0082FB] focus:bg-white transition-all duration-300 @error('email') border-red-500 @enderror"
                            type="email"
                            placeholder="nama@email.com"
                            required
                            autofocus />
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            name="password"
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl font-medium bg-[#F1F5F8] border-2 border-transparent text-gray-800 placeholder-gray-400 text-sm focus:outline-none focus:border-[#0082FB] focus:bg-white transition-all duration-300 @error('password') border-red-500 @enderror"
                            type="password"
                            placeholder="••••••••"
                            required />
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-[#0082FB] bg-gray-100 border-gray-300 rounded focus:ring-[#0082FB] focus:ring-2">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm font-medium text-[#0082FB] hover:text-[#0064E0] transition-colors">
                        Lupa password?
                    </a>
                </div>

                <button
                    type="submit"
                    class="w-full py-4 rounded-xl font-semibold bg-gradient-to-r from-[#0082FB] to-[#0064E0] hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] text-white transition-all duration-300 ease-in-out flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk
                </button>

                <p class="text-xs text-center text-gray-500 leading-relaxed">
                    Dengan masuk, Anda menyetujui
                    <a href="#" class="text-[#0082FB] hover:underline font-medium">Kebijakan Privasi</a>
                    dan
                    <a href="#" class="text-[#0082FB] hover:underline font-medium">Ketentuan Layanan</a>
                    kami.
                </p>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register.form') }}" class="font-semibold text-[#0082FB] hover:text-[#0064E0] transition-colors">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>

        <div class="lg:w-1/2 bg-gradient-to-br from-[#0082FB] to-[#0064E0] relative overflow-hidden hidden lg:flex items-center justify-center p-12">
            <div class="absolute top-0 right-0 w-72 h-72 bg-white opacity-5 rounded-full -mr-36 -mt-36"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full -ml-48 -mb-48"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white opacity-5 rounded-full"></div>

            <div class="relative z-10 text-center text-white space-y-8">
                <div class="flex justify-center">
                    <img src="{{ asset('image/icon/data-analysis-animate.svg') }}"
                         class="w-80 h-80 drop-shadow-2xl"
                         alt="Task Management Illustration">
                </div>

                <div class="space-y-4">
                    <h3 class="text-3xl font-bold">Kelola Tugas dengan Mudah</h3>
                    <p class="text-white/90 text-lg max-w-md mx-auto">
                        Platform manajemen tugas modern untuk meningkatkan produktivitas belajar mengajar
                    </p>
                </div>
            </div>
        </div>
    </div>

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#0082FB',
        confirmButtonText: 'OK'
    });
</script>
@endif


</body>
</html>

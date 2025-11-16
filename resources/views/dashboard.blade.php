@extends('layout.app')

@section('content')
    <div class="p-6 md:p-10">
           <div
            class="relative flex items-center justify-between rounded-3xl overflow-hidden shadow-2xl border border-white/30
            bg-gradient-to-r from-[#0064E0] to-[#0082FB] text-white p-6 md:p-10 mb-10">

            {{-- Text Section --}}
            <div class="relative z-10 max-w-lg">
                {{-- Date/Time Tag --}}
                <div class="flex items-center gap-2 mb-3 text-sm font-medium bg-white/30 backdrop-blur-md px-3 py-1.5 rounded-full text-white/90 w-fit">
                    {{ svg('heroicon-o-calendar-days', 'w-5 h-5') }}
                    <span>{{ now()->format('d M Y, H:i') }}</span>
                </div>

                <h1 class="text-3xl md:text-5xl font-extrabold mb-2 tracking-tight">
                    Halo, {{ Auth::user()->name }}!
                </h1>
                <p class="text-lg opacity-90 font-light">
                    Anda login sebagai <b class="capitalize font-semibold">{{ Auth::user()->role->name }}</b>.
                </p>
            </div>

            <div class="absolute right-[-20px] bottom-[-10px] md:bottom-0 md:right-0 lg:right-10 opacity-100">
                @if (Auth::user()->role->name === 'Admin')
                    <img src="{{ asset('image/icon/teacher.svg') }}" alt="Admin Illustration"
                        class="w-[180px] md:w-[240px] lg:w-[280px] drop-shadow-[0_20px_25px_rgba(0,0,0,0.3)] transition-all duration-500 ease-out">
                @else
                    <img src="{{ asset('image/icon/student1.svg') }}" alt="Student Illustration"
                        class="w-[180px] md:w-[240px] lg:w-[280px] drop-shadow-[0_20px_25px_rgba(0,0,0,0.3)] transition-all duration-500 ease-out">
                @endif
            </div>

            <div class="absolute inset-0 bg-[url('/image/pattern.svg')] bg-cover opacity-5 mix-blend-overlay"></div>
        </div>




        {{-- ==== ADMIN ==== --}}
        @if (Auth::user()->role->name === 'Admin')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-[#1C2B33] rounded-2xl shadow p-6 hover:shadow-md transition">


                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Jumlah Siswa</h2>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $jumlahSiswa ?? 0 }}</p>
                        </div>
                        {{ svg('ri-user-3-line')->class('w-10 h-10 text-blue-600') }}
                    </div>
                </div>

                <div class="bg-white dark:bg-[#1C2B33] rounded-2xl shadow p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Total Tugas Diberikan</h2>
                            <p class="text-3xl font-bold text-yellow-500 mt-2">{{ $totalTugas ?? 0 }}</p>
                        </div>
                        {{ svg('ri-file-list-line')->class('w-10 h-10 text-yellow-500') }}
                    </div>
                </div>

                <div class="bg-white dark:bg-[#1C2B33] rounded-2xl shadow p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tugas Dikirim (Siswa)</h2>
                            <p class="text-3xl font-bold text-green-500 mt-2">{{ $tugasDikirim ?? 0 }}</p>
                        </div>
                        {{ svg('ri-mail-send-line')->class('w-10 h-10 text-green-500') }}
                    </div>
                </div>
            </div>

            {{-- === Chart Admin === --}}
            <div class="bg-white dark:bg-[#1C2B33] rounded-2xl shadow p-6 mt-10">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    Statistik Pengumpulan Tugas
                </h2>
                <div id="admin-chart" class="w-full"></div>

                <div class="flex flex-wrap gap-4 justify-center mt-6">
                    @php
                        $colors = ['#3B82F6', '#F59E0B', '#10B981', '#EF4444', '#8B5CF6', '#6366F1', '#14B8A6'];
                    @endphp
                    @foreach ($chartData as $index => $item)
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full"
                                style="background-color: {{ $colors[$index % count($colors)] }}"></span>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $item['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ==== USER ==== --}}
        @if (Auth::user()->role->name === 'User')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                <div class="bg-white dark:bg-[#1C2B33] rounded-2xl shadow p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tugas Selesai</h2>
                            <p class="text-3xl font-bold text-green-500 mt-2">{{ $tugasSelesai }}</p>
                        </div>
                        {{ svg('ri-check-line')->class('w-10 h-10 text-green-500') }}
                    </div>
                </div>

                <div class="bg-white dark:bg-[#1C2B33] rounded-2xl shadow p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Belum Dikerjakan</h2>
                            <p class="text-3xl font-bold text-red-500 mt-2">{{ $belumDikerjakan }}</p>
                        </div>
                        {{ svg('ri-time-line')->class('w-10 h-10 text-red-500') }}
                    </div>
                </div>

                <div class="bg-white dark:bg-[#1C2B33] rounded-2xl shadow p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Total Tugas</h2>
                            <p class="text-3xl font-bold text-blue-500 mt-2">{{ $totalTugas }}</p>
                        </div>
                        {{ svg('ri-task-line')->class('w-10 h-10 text-blue-500') }}
                    </div>
                </div>
            </div>

            {{-- === Chart User === --}}
            <div class="relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md mt-10">
                <div class="pt-6 px-2 pb-0">
                    <div id="user-chart"></div>
                </div>
            </div>

            <div class="mt-10 bg-white dark:bg-[#1C2B33] rounded-2xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Tugas Terbaru</h2>
                <table class="w-full text-left text-gray-700 dark:text-gray-300">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2">Judul</th>
                            <th class="py-2">Deadline</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tugasTerbaru as $tugas)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3">{{ $tugas->title }}</td>
                                <td>{{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}</td>
                                <td>
                                    @if ($tugas->status === 'Completed')
                                        <span class="text-green-500 font-semibold">Selesai</span>
                                    @elseif ($tugas->status === 'In Progress')
                                        <span class="text-yellow-500 font-semibold">Menunggu ACC Guru</span>
                                    @elseif ($tugas->status === 'Pending')
                                        <span class="text-red-500 font-semibold">Belum Dikerjakan</span>
                                    @else
                                        <span class="text-gray-400 font-semibold">Tidak Diketahui</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @if (Auth::user()->role->name === 'Admin')
        <script>
            const adminConfig = {
                series: @json($chartData),
                chart: {
                    type: "line",
                    height: 280,
                    toolbar: {
                        show: false
                    }
                },
                colors: ["#3B82F6", "#F59E0B", "#10B981", "#EF4444", "#8B5CF6", "#6366F1", "#14B8A6"],
                stroke: {
                    width: 3,
                    curve: "smooth"
                },
                xaxis: {
                    categories: @json($bulanLabels)
                },
                grid: {
                    borderColor: "#E5E7EB",
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: "dark"
                },
                legend: {
                    show: false
                },
            };
            new ApexCharts(document.querySelector("#admin-chart"), adminConfig).render();
        </script>
    @endif

    @if (Auth::user()->role->name === 'User')
        <script>
            const userConfig = {
                series: [{
                    name: "Tugas Selesai",
                    data: @json($chartData)
                }],
                chart: {
                    type: "line",
                    height: 240,
                    toolbar: {
                        show: false
                    }
                },
                colors: ["#2563eb"],
                stroke: {
                    curve: "smooth",
                    lineCap: "round"
                },
                xaxis: {
                    categories: @json($chartLabels)
                },
                grid: {
                    borderColor: "#dddddd",
                    strokeDashArray: 5
                },
                tooltip: {
                    theme: "dark"
                },
            };
            new ApexCharts(document.querySelector("#user-chart"), userConfig).render();
        </script>
    @endif
@endsection

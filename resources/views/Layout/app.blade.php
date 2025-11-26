<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  {{ $title ?? 'Motodo' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @turboScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @stack('head')
</head>

<body class="bg-gray-100 min-h-screen">
    @includeIf('Layout.navbar')

    @includeIf('Layout.sidebar')
    <div class="p-6 md:ml-64">
        @yield('content')
    </div>

    @stack('scripts')
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
</script>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .title { font-size: 22px; font-weight: bold; margin-bottom: 5px; }
        .section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; font-size: 12px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

    <div class="section">
        <div class="title">{{ $task->title }}</div>
        <div>{{ $task->description }}</div>
    </div>

    <div class="section">
        <b>Deadline:</b>
        {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : 'Tidak ada' }}<br>

        <b>Prioritas:</b> {{ $task->priority->name ?? 'Tidak ada' }}<br>
        <b>Status:</b> {{ $task->status->name ?? 'Belum' }}<br>
        <b>Jumlah Siswa:</b> {{ $task->detail->assigned_user_count ?? 0 }}
    </div>

    @if ($task->detail?->long_description)
        <div class="section">
            <b>Deskripsi Lengkap:</b><br>
            {!! nl2br(e($task->detail->long_description)) !!}
        </div>
    @endif

    <div class="section">
        <h3>Daftar Pengumpulan</h3>

        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($task->submissions as $sub)
                    <tr>
                        <td>{{ $sub->user->name }}</td>

                      @php
    $fullPath = asset('storage/' . $sub->file_path);
    $filename = basename($sub->file_path);
    $short = strlen($filename) > 15
        ? substr($filename, 0, 8) . '...' . substr($filename, -6)
        : $filename;
@endphp

<td>
    <a href="{{ $fullPath }}">{{ $short }}</a>
</td>

                        <td>
                            {{ $sub->status->name ?? 'Belum' }}
                        </td>

                        <td>{{ $sub->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>

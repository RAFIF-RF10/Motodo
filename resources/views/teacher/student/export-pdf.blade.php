<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengumpulan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #0082FB; color: white; }
        h2 { text-align: center; color: #0082FB; }
    </style>
</head>
<body>
    <h2>Laporan Pengumpulan Tugas: {{ $task->title }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Catatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($submissions as $i => $s)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $s->user->name ?? '-' }}</td>
                <td>{{ $s->notes ?? '-' }}</td>
                <td>{{ $s->status->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

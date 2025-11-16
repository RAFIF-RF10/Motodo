@extends('Layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Daftar Todo</h1>

    <a href="{{ route('todolists.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded">Buat Daftar Baru</a>

    @if(session('success'))
        <div class="mb-4 text-green-700">{{ session('success') }}</div>
    @endif

    @forelse($lists as $list)
        <div class="p-4 bg-white rounded shadow mb-3">
            <h2 class="text-xl font-semibold">{{ $list->title }}</h2>
            <p class="text-sm text-gray-600">{{ $list->description }}</p>
            <div class="mt-2">
                <a href="{{ route('todolists.show', $list->id) }}" class="text-blue-600">Lihat</a>
                |
                <a href="{{ route('todolists.edit', $list->id) }}" class="text-yellow-600">Edit</a>
            </div>
            @if($list->tasks->count())
                <ul class="mt-3">
                    @foreach($list->tasks as $task)
                        <li class="py-1">• <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600">{{ $task->title }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    @empty
        <p>Tidak ada daftar.</p>
    @endforelse
@endsection

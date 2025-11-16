@extends('Layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-2">{{ $list->title }}</h1>
    <p class="text-sm text-gray-600 mb-4">{{ $list->description }}</p>

    @if(session('success'))
        <div class="mb-4 text-green-700">{{ session('success') }}</div>
    @endif

    <h2 class="text-xl font-semibold mb-2">Tugas</h2>
    @if($list->tasks->count())
        <ul class="mb-4">
            @foreach($list->tasks as $task)
                <li class="py-2 border-b">
                    <a href="{{ route('tasks.show', ['todolist' => $list->id, 'task' => $task->id]) }}" class="text-blue-600 font-medium">{{ $task->title }}</a>
                    <div class="text-sm text-gray-600">{{ $task->description }}</div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="mb-4">Belum ada tugas.</p>
    @endif

    <h3 class="text-lg font-semibold mb-2">Tambah Tugas</h3>
    <form action="{{ route('tasks.store', $list->id) }}" method="POST" class="space-y-3">
        @csrf
        <input type="hidden" name="todo_list_id" value="{{ $list->id }}">
        <div>
            <label class="block">Judul</label>
            <input type="text" name="title" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div>
            <label class="block">Deskripsi</label>
            <textarea name="description" class="border rounded px-2 py-1 w-full"></textarea>
        </div>
        <div>
            <label class="block">Deadline</label>
            <input type="date" name="deadline" class="border rounded px-2 py-1">
        </div>
        <div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Tambah Tugas</button>
        </div>
    </form>
@endsection

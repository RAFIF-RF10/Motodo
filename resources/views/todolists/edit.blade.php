@extends('Layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Todo</h1>

    <form action="{{ route('todolists.update', $list->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block">Judul</label>
            <input type="text" name="title" class="border rounded px-2 py-1 w-full" value="{{ old('title', $list->title) }}">
            @error('title') <div class="text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block">Deskripsi</label>
            <textarea name="description" class="border rounded px-2 py-1 w-full">{{ old('description', $list->description) }}</textarea>
        </div>
        <div>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Update</button>
        </div>
    </form>
@endsection

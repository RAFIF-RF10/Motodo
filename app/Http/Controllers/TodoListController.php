<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\Task;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoListController extends Controller
{
    // Methods untuk Guru
    public function index()
    {
        $lists = TodoList::where('user_id', Auth::id())
            ->with(['tasks' => function ($query) {
                $query->withCount('submissions');
            }])
            ->get();
        return view('teacher.todolist.index', compact('lists'));
    }

    public function create()
    {
        return view('todolists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ]);

        $createData = $request->only(['title', 'description']);
        $createData['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('todolists', 'public');
            $createData['image'] = $imagePath;
        }

        $list = TodoList::create($createData);

        return redirect()
            ->route('todolist.index')
            ->with('success', 'Todo List berhasil dibuat.');
    }


    public function show($id)
    {
        $list = TodoList::with(['tasks' => function ($query) {
            $query->with(['priority', 'status', 'detail', 'submissions']);
        }])->findOrFail($id);

        foreach ($list->tasks as $task) {
            $totalSiswa = $task->detail?->assigned_user_count ?? 0;
            $sudahKumpul = $task->submissions->where('status_id', 3)->count();

            if ($totalSiswa > 0) {
                if ($sudahKumpul == 0 && $task->status_id != 1) {
                    $task->status_id = 1;
                    $task->save();
                } elseif ($sudahKumpul > 0 && $sudahKumpul < $totalSiswa && $task->status_id != 2) {
                    $task->status_id = 2;
                    $task->save();
                } elseif ($sudahKumpul >= $totalSiswa && $task->status_id != 3) {
                    $task->status_id = 3;
                    $task->save();
                }
            }
        }

        $list->load(['tasks.priority', 'tasks.status']);

        $tasks = $list->tasks;

        return view('teacher.todolist.show', compact('list', 'tasks'));
    }

    public function edit($id)
    {
        $list = TodoList::findOrFail($id);
        $this->authorize('update', $list);
        return view('todolists.edit', compact('list'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ]);

        $list = TodoList::findOrFail($id);
        $updateData = $request->only(['title', 'description']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('todolists', 'public');
            $updateData['image'] = $path;
        }


        $list->update($updateData);

        return redirect()->route('todolist.index')
            ->with('success', 'Todo List berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $list = TodoList::findOrFail($id);

        $list->delete();
        return redirect()->route('todolist.index')
            ->with('success', 'Todo List berhasil dihapus.');
    }

    // Methods untuk Siswa
    public function studentIndex()
    {
        $lists = TodoList::with(['tasks' => function ($query) {
            $query->withCount(['submissions' => function ($q) {
                $q->where('user_id', Auth::id());
            }]);
        }])->get();

        return view('student.todo.index', compact('lists'));
    }

    public function studentShow($id)
    {
        $list = TodoList::with(['tasks' => function ($query) {
            $query->with(['priority', 'status', 'detail'])
                ->with(['submissions' => function ($q) {
                    $q->where('user_id', Auth::id());
                }]);
        }])->findOrFail($id);

        $tasks = $list->tasks;

        return view('student.todo.show', compact('list', 'tasks'));
    }
}

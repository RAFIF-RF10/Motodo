<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use App\Models\Status;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index($todoListId)
    {
        $todoList = TodoList::findOrFail($todoListId);
        $tasks = $todoList->tasks()->withCount('submissions')->get();
        return view('tasks.index', compact('todoList', 'tasks'));
    }

    public function create($todoListId)
    {
        $todoList = TodoList::findOrFail($todoListId);
        $priorities = Priority::all();
        $statuses = Status::all();
        return view('tasks.create', compact('todoList', 'priorities', 'statuses'));
    }

    public function store(Request $request, $todoListId)
    {
        $todoList = TodoList::findOrFail($todoListId);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'deadline' => 'required|date|after:today',
            'priority_id' => 'nullable|exists:priorities,id',
            'status_id' => 'nullable|exists:statuses,id',
            'long_description' => 'nullable|string',
            'assigned_user_count' => 'nullable|integer|min:0',
        ]);

        $createData = $request->only([
            'title',
            'description',
            'deadline',
            'priority_id',
            'status_id'
        ]);

        $createData['user_id'] = Auth::id();
        $createData['todo_list_id'] = $todoListId;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tasks', 'public');
            $createData['image'] = $imagePath;
        }

        $task = Task::create($createData);

        $task->detail()->create([
            'long_description' => $request->long_description,
            'assigned_user_count' => $request->assigned_user_count ?? 0,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('tasks.show', ['todolist' => $todoListId, 'task' => $task->id])->with('success', 'Task berhasil dibuat.');
    }

    public function show($todoListId, $taskId)
    {
        $task = Task::where('todo_list_id', $todoListId)
            ->with(['submissions' => function ($q) {
                $q->with('user', 'status');
            }, 'detail', 'priority', 'status'])
            ->findOrFail($taskId);

        return view('tasks.show', compact('task'));
    }


    public function edit($todoListId, $taskId)
    {
        $task = Task::where('todo_list_id', $todoListId)->with('detail')->findOrFail($taskId);
        $priorities = Priority::all();
        $statuses = Status::all();
        return view('tasks.edit', compact('task', 'priorities', 'statuses'));
    }

    public function update(Request $request, $todoListId, $taskId)
    {
        $task = Task::where('todo_list_id', $todoListId)->findOrFail($taskId);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'image' => 'sometimes|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'deadline' => 'sometimes|date',
            'priority_id' => 'sometimes|exists:priorities,id',
            'status_id' => 'sometimes|exists:statuses,id',
            'long_description' => 'sometimes|string',
            'assigned_user_count' => 'sometimes|integer|min:0',
        ]);

        $updateData = $request->only([
            'title',
            'description',
            'deadline',
            'priority_id',
            'status_id'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tasks', 'public');
            $updateData['image'] = $imagePath;
        }

        $task->update($updateData);

        $task->detail()->update([
            'long_description' => $request->long_description,
            'assigned_user_count' => $request->assigned_user_count ?? 0,
        ]);

        return redirect()->route('tasks.show', ['todolist' => $todoListId, 'task' => $taskId])->with('success', 'Task berhasil diperbarui.');
    }
    public function export($todoListId, $taskId)
    {
        $task = Task::where('todo_list_id', $todoListId)
            ->where('id', $taskId)
            ->with(['detail', 'priority', 'status', 'submissions.user'])
            ->firstOrFail();

        return response()->json([
            'title' => $task->title,
            'description' => $task->description,
            'priority' => $task->priority->name ?? null,
            'status' => $task->status->name ?? null,
            'deadline' => $task->deadline,
            'detail' => $task->detail,
            'submissions' => $task->submissions,
        ]);
    }

    public function destroy($todoListId, $taskId)
    {
        $task = Task::where('todo_list_id', $todoListId)->findOrFail($taskId);
        $this->authorize('delete', $task);

        $task->delete();
        return redirect()->route('todolist.show', $todoListId)
            ->with('success', 'Task berhasil dihapus.');
    }

    public function studentShow($taskId)
    {
        $task = Task::with([
            'detail',
            'priority',
            'status',
            'submissions' => function ($query) {
                $query->where('user_id', Auth::id())
                    ->with('status')
                    ->latest();
            }
        ])->findOrFail($taskId);

        return view('student.task.show', compact('task'));
    }
    public function studentIndex(Request $request)
    {
        $query = Task::query()->with(['todoList', 'priority', 'status']);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        if ($date = $request->input('date')) {
            $query->whereDate('deadline', $date);
        }
        $sort = $request->input('sort', 'desc');
        $query->orderBy('created_at', $sort);

        $tasks = $query->paginate(10);

        return view('student.task.index', compact('tasks'));
    }
}


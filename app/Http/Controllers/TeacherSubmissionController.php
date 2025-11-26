<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Status;
use Illuminate\Http\Request;

class TeacherSubmissionController extends Controller
{
    public function index()
    {
        // Ambil semua submissions beserta relasi
        $submissions = Submission::with(['user', 'task.todoList', 'status'])
            ->latest()
            ->get();

        // Grouping: TodoList → Task → Submissions
        $groupedSubmissions = $submissions->groupBy(function ($item) {
            return $item->task->todoList->title ?? 'Tanpa Todo List';
        })->map(function ($todoListSubmissions) {
            return $todoListSubmissions->groupBy(function ($item) {
                return $item->task->title ?? 'Tanpa Judul Task';
            });
        });

        return view('teacher.student.index', compact('groupedSubmissions'));
    }

    public function updateStatus(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        $status = Status::where('name', $request->status)->first();

        if (!$status) {
            return redirect()->back()->with('error', 'Status tidak ditemukan!');
        }

        $submission->update(['status_id' => $status->id]);

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui!');
    }
    public function setStatusFromTask(Request $request, $id)
{
    $submission = Submission::findOrFail($id);

    $status = Status::where('name', $request->status)->first();

    if (!$status) {
        return back()->with('error', 'Status tidak ditemukan!');
    }

    $submission->update([
        'status_id' => $status->id
    ]);

    return back()->with('success', 'Status berhasil diperbarui!');
}


}

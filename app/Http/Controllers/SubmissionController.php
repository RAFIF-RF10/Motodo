<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Store a new submission (Student submits task)
     */
    public function store(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|file|max:50000|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png',
            'notes' => 'nullable|string|max:500',
        ]);

        $task = Task::findOrFail($taskId);
        $inProgressStatus = Status::firstOrCreate(['name' => 'In Progress']);

        try {
            $filePath = $request->file('file')->store('submissions', 'public');

            $submission = Submission::updateOrCreate(
                [
                    'task_id' => $taskId,
                    'user_id' => Auth::id(),
                ],
                [
                    'file_path' => $filePath,
                    'status_id' => $inProgressStatus->id,
                    'notes' => $request->input('notes'),
                    'submitted_at' => now(),
                ]
            );

            return redirect()->back()->with('success', 'Tugas berhasil dikumpulkan! Status: In Progress.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengumpulkan tugas: ' . $e->getMessage());
        }
    }

    /**
     * List semua submission milik siswa + tugas yang belum dikumpulkan
     */
    public function studentIndex()
    {
        $userId = Auth::id();

        $submissions = Submission::where('user_id', $userId)
            ->with(['task.todoList', 'status'])
            ->orderBy('created_at', 'desc')
            ->get();

        $allTasks = Task::with(['todoList', 'submissions' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->orderBy('created_at', 'desc')->get();

        $submittedTaskIds = $submissions->pluck('task_id')->toArray();
        $notSubmittedTasks = $allTasks->filter(function ($task) use ($submittedTaskIds) {
            return !in_array($task->id, $submittedTaskIds);
        });

        $totalTasks = $allTasks->count();
        $completedCount = $submissions->where('status.name', 'Completed')->count();
        $inProgressCount = $submissions->where('status.name', 'In Progress')->count();
        $notSubmittedCount = $notSubmittedTasks->count();

        return view('student.submission.index', compact(
            'submissions',
            'allTasks',
            'notSubmittedTasks',
            'totalTasks',
            'completedCount',
            'inProgressCount',
            'notSubmittedCount'
        ));
    }

    /**
     * Update submission (edit file & notes)
     */
    public function update(Request $request, $submissionId)
    {
        $submission = Submission::findOrFail($submissionId);

        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($submission->status->name === 'Completed') {
            return redirect()->back()->with('error', 'Tidak dapat mengedit submission yang sudah selesai!');
        }

        $request->validate([
            'file' => 'nullable|file|max:50000|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            if ($request->hasFile('file')) {
                if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                    Storage::disk('public')->delete($submission->file_path);
                }

                $submission->file_path = $request->file('file')->store('submissions', 'public');
            }

            $submission->notes = $request->input('notes');
            $submission->save();

            return redirect()->route('student.submissions.index')
                ->with('success', 'Pengumpulan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    /**
     * Detail submission milik siswa
     */
    public function studentShow(Submission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $submission->load(['task', 'status', 'user']);
        return view('student.submission.show', compact('submission'));
    }

    /**
     * Guru mengubah status submission
     */
    public function updateStatus(Request $request, $submissionId)
    {
        $request->validate([
            'status_id' => 'required|integer|exists:statuses,id',
        ]);

        $submission = Submission::findOrFail($submissionId);
        $submission->status_id = $request->status_id;
        $submission->save();

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui!');
    }
}

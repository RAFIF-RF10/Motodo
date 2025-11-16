<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $roleName = optional($user->role)->name;
        $title = 'Dashboard';

     if ($roleName === 'Admin') {
    $userRoleId = DB::table('roles')->where('name', 'User')->value('id');
    $jumlahSiswa = DB::table('users')->where('role_id', $userRoleId)->count();
    $tugasDikirim = DB::table('submissions')->count();
    $totalTugas = DB::table('tasks')->count();

    $statuses = DB::table('statuses')->pluck('id', 'name');

    $chartRaw = DB::table('submissions')
        ->selectRaw('MONTH(created_at) as bulan, status_id, COUNT(*) as total')
        ->groupBy('bulan', 'status_id')
        ->orderBy('bulan')
        ->get();

    $bulanLabels = collect(range(1, 12))->map(fn($b) => date('M', mktime(0, 0, 0, $b, 10)));

    $chartData = [];
    foreach ($statuses as $statusName => $statusId) {
        $data = [];
        foreach (range(1, 12) as $bulan) {
            $count = $chartRaw->where('status_id', $statusId)
                ->where('bulan', $bulan)
                ->first()
                ->total ?? 0;
            $data[] = $count;
        }
        $chartData[] = ['name' => $statusName, 'data' => $data];
    }

    return view('dashboard', compact(
        'title',
        'jumlahSiswa',
        'tugasDikirim',
        'totalTugas',
        'bulanLabels',
        'chartData'
    ));
}


        if ($roleName === 'User') {
            $totalTugas = DB::table('tasks')->count();
            $completedStatusId = DB::table('statuses')->where('name', 'Completed')->value('id');

            $tugasSelesai = DB::table('submissions')
                ->where('user_id', $user->id)
                ->where('status_id', $completedStatusId)
                ->count();

            $belumDikerjakan = $totalTugas - $tugasSelesai;

            $tugasTerbaru = DB::table('tasks')
                ->leftJoin('submissions', function ($join) use ($user) {
                    $join->on('tasks.id', '=', 'submissions.task_id')
                        ->where('submissions.user_id', '=', $user->id);
                })
                ->leftJoin('statuses', 'submissions.status_id', '=', 'statuses.id')
                ->select(
                    'tasks.title',
                    'tasks.deadline',
                    DB::raw("COALESCE(statuses.name, 'Belum Dikerjakan') as status")
                )
                ->orderByDesc('tasks.created_at')
                ->limit(3)
                ->get();

            $chart = DB::table('submissions')
                ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->where('user_id', $user->id)
                ->where('status_id', $completedStatusId)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $chartLabels = $chart->pluck('bulan')->map(fn($b) => date('M', mktime(0, 0, 0, $b, 10)));
            $chartData = $chart->pluck('total');

            return view('dashboard', compact(
                'title',
                'totalTugas',
                'tugasSelesai',
                'belumDikerjakan',
                'tugasTerbaru',
                'chartLabels',
                'chartData'
            ));
        }

        return redirect()->back()->with('error', 'Role tidak dikenali.');
    }
}


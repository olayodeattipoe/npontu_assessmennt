<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskUpdate;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(){
        $tasks = Task::with(['creator', 'updater'])->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'comment' => 'nullable|string',
        ]);

        try {
            $task = Task::create([
                'name' => $request->name,
                'status' => 'New',
                'comment' => $request->comment,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Log the initial creation as an update
            TaskUpdate::create([
                'task_id' => $task->id,
                'status' => 'New',
                'remark' => 'Activity created' . ($request->comment ? ': ' . $request->comment : ''),
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('tasks.index')
                ->with('success', $request->name . ' task created successfully!');

        } catch (\Exception $e) {
            return redirect()->route('tasks.index')
                ->with('error', 'Something went wrong. Task could not be created.');
        }
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:New,Active,Completed',
            'remark' => 'nullable|string|max:1000',
        ]);

        $task->update([
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        // Log the status change
        TaskUpdate::create([
            'task_id' => $task->id,
            'status' => $request->status,
            'remark' => $request->remark ?? 'Status changed to ' . $request->status,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function handover(Request $request)
    {
        $date = $request->query('date', Carbon::today()->toDateString());

        $updates = TaskUpdate::with(['task', 'user'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($update) {
                return $update->user->name ?? 'Unknown';
            });

        return view('tasks.handover', compact('updates', 'date'));
    }

    public function reports(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->subDays(7)->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());

        $updates = TaskUpdate::with(['task', 'user'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by date then by user
        $groupedByDate = $updates->groupBy(function ($update) {
            return $update->created_at->toDateString();
        });

        return view('tasks.reports', compact('updates', 'groupedByDate', 'startDate', 'endDate'));
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $show = request()->query('show', 'open');
        $taskStatus = $this->getTaskStatus($show);

        $tasks = auth()->user()->tasks()->where('status', $taskStatus)->get();
        return view('app.tasks.index', compact('tasks', 'show'));
    }

    private function getTaskStatus($show)
    {
        switch ($show) {
            case 'open':
                return TaskStatus::Open;
            case 'closed':
                return TaskStatus::Closed;
            case 'hold':
                return TaskStatus::Hold;
            default:
                return TaskStatus::Open;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        Task::create([
            'name' => request('name'),
            'description' => request('description'),
            'owner_id' => auth()->id(),
        ]);

        return redirect(route('tasks.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('app.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('app.tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $attrs = $request->validate([
            'name' => 'string',
            'description' => 'string'
        ]);

        $task->update($attrs);
        return redirect(route('tasks.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect(route('tasks.index'));
    }
}

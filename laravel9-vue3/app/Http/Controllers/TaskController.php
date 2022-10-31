<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\Collection;

class TaskController extends Controller
{
    public function index(): Collection
    {
        return Task::all();
    }

    public function show(Task $task): Task
    {
        return $task;
    }

    public function store(Request $request): Request
    {
        return Task::create($request->all());
    }

    public function update(Request $request, Task $task): Task
    {
        $task->update($request->all());

        return $task;
    }
}

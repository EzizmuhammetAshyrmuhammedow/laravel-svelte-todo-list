<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return auth()->user()->tasks;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task_name' => 'required|string|max:255',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task = new Task();
        $task->user_id = auth()->id();
        $task->task_name = $validatedData['task_name'];
        $task->due_date = $validatedData['due_date'];
        $task->priority = $validatedData['priority'];
        $task->status = $validatedData['status'];
        $task->save();

        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Check if the user owns the task
        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the incoming data
        $validatedData = $request->validate([
            'task_name' => 'string|max:255',
            'due_date' => 'date',
            'priority' => 'in:low,medium,high',
            'status' => 'in:pending,in progress,completed',
        ]);

        // Update the task with the validated data

$task->task_name = $validatedData['task_name'];
$task->due_date = $validatedData['due_date'];
$task->priority = $validatedData['priority'];
$task->status = $validatedData['status'];
$task->save();


        return response()->json(['message' => 'Task updated successfully', 'task' => $task]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return response()->json([
            'tasks' => $tasks
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required'
        ]);
        $task = Task::create([
            'name' => request('name'),
            'description' => request('description'),
            'user_id' => Auth::id()
        ]);
        return response()->json([
            'task' => $task,
            'message' => 'Success'
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Task $task
     * @return JsonResponse
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required'
        ]);
        $task->name = request('name');
        $task->description = request('description');
        $task->save();
        return response()->json([
            'message' => 'Task updated successfully!'
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Task $task
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return \response()->json([
            'message' => 'Task deleted successfully!'
        ], Response::HTTP_NO_CONTENT);
    }
}

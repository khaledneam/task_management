<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(){
        return view('tasks.index');
    }
    public function add(){
        return view('tasks.add');
    }


    public function indexVue(Request $request){

        $search = $request->query('search');
        if(isset($search)){
            $tasks = Task::with('user')
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%');
                })
                ->paginate(5);
        }else{
            $tasks = Task::with('user')->paginate(5);
        }

        return response()->json($tasks);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        if (empty($validatedData['description'])){
            $validatedData['description'] = null;
        }

        // Create a new task using the validated data
        $task = Task::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'user_id' => $validatedData['user_id'],
        ]);

        // Return a response indicating success
        return response()->json(['status'=>true,'message' => 'Task created successfully'], 201);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete(); // Soft delete the task

        return response()->json(['message' => 'Task deleted successfully','status'=>true]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('id');
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->update($data);

        return response()->json(['status'=>true,'message' => 'Task updated successfully']);
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Task;

class TasksController extends Controller
{
    public function index()
    {   
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            $data += $this->counts($user);
            return view('tasks.index', $data);
        }else {
            return view('welcome');
        }
    }
    
    public function show($id)
    {
        $task = Task::find($id);
        
        if (\Auth::user()->id === $task->user_id) {

        return view("tasks.show", [
            "task" => $task,
        ]);
        }else {
            return redirect("/");
        }
    }
    
    public function create()
    {
        $task = new Task;

        return view("tasks.create", [
            "task" => $task,
        ]);
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            "content" => "required|max:191",
            "status" => "required|max:10",
        ]);
            
        $task = new Task;
        $task->user_id = $request->user()->id;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

        return redirect("/");
    }
    
    public function edit($id)
    {
        $task = Task::find($id);
        
        if (\Auth::user()->id === $task->user_id) {

        return view("tasks.edit", [
            "task" => $task,
        ]);
        }else {
            return redirect("/");
        }
    }
    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "content" => "required|max:191",
            "status" => "required|max:10",
        ]);
        
        $task = Task::find($id);
        $task->user_id = $request->user()->id;;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

        return redirect("/");
    }
    
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return redirect("/");
    }
}
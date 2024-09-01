<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class TodoController extends Controller
{
    public function index()
    {
        return view('todos.index');
    }
    public function getTodoList(){
        $todos = Todo::all();
        $data       =   '';
        $count=1;if(!empty($todos)){foreach($todos as $row){
            if($row->status==1){
                $taskStatus     =   'Completed';
            }else{
                $taskStatus     =   'Non completed';
            }
            $data.='<tr class="row-'.$row->id.'">
                <td>'.$count++.'</td>
                <td>'.$row->task.'</td>
                <td>'.$taskStatus.'</td>
                <td>
                    <button class="btn btn-success btm-sm" onclick="changeStatus('.$row->id.')"><i class="fa fa-edit"></i></button> | 
                    <button class="btn btn-danger btm-sm" onclick="deleteStatus('.$row->id.')"><i class="fa fa-trash"></i></button>
                </td>
            </tr>';
        } } 

        return response()->json(['status'=>'true','todoList'=>$data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:255|unique:todos,task',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'errors' => $validator->errors()
            ]);
        }

        $todo = Todo::create([
            'task' => $request->task,
            'status'=>0
        ]);
        if($todo){
            return response()->json(['status' => 'true','message'=>'Todo created successfully']);
        }else{
            return response()->json(['status'=>'false','message'=>'Something went wrong, try again or later']);
        }
    }

    public function update(Request $request, $id)
    {
        $task = Todo::find($id);
        if (!$task) {
            return response()->json(['status'=>'false','message' => 'Task not found']);
        }
        $task->status = 1;
        $update     =   $task->save();
        if($update){
            return response()->json(['status'=>'true','message'=>'Task Status Changed']);
        }else{
             return response()->json(['status'=>'false','message'=>'Something went wrong, try again or later']);
        }
    }

    public function destroy($id)
    {
        $todo       =   Todo::find($id);
        $delete     =   $todo->delete();
        if($delete){
            return response()->json(['status' => 'true','message'=>'Todo deleted successfully']);
        }else{
            return response()->json(['status' => 'false','message'=>'Something went wrong, try again or later']);
        }
    }
}

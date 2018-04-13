<?php

namespace App\Controllers;
use Engine\Request\Request;
use App\Model\Tasks;
use App\Model\TasksManage;
class TaskController
{
    public function index(Request $request)
    {
        $params = $request->getAll();
        if($params['page'])
        {
            $page = $request->get('page');
        }else{
            $page = 1;
        }

        $tasks = TasksManage::with('task')->orderBy('created_at','desc')->paginate(10,['*'],null,$page);

        return view('tasks',['tasks' => $tasks,'currentPage' => $page,'lastPage' => $tasks->lastPage()]);
    }

    public function create(Request $request)
    {

        if($request->method('POST'))
        {
            $task = Tasks::create($request->getAll());
            $response = ['success' => true,'data' => $task];
            echo json_encode($response);
        }else{
            $tasks = Tasks::all();
            return view('create',['tasks' => $tasks]);
        }

    }

    public function delete(Request $request)
    {
        $data = $request->getAll();
        $task = Tasks::find($data['id']);

        if($task)
        {
            $task->delete();
            echo 1;
        }else{
            echo 0;
        }
    }

    public function deleteManage(Request $request)
    {
        $data = $request->getAll();
        $task = TasksManage::find($data['id']);

        if($task)
        {
            $task->delete();
            echo 1;
        }else{
            echo 0;
        }
    }

    public function createManage(Request $request)
    {
        $data = $request->getAll();
        $task = TasksManage::create($data);
        $response = ['success' => true,'data' => $task];
        echo json_encode($response);
    }

    public function updateManage(Request $request)
    {
        $task = TasksManage::find($request->get('id'));

        if($task){
            $task->update([
                'time' => $request->get('time')
            ]);

            echo json_encode(['success' => true]);
        }else{
            echo json_encode(['success' => false]);
        }
    }
}